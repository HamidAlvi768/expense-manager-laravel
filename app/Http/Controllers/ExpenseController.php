<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Expense;
use App\Models\DdExpenseCategory;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\GenericExport;
use App\Models\Event;
use App\Models\ExpenseCategoryThreshold;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Carbon;


class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->export) {
            return $this->doExport($request);
        }

        $expenseQuery = $this->filter($request)->with(['account', 'expenseCategory']);

        // Sum the amounts for the filtered income items
        $total = $expenseQuery->sum('amount');

        // Paginate the income items
        $expenses = $expenseQuery->orderBy('expense_date', 'desc')->paginate(10);
        $expenseCategories = DdExpenseCategory::where('status', '1')->get(); // Fetch categories
        // Get only the categories used in the current page of incomes
        $usedCategoryIds = $expenses->pluck('expense_category_id')->unique();
        $usedCategories = DdExpenseCategory::whereIn('id', $usedCategoryIds)->get();

        // Generate colors only for the used categories
        $categoryColors = getCategoryColors($usedCategories);

        $accounts = Account::where('user_id', auth()->id())->get();

        return view('expense.index', compact('expenses', 'total', 'expenseCategories', 'accounts', 'categoryColors', 'usedCategories'));
    }

    public function doExport(Request $request)
    {
        // Fetch filtered incomes with eager-loaded relationships
        $expenses = $this->filter($request)->with(['account', 'expenseCategory'])->get();

        // Prepare data for export
        $data = $expenses->map(function ($expense) {
            return [
                'ID' => $expense->id,
                'Account' => $expense->account->account_title ?? "-",
                'Category' => $expense->expenseCategory->title ?? "-",
                'Description' => $expense->description ?? "-",
                'Amount' => $expense->amount,
                'Date' => $expense->expense_date ?? "-",
            ];
        })->toArray();

        // Define headers for the export
        $headers = ['ID', 'Account', 'Category', 'Description', 'Amount', 'Date'];

        return Excel::download(new GenericExport($data, $headers), 'expenses.xlsx');
    }
    private function filter(Request $request)
    {
        $query = Expense::where('user_id', auth()->id());

        // Filter by account
        if ($request->account_id) {
            $query->where('account_id', $request->account_id);
        }

        // Filter by expense category
        if ($request->expense_category_id) {
            $query->where('expense_category_id', $request->expense_category_id);
        }

        // Filter by amount (partial match)
        if ($request->amount) {
            $query->where('amount', 'like', '%' . $request->amount . '%');
        }

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            // If both start_date and end_date are provided
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        } elseif ($request->start_date) {
            // If only start_date is provided
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->whereDate('expense_date', $startDate);
        } elseif ($request->end_date) {
            // If only end_date is provided, use today's date as the start_date
            $endDate = Carbon::parse($request->end_date)->startOfDay();
            $query->whereDate('expense_date', $endDate);
        }

        return $query;
    }


    public function create()
    {
        $expenseCategories = DdExpenseCategory::where('status', '1')->select('id', 'title')->orderBy('title', 'asc')->get();
        // $accounts = Account::where('user_id', auth()->id())->select('id', 'account_title')->orderBy('id', 'desc')->get();
        $accounts = Account::where('user_id', auth()->id())
            ->select('id', 'account_title')
            ->orderBy('account_title', 'asc')
            ->get();

        // Fetch user's thresholds
        $userId = auth()->id();
        $thresholds = ExpenseCategoryThreshold::where('user_id', $userId)
            ->orderBy('expense_category_id', 'asc')->get()->keyBy('expense_category_id')->sortKeys()
            ->values();
        return view('expense.create', compact('expenseCategories', 'accounts', 'thresholds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */





    public function store(Request $request)
    {
        $this->validation($request);

        $itemsData = $request->input('expense_items', []); // Array of income records
        $totalAmount = 0; // Initialize total amount for all incomes
        $userId = auth()->id();
        $account = Account::findOrFail($request->account_id);
        $expenseDate = $request->expense_date;
        DB::transaction(function () use ($itemsData, $userId, &$totalAmount, $expenseDate, $account) {
            // Validate account existence once (outside the loop)

            foreach ($itemsData as $itemData) {
                $itemAmount = $itemData['amount'];
                $totalAmount += $itemAmount; // Increment the total amount for all incomes

                // Create an Income record
                $expense = Expense::create([
                    'user_id' => $userId,
                    'account_id' => $account->id,
                    'income_date' => $expenseDate,
                    'amount' => $itemAmount,
                    'expense_category_id' => $itemData['expense_category_id'] ?? '',
                    'description' => $itemData['description'] ?? '',
                    'reason' => $itemData['reason'] ?? null, // Add reason field
                    'created_by' => $userId,
                ]);

                // Create a Transaction record
                Transaction::create([
                    'account_id' => $account->id,
                    'reference_id' => $expense->id, // Reference the Income record
                    'reference_type' => Expense::class, // Polymorphic relation
                    'transaction_type' => 'expense',
                    'amount' => $itemAmount,
                    'description' => $itemData['description'] ?? '',
                    'created_by' => $userId,
                ]);
            }

            // Update the account balances after all incomes are created
            $account->increment('withdrawal', $totalAmount);
            $account->decrement('balance', $totalAmount);
            $account->decrement('total', $totalAmount);
        });

        return redirect()->route('expenses.index')
            ->with('success', trans('Expense added successfully, Total Amount: :total', ['total' => $totalAmount]));
    }

    public function markImportant($id)
    {
        $expense = Expense::findOrFail($id);

        // Check if a threshold already exists for this user and category
        $threshold = ExpenseCategoryThreshold::where('user_id', auth()->id())
            ->where('expense_category_id', $expense->expense_category_id)
            ->first();

        if ($threshold) {
            // Optionally, you can update the existing threshold if needed
            // $threshold->threshold_amount = $expense->amount; // Uncomment if you want to update the threshold amount
            // $threshold->save();
            return redirect()->back()->with('error', 'This expense is already marked as important.');
        }

        // Create a new threshold record
        ExpenseCategoryThreshold::create([
            'user_id' => auth()->id(),
            'expense_category_id' => $expense->expense_category_id,
            'threshold_amount' => $expense->amount,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Expense marked as important successfully.');
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\Models\income  $income
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        return view('expense.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $expenseCategories = DdExpenseCategory::where('status', '1')->select('id', 'title')->orderBy('id', 'desc')->get();

        return view('expense.edit', compact('expense', 'expenseCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            // Parent income fields
            'account_id' => 'required|exists:accounts,id',
            'expense_date' => 'required|date',
            'expense_category_id' => 'required|exists:dd_expense_categories,id',
            'amount' => 'required|numeric|min:0.01', // Amount must be a positive number
            'description' => 'nullable|string',
        ]);

        // Retrieve the updated data from the request
        $data = $request->only(['account_id', 'expense_category_id', 'expense_date', 'amount', 'description']);
        $newAmount = $data['amount'];
        $oldAmount = $expense->amount;
        $difference = $newAmount - $oldAmount; // Calculate the difference

        // Retrieve the associated account
        $account = Account::findOrFail($data['account_id']);

        DB::transaction(function () use ($expense, $data, $difference, $account) {
            // Update the income record
            $data['updated_by'] = auth()->id();
            $expense->update($data);

            // Update the transaction linked to this income
            $transaction = Transaction::where('reference_id', $expense->id)
                ->where('transaction_type', 'expense')
                ->where('reference_type', Expense::class)
                ->firstOrFail();
            $transaction->update([
                'account_id' => $data['account_id'],
                'amount' => $data['amount'],
                'description' => $data['description'] ?? '',
                'updated_by' => auth()->id(),
            ]);

            // Adjust the account balances based on the difference
            $account->increment('withdrawal', $difference);
            $account->decrement('balance', $difference);
            $account->decrement('total', $difference);
        });

        return redirect()->route('expenses.edit', $expense->id)
            ->with('success', trans('Expense updated successfully'));
    }






    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        // Check if the expense is part of a bank statement
        if ($expense->creation_mode === 'statement') {
            return redirect()->route('expenses.index')
                ->with('error', trans('Cannot delete expense as it is part of a bank statement.'));
        }

        // If deletion is allowed, revert account balances
        $account = $expense->account;

        // The amount to be reverted
        $amount = $expense->amount;

        // Decrement withdrawal (as the expense decreases the available funds)
        $account->decrement('withdrawal', $amount);  // Decrease withdrawal
        // Increment balance and total (as the funds are being returned)
        $account->increment('balance', $amount);     // Increase balance
        $account->increment('total', $amount);       // Increase total

        Transaction::where('reference_id', $expense->id)
            ->where('reference_type', Expense::class)
            ->where('transaction_type', 'expense')
            ->delete();

        // Delete the expense record
        $expense->delete();

        // Redirect back with success message
        return redirect()->route('expenses.index')
            ->with('success', trans('Expense deleted successfully.'));
    }


    private function validation(Request $request, $id = 0)
    {

        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'expense_date' => 'required|date',
            // Income items validation
            'expense_items' => 'required|array|min:1', // Ensure at least one income item is provided
            'expense_items.*.expense_category_id' => 'required|exists:dd_expense_categories,id',
            'expense_items.*.amount' => 'required|numeric|min:0.01', // Amount must be a positive number
            'expense_items.*.description' => 'nullable|string',
        ]);
    }
}

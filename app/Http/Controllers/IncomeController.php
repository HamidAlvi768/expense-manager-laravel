<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\DdIncomeCategory;
use App\Exports\GenericExport;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Event;
use Maatwebsite\Excel\Facades\Excel;
class IncomeController extends Controller
{

    public function index(Request $request)
    {
        if ($request->export) {
            return $this->doExport($request);
        }
    
        $incomeQuery = $this->filter($request)->with(['account', 'incomeCategory']);
    
        // Sum the amounts for the filtered income items
        $total = $incomeQuery->sum('amount');
    
        // Paginate the income items
        $incomes = $incomeQuery->orderBy('income_date', 'desc')->paginate(10);
        $incomeCategories = DdIncomeCategory::where('status','1')->get(); // Fetch categories
        // Get only the categories used in the current page of incomes
        $usedCategoryIds = $incomes->pluck('income_category_id')->unique();
        $usedCategories = DdIncomeCategory::whereIn('id', $usedCategoryIds)->get();

        // Generate colors only for the used categories
        $categoryColors = getCategoryColors($usedCategories);
        $accounts = Account::where('user_id', auth()->id())->get();

        return view('income.index', compact('incomes', 'total', 'incomeCategories','accounts','categoryColors','usedCategories'));
    }

    public function doExport(Request $request)
    {
        // Fetch filtered incomes with eager-loaded relationships
        $incomes = $this->filter($request)->with(['account', 'incomeCategory'])->get();
    
        // Prepare data for export
        $data = $incomes->map(function ($income) {
            return [
                'ID' => $income->id,
                'Account' => $income->account->account_title ?? "-",
                'Category' => $income->incomeCategory->title ?? "-",
                'Description' => $income->description ?? "-",
                'Amount' => $income->amount,
                'Date' => $income->income_date ?? "-",
            ];
        })->toArray();
    
        // Define headers for the export
        $headers = ['ID', 'Account', 'Category', 'Description', 'Amount', 'Date'];
    
        return Excel::download(new GenericExport($data, $headers), 'incomes.xlsx');
    }
    

    private function filter(Request $request)
    {
        $query = Income::where('user_id', auth()->id());
    
        // Filter by account
        if ($request->account_id) {
            $query->where('account_id', $request->account_id);
        }
    
        // Filter by income category
        if ($request->income_category_id) {
            $query->where('income_category_id', $request->income_category_id);
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
            $query->whereBetween('income_date', [$startDate, $endDate]);
        } elseif ($request->start_date) {
            // If only start_date is provided
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->whereDate('income_date', $startDate);
        } elseif ($request->end_date) {
            // If only end_date is provided, use today's date as the start_date
            $endDate = Carbon::parse($request->end_date)->startOfDay();
            $query->whereDate('income_date', $endDate);
        }


    
        return $query;
    }
    
    
    
    
    public function create()
    {
        $incomeCategories = DdIncomeCategory::where('status','1')->select('id', 'title')->orderBy('id', 'desc')->get();
        $accounts = Account::where('user_id', auth()->id())->select('id', 'account_title')->orderBy('id', 'desc')->get();
        $users = User::select('id', 'name')->orderBy('id', 'desc')->get();
        return view('income.create', compact('incomeCategories','users','accounts'));
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
        
        $itemsData = $request->input('income_items', []); // Array of income records
        $totalAmount = 0; // Initialize total amount for all incomes
        $userId = auth()->id();
        $account = Account::findOrFail($request->account_id);
        $incomeDate = $request->income_date;
            DB::transaction(function () use ($itemsData, $userId, &$totalAmount, $incomeDate,$account) {
                // Validate account existence once (outside the loop)
        
                foreach ($itemsData as $itemData) {
                    $itemAmount = $itemData['amount'];
                    $totalAmount += $itemAmount; // Increment the total amount for all incomes
        
                    // Create an Income record
                    $income = Income::create([
                        'user_id' => $userId,
                        'account_id' => $account->id,
                        'income_date' => $incomeDate,
                        'amount' => $itemAmount,
                        'income_category_id' => $itemData['income_category_id'] ?? '',
                        'description' => $itemData['description'] ?? '',
                        'created_by' => $userId,
                    ]);
        
                    // Create a Transaction record
                    Transaction::create([
                        'account_id' => $account->id,
                        'reference_id' => $income->id, // Reference the Income record
                        'reference_type' => Income::class, // Polymorphic relation
                        'transaction_type' => 'income',
                        'amount' => $itemAmount,
                        'description' => $itemData['description'] ?? '',
                        'created_by' => $userId,
                    ]);
                }
        
                // Update the account balances after all incomes are created
                $account->increment('deposit', $totalAmount);
                $account->increment('balance', $totalAmount);
                $account->increment('total', $totalAmount);
        });

        return redirect()->route('incomes.index')
            ->with('success', trans('Incomes added successfully, Total Amount: :total', ['total' => $totalAmount]));
    }
     
     
     
     

     


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\income  $income
     * @return \Illuminate\Http\Response
     */
    public function show(income $income)
    {
        return view('income.show', compact('income'));    
    }

    public function edit(income $income)
    {
        $incomeCategories = DdIncomeCategory::where('status','1')->select('id', 'title')->orderBy('id', 'desc')->get();
        $users = User::select('id', 'name')->orderBy('id', 'desc')->get();
        return view('income.edit', compact('income', 'incomeCategories','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        $request->validate([
            // Parent income fields
            'account_id' => 'required|exists:accounts,id',
            'income_date' => 'required|date',
            'income_category_id' => 'required|exists:dd_income_categories,id',
            'amount' => 'required|numeric|min:0.01', // Amount must be a positive number
            'description' => 'nullable|string',
        ]);

        // Retrieve the updated data from the request
        $data = $request->only(['account_id','income_category_id', 'income_date', 'amount', 'description']);
        $newAmount = $data['amount'];
        $oldAmount = $income->amount;
        $difference = $newAmount - $oldAmount; // Calculate the difference
    
        // Retrieve the associated account
        $account = Account::findOrFail($data['account_id']);
        
        DB::transaction(function () use ($income, $data, $difference, $account) {
            // Update the income record
            $data['updated_by'] = auth()->id();
            $income->update($data);
    
            // Update the transaction linked to this income
            $transaction = Transaction::where('reference_id', $income->id)
                ->where('transaction_type', 'income')
                ->where('reference_type', Income::class)
                ->firstOrFail();
            $transaction->update([
                'account_id' => $data['account_id'],
                'amount' => $data['amount'],
                'description' => $data['description'] ?? '',
                'updated_by' => auth()->id(),
            ]);
    
            // Adjust the account balances based on the difference
            $account->increment('deposit', $difference);
            $account->increment('balance', $difference);
            $account->increment('total', $difference);
        });
    
        return redirect()->route('incomes.edit', $income->id)
            ->with('success', trans('Income updated successfully'));
    }
    
    
    
    
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        // Check if the income is part of a bank statement
        if ($income->creation_mode === 'statement') {
            // Prevent deletion if it's part of a bank statement
            return redirect()->route('incomes.index')
                ->with('error', trans('Cannot delete income as it is part of a bank statement.'));
        }

        // If deletion is allowed, revert account balances
        $account = $income->account;
        
        // Decrement account fields (deposit, balance, total) by the income amount
        $amount = $income->amount;
        
        $account->decrement('deposit', $amount); // Decrease deposit
        $account->decrement('balance', $amount); // Decrease balance
        $account->decrement('total', $amount);   // Decrease total

        Transaction::where('reference_id', $income->id)
        ->where('transaction_type', 'income')
        ->where('reference_type', Income::class)
        ->delete();
        
        // Delete the income record
        $income->delete();
    
        // Redirect back with success message
        return redirect()->route('incomes.index')
            ->with('success', trans('Income deleted successfully.'));
    }
    

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            // Parent income fields
            'account_id' => 'required|exists:accounts,id',
            'income_date' => 'required|date',
    
            // Income items validation
            'income_items' => 'required|array|min:1', // Ensure at least one income item is provided
            'income_items.*.income_category_id' => 'required|exists:dd_income_categories,id',
            'income_items.*.amount' => 'required|numeric|min:0.01', // Amount must be a positive number
            'income_items.*.description' => 'nullable|string',
        ]);
    }
    

}
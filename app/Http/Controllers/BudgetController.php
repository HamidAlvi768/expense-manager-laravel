<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;
use App\Models\DdExpenseCategory;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->export) {
            return $this->doExport($request);
        }

        $budgetQuery = $this->filter($request)->with([ 'expenseCategory']);
    
        // Sum the amounts for the filtered budget records
        $total = $budgetQuery->sum('amount');
    
        // Paginate the budget records
        $budgets = $budgetQuery->orderBy('month', 'desc')->paginate(10);
        $expenseCategories = DdExpenseCategory::all(); // Fetch categories
        $accounts = Account::where('user_id', auth()->id())->get();

        return view('budget.index', compact('budgets', 'total', 'expenseCategories', 'accounts'));
    }

    public function doExport(Request $request)
    {
        // Fetch filtered budgets with eager-loaded relationships
        $budgets = $this->filter($request)->with([ 'expenseCategory'])->get();
    
        // Prepare data for export
        $data = $budgets->map(function ($budget) {
            return [
                'ID' => $budget->id,
                'Category' => $budget->expenseCategory->title ?? "-",
                'Description' => $budget->description ?? "-",
                'Amount' => $budget->amount,
                'Month' => $budget->month ?? "-",
            ];
        })->toArray();
    
        // Define headers for the export
        $headers = ['ID', 'Category', 'Description', 'Amount', 'Month'];
    
        return Excel::download(new GenericExport($data, $headers), 'budgets.xlsx');
    }

    private function filter(Request $request)
    {
        $query = Budget::where('user_id', auth()->id());
    
        // Filter by expense category
        if ($request->expense_category_id) {
            $query->where('expense_category_id', $request->expense_category_id);
        }

    
        // Filter by month
        if ($request->month) {
            $query->where('month', $request->month);
        }

        // Filter by Date Range
        if ($request->start_date && $request->end_date) {
            // If both start_date and end_date are provided
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($request->start_date) {
            // If only start_date is provided
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->whereDate('created_at', $startDate);
        } elseif ($request->end_date) {
            // If only end_date is provided
            $endDate = Carbon::parse($request->end_date)->startOfDay();
            $query->whereDate('created_at', $endDate);
        }

        return $query;
    }

    public function create()
    {
        $expenseCategories = DdExpenseCategory::select('id', 'title')->orderBy('id', 'desc')->get();
        return view('budget.create', compact('expenseCategories'));
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
        $itemsData = $request->input('budget_items', []); // Array of budget records
        $totalAmount = 0; // Initialize total amount for all budgets
        $userId = auth()->id();
        $month = $request->month;

        DB::transaction(function () use ($itemsData, $userId, &$totalAmount, $month) {
            foreach ($itemsData as $itemData) {
                $itemAmount = $itemData['amount'];
                $totalAmount += $itemAmount; // Increment the total amount for all budgets

                // Create a Budget record
                $budget = Budget::create([
                    'user_id' => $userId,
                    'expense_category_id' => $itemData['expense_category_id'] ?? '',
                    'amount' => $itemAmount,
                    'month' => $month,
                    'description' => $itemData['description'] ?? '',
                    'created_by' => $userId,
                ]);
            }

            // Update the account balances after all budgets are created
            // You can add any relevant logic here for budget accounting if needed
        });

        return redirect()->route('budgets.index')
            ->with('success', trans('Budget added successfully, Total Amount: :total', ['total' => $totalAmount]));
    }

    public function show(Budget $budget)
    {
        return view('budget.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        $expenseCategories = DdExpenseCategory::select('id', 'title')->orderBy('id', 'desc')->get();
        return view('budget.edit', compact('budget', 'expenseCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Budget $budget)
    {
        $request->validate([
            'month' => 'required|string',
            'expense_category_id' => 'required|exists:dd_expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ]);

        // Retrieve the updated data from the request
        $data = $request->only([ 'expense_category_id', 'month', 'amount', 'description']);
        DB::transaction(function () use ($budget, $data) {
            // Update the budget record
            $data['updated_by'] = auth()->id();
            $budget->update($data);
        });

        return redirect()->route('budgets.edit', $budget->id)
            ->with('success', trans('Budget updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Budget $budget)
    {
        $budget->delete();

        return redirect()->route('budgets.index')
            ->with('success', trans('Budget deleted successfully.'));
    }

    private function validation(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'budget_items' => 'required|array|min:1',
            'budget_items.*.expense_category_id' => 'required|exists:dd_expense_categories,id',
            'budget_items.*.amount' => 'required|numeric|min:0.01',
            'budget_items.*.description' => 'nullable|string',
        ]);
    }
}

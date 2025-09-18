<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;
use App\Models\DdExpenseCategory;
use App\Models\DdIncomeCategory;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Support\Carbon;


class ReportController extends Controller
{
    public function transferReportIndex(Request $request)
    {
        // Handle export directly
        if ($request->export) {
            return $this->transferExport($request);
        }
    
        
        // Fetch transfers only if filters are applied
        $transfers = $this->filterTransfers($request)->get();
        // Fetch accounts for dropdown
        $accounts = Account::where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get(['id', 'account_title']);
    
        return view('reports.transfer-report-index', compact('transfers', 'accounts'));
    }
    

    private function filterTransfers(Request $request)
    {
        $query = Transfer::query();
        
        if ($request->filled('transfer_amount')) {
            $query->where('transfer_amount', 'like', '%' . $request->input('transfer_amount') . '%');
        }

        if ($request->filled('from_account_id')) {
            $query->where('from_account_id', $request->input('from_account_id'));
        }

        if ($request->filled('to_account_id')) {
            $query->where('to_account_id', $request->input('to_account_id'));
        }

        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->input('description') . '%');
        }

        if ($request->date_from && $request->date_to) {
            // If both start_date and end_date are provided
            $startDate = Carbon::parse($request->date_from)->startOfDay();
            $endDate = Carbon::parse($request->date_to)->endOfDay();
            $query->whereBetween('transfer_date', [$startDate, $endDate]);
        } elseif ($request->date_from) {
            // If only start_date is provided
            $startDate = Carbon::parse($request->date_from)->startOfDay();
            $query->whereDate('transfer_date', $startDate);
        } elseif ($request->date_to) {
            // If only end_date is provided, use today's date as the start_date
            $endDate = Carbon::parse($request->date_to)->startOfDay();
            $query->whereDate('transfer_date', $endDate);
        }

        return $query;
    }
    
    public function transferExport(Request $request)
    {
        $transfers = $this->filterTransfers($request)
            ->with(['user', 'fromAccount', 'toAccount'])
            ->get();

        // Map data for export
        $data = $transfers->map(function ($transfer, $index) {
            return [
                'S.No' => $index + 1, // Adding an incremented index as a serial number
                'User' => $transfer->user->name ?? 'N/A',
                'From' => $transfer->fromAccount->account_title ?? 'N/A',
                'To' => $transfer->toAccount->account_title ?? 'N/A',
                'Amount' => $transfer->transfer_amount,
                'Description' => $transfer->description ?? 'N/A',
                'Date' => $transfer->transfer_date,
            ];
        })->toArray();

        $headers = ['S.No', 'User', 'From', 'To', 'Amount', 'Description', 'Date'];

        return Excel::download(new GenericExport($data, $headers), 'transfers.xlsx');
    }






    public function incomeReportIndex(Request $request)
    {
        if ($request->export) {
            return $this->incomeExport($request);
        }
        
        $incomeItems = $this->filterIncomes($request)->get();


        $accounts = Account::where('user_id', auth()->id())->get(['id', 'account_title']);
        $incomeCategories = DdIncomeCategory::orderBy('id')->get(['id', 'title']);

                // Get only the categories used in the current page of incomes
        $usedCategoryIds = $incomeItems->pluck('income_category_id')->unique();
        $usedCategories = DdIncomeCategory::whereIn('id', $usedCategoryIds)->get();

        // Generate colors only for the used categories
        $categoryColors = getCategoryColors($usedCategories);

        return view('reports.income-report-index', compact('incomeItems', 'accounts', 'incomeCategories','categoryColors','usedCategories'));
    }

    private function filterIncomes(Request $request)
    {
        $query = Income::query()->with(['user', 'account', 'incomeCategory']);

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->input('account_id'));
        }

        if ($request->filled('income_category_id')) {
            $query->where('income_category_id', $request->input('income_category_id'));
        }

        if ($request->filled('amount')) {
            $query->where('amount', 'like', '%' . $request->input('amount') . '%');
        }

        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->input('description') . '%');
        }

        if ($request->date_from && $request->date_to) {
            // If both start_date and end_date are provided
            $startDate = Carbon::parse($request->date_from)->startOfDay();
            $endDate = Carbon::parse($request->date_to)->endOfDay();
            $query->whereBetween('income_date', [$startDate, $endDate]);
        } elseif ($request->date_from) {
            // If only start_date is provided
            $startDate = Carbon::parse($request->date_from)->startOfDay();
            $query->whereDate('income_date', $startDate);
        } elseif ($request->date_to) {
            // If only end_date is provided, use today's date as the start_date
            $endDate = Carbon::parse($request->date_to)->startOfDay();
            $query->whereDate('income_date', $endDate);
        }

        return $query;
    }



    public function incomeExport(Request $request)
    {
        $incomeItems = $this->filterIncomes($request)
            ->get();

            $data = $incomeItems->map(function ($income, $index) {
                return [
                'S.No' => $index + 1, // Adding an incremented index as a serial number
                'User' => $income->user->name ?? '-',
                'Account' => $income->account->account_title ?? '-',
                'Income Category' => $income->incomeCategory->title ?? '-',
                'Amount' => $income->amount,
                'Description' => $income->description ?? '-',
                'Date' => $income->income_date ?? '-',
            ];
        })->toArray();

        $headers = ['S.No','User', 'Account', 'Income Category', 'Amount', 'Description', 'Date'];

        return Excel::download(new GenericExport($data, $headers), 'incomes-report.xlsx');
    }


    public function expenseReportIndex(Request $request)
    {
        if ($request->export) {
            return $this->expenseExport($request);
        }

        $filtersApplied = $request->hasAny(['date_from', 'date_to','account_id' ,'expense_category_id', 'amount','notes']);
        $expenseItems =  $this->filterExpenses($request)->get();

        $accounts = Account::where('user_id', auth()->id())->get(['id', 'account_title']);
        $expenseCategories = DdExpenseCategory::orderBy('id')->get(['id', 'title']);

        // Get only the categories used in the current page of incomes
        $usedCategoryIds = $expenseItems->pluck('expense_category_id')->unique();
        $usedCategories = DdExpenseCategory::whereIn('id', $usedCategoryIds)->get();

        // Generate colors only for the used categories
        $categoryColors = getCategoryColors($usedCategories);
        return view('reports.expense-report-index', compact('expenseItems', 'accounts', 'expenseCategories','categoryColors','usedCategories'));
    }

    private function filterExpenses(Request $request)
    {
        $query = Expense::query()->with(['user', 'account', 'expenseCategory']);
    
        // Filtering by account_id
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->input('account_id'));
        }
    
        // Filtering by expense_category_id
        if ($request->filled('expense_category_id')) {
            $query->where('expense_category_id', $request->input('expense_category_id'));
        }
    
        // Filtering by amount
        if ($request->filled('amount')) {
            $query->where('amount', 'like', '%' . $request->input('amount') . '%');
        }
    
        // Filtering by notes
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->input('description') . '%');
        }

        if ($request->date_from && $request->date_to) {
            // If both start_date and end_date are provided
            $startDate = Carbon::parse($request->date_from)->startOfDay();
            $endDate = Carbon::parse($request->date_to)->endOfDay();
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        } elseif ($request->date_from) {
            // If only start_date is provided
            $startDate = Carbon::parse($request->date_from)->startOfDay();
            $query->whereDate('expense_date', $startDate);
        } elseif ($request->date_to) {
            // If only end_date is provided, use today's date as the start_date
            $endDate = Carbon::parse($request->date_to)->startOfDay();
            $query->whereDate('expense_date', $endDate);
        }
    
        return $query;
    }
    public function expenseExport(Request $request)
    {
        $expenses = $this->filterExpenses($request)
            ->with(['user', 'account', 'expenseCategory'])
            ->get();
    
        $data = $expenses->map(function ($item, $index) {
            return [
                'S.No' => $index + 1, // Adding an incremented index as a serial number
                'User' => $item->user->name ?? '-',
                'Account' => $item->account->account_title ?? '-',
                'Expense Category' => $item->expenseCategory->title ?? '-',
                'Amount' => $item->amount,
                'Description' => $item->description ?? '-',
                'Date' => $item->expense_date ?? '-',
            ];
        })->toArray();
    
        $headers = ['S.No','User', 'Account', 'Expense Category', 'Amount', 'Description', 'Date'];
    
        return Excel::download(new GenericExport($data, $headers), 'expenses-report.xlsx');
    }


 
    

}



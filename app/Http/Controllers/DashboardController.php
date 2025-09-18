<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\DdExpenseCategory;
use App\Models\DdIncomeCategory;
use App\Models\ExamInvestigation;
use App\Models\Expense;
use App\Models\ExpenseItem;
use App\Models\HospitalDepartment;
use App\Models\Income;
use App\Models\IncomeItem;
use App\Models\Invoice;
use App\Models\LabReport;
use App\Models\PatientAppointment;
use App\Models\PatientCaseStudy;
use App\Models\PatientTreatmentPlan;
use App\Models\Payment;
use App\Models\InsuranceProvider;
use App\Models\Inventory;
use App\Models\Task;
use App\Models\Prescription;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers
 * @category Controller
 */
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @access public
     * @return mixed
     */
    public function index()
    {
        
        if (auth()->user()->hasRole('Super Admin')) {
            return $this->adminDashboard();
        } elseif(auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin')) {
            return $this->adminDashboard();
        } elseif(auth()->user()->hasRole('Doctor')) {
            return $this->adminDashboard();
            //$doctorId = auth()->user()->id;
            //$patientAppointment = PatientAppointment::with('user')->where('doctor_id', $doctorId)->where('company_id', session('company_id'))->get();
            //return view('dashboards.common-dashboard', compact('patientAppointment'));
        } elseif(auth()->user()->hasRole('Patient')) {
            $patientId = auth()->user()->id;
            $appointments = PatientAppointment::with('user')->where('user_id', $patientId)->where('company_id', session('company_id'))->get();
            return view('dashboards.common-dashboard', compact('appointments'));
        } elseif(auth()->user()->hasRole('Receptionist')) {
            return $this->adminDashboard();
            //$receptionistAppointments = PatientAppointment::with('user')->where('company_id', session('company_id'))->get();
            //return view('dashboards.common-dashboard', compact('receptionistAppointments'));
        } else {
            return view('dashboards.common-dashboard');
        }
    }

    /**
     * shows admin dashboard
     *
     * @return \Illuminate\Http\Response
     */
    private function adminDashboard()
    {
        $dashboardCounts = $this->dashboardCounts();

        return view('dashboardview', compact('dashboardCounts'));
    }



    public function getExpenseDonutData(Request $request)
    {
        $userId = auth()->id();
        $filter = $request->input('filter', 'all');  // Default to 'all'
    
        // Prepare date range based on filter
        $dateRange = null;
        switch ($filter) {
            case 'today':
                $dateRange = [now()->startOfDay(), now()->endOfDay()];
                break;
            case 'last_3_days':
                $dateRange = [now()->subDays(3), now()];
                break;
            case 'last_7_days':
                $dateRange = [now()->subDays(7), now()];
                break;
            case 'last_15_days':
                $dateRange = [now()->subDays(15), now()];
                break;
            case 'last_30_days':
                $dateRange = [now()->subDays(30), now()];
                break;
            case 'all':
            default:
                $dateRange = null;  // No date filter
                break;
        }
    
        // Get all expense categories with their total amounts grouped by category_id
        $data = DdExpenseCategory::all()
            ->map(function ($category) use ($userId, $dateRange) {
                // Query to get the total sum of amounts for each expense category
                $query = Expense::where('user_id', $userId)
                    ->where('expense_category_id', $category->id);
    
                // Apply date filter if specified
                if ($dateRange) {
                    $query->whereBetween('expense_date', $dateRange);
                }
    
                $totalAmount = $query->sum('amount'); // Sum the 'amount' for the category
    
                return [
                    'label' => $category->title,
                    'value' => $totalAmount,
                ];
            });
    
        // If there are no expenses at all (all zero values), return a single entry with zero value
        if ($data->sum('value') == 0) {
            return response()->json([[ 'label' => 'Expenses', 'value' => 0 ]]);
        }
    
        // Filter out categories with zero value and return the remaining data
        $filteredData = $data->filter(function ($item) {
            return $item['value'] > 0; // Only include categories with a positive value
        })->values(); // Re-index the filtered collection
    
        // Return the filtered data as a JSON response
        return response()->json($filteredData);
    }
    
    public function incomeExpenseData(Request $request)
    {
        $year = $request->input('year', date('Y')); // Default to current year if not provided
        $userId = auth()->id(); // Get the authenticated user's ID
        
        // Fetch and group incomes by month for the selected year
        $incomeData = Income::where('user_id', $userId)
            ->whereYear('income_date', $year) // Filter by year
            ->selectRaw('SUM(amount) as total, MONTH(income_date) as month') // Sum the 'amount' for each month
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
    
        // Ensure all months (1-12) are included in the data, filling with 0 if missing
        $incomeTotals = array_replace(
            array_fill(1, 12, 0), // Fill months 1-12 with 0 initially
            $incomeData // Replace with actual income data for each month
        );
    
        // Fetch and group expenses by month for the selected year
        $expenseData = Expense::where('user_id', $userId)
            ->whereYear('expense_date', $year) // Filter by year
            ->selectRaw('SUM(amount) as total, MONTH(expense_date) as month') // Sum the 'amount' for each month
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
    
        // Ensure all months (1-12) are included in the data, filling with 0 if missing
        $expenseTotals = array_replace(
            array_fill(1, 12, 0), // Fill months 1-12 with 0 initially
            $expenseData // Replace with actual expense data for each month
        );
    
        // Return the data as JSON
        return response()->json([
            'income' => array_values($incomeTotals),
            'expenses' => array_values($expenseTotals)
        ]);
    }

    public function getBudgetVsExpenses(Request $request){
        $userId = auth()->id();
        $month = $request->input('month');
        $year = $request->input('year', now()->year); // Default to current year if not provided

        // Get the budget data for the selected month and year
        $budgets = Budget::where('user_id', $userId)
            ->whereMonth('month', $month)
            ->whereYear('created_at', $year)
            ->get();

        // Get all expense categories and calculate actual expenses within the month
        $data = DdExpenseCategory::all()->map(function ($category) use ($userId, $month, $year, $budgets) {
            // Get the budgeted amount for the category
            $budgetAmount = $budgets->where('expense_category_id', $category->id)->sum('amount');
            
            // Get the total sum of actual expenses for the category in the given month
            $actualExpenses = Expense::where('user_id', $userId)
                ->where('expense_category_id', $category->id)
                ->whereMonth('expense_date', $month)
                ->whereYear('expense_date', $year)
                ->sum('amount');

            return [
                'label' => $category->title,
                'budgeted' => $budgetAmount,
                'actual' => $actualExpenses,
            ];
        });

        // If no data found, return a default empty data response
        if ($data->sum('budgeted') == 0 && $data->sum('actual') == 0) {
            return response()->json([[ 'label' => 'No Data', 'budgeted' => 0, 'actual' => 0 ]]);
        }

        // Filter out categories with zero budget and actual value
        $filteredData = $data->filter(function ($item) {
            return $item['budgeted'] > 0 || $item['actual'] > 0;
        })->values(); // Re-index the filtered collection

        return response()->json($filteredData);
    }

    public function updateDashboardCounts(Request $request)
{
    $userId = auth()->id();
    $filter = $request->input('filter', 'all');  // Default to 'all'
    $dateRange = null;

    // Prepare date range based on filter
    switch ($filter) {
        case 'today':
            $dateRange = [now()->startOfDay(), now()->endOfDay()];
            break;
        case 'yesterday':
            $dateRange = [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()];
            break;
        case 'last_3_days':
            $dateRange = [now()->subDays(3), now()];
            break;
        case 'last_7_days':
            $dateRange = [now()->subDays(7), now()];
            break;
        case 'last_15_days':
            $dateRange = [now()->subDays(15), now()];
            break;
        case 'last_30_days':
            $dateRange = [now()->subDays(30), now()];
            break;
        case 'all':
        default:
            $dateRange = null;  // No date filter
            break;
    }

    // Get the dashboard counts
    $counts = [
        'accounts' => Account::where('user_id', $userId)->count(),
        'assets' => Account::where('user_id', $userId)->where('balance', '>', 0)->sum('balance'),
        'liabilities' => Account::where('user_id', $userId)->where('balance', '<', 0)->sum('balance'),
        'total' => Account::where('user_id', $userId)->sum('balance'),
        'incomes' => Income::where('user_id', $userId)
            ->when($dateRange, function ($query) use ($dateRange) {
                $query->whereBetween('income_date', $dateRange);
            })
            ->count(),
        'income_total' => Income::where('user_id', $userId)
            ->when($dateRange, function ($query) use ($dateRange) {
                $query->whereBetween('income_date', $dateRange);
            })
            ->sum('amount'),
        'expenses' => Expense::where('user_id', $userId)
            ->when($dateRange, function ($query) use ($dateRange) {
                $query->whereBetween('expense_date', $dateRange);
            })
            ->count(),
        'expense_total' => Expense::where('user_id', $userId)
            ->when($dateRange, function ($query) use ($dateRange) {
                $query->whereBetween('expense_date', $dateRange);
            })
            ->sum('amount'),
        'transfers' => Transfer::where('user_id', $userId)
            ->when($dateRange, function ($query) use ($dateRange) {
                $query->whereBetween('transfer_date', $dateRange);
            })
            ->count(),
        'transfer_total' => Transfer::where('user_id', $userId)
            ->when($dateRange, function ($query) use ($dateRange) {
                $query->whereBetween('transfer_date', $dateRange);
            })
            ->sum('transfer_amount'),
    ];

    return response()->json($counts);
}



    /**
     * shows admin char data
     *
     * @return \Illuminate\Http\Response
     */
    public function getChartData()
    {
        return response()->json([
            'monthlyDebitCredit' => $this->monthlyDebitCredit(),
            'currentYearDebitCredit' => $this->currentYearDebitCredit(),
            'overallDebitCredit' => $this->overallDebitCredit()
        ], 200);
    }

    private function dashboardCounts()
    {
        return cache()->remember('dashboardCounts', 600, function () {
            $userId = auth()->id();  // Get the authenticated user's ID
            
            return [
                'accounts' => Account::where('user_id', $userId)->count(),
                'assets' => Account::where('user_id', $userId)->where('balance', '>', 0)->sum('balance'),
                'liabilities' => Account::where('user_id', $userId)->where('balance', '<', 0)->sum('balance'),
                'total' => Account::where('user_id', $userId)->sum('balance'),
                'incomes' => Income::where('user_id', $userId)->count(),
                'expenses' => Expense::where('user_id', $userId)->count(),
                'income_total' => Income::where('user_id', $userId)->sum('amount'),  // Sum of amounts for the authenticated user
                'expense_total' => Expense::where('user_id', $userId)->sum('amount'),  // Sum of amounts for the authenticated user
                'transfers' => Transfer::where('user_id', $userId)->count(),
                'transfer_total' => Transfer::where('user_id', $userId)->sum('transfer_amount'),
            ];
        });
    }
    
}

<?php

namespace App\Http\Controllers;

use App\Models\UserLogs;
use App\Exports\UserExport;
use App\Models\User;
use App\Traits\Loggable;
use App\Models\BudgetDetail;
use App\Models\Invoice;
use App\Models\Prescription;
use App\Models\PatientAppointment;
use App\Models\ExamInvestigation;
use App\Models\PatientTreatmentPlan;
use App\Models\DdBloodGroup;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\DoctorRegistrationNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\DdIncomeCategory;

use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;
class BudgetDetailController extends Controller
{
    /**
     * Constructor
     */
    function __construct()
    {
        // $this->middleware('permission:budgetDetail-read|budgetDetail-create|budgetDetail-update|budgetDetail-delete', ['only' => ['index', 'show']]);
        // $this->middleware('permission:budgetDetail-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:budgetDetail-update', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:budgetDetail-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->export) {
            return $this->doExport($request);
        }
       

        $budgetDetails = $this->filter($request)->with('incomeCategory')->orderBy('id', 'desc')->paginate(10);
        return view('budgetDetails.index', compact('budgetDetails'));
    }

    public function doExport(Request $request)
    {
        // Retrieve filtered data
        $budgetDetails = $this->filter($request)
            ->with(['user']) // eager load relationships
            ->get();

        // Prepare data for export
        $data = $budgetDetails->map(function ($budgetDetail) {
            return [
                'ID' => $budgetDetail->id,
                'User Name' => $budgetDetail->user->name ?? 'N/A',
                'Category Id' => $budgetDetail->category_id,
                'Balance' => $budgetDetail->balance,
                'Created At' => $budgetDetail->created_at,
                'Updated At' => $budgetDetail->updated_at,
            ];
        })->toArray();

        // Define headers for the export
        $headers = ['ID', 'User Name', 'Category Id', 'Balance', 'Created At', 'Updated At'];

        return Excel::download(new GenericExport($data, $headers), 'BudgetDetails.xlsx');
    }




    /**
     * Filter function
     *
     * @param Request $request
     * @return Illuminate\Database\Eloquent\Builder
     */
    private function filter(Request $request)
    {
        $query = BudgetDetail::query();  // Start with the BudgetDetail model query
    
        // Apply filters based on the request inputs
        if ($request->category_id) {
            $query->where('category_id', 'like', '%' . $request->category_id . '%');
        }
    
        if ($request->balance) {
            $query->where('balance', 'like', '%' . $request->balance . '%');
        }
    
        // Filter by date range if provided
        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($request->start_date) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->where('created_at', '>=', $startDate);
        } elseif ($request->end_date) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->where('created_at', '<=', $endDate);
        }
    
        return $query;
    }
    

    /**
     * Get active budgetDetail list
     *
     * @return \Illuminate\Http\Response
     */
    public function getDoctorList(Request $request)
    {
        if ($request->lang)
            app()->setLocale($request->lang);

        $budgetDetails = User::role('BudgetDetail')->where('status', '1')->get();
        $output = '<option value="">' . __('Select BudgetDetail') . '*</option>';
        foreach ($budgetDetails as $budgetDetail) {
            $output .= '<option value="' . $budgetDetail->id . '">' . $budgetDetail->name . '</option>';
        }
        return response()->json($output, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $incomeCategories = DdIncomeCategory::all(); 
        return view('budgetDetails.create', compact('incomeCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */




  public function store(Request $request)
{
    // Validate the incoming request
    $this->validation($request);

    // Get only the budgetDetail-related data from the request
    $budgetDetailData = $request->only(['category_id', 'balance']);

    DB::transaction(function () use ($budgetDetailData, &$budgetDetail) {
        // Add the authenticated user ID to budgetDetail data
        $budgetDetailData['user_id'] = auth()->id(); // Assuming the logged-in user creates the budgetDetail
        $budgetDetailData['created_by'] = auth()->id(); // Optionally, add who created the budgetDetail

        // Create the budgetDetail
        $budgetDetail = BudgetDetail::create($budgetDetailData);
    });

    // Redirect after successful creation
    return redirect()->route('budgetDetails.edit', $budgetDetail->id)
        ->with('success', trans('BudgetDetail Added Successfully'));
}

     


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BudgetDetail  $budgetDetail
     * @return \Illuminate\Http\Response
     */
    public function show(BudgetDetail $budgetDetail)
    {
        $patientAppointments = PatientAppointment::where('doctor_id', $budgetDetail->user->id)->orderBy('created_at', 'desc')->get();
        $examInvestigations = 1;
        $patientTreatmentPlans = PatientTreatmentPlan::where('doctor_id', $budgetDetail->user->id)->orderBy('created_at', 'desc')->get();
        $prescriptions = Prescription::where('doctor_id', $budgetDetail->user->id)->orderBy('created_at', 'desc')->get();
        $invoices = Invoice::where('user_id', $budgetDetail->user->id)->orderBy('created_at', 'desc')->get();
        return view('budgetDetails.show', compact('budgetDetail','patientAppointments','examInvestigations','patientTreatmentPlans','prescriptions','invoices'));    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BudgetDetail  $budgetDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(BudgetDetail $budgetDetail)
    {
        $bloodGroups = DdBloodGroup::all();

        // start of log
        $logs = UserLogs::where('table_name', 'doctor_details')->orderBy('id', 'desc')
        ->with('user')
        ->paginate(10);
                // end of log
        $logs = '';
        $incomeCategories = DdIncomeCategory::all(); 

        return view('budgetDetails.edit', compact('budgetDetail', 'incomeCategories','logs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BudgetDetail  $budgetDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BudgetDetail $budgetDetail)
    {
        $this->validation($request, $budgetDetail->user_id);
        $budgetDetailData = $request->only(['category_id', 'balance']);
    
        // Use a transaction to ensure both user and budgetDetail data are updated atomically
        DB::transaction(function () use ($budgetDetailData, $budgetDetail) {
            $budgetDetail->update($budgetDetailData);
        });
    
        // Redirect to the budgetDetail edit page with a success message
        return redirect()->route('budgetDetails.edit', $budgetDetail->id)
            ->with('success', trans('BudgetDetail Updated Successfully'));
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BudgetDetail  $budgetDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(BudgetDetail $budgetDetail)
    {
        $budgetDetail->delete();
        return redirect()->route('budgetDetails.index')->with('success', trans('BudgetDetail Deleted Successfully'));
    }

    /**
     * Validation function
     *
     * @param Request $request
     * @return void
     */
    private function validation(Request $request, $id = 0)
{
  
    $request->validate([
        'category_id' => ['required', 'integer'],
        'balance' => ['nullable', 'numeric']
    ]);
}

}
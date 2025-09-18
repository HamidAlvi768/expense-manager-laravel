<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DdExpenseCategory;
use Illuminate\Support\Facades\Auth;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

class DdExpenseCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->export) {
            return $this->doExport($request);
        }
        $expenseCategories = $this->filter($request)->orderBy('id', 'desc')->paginate(10);
        return view('dd-expense-category.index', compact('expenseCategories'));
    }
    private function doExport(Request $request)
    {
        // Retrieve all data without filters
        $ddExpenseCategory = DdExpenseCategory::get();

        // Prepare data for export
        $data = $ddExpenseCategory->map(function ($expenseCategories) {
            return [
                $expenseCategories->id,
                $expenseCategories->title,
                $expenseCategories->status == '1' ? 'Active' : 'Inactive',
                $expenseCategories->created_at,
                $expenseCategories->updated_at,
            ];
        })->toArray();

        // Define headers for the export
        $headers = ['ID', 'Title', 'Status', 'Created At', 'Updated At'];

        return Excel::download(new GenericExport($data, $headers), 'ddExpenseCategories.xlsx');
    }



    private function filter(Request $request)
    {
        $query = DdExpenseCategory::query();

        if ($request->has('title') && $request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        return $query;
    }






    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('dd-expense-category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $this->validation($request);

        // Extract and add 'created_by' to the data array
        $drugHistoryData = $request->only(['title']);
        $drugHistoryData['created_by'] = Auth::id(); // Add the authenticated user's ID

        // Store the validated data into the database
        $drugHistory = new DdExpenseCategory($drugHistoryData);
        $drugHistory->save();
        $DdExpenseCategory= $drugHistory->id;
        // Redirect to the medical history index route with a success message
        return   redirect()->route('dd-expense-category.edit',$DdExpenseCategory)->with('success', trans('Expense Category created successfully'));
    }
    public function show(DdExpenseCategory $DdExpenseCategory)
    {
        return view('dd-expense-category.show', compact('DdExpenseCategory'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DoctorDetail  $doctorDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(DdExpenseCategory $DdExpenseCategory)
    {

        return view('dd-expense-category.edit', compact('DdExpenseCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DoctorDetail  $doctorDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,DdExpenseCategory $DdExpenseCategory)

    {
        $this->validation($request);
        $data = $request->all();
        $data['updated_by'] = auth()->id();
        $DdExpenseCategory->update($data);
        return   redirect()->route('dd-expense-category.edit', $DdExpenseCategory)->with('success', trans('Expense Category updated successfully'));

    }



    public function destroy(DdExpenseCategory $DdExpenseCategory)
    {
        $DdExpenseCategory->delete();
        return redirect()->route('dd-expense-category.index')->with('success', trans('Expense Category Deleted Successfully'));
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
            'title' => ['required', 'unique:users,name,' . $id, 'max:255'],
            // Adjust max length as needed

        ]);
    }
}

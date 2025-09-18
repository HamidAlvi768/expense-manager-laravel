<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DdIncomeCategory;
use Illuminate\Support\Facades\Auth;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

class DdIncomeCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->export) {
            return $this->doExport($request);
        }
        $incomeCategories = $this->filter($request)->orderBy('id', 'desc')->paginate(10);
        return view('dd-income-category.index', compact('incomeCategories'));
    }
    private function doExport(Request $request)
    {
        // Retrieve all data without filters
        $ddIncomeCategory = DdIncomeCategory::get();

        // Prepare data for export
        $data = $ddIncomeCategory->map(function ($incomeCategories) {
            return [
                $incomeCategories->id,
                $incomeCategories->title,
                $incomeCategories->status == '1' ? 'Active' : 'Inactive',
                $incomeCategories->created_at,
                $incomeCategories->updated_at,
            ];
        })->toArray();

        // Define headers for the export
        $headers = ['ID', 'Title', 'Status', 'Created At', 'Updated At'];

        return Excel::download(new GenericExport($data, $headers), 'ddincomeCategories.xlsx');
    }



    private function filter(Request $request)
    {
        $query = DdIncomeCategory::query();

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

        return view('dd-income-category.create');
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
        $drugHistory = new DdIncomeCategory($drugHistoryData);
        $drugHistory->save();
        $DdIncomeCategory= $drugHistory->id;
        // Redirect to the medical history index route with a success message
        return   redirect()->route('dd-income-category.edit',$DdIncomeCategory)->with('success', trans('Income Category created successfully'));
    }
    public function show(DdIncomeCategory $DdIncomeCategory)
    {
        return view('dd-income-category.show', compact('DdIncomeCategory'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DoctorDetail  $doctorDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(DdIncomeCategory $DdIncomeCategory)
    {

        return view('dd-income-category.edit', compact('DdIncomeCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DoctorDetail  $doctorDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,DdIncomeCategory $DdIncomeCategory)

    {
        $this->validation($request);
        $data = $request->all();
        $data['updated_by'] = auth()->id();
        $DdIncomeCategory->update($data);
        return   redirect()->route('dd-income-category.edit', $DdIncomeCategory)->with('success', trans('Income Category updated successfully'));

    }



    public function destroy(DdIncomeCategory $DdIncomeCategory)
    {
        $DdIncomeCategory->delete();
        return redirect()->route('dd-income-category.index')->with('success', trans('Income Category Deleted Successfully'));
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DdAccountType;
use Illuminate\Support\Facades\Auth;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

class DdAccountTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->export) {
            return $this->doExport($request);
        }
        $accountTypes = $this->filter($request)->orderBy('id', 'desc')->paginate(10);
        return view('dd-account-type.index', compact('accountTypes'));
    }
    private function doExport(Request $request)
    {
        // Retrieve all data without filters
        $ddAccontTypes = DdAccountType::get();

        // Prepare data for export
        $data = $ddAccontTypes->map(function ($accountTypes) {
            return [
                $accountTypes->id,
                $accountTypes->title,
                $accountTypes->status == '1' ? 'Active' : 'Inactive',
                $accountTypes->created_at,
                $accountTypes->updated_at,
            ];
        })->toArray();

        // Define headers for the export
        $headers = ['ID', 'Title', 'Status', 'Created At', 'Updated At'];

        return Excel::download(new GenericExport($data, $headers), 'ddAccountTypes.xlsx');
    }



    private function filter(Request $request)
    {
        $query = DdAccountType::query();

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

        return view('dd-account-type.create');
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
        $drugHistory = new DdAccountType($drugHistoryData);
        $drugHistory->save();
        $DdAccountType= $drugHistory->id;
        // Redirect to the medical history index route with a success message
        return   redirect()->route('dd-account-type.edit',$DdAccountType)->with('success', trans('Accont Type created successfully'));
    }
    public function show(DdAccountType $DdAccountType)
    {
        return view('dd-account-type.show', compact('DdAccountType'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DoctorDetail  $doctorDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(DdAccountType $DdAccountType)
    {

        return view('dd-account-type.edit', compact('DdAccountType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DoctorDetail  $doctorDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,DdAccountType $DdAccountType)

    {
        $this->validation($request);
        $data = $request->all();
        $data['updated_by'] = auth()->id();
        $DdAccountType->update($data);
        return   redirect()->route('dd-account-type.edit', $DdAccountType)->with('success', trans('Accont Type updated successfully'));

    }



    public function destroy(DdAccountType $DdAccountType)
    {
        $DdAccountType->delete();
        return redirect()->route('dd-account-type.index')->with('success', trans('Accont Type Deleted Successfully'));
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

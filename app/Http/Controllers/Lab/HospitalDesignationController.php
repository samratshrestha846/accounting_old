<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\HostpitalDesignationRequest;
use App\Models\Lab\HospitalDesignation;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HospitalDesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designations = HospitalDesignation::query()
            ->when(request('search'), fn ($query) => $query->where('title', 'like', "%" . request('search') . '%'))
            ->get();
        return view('lab.designation.index', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designation = new HospitalDesignation();
        return view('lab.designation.form', compact('designation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HostpitalDesignationRequest $request)
    {

        if($request->ajax()){
            $designation =  HospitalDesignation::create($request->except(['_token']));
            return '<option value="'.$designation->id.'" selected>'.$designation->title.'</option>';
        }
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $designation =  HospitalDesignation::create($data);
            if($request->ajax()){
                return '<option value="'.$designation->id.'">'.$designation->title.'</option>';
            }
            DB::commit();
            request()->session()->flash('success', 'Designation ' . $designation->title . '  created Successfully');
            return redirect()->route('hospital-designation.index');
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $designation =  HospitalDesignation::findorfail($id);
        return view('lab.designation.form', compact('designation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HostpitalDesignationRequest $request, $id)
    {
        $designation = HospitalDesignation::findorfail($id);

        $data = $request->validated();
        DB::beginTransaction();

        try {
            $designation->update($data);
            DB::commit();
            request()->session()->flash('success', 'Designation ' . $designation->title . '  created Successfully');
            return redirect()->route('hospital-designation.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $designation = HospitalDesignation::findorfail($id);
        try {
            $designation->delete();
            request()->session()->flash('success', 'Designation ' . $designation->title . '  created Successfully');
            return redirect()->route('hospital-designation.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}

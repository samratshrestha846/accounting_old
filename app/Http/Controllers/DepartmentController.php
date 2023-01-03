<?php

namespace App\Http\Controllers;

use App\Actions\CreateDepartmentAction;
use App\Actions\DeleteDepartmentAction;
use App\Actions\UpdateDepartmentAction;
use App\FormDatas\DepartmentFormData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\Device;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ( $request->user()->can( 'manage-department' ) ) {
            $departments = Department::with('device')->latest()->paginate( 10 );
            return view( 'backend.department.index', compact( 'departments' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ( $request->user()->can( 'manage-department' ) ) {
            $devices = Device::select('id','name','serial_number','ip_address')->get();
            return view('backend.department.create', compact('devices'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDepartmentRequest $request)
    {
        $departmentFormData = new DepartmentFormData(
            $request->name,
            $request->code,
            $request->location,
            $request->device ? (int) $request->device : null,
        );

        (new CreateDepartmentAction)->execute($departmentFormData);

        return redirect()->route( 'department.index' )->with( 'success', 'New Department information added successfully.' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department, Request $request)
    {
        if ( $request->user()->can( 'manage-department' ) ) {
            return view('backend.department.edit', compact('department'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $departmentFormData = new DepartmentFormData(
            $request->name,
            $request->code,
            $request->location,
        );

        (new UpdateDepartmentAction)->execute($department , $departmentFormData);

        return redirect()->route( 'department.index' )->with( 'success', 'Department information updated successfully.' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department, Request $request)
    {
        if ( $request->user()->can( 'manage-department' ) ) {
            try {
                (new DeleteDepartmentAction)->execute($department);
            } catch(\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            return redirect()->back()->with('success',"Department deleted successfully");
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\CreateStaffAction;
use App\Actions\UpdateStaffAction;
use App\Enums\Gender;
use App\FormDatas\StaffFormData;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Department;
use App\Models\Position;
use App\Models\Staff;
use App\Services\UploadService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller {

    protected UploadService $uploadService;

    public function __construct()
    {
        $this->middleware('auth');
        $this->uploadService = new UploadService;
    }

    public function index( Request $request ) {
        if ( $request->user()->can( 'manage-staffs' ) ) {
            $staffs = Staff::with('department')->latest()->paginate( 10 );
            return view( 'backend.staffs.index', compact( 'staffs' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $staffs = Staff::query()
        ->where( 'name', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.staffs.search', compact( 'staffs' ) );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'manage-staffs' ) ) {
            $positions = Position::where( 'status', 1 )->get();
            $departments = Department::get();
            $genders = Gender::getAllValues();
            return view( 'backend.staffs.create', compact( 'positions', 'departments','genders' ) );
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

    public function store( StoreStaffRequest $request ) {

        $data = $request->validated();
        $imagename = '';
        $national_id_name = '';
        $documents_name = '';
        $contract_name = '';

        DB::beginTransaction();
        try {
            if($request->hasFile('image')) {
                $imagename = $request->file('image')->store( 'staff_docs', 'uploads' );
            }

            if($request->hasFile('national_id')) {
                $national_id_name = $request->file('national_id')->store( 'staff_docs', 'uploads' );
            }

            if($request->hasFile('documents'))  {
                $documents_name = $request->file('documents')->store( 'staff_docs', 'uploads' );
            }

            if($request->hasFile('contract'))  {
                $contract_name = $request->file('contract')->store( 'staff_docs', 'uploads' );
            }

            $staffFormData = new StaffFormData(
                (int) $request->department,
                (int) $request->position,
                (string) $request->employee_id,
                $request->first_name,
                $request->last_name,
                $request->email,
                $request->phone,
                $request->gender,
                $request->emp_type,
                $request->date_of_birth,
                $request->city,
                $request->address,
                $request->postcode,
                $request->join_date,
                $imagename,
                $national_id_name,
                $documents_name,
                $contract_name,
            );

            $staff = (new CreateStaffAction)->execute($staffFormData);

            DB::commit();

        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route( 'staff.index' )->with( 'success', 'New Staff information added successfully.' );

        if ( $request->hasfile( 'image' ) && $request->hasfile( 'national_id' ) && $request->hasfile( 'documents' ) && $request->hasfile( 'contract' ) ) {
            $image = $request->file( 'image' );
            $national_id = $request->file( 'national_id' );
            $documents = $request->file( 'documents' );
            $contract = $request->file( 'contract' );
            $imagename = $image->store( 'staff_docs', 'uploads' );
            $national_id_name = $national_id->store( 'staff_docs', 'uploads' );
            $documents_name = $documents->store( 'staff_docs', 'uploads' );
            $contract_name = $contract->store( 'staff_docs', 'uploads' );

            $staff = Staff::create( [
                'position_id' => $data['position'],
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'image' => $imagename,
                'national_id' => $national_id_name,
                'documents' => $documents_name,
                'contract' => $contract_name,
                'status' => 1,
            ] );

            $staff->save();
            return redirect()->route( 'staff.index' )->with( 'success', 'New Staff information added successfully.' );
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Staff  $staff
    * @return \Illuminate\Http\Response
    */

    public function show( $id, Request $request ) {
        if ( $request->user()->can( 'manage-staffs' ) ) {
            $staff = Staff::where( 'id', $id )->with( 'position' )->first();
            return view( 'backend.staffs.show', compact( 'staff' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Staff  $staff
    * @return \Illuminate\Http\Response
    */

    public function edit( $id, Request $request ) {
        if ( $request->user()->can( 'manage-staffs' ) ) {
            $staff = Staff::findorFail( $id );
            $departments = Department::get();
            $position = Position::where( 'status', 1 )->get();
            $genders = Gender::getAllValues();
            return view( 'backend.staffs.edit', compact( 'staff', 'position', 'departments', 'genders' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Staff  $staff
    * @return \Illuminate\Http\Response
    */

    public function update( UpdateStaffRequest $request, Staff $staff) {

        $imagename = '';
        $national_id_name = '';
        $documents_name = '';
        $contract_name = '';

        DB::beginTransaction();
        try {
            if($request->hasFile('image')) {
                $imagename = $request->file('image')->store( 'staff_docs', 'uploads' );
                $this->uploadService->unlinkUploadFile('uploads/'.$staff->image);
                //unlink image
            }

            if($request->hasFile('national_id')) {
                $national_id_name = $request->file('national_id')->store( 'staff_docs', 'uploads' );
                $this->uploadService->unlinkUploadFile('uploads/'.$staff->national_id);
                //unlink file
            }

            if($request->hasFile('documents'))  {
                $documents_name = $request->file('documents')->store( 'staff_docs', 'uploads' );
                $this->uploadService->unlinkUploadFile('uploads/'.$staff->documents);
                //unlink file
            }

            if($request->hasFile('contract'))  {
                $contract_name = $request->file('contract')->store( 'staff_docs', 'uploads' );
                $this->uploadService->unlinkUploadFile('uploads/'.$staff->contract);
                //unlink file
            }

            $staffFormData = new StaffFormData(
                (int) $request->department,
                (int) $request->position,
                (string) $request->employee_id,
                $request->first_name,
                $request->last_name,
                $request->email,
                $request->phone,
                $request->gender,
                $request->emp_type,
                $request->date_of_birth,
                $request->city,
                $request->address,
                $request->postcode,
                $request->join_date,
                $imagename,
                $national_id_name,
                $documents_name,
                $contract_name,
            );

            $staff = (new UpdateStaffAction)->execute($staff, $staffFormData);

            DB::commit();

        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route( 'staff.index' )->with( 'success', 'Staff information updated successfully.' );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Staff  $staff
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id, Request $request ) {
        if ( $request->user()->can( 'manage-staffs' ) ) {
            $staff = Staff::findorFail( $id );

            Storage::disk( 'uploads' )->delete( $staff->image );
            Storage::disk( 'uploads' )->delete( $staff->national_id );
            Storage::disk( 'uploads' )->delete( $staff->documents );
            Storage::disk( 'uploads' )->delete( $staff->contract );

            $staff->delete();
            return redirect()->back()->with( 'success', 'Staff information deleted successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function disablestaff( $id, Request $request ) {
        if ( $request->user()->can( 'manage-staffs' ) ) {
            $position = Staff::findorfail( $id );
            $position->update( [
                'status' => '0',
            ] );
            return redirect()->route( 'staff.index' )->with( 'success', 'Staff Disabled Successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function enablestaff( $id, Request $request ) {
        if ( $request->user()->can( 'manage-staffs' ) ) {
            $position = Staff::findorfail( $id );
            $position->update( [
                'status' => '1',
            ] );
            return redirect()->route( 'staff.index' )->with( 'success', 'Staff Enabled Successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}

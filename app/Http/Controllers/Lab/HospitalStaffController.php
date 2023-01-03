<?php

namespace App\Http\Controllers\Lab;

use App\Actions\CreateCurrentBranch;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Helpers\HashPinNumber;
use App\Http\Controllers\Controller;
use App\Http\Requests\HospitalStaffRequest;
use App\Models\Lab\HospitalDesignation;
use App\Models\Lab\HospitalStaff;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HospitalStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staffs = HospitalStaff::with('designation:id,title')->paginate(20);
        return view('lab.staff.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $staff = new HospitalStaff();
        $designations = $this->getDesignation();
        return view('lab.staff.form', compact('staff', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\HospitalStaffRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HospitalStaffRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $staff =  HospitalStaff::create(Arr::except($data, 'password'));
            if ($staff->login) {
                $user =  (new CreateNewUser())->create(
                    [
                        'name' => $staff->name,
                        'email' => $staff->email,
                        'password' => request('password'),
                        'password_confirmation' => request('password'),
                        'pin_number' => (new HashPinNumber())->make(Str::random(4))
                    ]
                );
                (new  CreateCurrentBranch($user->id))->exceute();
                $role = Role::where('name', 'Hospital Staff')->first();
                $user->roles()->attach([$role->id]);
                $staff->update(['userId' => $user->id]);
            }

            DB::commit();
            request()->session()->flash('success', 'staff ' . $staff->name . '  created Successfully');
            return redirect()->route('hospital-staff.index');
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $staff =  HospitalStaff::Findorfail($id);
        $designations  = $this->getDesignation();
        return view('lab.staff.form', compact('staff', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HospitalStaffRequest $request, $id)
    {
        $staff = HospitalStaff::findorfail($id);

        $data = $request->validated();
        DB::beginTransaction();
        try {
            $staff->update(Arr::except($data, 'password'));
            if ($staff->userId) {
                (new UpdateUserProfileInformation())->update($staff->user, [
                    'name' => $staff->name,
                    'email' => $staff->email,
                ]);
            }
            DB::commit();
            request()->session()->flash('success', 'staff ' . $staff->name . '  created Successfully');
            return redirect()->route('hospital-staff.index');
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
        $staff = HospitalStaff::findorfail($id);
        try {
            $staff->delete();
            request()->session()->flash('success', 'staff ' . $staff->name . '  created Successfully');
            return redirect()->route('hospital-staff.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    private function getDesignation()
    {
        return HospitalDesignation::select('id', 'title')->orderBy('position', 'ASC')->get();
    }
}

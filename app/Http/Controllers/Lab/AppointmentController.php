<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lab\Appointment;
use App\Models\Lab\Patient;
use App\Models\Lab\HospitalStaff;
use App\Models\Lab\TestType;
use App\Models\Lab\HospitalDesignation;
use App\Http\Requests\AppointmentRequest;
use App\Models\UserCompany;
class AppointmentController extends Controller
{
    public function __construct(Appointment $appointment, Patient $patient)
    {

        $this->appointment = $appointment;
        $this->patient = $patient;
    }
    public function index(Request $request){
        $appointments = $this->getAppointments($request);
        return view('lab.appointment.index',compact('appointments'));
    }
    protected function getAppointments($request){
        $query = $this->appointment;
        if($request->date){
            $query = $query->whereDate('date',$request->date);
        }
        if($request->search){
            $keyword = $request->search;
            $query = $query->where(function ($qr) use ($keyword) {
                $qr->where('title', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('notes', 'LIKE', '%' . $keyword . '%');
            });
        }
        $query = $query->latest()->paginate(20);
        return $query;

    }
    public function assignedToMe(Request $request){
        $profile = HospitalStaff::where('userId',auth()->id())->first();
        $query = $this->appointment->with('staffs');
        $query = $query->whereHas('staffs', function ($appointments) use ($profile) {
                return $appointments->where('staffId',$profile->id);
        });
        if($request->date){
            $query = $query->whereDate('date',$request->date);
        }
        if($request->search){
            $keyword = $request->search;
            $query = $query->where(function ($qr) use ($keyword) {
                $qr->where('title', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('notes', 'LIKE', '%' . $keyword . '%');
            });
        }
        $query = $query->latest()->paginate(20);
        $appointments = $query;
        return view('lab.appointment.index',compact('appointments'));
    }
    public function show(Appointment $appointment){
        return view('lab.appointment.show', compact('appointment'));
    }
    //
    public function create(Patient $patient)
    {
        $appointment  =  new Appointment();
        $designations = HospitalDesignation::with('staffs')->get();
        $testType = TestType::where('publish',true)->get();
        return view('lab.appointment.form', compact('appointment', 'patient','designations','testType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppointmentRequest $request, Patient $patient, Appointment $appointment)
    {
        $data = $request->validated();
        $user = \Auth::user()->id;
        $currentcomp = UserCompany::where('user_id', $user)->where('is_selected', 1)->first();
        $company_id = $currentcomp->company_id;
        $branch_id = $currentcomp->branch_id;
        \DB::beginTransaction();

        try {
            foreach($data['startTime'] as $key => $test){
                $insert = [
                    // 'appointmentdate' => $test,
                    'startTime' => $data['startTime'][$key],
                    'endTime' => $data['endTime'][$key],
                    'notes' => $data['notes'][$key],
                    'type' => $data['type'][$key],
                    'patientId' => $request->patientId,
                    'createdBy' => auth()->id(),
                    'company_id' => $company_id,
                    'branch_id' => $branch_id,
                ];

                $staff = $data['staff'][$key + 1];
                $testType = $data['testType'][$key + 1];

            $appointment = Appointment::create($insert);
            $appointment->staffs()->sync($staff);
            $appointment->testTypes()->sync($testType);
            }
            // $appointment = Appointment::insert($insert);

            // dd($appointment);
            \DB::commit();
            request()->session()->flash('success',  'Appointment created Successfully');
            return redirect()->route('patients.show', $patient->id);
        } catch (\Throwable $th) {
            \DB::rollback();
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    protected function getStaff(){
        return HospitalStaff::get()->groupBy('designationId');
    }
    public function edit(Appointment $appointment){
        $designations = HospitalDesignation::with('staffs')->get();
        $testType = TestType::where('publish',true)->get();
        $staffId = $appointment->staffs->pluck('id')->toArray();
        $testId = $appointment->testTypes->pluck('id')->toArray();
        return view('lab.appointment.edit', compact('appointment','designations','testType','testId','staffId'));
    }
    public function update(Request $request,Appointment $appointment){
        $data =  \Validator::make($request->all(), [
            'testType' => ['nullable'],
            'staff' => ['nullable'],
            'appointmentdate' => ['nullable'],
            'startTime' => ['nullable'],
            'endTime' => ['nullable'],
            'notes' => ['nullable'],
            'type' => ['nullable'],
        ])->validated();
        \DB::beginTransaction();

        try {
                $update = [
                    // 'appointmentdate' => $test,
                    'startTime' => $data['startTime'],
                    'endTime' => $data['endTime'],
                    'notes' => $data['notes'],
                    'type' => $data['type'],
                ];

                $staff = $data['staff'];
                $testType = $data['testType'];

            $appointment->update($update);
            $appointment->staffs()->sync($staff);
            $appointment->testTypes()->sync($testType);

            \DB::commit();
            request()->session()->flash('success',  'Appointment created Successfully');
            return redirect()->route('patients.show', $appointment->patientId);
        } catch (\Throwable $th) {
            \DB::rollback();
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}

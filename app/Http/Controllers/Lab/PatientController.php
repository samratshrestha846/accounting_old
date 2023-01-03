<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Models\Lab\MedicalHistory;
use App\Models\Lab\Patient;
use App\Models\Lab\Appointment;
use App\Models\Lab\HospitalDesignation;
use App\Models\Lab\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $patients = Patient::query()
            ->when(request('search'), fn ($query) => $query->where('name', 'like', "%" . request('search') . '%')
                                                            ->orwhere('patientCode', 'like', "%" . request('search') . '%')
                                                            ->orwhere('number', 'like', "%" . request('search') . '%'))
            ->paginate(20);
        return view('lab.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $patient = new Patient();
        $designations = HospitalDesignation::with('staffs')->get();
        $testType = TestType::where('publish',true)->get();

        return view('lab.patients.form', compact('patient','designations','testType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatientRequest $request)
    {
        $data = $request->validated();
            \DB::beginTransaction();
        try {
            $patientData = [
                'patientCode' => $data['patientCode'],
                'name' => $data['name'],
                'address' => $data['address'],
                'email' => $data['email'],
                'number' => $data['number'],
                'date' => $data['date'],
                'gender' => $data['gender'],
                'publishStatus' => $data['publishStatus'],
                'description'=>$data['description'],
            ];

            $patient =  Patient::create($patientData);
            if(isset($data['startTime']) && !empty($data['startTime'][0])){
                foreach($data['startTime'] as $key => $test){
                    $insert = [
                        // 'appointmentdate' => $test,
                        'startTime' => $data['startTime'][$key],
                        'endTime' => $data['endTime'][$key],
                        'notes' => $data['notes'][$key],
                        'type' => $data['type'][$key],
                        'patientId' => $patient->id,
                        'createdBy' => auth()->id(),
                    ];

                    $staff = $data['staff'][$key + 1];
                    $testType = $data['testType'][$key + 1];

                $appointment = Appointment::create($insert);
                $appointment->staffs()->sync($staff);
                $appointment->testTypes()->sync($testType);
                }
            }


            \DB::commit();
            request()->session()->flash('success', 'Patient ' . $patient->name . '  created Successfully');
            return redirect()->route('patients.show', $patient->id);
        } catch (\Throwable $th) {
            \DB::rollback();
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
        $patient = Patient::withCount('history')->findorfail($id);
        // $history = MedicalHistory::query()
        //     ->select('medical_histories.*', 'users.name as doctorName')
        //     ->leftJoin('doctors', 'doctors.id', 'medical_histories.doctorId')
        //     ->leftJoin('users', 'users.id', 'doctors.userId')
        //     ->where('medical_histories.patientId', $patient->id)
        //     ->latest('medical_histories.id')
        //     ->paginate(20);
            $appointments = Appointment::query()->latest()->paginate(20);
        return view('lab.patients.show', compact('patient','appointments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = Patient::findorfail($id);
        $designations = HospitalDesignation::with('staffs')->get();
        $testType = TestType::where('publish',true)->get();
        return view('lab.patients.form', compact('patient','designations','testType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PatientRequest $request, $id)
    {
        $patient = Patient::findorfail($id);

        $data = $request->validated();
        try {
            $patient->update(Arr::except($data, 'patientCode'));
            request()->session()->flash('success', 'Patient ' . $patient->name . ' updated Successfully');
            return redirect()->route('patients.show', $patient->id);
        } catch (\Throwable $th) {
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
        $patient = Patient::findorfail($id);
        try {
            $patient->delete();
            request()->session()->flash('success', 'Patient ' . $patient->name . ' deleted Successfully');
            return redirect()->route('patients.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}

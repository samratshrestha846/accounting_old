<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalHistroyRequest;
use App\Models\Lab\HospitalStaff;
use App\Models\Lab\MedicalHistory;
use App\Models\Lab\Patient;

class MedicalHistoryController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Patient $patient)
    {
        $medicalHistory  =  new MedicalHistory();
        $staffs = $this->getUser();
        return view('lab.medicalhistory.form', compact('medicalHistory', 'patient', 'staffs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MedicalHistroyRequest $request, Patient $patient, MedicalHistory $medicalHistory)
    {
        $data = $request->validated();
        try {
            $medicalHistory = MedicalHistory::create($data);
            request()->session()->flash('success',  $medicalHistory->title . '  created Successfully');
            return redirect()->route('patients.show', $patient->id);
        } catch (\Throwable $th) {
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
    public function edit(Patient $patient, MedicalHistory $medicalHistory)
    {
        $staffs = $this->getUser();
        return view('lab.medicalhistory.form', compact('medicalHistory', 'patient', 'staffs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MedicalHistroyRequest $request, Patient $patient, MedicalHistory $medicalHistory)
    {
        $data = $request->validated();
        try {
            $medicalHistory->update($data);
            request()->session()->flash('success',  $medicalHistory->title . ' updated Successfully');
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
    public function destroy(Patient $patient, MedicalHistory $medicalHistory)
    {
        try {
            $medicalHistory->delete();
            request()->session()->flash('success',  $medicalHistory->title . ' deleted Successfully');
            return redirect()->route('patients.show', $patient->id);
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    private function getUser()
    {
        return HospitalStaff::query()
            ->select('id', 'name')
            ->get()
            ->toArray();
    }
}

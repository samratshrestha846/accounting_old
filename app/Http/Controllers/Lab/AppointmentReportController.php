<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AppointmentReportRequest;
use App\Models\Lab\AppointmentReport;
use App\Models\Lab\Appointment;
use File;

class AppointmentReportController extends Controller
{
    //
    public function store(AppointmentReportRequest $request){
        $data = $request->validated();
        $user = \Auth::user()->id;
        try {

            foreach($data['report'] as$key => $item){
                $insert[] = [
                    'report' => $this->uploadFile($item,'hospital report'),
                    'notes' => $data['notes'][$key],
                    'appointmentId' => $request->appointmentId,
                    'addedBy' => $user,
                ];
            }
            $report = AppointmentReport::insert($insert);
            Appointment::where('id', $request->appointmentId)->update([
                'status' => true,
            ]);


            request()->session()->flash('success',  'Report submitted Successfully');
            return redirect()->route('appointment.show',$request->appointmentId);
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    protected function uploadFile($file, $dir)
    {
        $path = public_path() . '/' . $dir;
        if (!File::exists($path)) {
            File::makedirectory($path, 0777, true, true);
        }
        if (!$file->getClientOriginalExtension()) {
            return false;
        }


        $file_name =  date("ymdhis") . "." . $file->getClientOriginalExtension();

        if ($file_name) {
            $success = $file->move($path, $file_name);
            if ($success) :
                return $dir . '/' . $file_name;
            else :
                return false;
            endif;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\Staff;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;

class AttendanceController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        //
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'manage-attendance' ) ) {
            $staffs = Staff::latest()->where( 'status', 1 )->get();
            $date = date( 'Y-m-d' );
            $attendances = Attendance::where( 'date', $date )->with( 'staff' )->get();
            return view( 'backend.attendance.record', compact( 'staffs', 'attendances' ) );
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

    public function store( Request $request ) {
        for ( $i = 0 ; $i < count( $request['staffname'] ); $i++ ) {
            $number = $request['staffname'][$i];
            $entry_time = $request['time'.$number];
            $present = 0;
            $paid_leave = 0;
            $unpaid_leave = 0;

            if ( $request[$number] == 1 ) {
                $present = 1;
            } elseif ( $request[$number] == 2 ) {
                $paid_leave = 1;
            } elseif ( $request[$number] == 3 ) {
                $unpaid_leave = 1;
            }

            $attendance = Attendance::create( [
                'staff_id' => $number,
                'date' => date( 'Y-m-d' ),
                'monthyear' => date( 'F, Y' ),
                'present' => $present,
                'paid_leave' => $paid_leave,
                'unpaid_leave' => $unpaid_leave,
                'entry_time' => $entry_time
            ] );
            $attendance->save();
        }
        return redirect()->route( 'attendance.create' )->with( 'success', "Today's attendance record sucessfully recorded." );
    }

    public function updateexit( Request $request ) {
        if ( $request->user()->can( 'manage-attendance' ) ) {
            foreach ( $request['attendances'] as $attendance ) {
                $attend = Attendance::findorFail( $attendance );
                $attend->update( [
                    'exit_time' => $request['exit_time'.$attendance]
                ] );
            }
            return redirect()->route( 'attendance.create' )->with( 'success', 'Exit Time updated successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Attendance  $attendance
    * @return \Illuminate\Http\Response
    */

    public function show() {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Attendance  $attendance
    * @return \Illuminate\Http\Response
    */

    public function edit( $id, Request $request ) {
        if ( $request->user()->can( 'manage-attendance' ) ) {
            $attendance = Attendance::findorFail( $id );
            return view( 'backend.attendance.edit', compact( 'attendance' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Attendance  $attendance
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        $attendance = Attendance::findorFail( $id );

        $this->validate( $request, [
            'attendance' => 'required',
            'entry_time' => '',
            'exit_time' => '',
        ] );

        if ( $request['attendance'] == 'present' ) {
            $attendance->update( [
                'present' => 1,
                'paid_leave' => 0,
                'unpaid_leave' => 0,
            ] );
        } elseif ( $request['attendance'] == 'paid_leave' ) {
            $attendance->update( [
                'present' => 0,
                'paid_leave' => 1,
                'unpaid_leave' => 0,
            ] );
        } elseif ( $request['attendance'] == 'unpaid_leave' ) {
            $attendance->update( [
                'present' => 0,
                'paid_leave' => 0,
                'unpaid_leave' => 1,
            ] );
        }
        $attendance->update( [
            'entry_time' => $request['entry_time'],
            'exit_time' => $request['exit_time'],
        ] );

        return redirect()->route( 'attendance.create' )->with( 'success', 'Staff Attendance updated successfully.' );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Attendance  $attendance
    * @return \Illuminate\Http\Response
    */

    public function destroy() {
        //
    }

    public function report( Request $request ) {
        if ( $request->user()->can( 'manage-attendance-report' ) ) {
            $date = date( 'F, Y' );
            $staff = Staff::latest()->where( 'status', 1 )->first();
            $staffs = Staff::latest()->where( 'status', 1 )->get();
            if ( $staff ) {
                $thismonthattendance = Attendance::where( 'monthyear', $date )->where( 'staff_id', $staff->id )->get();
                return view( 'backend.attendance.report', compact( 'thismonthattendance', 'staffs' ) );
            } else {
                return redirect()->route( 'staff.index' )->with( 'failure', 'Please Create Staff first!' );
            }
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function reportgenerator( Request $request ) {
        if ( $request->user()->can( 'manage-attendance-report' ) ) {
            $data = $this->validate( $request, [
                'monthyear' => 'required',
                'staff' => 'required'
            ] );

            $datetoselect = date( 'F, Y', strtotime( $data['monthyear'] ) );
            $staffinfo = $request['staff'];
            $requireattendance = Attendance::where( 'monthyear', $datetoselect )->get();
            $staffs = Staff::latest()->where( 'status', 1 )->get();

            $single_staff = Staff::where( 'status', 1 )->first();
            $working_days = Attendance::where( 'staff_id', $single_staff->id )->where( 'monthyear', $datetoselect )->get()->count();

            if ( $staffinfo == 'all' ) {
                return view( 'backend.attendance.result', compact( 'staffinfo', 'requireattendance', 'staffs', 'datetoselect', 'working_days' ) );
            } else {
                $singleattendance = Attendance::where( 'monthyear', $datetoselect )->where( 'staff_id', $request['staff'] )->orderBy( 'date', 'ASC' )->get();
                return view( 'backend.attendance.result', compact( 'staffinfo', 'staffs', 'singleattendance', 'requireattendance', 'datetoselect', 'working_days' ) );
            }
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function leftattendance( Request $request ) {
        if ( $request->user()->can( 'manage-attendance' ) ) {
            $data = $this->validate( $request, [
                'date'=>'required',
            ] );

            $date = date( 'Y-m-d', strtotime( $data['date'] ) );
            $attendance = Attendance::where( 'date', $date )->get();
            $staffs = Staff::latest()->where( 'status', 1 )->get();
            return view( 'backend.attendance.leftattendance', compact( 'staffs', 'attendance', 'date' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function storeleftattendance( Request $request ) {
        if ( $request->user()->can( 'manage-attendance' ) ) {
            for ( $i = 0 ; $i < count( $request['staffname'] );
            $i++ ) {
                $number = $request['staffname'][$i];
                $entry_time = $request['entry_time'.$number];
                $exit_time = $request['exit_time'.$number];
                $date = $request['date'];
                $monthyear = date( 'F, Y', strtotime( $request['date'] ) );
                $present = 0;
                $paid_leave = 0;
                $unpaid_leave = 0;

                if ( $request[$number] == 1 ) {
                    $present = 1;
                } elseif ( $request[$number] == 2 ) {
                    $paid_leave = 1;
                } elseif ( $request[$number] == 3 ) {
                    $unpaid_leave = 1;
                }

                $attendance = Attendance::create( [
                    'staff_id' => $number,
                    'date' => $date,
                    'monthyear' => $monthyear,
                    'present' => $present,
                    'paid_leave' => $paid_leave,
                    'unpaid_leave' => $unpaid_leave,
                    'entry_time' => $entry_time,
                    'exit_time' => $exit_time,
                ] );

                $attendance->save();
            }
            return redirect()->back()->with( 'success', 'Attendance left out is done.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function storeOvertime( Request $request ) {
        if ( $request->user()->can( 'manage-attendance' ) ) {
            $attendance = Attendance::findorFail( $request['overtime_staff'] );
            $attendance->update( [
                'overtime' => $request['overtime_hours'].':'.$request['overtime_minutes']
            ] );

            return redirect()->back()->with( 'success', 'Overtime information is saved successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }


    // Payroll
    public function payrollIndex( Request $request ) {
        if ( $request->user()->can( 'manage-payroll' ) ) {
            $engtoday = date( 'Y-m-d' );
            $neptoday = datenep( $engtoday );
            $monthint = explode( '-', $neptoday );
            $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];

            $nepdate = ( int )$monthint[1] - 1;
            $nepmonth = $monthname[$nepdate];

            $today = $nepmonth . ', ' . $monthint[0];
            $payrolls = Payroll::latest()->where( 'monthyear', $today )->paginate( 10 );
            return view( 'backend.payroll.index', compact( 'payrolls', 'today', 'neptoday' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function payroll(Request $request) {
        if ( $request->user()->can( 'manage-payroll' ) ) {
            $staffs = Staff::latest()->where( 'status', 1 )->get();
            return view( 'backend.payroll.create', compact( 'staffs' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function paymentInfo( $id ) {
        $last_payment = Payroll::latest()->where( 'staff_id', $id )->first();
        return response()->json( $last_payment );
    }

    public function savePayroll( Request $request ) {
        $this->validate( $request, [
            'staff' => 'required',
            'payment_date_nepali' => 'required',
            'amount_paid' => 'required',
            'payment_type' => 'required'
        ] );

        $monthint = explode( '-', $request['payment_date_nepali'] );
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];

        $nepdate = ( int )$monthint[1] - 1;
        $nepmonth = $monthname[$nepdate];

        $monthyear = $nepmonth . ', ' . $monthint[0];

        $payroll = Payroll::create( [
            'staff_id' => $request['staff'],
            'nepali_date' => $request['payment_date_nepali'],
            'date' => $request['payment_date'],
            'amount_paid' => $request['amount_paid'],
            'advance_regular' => $request['payment_type'],
            'monthyear' => $monthyear
        ] );

        $payroll->save();
        return redirect()->route( 'payrollIndex' )->with( 'success', 'Payroll Information saved successfully.' );
    }

    public function editPayroll( $id, Request $request ) {
        if ( $request->user()->can( 'manage-payroll' ) ) {
            $payroll = Payroll::findorFail( $id );
            $staffs = Staff::latest()->where( 'status', 1 )->get();
            return view( 'backend.payroll.edit', compact( 'payroll', 'staffs' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function updatePayroll( Request $request, $id ) {
        $payroll = Payroll::findorFail( $id );

        $this->validate( $request, [
            'staff' => 'required',
            'payment_date_nepali' => 'required',
            'amount_paid' => 'required',
            'payment_type' => 'required'
        ] );

        $monthint = explode( '-', $request['payment_date_nepali'] );
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];

        $nepdate = ( int )$monthint[1] - 1;
        $nepmonth = $monthname[$nepdate];

        $monthyear = $nepmonth . ', ' . $monthint[0];

        $payroll->update( [
            'staff_id' => $request['staff'],
            'nepali_date' => $request['payment_date_nepali'],
            'date' => $request['payment_date'],
            'amount_paid' => $request['amount_paid'],
            'advance_regular' => $request['payment_type'],
            'monthyear' => $monthyear
        ] );

        return redirect()->route( 'payrollIndex' )->with( 'success', 'Payroll Information updated successfully.' );
    }

    public function searchPayroll( Request $request ) {
        $search = $request->input( 'search' );
        $payrolls = Payroll::query()
        ->where( 'nepali_date', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.payroll.search', compact( 'payrolls', 'search' ) );
    }

    public function deletePayroll( $id ) {
        $payroll = Payroll::findorFail( $id );
        $payroll->delete();

        return redirect()->route( 'payrollIndex' )->with( 'success', 'Payroll Information deleted successfully.' );
    }

    public function payrollReport() {
        $engtoday = date( 'Y-m-d' );
        $neptoday = datenep( $engtoday );
        $staffs = Staff::latest()->where('status', 1)->get();
        return view('backend.payroll.reportfilter', compact('neptoday', 'staffs'));
    }

    public function printSinglePayroll($id)
    {
        $payroll = Payroll::findorfail($id);
        $currentcomp = UserCompany::with('company')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

        $opciones_ssl=array(
            "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
            ),
        );

        $img_path = 'uploads/' . $currentcomp->company->company_logo;
        $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
        $data = file_get_contents($img_path, false, stream_context_create($opciones_ssl));
        $img_base_64 = base64_encode($data);
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'portrait')->loadView('backend.payroll.singlePayrollPrint', compact('payroll', 'currentcomp', 'path_img'));
        return $pdf->download($payroll->staff->name.'_payroll.pdf');
    }

    public function generatePayrollReport(Request $request)
    {
        $engtoday = date( 'Y-m-d' );
        $neptoday = datenep( $engtoday );
        $starting_date = $request['starting_date'];
        $ending_date = $request['ending_date'];
        $start_date = dateeng($request['starting_date']);
        $end_date = dateeng($request['ending_date']);
        $staff = Staff::findorFail($request['staff']);

        $staffs = Staff::latest()->where('status', 1)->get();

        if ($start_date > $end_date) {
            return redirect()->back()->with('error', 'Ending date should be greater than starting date.');
        }

        $payrolls = Payroll::latest()->where('staff_id', $request['staff'])->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();

        return view('backend.payroll.generatedReport', compact('starting_date', 'ending_date', 'payrolls', 'staff', 'staffs', 'neptoday'));
    }
}

@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Staff Attendance </h1>
                    <div class="btn-bulk">
                        <a href="#" data-toggle='modal' data-target='#attendance_import' data-toggle='tooltip'
                            data-placement='top' class="global-btn" title="Import Attendance">Import (CSV)</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->

            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert  alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card mb-3">
                    <div class="card-header">
                        <h2>Enter Previous Attendance</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('leftattendance') }}" method="GET" class="row">
                            @csrf
                            @method("GET")
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">When was attendance left out?</label>
                                    <input type="date" name="date" class="form-control">
                                </div>
                                @error('date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="">&nbsp;</label>
                                <button type="submit" class="btn btn-primary">Enter attendance</button>
                            </div>
                        </form>
                    </div>
                </div>

                @php
                    $attendance = DB::table('attendances')
                        ->where('date', date('Y-m-d'))
                        ->get();
                @endphp

                @if (count($attendance) == 0)
                    <div class="card">
                        <div class="card-header">
                            <h2>Record Today's Attendance ({{ date('F j, Y') }})</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive text-center">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Staff Name</th>
                                            <th>Present</th>
                                            <th>Paid Leave</th>
                                            <th>Unpaid Leave</th>
                                            <th>Entry Time</th>
                                        </tr>
                                    </thead>
                                    <form action="{{ route('attendance.store') }}" method="POST">
                                        @csrf
                                        @method('POST')

                                        @foreach ($staffs as $staff)
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        {{ $staff->fullName }}
                                                        <input type="hidden" name="staffname[]" value="{{ $staff->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="1"
                                                            style="font-size: 5px;" name="{{ $staff->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="2"
                                                            style="font-size: 5px;" name="{{ $staff->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="3"
                                                            style="font-size: 5px;" name="{{ $staff->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control"
                                                            name="time{{ $staff->id }}">
                                                    </td>
                                                </tr>

                                            </tbody>
                                        @endforeach
                                </table>
                                <button type="submit" class="btn btn-secondary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                @elseif(count($attendance) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h2>Record Today's Attendance ({{ date('F j, Y') }})</h2>
                        </div>
                        <div class="card-body mid-body text-center">
                            <p style="margin-bottom:0;">(Attendance for today is done)</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive text-center">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Staff Name</th>
                                            <th>Present</th>
                                            <th>Paid Leave</th>
                                            <th>Unpaid Leave</th>
                                            <th>Entry Time</th>
                                            <th>Exit Time</th>
                                        </tr>
                                    </thead>
                                    <form action="{{ route('updateexit') }}" method="POST">
                                        @csrf
                                        @method('POST')

                                        @foreach ($attendance as $staffattendance)
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        @php
                                                            $staff = DB::table('staff')
                                                                ->latest()
                                                                ->where('id', $staffattendance->staff_id)
                                                                ->first();
                                                        @endphp
                                                        {{ $staff->name }}
                                                        <input type="hidden" name="attendances[]"
                                                            value="{{ $staffattendance->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="1"
                                                            {{ $staffattendance->present == 1 ? 'checked' : '' }}
                                                            style="font-size: 5px;" name="{{ $staff->id }}" disabled>
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="2"
                                                            {{ $staffattendance->paid_leave == 1 ? 'checked' : '' }}
                                                            style="font-size: 5px;" name="{{ $staff->id }}" disabled>
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="3"
                                                            {{ $staffattendance->unpaid_leave == 1 ? 'checked' : '' }}
                                                            style="font-size: 5px;" name="{{ $staff->id }}" disabled>
                                                    </td>
                                                    <td>
                                                        @if ($staffattendance->entry_time == null)
                                                            -
                                                        @else
                                                            {{ date('h:i a', strtotime($staffattendance->entry_time)) }}
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if ($staffattendance->exit_time == null && $staffattendance->present == 1)
                                                            <input type="time" class="form-control"
                                                                name="exit_time{{ $staffattendance->id }}">
                                                        @elseif($staffattendance->paid_leave == 1 ||
                                                            $staffattendance->unpaid_leave == 1 ||
                                                            $staffattendance->exit_time == null)
                                                            -
                                                            <input type="hidden" class="form-control"
                                                                name="exit_time{{ $staffattendance->id }}" value="">
                                                        @elseif($staffattendance->exit_time != null)
                                                            {{ date('h:i a', strtotime($staffattendance->exit_time)) }}
                                                            <input type="hidden" class="form-control"
                                                                name="exit_time{{ $staffattendance->id }}"
                                                                value="{{ $staffattendance->exit_time }}">
                                                        @endif
                                                    </td>
                                                </tr>

                                            </tbody>
                                        @endforeach
                                </table>
                                <button type="submit" class="btn btn-secondary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                @elseif($exit == 0)
                    {{ dd($exit) }}
                @endif

                <div class="card mt-3">
                    <div class="card-header">
                        <h2>Today's Attendance ({{ date('F j, Y') }})</h2>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Staff Name</th>
                                    <th class="text-center">Attendance Status</th>
                                    <th class="text-center">Entry Time</th>
                                    <th class="text-center">Exit Time</th>
                                    <th class="text-center">Overtime (Hours:Minutes)</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($attendances) == 0)
                                    <tr>
                                        <td colspan="6">
                                            <h5>Attendances not recorded for today yet.</h5>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($attendances as $attendance)
                                        <tr>
                                            <td>{{ $attendance->staff->name }}</td>
                                            <td>
                                                @if ($attendance->present == 1)
                                                    Present
                                                @elseif($attendance->paid_leave == 1)
                                                    On Paid Leave
                                                @elseif($attendance->unpaid_leave == 1)
                                                    Absent
                                                @endif
                                            </td>
                                            <td>
                                                @if ($attendance->entry_time == null)
                                                    -
                                                @else
                                                    {{ date('h:i a', strtotime($attendance->entry_time)) }}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($attendance->exit_time == null)
                                                    -
                                                @else
                                                    {{ date('h:i a', strtotime($attendance->exit_time)) }}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($attendance->overtime == null)
                                                    -
                                                @else
                                                    {{ $attendance->overtime }}
                                                @endif
                                            </td>
                                            <td style="width: 120px;">
                                                <div class="btn-bulk justify-content-center">
                                                    <a href='{{ route('attendance.edit', $attendance->id) }}'
                                                        class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i
                                                            class='fa fa-edit'></i></a>
                                                    <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal'
                                                        data-target='#enterovertime{{ $attendance->id }}'
                                                        data-toggle='tooltip' data-placement='top' title='Enter Overtime'><i
                                                            class='fa fa-clock'></i></button>
                                                </div>
                                                <!-- Modal -->
                                                <div class='modal fade text-left' id='enterovertime{{ $attendance->id }}'
                                                    tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel'
                                                    aria-hidden='true'>
                                                    <div class='modal-dialog' role='document'>
                                                        <div class='modal-content'>
                                                            <div class='modal-header'>
                                                                <h5 class='modal-title' id='exampleModalLabel'>Enter
                                                                    Overtime Information</h5>
                                                                <button type='button' class='close' data-dismiss='modal'
                                                                    aria-label='Close'>
                                                                    <span aria-hidden='true'>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class='modal-body text-center'>
                                                                <form action='{{ route('storeOvertime') }}' method='POST'
                                                                    style='display:inline-block;'>
                                                                    @csrf
                                                                    @method("POST")
                                                                    <div class="form-group">
                                                                        <label for="">Enter Overtime Hours:</label>
                                                                        <input type="hidden" name="overtime_staff"
                                                                            value="{{ $attendance->id }}">
                                                                        <input type="number" step="any"
                                                                            name="overtime_hours" class="form-control"
                                                                            placeholder="Overtime hours??">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="">Enter Overtime Minutes:</label>
                                                                        <input type="number" step="any"
                                                                            name="overtime_minutes" class="form-control"
                                                                            placeholder="Overtime minutes??">
                                                                    </div>
                                                                    <button type='submit'
                                                                        class='btn btn-primary'>Submit</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        <div class='modal fade text-left' id='attendance_import' tabindex='-1' role='dialog'
                            aria-labelledby='exampleModalLabel' aria-hidden='true'>
                            <div class='modal-dialog' role='document' style="max-width: 800px;">
                                <div class='modal-content'>
                                    <div class='modal-header text-center'>
                                        <h2 class='modal-title' id='exampleModalLabel'>Select Attendance file (CSV)</h2>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                    <div class='modal-body'>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method("POST")
                                            <div class="row">

                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label for="attendance_csv_file">CSV file<i
                                                                class="text-danger">*</i> </label>
                                                        <input type="file" name="attendance_csv_file" class="form-control"
                                                            required>
                                                        <p class="text-danger">
                                                            {{ $errors->first('attendance_csv_file') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-success btn-sm">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')

@endpush

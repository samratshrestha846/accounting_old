@extends('backend.layouts.app')
@push('styles')
    <style>
        * {
            box-sizing: border-box;
        }

        #myInput {
            background-image: url('/uploads/search.png');
            background-position: 10px 10px;
            background-repeat: no-repeat;
            width: 100%;
            font-size: 13px;
            padding: 12px 20px 12px 40px;
            border: 1px solid #e1e6eb;
            margin-bottom: 12px;
        }

    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content mt">
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

                {{-- <h2 class="text-center">Total working days for this month ({{date('F')}}) = 30</h2> --}}

                <div class="card">
                    <div class="card-header">
                        <h2>Monthly Attendance Report</h2>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('reportgenerator') }}" method="GET">
                            @csrf
                            @method('GET')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="month">Select a year and month to filter:</label>
                                        @php
                                            $today = date('Y-m');
                                        @endphp
                                        <input type="month" name="monthyear" value="{{ $today }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="staffs">Select a staff</label>
                                        <select name="staff" class="form-control" required>
                                            <option value="">--Select a staff--</option>
                                            @foreach ($staffs as $staff)
                                                <option value="{{ $staff->id }}">{{ $staff->fullName }}</option>
                                            @endforeach
                                            <option value="all">All Report</option>
                                        </select>
                                        @error('staff')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-secondary">Generate Report</button>
                                </div>
                            </div>
                        </form>

                        <hr>

                        {{-- {{dd(count($requireattendance))}} --}}

                        @if (count($requireattendance) == 0)
                            <div class="col-md-12 mt-5 text-center">
                                <p style="font-size: 20px;">Sorry, We could't find the data.</p>
                            </div>

                        @elseif($staffinfo == 'all')

                            <h3 class="text-center mt-3">Monthly Attendance Report for the month {{ $datetoselect }}</h3>
                            <h4 class="text-center mt-3">Total days worked in {{ $datetoselect }} = {{ $working_days }}
                            </h4>

                            <div class="row mt-3">
                                <div class="div col-9"></div>
                                <div class="div col-3">
                                    <input type="text" id="myInput" onkeyup="myFunction()"
                                        placeholder="Search for staff names.." title="Type in a name">
                                </div>
                            </div>

                            <div class="table-responsive text-center mt-2">
                                <table class="table table-bordered" id="myTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Staff Name</th>
                                            <th>Present (Days)</th>
                                            <th>Paid Leave (Days)</th>
                                            <th>Unpaid Leave (Days)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($staffs as $staff)
                                            @php
                                                $presentdays = 0;
                                                $paid_leave = 0;
                                                $unpaid_leave = 0;
                                                $monthlyinfo = DB::table('attendances')
                                                    ->where('staff_id', $staff->id)
                                                    ->where('monthyear', $datetoselect)
                                                    ->get();
                                                foreach ($monthlyinfo as $info) {
                                                    $presentdays = $presentdays + $info->present;
                                                    $paid_leave = $paid_leave + $info->paid_leave;
                                                    $unpaid_leave = $unpaid_leave + $info->unpaid_leave;
                                                }
                                            @endphp

                                            <tr>
                                                <td>{{ $staff->fullName }}</td>
                                                <td>{{ $presentdays }}</td>
                                                <td>{{ $paid_leave }}</td>
                                                <td>{{ $unpaid_leave }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            @php
                                $staff = DB::table('staff')
                                    ->where('id', $staffinfo)
                                    ->first();
                            @endphp
                            <h3 class="text-center mt-5">Monthly Attendance Report Of {{ $staff->name }}</h3>
                            <h3 class="text-center">For the month {{ $datetoselect }}</h3>
                            <h4 class="text-center mt-3">Total days worked in {{ $datetoselect }} =
                                {{ $working_days }}</h4>

                            <div class="table-responsive text-center mt-4">
                                <table class="table" id="myTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Report</th>
                                            <th>Arrival Time</th>
                                            <th>Exit Time</th>
                                            <th>Overtime (Hours:Minutes)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($singleattendance as $attendance)
                                            <tr>
                                                <td>{{ date('F j, Y', strtotime($attendance->date)) }}</td>
                                                <td>
                                                    @if ($attendance->present == 1)
                                                        Present
                                                    @elseif($attendance->paid_leave == 1)
                                                        Paid Leave
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')

    <script>
        function myFunction() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

@endpush

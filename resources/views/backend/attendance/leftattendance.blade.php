@extends('backend.layouts.app')
@push('styles')
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        {{-- <h1 class="m-0">New Bank Info </h1> --}}
                    </div><!-- /.col -->
                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Attendance</li>
                        </ol>
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
                    <div class="col-sm-12">
                        <div class="alert  alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="col-sm-12">
                        <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif

                @if (count($attendance) == 0)
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Record Attendance for {{date('F j, Y', strtotime($date))}}</h3>
                            <p>(Attendance was left out)</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive text-center">
                                <table class="table">
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
                                    <form action="{{route('storeleftattendance')}}" method="POST">
                                        @csrf
                                        @method("POST")

                                        @foreach ($staffs as $staff)
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        {{$staff->fullName}}
                                                        <input type="hidden" name="staffname[]" value="{{$staff->id}}">
                                                        <input type="hidden" name="date" value="{{$date}}">
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="1" style="font-size: 5px;" name="{{$staff->id}}">
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="2" style="font-size: 5px;" name="{{$staff->id}}">
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="3" style="font-size: 5px;" name="{{$staff->id}}">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" name="entry_time{{$staff->id}}">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" name="exit_time{{$staff->id}}">
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
                        <div class="card-header text-center">
                            <h3>Attendance for {{date('F j, Y', strtotime($date))}}</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive text-center">
                                <table class="table">
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
                                        @foreach ($attendance as $staffattendance)
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        @php
                                                            $staff = DB::table('staff')->latest()->where('id', $staffattendance->staff_id)->first();
                                                        @endphp
                                                        {{$staff->name}}
                                                        <input type="hidden" name="attendances[]" value="{{$staffattendance->id}}">
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="1"{{$staffattendance->present == 1?'checked':''}} style="font-size: 5px;" name="{{$staff->id}}" disabled>
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="2"{{$staffattendance->paid_leave == 1?'checked':''}} style="font-size: 5px;" name="{{$staff->id}}" disabled>
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="form-control" value="3"{{$staffattendance->unpaid_leave == 1?'checked':''}} style="font-size: 5px;" name="{{$staff->id}}" disabled>
                                                    </td>
                                                    <td>
                                                            @if($staffattendance->entry_time == null)
                                                                -
                                                            @else
                                                                {{date('h:i a', strtotime($staffattendance->entry_time))}}
                                                            @endif

                                                    </td>
                                                    <td>
                                                        @if($staffattendance->exit_time == null && $staffattendance->present == 1)
                                                            <input type="time" class="form-control" name="exit_time{{$staffattendance->id}}">
                                                        @elseif($staffattendance->paid_leave == 1 || $staffattendance->unpaid_leave == 1 || $staffattendance->exit_time == null)
                                                            -
                                                            <input type="hidden" class="form-control" name="exit_time{{$staffattendance->id}}" value="">
                                                        @elseif($staffattendance->exit_time != null)
                                                            {{date('h:i a', strtotime($staffattendance->exit_time))}}
                                                            <input type="hidden" class="form-control" name="exit_time{{$staffattendance->id}}" value="{{$staffattendance->exit_time}}">
                                                        @endif
                                                    </td>
                                                </tr>

                                            </tbody>
                                        @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    @elseif($exit == 0)
                        {{dd($exit)}}
                @endif
            </div>
        </section>

    </div>
@endsection
@push('scripts')

@endpush

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
                    <h1>Appointment Detail </h1>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h2>Patient Info</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b> Name: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $appointment->patient->name }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b> Code: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ @$appointment->patient->patientCode }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b> Phone: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $appointment->patient->number }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b> Email: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $appointment->patient->email }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b> Address: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $appointment->patient->address }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">

                        <div class="card">
                            <div class="card-header">
                                <h2>Appointment's Information</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Id: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">#{{ $appointment->id }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Test Type: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">@foreach ($appointment->testTypes as $test)
                                            <span class="badge badge-primary">{{ $test->title }}</span>
                                        @endforeach</p>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Date Time: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $appointment->startTime->format('F d, Y h:i a') }} -
                                            {{ $appointment->endTime->format('F d, Y h:i a') }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Status: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">
                                            {{ $appointment->status == 0 ? 'pending' : 'completed' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Notes: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $appointment->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="row">
                    @if ($appointment->report->count() > 0)
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Report</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="appointment">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center"> Report<i class="text-danger">*</i>
                                                        </th>
                                                        <th class="text-center"> Notes (if any)</th>

                                                    </tr>
                                                </thead>
                                                <tbody id="appoint">
                                                    @foreach ($appointment->report as $report)
                                                        <tr class="test">
                                                            <td>
                                                                <a href="{{asset($report->report)}}"  download="{{$report->report}}">{{ basename($report->report) }}</a>
                                                            </td>
                                                            <td>
                                                                {{ $report->notes }}
                                                            </td>


                                                        </tr>
                                                    @endforeach

                                                </tbody>


                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <form action="{{ route('appointment-report.store') }}" method="post" enctype='multipart/form-data'>
                            @csrf
                            <input type="hidden" value="{{ $appointment->id }}" name="appointmentId">
                            <div class="card">
                                <div class="card-header">Report Form</div>
                                <div class="card-body">
                                    <div class="row">
                                        @if (session('success'))
                                            <div class="alert  alert-success alert-dismissible fade show" role="alert">
                                                {{ session('success') }}
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endif
                                        @if (session('error'))
                                            <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                                                {{ session('error') }}
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endif
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="appointment">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center"> Report<i class="text-danger">*</i>
                                                        </th>
                                                        <th class="text-center"> Notes (if any)</th>

                                                    </tr>
                                                </thead>
                                                <tbody id="appoint">
                                                    <tr class="test">
                                                        <td>
                                                            <input type="file" name="report[]" class="form-control"
                                                                id="report_1" required>
                                                        </td>
                                                        <td>
                                                            <textarea name="notes[]" id="" cols="20" rows="2"></textarea>

                                                        </td>


                                                    </tr>
                                                </tbody>


                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-footer d-flex">
                                    <button type="submit" class="btn btn-primary">Add Report</button>
                                    <button type="Reset" class="btn btn-dark ml-2">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
    </div>
    </div>

    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')

@endpush

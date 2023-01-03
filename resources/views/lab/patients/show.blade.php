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
                    <h1>Patients </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('patients.create') }}" class="global-btn">Add New patients</a>
                        <a href='{{ route('patients.edit', $patient->id) }}' class='global-btn' title='Edit'>Edit Patient</a>
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">S.n</th>
                                            <th class="text-nowrap">FullName</th>
                                            <th class="text-nowrap">Address</th>
                                            <th class="text-nowrap">Email</th>
                                            <th class="text-nowrap">Phone</th>
                                            <th class="text-nowrap">Reason for Visit</th>
                                            {{-- <th class="text-nowrap">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ 1 }}</td>
                                            <td>{{ $patient->name }}</td>
                                            <td>{{ $patient->address }}</td>
                                            <td>{{ $patient->email }}</td>
                                            <td>{{ $patient->number }}</td>
                                            <td>{{ $patient->description }}</td>
                                            {{-- <td style="width: 120px;">
                                                <div class="btn-bulk justify-content-center">
                                                    <a href='{{ route('patients.edit', $patient->id) }}'
                                                        class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i
                                                            class='fa fa-edit'></i></a>
                                                    @include('lab.includes._modal',['id'=>$patient->id,'route'=>route('patients.destroy',$patient->id)])
                                                </div>
                                            </td> --}}
                                        </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>Appointment History of {{ $patient->name }}</h3>
                        <div class="btn-bulk ">

                                <a href="{{ route('appointment.create', ['patient' => $patient->id]) }}"
                                    class="global-btn">Add New Appointment</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">S.N</th>
                                            <th class="text-nowrap">Test Type</th>
                                            <th class="text-nowrap">Assigned To</th>
                                            <th class="text-nowrap">Date Time</th>
                                            <th class="text-nowrap">Status</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($patient->appointments as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>@foreach ($item->testTypes as $test)
                                                    <span class="badge badge-info">{{ $test->title }}</span>
                                                @endforeach</td>
                                                <td>@foreach ($item->staffs as $staff)
                                                    <span class="badge badge-info">{{ $staff->name }}</span>
                                                @endforeach</td>
                                                <td>{{ $item->startTime->format('F d, Y h:i a') }}  {{ ($item->endTime != null) ? '-'.$item->endTime->format('F d, Y h:i a') : "" }}</td>
                                                <td>{{ $item->status == 0 ? 'pending' : 'completed' }}</td>
                                                <td style="width: 120px;">
                                                    @if ($item->status == 0)
                                                    <div class="btn-bulk justify-content-center">
                                                        <a href='{{ route('appointment.edit', $item->id) }}'
                                                            class='edit btn btn-primary icon-btn btn-sm' title='Edit Appointment'><i
                                                                class='fa fa-pen'></i></a>
                                                    </div>
                                                    @endif
                                                    <div class="btn-bulk justify-content-center">
                                                        <a href='{{ route('appointment.show', $item->id) }}'
                                                            class='edit btn btn-primary icon-btn btn-sm' title='View Appointment'><i
                                                                class='fa fa-eye'></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">No Prior Appointment History</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                    <tfoot>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="text-sm">
                                                        Showing <strong>{{ $appointments->firstItem() }}</strong> to
                                                        <strong>{{ $appointments->lastItem() }} </strong> of <strong>
                                                            {{ $appointments->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $appointments->links() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>Medical History of {{ $patient->name }}</h3>
                        <div class="btn-bulk ">
                            <a href="{{ route('medical-history.create', ['patient' => $patient->id]) }}"
                                class="global-btn">Add New History</a>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">S.n</th>
                                            <th class="text-nowrap">Doctor</th>
                                            <th class="text-nowrap">StartDate</th>
                                            <th class="text-nowrap">endDate</th>
                                            <th class="text-nowrap">prescription</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($history as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->doctorName }}</td>
                                                <td>{{ $item->startDate }}</td>
                                                <td>{{ $item->endDate }}</td>
                                                <td>{{ $item->prescription }}</td>
                                                <td style="width: 120px;">
                                                    <div class="btn-bulk justify-content-center">
                                                        <a href='{{ route('medical-history.edit', ['medicalHistory' => $item->id, 'patient' => $patient->id]) }}'
                                                            class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i
                                                                class='fa fa-edit'></i></a>
                                                        @include('lab.includes._modal',['id'=>$item->id.'-'.$item->id,'route'=>route('medical-history.destroy',['medicalHistory'=>$item->id,'patient'=>$patient->id])])
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">No Prior Medical History</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                    <tfoot>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="text-sm">
                                                        Showing <strong>{{ $history->firstItem() }}</strong> to
                                                        <strong>{{ $history->lastItem() }} </strong> of <strong>
                                                            {{ $history->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $history->links() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')

@endpush

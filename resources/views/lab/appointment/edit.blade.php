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
                    <h1>Make Appointment </h1>

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
                @if ($appointment->id)
                    <form action="{{ route('appointment.update', ['appointment' => $appointment->id]) }}" method="post">
                        @method('PATCH')
                @endif
                @csrf
                <div class="card">
                    <div class="card-header">Appointment Form</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="appointment">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center"> Test Type<i class="text-danger">*</i></th>

                                            <th class="text-center"> Assigned To<i class="text-danger">*</i></th>
                                            {{-- <th class="text-center"> Date<i
                                                class="text-danger">*</i></th> --}}
                                            <th class="text-center"> From<i class="text-danger">*</i></th>
                                            <th class="text-center"> To<i class="text-danger">*</i></th>
                                            <th class="text-center"> Type<i class="text-danger">*</i></th>
                                            <th class="text-center"> Notes (if any)</th>

                                        </tr>
                                    </thead>
                                    <tbody id="appoint">
                                        <tr class="test">
                                            <td class="">
                                                <select name='testType[]' class='form-control' id='testType_1' multiple>
                                                    <option disbaled>--Select Test Type--</option>
                                                    @foreach ($testType as $test)
                                                        <option value="{{ $test->id }}" {{in_array($test->id, $testId) ? 'selected' : ''}}>
                                                            {{ $test->title }}</option>
                                                    @endforeach

                                                </select>
                                            </td>
                                            <td class="">
                                                <select name="staff[]" class="form-control account_head" required
                                                    id="staff_1" multiple>
                                                    <option>--Select Staff--</option>
                                                    @foreach ($designations as $designation)
                                                        @if ($designation->staffs->count() > 0)
                                                            <option value="" disabled>
                                                                {{ $designation->title }}</option>
                                                            @foreach ($designation->staffs as $staff)
                                                                <option value="{{ $staff->id }}" {{in_array($staff->id, $staffId) ? 'selected' : ''}}>
                                                                    &nbsp&nbsp&nbsp
                                                                    ↪{{ $staff->name }}({{ $staff->id }})</option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach

                                                </select>
                                            </td>

                                            <td>
                                                <input type="datetime-local" name="startTime" id="startTime_1" required
                                                    class='form-control' value="{{date ('Y-m-d\TH:i:s', strtotime($appointment->startTime))}}" min="">
                                            </td>
                                            <td>
                                                <input type="datetime-local" name="endTime" id="endTime_1" required
                                                    class='form-control' value="{{date ('Y-m-d\TH:i:s', strtotime($appointment->endTime))}}">
                                            </td>
                                            <td>
                                                <select name="type" class="form-control" required id="type_1">
                                                    <option value='0' {{$appointment->type == '0' ? 'selected' : ''}}>Visit</option>
                                                    <option value='1' {{$appointment->type == '1' ? 'selected' : ''}}>Home Service</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="notes" class="form-control" id="notes_1">
                                            </td>

                                        </tr>
                                    </tbody>


                                </table>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="card-footer d-flex">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="Reset" class="btn btn-dark ml-2">Reset</button>

                </div>

            </div>
            </form>

    </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script>
        $("select.form-control").select2();
    </script>

@endpush

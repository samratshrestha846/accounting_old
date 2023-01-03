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
                    <h1>Medical History </h1>

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
                @if ($medicalHistory->id)
                    <form
                        action="{{ route('medical-history.update', ['patient' => $patient->id, 'medicalHistory' => $medicalHistory->id]) }}"
                        method="post">
                        @method('PATCH')
                    @else
                        <form action="{{ route('medical-history.store', ['patient' => $patient->id]) }}" method="post">
                @endif
                @csrf
                <div class="card">
                    <div class="card-header">Medical History Form</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="staffId">Staff</label>

                                    <select class="form-control" name="staffId" id="staffId">

                                        @foreach ($staffs as $staff)
                                            <option value="{{ $staff['id'] }}" @if ($staff['id'] == $medicalHistory->staffId)
                                                {{ 'selected' }}
                                        @endif
                                        >{{ $staff['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('staffId')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" name="startDate" id="startDate" class="form-control"
                                        placeholder="startDate" aria-describedby="helpId"
                                        value="{{ old('startDate', optional($medicalHistory->startDate)->format('Y-m-d')) }}">
                                    @error('startDate')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="endDate">End Date</label>
                                    <input type="date" name="endDate" id="endDate" class="form-control"
                                        placeholder="endDate" aria-describedby="helpId"
                                        value="{{ old('endDate', optional($medicalHistory->endDate)->format('Y-m-d')) }}">
                                    @error('endDate')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class=" col-sm-12">
                                <div class="form-group">
                                    <label for="">prescription</label>
                                    <textarea class="form-control" name="prescription" id=""
                                        rows="3">{{ old('prescription', $medicalHistory->prescription) }}</textarea>
                                    @error('prescription')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class=" col-sm-12">
                                <div class="form-group">
                                    <label for="">symptoms</label>
                                    <textarea class="form-control" name="symptoms" id="symptoms"
                                        rows="3">{{ old('symptoms', $medicalHistory->symptoms) }}</textarea>
                                    @error('symptoms')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="card-footer d-flex">
                    <button type="submit" class="btn btn-primary">Submit</button>
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
        $(document).ready(function() {
            $("#users").select2();
        });
    </script>
@endpush

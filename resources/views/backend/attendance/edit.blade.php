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
                    <h1>Edit Staff Attendance => {{ $attendance->staff->fullName }} </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('attendance.create') }}" class="global-btn"><i class="fa fa-eye"></i>
                            Back</a>
                    </div>
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

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('attendance.update', $attendance->id) }}" method="POST"
                                    class="">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="attendance">Today's Attendance: </label>
                                                <select name="attendance" class="form-control">
                                                    <option value="">--Select an option--</option>
                                                    <option value="present"
                                                        {{ $attendance->present == 1 ? 'selected' : '' }}>
                                                        Present</option>
                                                    <option value="paid_leave"
                                                        {{ $attendance->paid_leave == 1 ? 'selected' : '' }}>Paid Leave
                                                    </option>
                                                    <option value="unpaid_leave"
                                                        {{ $attendance->unpaid_leave == 1 ? 'selected' : '' }}>Absent
                                                    </option>
                                                </select>
                                                @error('attendance')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="entry_time">Entry Time:</label>
                                                <input type="time" class="form-control"
                                                    value="{{ @old('entry_time') ? @old('entry_time') : $attendance->entry_time }}"
                                                    name="entry_time">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exit_time">Exit Time:</label>
                                                <input type="time" class="form-control"
                                                    value="{{ @old('exit_time') ? @old('exit_time') : $attendance->exit_time }}"
                                                    name="exit_time">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-secondary mt-2">Submit</button>
                                </form>
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

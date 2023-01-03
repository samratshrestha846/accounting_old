@extends('backend.layouts.app')
@push('styles')

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
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')

@endpush

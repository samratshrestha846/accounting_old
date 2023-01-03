@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- /.content-header -->

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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Software Setting</h2>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('supersetting.update', $supersetting->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method("PUT")
                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="expire_date">Expire Date<i
                                                            class="text-danger">*</i>:</label>
                                                    @php
                                                        $expdate = $supersetting->expire_date;
                                                        $strtime = strtotime($expdate);
                                                        $date = date('Y-m-d', $strtime);
                                                        $time = date('H:i:s', $strtime);
                                                        $expiredate = $date . 'T' . $time;
                                                        // dd($expiredate);
                                                    @endphp
                                                    <input type="datetime-local" id="expire_date" name="expire_date"
                                                        class="form-control" value="{{ $expiredate }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('expire_date') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="user_limit">User Limit<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="number" id="user_limit" name="user_limit"
                                                        class="form-control" value="{{ $supersetting->user_limit }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('user_limit') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="company_limit">Company Limit<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="number" id="company_limit" name="company_limit"
                                                        class="form-control"
                                                        value="{{ $supersetting->company_limit }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_limit') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="branch_limit">Branch Limit<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="number" id="branch_limit" name="branch_limit"
                                                        class="form-control"
                                                        value="{{ $supersetting->branch_limit }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('branch_limit') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="journal_edit_number">Journal Edit Limit<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="number" id="journal_edit_number" name="journal_edit_number"
                                                        class="form-control"
                                                        value="{{ $supersetting->journal_edit_number }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('journal_edit_number') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="journal_edit_days_limit">Journal Edit Days Limit<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="number" id="journal_edit_days_limit"
                                                        name="journal_edit_days_limit" class="form-control"
                                                        value="{{ $supersetting->journal_edit_days_limit }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('journal_edit_days_limit') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="attendance">Attendance Method<i
                                                            class="text-danger">*</i>:</label><br>
                                                    <input type="radio" name="attendance" value="0"
                                                        {{ $supersetting->attendance == 0 ? 'checked' : '' }}> Manual
                                                    &nbsp;
                                                    <input type="radio" name="attendance" value="1"
                                                        {{ $supersetting->attendance == 1 ? 'checked' : '' }}> Biometric
                                                    <p class="text-danger">
                                                        {{ $errors->first('attendance') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group text-right">
                                                    <button type="submit" class="btn btn-primary ml-auto"
                                                        name="supersetting">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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

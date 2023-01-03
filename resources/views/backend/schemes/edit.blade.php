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
                    <h1>Update Scheme information </h1>
                    <div class="btn-bulk" style="margin-top:10px;">
                        <a href="{{ route('scheme.index') }}" class="global-btn">View All Schemes</a>
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('scheme.update', $scheme->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method("PUT")
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="scheme_name">Scheme Name<i class="text-danger">*</i>
                                                        :</label>
                                                    <input type="text" id="scheme_name" name="scheme_name"
                                                        class="form-control" value="{{ old('name', $scheme->name) }}"
                                                        placeholder="Scheme Name">
                                                    <p class="text-danger">
                                                        {{ $errors->first('scheme_name') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="code">Scheme Code (Code must be unique)<i
                                                            class="text-danger">*</i> :</label>
                                                    <input type="text" id="code" name="code" class="form-control"
                                                        value="{{ old('code', $scheme->code) }}" placeholder="Scheme Code">
                                                    <p class="text-danger scheme_code_error hide">Code is already used. Use
                                                        Different code.</p>
                                                    <p class="text-danger">
                                                        {{ $errors->first('code') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Starting date</label>
                                                    <input type="text" name="starting_date" class="form-control startdate"
                                                        id="starting_date" value="{{ old('starting_date', $scheme->start_date) }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Ending date</label>
                                                    <input type="text" name="ending_date" class="form-control enddate"
                                                        id="ending_date" value="{{ old('end_date', $scheme->end_date) }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="based_on">Based On
                                                        :</label>
                                                    <select name="based_on" class="form-control">
                                                        <option value="">--Select an option--</option>
                                                        <option value="0" {{ $scheme->based_on == 0 ? 'selected' : '' }}>
                                                            Product Based</option>
                                                        <option value="1" {{ $scheme->based_on == 1 ? 'selected' : '' }}>
                                                            Service Based</option>
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('based_on') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="percent_fixed">Percentage / Fixed
                                                        :</label>
                                                    <select name="percent_fixed" class="form-control">
                                                        <option value="">--Select an option--</option>
                                                        <option value="0"
                                                            {{ $scheme->percent_fixed == 0 ? 'selected' : '' }}>
                                                            Percentage</option>
                                                        <option value="1"
                                                            {{ $scheme->percent_fixed == 1 ? 'selected' : '' }}>Fixed
                                                            Amount</option>
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('percent_fixed') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="amount">Amount (% / Rs.)
                                                        :</label>
                                                    <input type="text" id="amount" name="amount" class="form-control amount"
                                                        value="{{ old('amount', $scheme->amount) }}" placeholder="Enter Amount">
                                                    <p class="text-danger">
                                                        {{ $errors->first('amount') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="Status" style="display: block;">Status: </label>
                                                    <span style="margin-right: 5px; font-size: 12px;"> Disable </span>
                                                    <label class="switch pt-0">
                                                        <input type="checkbox" name="status" value="1"
                                                            {{ $scheme->status == 1 ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <span style="margin-left: 5px; font-size: 12px;">Enable</span>
                                                </div>
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <button type="submit" class="btn btn-primary btn-sm ml-auto">Submit</button>
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
    <script type="text/javascript">
        window.onload = function() {
            var starting_date = document.getElementById("starting_date");
            var ending_date = document.getElementById("ending_date");
            starting_date.nepaliDatePicker();
            ending_date.nepaliDatePicker();
        };
    </script>
@endpush

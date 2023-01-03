@extends('backend.layouts.app')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Update Budget Info </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('budgetinfo') }}" class="global-btn">View Budget Info</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    {{-- <div class="card-header text-center">
                                    <h3>Edit Budge</h3>
                                </div> --}}
                                    <div class="card-body">
                                        <form action="{{ route('updatebudget', $budget_info->id) }}" method="POST">
                                            @csrf
                                            @method("PUT")
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="account_head">Ledger Head:</label>
                                                        <select name="child_account_id" class="form-control account_head"
                                                            required>
                                                            <option value="">--Select an Account Head--</option>
                                                            @foreach ($accounts as $account)
                                                                <option value="" class="title" disabled>
                                                                    {{ $account->title }}</option>
                                                                @php
                                                                    $sub_accounts = DB::table('sub_accounts')
                                                                        ->where('account_id', $account->id)
                                                                        ->get();
                                                                @endphp

                                                                @foreach ($sub_accounts as $sub_account)
                                                                    @php
                                                                        $child_accounts = DB::table('child_accounts')
                                                                            ->where('sub_account_id', $sub_account->id)
                                                                            ->get();
                                                                    @endphp
                                                                    @foreach ($child_accounts as $child_account)
                                                                        <option value="{{ $child_account->id }}"
                                                                            {{ $child_account->id == $budget_info->child_account_id ? 'selected' : '' }}>
                                                                            {{ $child_account->title }} -
                                                                            {{ $sub_account->title }}</option>
                                                                    @endforeach
                                                                @endforeach
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Fiscal Year:</label>
                                                        <select name="fiscal_year" class="form-control fiscal">
                                                            @foreach ($fiscal_years as $fiscal_year)
                                                                <option value="{{ $fiscal_year->fiscal_year }}"
                                                                    {{ $fiscal_year->fiscal_year == $budget_info->fiscal_year ? 'selected' : '' }}>
                                                                    {{ $fiscal_year->fiscal_year }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Allocated From:</label>
                                                        <input type="text" name="starting_date"
                                                            class="form-control startdate" id="starting_date"
                                                            value="{{ old('starting_date', $budget_info->starting_date_nepali) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Allocated upto:</label>
                                                        <input type="text" name="ending_date" class="form-control enddate"
                                                            id="ending_date"
                                                            value="{{ old('ending_date', $budget_info->ending_date_nepali) }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Amount Allocation (in Rs.):</label>
                                                        <input type="number" name="budget_amount"
                                                            class="form-control enddate" id="budget_amount" step="any"
                                                            value="{{ old('budget_amount', $budget_info->budget_allocated) }}"
                                                            placeholder="Amount in Rs.">
                                                        <p class="text-danger">
                                                            {{ $errors->first('budget_amount') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="details">Details:</label>
                                                        <textarea name="details" id="summernote"
                                                            placeholder="Some details on budget.."
                                                            class="form-control">{!! old('details', $budget_info->details) !!}</textarea>
                                                        <p class="text-danger">
                                                            {{ $errors->first('details') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 text-center mt-3">
                                                    <button type="submit" class="btn btn-secondary">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <script type="text/javascript">
        $('#summernote').summernote({
            height: 150
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script type="text/javascript">
        $('#summernote').summernote({
            height: 150
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".account_head").select2();
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".account_head").select2();
        });
    </script>

    <script type="text/javascript">
        window.onload = function() {
            var starting_date = document.getElementById("starting_date");
            starting_date.nepaliDatePicker();
            var ending_date = document.getElementById("ending_date");
            ending_date.nepaliDatePicker();
        };
    </script>

    <script>
        $(function() {
            $('.fiscal').change(function() {
                var fiscal_year = $(this).children("option:selected").val();
                var array_date = fiscal_year.split("/");
                var starting_date = document.getElementById("starting_date");
                var starting_full_date = array_date[0] + '-04-01';
                starting_date.value = starting_full_date;
                starting_date.nepaliDatePicker();
                var ending_date = document.getElementById("ending_date");
                var ending_year = array_date[1];
                var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
                var ending_full_date = ending_year + '-03-' + days_count;
                ending_date.value = ending_full_date;
                ending_date.nepaliDatePicker();
            })
        })
    </script>
@endpush

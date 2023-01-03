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
                    <h1>Accounting Ledgers </h1>
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
                            <div class="card-header">
                                <h2>Generate Ledgers</h2>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('generateledgers') }}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Account Heads:</label>
                                                <select name="child_account_id" class="form-control account_head" required>
                                                    <option value="">--Select an Account Head--</option>
                                                    @foreach ($accounts as $account)
                                                        <option value="" class="title" disabled>
                                                            {{ $account->title }}</option>
                                                        @php
                                                            $sub_accounts = $account->sub_accounts;
                                                        @endphp

                                                        @foreach ($sub_accounts as $sub_account)
                                                            @php
                                                                $child_accounts = $sub_account->child_accounts;
                                                            @endphp
                                                            @foreach ($child_accounts as $child_account)
                                                                <option value="{{ $child_account->id }}">
                                                                    {{ $child_account->title }} -
                                                                    {{ $sub_account->title }}</option>
                                                            @endforeach
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Fiscal Year</label>
                                                <select name="fiscal_year" class="form-control fiscal">
                                                    @foreach ($fiscal_years as $fiscal_year)
                                                        <option value="{{ $fiscal_year->fiscal_year }}"
                                                            {{ $fiscal_year->id == $current_fiscal_year->id ? 'selected' : '' }}>
                                                            {{ $fiscal_year->fiscal_year }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Starting date</label>
                                                <input type="text" name="starting_date" class="form-control startdate"
                                                    id="starting_date" value="{{ $actual_year[0] . '-04-01' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Ending date</label>
                                                <input type="text" name="ending_date" class="form-control enddate"
                                                    id="ending_date" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary ml-auto">Generate</button>
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
    <script>
        $(document).ready(function() {
            $(".account_head").select2();
        });
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

    <script type="text/javascript">
        window.onload = function() {
            var starting_date = document.getElementById("starting_date");
            var ending_date = document.getElementById("ending_date");
            var ending_year = "{{ $actual_year[1] }}";
            var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
            starting_date.nepaliDatePicker();
            var ending_full_date = ending_year + '-03-' + days_count;
            ending_date.value = ending_full_date;
            ending_date.nepaliDatePicker();
        };
    </script>
@endpush

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
                    <h1>Add Account Types </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('account.index') }}" class="global-btn">View Main Account
                            Types</a> <a href="{{ route('sub_account.index') }}" class="global-btn">View
                            Sub Account
                            Types</a> <a href="{{ route('child_account.index') }}" class="global-btn">View
                            Child Account Types</a>
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
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h2>Main Account</h2>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('account.store') }}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <div class="form-group">
                                        <label for="title">Title<i class="text-danger">*</i></label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="Enter Account Title">
                                        <p class="text-danger">
                                            {{ $errors->first('title') }}
                                        </p>
                                    </div>

                                    <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h2>Sub Account</h2>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('sub_account.store') }}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <div class="form-group">
                                        <label for="title">Title<i class="text-danger">*</i></label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="Enter Account Title">
                                        <p class="text-danger">
                                            {{ $errors->first('title') }}
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label for="account_id">Embedded Account Head<i class="text-danger">*</i></label>
                                        <select name="account_id" class="form-control sub_account">
                                            <option value="">--Select an Account Type--</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->title }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-danger">
                                            {{ $errors->first('account_id') }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="sub_account_id">Embedded Sub-Account<i class="text-danger">*</i></label>
                                        <select name="sub_account_id" class="form-control sub_account">
                                            <option value="">--Select an Sub-Account Type--</option>
                                            @foreach ($main_sub_accounts as $sub_account)
                                                <option value="{{ $sub_account->id }}">{{ $sub_account->title }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-danger">
                                            {{ $errors->first('sub_account_id') }}
                                        </p>
                                    </div>

                                    <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h2>Child Account</h2>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('child_account.store') }}" method="POST" id="subaccountForm">
                                    @csrf
                                    @method("POST")
                                    <div class="form-group">
                                        <label for="title">Title<i class="text-danger">*</i></label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="Enter Account Title">
                                        <p class="text-danger">
                                            {{ $errors->first('title') }}
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label for="sub_account_id">Embedded Sub-Account Head<i
                                                class="text-danger">*</i></label>
                                        <select name="sub_account_id" class="form-control child_account">
                                            <option value="">--Select an Account Type--</option>
                                            @foreach ($sub_accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->title }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-danger">
                                            {{ $errors->first('sub_account_id') }}
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label for="opening_balance">Opening Balance (Optional)</label>
                                        <input type="number" class="form-control" name="opening_balance"
                                            placeholder="Opening Balance in Rs." step="any" min="0">
                                        <p class="text-danger">
                                            {{ $errors->first('opening_balance') }}
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label for="behaviour">Opening Balance behaviour (Optional)</label>
                                        <select name="behaviour" class="form-control">
                                            <option value="debit">Debit</option>
                                            <option value="credit">Credit</option>
                                        </select>
                                        <p class="text-danger">
                                            {{ $errors->first('behaviour') }}
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label for="behaviour">Select Fiscal Year</label>
                                        <select name="fiscal_year_id" class="form-control">
                                            @foreach ($fiscal_years as $fiscalyear)
                                            <option value="{{$fiscalyear->id}}" {{$fiscalyear->id == $current_fiscal_year->id ? 'selected' : ''}}>{{$fiscalyear->fiscal_year}}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-danger">
                                            {{ $errors->first('behaviour') }}
                                        </p>
                                    </div>

                                    <button type="submit" class="btn btn-secondary btn-sm">Save</button>
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

    <script>
        $(document).ready(function() {
            $(".sub_account").select2();
        });
        $(document).ready(function() {
            $(".child_account").select2();
        });
    </script>
@endpush

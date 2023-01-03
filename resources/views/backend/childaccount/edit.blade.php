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
                    <h1>Update Account Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('child_account.index') }}" class="global-btn">See Child Account
                            Types</a>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <p class="text-danger">(Note: Editing Fiscal Year and Opening Balance may Change Accounting Report. Hope you understand what you are doing)</p>
                                <form action="{{ route('child_account.update', $childAccount->id) }}" method="POST">
                                    @csrf
                                    @method("PUT")
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="title">Title<i class="text-danger">*</i></label>
                                                <input type="text" name="title" class="form-control"
                                                    placeholder="Enter Account Title" value="{{ $childAccount->title }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('title') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sub_account_id">Embedded Sub-Account Head<i
                                                        class="text-danger">*</i></label>
                                                <select name="sub_account_id" class="form-control sub_accounts">
                                                    <option value="">--Select an Account Type--</option>
                                                    @foreach ($sub_accounts as $account)
                                                        <option value="{{ $account->id }}"
                                                            {{ $account->id == $childAccount->sub_account_id ? 'selected' : '' }}>
                                                            {{ $account->title }} </option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('sub_account_id') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="opening_balance">Opening Balance (Optional)</label>
                                                <input type="number" class="form-control" name="opening_balance"
                                                    placeholder="Opening Balance in Rs."
                                                    value="{{ $childAccount->opening_balance < 0 ? $childAccount->opening_balance * -1 : $childAccount->opening_balance }}" step="any">
                                                <p class="text-danger">
                                                    {{ $errors->first('opening_balance') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="behaviour">Opening Balance behaviour (Optional)</label>
                                                <select name="behaviour" class="form-control">
                                                    <option value="">--Select an Account Behaviour--</option>
                                                    <option value="debit"{{ $childAccount->opening_balance > 0 ? 'selected' : '' }}>Debit</option>
                                                    <option value="credit"{{ $childAccount->opening_balance < 0 ? 'selected' : '' }}>Credit</option>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('behaviour') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="behaviour">Select Fiscal Year</label>
                                                <select name="fiscal_year_id" class="form-control">
                                                    @foreach ($fiscal_years as $fiscalyear)
                                                    <option value="{{$fiscalyear->id}}">{{$fiscalyear->fiscal_year}}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('behaviour') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-sm ml-auto">Update</button>
                                        </div>
                                    </div> 
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
        $(".sub_accounts").select2();
    });
</script>
@endpush

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
                    <h1 class="m-0">Update Account Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('sub_account.index') }}" class="global-btn">See Sub Account Types</a>
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
                                <form action="{{ route('sub_account.update', $subAccount->id) }}" method="POST">
                                    @csrf
                                    @method("PUT")
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="title">Title<i class="text-danger">*</i>:</label>
                                                <input type="text" name="title" class="form-control"
                                                    placeholder="Enter Account Title" value="{{ $subAccount->title }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('title') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="account_id">Embedded Account Head<i
                                                        class="text-danger">*</i>:</label>
                                                <select name="account_id" class="form-control main_accounts">
                                                    <option value="">--Select an Account Type--</option>
                                                    @foreach ($accounts as $account)
                                                        <option value="{{ $account->id }}"
                                                            {{ $account->id == $subAccount->account_id ? 'selected' : '' }}>
                                                            {{ $account->title }} </option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('account_id') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sub_account_id">Embedded Sub-Account<i class="text-danger">*</i></label>
                                                <select name="sub_account_id" class="form-control sub_account">
                                                    <option value="">--Select an Sub-Account Type--</option>
                                                    @foreach ($main_sub_accounts as $sub_account)
                                                        <option value="{{ $sub_account->id }}" {{$sub_account->id == $subAccount->sub_account_id ? 'selected' : ''}}>{{ $sub_account->title }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('sub_account_id') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class='form-group'>
                                                <label for="">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                            </div>
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
        $(".main_accounts").select2();
    });
</script>
@endpush

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
                        <a href="{{ route('account.index') }}" class="global-btn">See Account Types</a>
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
                                <form action="{{ route('account.update', $account->id) }}" method="POST">
                                    @csrf
                                    @method("PUT")
                                    <div class="form-group">
                                        <label for="title">Title<i class="text-danger">*</i></label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ $account->title }}">
                                        <p class="text-danger">
                                            {{ $errors->first('title') }}
                                        </p>
                                    </div>
                                    <button type="submit" class="btn btn-secondary">Save</button>
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

@endpush

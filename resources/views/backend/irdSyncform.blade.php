@extends('backend.layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">IRD Sync</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">IRD Sync</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>IRD Sync Form</h3>
                    </div>

                    <div class="card-body">
                        <form action="" method="POST">
                            @csrf
                            @method("POST")
                            <div class="row">
                                <div class="col-md-3">
                                    <b>Sales URL:</b>
                                </div>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="sales_url" placeholder="Enter Sales URL from IRD">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <b>Sales Return URL:</b>
                                </div>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="sales_return_url" placeholder="Enter Sales Return URL from IRD">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <b>Username:</b>
                                </div>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="username" placeholder="Enter Username">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <b>Password:</b>
                                </div>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password" placeholder="Enter Password">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <b>PAN number:</b>
                                </div>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="pan_number" placeholder="Enter PAN number">
                                    </div>
                                </div>

                                <div class="col-md-3"></div>

                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-success">Sync</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('scripts')

@endpush

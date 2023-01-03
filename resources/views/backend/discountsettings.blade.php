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
                    <h1>Discount Settings</h1>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
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
                                <div class="card-body">
                                    <form action="{{ route('supersetting.update', $supersetting->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method("PUT")
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Discount Implementation:</label>
                                                    <select class="form-control" name="before_after">
                                                        <option value="">--Select an option--</option>
                                                        <option value="0"
                                                            {{ $supersetting->before_after == 0 ? 'selected' : '' }}>
                                                            Before Tax</option>
                                                        <option value="1"
                                                            {{ $supersetting->before_after == 1 ? 'selected' : '' }}>After
                                                            Tax</option>
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('before_after') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Discount Type:</label>
                                                    <select class="form-control" name="discount_type">
                                                        <option value="">--Select an option--</option>
                                                        <option value="0"
                                                            {{ $supersetting->discount_type == 0 ? 'selected' : '' }}>
                                                            Individual Discount</option>
                                                        <option value="1"
                                                            {{ $supersetting->discount_type == 1 ? 'selected' : '' }}>Bulk
                                                            Discount</option>
                                                        <option value="2"
                                                            {{ $supersetting->discount_type == 2 ? 'selected' : '' }}>Both
                                                        </option>
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('discount_type') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group text-center">
                                                    <label for="">&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary" name="discount">Submit</button>
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

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
                    <h1>Update Unit </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('unit.index') }}" class="global-btn">View Units</a>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('unit.update', $existingUnit->id) }}" method="POST"
                                    id="unit_store_form">
                                    @csrf
                                    @method("PUT")
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="unit">Unit Name:<i class="text-danger">*</i> </label>
                                                <input type="text" name="unit" class="form-control"
                                                    placeholder="Enter Unit Name" id="unit" required
                                                    value="{{ old('unit', $existingUnit->unit) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('unit') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="unit_code">Unit Code:<i class="text-danger">*</i> </label>
                                                <input type="text" name="unit_code" class="form-control"
                                                    placeholder="Enter Unit Name" id="unit_code" required
                                                    value="{{ old('unit_code', $existingUnit->unit_code) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('unit_code') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="short_form">Short form<i class="text-danger">*</i> </label>
                                                <input type="text" name="short_form" class="form-control"
                                                    placeholder="Enter Category Code" id="short_form" required
                                                    value="{{ old('short_form', $existingUnit->short_form) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('short_form') }}
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

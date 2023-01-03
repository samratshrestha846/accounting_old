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
                    <h1>Edit Hotel Floor </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-floor.index') }}" class="global-btn">View Hotel Floors</a>
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
                                <div class="alert  alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('hotel-floor.update', $floor->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method("PATCH")
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="floor_name">Floor Name<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="floor_name" class="form-control"
                                                    placeholder="Enter Floor Name" value="{{ old('floor_name', $floor->name) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('floor_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="floor_code">Floor Code (Code must be unique)<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="floor_code" class="form-control"
                                                    placeholder="Enter Floor Code" value="{{ old('floor_code', $floor->code) }}"
                                                    id="floor_code">

                                                <p class="text-danger categorycode_error hide">Code is already used. Use
                                                    Different code.</p>
                                                <p class="text-danger">
                                                    {{ $errors->first('floor_code') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
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

@endpush

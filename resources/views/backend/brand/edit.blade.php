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
                    <h1 class="m-0">Update Brand </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('brand.index') }}" class="global-btn">View Brands</a>
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
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('brand.update', $existing_brand->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method("PUT")
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="brand_name">Brand Name<i class="text-danger">*</i></label>
                                                <input type="text" name="brand_name" class="form-control"
                                                    value="{{ old('brand_name', $existing_brand->brand_name) }}"
                                                    placeholder="Enter Brand Name">
                                                <p class="text-danger">
                                                    {{ $errors->first('brand_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="brand_code">Brand Code (Code must be unique)<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="brand_code" class="form-control"
                                                    placeholder="Enter Brand Code"
                                                    value="{{ old('brand_code', $existing_brand->brand_code) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('brand_code') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="brand_logo">Brand Logo</label>
                                                <input type="file" name="brand_logo" class="form-control"
                                                    onchange="loadFile(event)">
                                                <p class="text-danger">
                                                    {{ $errors->first('brand_logo') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="vertical-align: top;">Preview Image</label>
                                                <img src="{{ Storage::disk('uploads')->url($existing_brand->brand_logo) }}"
                                                    id="output" style="max-height: 150px; max-width:250px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-bulk d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
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
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
@endpush

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
                    <h1>Update Item Category </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('category.index') }}" class="global-btn">View Categories</a>
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

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="category_name">Category Name<i
                                                class="text-danger">*</i></label>
                                        <input type="text" name="category_name" class="form-control"
                                            placeholder="Enter Category Name" value="{{ old('category_name', $category->category_name) }}">
                                        <p class="text-danger">
                                            {{ $errors->first('category_name') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="category_code">Category Code (Code must be unique)<i
                                                class="text-danger">*</i></label>
                                        <input type="text" name="category_code" class="form-control"
                                            placeholder="Enter Category Code" value="{{ old('category_code', $category->category_code) }}"
                                            id="category_code">

                                        <p class="text-danger categorycode_error hide">Code is already used. Use
                                            Different code.</p>
                                        <p class="text-danger">
                                            {{ $errors->first('category_code') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="category_image">Category Image</label>
                                        <input type="file" name="category_image" class="form-control"
                                            onchange="loadFile(event)">
                                        <p class="text-danger">
                                            {{ $errors->first('category_image') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="" style="display: block;">Preview Image</label>
                                    <img src={{ Storage::disk('uploads')->url($category->category_image) }} id="output" style="height: 50px;">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm ml-auto">Save</button>
                        </form>
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

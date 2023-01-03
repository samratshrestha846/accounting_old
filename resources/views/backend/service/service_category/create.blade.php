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
                    <h1>New Category </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('service_category.index') }}" class="global-btn">View
                            Categories</a>
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
                                <form action="{{ route('service_category.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="category_name">Category Name<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="category_name" class="form-control"
                                                    placeholder="Enter Category Name" value="{{ old('category_name') }}">
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
                                                    placeholder="Enter Category Code" value="{{ old('category_code', $category_code) }}"
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
                                            <img id="output" style="height: 30px; width:auto;">
                                        </div>
                                    </div>
                                    <div class="btn-bulk d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                    <button type="submit" class="btn btn-secondary btn-large" name="saveandcontinue" value="1">Submit And Continue</button>
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

        var categorycodes = @php echo json_encode($allcategorycodes) @endphp;
        $("#category_code").change(function() {
            var productcategoryval = $(this).val();
            if ($.inArray(productcategoryval, categorycodes) != -1) {
                $('.categorycode_error').addClass('show');
                $('.categorycode_error').removeClass('hides');
            } else {
                $('.categorycode_error').removeClass('show');
                $('.categorycode_error').addClass('hide');

            }
        })
    </script>
@endpush

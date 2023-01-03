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
                    <h1>New Brand </h1>
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
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('brand.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="brand_name">Brand Name<i class="text-danger">*</i></label>
                                                <input type="text" name="brand_name" class="form-control"
                                                    placeholder="Enter Brand Name" value="{{ old('brand_name') }}">
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
                                                    value="{{ old('brand_code', $branch_code) }}" placeholder="Enter Brand Code"
                                                    id="brand_code">
                                                <p class="text-danger brandcode_error hide">Code is already used. Use
                                                    Different code.</p>
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
                                            <label for="brand_logo" style="vertical-align: top;display:block;">Preview Image</label>
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

        var brandcodes = @php echo json_encode($allbrandcodes) @endphp;
        $("#brand_code").change(function() {
            var productcategoryval = $(this).val();
            if ($.inArray(productcategoryval, brandcodes) != -1) {
                $('.brandcode_error').addClass('show');
                $('.brandcode_error').removeClass('hides');
            } else {
                $('.brandcode_error').removeClass('show');
                $('.brandcode_error').addClass('hide');
            }
        })
    </script>
@endpush

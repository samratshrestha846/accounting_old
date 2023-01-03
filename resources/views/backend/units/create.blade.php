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
                    <h1>Unit </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('unit.index') }}" class="global-btn">View Units</a>
                    </div>
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
                                <form action="{{ route('unit.store') }}" method="POST" enctype="multipart/form-data"
                                    id="unit_store_form">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="unit">Unit Name:<i class="text-danger">*</i> </label>
                                                <input type="text" name="unit" class="form-control" value="{{ old('unit') }}"
                                                    placeholder="Enter Unit Name" id="unit" required>
                                                <p class="text-danger">
                                                    {{ $errors->first('unit') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="unit_code">Unit Code:<i class="text-danger">*</i> </label>
                                                <input type="text" name="unit_code" class="form-control"
                                                    value="{{ old('unit_code', $unit_code) }}" placeholder="Enter Unit Name" id="unit_code"
                                                    required>
                                                <p class="text-danger unitcode_error hide">Code is already used. Use
                                                    Different code.</p>
                                                <p class="text-danger">
                                                    {{ $errors->first('unit_code') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="short_form">Short form<i class="text-danger">*</i> </label>
                                                <input type="text" name="short_form" class="form-control" value="{{ old('short_form') }}"
                                                    placeholder="Enter Category Code" id="short_form" required>
                                                <p class="text-danger">
                                                    {{ $errors->first('short_form') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-12 btn-bulk d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <button type="submit" class="btn btn-secondary btn-large" name="saveandcontinue" value="1">Submit And Continue</button>
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
        var unitcodes = @php echo json_encode($allunitcodes) @endphp;
        $("#unit_code").change(function() {
            var productcategoryval = $(this).val();
            if ($.inArray(productcategoryval, unitcodes) != -1) {
                $('.unitcode_error').addClass('show');
                $('.unitcode_error').removeClass('hides');
            } else {
                $('.unitcode_error').removeClass('show');
                $('.unitcode_error').addClass('hide');
            }
        })
    </script>
@endpush

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
                    <h1>Update Series Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('series.index') }}" class="global-btn">View Series</a>
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
                                <form action="{{ route('series.update', $selected_series->id) }}" method="POST">
                                    @csrf
                                    @method("PUT")
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="related_brand">Related Brand<i
                                                        class="text-danger">*</i></label>
                                                <select name="related_brand" class="form-control related_brand">
                                                    <option value="">--Select a Brand--</option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}"
                                                            {{ $brand->id == $selected_series->brand_id ? 'selected' : '' }}>
                                                            {{ $brand->brand_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('related_brand') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="series_name">Series Name <i class="text-danger">*</i></label>
                                                <input type="text" name="series_name" class="form-control"
                                                    placeholder="Enter Series Name" id="series_name"
                                                    value="{{ old('series_name', $selected_series->series_name) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('series_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="series_code">Series Code (Code must be unique)<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="series_code" class="form-control"
                                                    placeholder="Enter series Code" id="series_code"
                                                    value="{{ old('series_code', $selected_series->series_code) }}">
                                                <p class="text-danger seriescode_error hide">Code is already used. Use
                                                    Different code.</p>
                                                <p class="text-danger">
                                                    {{ $errors->first('series_code') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-bulk d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
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
        $(document).ready(function() {
            $(".related_brand").select2();
        });

        var seriescodes = @php echo json_encode($allseriescodes) @endphp;
        $("#series_code").change(function() {
            var productcategoryval = $(this).val();
            if ($.inArray(productcategoryval, seriescodes) != -1) {
                $('.seriescode_error').addClass('show');
                $('.seriescode_error').removeClass('hides');
            } else {
                $('.seriescode_error').removeClass('show');
                $('.seriescode_error').addClass('hide');
            }
        })
    </script>
@endpush

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
                    <h1>New Service information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('service.index') }}" class="global-btn">View All Services</a>
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

                <div class="card">
                    <div class="card-header">
                        <h2>Fill Service Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="{{ route('service.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method("POST")
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="service_name">Service Name<i class="text-danger">*</i>
                                                            :</label>
                                                        <input type="text" id="service_name" name="service_name"
                                                            class="form-control" value="{{ old('service_name') }}"
                                                            placeholder="Service Name">
                                                        <p class="text-danger">
                                                            {{ $errors->first('service_name') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="service_code">Service Code (Unique)<i
                                                                class="text-danger">*</i> :</label>
                                                        <input type="text" id="service_code" name="service_code"
                                                            class="form-control" value="{{ old('service_code', $service_code) }}"
                                                            placeholder="Service Code">
                                                        <p class="text-danger service_code_error hide">Code is already used. Use
                                                            Different code.</p>
                                                        <p class="text-danger">
                                                            {{ $errors->first('service_code') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="category">Service Category<i class="text-danger">*</i>
                                                            :</label>
                                                        <select name="category" class="form-control category"
                                                            id="category_service">
                                                            <option value="">--Select a category--</option>
                                                            @foreach ($service_categories as $category)
                                                                <option value="{{ $category->id }}"{{ old('category') == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->category_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="text-danger">
                                                            {{ $errors->first('category') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cost_price">Cost Price (Rs.)<i
                                                                class="text-danger">*</i> :</label>
                                                        <input type="number" step="any" name="cost_price"
                                                            class="form-control" id="cost_price" value="{{ old('cost_price') }}"
                                                            placeholder="Cost Price in Rs.">
                                                        <p class="text-danger">
                                                            {{ $errors->first('cost_price') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="selling_price">Selling Price (Rs.)<i
                                                                class="text-danger">*</i> :</label>
                                                        <input type="number" step="any" name="selling_price"
                                                            class="form-control" id="selling_price" value="{{ old('selling_price') }}"
                                                            placeholder="Selling Price in Rs.">
                                                        <p class="text-danger">
                                                            {{ $errors->first('selling_price') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="image">Service image (Multiple images) :</label>
                                                        <input type="file" name="service_image[]" class="form-control"
                                                            multiple onchange="loadFile(event)">
                                                        <p class="text-danger">
                                                            {{ $errors->first('service_image') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="" style="vertical-align: top;">Preview Image</label>
                                                    <img id="output" style="height: 50px;">
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="Status" style="display: block;">Status: </label>
                                                        <span style="margin-right: 5px; font-size: 12px;"> Disable </span>
                                                        <label class="switch pt-0">
                                                            <input type="checkbox" name="status" value="1"{{ old('status') == 1 ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <span style="margin-left: 5px; font-size: 12px;">Enable</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Service Details For invoice</label>
                                                        <textarea name="description" class="form-control" cols="30" rows="5"
                                                            placeholder="Something about service..."
                                                            value="">{{ old('description') }}</textarea>
                                                        <p class="text-danger">
                                                            {{ $errors->first('description') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <hr>
                                                    <h2>Ledger Details</h2>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="opening_balance">Opening Balance</label>
                                                                <input type="number" name="opening_balance" min="" class="form-control opening_balance" value="{{ @old('opening_balance') ?? 0 }}" step=".01">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('opening_balance') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="behaviour">Opening Balance behaviour (Optional) </label>
                                                                <select name="behaviour" class="form-control behaviour">
                                                                    <option value="debit">Debit</option>
                                                                    <option value="credit">Credit</option>
                                                                </select>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('behaviour') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group text-right">
                                                <button type="submit" class="btn btn-primary btn-sm ml-auto">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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

    <script>
        $(document).ready(function() {
            $(".category").select2();
        });

        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>

    <script>
        $(function() {

            var servicecodes = @php echo json_encode($allservicecodes) @endphp;
            $("#service_code").change(function() {
                var servicecodeval = $(this).val();
                if ($.inArray(servicecodeval, servicecodes) != -1) {
                    $('.service_code_error').addClass('show');
                    $('.service_code_error').removeClass('hides');
                } else {
                    $('.service_code_error').removeClass('show');
                    $('.service_code_error').addClass('hide');

                }
            })
        })
    </script>
@endpush

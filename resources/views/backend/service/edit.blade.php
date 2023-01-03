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
                    <h1>Edit Service information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('service.index') }}" class="global-btn">View All Services</a>
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

                <div class="card">
                    <div class="card-header">
                        <h2>Update Service</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="{{ route('service.update', $service->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method("PUT")
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="service_name">Service Name<i
                                                                class="text-danger">*</i>:</label>
                                                        <input type="text" id="service_name" name="service_name"
                                                            class="form-control" value="{{ old('service_name', $service->service_name) }}"
                                                            placeholder="Service Name" />
                                                        <p class="text-danger">
                                                            {{ $errors->first('service_name') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="service_code">Service Code (Unique)<i
                                                                class="text-danger">*</i>:</label>
                                                        <input type="text" id="service_code" name="service_code"
                                                            class="form-control" value="{{ old('service_code', $service->service_code) }}"
                                                            placeholder="Service Name" />
                                                        <p class="text-danger">
                                                            {{ $errors->first('service_code') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="category">Service Category<i
                                                                class="text-danger">*</i>:</label>
                                                        <select name="category" class="form-control category">
                                                            <option value="">--Select a category--</option>
                                                            @foreach ($service_categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ $category->id == $service->service_category_id ? 'selected' : '' }}>
                                                                    {{ $category->category_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="text-danger">
                                                            {{ $errors->first('category') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="cost_price">Cost Price (Rs.)<i
                                                                class="text-danger">*</i>:</label>
                                                        <input type="number" step="any" name="cost_price"
                                                            class="form-control" value="{{ old('cost_price', $service->cost_price) }}"
                                                            placeholder="Allocated Cost Price">
                                                        <p class="text-danger">
                                                            {{ $errors->first('cost_price') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="selling_price">Selling Price (Rs.)<i
                                                                class="text-danger">*</i>:</label>
                                                        <input type="number" step="any" name="selling_price"
                                                            class="form-control" value="{{ old('selling_price', $service->sale_price) }}"
                                                            placeholder="Allocated Sale Price">
                                                        <p class="text-danger">
                                                            {{ $errors->first('selling_price') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="" style="margin-right: 5px;display:block;">Status:</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="service_status" value="1"
                                                                {{ $service->status == 1 ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label><span style="margin-left: 5px;">Enable</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Service Details For invoice</label>
                                                        <textarea name="description" class="form-control" cols="30" rows="5"
                                                            placeholder="Something about product..."
                                                            value="">{{ old('description', $service->description) }}</textarea>
                                                        <p class="text-danger">
                                                            {{ $errors->first('description') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group m-0">
                                                <button type="submit" class="btn btn-primary ml-auto" name="submit">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <form action="{{ route('service.update', $service->id) }}" id="validate"
                                                    enctype="multipart/form-data" method="POST">
                                                    @csrf
                                                    @method("PUT")

                                                    <div class="form-group">
                                                        <label for="service_image">Attach file (Bills, etc.)<i
                                                                class="text-danger">*</i></label>
                                                        <input type="file" name="service_image[]" id="service_image"
                                                            class="form-control" onchange="loadFile(event)" multiple>
                                                    </div>

                                                    <input type="submit" id="add_receive" class="btn btn-secondary btn-large"
                                                        name="update" value="Save" tabindex="9" />
                                                </form>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Preview Image</label>
                                                @if (count($service_images) > 0)
                                                    <div class="row">
                                                        @foreach ($service_images as $image)
                                                            <div class="col-md-3 wrapp">
                                                                <img src="{{ Storage::disk('uploads')->url($image->location) }}"
                                                                    alt="" style="width:100%;">
                                                                <form action="{{ route('deleteserviceimage', $image->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" data-id="{{ $image->id }}"
                                                                        class="btn btn-danger py-0 px-1 absolutebtn"
                                                                        class="remove">x</button>
                                                                </form>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <img src="{{ Storage::disk('uploads')->url('noimage.jpg') }}" alt=""
                                                        style="width:200px; max-height: 200px;">
                                                @endif
                                            </div>
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
    </script>
@endpush

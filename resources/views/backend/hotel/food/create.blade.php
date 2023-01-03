@extends('backend.layouts.app')
@push('styles')
    <style>
        .lh-0-6 {
            line-height: 0.6 !important;
        }

    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>New Food Item </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-food.index') }}" class="global-btn">View Food Items</a>
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
                                <form action="{{ route('hotel-food.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="room">Category <i class="text-danger">*</i></label>
                                                <select name="category" class="form-control select2">
                                                    <option value="">Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ old('category') == $category->id ? 'selected' : '' }}>
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
                                                <label for="room">Kitchen <i class="text-danger">*</i></label>
                                                <select name="kitchen" class="form-control select2">
                                                    <option value="">Select Kitchen</option>
                                                    @foreach ($kitchens as $kitchen)
                                                        <option value="{{ $kitchen->id }}"
                                                            {{ old('kitchen') == $kitchen->id ? 'selected' : '' }}>
                                                            {{ $kitchen->kitchen_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('kitchen') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="food_name">Food Name<i class="text-danger">*</i></label>
                                                <input type="text" name="food_name" class="form-control"
                                                    placeholder="Enter Food Name" value="{{ old('food_name') }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('food_name') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="component">Component</label>
                                                <input type="text" name="component" class="form-control"
                                                    placeholder="Eg- Salt,peeper, chicken"
                                                    value="{{ old('component') }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('component') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea type="text" name="description" class="form-control"
                                                    id="description">{{ old('remarks') }}</textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('description') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="food_image">Food Image</label>
                                                <input type="file" name="food_image" class="form-control"
                                                    onchange="loadFile(event)">
                                                <p class="text-danger">
                                                    {{ $errors->first('food_image') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="" style="display: block;">Preview Image</label>
                                                <img id="output" style="height: 50px;">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="cooking_time">Cooking Time</label>

                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text lh-0-6">Hour</div>
                                                    </div>
                                                    <input type="number" name="cooking_time_hour" min="0" max="12" class="form-control"
                                                        id="inlineFormInputGroup" value="{{ old('cooking_time_hour') ?? '0' }}">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text lh-0-6">Minute</div>
                                                    </div>
                                                    <input type="number" class="form-control" name="cooking_time_min" id="inlineFormInputGroup"
                                                        min="0" max="60" value="{{ old('cooking_time_min') ?? '0' }}">

                                                </div>
                                                <p class="text-danger">
                                                    {{ $errors->first('cooking_time_hour') }}
                                                </p>
                                                <p class="text-danger">
                                                    {{ $errors->first('cooking_time_min') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="food_price">Food Price</label>
                                                <input type="text" name="food_price" class="form-control" placeholder=""
                                                    value="{{ old('food_price') }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('food_price') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">Status: </label>
                                                <span style="margin-right: 5px; font-size: 12px;"> Inactive </span>
                                                <label class="switch pt-0">
                                                    <input type="checkbox" name="status" id="status" value="1"
                                                        {{ old('status') == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                                <span style="margin-left: 5px; font-size: 12px;">Active</span>
                                            </div>
                                            <p class="text-danger">
                                                {{ $errors->first('status') }}
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="alert alert-warning">Note: Status must be active in order to use it in pos</div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm ml-auto">Save</button>
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

        $(document).ready(function() {
            $(".select2").select2();
        });
    </script>
@endpush

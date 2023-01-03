@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>POS Setting </h1>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content mt">
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('posSettings.update', $posSetting->id) }}" method="POST">
                                        @csrf
                                        @method("PUT")
                                        <div class="row mt-2">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="display_products">No. of Products to Display:</label>
                                                    <input type="number" id="display_products" name="display_products"
                                                        class="form-control"
                                                        value="{{ old('display_products', $posSetting->display_products) }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('display_products') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="default_category">Default Category:</label>
                                                    <select name="default_category" class="form-control">
                                                        <option value="">--Select a category--</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ $posSetting->default_category == $category->id ? 'selected' : '' }}>
                                                                {{ $category->category_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('default_category') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="default_customer">Default Customer:</label>
                                                    <select name="default_customer" class="form-control">
                                                        <option value="">--Select a customer--</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}"
                                                                {{ $posSetting->default_customer == $customer->id ? 'selected' : '' }}>
                                                                {{ $customer->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('default_customer') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="default_biller">Default Biller:</label>
                                                    <select name="default_biller" class="form-control">
                                                        <option value="">--Select a biller--</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}"
                                                                {{ $posSetting->default_biller == $user->id ? 'selected' : '' }}>
                                                                {{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('default_biller') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="default_biller">Default Currency:</label>
                                                    <input type="text" class="form-control" name="default_currency" value="{{ old('default_currency', $posSetting->default_currency) }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('default_currency') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-2">
                                                <div class="form-group">
                                                    <label for="show_tax" style="display: block;">Show Tax: </label>
                                                    <span style="margin-right: 5px; font-size: 12px;"> Disable </span>
                                                    <label class="switch pt-0">
                                                        <input type="checkbox" name="show_tax" value="1"
                                                            {{ $posSetting->show_tax == 1 ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <span style="margin-left: 5px; font-size: 12px;">Enable</span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="show_discount" style="display: block;">Show Discount: </label>
                                                    <span style="margin-right: 5px; font-size: 12px;"> Disable </span>
                                                    <label class="switch pt-0">
                                                        <input type="checkbox" name="show_discount" value="1"
                                                            {{ $posSetting->show_discount == 1 ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <span style="margin-left: 5px; font-size: 12px;">Enable</span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="kot_print_after_placing_order">KOT Print After Placing Order: </label>
                                                    <span style="margin-left: 5px; font-size: 15px;"> Disable </span>
                                                    <label class="switch pt-0">
                                                        <input type="checkbox" name="kot_print_after_placing_order" value="1"
                                                            {{ $posSetting->kot_print_after_placing_order == 1 ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <span style="margin-left: 5px; font-size: 15px;">Enable</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group text-center">
                                            <button type="submit" class="btn btn-primary ml-auto"
                                                name="creditSetting">Submit</button>
                                        </div>
                                    </form>
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

@endpush

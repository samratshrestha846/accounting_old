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
                <h1>Edit Product information</h1>
                <div class="btn-bulk">
                    <a href="{{ route('product.index') }}" class="global-btn">View All Items</a>
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
                <div class="col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="productForm" action="{{ route('product.update', $product->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method("PUT")
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="product_name">Product Name<i class="text-danger">*</i>:</label>
                                            <input type="text" id="product_name" name="product_name"
                                                class="form-control"
                                                value="{{ old('product_name', $product->product_name) }}"
                                                placeholder="Product Name" />
                                            <p class="text-danger">
                                                {{ $errors->first('product_name') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="product_code">Product SKU (Unique)<i
                                                    class="text-danger">*</i>:</label>
                                            <input type="text" id="product_code" name="product_code"
                                                class="form-control"
                                                value="{{ old('product_code', $product->product_code) }}"
                                                placeholder="Product Name" />
                                            <p class="text-danger">
                                                {{ $errors->first('product_code') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="category">Product Category<i class="text-danger">*</i>:</label>
                                            <select name="category" class="form-control category">
                                                <option value="">--Select a category--</option>
                                                @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $category->id == $product->category_id ? 'selected' : '' }}>
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
                                            <label for="supplier_name">Supplier
                                                :</label>
                                            <select name="supplier_id" class="form-control supplier">
                                                <option value="">--Select an option--</option>
                                                @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ $supplier->id ==
                                                    $product->supplier_id ? 'selected' : '' }}>
                                                    {{ $supplier->company_name }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger">
                                                {{ $errors->first('supplier_id') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <div class="form-group">
                                            <label for="Status">Has Serial number: </label>
                                            <span style="margin-right: 5px; font-size: 12px;"> No </span>
                                            <label class="switch pt-0">
                                                <input type="checkbox" name="check_serial_number" id="serial_number"
                                                    {{$product->has_serial_number ? 'checked' : ''}}
                                                onchange="updateTableRow(this)">
                                                <span class="slider round"></span>
                                            </label>
                                            <span style="margin-left: 5px; font-size: 12px;">Yes</span>
                                        </div>
                                    </div>
                                    @if ($currentcomp->company->is_importer == 1)
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="declaration_form_no">Decleration Form no<i
                                                        class="text-danger"></i> :</label>
                                                <input type="text" id="declaration_form_no" name="declaration_form_no"
                                                    class="form-control" value="  {{$product->declaration_form_no}}"
                                                    placeholder="Declaratoin form no" style="width:91%;">

                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-12">
                                        {{-- <h4 class="text-center mb-3">
                                            Godown To Product Information
                                        </h4> --}}
                                        <hr>
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h4 class="text-left">
                                                    Godown To Product Information
                                                </h4>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                @php
                                                $selectedFilter = json_decode($product->selected_filter_option);

                                                @endphp
                                                <input type="hidden" name="selected_filter_option"
                                                    value="{{ $selectedFilter }}" id="SelectedFilter">
                                                <div class="dropdown">
                                                    <button class="global-btn dropdown-toggle" type="button"
                                                        data-toggle="dropdown"><span class="dropdown-text"> <i
                                                                class="las la-filter"></i></span>
                                                        <span class="caret"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="#">
                                                                <label>
                                                                    <input name='filter_cols[]' type="checkbox"
                                                                        onclick="showHide(this)" class="option justone"
                                                                        value='floor_no' checked />
                                                                    Floor no
                                                                </label>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <label>
                                                                    <input name='filter_cols[]' type="checkbox"
                                                                        onclick="showHide(this)" class="option justone"
                                                                        value='rack_no' checked />
                                                                    Rack no
                                                                </label>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <label>
                                                                    <input name='filter_cols[]' type="checkbox"
                                                                        onclick="showHide(this)" class="option justone"
                                                                        value='row_no' checked />
                                                                    Row no
                                                                </label>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <label>
                                                                    <input name='filter_cols[]' onclick="showHide(this)"
                                                                        type="checkbox" class="option justone"
                                                                        value='stock' checked />
                                                                    Stock
                                                                </label>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive mt-2">
                                            <table class="table table-bordered table-hover text-center"
                                                id="product_godown">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center" style="width: 35%;"> Godown</th>
                                                        <th class="text-center floor_no" style="width: 10%;"> Floor no.
                                                        </th>
                                                        <th class="text-center rack_no" style="width: 10%;"> Rack no.
                                                        </th>
                                                        <th class="text-center row_no" style="width: 10%;"> Row no.</th>
                                                        <th class="text-center" style="width: 15%;"> Alert On
                                                        </th>
                                                        <th class="text-center stock" style="width: 15%;"> Stock</th>
                                                        <th class="text-center" style="width: 5%;"> Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="godown_body">
                                                    {{-- @php
                                                        dd($godown_products);
                                                    @endphp --}}
                                                    @foreach ($godown_products as $key => $godown_product)
                                                    <tr class="test">
                                                        <td class="" width="300px">
                                                            <select name="godown[]" id="goDown_{{ $key+1 }}"
                                                                class="form-control godown" required>
                                                                <option value="">--Select an option--</option>
                                                                @foreach ($godowns as $godown)
                                                                <option value="{{ $godown->id }}" {{ $godown->id ==
                                                                    $godown_product->godown_id ? 'selected' : '' }}>
                                                                    {{ $godown->godown_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="floor_no[]"
                                                                value="{{ $godown_product->floor_no }}"
                                                                placeholder="Floor no."
                                                                class="form-control text-center">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="rack_no[]"
                                                                value="{{ $godown_product->rack_no }}"
                                                                placeholder="Rack no." class="form-control text-center">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="row_no[]"
                                                                value="{{ $godown_product->row_no }}"
                                                                placeholder="Rack no." class="form-control text-center">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="alert_on[]"
                                                                value="{{ $godown_product->alert_on }}"
                                                                placeholder="Alert On Less Stock"
                                                                class="form-control text-right">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="stock[]"
                                                                value="{{ $godown_product->stock }}"
                                                                placeholder="How Much Stock???"
                                                                class="form-control godown_stock text-right"
                                                                id="stock_{{ $key+1 }}" onkeyup="calculateTotal(1)"
                                                                data-toggle='{{$product->has_serial_number ? ' modal'
                                                                : '' }}' data-target="#stock_add_{{$key+1}}"
                                                                {{$product->has_serial_number ? 'readonly' : ''}}
                                                                step=".01"
                                                            >
                                                            @if($product->has_serial_number)
                                                            <div class='modal fade text-left serial-number__modal'
                                                                id='stock_add_{{$key+1}}' data-id='{{$key+1}}'
                                                                tabindex='-1' role='dialog'>
                                                                <div class='modal-dialog' role='document'
                                                                    style='max-width: 600px;'>
                                                                    <div class='modal-content'>
                                                                        <div class='modal-header text-center'>
                                                                            <h2 class='modal-title'
                                                                                id='exampleModalLabel'>Enter serial
                                                                                number</h2>
                                                                            <button type='button' class='close'
                                                                                data-dismiss='modal' aria-label='Close'>
                                                                                <span aria-hidden='true'>&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class='modal-body'
                                                                            id='modal_body_{{$key+1}}'>
                                                                            @foreach ($godown_product->serialnumbers as
                                                                            $value)
                                                                            <div class="row serial_number_list">
                                                                                <div class='col-md-9 mt-2'>
                                                                                    <input type='text'
                                                                                        class='form-control input__serial-number avaliableSerialNo'
                                                                                        value='{{$value->serial_number}}'
                                                                                        placeholder='Write a serial number'>
                                                                                </div>
                                                                                <div class='col-md-3'>
                                                                                    <button class='btn btn-primary'
                                                                                        type='button'
                                                                                        onclick='removeDom(this,".serial_number_list")'>
                                                                                        <i class='fa fa-trash'></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                            <div class='mt-2'>
                                                                                <a href=''
                                                                                    class='btn btn-primary icon-btn btn-sm'
                                                                                    title='New Serial Number'
                                                                                    onclick='addNewModalRow("{{$key+1}}")'><i
                                                                                        class='fas fa-plus'></i></a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" data-id='{{$key+1}}'
                                                                                class="btn btn-primary submitSerialNumber"
                                                                                data-id="{{ $key+1 }}">Save
                                                                                changes</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-bulk justify-content-center">
                                                                <button class="btn btn-primary icon-btn" type="button"
                                                                    value="Delete" data-id="{{$key+1}}"
                                                                    onclick="deletegodownrow(this)"><i
                                                                        class="fa fa-trash"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-right">
                                                            <label for="reason" class="col-form-label p-0">Total</label>
                                                        </td>
                                                        <td class="text-right">
                                                            <input type="text" id="stockTotal"
                                                                class="form-control text-right" name="stockTotal"
                                                                value="{{ old('total_stock', $product->total_stock) }}"
                                                                readonly="readonly" value />
                                                        </td>
                                                        <td>
                                                            <div class="btn-bulk justify-content-center">
                                                                <a id="add_more" class="btn btn-primary icon-btn"
                                                                    name="add_more"
                                                                    onClick="addGodownRow('godown_body')"><i
                                                                        class="fa fa-plus"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="units">Units for Product<i class="text-danger">*</i>
                                                :</label>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="number" class="form-control" step="any"
                                                        placeholder="Number.." name="primary_number"
                                                        value="{{ old('primary_number', $product->primary_number) }}">
                                                </div>
                                                <div class="col-md-3 pl-0">
                                                    <select name="primary_unit" class="form-control" id="primary_unit">
                                                        @foreach ($units as $unit)
                                                        <option value="{{ $unit->unit_code }}" {{ $unit->unit ==
                                                            $product->primary_unit ? 'selected' : '' }}>
                                                            {{ $unit->unit }}({{ $unit->short_form }})
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <input type="number" class="form-control" step="any"
                                                        placeholder="Number.." name="secondary_number"
                                                        value="{{ old('secondary_number', $product->secondary_number) }}">
                                                </div>
                                                <div class="col-md-3 pl-0">
                                                    <select name="secondary_unit" class="form-control"
                                                        id="secondary_unit">
                                                        <option value=""> -Secondary unit- </option>
                                                        @foreach ($units as $unit)
                                                        <option value="{{ $unit->unit_code }}" {{ $unit->unit ==
                                                            $product->secondary_unit ? 'selected' : '' }}>
                                                            {{ $unit->unit }}({{ $unit->short_form }})
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="brand">Related Brand
                                                :</label>
                                            <select name="brand" class="form-control brand"
                                                id="brand_product">
                                                <option value="">--Select a Brand--</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                                                        {{ $brand->brand_name }}</option>
                                                @endforeach
                                            </select>

                                            <p class="text-danger">
                                                {{ $errors->first('brand') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="series">Related Series :</label>
                                            <select name="series" class="form-control series"
                                                id="series_product">
                                                <option value="">--Select a Brand--</option>
                                                @foreach ($related_series as $series)
                                                    <option value="{{ $series->id }}"
                                                        {{ $series->id == $product->series_id ? 'selected' : '' }}>
                                                        {{ $series->series_name }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger">
                                                {{ $errors->first('series') }}
                                            </p>
                                        </div>
                                    </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="size">Size:</label>
                                                <input type="text" id="size" name="size" class="form-control"
                                                    value="{{ old('size', $product->size) }}" placeholder="Eg: Medium(M)" />
                                                <p class="text-danger">
                                                    {{ $errors->first('size') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="color">Color (Multiple Colors):</label>
                                                <input type="text" id="color" name="color" class="form-control"
                                                    value="{{ old('color', $product->color) }}" placeholder="Eg: Red, Green" />
                                                <p class="text-danger">
                                                    {{ $errors->first('color') }}
                                                </p>
                                            </div>
                                        </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="weight">Weight :</label>
                                            <input type="text" id="weight" name="weight" class="form-control"
                                                value="{{ old('weight', $product->weight) }}"
                                                placeholder="Eg: 25 kilograms" />
                                            <p class="text-danger">
                                                {{ $errors->first('weight') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="lot_no">Lot No. :</label>
                                            <input type="number" id="lot_no" name="lot_no" class="form-control"
                                                value="{{ old('lot_no', $product->lot_no) }}" placeholder="Eg: 1" />
                                            <p class="text-danger">
                                                {{ $errors->first('lot_no') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="warranty_months">Warranty (In months) :</label>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <input type="number" id="warranty_months" name="warranty_months"
                                                        class="form-control"
                                                        value="{{ old('warranty_months', $product->warranty_months) }}"
                                                        placeholder="Eg: 10" />
                                                </div>
                                                <div class="col-md-3 pl-0">
                                                    <input type="text" class="form-control" value="Months" disabled />
                                                </div>
                                            </div>
                                            <p class="text-danger">
                                                {{ $errors->first('warranty_months') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="manufacturing_date">Manufacturing Date</label>
                                            <input type ="date" name="manufacturing_date" value="{{ old('manufacturing_date', $product->manufacturing_date) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="expiry_date">Expiry Date</label>
                                            <input type ="date" name="expiry_date" value="{{ old('expiry_date', $product->expiry_date) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="" style="margin-right: 5px;display: block;">Status: </label>
                                            <span style="margin-left: 5px; font-size: 12px;"> Disable </span>
                                            <label class="switch">
                                                <input type="checkbox" name="status" value="1" {{ $product->status == 1
                                                ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                            <span style="margin-left: 5px; font-size: 12px;">Enable</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="Status" style="display: block;">Property: </label>
                                            <span style="margin-right: 5px; font-size: 12px;"> Refundable </span>
                                            <label class="switch pt-0">
                                                <input type="checkbox" name="refundable" value="1" {{
                                                    $product->refundable == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                            <span style="margin-left: 5px; font-size: 12px;">Non-Refundable</span>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <hr>
                                        <h2>Pricing</h2>
                                    </div>

                                    @if ($currentcomp->company->is_importer == 1)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="original_vendor_price">Original Supplier Price (Rs.) :</label>
                                            <input type="number" step="any" name="original_vendor_price"
                                                class="form-control" id="original_vendor_price"
                                                value="{{ old('original_vendor_price', $product->original_vendor_price) }}"
                                                placeholder="Original Supplier Price" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('original_vendor_price') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="charging_rate">Changing rate (%) (If any):</label>
                                            <input type="number" step="any" name="charging_rate" class="form-control"
                                                id="charging_rate"
                                                value="{{ old('charging_rate', $product->charging_rate) }}"
                                                placeholder="Changing rate in %" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('charging_rate') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="final_vendor_price">Final Supplier Price (Rs.) :</label>
                                            <input type="number" step="any" name="final_vendor_price"
                                                class="form-control" id="final_vendor_price"
                                                value="{{ old('final_vendor_price', $product->final_vendor_price) }}"
                                                placeholder="Final Supplier Price" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('final_vendor_price') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="carrying_cost">Carrying cost (Rs.) (If any):</label>
                                            <input type="number" step="any" name="carrying_cost" class="form-control"
                                                id="carrying_cost"
                                                value="{{ old('carrying_cost', $product->carrying_cost) }}"
                                                placeholder="Carrying cost for product" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('carrying_cost') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="transportation_cost">Transportation cost (Rs.) (If
                                                any):</label>
                                            <input type="number" step="any" name="transportation_cost"
                                                class="form-control" id="transportation_cost"
                                                value="{{ old('transportation_cost', $product->transportation_cost) }}"
                                                placeholder="Transportation cost for product" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('transportation_cost') }}
                                            </p>
                                        </div>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="other_cost">Other cost (Rs.) (If any):</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="form-group">
                                                            <input type="number" step="any" name="miscellaneous_percent"
                                                                class="form-control" id="miscellaneous_percent"
                                                                value="{{ old('miscellaneous_percent', $product->miscellaneous_percent) }}"
                                                                placeholder="In percent (%)" oninput="setRupees()"
                                                                onblur="calculate()">
                                                            <p class="text-danger">
                                                                {{ $errors->first('miscellaneous_percent') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 pl-0">
                                                        <p style="font-size: 14px; font-weight:bold;">%</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="number" step="any" name="other_cost"
                                                        class="form-control" id="other_cost"
                                                        value="{{ old('other_cost', $product->other_cost) }}"
                                                        placeholder="In Rupees" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('other_cost') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product_cost">Cost of Product (Rs.) :</label>
                                            <input type="number" step="any" name="product_cost" class="form-control"
                                                id="product_cost"
                                                value="{{ old('cost_of_product', $product->cost_of_product) }}"
                                                placeholder="Product cost" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('product_cost') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="custom_duty">Custom Duty (%) :</label>
                                        <div class="row">
                                            <div class="col-md-6 pr-2">
                                                <div class="form-group">
                                                    <input type="number" step="any" name="custom_duty" class="form-control"
                                                        id="custom_duty" value="{{ old('custom_duty', $product->custom_duty) }}"
                                                        placeholder="Custom Duty" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('custom_duty') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 pl-2">
                                                <div class="form-group">
                                                    <input type="number" step="any" name="after_custom" class="form-control"
                                                        id="after_custom"
                                                        value="{{ old('after_custom', $product->after_custom) }}"
                                                        placeholder="After Custom" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('after_custom') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="vat">Tax (VAT) :</label>
                                            <select name="tax" class="form-control vat_selected" id="vat_select"
                                                onchange="calculate()">
                                                <option value="">--Select an option--</option>
                                                @foreach ($taxes as $tax)
                                                <option value="{{ $tax->percent }}" {{ $tax->percent == $product->tax ?
                                                    'selected' : '' }}>
                                                    {{ $tax->title }} ({{ $tax->percent }} %)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="total_cost">Total Cost (Rs.) :</label>
                                            <input type="number" step="any" name="total_cost" class="form-control"
                                                id="total_cost" value="{{ old('total_cost', $product->total_cost) }}"
                                                placeholder="Total cost" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('total_cost') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="margin_profit">Profit margin :</label>
                                        <div class="row">
                                            <div class="col-md-6 pr-2">
                                                <div class="form-group">
                                                    <select name="margin_type" class="form-control" id="margin_type"
                                                        onchange="calculate()">
                                                        <option value="percent" {{$product->margin_type == 'percent' ?
                                                            'selected' : ''}}>Percent</option>
                                                        <option value="fixed" {{$product->margin_type == 'fixed' ?
                                                            'selected' : ''}}>Fixed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 pl-2">
                                                <div class="form-group">
                                                    <input type="number" step="any" name="margin_value"
                                                        class="form-control" id="margin_value"
                                                        value="{{ old('margin_value', $product->margin_value) }}"
                                                        placeholder="Profit Margin Value" onkeyup="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('margin_value') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product_price">Selling Price (Rs.) :</label>
                                            <input type="number" step="any" name="product_price" class="form-control"
                                                id="product_price"
                                                value="{{ old('product_price', $product->product_price) }}"
                                                placeholder="Product Price in Rs.">
                                            <p class="text-danger">
                                                {{ $errors->first('product_price') }}
                                            </p>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="original_vendor_price">Purchase Price (Rs.) :</label>
                                            <input type="number" step="any" name="original_vendor_price"
                                                class="form-control" id="original_vendor_price"
                                                value="{{ old('original_vendor_price', $product->original_vendor_price) }}"
                                                placeholder="Purchase Price" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('original_vendor_price') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label for="charging_rate">Changing rate (%) (If any):</label>
                                            <input type="number" step="any" name="charging_rate" class="form-control"
                                                id="charging_rate"
                                                value="{{ old('charging_rate', $product->charging_rate) }}"
                                                placeholder="Changing rate in %" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('charging_rate') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label for="final_vendor_price">Final Supplier Price (Rs.) :</label>
                                            <input type="number" step="any" name="final_vendor_price"
                                                class="form-control" id="final_vendor_price"
                                                value="{{ old('final_vendor_price', $product->final_vendor_price) }}"
                                                placeholder="Final Supplier Price" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('final_vendor_price') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label for="carrying_cost">Carrying cost (Rs.) (If any):</label>
                                            <input type="number" step="any" name="carrying_cost" class="form-control"
                                                id="carrying_cost"
                                                value="{{ old('carrying_cost', $product->carrying_cost) }}"
                                                placeholder="Carrying cost for product" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('carrying_cost') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label for="transportation_cost">Transportation cost (Rs.) (If
                                                any):</label>
                                            <input type="number" step="any" name="transportation_cost"
                                                class="form-control" id="transportation_cost"
                                                value="{{ old('transportation_cost', $product->transportation_cost) }}"
                                                placeholder="Transportation cost for product" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('transportation_cost') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <label for="other_cost">Miscellaneous cost (Rs.) (If any):</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="form-group">
                                                            <input type="number" step="any" name="miscellaneous_percent"
                                                                class="form-control" id="miscellaneous_percent"
                                                                value="{{ old('miscellaneous_percent', $product->miscellaneous_percent) }}"
                                                                placeholder="In percent (%)" oninput="setRupees()"
                                                                onblur="calculate()">
                                                            <p class="text-danger">
                                                                {{ $errors->first('miscellaneous_percent') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 pl-0">
                                                        <p style="font-size: 14px; font-weight:bold;">%</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="number" step="any" name="other_cost"
                                                        class="form-control" id="other_cost"
                                                        value="{{ old('other_cost', $product->other_cost) }}"
                                                        placeholder="In Rupees" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('other_cost') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label for="product_cost">Cost of Product (Rs.) :</label>
                                            <input type="number" step="any" name="product_cost" class="form-control"
                                                id="product_cost"
                                                value="{{ old('cost_of_product', $product->cost_of_product) }}"
                                                placeholder="Product cost" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('product_cost') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <label for="custom_duty">Custom Duty (%) :</label>
                                        <div class="row">
                                            <div class="col-md-6 pr-2">
                                                <div class="form-group">
                                                    <input type="number" step="any" name="custom_duty" class="form-control"
                                                        id="custom_duty" value="{{ old('custom_duty', $product->custom_duty) }}"
                                                        placeholder="Custom Duty" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('custom_duty') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 pl-2">
                                                <div class="form-group">
                                                    <input type="number" step="any" name="after_custom" class="form-control"
                                                        id="after_custom"
                                                        value="{{ old('after_custom', $product->after_custom) }}"
                                                        placeholder="After Custom" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('after_custom') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label for="vat">Tax (VAT) :</label>
                                            <select name="tax" class="form-control vat_selected" id="vat_select"
                                                onchange="calculate()">
                                                <option value="">--Select an option--</option>
                                                @foreach ($taxes as $tax)
                                                <option value="{{ $tax->percent }}" {{ $tax->percent == $product->tax ?
                                                    'selected' : '' }}>
                                                    {{ $tax->title }} ({{ $tax->percent }} %)
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="display: none;">
                                        <div class="form-group">
                                            <label for="total_cost">Total Cost (Rs.) :</label>
                                            <input type="number" step="any" name="total_cost" class="form-control"
                                                id="total_cost" value="{{ old('total_cost', $product->total_cost) }}"
                                                placeholder="Total cost" oninput="calculate()">
                                            <p class="text-danger">
                                                {{ $errors->first('total_cost') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="margin_profit">Profit margin :</label>
                                        <div class="row">
                                            <div class="col-md-6 pr-2">
                                                <div class="form-group">
                                                    <select name="margin_type" class="form-control" id="margin_type"
                                                        onchange="calculate()">
                                                        <option value="percent" {{$product->margin_type == 'percent' ?
                                                            'selected' : ''}}>Percent</option>
                                                        <option value="fixed" {{$product->margin_type == 'fixed' ?
                                                            'selected' : ''}}>Fixed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 pl-2">
                                                <div class="form-group">
                                                    <input type="number" step="any" name="margin_value"
                                                        class="form-control" id="margin_value"
                                                        value="{{ old('margin_value', $product->margin_value) }}"
                                                        placeholder="Profit Margin Value" onkeyup="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('margin_value') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product_price">Selling Price (Rs.) :</label>
                                            <input type="number" step="any" name="product_price" class="form-control"
                                                id="product_price"
                                                value="{{ old('product_price', $product->product_price) }}"
                                                placeholder="Product Price in Rs.">
                                            <p class="text-danger">
                                                {{ $errors->first('product_price') }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-12">
                                        <hr>
                                        <h2>Product Details For invoice</h2>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea name="description" class="form-control" cols="30" rows="5"
                                                placeholder="Something about product...">{{ old('description', $product->description) }}</textarea>
                                            <p class="text-danger">
                                                {{ $errors->first('description') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                @if($product->has_serial_number)
                                @foreach ($godown_products as $key=> $godown_product)
                                @foreach ($godown_product->serialnumbers as $value)
                                <input type="hidden" class="hidden__serial-number" name="serial_numbers_{{$key+1}}[]"
                                    value="{{$value->serial_number}}" readonly>
                                @endforeach
                                @endforeach
                                @endif

                                <div class="btn-bulk d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                </div>

                            </div>
                        </form>
                    </div>
                    <input type="hidden" id="headoption" value="<option value=''>--Select an option--</option>
                            @foreach ($godowns as $godown)
                                <option value='{{ $godown->id }}'>{{ $godown->godown_name }}</option>
                            @endforeach" name="">

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{ route('product.update', $product->id) }}" id="validate"
                                        enctype="multipart/form-data" method="POST">
                                        @csrf
                                        @method("PUT")

                                        <div class="form-group">
                                            <label for="product_image">Attach file (Bills, etc.) :</label>
                                            <input type="file" name="product_image[]" id="product_image"
                                                class="form-control" onchange="loadFile(event)" multiple>
                                        </div>

                                        <input type="submit" id="add_receive" class="btn btn-secondary btn-large"
                                            name="update" value="Save" tabindex="9" />
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <label>Preview Image:</label>
                                    @if (count($product_images) > 0)
                                    <div class="row">
                                        @foreach ($product_images as $image)
                                        <div class="col-md-3 wrapp">
                                            <img src="{{ Storage::disk('uploads')->url($image->location) }}" alt=""
                                                style="height: 50px;">
                                            <form action="{{ route('deleteproductimage', $image->id) }}" method="POST">
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
                                        style="height:50px;">
                                    @endif
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

<script type="text/javascript">
    var selected_filter_array = new Array();
$(".dropdown-menu input[type=checkbox]:checked").each(function () {
    selected_filter_array.push(this.value);

});
$('#SelectedFilter').val(selected_filter_array);
window.selected_filter_array = selected_filter_array;
// console.log(selected_filter_array);

function showHide(that)
{
if($(that).is(":checked")){
    var column = "table ." + $(that).val();
    target =  column;
    var $el = $(target);
    var $cell = $el.closest('th,td');
    var $table = $cell.closest('table');

    // get cell location
    var colIndex = $cell[0].cellIndex + 1;

    // find and hide col index
    $table.find("tbody tr, thead tr, tfoot tr")
    .children(":nth-child(" + colIndex + ")")
    .toggle();

}else{
    var column = "table ." + $(that).val();
    target =  column;
    var $el = $(target);
    var $cell = $el.closest('th,td');
    var $table = $cell.closest('table');

    // get cell location
    var colIndex = $cell[0].cellIndex + 1;

    // find and hide col index
    $table.find("tbody tr, thead tr, tfoot tr")
    .children(":nth-child(" + colIndex + ")")
    .hide();

}

var selected_filter_array = new Array();

$(".dropdown-menu input[type=checkbox]:checked").each(function () {
    selected_filter_array.push(this.value);
});
$('#SelectedFilter').val(selected_filter_array);

window.selected_filter_array = selected_filter_array;

}
</script>

<script>
    window.onload = function() {
            initializedSerialNumberModalEvent();
            initializedSerialNumberClickEvent();
        }

        function addGodownRow(divName) {

            let checkedSerialNumber = $("input[name='check_serial_number']").is(":checked");

            var optionval = $("#headoption").val();
            var row = $("#product_godown tbody tr").length;
            var count = row + 1;
            var limits = 500;
            var tabin = 0;
            if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
            else {
                var newdiv = document.createElement('tr');
                var tabin = "goDown_" + count;
                var tabindex = count * 2;
                let serialModelHtml = serialModelForm(count);
                let dataToggleHtml = checkedSerialNumber ? `data-toggle='modal' data-target="#stock_add_${count}"` : '';

                newdiv = document.createElement("tr");
                newdiv.setAttribute('data-id', count);
                newdiv.innerHTML = `<td>
                                        <select name='godown[]' id='goDown_${count}' class='form-control godown' required>
                                        </select>
                                    </td>
                                    <td style="${selected_filter_array.includes('floor_no') == false ? 'display:none' : ''}">
                                        <input type='text' name='floor_no[]' placeholder='Floor no.' class='form-control text-center'>
                                    </td>
                                    <td style="${selected_filter_array.includes('rack_no') == false ? 'display:none' : ''}">
                                        <input type='text' name='rack_no[]' placeholder='Rack no.' class='form-control text-center'>
                                    </td>
                                    <td style="${selected_filter_array.includes('row_no') == false ? 'display:none' : ''}">
                                        <input type='text' name='row_no[]' placeholder='Row no.' class='form-control text-center'>
                                    </td>
                                    <td>
                                        <input type='number' name='alert_on[]' placeholder='Alert On Stock??' class='form-control text-right'>
                                    </td>
                                    <td>
                                        <input type='number' name='stock[]' class='form-control godown_stock text-right' value placeholder='How Much Stock???' id='stock_${count}' onkeyup='calculateTotal(${count})' ${dataToggleHtml} step='.01'>
                                        ${serialModelHtml}
                                    </td>
                                    <td>
                                        <button  class='btn btn-primary icon-btn btn-sm' style="margin:auto;" type='button' data-id='${count}'  onclick='deletegodownrow(this)'>
                                            <i class='fa fa-trash'></i>
                                        </button>
                                    </td>`;
                document.getElementById(divName).appendChild(newdiv);
                document.getElementById(tabin).focus();
                $("#goDown_" + count).html(optionval);
                count++;
                $("select.form-control:not(.dont-select-me)").select2({
                    // placeholder: "--Select One--",
                    // allowClear: true
                });
            }

            initializedSerialNumberModalEvent();
            initializedSerialNumberClickEvent();
        }

        function dbtvouchercalculation(sl) {
            var gr_tot = 0;
            $(".godown_stock").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
            });
            $("#stockTotal").val(gr_tot.toFixed(0, 2));
        }

        function calculateTotal(sl) {
            var gr_tot1 = 0;
            var gr_tot = 0;
            $(".godown_stock").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
            });
            $(".creditPrice").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot1 += parseFloat(this.value))
            });
            $("#stockTotal").val(gr_tot.toFixed(2, 2));
            $("#creditTotal").val(gr_tot1.toFixed(2, 2));

            if ($(".godown_stock").value != 0) {
                $(".creditPrice").attr('disabled');
            }
        }

        function deletegodownrow(e) {
            let dataId = $(e).attr('data-id');
            var t = $("#product_godown > tbody > tr").length;
            if (1 == t) alert("There only one row you can't delete.");
            else {
                var a = e.parentNode.parentNode;
                a.parentNode.removeChild(a)
            }
            $("input[name='serial_numbers_"+dataId+"[]']").remove();
            calculateTotal()
        }

        function updateTableRow(event) {
            let checkedSerialNumber = $(event).is(":checked");

            $('input.godown_stock').each((index, value) => {
                let element = $(value);

                let hasModal = $('#stock_add_'+(index+1))[0] ? true : false;

                // element.val(0);

                if(!checkedSerialNumber) {
                    element.attr('data-toggle','none');
                    element.attr('data-target','none');
                    element.attr('readonly', false);
                    $('#stock_add_'+(index+1)).remove();
                } else {
                    element.attr('data-toggle','modal');
                    element.attr('data-target','#stock_add_'+(index+1));
                    element.attr('readonly', true);


                    if(!hasModal) {
                        element.after(serialModelForm(index+1));
                    }
                }
            });
        }

        function serialModelForm(count) {

            return `<div class='modal fade text-left serial-number__modal' id='stock_add_${count}' data-id='${count}' tabindex='-1' role='dialog'
                aria-labelledby='exampleModalLabel' aria-hidden='true'>
                <div class='modal-dialog' role='document' style='max-width: 600px;'>
                    <div class='modal-content'>
                        <div class='modal-header text-center'>
                            <h2 class='modal-title' id='exampleModalLabel'>Enter serial number</h2>
                            <button type='button' class='close' data-dismiss='modal'
                                aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <div class='modal-body' id='modal_body_${count}'>
                            <div class='row serial_number_list'>
                                <div class='col-md-9'>
                                    <input type='text' class='form-control input__serial-number avaliableSerialNo' name='serial_numbers_${count}[]' placeholder='Write a serial number'>
                                </div>
                            </div>

                            <div class='mt-2'>
                                <a href='' class='btn btn-primary icon-btn btn-sm' title='New Serial Number' onclick='addNewModalRow(${count})'><i class='fas fa-plus'></i></a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-id='${count}' class="btn btn-primary submitSerialNumber">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>`
        }

        function addNewModalRow(count, value)
        {
            value = value ? value : '';
            event.preventDefault();
            var x = document.getElementById("modal_body_"+count);
            var new_row = document.createElement("div");
            new_row.className = `row serial_number_list`;
            new_row.innerHTML = `
                                <div class='col-md-9 mt-2'>
                                    <input type='text' class='form-control input__serial-number ' value='${value}' placeholder='Write a serial number'>
                                </div>
                                <div class='col-md-3'>
                                        <button  class='btn btn-primary' style="margin:auto;" type='button' onclick='removeDom(this,".serial_number_list")'>
                                            <i class='fa fa-trash'></i>
                                        </button>
                                </div>
                                `;
            var pos = x.childElementCount + 1;
            x.insertBefore(new_row, x.childNodes[pos]);
        }

        //watch for serial_number model open
        function initializedSerialNumberModalEvent() {
            $('.serial-number__modal').on('show.bs.modal', function (event) {
                var modal = $(this);
                let dataId = modal.attr('data-id');

                $(modal).find('.serial_number_list').remove();
                $('#productForm').find("input[name='serial_numbers_"+dataId+"[]']").each((index,value) => {
                    addNewModalRow(dataId, $(value).val());
                });
            })
        }

        //remove dom element
        function removeDom(event,element)
        {
            $(event).parents(element).remove();
        }

        function initializedSerialNumberClickEvent() {
            //submit serial_number buttom
            $('.submitSerialNumber').click(function() {

                let dataId = $(this).attr('data-id');
                let hasErrors = false;
                let AvaliableSerialNo = $('.avaliableSerialNo').map(function() {
                    return this.value;
                }).get();

                let serialNumbers = AvaliableSerialNo;
                let inputSerialNumberHtml = '';

                $(this).parents('#stock_add_' + dataId).find('.input__serial-number').each((index, value)  => {

                    let inputValue = $(value).val();

                    console.log("i am ", serialNumbers.includes(inputValue));
                    console.log("valeu is ", inputValue);

                    if(serialNumbers.includes(inputValue)){
                        hasErrors = true;
                        $(value).after(`<div class='invalid-error text-danger'><span>Serial Number already exist</span></div>`);
                    }

                    inputSerialNumberHtml += `<input type="hidden" class="hidden__serial-number" name="serial_numbers_${dataId}[]" value="${inputValue}" readonly>`;

                    //if the value is not empty
                    if(inputValue){
                        serialNumbers.push(inputValue);
                    }

                });

                if(hasErrors) return;

                $("input[name='serial_numbers_"+dataId+"[]']").remove();
                $("#stock_"+dataId).val(serialNumbers.length);
                $('#productForm').append(inputSerialNumberHtml)
                calculateTotal(dataId);
                $('#stock_add_'+dataId).modal('hide');
            });
        }
</script>

<script>
    var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
</script>

<script>
    $(document).ready(function() {
            $(".supplier").select2();
        });
        $(document).ready(function() {
            $(".godown").select2();
        });

        $(document).ready(function() {
            $(".category").select2();
        });
        $(document).ready(function() {
            $(".brand").select2();
        });

        $(document).ready(function() {
            $(".series").select2();
        });

        $(function() {
            $('.brand').change(function() {
                var brand_id = $(this).children("option:selected").val();

                function fillSeries(series) {
                    document.getElementById("series_product").innerHTML =
                        series.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.series_name}</option>`,
                            '');
                }

                function fetchBrands(brand_id) {
                    var uri = "{{ route('getSeries', ':no') }}";
                    uri = uri.replace(':no', brand_id);
                    $.ajax({
                        url: uri,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var series = response;
                            console.log(series);
                            fillSeries(series);
                        }
                    });
                }
                fetchBrands(brand_id);
            })
        });

        function setRupees() {
            var miscellaneous_percent = document.getElementById("miscellaneous_percent");

            if (miscellaneous_percent.value > 0) {
                var takeout = parseFloat(final_vendor_price.value) + parseFloat(carrying_cost.value) + parseFloat(
                    transportation_cost.value);

                var percent_amount = takeout * (miscellaneous_percent.value / 100);
                other_cost.value = percent_amount;
            }
        }

        function calculate() {
            var original_vendor_price = document.getElementById("original_vendor_price");
            var charging_rate = document.getElementById("charging_rate");
            var final_vendor_price = document.getElementById("final_vendor_price");
            var carrying_cost = document.getElementById("carrying_cost");
            var transportation_cost = document.getElementById("transportation_cost");
            var other_cost = document.getElementById("other_cost");
            var product_cost = document.getElementById("product_cost");
            var custom_duty = document.getElementById("custom_duty");
            var after_custom = document.getElementById("after_custom");
            var vat = document.getElementById("vat_select");
            var total_cost = document.getElementById("total_cost");
            var margin_type = document.getElementById("margin_type");
            var margin_value = document.getElementById("margin_value");
            // var margin_profit = document.getElementById("margin_profit");
            var product_price = document.getElementById("product_price");

            if (original_vendor_price.value == "" || original_vendor_price == 0)
            {
                original_vendor_price.value = 0;
                final_vendor_price.value = 0;
                product_cost.value = 0;
                after_custom.value = 0;
                total_cost.value = 0;
                product_price.value = 0;
            }
            else if (original_vendor_price.value > 0 && charging_rate.value > 0)
            {
                var charging_rate_amount = charging_rate.value;
                final_vendor_price.value = parseFloat(original_vendor_price.value) * parseFloat(charging_rate_amount);
                product_cost.value = final_vendor_price.value;
                after_custom.value = final_vendor_price.value;
                total_cost.value = final_vendor_price.value;
                product_price.value = product_cost.value;
            }
            else
            {
                final_vendor_price.value = original_vendor_price.value;
                product_cost.value = final_vendor_price.value;
                after_custom.value = final_vendor_price.value;
                total_cost.value = final_vendor_price.value;
                product_price.value = product_cost.value;
            }

            if (carrying_cost.value > 0)
            {
                product_cost.value = parseFloat(product_cost.value) + parseFloat(carrying_cost.value);
                after_custom.value = product_cost.value;
                total_cost.value = product_cost.value;
                product_price.value = product_cost.value;
            }

            if (transportation_cost.value > 0)
            {
                product_cost.value = parseFloat(product_cost.value) + parseFloat(transportation_cost.value);
                after_custom.value = product_cost.value;
                total_cost.value = product_cost.value;
                product_price.value = product_cost.value;
            }

            if (other_cost.value > 0)
            {
                var new_cost = parseFloat(product_cost.value) + parseFloat(other_cost.value);
                var percent_cost = parseFloat(final_vendor_price.value) + parseFloat(carrying_cost.value) + parseFloat(
                    transportation_cost.value);
                percent = (parseFloat(other_cost.value) / percent_cost) * 100;
                miscellaneous_percent.value = percent;
                product_cost.value = new_cost;
                after_custom.value = new_cost;
                total_cost.value = new_cost;
                product_price.value = new_cost;
            }

            if (custom_duty.value > 0)
            {
                var custom_duty_amount = product_cost.value * (custom_duty.value / 100);
                after_custom.value = parseFloat(product_cost.value) + parseFloat(custom_duty_amount);
                total_cost.value = parseFloat(product_cost.value) + parseFloat(custom_duty_amount);
                product_price.value = parseFloat(product_cost.value) + parseFloat(custom_duty_amount);
            }

            if (vat.value > 0)
            {
                var vat_amount = after_custom.value * (vat.value / 100);
                total_cost.value = parseFloat(after_custom.value) + parseFloat(vat_amount);
                product_price.value = parseFloat(after_custom.value) + parseFloat(vat_amount);
            }

            if (margin_value.value > 0 && margin_type.value == "percent")
            {
                var profit_margin_amount = total_cost.value * (margin_value.value / 100);
                product_price.value = parseFloat(total_cost.value) + parseFloat(profit_margin_amount);
            }
            else if(margin_value.value > 0 && margin_type.value == "fixed")
            {
                var profit_margin_amount = margin_value.value;
                product_price.value = parseFloat(total_cost.value) + parseFloat(profit_margin_amount);
            }
        }

        $(function() {
            $('.secondary_unit').change(function() {
                var secondary_unit = $(this).children("option:selected").val();

                if (secondary_unit == "")
                {
                    document.getElementById("secondary_unit_selling_price").readOnly = true;
                    document.getElementById("secondary_code").readOnly = true;
                } else
                {
                    document.getElementById("secondary_unit_selling_price").removeAttribute("readonly");
                    document.getElementById("secondary_code").removeAttribute("readonly");
                }
            })
        });
</script>
@endpush

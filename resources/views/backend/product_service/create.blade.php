@extends('backend.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Fill Products information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('product.index') }}" class="global-btn">View All Products</a>
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
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="productForm" action="{{ route('product.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="product_name">Product Name<i class="text-danger">*</i>
                                                    :</label>
                                                <input type="text" id="product_name" name="product_name"
                                                    class="form-control" value="{{ old('product_name') }}"
                                                    placeholder="Product Name" style="width:91%;">
                                                <p class="text-danger">
                                                    {{ $errors->first('product_name') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="product_code">Product SKU (Unique)<i
                                                        class="text-danger">*</i> :</label>
                                                <input type="text" id="product_code" name="product_code"
                                                    class="form-control" value="{{ old('product_code', $product_code) }}"
                                                    placeholder="Product SKU" style="width:91%;">
                                                <p class="text-danger procode_error hide">Code is already used. Use
                                                    Different code.</p>
                                                <p class="text-danger">
                                                    {{ $errors->first('product_code') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="category">Product Category<i class="text-danger">*</i>
                                                    :</label>
                                                <div class="row">
                                                    <div class="col-md-9 pr-0">
                                                        <select name="category" class="form-control category"
                                                            id="category_product">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3" style="padding-left:7px;">
                                                        <button type="button" data-toggle='modal'
                                                            data-target='#category_add' data-toggle='tooltip'
                                                            data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                            title="Add New Category"><i
                                                                class="fas fa-plus"></i></button>
                                                    </div>
                                                </div>
                                                <p class="text-danger">
                                                    {{ $errors->first('category') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="supplier_name">Supplier
                                                    :</label>
                                                <div class="row">
                                                    <div class="col-md-9 pr-0">
                                                        <select name="supplier_id" id="vendor"
                                                            class="form-control supplier">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3" style="padding-left:7px;">
                                                        <button type="button" data-toggle='modal'
                                                            data-target='#supplieradd' data-toggle='tooltip'
                                                            data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                            title="Add New Supplier"><i
                                                                class="fas fa-plus"></i></button>
                                                    </div>
                                                </div>
                                                {{-- <p class="text-danger">
                                                    {{ $errors->first('supplier_id') }}
                                                </p> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <div class="form-group">
                                                <label for="Status">Has Serial number: </label>
                                                <span style="margin-right: 5px; font-size: 12px;"> No </span>
                                                <label class="switch pt-0">
                                                    <input type="checkbox" name="check_serial_number" id="serial_number" onchange="updateTableRow(this)" {{ old('check_serial_number') == "on" ? 'checked' : '' }}>
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
                                                        class="form-control" value="{{ old('declaration_form_no') }}"
                                                        placeholder="Declaratoin form no" style="width:91%;">

                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-12">
                                            <hr>
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <h4 style="text-align: left;">
                                                        Godown To Product Information
                                                    </h4>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <input type="hidden" name="selected_filter_option" id="SelectedFilter">
                                                    <div class="dropdown">
                                                        <button class="global-btn dropdown-toggle" type="button" data-toggle="dropdown"><span class="dropdown-text"> <i class="las la-filter"></i></span>
                                                        <span class="caret"></span></button>
                                                        <ul class="dropdown-menu">
                                                            <li class="divider"></li>
                                                            <li>
                                                                <a href="#">
                                                                    <label>
                                                                        <input name='filter_cols[]' type="checkbox" onclick="showHide(this)" class="option justone" value='floor_no' checked/>
                                                                        Floor no
                                                                    </label>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#">
                                                                    <label>
                                                                        <input name='filter_cols[]' type="checkbox" onclick="showHide(this)" class="option justone" value='rack_no' checked/>
                                                                        Rack no
                                                                    </label>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#">
                                                                    <label>
                                                                        <input name='filter_cols[]' type="checkbox" onclick="showHide(this)" class="option justone" value='row_no' checked/>
                                                                        Row no
                                                                    </label>
                                                                </a>
                                                            </li>
                                                            {{-- <li>
                                                                <a href="#">
                                                                    <label>
                                                                        <input name='filter_cols[]' onclick="showHide(this)" type="checkbox" class="option justone" value='stock' checked/>
                                                                        Stock
                                                                    </label>
                                                                </a>
                                                            </li> --}}
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
                                                            <th class="text-center floor_no" style="width: 10%;"> Floor no.</th>
                                                            <th class="text-center rack_no" style="width: 10%;"> Rack no.</th>
                                                            <th class="text-center row_no" style="width: 10%;"> Row no.</th>
                                                            <th class="text-center" style="width: 15%;"> Alert On</th>
                                                            <th class="text-center stock" style="width: 15%;"> Stock</th>
                                                            <th class="text-center" style="width: 5%;"> Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="godown_body">
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td class="floor_no"></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right">
                                                                <label for="reason" class="col-form-label p-0">Total</label>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" id="stockTotal"
                                                                    class="form-control text-right" name="stockTotal"
                                                                    value="" readonly="readonly" value />
                                                            </td>
                                                            <td>
                                                                <a id="add_more" class="btn btn-primary icon-btn" style="margin:auto;"
                                                                    name="add_more"
                                                                    onClick="addGodownRow('godown_body')"><i
                                                                        class="fa fa-plus"></i></a>
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
                                                            placeholder="Number.." name="primary_number" value="{{ old('primary_number') }}">
                                                    </div>
                                                    <div class="col-md-3 pl-0">
                                                        <select name="primary_unit" class="form-control" id="primary_unit">
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control" step="any" placeholder="Number.." name="secondary_number" value="{{ old('secondary_number') }}">
                                                    </div>
                                                    <div class="col-md-3 pl-0">
                                                        <div class="row">
                                                            <div class="col-md-9 pr-2">
                                                                <select name="secondary_unit" class="form-control" id="secondary_unit">
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 pl-0" style="padding-left:7px;">
                                                                <button type="button" data-toggle='modal'
                                                                    data-target='#unit_add_service' data-toggle='tooltip'
                                                                    data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                                    title="Add New Unit"><i class="fas fa-plus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="brand">Related Brand :</label>
                                                <select name="brand" class="form-control brand"
                                                    id="brand_product">
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('brand') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="series">Related Series :</label>
                                                <select name="series" class="form-control series" id="series_product">
                                                    <option value="">--Select a Brand--</option>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('series') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="size">Size :</label>
                                                <input type="text" id="size" name="size" class="form-control" value="{{ old('size') }}" placeholder="Eg: Medium(M)" />
                                                <p class="text-danger">
                                                    {{ $errors->first('size') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="color">Color (Multiple colors) :</label>
                                                <input type="text" id="color" name="color" class="form-control" value="{{ old('color') }}" placeholder="Eg: Red, Green" />
                                                <p class="text-danger">
                                                    {{ $errors->first('color') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="weight">Weight  :</label>
                                                <input type="text" id="weight" name="weight" class="form-control" value="{{ old('weight') }}" placeholder="Eg: 25 kilograms" />
                                                <p class="text-danger">
                                                    {{ $errors->first('weight') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="lot_no">Lot No. :</label>
                                                <input type="number" id="lot_no" name="lot_no" class="form-control" value="{{ old('lot_no') }}" placeholder="Eg: 1" />
                                                <p class="text-danger">
                                                    {{ $errors->first('lot_no') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="warranty_months">Warranty (In months) :</label>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <input type="number" id="warranty_months" name="warranty_months" class="form-control" value="{{ old('warranty_months') }}" placeholder="Eg: 10" />
                                                    </div>
                                                    <div class="col-md-5 pl-0">
                                                        <input type="text" class="form-control" value="Months" disabled/>
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
                                                <input type ="date" name="manufacturing_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="expiry_date">Expiry Date</label>
                                                <input type ="date" name="expiry_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="image">Product image (Multiple) :</label>
                                                <input type="file" name="product_image[]" class="form-control" multiple onchange="loadFile(event)">
                                                <p class="text-danger">
                                                    {{ $errors->first('product_image') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="" style="vertical-align: top;">Preview Image</label>
                                                <img id="output" style="height: 50px;">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Status" style="display:block;">Status: </label>
                                                <span style="margin-right: 5px; font-size: 12px;"> Disable </span>
                                                <label class="switch pt-0">
                                                    <input type="checkbox" name="status" value="1" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                                <span style="margin-left: 5px; font-size: 12px;">Enable</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Status" style="display:block;">Property: </label>
                                                <span style="margin-right: 5px; font-size: 12px;"> Refundable </span>
                                                <label class="switch pt-0">
                                                    <input type="checkbox" name="refundable" value="1" {{ old('refundable') == 1 ? 'checked' : '' }}>
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
                                                        class="form-control" id="original_vendor_price" value="{{ old('original_vendor_price') ?? 0 }}"
                                                        placeholder="Original Supplier Price" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('original_vendor_price') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="charging_rate">Changing rate (%) (If any):</label>
                                                    <input type="number" step="any" name="charging_rate"
                                                        class="form-control" id="charging_rate" value="{{ old('charging_rate') ?? 0 }}"
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
                                                        class="form-control" id="final_vendor_price" value="{{ old('final_vendor_price') ?? 0}}"
                                                        placeholder="Final Supplier Price" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('final_vendor_price') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="carrying_cost">Carrying cost (Rs.) (If any):</label>
                                                    <input type="number" step="any" name="carrying_cost"
                                                        class="form-control" id="carrying_cost" value="{{ old('carrying_cost') }}"
                                                        placeholder="Carrying cost for product" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('carrying_cost') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="transportation_cost">Transportation cost (Rs.) (If any):</label>
                                                    <input type="number" step="any" name="transportation_cost"
                                                        class="form-control" id="transportation_cost" value="{{ old('transportation_cost') }}"
                                                        placeholder="Transportation cost for product"
                                                        oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('transportation_cost') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="miscellaneous_percent">Miscellaneous cost (If any):</label>
                                                    <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="row">
                                                                    <div class="col-md-10">
                                                                        <div class="form-group">
                                                                            <input type="number" step="any"
                                                                                name="miscellaneous_percent"
                                                                                class="form-control"
                                                                                id="miscellaneous_percent" value="{{ old('miscellaneous_percent') }}"
                                                                                placeholder="In percent"
                                                                                oninput="setRupees()" onblur="calculate()">
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
                                                                        class="form-control" id="other_cost" value="{{ old('other_cost') }}"
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
                                                    <input type="number" step="any" name="product_cost"
                                                        class="form-control" id="product_cost" value="{{ old('product_cost') ?? 0 }}"
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
                                                            <input type="number" step="any" name="custom_duty"
                                                                class="form-control" id="custom_duty" value="{{ old('custom_duty') }}"
                                                                placeholder="Custom Duty" oninput="calculate()">
                                                            <p class="text-danger">
                                                                {{ $errors->first('custom_duty') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pl-2">
                                                        <div class="form-group">
                                                            <input type="number" step="any" name="after_custom"
                                                                class="form-control" id="after_custom" value="{{ old('after_custom') }}"
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
                                                    <select name="tax" class="form-control vat_selected"
                                                        id="vat_select" onchange="calculate()">
                                                        <option value="">--Select an option--</option>
                                                        @foreach ($taxes as $tax)
                                                            <option value="{{ $tax->id }}" data-percent="{{$tax->percent}}">{{ $tax->title }}
                                                                ({{ $tax->percent }} %)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="total_cost">Total Cost (Rs.):</label>
                                                    <input type="number" step="any" name="total_cost"
                                                        class="form-control" id="total_cost" value="{{ old('total_cost') ?? 0 }}"
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
                                                            <select name="margin_type" class="form-control" id="margin_type" onchange="calculate()">
                                                                <option value="percent"{{ old('margin_type') == "percent" ? 'selected' : '' }}>Percent</option>
                                                                <option value="fixed"{{ old('margin_type') == "fixed" ? 'selected' : '' }}>Fixed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pl-2">
                                                        <div class="form-group">
                                                            <input type="number" step="any" name="margin_value"
                                                                class="form-control" id="margin_value" value="{{ old('margin_value') ?? 0}}"
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
                                                    <input type="number" step="any" name="product_price"
                                                        class="form-control" id="product_price" value="{{ old('product_price') ?? 0}}"
                                                        placeholder="Product Price in Rs.">
                                                    <p class="text-danger">
                                                        {{ $errors->first('product_price') }}
                                                    </p>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-3">
                                                <label for="product_price">Secondary Unit Selling Price (Rs.)<i
                                                        class="text-danger">*</i> :</label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="number" step="any" name="secondary_unit_selling_price"
                                                        class="form-control" id="secondary_unit_selling_price" value=""
                                                        placeholder="Secondary Unit Selling Price in Rs." readonly="readonly">
                                                    <p class="text-danger">
                                                        {{ $errors->first('secondary_unit_selling_price') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3"></div> --}}

                                            {{-- <div class="col-md-3">
                                                <label for="secondary_code">Secondary Unit Code<i
                                                        class="text-danger">*</i> :</label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" id="secondary_code" name="secondary_code"
                                                        class="form-control" value="{{ $secondary_code }}"
                                                        placeholder="Secondary Code">
                                                    <p class="text-danger secondarycode_err hide">Code is already used. Use
                                                        Different code.</p>
                                                    <p class="text-danger">
                                                        {{ $errors->first('secondary_code') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3"></div> --}}

                                        @else
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="original_vendor_price">Purchase Price (Rs.) :</label>
                                                    <input type="number" step="any" name="original_vendor_price"
                                                        class="form-control" id="original_vendor_price" value="{{ old('original_vendor_price') ?? 0 }}"
                                                        placeholder="Purchase Price" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('original_vendor_price') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="display: none;">
                                                <div class="form-group">
                                                    <label for="charging_rate">Changing rate (%) (If any):</label>
                                                    <input type="number" step="any" name="charging_rate"
                                                        class="form-control" id="charging_rate" value="{{ old('charging_rate') ?? 0 }}"
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
                                                        class="form-control" id="final_vendor_price" value="{{ old('final_vendor_price') ?? 0 }}"
                                                        placeholder="Final Supplier Price" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('final_vendor_price') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="display: none;">
                                                <div class="form-group">
                                                    <label for="carrying_cost">Carrying cost (Rs.) (If any):</label>
                                                    <input type="number" step="any" name="carrying_cost"
                                                        class="form-control" id="carrying_cost" value="{{ old('carrying_cost') }}"
                                                        placeholder="Carrying cost for product" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('carrying_cost') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="display: none;">
                                                <div class="form-group">
                                                    <label for="transportation_cost">Transportation cost (Rs.) (If any):</label>
                                                    <input type="number" step="any" name="transportation_cost"
                                                        class="form-control" id="transportation_cost" value="{{ old('transportation_cost') }}"
                                                        placeholder="Transportation cost for product"
                                                        oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('transportation_cost') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="display: none;">
                                                <label for="miscellaneous_percent">Miscellaneous cost (If any):</label>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <input type="number" step="any"
                                                                        name="miscellaneous_percent"
                                                                        class="form-control"
                                                                        id="miscellaneous_percent" value="{{ old('miscellaneous_percent') }}"
                                                                        placeholder="In percent (%)"
                                                                        oninput="setRupees()" onblur="calculate()">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('miscellaneous_percent') }}
                                                                    </p>
                                                                </div>
                                                    </div>
                                                    <div class="col-md-2 pl-0">
                                                        <p style="font-size: 14px; font-weight:bold;">%</p>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <input type="number" step="any" name="other_cost"
                                                                class="form-control" id="other_cost" value="{{ old('other_cost') }}"
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
                                                    <input type="number" step="any" name="product_cost"
                                                        class="form-control" id="product_cost" value="{{ old('product_cost') ?? 0 }}"
                                                        placeholder="Product cost" oninput="calculate()">
                                                    <p class="text-danger">
                                                        {{ $errors->first('product_cost') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="display: none;">
                                                <label for="custom_duty">Custom Duty (%) :</label>
                                                <div class="row">
                                                    <div class="col-md-6 pr-2" style="display:none;">
                                                        <div class="form-group">
                                                            <input type="number" step="any" name="custom_duty"
                                                                class="form-control" id="custom_duty" value="{{ old('custom_duty') }}"
                                                                placeholder="Custom Duty" oninput="calculate()">
                                                            <p class="text-danger">
                                                                {{ $errors->first('custom_duty') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pl-2">
                                                        <div class="form-group">
                                                            <input type="number" step="any" name="after_custom"
                                                                class="form-control" id="after_custom" value="{{ old('after_custom') }}"
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
                                                    <select name="tax" class="form-control vat_selected"
                                                        id="vat_select" onchange="calculate()">
                                                        <option value="">--Select an option--</option>
                                                        @foreach ($taxes as $tax)
                                                            <option value="{{ $tax->percent }}">{{ $tax->title }}
                                                                ({{ $tax->percent }} %)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="display: none;">
                                                <div class="form-group">
                                                    <label for="total_cost">Total Cost (Rs.):</label>
                                                    <input type="number" step="any" name="total_cost"
                                                        class="form-control" id="total_cost" value="{{ old('total_cost') ?? 0 }}"
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
                                                            <select name="margin_type" class="form-control" id="margin_type" onchange="calculate()">
                                                                <option value="percent"{{ old('margin_type') == "percent" ? 'selected' : '' }}>Percent</option>
                                                                <option value="fixed"{{ old('margin_type') == "fixed" ? 'selected' : '' }}>Fixed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pl-2">
                                                        <div class="form-group">
                                                            <input type="number" step="any" name="margin_value"
                                                                class="form-control" id="margin_value" value="{{ old('margin_value') ?? 0 }}"
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
                                                    <input type="number" step="any" name="product_price"
                                                        class="form-control" id="product_price" value="{{ old('product_price') ?? 0 }}"
                                                        placeholder="Selling Price in Rs.">
                                                    <p class="text-danger">
                                                        {{ $errors->first('product_price') }}
                                                    </p>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-3">
                                                <label for="product_price">Secondary Unit Selling Price (Rs.)<i
                                                        class="text-danger">*</i> :</label>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="number" step="any" name="secondary_unit_selling_price"
                                                        class="form-control" id="secondary_unit_selling_price" value=""
                                                        placeholder="Secondary Unit Selling Price in Rs." readonly="readonly">
                                                    <p class="text-danger">
                                                        {{ $errors->first('secondary_unit_selling_price') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3"></div> --}}

                                            {{-- <div class="col-md-3">
                                                <label for="product_price">Secondary Unit Code<i
                                                        class="text-danger">*</i> :</label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" id="secondary_code" name="secondary_code"
                                                        class="form-control" value="{{ $secondary_code }}"
                                                        placeholder="Secondary Code">
                                                    <p class="text-danger secondarycode_err hide">Code is already used. Use
                                                        Different code.</p>
                                                    <p class="text-danger">
                                                        {{ $errors->first('secondary_code') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3"></div> --}}
                                        @endif
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
                                        <div class="col-md-12">
                                            <hr>
                                            <h2>Product Details For invoice</h2>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea name="description" class="form-control" cols="30" rows="5" placeholder="Product Details for invoice..">{{ old('description') }}</textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('description') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group  btn-bulk d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary btn-large">Submit</button>
                                        <button type="submit" class="btn btn-secondary btn-large" name="saveandcontinue" value="1">Submit And Continue</button>
                                    </div>
                                </form>
                                <div class='modal fade text-left' id='category_add' tabindex='-1' role='dialog'
                                    aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document' style="max-width: 800px;">
                                        <div class='modal-content'>
                                            <div class='modal-header text-center'>
                                                <h2 class='modal-title' id='exampleModalLabel'>Add Category</h2>
                                                <button type='button' class='close' data-dismiss='modal'
                                                    aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                <form action="" method="POST" id="product_category_form">
                                                    @csrf
                                                    @method("POST")
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="category_name">Category Name<i
                                                                        class="text-danger">*</i> </label>
                                                                <input type="text" name="category_name"
                                                                    class="form-control"
                                                                    placeholder="Enter Category Name"
                                                                    id="product_category_name" required>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('category_name') }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="category_code">Category Code (Code must be
                                                                    unique)<i class="text-danger">*</i> </label>
                                                                <input type="text" name="category_code"
                                                                    class="form-control"
                                                                    value="{{ $category_code }}"
                                                                    placeholder="Enter Category Code"
                                                                    id="product_category_code" required>
                                                                <p class="text-danger categorycode_error hide">Code is
                                                                    already used. Use Different code.</p>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('category_code') }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <img id="categoryproduct" style="max-height: 100px;">
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-secondary btn-sm"
                                                        name="modal_button">Save</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='modal fade text-left' id='brand_add' tabindex='-1' role='dialog'
                                    aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document' style="max-width: 800px;">
                                        <div class='modal-content'>
                                            <div class='modal-header text-center'>
                                                <h2 class='modal-title' id='exampleModalLabel'>Add Brand</h2>
                                                <button type='button' class='close' data-dismiss='modal'
                                                    aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                <form action="{{ route('brand.store') }}" method="POST"
                                                    enctype="multipart/form-data" id="product_brand_form">
                                                    @csrf
                                                    @method("POST")
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="brand_name">Brand Name<i
                                                                        class="text-danger">*</i> </label>
                                                                <input type="text" name="brand_name"
                                                                    class="form-control"
                                                                    placeholder="Enter Category Name"
                                                                    id="product_brand_name" required>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('brand_name') }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="brand_code">Brand Code (Code must be
                                                                    unique)<i class="text-danger">*</i></label>
                                                                <input type="text" name="brand_code"
                                                                    class="form-control" value="{{ $brand_code }}"
                                                                    id="brand_code" placeholder="Enter Brand Code">
                                                                <p class="text-danger brandcode_error hide">Code is
                                                                    already used. Use Different code.</p>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('brand_code') }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="brand_logo">Brand Logo<i
                                                                        class="text-danger">*</i> </label>
                                                                <input type="file" name="brand_logo"
                                                                    class="form-control"
                                                                    onchange="loadFilebrandproduct(event)"
                                                                    id="product_brand_logo" required>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('brand_logo') }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <img id="brandproduct" style="max-height: 80px;">
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-secondary"
                                                        name="brand_modal_button">Save</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='modal fade text-left' id='unit_add_service' tabindex='-1' role='dialog'
                                    aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document' style="max-width: 800px;">
                                        <div class='modal-content'>
                                            <div class='modal-header text-center'>
                                                <h2 class='modal-title' id='exampleModalLabel'>Add Unit</h2>
                                                <button type='button' class='close' data-dismiss='modal'
                                                    aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                <form action="" method="POST" enctype="multipart/form-data"
                                                    id="unit_store_form">
                                                    {{-- @csrf --}}
                                                    @method("POST")
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="unit">Unit Name:<i
                                                                        class="text-danger">*</i> </label>
                                                                <input type="text" name="unit" class="form-control"
                                                                    placeholder="Enter Unit Name" id="unit" required>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('unit') }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="unit_code">Unit Code:<i
                                                                        class="text-danger">*</i> </label>
                                                                <input type="text" name="unit_code"
                                                                    class="form-control" value="{{ $unit_code }}"
                                                                    placeholder="Enter Unit Name" id="unit_code"
                                                                    required>
                                                                <p class="text-danger unitcode_error hide">Code is
                                                                    already used. Use Different code.</p>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('unit_code') }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="short_form">Short form<i
                                                                        class="text-danger">*</i> </label>
                                                                <input type="text" name="short_form"
                                                                    class="form-control"
                                                                    placeholder="Enter Category Code" id="short_form"
                                                                    required>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('short_form') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-secondary"
                                                        name="modal_button">Save</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='modal fade text-left' id='supplieradd' tabindex='-1' role='dialog'
                                    aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document' style="max-width: 1000px;">
                                        <div class='modal-content'>
                                            <div class='modal-header text-center'>
                                                <h2 class='modal-title' id='exampleModalLabel'>Add Supplier</h2>
                                                <button type='button' class='close' data-dismiss='modal'
                                                    aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                <form action="" method="POST" id="supplier_form">
                                                    {{-- @csrf --}}
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <p class="card-title">Company Details</p>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group"
                                                                        id="company_name_group">
                                                                        <label for="name">Company Name<i
                                                                                class="text-danger">*</i></label>
                                                                        <input type="text" name="company_name"
                                                                            class="form-control"
                                                                            value="{{ old('company_name') }}"
                                                                            placeholder="Enter Company Name"
                                                                            id="company_name" required>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('company_name') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="name">Company Code (Code has to be
                                                                            unique)</label>
                                                                        <input type="text" name="supplier_code"
                                                                            class="form-control"
                                                                            value="{{ $company_code }}"
                                                                            placeholder="Enter Company Code"
                                                                            id="company_code">
                                                                        <p class="text-danger companycode_error hide">
                                                                            Code is already used. Use Different code.
                                                                        </p>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('supplier_code') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group"
                                                                        id="company_email_group">
                                                                        <label for="company_email">Company Email</label>
                                                                        <input type="email" name="company_email"
                                                                            class="form-control"
                                                                            value="{{ old('company_email') }}"
                                                                            placeholder="Enter Company Email"
                                                                            id="company_email">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('company_email') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group"
                                                                        id="company_phone_group">
                                                                        <label for="company_phone">Company Phone</label>
                                                                        <input type="text" name="company_phone"
                                                                            class="form-control"
                                                                            value="{{ old('company_phone') }}"
                                                                            placeholder="Enter Company Contact no."
                                                                            id="company_phone">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('company_phone') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="pan_vat_group">
                                                                        <label for="pan_vat">PAN No./VAT No.
                                                                            (Optional)</label>
                                                                        <input type="text" name="pan_vat"
                                                                            class="form-control"
                                                                            value="{{ old('pan_vat') }}"
                                                                            placeholder="Enter Company PAN or VAT No."
                                                                            id="pan_vat">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="province_id_group">
                                                                        <label for="province">Province no.</label>
                                                                        <select name="province"
                                                                            class="form-control province"
                                                                            id="province_id">
                                                                            <option value="">--Select a province--
                                                                            </option>
                                                                            @foreach ($provinces as $province)
                                                                                <option value="{{ $province->id }}">
                                                                                    {{ $province->eng_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('province') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="district_group">
                                                                        <label for="district">Districts</label>
                                                                        <select name="district" class="form-control"
                                                                            id="district">
                                                                            <option value="">--Select a province first--
                                                                            </option>
                                                                        </select>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('district') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group"
                                                                        id="company_address_group">
                                                                        <label for="company_address">Company Local
                                                                            Address</label>
                                                                        <input type="text" name="company_address"
                                                                            class="form-control"
                                                                            value="{{ old('company_address') }}"
                                                                            placeholder="Company Address"
                                                                            id="company_address">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('company_address') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="Payment Type">Is Client:</label><br>
                                                                        <span style="margin-right: 5px; font-size: 12px;">NO</span>
                                                                            <label class="switch pt-0">
                                                                                <input type="checkbox" name="is_client" value="1" {{ old('is_client') == 1 ? 'checked' : '' }}>
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        <span style="margin-left: 5px; font-size: 12px;">YES</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h2>Ledger Account Details</h2>
                                                        </div>
                                                        <div class="card-body">
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
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <p class="card-title">Concerned Person Details</p>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group"
                                                                        id="concerned_name_group">
                                                                        <label for="concerned_name">Name</label>
                                                                        <input type="text" name="concerned_name"
                                                                            class="form-control"
                                                                            value="{{ old('concerned_name') }}"
                                                                            placeholder="Enter Name"
                                                                            id="concerned_name">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('concerned_name') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group"
                                                                        id="concerned_phone_group">
                                                                        <label for="concerned_phone">Phone</label>
                                                                        <input type="text" name="concerned_phone"
                                                                            class="form-control"
                                                                            value="{{ old('concerned_phone') }}"
                                                                            placeholder="Enter Phone"
                                                                            id="concerned_phone">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('concerned_phone') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group"
                                                                        id="concerned_email_group">
                                                                        <label for="concerned_email">Email</label>
                                                                        <input type="email" name="concerned_email"
                                                                            class="form-control"
                                                                            value="{{ old('concerned_email') }}"
                                                                            placeholder="Enter Email"
                                                                            id="concerned_email">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('concerned_email') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="designation_group">
                                                                        <label for="designation">Designation</label>
                                                                        <input type="text" name="designation"
                                                                            class="form-control"
                                                                            value="{{ old('designation') }}"
                                                                            placeholder="Enter Designation"
                                                                            id="designation">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('designation') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit"
                                                            class="btn btn-secondary btn-sm">Submit</button>
                                                    </div>


                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="headoption" value="<option value=''>--Select an option--</option>
                    @foreach ($godowns as $godown)
                        <option value='{{ $godown->id }}' @if($loop->first) selected @endif>{{ $godown->godown_name }}</option>
                    @endforeach" name="">
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
        function addGodownRow(divName) {
            let checkedSerialNumber = $("input[name='check_serial_number']").is(":checked");
            var optionval = $("#headoption").val();
            var row = $("#product_godown tbody tr").length;
            var count = row + 1;
            var limits = 500;
            var tabin = 0;
            if (count == limits)
            {
                alert("You have reached the limit of adding " + count + " inputs");
            }
            else
            {
                var newdiv = document.createElement('tr');
                var tabin = "goDown_" + count;
                var tabindex = count * 2;
                let serialModelHtml = serialModelForm(count);
                let dataToggleHtml = checkedSerialNumber ? `data-toggle='modal' data-target="#stock_add_${count}"` : '';
                newdiv = document.createElement("tr");
                newdiv.setAttribute('data-id', count);
                newdiv.innerHTML = `<td>
                                        <select name='godown[]' id='goDown_${count}' class='form-control godown'>
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
                                        <input type='number' name='stock[]' class='form-control godown_stock text-right' value placeholder='How Much Stock???' id='stock_${count}' onkeyup='calculateTotal(${count})' ${dataToggleHtml} step=".01">
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

        function serialModelForm(count)
        {
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
                                <div class='col-md-9 mt-3'>
                                    <input type='text' class='form-control input__serial-number' value='${value}' placeholder='Write a serial number'>
                                </div>
                                <div class='col-md-3 mt-3 pl-0'>
                                        <button  class='btn btn-primary' type='button' onclick='removeDom(this,".serial_number_list")'>
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

        function initializedSerialNumberClickEvent()
        {
            //submit serial_number button
            $('.submitSerialNumber').click(function()
            {
                let dataId = $(this).attr('data-id');
                let hasErrors = false;
                let AvaliableSerialNo = $('.avaliableSerialNo').map(function() {
                    return this.value;
                }).get()
                let serialNumbers = AvaliableSerialNo;
                // console.log(serialNumbers);
                let inputSerialNumberHtml = '';

                $(this).parents('#stock_add_' + dataId).find('.input__serial-number').each((index, value)  => {

                    let inputValue = $(value).val();

                    // console.log("i am ", serialNumbers.includes(inputValue));
                    // console.log("valeu is ", inputValue);

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

    <script type="text/javascript">
        let taxes = JSON.parse('{!! json_encode($taxes) !!}');
        window.onload = function() {

            addGodownRow('godown_body');
            // ${x.children_categories.length > 0 ? 'disabled="disabled"' : '' }
            let categoryId = "{{ old('category') }}";
            function fillSelectcategories(categories) {
                document.getElementById("category_product").innerHTML =
                    '<option value=""> --Select a category-- </option>' +
                    categories.reduce((tmp, x) => `${tmp}<option value='${x.id}'${x.id == categoryId ? 'selected' : ''}>${x.category_id == null ? '' : '&nbsp&nbsp&nbsp&nbsp'}${x.category_name}</option>`, '');
            }

            function fetchcategories() {
                $.ajax({
                    url: "{{ route('apicategory') }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var categories = response.data;
                        fillSelectcategories(categories);
                    }
                });
            }
            fetchcategories();

            let primaryUnitId = "{{ old('primary_unit') }}";
            let secondaryUnitId = "{{ old('secondary_unit') }}";
            function fillSelectunits(units) {
                document.getElementById("primary_unit").innerHTML = '<option value=""> -Primary unit- </option>' +
                    units.reduce((tmp, x) =>
                        `${tmp}<option value='${x.unit_code}'${x.unit_code == primaryUnitId ? 'selected' : ''}>${x.unit}(${x.short_form} - ${x.unit_code})</option>`, ''
                    );

                document.getElementById("secondary_unit").innerHTML = '<option value=""> -Secondary unit- </option>' +
                    units.reduce((tmp, x) =>
                        `${tmp}<option value='${x.unit_code}'${x.unit_code == secondaryUnitId ? 'selected' : ''}>${x.unit}(${x.short_form} - ${x.unit_code})</option>`, ''
                    );
            }

            function fetchunits() {
                $.ajax({
                    url: "{{ route('apiunits') }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var units = response;
                        fillSelectunits(units);
                    }
                });
            }
            fetchunits();

            let supplierId = "{{ old('supplier_id') }}";
            function fillSelectSuppliers(suppliers) {
                document.getElementById("vendor").innerHTML = '<option value=""> --Select an option-- </option>' +
                    suppliers.reduce((tmp, x) => `${tmp}<option value='${x.id}' ${x.id == supplierId ? 'selected' : ''}>${x.company_name}</option>`, '');
            }

            function fetchvendors() {
                $.ajax({
                    url: "{{ route('apisupplier') }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var suppliers = response;
                        fillSelectSuppliers(suppliers);
                    }
                });
            }
            fetchvendors();

            let brandId = "{{ old('brand') }}";
            function fillSelectBrands(brands) {
                document.getElementById("brand_product").innerHTML =
                    '<option value=""> --Select an option-- </option>' +
                    brands.reduce((tmp, x) => `${tmp}<option value='${x.id}' ${x.id == brandId ? 'selected' : ''}>${x.brand_name}</option>`, '');
            }

            function fetchBrands() {
                $.ajax({
                    url: "{{ route('apibrand') }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var brands = response;
                        fillSelectBrands(brands);
                    }
                });
            }
            fetchBrands();
        };
    </script>

    <script>
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

    <script>
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        var loadFileService = function(event) {
            var output = document.getElementById('service');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        var loadFilecategory = function(event) {
            var output = document.getElementById('category');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        var loadFilecategoryservice = function(event) {
            var output = document.getElementById('categoryservice');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        var loadFilecategoryproduct = function(event) {
            var output = document.getElementById('categoryproduct');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        var loadFilebrandproduct = function(event) {
            var output = document.getElementById('brandproduct');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>

    <script>
        function setRupees() {
            var miscellaneous_percent = document.getElementById("miscellaneous_percent");

            if (miscellaneous_percent.value > 0)
            {
                var takeout = parseFloat(final_vendor_price.value) + parseFloat(carrying_cost.value) + parseFloat(transportation_cost.value);

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
            var vat = {
                value: taxes.find(item => parseInt(item.id) === parseInt(document.getElementById("vat_select").value))?.percent
            };
            var total_cost = document.getElementById("total_cost");
            var margin_type = document.getElementById("margin_type");
            var margin_value = document.getElementById("margin_value");
            var product_price = document.getElementById("product_price");

            if (original_vendor_price.value == "" || original_vendor_price == 0) {
                original_vendor_price.value = 0;
                final_vendor_price.value = 0;
                product_cost.value = 0;
                after_custom.value = 0;
                total_cost.value = 0;
                product_price.value = 0;
            } else if (original_vendor_price.value > 0 && charging_rate.value > 0) {
                var charging_rate_amount = charging_rate.value;
                final_vendor_price.value = parseFloat(original_vendor_price.value) * parseFloat(charging_rate_amount);
                product_cost.value = final_vendor_price.value;
                after_custom.value = final_vendor_price.value;
                total_cost.value = final_vendor_price.value;
                product_price.value = product_cost.value;
            } else {
                final_vendor_price.value = original_vendor_price.value;
                product_cost.value = final_vendor_price.value;
                after_custom.value = final_vendor_price.value;
                total_cost.value = final_vendor_price.value;
                product_price.value = product_cost.value;
            }

            if (carrying_cost.value > 0) {
                product_cost.value = parseFloat(product_cost.value) + parseFloat(carrying_cost.value);
                after_custom.value = product_cost.value;
                total_cost.value = product_cost.value;
                product_price.value = product_cost.value;
            }

            if (transportation_cost.value > 0) {
                product_cost.value = parseFloat(product_cost.value) + parseFloat(transportation_cost.value);
                after_custom.value = product_cost.value;
                total_cost.value = product_cost.value;
                product_price.value = product_cost.value;
            }

            if (other_cost.value > 0) {
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

            if (custom_duty.value > 0) {
                var custom_duty_amount = product_cost.value * (custom_duty.value / 100);
                after_custom.value = parseFloat(product_cost.value) + parseFloat(custom_duty_amount);
                total_cost.value = parseFloat(product_cost.value) + parseFloat(custom_duty_amount);
                product_price.value = parseFloat(product_cost.value) + parseFloat(custom_duty_amount);
            }

            if (vat.value > 0) {
                var vat_amount = after_custom.value * (vat.value / 100);
                total_cost.value = parseFloat(after_custom.value) + parseFloat(vat_amount);
                product_price.value = parseFloat(after_custom.value) + parseFloat(vat_amount);
            }

            if (margin_value.value > 0 && margin_type.value == "percent") {
                var profit_margin_amount = total_cost.value * (margin_value.value / 100);
                product_price.value = parseFloat(total_cost.value) + parseFloat(profit_margin_amount);
            }else if(margin_value.value > 0 && margin_type.value == "fixed"){
                var profit_margin_amount = margin_value.value;
                product_price.value = parseFloat(total_cost.value) + parseFloat(profit_margin_amount);
            }
        }
    </script>

    <script>
        $(function() {
            $('.province').change(function()
            {
                var province_no = $(this).children("option:selected").val();

                function fillSelect(districts)
                {
                    document.getElementById("district").innerHTML =
                        districts.reduce((tmp, x) =>
                            `${tmp}<option value='${x.id}'>${x.dist_name}</option>`, '');
                }

                function fetchRecords(province_no)
                {
                    var uri = "{{ route('getdistricts', ':no') }}";
                    uri = uri.replace(':no', province_no);
                    $.ajax({
                        url: uri,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var districts = response;
                            fillSelect(districts);
                        }
                    });
                }
                fetchRecords(province_no);
            })
        });

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

        $(document).ready(function() {
            $("#supplier_form").submit(function(event) {

                if ($(this).find("input[type='checkbox']").is(":checked"))
                {
                    var client_check = 1
                }else{
                    var client_check = 0
                }
                var formData = {
                    company_name: $("#company_name").val(),
                    company_code: $("#company_code").val(),
                    company_email: $("#company_email").val(),
                    company_phone: $("#company_phone").val(),
                    company_address: $("#company_address").val(),
                    province_id: $("#province_id").val(),
                    district_id: $("#district").val(),
                    pan_vat: $("#pan_vat").val(),
                    concerned_name: $("#concerned_name").val(),
                    concerned_phone: $("#concerned_phone").val(),
                    concerned_email: $("#concerned_email").val(),
                    designation: $("#designation").val(),
                    opening_balance: $('#supplier_form').find(".opening_balance").val(),
                    behaviour: $('#supplier_form').find(".behaviour").find(':selected').val(),
                    is_client: client_check
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('post.apisupplier') }}",
                    data: formData,
                    dataType: "json",
                    encode: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {

                    function fillSelectSuppliers(suppliers) {
                        document.getElementById("vendor").innerHTML =
                            '<option value=""> --Select an option-- </option>' +
                            suppliers.reduce((tmp, x) =>
                                `${tmp}<option value='${x.id}'>${x.company_name}</option>`, '');
                    }

                    function fetchvendors() {
                        $.ajax({
                            url: "{{ route('apisupplier') }}",
                            type: 'get',
                            dataType: 'json',
                            success: function(response) {
                                var suppliers = response;
                                fillSelectSuppliers(suppliers);
                            }
                        });
                    }
                    fetchvendors();
                    $("#supplier_form").html(
                        '<div class="alert alert-success">Successfully added.</div>'
                    );
                });
                event.preventDefault();
            });
        });

        $(function() {
            $('.brand').change(function() {
                var brand_id = $(this).children("option:selected").val();

                let seriesId = "{{ old('series') }}";
                function fillSeries(series) {
                    document.getElementById("series_product").innerHTML =
                        series.reduce((tmp, x) => `${tmp}<option value='${x.id}' ${x.id == seriesId ? 'selected' : ''}>${x.series_name}</option>`,
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
    </script>

    <script>
        $(document).ready(function()
        {
            $("#unit_store_form").submit(function(event)
            {
                var formData = {
                    unit: $("#unit").val(),
                    short_form: $("#short_form").val(),
                    unit_code: $("#unit_code").val(),
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('post.apiunits') }}",
                    data: formData,
                    dataType: "json",
                    encode: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {

                    function fillSelectunits(units) {
                        document.getElementById("primary_unit").innerHTML =
                            '<option value=""> -Primary unit- </option>' +
                            units.reduce((tmp, x) =>
                                `${tmp}<option value='${x.id}'>${x.unit}(${x.short_form} - ${x.unit_code})</option>`,
                                '');

                        document.getElementById("secondary_unit").innerHTML =
                            '<option value=""> -Secondary unit- </option>' +
                            units.reduce((tmp, x) =>
                                `${tmp}<option value='${x.id}'>${x.unit}(${x.short_form} - ${x.unit_code})</option>`,
                                '');
                    }

                    function fetchunits() {
                        $.ajax({
                            url: "{{ route('apiunits') }}",
                            type: 'get',
                            dataType: 'json',
                            success: function(response) {
                                var units = response;
                                fillSelectunits(units);
                            }
                        });
                    }
                    fetchunits();
                    $("#unit_store_form").html(
                        '<div class="alert alert-success">Successfully added.</div>'
                    );
                });
                event.preventDefault();
            });
        });

        $(document).ready(function()
        {
            $("#product_category_form").submit(function(event)
            {
                var formData = {
                    category_name: $("#product_category_name").val(),
                    category_code: $("#product_category_code").val(),
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('storeProductCategory') }}",
                    data: formData,
                    dataType: "json",
                    encode: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {

                    function fillSelectcategoriesNew(categories) {
                        document.getElementById("category_product").innerHTML =
                            '<option value=""> --Select a category-- </option>' +
                            categories.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.category_id == null ? '' : '&nbsp&nbsp&nbsp&nbsp'}${x.category_name}</option>`, '');
                    }

                    function fetchcategoriesNew() {
                        $.ajax({
                            url: "{{ route('apicategory') }}",
                            type: 'get',
                            dataType: 'json',
                            success: function(response) {
                                var categories = response.data;
                                fillSelectcategoriesNew(categories);
                            }
                        });
                    }
                    fetchcategoriesNew();
                    $("#product_category_form").html(
                        '<div class="alert alert-success">Successfully added.</div>'
                    );
                });
                event.preventDefault();
            });
        });
    </script>

    <script>
        $(function() {
            var procodes = @php echo json_encode($allprocodes) @endphp;
            $("#product_code").change(function() {
                var procodeval = $(this).val();
                if ($.inArray(procodeval, procodes) != -1) {
                    $('.procode_error').addClass('show');
                    $('.procode_error').removeClass('hides');
                } else {
                    $('.procode_error').removeClass('show');
                    $('.procode_error').addClass('hide');

                }
            });

            var servicecodes = @php echo json_encode($allservicecodes) @endphp;
            $("#service_code").change(function() {
                var servicecodeval = $(this).val();
                if ($.inArray(servicecodeval, servicecodes) != -1) {
                    $('.servicecode_error').addClass('show');
                    $('.servicecode_error').removeClass('hides');
                } else {
                    $('.servicecode_error').removeClass('show');
                    $('.servicecode_error').addClass('hide');

                }
            })

            var categorycodes = @php echo json_encode($allcategorycodes) @endphp;
            $("#product_category_code").change(function() {
                var productcategoryval = $(this).val();
                if ($.inArray(productcategoryval, categorycodes) != -1) {
                    $('.categorycode_error').addClass('show');
                    $('.categorycode_error').removeClass('hides');
                } else {
                    $('.categorycode_error').removeClass('show');
                    $('.categorycode_error').addClass('hide');

                }
            })

            var categorycodes = @php echo json_encode($allcategorycodes) @endphp;
            $("#service_category_code").change(function() {
                var servicecategoryval = $(this).val();
                if ($.inArray(servicecategoryval, categorycodes) != -1) {
                    $('.servicecategorycode_error').addClass('show');
                    $('.servicecategorycode_error').removeClass('hides');
                } else {
                    $('.servicecategorycode_error').removeClass('show');
                    $('.servicecategorycode_error').addClass('hide');

                }
            })

            var suppliercodes = @php echo json_encode($allsuppliercodes) @endphp;
            $("#company_code").change(function() {
                var productcategoryval = $(this).val();
                if ($.inArray(productcategoryval, suppliercodes) != -1) {
                    $('.companycode_error').addClass('show');
                    $('.companycode_error').removeClass('hides');
                } else {
                    $('.companycode_error').removeClass('show');
                    $('.companycode_error').addClass('hide');
                }
            })

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
        })
    </script>
@endpush

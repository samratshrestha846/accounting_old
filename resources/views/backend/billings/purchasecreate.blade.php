@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
    <style type="text/css">

        .coloradd{
             color: red;
        }
        .bootstrap-tagsinput span {
        margin-bottom: 10px;
        }
        .bootstrap-tagsinput{
            width: 100% !important;
            display: flex ;
            flex-wrap: wrap;
            /* overflow-x: scroll; */
        }

        .bootstrap-tagsinput .tag{
            background-color: #17a2b8;
        }
        .label-info{
            background-color: #17a2b8;

        }
        .label {
            display: inline-block;
            padding: .25em .4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,
            border-color .15s ease-in-out,box-shadow .15s ease-in-out;
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
                    <h1>Entry Purchase</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('billings.report', $billing_type_id) }}" class="global-btn">View All
                            Purchases</a>
                    </div>
                </div>
            </div>
        </div>
        {{-- <input type="text" data-role="tagsinput" name="tags" class="form-control"> --}}
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
                    <div class="card-body">
                        <div class="">
                            <div class="row ibox-body">
                                <div class="col-12">
                                    <form action="{{ route('billings.purchasestore') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="billing_type_id" value="2">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="fiscal-year">Fiscal Year: </label>
                                                    <select name="fiscal_year_id" id="fiscal-year" class="form-control">
                                                        @foreach ($fiscal_years as $fiscalyear)
                                                            <option value="{{ $fiscalyear->id }}">{{ $fiscalyear->fiscal_year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('fiscal_year_id') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="entry_date">Entry Date (B.S)<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="nep_date" id="entry_date_nepali"
                                                        class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="english_date">Entry Date (A.D)<i
                                                            class="text-danger">*</i></label>
                                                    <input type="date" name="eng_date" id="english" class="form-control"
                                                        value="{{ date('Y-m-d') }}" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="party_name">Party Name: </label>
                                                    <div class="v-add">
                                                        <select name="vendor_id" id="vendor" class="form-control vendor_info"
                                                            style="font-size: 18px; padding: 5px;" required>
                                                        </select>
                                                        &nbsp;&nbsp;<button type="button" data-toggle='modal'
                                                            data-target='#supplieradd' data-toggle='tooltip' data-placement='top'
                                                            class="btn btn-primary icon-btn" title="Add New Supplier"><i
                                                                class="fas fa-plus"></i></button>
                                                        <p class="text-danger">
                                                            {{ $errors->first('vendor_id') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="pan_vat">Supplier Pan/Vat</label>
                                                    <input type="text" class="form-control panVat" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="ledger_no">Party Bill No: </label>
                                                    <input type="text" class="form-control" name="ledger_no"
                                                        placeholder="Party Bill No." >
                                                    <p class="text-danger">
                                                        {{ $errors->first('ledger_no') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="file_no">File No (Optional): </label>
                                                    <input type="text" name="file_no" class="form-control" placeholder="File No.">
                                                    <p class="text-danger">
                                                        {{ $errors->first('file_no') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="Payment Type">Stock Change:</label><br>
                                                    <span style="margin-right: 5px; font-size: 12px;">Disable</span>
                                                        <label class="switch pt-0">
                                                            <input type="checkbox" name="stock_change" value="1" checked>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    <span style="margin-left: 5px; font-size: 12px;">Enable</span>
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
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive inv-table">
                                                    <table class="table table-bordered text-center">
                                                        <thead class="thead-light">
                                                            <tr class="item-row">
                                                                <th style="width: 40%">Particulars</th>
                                                                <th>Quantity</th>
                                                                <th>Unit</th>
                                                                <th>Rate</th>
                                                                <th style="width: 20%">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <!-- Here should be the item row -->
                                                            <tr class="item-row">
                                                                <td>
                                                                    {{-- <input type="text" class="form-control item"
                                                                        placeholder="Particulars" type="text" name="particulars[]"> --}}
                                                                    <select name="particulars[]" class="form-control item">
                                                                        <option value="">--Select Option--</option>
                                                                        <option value="addproductoption" class="coloradd"> + Add new Product </option>
                                                                        {{-- @foreach ($categories as $category)

                                                                            @foreach ($category->products as $product)

                                                                                <option value="{{ $product->id }}"
                                                                                    data-rate="{{ $product->total_cost }}"
                                                                                    data-stock="{{ $product->stock }}"
                                                                                    data-priunit="{{ $product->primary_unit }}"
                                                                                    data-image="{{ count($product->product_images) > 0 ? Storage::disk('uploads')->url($product->product_images[0]->location) : '' }}"
                                                                                    data-has_serial_number="{{($product->has_serial_number == 1) ? 1 : 0}}">
                                                                                    {{ $product->product_name }}({{ $product->product_code }})@if(!empty($product->brand->brand_name))-{{$product->brand->brand_name}}@endif

                                                                                </option>
                                                                            @endforeach
                                                                        @endforeach --}}

                                                                    </select>
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('particulars') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control qty" placeholder="Quantity"
                                                                        type="text" name="quantity[]" value="0" step=".01">

                                                                        <div class="modal fade" tabindex="-1" role="dialog"
                                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <input type="hidden" name="has_serial_number[]" value="">
                                                                        <div class="modal-dialog modalstock" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">Stock Per Godown</h5>
                                                                                    <button type="button" class="close"
                                                                                        data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    @foreach ($godowns as $godown)
                                                                                    <div class="form-group">
                                                                                        <div class="row">

                                                                                            <div class="col-md-3 mb-2">

                                                                                                <input type="text" name="godown_name[]" class="form-control godown" value="{{$godown->godown_name}}" readonly="readonly">
                                                                                                <input type="hidden" name="godown_id[]" class="form-control godown" value="{{$godown->id}}" >
                                                                                            </div>
                                                                                            <div class="col-md-6 mb-2 forProductHavingSerialNo">
                                                                                                <input type="text" name="serial_product[{{$godown->id}}]" class="serial_product" placeholder="eg;-abcd,1234" data-role="tagsinput">

                                                                                            </div>
                                                                                            <div class="col-md-3 mb-2">
                                                                                                <input type="number" name="godown_qty[{{$godown->id}}][]" class="form-control godown_qty" step=".01" value="0">
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>

                                                                                    @endforeach

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('quantity') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control unit" placeholder="Unit" type="text"
                                                                        name="unit[]" readonly>
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('unit') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control rate" placeholder="Rate" type="text"
                                                                        name="rate[]" >
                                                                    <div class="modal fade" tabindex="-1" role="dialog"
                                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">Update Price of Products</h5>
                                                                                    <button type="button" class="close"
                                                                                        data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="row">
                                                                                        @if($currentcomp->company->is_importer == 1)
                                                                                        <div class="col-md-6">
                                                                                            <label for="original_vendor_price">Original Supplier Price (Rs.) :</label>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="original_vendor_price[]"
                                                                                                    class="form-control original_vendor_price" id="original_vendor_price" value="{{ old('original_vendor_price') ?? 0 }}"
                                                                                                    placeholder="Original Supplier Price">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('original_vendor_price') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="charging_rate">Changing rate (%) (If any):</label>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="charging_rate[]"
                                                                                                    class="form-control charging_rate" id="charging_rate" value="{{ old('charging_rate') ?? 0 }}"
                                                                                                    placeholder="Changing rate in %">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('charging_rate') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="final_vendor_price">Final Supplier Price (Rs.) :</label>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="final_vendor_price[]"
                                                                                                    class="form-control final_vendor_price" id="final_vendor_price" value="{{ old('final_vendor_price') ?? 0}}"
                                                                                                    placeholder="Final Supplier Price">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('final_vendor_price') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="carrying_cost">Carrying cost (Rs.) (If any):</label>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="carrying_cost[]"
                                                                                                    class="form-control carrying_cost" id="carrying_cost" value="{{ old('carrying_cost') }}"
                                                                                                    placeholder="Carrying cost for product">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('carrying_cost') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="transportation_cost">Transportation cost (Rs.) (If
                                                                                                any):</label>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="transportation_cost[]"
                                                                                                    class="form-control transportation_cost" id="transportation_cost" value="{{ old('transportation_cost') }}"
                                                                                                    placeholder="Transportation cost for product">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('transportation_cost') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="miscellaneous_percent">Miscellaneous cost (If any):</label>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6">
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-10">
                                                                                                            <div class="form-group">
                                                                                                                <input type="number" step="any"
                                                                                                                    name="miscellaneous_percent[]"
                                                                                                                    class="form-control miscellaneous_percent"
                                                                                                                    id="miscellaneous_percent" value="{{ old('miscellaneous_percent') }}"
                                                                                                                    placeholder="In percent (%)"
                                                                                                                    oninput="setRupees()">
                                                                                                                <p class="text-danger">
                                                                                                                    {{ $errors->first('miscellaneous_percent') }}
                                                                                                                </p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-2 pl-0">
                                                                                                            <p style="font-size: 20px; font-weight:bold;">%</p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <div class="form-group">
                                                                                                        <input type="number" step="any" name="other_cost[]"
                                                                                                            class="form-control other_cost" id="other_cost" value="{{ old('other_cost') }}"
                                                                                                            placeholder="In Rupees">
                                                                                                        <p class="text-danger">
                                                                                                            {{ $errors->first('other_cost') }}
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="product_cost">Cost of Product (Rs.) :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="product_cost[]"
                                                                                                    class="form-control product_cost" id="product_cost" value="{{ old('product_cost') ?? 0 }}"
                                                                                                    placeholder="Product cost">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('product_cost') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="custom_duty">Custom Duty (%) :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="custom_duty[]"
                                                                                                    class="form-control custom_duty" id="custom_duty" value="{{ old('custom_duty') }}"
                                                                                                    placeholder="Custom Duty">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('custom_duty') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="after_custom[]"
                                                                                                    class="form-control after_custom" id="after_custom" value="{{ old('after_custom') }}"
                                                                                                    placeholder="After Custom">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('after_custom') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="vat">Tax (VAT) :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <select name="product_tax[]" class="form-control vat_selected vat_select"
                                                                                                    id="vat_select">
                                                                                                    <option value="">--Select an option--</option>
                                                                                                    @foreach ($taxes as $tax)
                                                                                                        <option value="{{ $tax->id }}" data-percent="{{$tax->percent}}">{{ $tax->title }}
                                                                                                            ({{ $tax->percent }} %)</option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>


                                                                                        <div class="col-md-6">
                                                                                            <label for="total_cost">Total Cost (Rs.)
                                                                                                :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="total_cost[]"
                                                                                                    class="form-control total_cost" id="total_cost" value="{{ old('total_cost') ?? 0 }}"
                                                                                                    placeholder="Total cost">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('total_cost') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="margin_profit">Profit margin :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6">
                                                                                                    <div class="form-group">
                                                                                                        <select name="margin_type[]" class="form-control margin_type" id="margin_type">
                                                                                                            <option value="percent"{{ old('margin_type') == "percent" ? 'selected' : '' }}>Percent</option>
                                                                                                            <option value="fixed"{{ old('margin_type') == "fixed" ? 'selected' : '' }}>Fixed</option>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <div class="form-group">
                                                                                                        <input type="number" step="any" name="margin_value[]"
                                                                                                            class="form-control margin_value" id="margin_value" value="{{ old('margin_value') ?? 0}}"
                                                                                                            placeholder="Profit Margin Value">
                                                                                                        <p class="text-danger">
                                                                                                            {{ $errors->first('margin_value') }}
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="product_price">Selling Price (Rs.) :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="product_price[]"
                                                                                                    class="form-control product_price" id="product_price" value="{{ old('product_price') ?? 0}}"
                                                                                                    placeholder="Product Price in Rs.">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('product_price') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                        @else
                                                                                        <div class="col-md-6">
                                                                                            <label for="original_vendor_price">Purchase Price (Rs.) :</label>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="original_vendor_price[]"
                                                                                                    class="form-control original_vendor_price" id="original_vendor_price" value="{{ old('original_vendor_price') ?? 0 }}"
                                                                                                    placeholder="Purchase Price">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('original_vendor_price') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <label for="charging_rate">Changing rate (%) (If any):</label>
                                                                                        </div>
                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="charging_rate[]"
                                                                                                    class="form-control charging_rate" id="charging_rate" value="{{ old('charging_rate') ?? 0 }}"
                                                                                                    placeholder="Changing rate in %">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('charging_rate') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <label for="final_vendor_price">Final Supplier Price (Rs.) :</label>
                                                                                        </div>
                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="final_vendor_price[]"
                                                                                                    class="form-control final_vendor_price" id="final_vendor_price" value="{{ old('final_vendor_price') ?? 0 }}"
                                                                                                    placeholder="Final Supplier Price">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('final_vendor_price') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <label for="carrying_cost">Carrying cost (Rs.) (If any):</label>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="carrying_cost[]"
                                                                                                    class="form-control carrying_cost" id="carrying_cost" value="{{ old('carrying_cost') }}"
                                                                                                    placeholder="Carrying cost for product">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('carrying_cost') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <label for="transportation_cost">Transportation cost (Rs.) (If
                                                                                                any):</label>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="transportation_cost[]"
                                                                                                    class="form-control transportation_cost" id="transportation_cost" value="{{ old('transportation_cost') }}"
                                                                                                    placeholder="Transportation cost for product">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('transportation_cost') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <label for="miscellaneous_percent">Miscellaneous cost (If any):</label>
                                                                                        </div>
                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6">
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-10">
                                                                                                            <div class="form-group">
                                                                                                                <input type="number" step="any"
                                                                                                                    name="miscellaneous_percent[]"
                                                                                                                    class="form-control miscellaneous_percent"
                                                                                                                    id="miscellaneous_percent" value="{{ old('miscellaneous_percent') }}"
                                                                                                                    placeholder="In percent (%)"
                                                                                                                    oninput="setRupees()" onblur="calculate()">
                                                                                                                <p class="text-danger">
                                                                                                                    {{ $errors->first('miscellaneous_percent') }}
                                                                                                                </p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-2 pl-0">
                                                                                                            <p style="font-size: 20px; font-weight:bold;">%</p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <div class="form-group">
                                                                                                        <input type="number" step="any" name="other_cost[]"
                                                                                                            class="form-control other_cost" id="other_cost" value="{{ old('other_cost') }}"
                                                                                                            placeholder="In Rupees">
                                                                                                        <p class="text-danger">
                                                                                                            {{ $errors->first('other_cost') }}
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <label for="product_cost">Cost of Product (Rs.) :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display:none;">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="product_cost[]"
                                                                                                    class="form-control product_cost" id="product_cost" value="{{ old('product_cost') ?? 0 }}"
                                                                                                    placeholder="Product cost">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('product_cost') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <label for="custom_duty">Custom Duty (%) :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display:none;">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="custom_duty[]"
                                                                                                    class="form-control custom_duty" id="custom_duty" value="{{ old('custom_duty') }}"
                                                                                                    placeholder="Custom Duty">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('custom_duty') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display:none;">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="after_custom[]"
                                                                                                    class="form-control after_custom" id="after_custom" value="{{ old('after_custom') }}"
                                                                                                    placeholder="After Custom">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('after_custom') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <label for="vat">Tax (VAT) :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display:none;">
                                                                                            <div class="form-group">
                                                                                                <select name="tax[]" class="form-control vat_selected vat_select"
                                                                                                    id="vat_select" onchange="calculate()">
                                                                                                    <option value="">--Select an option--</option>
                                                                                                    @foreach ($taxes as $tax)
                                                                                                        <option value="{{ $tax->percent }}">{{ $tax->title }}
                                                                                                            ({{ $tax->percent }} %)</option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>

                                                                                        </div>

                                                                                        <div class="col-md-6" style="display: none;">
                                                                                            <label for="total_cost">Total Cost (Rs.)
                                                                                                :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6" style="display:none;">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="total_cost[]"
                                                                                                    class="form-control total_cost" id="total_cost" value="{{ old('total_cost') ?? 0 }}"
                                                                                                    placeholder="Total cost">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('total_cost') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="margin_profit">Profit margin :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6">
                                                                                                    <div class="form-group">
                                                                                                        <select name="margin_type[]" class="form-control margin_type" id="margin_type" onchange="calculate()">
                                                                                                            <option value="percent"{{ old('margin_type') == "percent" ? 'selected' : '' }}>Percent</option>
                                                                                                            <option value="fixed"{{ old('margin_type') == "fixed" ? 'selected' : '' }}>Fixed</option>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <div class="form-group">
                                                                                                        <input type="number" step="any" name="margin_value[]"
                                                                                                            class="form-control margin_value" id="margin_value" value="{{ old('margin_value') ?? 0 }}"
                                                                                                            placeholder="Profit Margin Value" onkeyup="calculate()">
                                                                                                        <p class="text-danger">
                                                                                                            {{ $errors->first('margin_value') }}
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        {{-- <div class="col-md-6"></div> --}}

                                                                                        <div class="col-md-6">
                                                                                            <label for="product_price">Selling Price (Rs.) :</label>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <input type="number" step="any" name="product_price[]"
                                                                                                    class="form-control product_price" id="product_price" value="{{ old('product_price') ?? 0 }}"
                                                                                                    placeholder="Selling Price in Rs.">
                                                                                                <p class="text-danger">
                                                                                                    {{ $errors->first('product_price') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3"></div>
                                                                                    @endif
                                                                                    </div>
                                                                                </div>
                                                                                {{-- <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-primary"
                                                                                        data-dismiss="modal">Submit</button>
                                                                                </div> --}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('rate') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input name="total[]" class="form-control total" value="0"
                                                                        readonly="readonly">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('total') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr id="hiderow">
                                                                <td colspan="5" class="text-right">
                                                                    <div class="btn-bulk justify-content-end">
                                                                    <a id="addRow" href="javascript:;" title="Add a row"
                                                                        class="btn btn-primary">+ Add</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong>Total Quantity: </strong>
                                                                </td>
                                                                <td>
                                                                    <span id="totalQty"
                                                                        style="color: red; font-weight: bold;font-size:12px;">0</span> Units
                                                                </td>
                                                                <td></td>
                                                                <td><strong>Sub Total</strong></td>
                                                                <td>
                                                                    <input type="text" name="subtotal" id="subtotal" value="0"
                                                                        class="form-control" readonly="readonly">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('subtotal') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td><strong>Discount</strong>
                                                                <td>
                                                                    <input name="discountamount" class="form-control alldiscount"
                                                                        id="discount" value="0" type="text">
                                                                    <div class="modal fade" tabindex="-1" role="dialog"
                                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">OverAll Discount</h5>
                                                                                    <button type="button" class="close"
                                                                                        data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="form-group">
                                                                                        <div class="row">
                                                                                            <div class="col-3">
                                                                                                <label for="alldiscounttype"
                                                                                                    class="txtleft">Discount
                                                                                                    Type:</label>
                                                                                            </div>
                                                                                            <div class="col-9">
                                                                                                <select name="alldiscounttype"
                                                                                                    class="form-control alldiscounttype">
                                                                                                    <option value="percent">Percent
                                                                                                        %</option>
                                                                                                    <option value="fixed">Fixed
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <div class="row">
                                                                                            <div class="col-3">
                                                                                                <label for="dtamt">Discount:</label>
                                                                                            </div>
                                                                                            <div class="col-9">
                                                                                                <input type="text" name="alldtamt"
                                                                                                    class="form-control alldtamt"
                                                                                                    placeholder="Discount"
                                                                                                    value="0">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-primary"
                                                                                        data-dismiss="modal">Submit</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('discountamount') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td><strong>Tax Amount</strong></td>
                                                                <td>
                                                                    <input type="text" name="taxamount"
                                                                        class="gtaxamount form-control" value="0">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('taxamount') }}
                                                                    </p>
                                                                    <div class="modal fade" tabindex="-1" role="dialog"
                                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">Individual Tax
                                                                                        Details
                                                                                    </h5>
                                                                                    <button type="button" class="close"
                                                                                        data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="form-group">
                                                                                        <div class="row">
                                                                                            <div class="col-3">
                                                                                                <label for="taxtype"
                                                                                                    class="txtleft">Tax
                                                                                                    Type:</label>
                                                                                            </div>
                                                                                            <div class="col-9">
                                                                                                <select name="alltaxtype"
                                                                                                    class="form-control alltaxtype">
                                                                                                    <option value="exclusive">
                                                                                                        Exclusive</option>
                                                                                                    <option value="inclusive">
                                                                                                        Inclusive</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <div class="row">
                                                                                            <div class="col-3">
                                                                                                <label for="dtamt">Tax%:</label>
                                                                                            </div>
                                                                                            <div class="col-9">
                                                                                                <select name="alltax"
                                                                                                    class="form-control alltaxper">
                                                                                                    @foreach ($taxes as $tax)
                                                                                                        <option
                                                                                                            value="{{ $tax->percent }}">
                                                                                                            {{ $tax->title }}({{ $tax->percent }}%)
                                                                                                        </option>
                                                                                                    @endforeach

                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-primary"
                                                                                        data-dismiss="modal">Submit</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td><strong>Shipping</strong></td>
                                                                <td>
                                                                    <input name="shipping" class="form-control" id="shipping"
                                                                        value="0" type="text">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('shipping') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td><strong>Grand Total</strong></td>
                                                                <td>
                                                                    <input type="text" name="grandtotal" class="form-control"
                                                                        id="grandTotal" value="0" readonly="readonly">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('grandtotal') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td><strong>Refundable VAT</strong></td>
                                                                <td><input type="text" name="vat_refundable" class="form-control"
                                                                        value="0"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                @if($ird_sync == 1)
                                                                <td>
                                                                    <strong>IRD Sync<i class="text-danger">*</i>: </strong>
                                                                </td>
                                                                <td>
                                                                    <input type="radio" name="sync_ird" value="1" checked> Yes
                                                                    <input type="radio" name="sync_ird" value="0"> No
                                                                </td>
                                                                @else
                                                                <td></td>
                                                                <td></td>
                                                                @endif
                                                                <td>
                                                                    <strong>Status:</strong>
                                                                </td>
                                                                <td>
                                                                    <input type="radio" name="status" value="1" checked> Approve
                                                                    <input type="radio" name="status" value="0"> Unapprove
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-4">
                                                <div class="row">
                                                    <div class="row col-md-6">
                                                        <div class="col-md-6">
                                                            <label for="payment_type">Payment Type</label>
                                                            <select name="payment_type" id="payment_type"
                                                                class="form-control payment_type">
                                                                <option value="">--Select an option--</option>
                                                                <option value="paid">Paid</option>
                                                                <option value="partially_paid">Partially Paid</option>
                                                                <option value="unpaid">Unpaid</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="payment_amount">Payment Amount</label>
                                                            <input type="text" id="payment_amount" name="payment_amount"
                                                                class="form-control payment_amount" placeholder="Enter Paid Amount"
                                                                required>
                                                            <p class="off text-danger">Payment can't be more than that of Grand Total
                                                            </p>
                                                        </div>
                                                     </div>
                                                    <div class="row col-md-6 paymentMethod">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="Payment Type">Payment Method:</label>
                                                                <select name="payment_method" class="form-control payment_method">
                                                                    @foreach ($payment_methods as $method)
                                                                        <option value="{{ $method->id }}">
                                                                            {{ $method->payment_mode }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" id="online_payment" style="display: none;">
                                                            <div class="form-group">
                                                                <label for="Bank">Select a portal:</label>
                                                                <div class="row">
                                                                    <div class="col-md-9 pr-0">
                                                                        <select name="online_portal" class="form-control online_portal_class" id="online_portal">
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-3" style="padding-left: 7px;">
                                                                        <button type="button" data-toggle='modal'
                                                                            data-target='#onlinePortalAdd' data-toggle='tooltip'
                                                                            data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                                            title="Add New Portal"><i class="fas fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" id="customer_portal_id" style="display: none;">
                                                            <div class="form-group">
                                                                <label for="">Portal Id:</label>
                                                                <input type="text" class="form-control" placeholder="Portal Id"
                                                                    name="customer_portal_id">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" id="bank" style="display: none;">
                                                            <div class="form-group">
                                                                <label for="Bank">From Bank:</label>
                                                                <div class="row">
                                                                    <div class="col-md-9 pr-0">
                                                                        <select name="bank_id" class="form-control bank_info_class" id="bank_info">
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-3" style="padding-left:7px;">
                                                                        <button type="button" data-toggle='modal'
                                                                            data-target='#bankinfoadd' data-toggle='tooltip'
                                                                            data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                                            title="Add New Bank"><i class="fas fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" id="cheque" style="display: none;">
                                                            <div class="form-group">
                                                                <label for="cheque no">Cheque no.:</label>
                                                                <input type="text" class="form-control" placeholder="Cheque No."
                                                                    name="cheque_no">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="creditDate col-md-6 row" style="display: none;">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="entry_date">Due Date (B.S)<i
                                                                        class="text-danger">*</i>:</label>
                                                                <input type="text" name="due_date_nep" id="entry_date_nepaliCr"
                                                                    class="form-control" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="english_date">Due Date (A.D)<i
                                                                        class="text-danger">*</i>:</label>
                                                                <input type="date" name="due_date_eng" id="englishCr"
                                                                    class="form-control" value="{{ date('Y-m-d') }}"
                                                                    readonly="readonly">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <div class="form-gorup">
                                                    <label for="remarks">Remarks: </label>
                                                    <textarea name="remarks" class="form-control" placeholder="Remarks.." rows="5"></textarea>
                                                    <p class="text-danger">
                                                        {{ $errors->first('remarks') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12 btn-bulk mt-2 d-flex justify-content-end">
                                                <button class="btn btn-primary submit" type="submit">Submit</button>
                                                <button type="submit" class="btn btn-secondary btn-large" name="saveandcontinue" value="1">Submit And Continue</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class='modal fade text-left' id='supplieradd' tabindex='-1' role='dialog'
                                        aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document' style="max-width: 1000px;">
                                            <div class='modal-content'>
                                                <div class='modal-header text-center'>
                                                    <h2 class='modal-title' id='exampleModalLabel'>Add Supplier</h2>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <form action="" method="POST" id="supplier_form">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <p class="card-title">Company Details</p>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="company_name_group">
                                                                            <label for="name">Company Name<i
                                                                                    class="text-danger">*</i></label>
                                                                            <input type="text" name="company_name"
                                                                                class="form-control"
                                                                                value="{{ old('company_name') }}"
                                                                                placeholder="Enter Company Name" id="company_name"
                                                                                required>
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
                                                                                value="{{ $supplier_code }}"
                                                                                placeholder="Enter Company Code" id="company_code">
                                                                            <p class="text-danger companycode_error hide">Code is
                                                                                already used. Use Different code.</p>
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('supplier_code') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="company_email_group">
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
                                                                        <div class="form-group" id="company_phone_group">
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
                                                                            <label for="pan_vat">PAN No./VAT No. (Optional)</label>
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
                                                                            <select name="province" class="form-control province"
                                                                                id="province_id">
                                                                                <option value="">--Select a province--</option>
                                                                                @foreach ($provinces as $province)
                                                                                    <option value="{{ $province->id }}">
                                                                                        {{ $province->eng_name }}</option>
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
                                                                        <div class="form-group" id="company_address_group">
                                                                            <label for="company_address">Company Local
                                                                                Address</label>
                                                                            <input type="text" name="company_address"
                                                                                class="form-control"
                                                                                value="{{ old('company_address') }}"
                                                                                placeholder="Company Address" id="company_address">
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
                                                                        <div class="form-group" id="concerned_name_group">
                                                                            <label for="concerned_name">Name</label>
                                                                            <input type="text" name="concerned_name"
                                                                                class="form-control"
                                                                                value="{{ old('concerned_name') }}"
                                                                                placeholder="Enter Name" id="concerned_name">
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('concerned_name') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="concerned_phone_group">
                                                                            <label for="concerned_phone">Phone</label>
                                                                            <input type="text" name="concerned_phone"
                                                                                class="form-control"
                                                                                value="{{ old('concerned_phone') }}"
                                                                                placeholder="Enter Phone" id="concerned_phone">
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('concerned_phone') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="concerned_email_group">
                                                                            <label for="concerned_email">Email</label>
                                                                            <input type="email" name="concerned_email"
                                                                                class="form-control"
                                                                                value="{{ old('concerned_email') }}"
                                                                                placeholder="Enter Email" id="concerned_email">
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
                                                                                placeholder="Enter Designation" id="designation">
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('designation') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-secondary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='modal fade text-left' id='bankinfoadd' tabindex='-1' role='dialog'
                                        aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document' style="max-width: 1000px;">
                                            <div class='modal-content'>
                                                <div class='modal-header text-center'>
                                                    <h2 class='modal-title' id='exampleModalLabel'>Add New Bank</h2>
                                                    <button type='button' class='close' data-dismiss='modal'
                                                        aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <form action="" method="POST" id="bank_add_form">
                                                        @csrf
                                                        @method("POST")
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="bank_name">Bank Name <span
                                                                                            class="text-danger">*</span>:
                                                                                    </label>
                                                                                    <input type="text" name="bank_name"
                                                                                        class="form-control"
                                                                                        id="bank_name"
                                                                                        placeholder="Enter Bank Name"
                                                                                        value="{{ old('bank_name') }}">
                                                                                    <p class="text-danger">
                                                                                        {{ $errors->first('bank_name') }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="head_branch">Branch <span
                                                                                            class="text-danger">*</span>:
                                                                                    </label>
                                                                                    <select name="head_branch"
                                                                                        class="form-control"
                                                                                        id="head_branch">
                                                                                        <option value="">--Select one
                                                                                            option--</option>
                                                                                        <option value="Head Office">Head
                                                                                            Office</option>
                                                                                        <option value="Branch">Branch
                                                                                        </option>
                                                                                    </select>
                                                                                    <p class="text-danger">
                                                                                        {{ $errors->first('head_branch') }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label for="bank_province_no">Province
                                                                                        no. <i
                                                                                            class="text-danger">*</i>:</label>
                                                                                    <select name="bank_province_no"
                                                                                        class="form-control bank_province"
                                                                                        id="bank_province_no">
                                                                                        <option value="">--Select a
                                                                                            province--</option>
                                                                                        @foreach ($provinces as $province)
                                                                                            <option
                                                                                                value="{{ $province->id }}">
                                                                                                {{ $province->eng_name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <p class="text-danger">
                                                                                        {{ $errors->first('bank_province_no') }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label for="bank_district_no">Districts
                                                                                        <i
                                                                                            class="text-danger">*</i>:</label>
                                                                                    <select name="bank_district_no"
                                                                                        class="form-control"
                                                                                        id="bank_district_no">
                                                                                        <option value="">--Select a province
                                                                                            first--</option>
                                                                                    </select>
                                                                                    <p class="text-danger">
                                                                                        {{ $errors->first('bank_district_no') }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label for="bank_local_address">Local
                                                                                        Address <i
                                                                                            class="text-danger">*</i>:</label>
                                                                                    <input type="text"
                                                                                        name="bank_local_address"
                                                                                        class="form-control"
                                                                                        placeholder="Eg: Chamti tole"
                                                                                        id="bank_local_address">
                                                                                    <p class="text-danger">
                                                                                        {{ $errors->first('bank_local_address') }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label for="account_no">Account no. <i
                                                                                            class="text-danger">*</i>:</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="account_no"
                                                                                        placeholder="Enter Account no."
                                                                                        id="account_no">
                                                                                    <p class="text-danger">
                                                                                        {{ $errors->first('account_no') }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label for="account_name">Account Name
                                                                                        <i
                                                                                            class="text-danger">*</i>:</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="account_name"
                                                                                        placeholder="Enter Account Name"
                                                                                        id="account_name">
                                                                                    <p class="text-danger">
                                                                                        {{ $errors->first('account_name') }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label for="account_type">Account Type <span class="text-danger">*</span>:
                                                                                    </label>
                                                                                    <select name="account_type" class="form-control" id="account_type_id">
                                                                                        <option value="">--Select an option--</option>
                                                                                        @foreach ($bankAccountTypes as $bankAccountType)
                                                                                            <option value="{{ $bankAccountType->id }}" {{ old('account_type') == $bankAccountType->id ? 'selected' : '' }}>{{ $bankAccountType->account_type_name }}</option>
                                                                                        @endforeach
                                                                                    </select>

                                                                                    <p class="text-danger">
                                                                                        {{ $errors->first('account_type') }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>

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


                                                                            <div class="col-md-12 text-center">
                                                                                <button type="submit"
                                                                                    class="btn btn-secondary btn-sm">Submit</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='modal fade text-left' id='onlinePortalAdd' tabindex='-1' role='dialog'
                                        aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document' style="max-width: 1000px;">
                                            <div class='modal-content'>
                                                <div class='modal-header text-center'>
                                                    <h2 class='modal-title' id='exampleModalLabel'>Add New Portal</h2>
                                                    <button type='button' class='close' data-dismiss='modal'
                                                        aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <form action="" method="POST" id="online_portal_add">
                                                        @csrf
                                                        @method("POST")
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="name">Portal Name <span
                                                                                            class="text-danger">*</span>:
                                                                                    </label>
                                                                                    <input type="text" name="portal_name"
                                                                                        class="form-control"
                                                                                        id="portal_name"
                                                                                        placeholder="Enter Portal Name"
                                                                                        value="{{ old('name') }}">
                                                                                    <p class="text-danger">
                                                                                        {{ $errors->first('name') }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="payment_id">Portal ID <i
                                                                                            class="text-danger">*</i>:</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="payment_id"
                                                                                        placeholder="Enter Portal Id"
                                                                                        id="payment_id">
                                                                                    <p class="text-danger">
                                                                                        {{ $errors->first('payment_id') }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
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

                                                                            <div class="col-md-12 text-center">
                                                                                <button type="submit"
                                                                                    class="btn btn-secondary btn-sm">Submit</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
<div class='modal fade text-left' id='stockadd' tabindex='-1' role='dialog'
aria-labelledby='exampleModalLabel' aria-hidden='true'>
<div class='modal-dialog' role='document' style="max-width: 800px;">
    <div class='modal-content'>
        <div class='modal-header text-center'>
            <h2 class='modal-title' id='exampleModalLabel'>Add Stock</h2>
            <button type='button' class='close' data-dismiss='modal'
                aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
        </div>
        <div class='modal-body'>
            <form action="" method="POST" id="">
                @csrf
                @method("POST")
                <div class="modelstock">


                </div>

                <button type="button" class="btn btn-secondary btn-sm save_stock"
                    name="modal_button">Save</button>
            </form>
        </div>
    </div>
</div>
</div>

<div class='modal fade text-left addnewproductModal' id='' tabindex='-1' role='dialog'
    aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog' role='document' style="max-width: 1000px;">
        <div class='modal-content'>
            <div class='modal-header text-center'>
                <h2 class='modal-title' id='exampleModalLabel'>Add New Product</h2>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body'>
                <form action="{{ route('product.store') }}" method="POST" id="productform">
                    @csrf
                    @method("POST")
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="product_name">Product Name<i class="text-danger">*</i>
                                                :</label>
                                            <input type="text" id="product_name" name="product_name"
                                                class="form-control" value="{{ old('product_name') }}"
                                                placeholder="Product Name" style="width:91%;">
                                            <p class="text-danger">
                                                {{ $errors->first('product_name') }}
                                            </p>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="product_code">Product SKU (SKU must be unique):</label>
                                            <input type="text" id="product_code" name="product_code"
                                                class="form-control" value=""
                                                placeholder="Product SKU" style="width:91%;">
                                            <p class="text-danger procode_error hide">Code is already used. Use
                                                Different code.</p>
                                            <p class="text-danger">
                                                {{ $errors->first('product_code') }}
                                            </p>
                                        </div>
                                        <div class="form-group col-md-4">

                                        <label for="product_code">Product Category:</label>
                                            <select name="category" class="form-control">
                                                <option value="">--Select Category --</option>
                                                @foreach($addproductCategory as $category)
                                                <option value="{{$category->id}}"> {{$category->category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">

                                        <label for="suppliers">Suppliers :</label>
                                            <select name="supplier_id" class="form-control" id="supplier">
                                                <option value="">--Select Suppliers --</option>
                                                @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->id}}"> {{$supplier->company_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                            <div class="form-group col-md-4">
                                                <label for="Status">Has Serial number: </label><br>
                                                <span style="margin-left: 5px; font-size: 15px;"> No </span>
                                                <label class="switch pt-0">
                                                    <input type="checkbox" name="check_serial_number" id="serial_number" {{ old('check_serial_number') == "on" ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                                <span style="margin-left: 5px; font-size: 15px;">Yes</span>
                                            </div>


                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="units">Units for Product<i class="text-danger">*</i>
                                                :</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control" step="any"
                                                        placeholder="Number.." name="primary_number" value="{{ old('primary_number') }}">
                                                </div>
                                                <div class="col-md-6 pl-0">
                                                    <select name="primary_unit" class="form-control" id="primary_unit">
                                                        @foreach($units as $unit)
                                                        <option value="{{$unit->id}}">{{$unit->unit}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="Status">Status: </label><br>
                                            <span style="margin-left: 5px; font-size: 15px;"> Disable </span>
                                            <label class="switch pt-0">
                                                <input type="checkbox" name="status" value="1" {{ old('status') == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                            <span style="margin-left: 5px; font-size: 15px;">Enable</span>
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
                                    </div>



                                    <input type="hidden" name="final_vendor_price" value=0>
                                    <input type="hidden" name="original_vendor_price" value=0>
                                    <input type="hidden" name="margin_type" value="percent">
                                    <input type="hidden" name="product_price" value=0>

                                    <input type="hidden" name="product_cost" value=0>

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
                                    <div class="col-md-12 text-center">
                                        <button type="button"
                                            class="btn btn-secondary btn-sm submitProduct">Submit</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('backend/dist/js/jquery.purchaseinvoice.js') }}"></script>
    <script src="{{ asset('backend/dist/js/mousetrap/purchase.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js" integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg==" crossorigin="anonymous"></script> --}}
    <script>
        $(document).ready(function(){
            $.get('{{route("ajaxgetProducts")}}',function(response){
                var html = '';
                $.each(JSON.parse(response),function(k,v){
                    console.log(v);
                    html += `<option value="${v.id}"
                    data-rate="${v.total_cost}"
                    data-stock="${v.stock}"
                    data-priunit="${v.primary_unit}"

                    data-has_serial_number="${v.has_serial_number}">
                     ${v.product_name}(${v.product_code})  ${v.brand_name ? '-' : ''} ${ v.brand_name }

                </option>`;
                });

                $('.item').append(html);
            })
        })

    </script>
    <script type="text/javascript">
        window.onload = function() {

            const d = new Date();
            var year = d.getFullYear();
            var month = d.getMonth() + 1;
            var day = d.getDate();
            var today = year + '-' + month + '-' + day;
            var mainInput = document.getElementById("entry_date_nepali");
            var engtodayobj = NepaliFunctions.ConvertToDateObject(today, "YYYY-MM-DD");
            var neptoday = NepaliFunctions.ConvertDateFormat(NepaliFunctions.AD2BS(engtodayobj), "YYYY-MM-DD");
            document.getElementById('entry_date_nepali').value = neptoday;


            // var engtodayobjCr = NepaliFunctions.ConvertToDateObject(today, "YYYY-MM-DD");
            // var neptoday = NepaliFunctions.ConvertDateFormat(NepaliFunctions.AD2BS(engtodayobjCr), "YYYY-MM-DD");
            // document.getElementById('entry_date_nepaliCr').value = neptoday;

            mainInput.nepaliDatePicker({
                onChange: function() {
                    var nepdate = mainInput.value;
                    var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                    document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(
                        NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                }
            });
            var mainInputCr = document.getElementById("entry_date_nepaliCr");
            mainInputCr.nepaliDatePicker({
                onChange: function() {
                    var nepdate = mainInputCr.value;
                    var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                    document.getElementById('englishCr').value = NepaliFunctions.ConvertDateFormat(
                        NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                }
            });

            function fillSelectSuppliers(suppliers) {
                // console.log(suppliers);
                document.getElementById("vendor").innerHTML = '<option value=""> --Select an option-- </option>' +
                    suppliers.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.company_name}</option>`, '');


            }

            function fillmodelSuppliers(suppliers) {
            document.getElementById("supplier").innerHTML = '<option value=""> --Select an option-- </option>' +
                    suppliers.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.company_name}</option>`, '');
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

            function fillSelectbank_info(bank_info) {
                document.getElementById("bank_info").innerHTML = '<option value=""> --Select option-- </option>' +
                    bank_info.reduce((tmp, x) =>
                        `${tmp}<option value='${x.id}'>${x.bank_name} (A/C Holder - ${x.account_name})</option>`, '');
            }

            function fetchbanks() {
                $.ajax({
                    url: "{{ route('apibankinfo') }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var bank_info = response;
                        fillSelectbank_info(bank_info);
                    }
                });
            }
            fetchbanks();

            function fillOnlinePortal(online_portal) {
                document.getElementById("online_portal").innerHTML = '<option value=""> --Select option-- </option>' +
                    online_portal.reduce((tmp, x) =>
                        `${tmp}<option value='${x.id}'>${x.name}</option>`, '');
            }

            function fetchOnlinePortals() {
                $.ajax({
                    url: "{{ route('apiOnlinePortal') }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var online_portal = response;
                        fillOnlinePortal(online_portal);
                    }
                });
            }
            fetchOnlinePortals();
        };
    </script>
    <script>
        $(function() {
            // var tablerow = $('table#salestbl tr');
            var particulars = $('#table input[particulars]')

            $(document).on('input', 'input.creditPrice', function() {
                $(this).parent().siblings().find('input.debitPrice').attr('readonly',
                    'readonly');
            });

        });

        $(document).on('focus', '.alldiscount', function() {
            $(this).siblings('.modal').modal({
                show: true
            });
        });

        $(document).on('click', '.qty', function() {

           var godowns = @php echo  json_encode($godowns); @endphp;
           var serialnumber = $(this).closest('tr').find('select[name="particulars[]"] option:selected').attr('data-has_serial_number');
             if($('input[name="stock_change"]').is(":checked") && godowns.length > 1 || serialnumber == 1 ){


            var product_id = $(this).closest('tr').find('select[name="particulars[]"]').val();


            $(this).siblings('.modal').modal('show').find('.modal-dialog').css('max-width','75%');
            $('.serial_product').tagsinput({
            tagClass: function(item) {
                return (item.length > 10 ? 'small' : 'small');
            }
            });

            if(serialnumber == 1){

                $(this).siblings('.modal').find('.forProductHavingSerialNo').show();

                var inputserialproduct = $(this).siblings('.modal').find('.serial_product');

                $.each(inputserialproduct,function(){
                    var nameWithoutslice =  $(this).attr('name');
                    var str=nameWithoutslice.slice(0, -1);
                      if(nameWithoutslice.indexOf('-') > -1)//find name with - alreadyexist
                        {

                        }else{
                            $(this).attr('name',str+'-'+product_id+']');
                        }
                })


            }else{

                $(this).siblings('.modal').find('.forProductHavingSerialNo').hide();
            }

            var total = 0;
            $(this).siblings('.modal').find('.godown_qty').each(function(k,v){
                total += parseFloat($(this).val());
                $(this).attr('data-hasserialnumber',serialnumber);
            });
            $(this).val(total)

        };

        });

        $(document).on('input','.qty',function(){

            var total = 0;

            $('.qty').each(function(k,v){
                total += parseFloat($(this).val());
            });
            $('#totalQty').val(total)

        })
        $(document).on('input','.godown_qty',function(){
            $(this).closest('tr').find('.qty').trigger('click');

        })

        $('input[name="stock_change"]').click(function(){
            var godowns = @php echo  json_encode($godowns); @endphp;

             if( godowns.length > 1){
                if($(this).is(':checked')){
                    $('.qty').val(0);
                }
            }
        })

        $(document).on('keyup', 'tr', function(){
            var total_cost = $(this).find('.total_cost').val();
            // window.alert(total_cost);
            $(this).find('.rate').val(total_cost);
        })

        $(document).on('change', '.item', function(){
            // console.log($(this).find(":selected").val());
            // return;

            if($(this).find(":selected").val() == 'addproductoption'){

                var product_code = @php echo json_encode('PD' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT)); @endphp;

                $('.addnewproductModal').modal('show');
                $('input[name="product_code"]').val(product_code);
            }else{
            var item_id = $(this).find(':selected').val();
            var rate = $(this).closest('tr').find('.rate');
            var original_vendor_price = $(this).closest('tr').find('.original_vendor_price');
            var charging_rate = $(this).closest('tr').find('.charging_rate');
            var final_vendor_price = $(this).closest('tr').find('.final_vendor_price');
            var carrying_cost = $(this).closest('tr').find('.carrying_cost');
            var transportation_cost = $(this).closest('tr').find('.transportation_cost');
            var other_cost = $(this).closest('tr').find('.other_cost');
            var product_cost = $(this).closest('tr').find('.product_cost');
            var custom_duty = $(this).closest('tr').find('.custom_duty');
            var after_custom = $(this).closest('tr').find('.after_custom');
            var vat = $(this).closest('tr').find('.vat_select');
            var total_cost = $(this).closest('tr').find('.total_cost');
            var margin_type = $(this).closest('tr').find('.margin_type');
            var margin_value = $(this).closest('tr').find('.margin_value');
            var product_price = $(this).closest('tr').find('.product_price');
            var miscellaneous_percent = $(this).closest('tr').find('.miscellaneous_percent');

            var products = @php echo json_encode($products) @endphp;
            let product = products.find(product => product.id == item_id);

            original_vendor_price.val(product.original_vendor_price);
            charging_rate.val(product.charging_rate);
            final_vendor_price.val(product.final_vendor_price);
            carrying_cost.val(product.carrying_cost);
            transportation_cost.val(product.transportation_cost);
            miscellaneous_percent.val(product.miscellaneous_percent);
            other_cost.val(product.other_cost);
            product_cost.val(product.cost_of_product);
            custom_duty.val(product.custom_duty);
            after_custom.val(product.after_custom);
            // vat.find
            vat.find('option').each(function(){
                let vatval = $(this).data('percent');
                if(vatval == product.tax.toFixed()){
                    $(this).attr('selected', true);
                }
            })
            margin_type.find('option').each(function(){
                let margintype_val = $(this).val();
                if(margintype_val == product.margin_type){
                    $(this).attr('selected', true);
                }
            })
            total_cost.val(product.total_cost);
            // margin_type.val(product.margin_type)
            margin_value.val(product.margin_value);
            product_price.val(product.product_price);
            rate.val(product.total_cost);

        }
        $('body').trigger('click');
        $('body').trigger('click');
        });

        let calculatePrice = function(){
            var original_vendor_price = $(this).closest('tr').find('.original_vendor_price');
            var charging_rate = $(this).closest('tr').find('.charging_rate');
            var final_vendor_price = $(this).closest('tr').find('.final_vendor_price');
            var carrying_cost = $(this).closest('tr').find('.carrying_cost');
            var transportation_cost = $(this).closest('tr').find('.transportation_cost');
            var other_cost = $(this).closest('tr').find('.other_cost');
            var product_cost = $(this).closest('tr').find('.product_cost');
            var custom_duty = $(this).closest('tr').find('.custom_duty');
            var after_custom = $(this).closest('tr').find('.after_custom');
            var vat = $(this).closest('tr').find('.vat_select').find(':selected').data('percent');
            var total_cost = $(this).closest('tr').find('.total_cost');
            var margin_type = $(this).closest('tr').find('.margin_type').find(':selected');
            var margin_value = $(this).closest('tr').find('.margin_value');
            var product_price = $(this).closest('tr').find('.product_price');

            var miscellaneous_percent = $(this).closest('tr').find('.miscellaneous_percent');

            if (miscellaneous_percent.val() > 0)
            {
                var takeout = parseFloat(final_vendor_price.val()) + parseFloat(carrying_cost.val()) + parseFloat(transportation_cost.val());

                var percent_amount = takeout * (miscellaneous_percent.val() / 100);
                other_cost.val(percent_amount);
            }

            if (original_vendor_price.val() == "" || original_vendor_price == 0) {
                original_vendor_price.val(0);
                final_vendor_price.val(0);
                product_cost.val(0);
                after_custom.val(0);
                total_cost.val(0);
                product_price.val(0);
            } else if (original_vendor_price.val() > 0 && charging_rate.val() > 0) {
                var charging_rate_amount = charging_rate.val();
                var fvp = parseFloat(original_vendor_price.val()) * parseFloat(charging_rate_amount);
                final_vendor_price.val(fvp.toFixed(2));
                product_cost.val(final_vendor_price.val());
                after_custom.val(final_vendor_price.val());
                total_cost.val(final_vendor_price.val());
                product_price.val(product_cost.val());
            } else {
                final_vendor_price.val(original_vendor_price.val());
                product_cost.val(final_vendor_price.val());
                after_custom.val(final_vendor_price.val());
                total_cost.val(final_vendor_price.val());
                product_price.val(product_cost.val());
            }

            if (carrying_cost.val() > 0) {
                var pc = parseFloat(product_cost.val()) + parseFloat(carrying_cost.val());
                product_cost.val(pc.toFixed(2));
                after_custom.val(product_cost.val());
                total_cost.val(product_cost.val());
                product_price.val(product_cost.val());
            }

            if (transportation_cost.val() > 0) {
                var pct = parseFloat(product_cost.val()) + parseFloat(transportation_cost.val());
                product_cost.val(pct.toFixed(2));
                after_custom.val(product_cost.val());
                total_cost.val(product_cost.val());
                product_price.val(product_cost.val());
            }

            if (other_cost.val() > 0) {
                var new_cost = parseFloat(product_cost.val()) + parseFloat(other_cost.val());
                var percent_cost = parseFloat(final_vendor_price.val()) + parseFloat(carrying_cost.val()) + parseFloat(
                    transportation_cost.val());
                percent = (parseFloat(other_cost.val()) / percent_cost) * 100;
                miscellaneous_percent.val(percent.toFixed(2));
                product_cost.val(new_cost.toFixed(2));
                after_custom.val(new_cost.toFixed(2));
                total_cost.val(new_cost.toFixed(2));
                product_price.val(new_cost.toFixed(2));
            }

            if (custom_duty.val() > 0) {
                var custom_duty_amount = product_cost.val() * (custom_duty.val() / 100);
                var pc_cda = parseFloat(product_cost.val()) + parseFloat(custom_duty_amount);
                after_custom.val(pc_cda.toFixed(2));
                total_cost.val(pc_cda.toFixed(2));
                product_price.val(pc_cda.toFixed(2));
            }

            if (vat > 0) {
                var vat_amount = after_custom.val() * (vat / 100);
                var ac_va = parseFloat(after_custom.val()) + parseFloat(vat_amount);
                total_cost.val(ac_va.toFixed(2));
                product_price.val(ac_va.toFixed(2));
            }

            if (margin_value.val() > 0 && margin_type.val() == "percent") {
                var profit_margin_amount = total_cost.val() * (margin_value.val() / 100);
                var tc_pma = parseFloat(total_cost.val()) + parseFloat(profit_margin_amount);
                product_price.val(tc_pma.toFixed(2));
            }else if(margin_value.val() > 0 && margin_type.val() == "fixed"){
                var profit_margin_amount = margin_value.val();
                var tc_pma = parseFloat(total_cost.val()) + parseFloat(profit_margin_amount);
                product_price.val(tc_pma.toFixed(2));
            }
        }

        $(document).on('keyup', ".original_vendor_price, .charging_rate, .final_vendor_price, .carrying_cost, .transportation_cost, .other_cost, .product_cost, .custom_duty, .after_custom, .total_cost, .margin_value, .product_price, .miscellaneous_percent", calculatePrice);
        $(document).on('change', ".vat_select, .margin_type", calculatePrice);


        $(document).on('itemAdded','.serial_product', function (event){
            var number = event.item;

            $.get('{{route('godownserialnumber')}}',{number:number},function(response){

                if(response == 'exist'){
                     $('.serial_product').tagsinput('remove', number);
                     $('.modal-header').after('<p class="text-danger errorserialnumber">Already exist serial number</p>');
                     setTimeout(function(){
                        $('.errorserialnumber').remove();
                        }, 3000);
                }

            })
            var length = $(this).closest('div').find('.tag').length;

            $(this).closest('div .row').find('.godown_qty').val(length);
        $(this).closest('tr').find('.qty').trigger('click');
        });





        $(document).on('itemRemoved','.serial_product', function (event) {
            var length = $(this).closest('div').find('.tag').length;
            $(this).closest('div .row').find('.godown_qty').val(length);
        $(this).closest('tr').find('.qty').trigger('click');
        });

        $(document).on('focus', '.gtaxamount', function() {
            $(this).siblings('.modal').modal({
                show: true
            });
        });
        $(document).on('focus', '.rate', function() {
            $(this).siblings('.modal').modal({
                show: true
            });
        });
        $(document).ready(function() {


            $(".item").select2({

                templateResult: function (data, container) {
                if (data.element) {
                    $(container).addClass($(data.element).attr("class"));
                }
                return data.text;
                }
            });



        });
        $(document).ready(function() {
            $(".vendor_info").select2();
        });

        $(document).on('change','.vendor_info',function(){
            var url = "{{route('vendors.show','id')}}";

            var url = url.replace("id", $(this).val());
            $.get(url,function(response){
                $('.panVat').val(response);
            })
        })

        $(function() {
            $('.payment_method').change(function() {
                var payment = $(this).children("option:selected").val();

                if (payment == 2) {
                    document.getElementById("bank").style.display = "block";
                    document.getElementById("cheque").style.display = "block";
                    document.getElementById("online_payment").style.display = "none";
                    document.getElementById("customer_portal_id").style.display = "none";
                } else if (payment == 3) {
                    document.getElementById("bank").style.display = "block";
                    document.getElementById("cheque").style.display = "none";
                    document.getElementById("online_payment").style.display = "none";
                    document.getElementById("customer_portal_id").style.display = "none";
                } else if(payment == 4)
                {
                    document.getElementById("bank").style.display = "none";
                    document.getElementById("cheque").style.display = "none";
                    document.getElementById("online_payment").style.display = "block";
                    document.getElementById("customer_portal_id").style.display = "block";
                }else {
                    document.getElementById("bank").style.display = "none";
                    document.getElementById("cheque").style.display = "none";
                    document.getElementById("online_payment").style.display = "none";
                    document.getElementById("customer_portal_id").style.display = "none";
                }

            })
        });

        $(document).ready(function() {
            $("#bank_add_form").submit(function(event) {
                var formData = {
                    bank_name: $("#bank_name").val(),
                    head_branch: $("#head_branch").val(),
                    bank_province_no: $("#bank_province_no").val(),
                    bank_district_no: $("#bank_district_no").val(),
                    bank_local_address: $("#bank_local_address").val(),
                    account_no: $("#account_no").val(),
                    account_name: $("#account_name").val(),
                    account_type_id: $("#account_type_id").val(),
                    opening_balance: $('#bank_add_form').find(".opening_balance").val(),
                    behaviour: $('#bank_add_form').find(".behaviour").find(':selected').val(),
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('post.apibankinfo') }}",
                    data: formData,
                    dataType: "json",
                    encode: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {

                    function fillSelectbank_info(bank_info) {
                        document.getElementById("bank_info").innerHTML =
                            '<option value=""> --Select option-- </option>' +
                            bank_info.reduce((tmp, x) =>
                                `${tmp}<option value='${x.id}'>${x.bank_name} (A/C Holder - ${x.account_name})</option>`,
                                '');
                    }

                    function fetchbanks() {
                        $.ajax({
                            url: "{{ route('apibankinfo') }}",
                            type: 'get',
                            dataType: 'json',
                            success: function(response) {
                                var bank_info = response;
                                fillSelectbank_info(bank_info);
                            }
                        });
                    }
                    fetchbanks();
                    $("#bank_add_form").html(
                        '<div class="alert alert-success">Successfully added.</div>'
                    );
                });
                event.preventDefault();
            });
        });

        $(document).ready(function() {
            $("#online_portal_add").submit(function(event) {
                var formData = {
                    name: $("#portal_name").val(),
                    payment_id: $("#payment_id").val(),
                    opening_balance: $('#online_portal_add').find(".opening_balance").val(),
                    behaviour: $('#online_portal_add').find(".behaviour").find(':selected').val()
                };

                console.log(formData)

                $.ajax({
                    type: "POST",
                    url: "{{ route('post.apiOnlinePortal') }}",
                    data: formData,
                    dataType: "json",
                    encode: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {

                function fillOnlinePortal(online_portal) {
                    document.getElementById("online_portal").innerHTML = '<option value=""> --Select option-- </option>' +
                        online_portal.reduce((tmp, x) =>
                            `${tmp}<option value='${x.id}'>${x.name}</option>`, '');
                }

                function fetchOnlinePortals() {
                    $.ajax({
                        url: "{{ route('apiOnlinePortal') }}",
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var online_portal = response;
                            fillOnlinePortal(online_portal);
                        }
                    });
                }
                fetchOnlinePortals();
                    $("#online_portal_add").html(
                        '<div class="alert alert-success">Successfully added.</div>'
                    );
                });
                event.preventDefault();
            });
        });

        $(function() {
            $('.bank_province').change(function() {
                var province_no = $(this).children("option:selected").val();

                function fillSelect(districts) {
                    document.getElementById("bank_district_no").innerHTML =
                        districts.reduce((tmp, x) =>
                            `${tmp}<option value='${x.id}'>${x.dist_name}</option>`, '');
                }

                function fetchRecords(province_no) {
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
    </script>
    <script>
        $(function() {
            $('.payment_type').change(function() {
                $('.paymentMethod').show();
                $('.creditDate').hide();
                var payment_type = $(this).find(':selected').val();
                var payment_amount = $('.payment_amount');
                var grandtotal = $('#grandTotal').val();
                if (payment_type == 'partially_paid') {
                    $('.creditDate').show();
                    payment_amount.attr('readonly', false);
                } else if (payment_type == 'paid') {
                    payment_amount.attr('readonly', 'readonly');
                    payment_amount.val(grandtotal);
                } else if (payment_type == 'unpaid') {
                    $('.creditDate').show();
                    $('.paymentMethod').hide();
                    payment_amount.attr('readonly', 'readonly');
                    payment_amount.val('0');
                }
            })
        });
    </script>
    <script>
        window.env_url = {!! json_encode(url('/')) !!} + '/';
        jQuery(document).ready(function() {
            jQuery().invoice({
                addRow: "#addRow",
                delete: ".delete",
                parentClass: ".item-row",

                item: ".item",
                unit: ".unit",
                godown: ".godown",
                godown_qty: '.godown_qty',
                product_image: ".product_image",
                rate: ".rate",
                qty: ".qty",
                total: ".total",
                totalQty: "#totalQty",

                // original_vendor_price: '.original_vendor_price',
                // charging_rate: '.charging_rate',
                // final_vendor_price: '.final_vendor_price',
                // carrying_cost: '.carrying_cost',
                // transportation_cost: '.transportation_cost',
                // miscellaneous_percent: '.miscellaneous_percent',
                // other_cost: '.other_cost',
                // product_cost: '.product_cost',
                // custom_duty: '.custom_duty',
                // after_custom: '.after_custom',
                // vat_select: '.vat_select',
                // total_cost: '.total_cost',
                // margin_type: '.margin_type',
                // margin_value: '.margin_value',
                // product_price: '.product_price',

                subtotal: "#subtotal",
                discountpercent: "#discountpercent",
                alldiscounttype: '.alldiscounttype',
                alldtamt: '.alldtamt',
                gtaxamount: ".gtaxamount",
                alltaxtype: ".alltaxtype",
                alltaxper: '.alltaxper',
                discount: "#discount",
                tax: "#tax",
                taxamount: "#taxamount",
                shipping: "#shipping",
                grandTotal: "#grandTotal"
            });
        });
    </script>
    <script>
        // $(document).on('change', '.item', function() {
        //     let rate = $(this).find(':selected').data('rate');
        //     let ratebox = $(this).closest('tr').find('.rate');
        //     ratebox.val(rate);
        // })
        $('.alldiscounttype').change(function(){
            $('body').click();
        })
        $('.alldtamt, .qty').keyup(function(){
            $('body').click();
            $('body').click();
        })
    </script>
    <script>
        $(function() {
            $('.province').change(function() {
                var province_no = $(this).children("option:selected").val();

                function fillSelect(districts) {
                    document.getElementById("district").innerHTML =
                        districts.reduce((tmp, x) =>
                            `${tmp}<option value='${x.id}'>${x.dist_name}</option>`, '');
                }

                function fetchRecords(province_no) {
                    var url = "{{ route('getdistricts', ':province_no') }}";
                    url = url.replace(':province_no', province_no);
                    $.ajax({
                        url: url,
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
        });
    </script>
    <script>
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

                    function fillmodelSuppliers(suppliers) {
                        document.getElementById("supplier").innerHTML =
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
                                fillmodelSuppliers(suppliers);
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
    </script>
    <script>
        $('#payment_amount').change(function() {
            var payment_amount = $(this).val();
            var grandtotal = $("#grandTotal").val();

            if (parseFloat(payment_amount) > parseFloat(grandtotal)) {
                $(this).parent().find('.text-danger').removeClass('off');
                $('.submit').addClass("disabled");
            } else {
                $(this).parent().find('.text-danger').addClass('off');
                $('.submit').removeClass("disabled");
            }
        });
    </script>

    <script>
        $(document).on('click','.submitProduct',function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
            url: "{{route('product.store')}}",
            method: 'post',
            data: $('#productform').serialize(),
            success:function(response){
                var serial_number = (response.has_serial_number == "1") ? "1" : "0";
                var stock = (response.stock) ? v.stock : "0";
                var html = '<option data-has_serial_number="'+serial_number+'" data-priunit="'+response.primary_unit+'" data-stock="'+stock+'" data-rate="'+response.total_cost+'" value="'+response.id+'">'+response.product_name+'('+response.product_code+')</option>';


                $('.item').append(html);
                $('.addnewproductModal').find('form')[0].reset();
                $('.addnewproductModal').modal('hide');
                $(".item").select2({
                // containerCssClass: "pink",
                    templateResult: function (data, container) {
                    if (data.element) {
                        $(container).addClass($(data.element).attr("class"));
                    }
                    return data.text;
                    }
                });
            },
            error:function(response){
                $.each(response.responseJSON.errors,function(k,v){

                    $('input[name="'+k+'"]').after('<p class="text-danger">'+v+'</p>');
                })
                if(response.responseJSON.errors.category){
                    $('select[name="category"]').after('<p class="text-danger">'+response.responseJSON.errors.category+'</p>');
                }

            }

            });

        });


        </script>
    {{-- <script>
        $('.select2-results__options li').each(function(){
            alert('HIU');
        })
    </script> --}}
    <script>
        window.godowns = @php echo  json_encode($godowns); @endphp;
        window.taxes = @php echo json_encode($taxes); @endphp;
        window.currentcomp = @php echo json_encode($currentcomp); @endphp;
    </script>
    <script>
        window.categories = @php echo json_encode($categories); @endphp;
    </script>
@endpush

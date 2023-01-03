@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
    <style>
        .icon-rtl {
            padding-right: 20px;
            background: url('/uploads/image.jpg') no-repeat right !important;
            background-size: 14px !important;
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
                    <h1>Edit Sales Bill</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('billings.report', $billing_type_id) }}" class="global-btn">View All Sales</a>
                    </div>
                </div>
                <!-- /.row -->
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
                        <h2>Sales</h2>
                    </div>
                    <div class="card-body">
                        <div class="ibox">
                            <div class="row ibox-body">
                                <div class="col-12">
                                    <form action="{{ route('billings.update', $billing->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="billing_type_id" value="1">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="fiscal-year">Fiscal Year: </label>
                                                    <select name="fiscal_year_id" id="fiscal-id" class="form-control">
                                                        @foreach ($fiscal_years as $fiscalyear)
                                                            <option value="{{ $fiscalyear->id }}"
                                                                {{ $fiscalyear->id == $billing->fiscal_year_id ? 'selected' : '' }}>
                                                                {{ $fiscalyear->fiscal_year }}
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
                                                        class="form-control" value="{{ $billing->nep_date }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="english_date">Entry Date (A.D)<i
                                                            class="text-danger">*</i></label>
                                                    <input type="date" name="eng_date" id="english" class="form-control"
                                                        value="{{ $billing->eng_date }}" readonly="readonly">
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="transaction_no">Transaction No:</label>
                                                    <input type="text" class="form-control" name="transaction_no"
                                                        value="{{ $transaction_no }}" readonly="readonly">
                                                    <p class="text-danger">
                                                        {{ $errors->first('transaction_no') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="reference_no">Reference No: </label>
                                                    <input type="text" class="form-control" name="reference_no"
                                                        value="{{ $reference_no }}" readonly="readonly">
                                                    <p class="text-danger">
                                                        {{ $errors->first('reference_no') }}
                                                    </p>
                                                </div>
                                            </div> --}}
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="client_name">Customer Name: </label>
                                                    <div class="v-add">
                                                        <select name="client_id" id="client" class="form-control client_info"
                                                            style="font-size: 18px; padding: 5px;" required>
                                                            @foreach ($clients as $client)
                                                                <option value="{{ $client->id }}"
                                                                    {{ $client->id == $billing->client_id ? 'selected' : '' }} data-dealer_percent = "{{$client->dealertype->percent}}">
                                                                    {{ $client->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="text-danger">
                                                            {{ $errors->first('client_id') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="pan_vat">Customer Pan/Vat</label>
                                                    <input type="text" class="form-control panVat" readonly value="{{$billing->client->pan_vat}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="total_credit">Customer Total Credit</label>
                                                    <input type="text" class="form-control total_credit" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="ledger_no">VAT Bill No: </label>
                                                    <input type="text" class="form-control" name="ledger_no"
                                                        value="{{ $billing->ledger_no }}" >
                                                    <p class="text-danger">
                                                        {{ $errors->first('ledger_no') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="file_no">File No (Optional): </label>
                                                <input type="text" name="file_no" class="form-control"
                                                    value="{{ $billing->file_no }}" placeholder="File no">
                                                <p class="text-danger">
                                                    {{ $errors->first('file_no') }}
                                                </p>
                                                </div>
                                            </div>


                                            <div class="col-md-2">
                                                <div class="form-gorup">
                                                    <label for="godown">Select Godown</label>
                                                    <select name="godown" id="godown" class="form-control" readonly="readonly">
                                                        <option value="{{ $selgodown->id }}"
                                                            {{ $selgodown->id == $billing->godown ? 'selected' : '' }}>
                                                            {{ $selgodown->godown_name }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            @if ($currentcomp->company->is_importer == 1)
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="declaration_form_no">Decleration Form no<i
                                                            class="text-danger"></i> :</label>
                                                    <input type="text" id="declaration_form_no" name="declaration_form_no"
                                                        class="form-control" value="{{ $billing->declaration_form_no }}"
                                                        placeholder="Declaratoin form no" style="width:91%;">

                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-md-12">
                                                <div class="row" style="align-items: flex-end;">
                                                    @include('backend.billings._includes.barcodereader')
                                                    <div class="col-md-8 text-right">
                                                        <label for=""></label>
                                                                <input type="hidden" name="selected_filter_option" id="SelectedFilter" >
                                                                <div class="dropdown">
                                                                <button class="global-btn dropdown-toggle" type="button" data-toggle="dropdown"><span class="dropdown-text"> <i class="las la-filter"></i></span>
                                                                <span class="caret"></span></button>
                                                                <ul class="dropdown-menu">
                                                                    <li class="divider"></li>
                                                                    <li>
                                                                        <a href="#">
                                                                            <label>
                                                                                <input name='filter_cols[]' type="checkbox" onclick="showHide(this)" class="option justone" value='stock' checked/>
                                                                                Stock
                                                                            </label>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#">
                                                                            <label>
                                                                                <input name='filter_cols[]' type="checkbox" onclick="showHide(this)" class="option justone" value='unit' checked/>
                                                                                Unit
                                                                            </label>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#">
                                                                            <label>
                                                                                <input name='filter_cols[]' type="checkbox" onclick="showHide(this)" class="option justone" value='rate' checked/>
                                                                                Rate
                                                                            </label>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#">
                                                                            <label>
                                                                                <input name='filter_cols[]' onclick="showHide(this)" type="checkbox" class="option justone" value='discount' checked/>
                                                                                Discount
                                                                            </label>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#">
                                                                            <label>
                                                                                <input name='filter_cols[]' type="checkbox" onclick="showHide(this)" class="option justone" value='tax' checked/>
                                                                                Tax
                                                                            </label>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive mt-2">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light text-center">
                                                            <tr class="item-row">
                                                                <th style="width: 29%">Particulars</th>
                                                                <th>Quantity</th>
                                                                <th class="stock">Stock</th>
                                                                <th class="unit">Unit</th>
                                                                <th class="rate">Rate</th>
                                                                <th class="discount">Discount</th>
                                                                <th class="tax">Individual Tax</th>
                                                                <th style="width: 15%">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($billing->billingextras as $billingextra)
                                                                <tr class="item-row">
                                                                    <td class="item-name">
                                                                        <div class="delete-btn">
                                                                            <select name="particulars[]" class="form-control item" readonly="readonly">
                                                                                <option value="">--Select Option--</option>
                                                                                @foreach ($selgodown->godownproduct as $prod)
                                                                                    <option value="{{ $prod->product_id }}"
                                                                                        {{ $prod->product_id == $billingextra->particulars ? 'selected' : '' }}
                                                                                        data-stock="{{ $prod->stock }}"
                                                                                        data-priunit=" {{ $prod->product->primary_unit }}"
                                                                                        data-rate="{{ $prod->product->product_price }}"
                                                                                        data-has_serial_number = {{$prod->product->has_serial_number}}>
                                                                                        {{ $prod->product->product_name }}({{ $prod->product->product_code }}){{(!empty($prod->product->brand->brand_name)) ? '-'.$prod->product->brand->brand_name : ""}}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <a class='delete' href="javascript:;"
                                                                                title="Remove row">X</a>
                                                                        </div>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('particulars') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control qty" placeholder="Quantity"
                                                                            type="text" name="quantity[]"
                                                                            value="{{$billingextra->quantity}}">
                                                                            <div class="modal fade" tabindex="-1" role="dialog"
                                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title">Select Serial Numbers</h5>
                                                                                        <button type="button" class="close"
                                                                                            data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="form-gorup text-left">
                                                                                            <label for="previous_No">Previously Selected</label>
                                                                                            <select name="previous_No[]" class="form-control preserial_numbers preqtyoption" multiple="multiple">
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group text-left">
                                                                                            <label for="serial_No">Serial No:</label>
                                                                                            <select name="serial_No[]" class="form-control serial_numbers qtyoption" multiple="multiple">
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-primary"
                                                                                            data-dismiss="modal">Submit</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" class="serialcount" value="0">
                                                                        <input type="hidden" class="preserialcount" value="0">
                                                                        {{-- <span class="stock">123</span> --}}
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('quantity') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="stock[]" class="form-control stock"
                                                                            readonly="readonly">
                                                                        <p class="text-danger danger"></p>
                                                                    </td>

                                                                    <td>
                                                                    <input class="form-control unit" placeholder="Unit" type="text" name="unit[]" value="{{$billingextra->unit}}" readonly>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('unit') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control rate" placeholder="Rate"
                                                                            type="text" name="rate[]" value={{$billingextra->rate}}>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('rate') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control discountamt"
                                                                            placeholder="Discount per unit" type="text"
                                                                            name="discountamt[]" value="0">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('discountamt') }}
                                                                        </p>
                                                                        <div class="modal fade" tabindex="-1" role="dialog"
                                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title">Discount Details
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
                                                                                                    <label for="discounttype"
                                                                                                        class="txtleft">Discount
                                                                                                        Type:</label>
                                                                                                </div>
                                                                                                <div class="col-9">
                                                                                                    <select name="discounttype[]"
                                                                                                        class="form-control discounttype">
                                                                                                        <option value="percent"
                                                                                                            {{ $billingextra->discounttype == 'percent' ? 'selected' : '' }}>
                                                                                                            Percent
                                                                                                            %</option>
                                                                                                        <option value="fixed"
                                                                                                            {{ $billingextra->discounttype == 'fixed' ? 'selected' : '' }}>
                                                                                                            Fixed
                                                                                                        </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <div class="row">
                                                                                                <div class="col-3">
                                                                                                    <label
                                                                                                        for="dtamt">Discount:</label>
                                                                                                </div>
                                                                                                <div class="col-9">
                                                                                                    <input type="text"
                                                                                                        name="dtamt[]"
                                                                                                        class="form-control dtamt"
                                                                                                        placeholder="Discount"
                                                                                                        value="{{ $billingextra->dtamt }}">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                            class="btn btn-secondary"
                                                                                            data-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="taxamt[]"
                                                                            class="form-control taxamt" value="0"> <input
                                                                            type="text" name="itemtax[]"
                                                                            class="form-control itemtax" value="0"
                                                                            style="display: none;">

                                                                        <p class="text-danger">
                                                                            {{ $errors->first('tax') }}
                                                                        </p>
                                                                        <div class="modal fade" tabindex="-1" role="dialog"
                                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title">Individual Tax
                                                                                            Details
                                                                                        </h5>
                                                                                        <button type="button"
                                                                                            class="close"
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
                                                                                                    <select name="taxtype[]"
                                                                                                        class="form-control taxtype">
                                                                                                        <option value="exclusive"
                                                                                                            {{ $billingextra->taxtype == 'exclusive' ? 'selected' : '' }}>
                                                                                                            Exclusive</option>
                                                                                                        <option value="inclusive"
                                                                                                            {{ $billingextra->taxtype == 'inclusive' ? 'selected' : '' }}>
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
                                                                                                    <select name="tax[]"
                                                                                                        class="form-control taxper">
                                                                                                        @foreach ($taxes as $tax)
                                                                                                            <option
                                                                                                                value="{{ $tax->percent }}"
                                                                                                                {{ $billingextra->tax == "$tax->percent" ? 'selected' : '' }}>
                                                                                                                {{ $tax->title }}({{ $tax->percent }}%)
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                            class="btn btn-secondary"
                                                                                            data-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input name="total[]" class="form-control total" value="{{$billingextra->total}}">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('total') }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <tr id="hiderow">
                                                                <td colspan="8" class="text-right">
                                                                    <div class="btn-bulk justify-content-end">
                                                                        <a id="addRow" href="javascript:;" title="Add a row"
                                                                        class="btn btn-primary">+ Add</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <!-- Here should be the item row -->
                                                            <!--<tr class="item-row">
                                                                    <td><input class="form-control item" placeholder="Item" type="text"></td>
                                                                    <td><input class="form-control price" placeholder="Price" type="text"></td>
                                                                    <td><input class="form-control qty" placeholder="Quantity" type="text"></td>
                                                                    <td><span class="total">0.00</span></td>
                                                                </tr>-->
                                                            <tr>
                                                                <td><strong>Total Quantity: </strong></td>
                                                                <td><span id="totalQty"
                                                                        style="color: red; font-weight: bold">0</span> Units</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
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
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td><strong>Discount</strong>
                                                                <td>
                                                                    <input name="discountamount" class="form-control alldiscount"
                                                                        id="discount" value="{{ $billing->discountamount }}"
                                                                        type="text">
                                                                    <div class="modal fade" tabindex="-1" role="dialog"
                                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">OverAll Discount
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
                                                                                                <label for="alldiscounttype"
                                                                                                    class="txtleft">Discount
                                                                                                    Type:</label>
                                                                                            </div>
                                                                                            <div class="col-9">
                                                                                                <select name="alldiscounttype"
                                                                                                    class="form-control alldiscounttype">
                                                                                                    <option value="percent"
                                                                                                        {{ $billing->alldiscounttype == 'percent' ? 'selected' : '' }}>
                                                                                                        Percent
                                                                                                        %</option>
                                                                                                    <option value="fixed"
                                                                                                        {{ $billing->alldiscounttype == 'fixed' ? 'selected' : '' }}>
                                                                                                        Fixed
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
                                                                                                    value="{{ $billing->alldtamt }}">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary"
                                                                                        data-dismiss="modal">Close</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('discountamount') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            {{-- <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>Tax %</strong></td>
                                                                <td>
                                                                    <input name="taxpercent" class="form-control" id="tax" value="0"
                                                                        type="text">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('taxpercent') }}
                                                                    </p>
                                                                </td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td><strong>Tax Amount</strong></td>
                                                                <td>
                                                                    <input type="text" name="taxamount" id="taxamount"
                                                                        class="form-control off" value="0">
                                                                    <input type="text" name="taxamount"
                                                                        class="gtaxamount form-control on" value="0">
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
                                                                                                    <option value="exclusive"
                                                                                                        {{ $billing->alltaxtype == 'exclusive' ? 'selected' : '' }}>
                                                                                                        Exclusive</option>
                                                                                                    <option value="inclusive"
                                                                                                        {{ $billing->alltaxtype == 'inclusive' ? 'selected' : '' }}>
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
                                                                                                            value="{{ $tax->percent }}"
                                                                                                            {{ $billing->alltax == $tax->percent ? 'selected' : '' }}>
                                                                                                            {{ $tax->title }}({{ $tax->percent }}%)
                                                                                                        </option>
                                                                                                    @endforeach

                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary"
                                                                                        data-dismiss="modal">Close</button>
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
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td><strong>Shipping</strong></td>
                                                                <td>
                                                                    <input name="shipping" class="form-control" id="shipping"
                                                                        value="{{ $billing->shipping }}" type="text">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('shipping') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
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
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td><strong>Refundable VAT</strong></td>
                                                                <td><input type="text" name="vat_refundable" class="form-control"
                                                                        value="{{ $billing->vat_refundable }}"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                @if($ird_sync == 1)
                                                                <td>
                                                                    <strong>IRD Sync<i class="text-danger">*</i>: </strong>
                                                                </td>
                                                                <td>
                                                                    <input type="radio" name="sync_ird" value="1"
                                                                        {{ $billing->sync_ird == 1 ? 'checked' : '' }}> Yes
                                                                    <input type="radio" name="sync_ird" value="0"
                                                                        {{ $billing->sync_ird == 0 ? 'checked' : '' }}> No
                                                                </td>
                                                                @else
                                                                <td></td>
                                                                <td></td>
                                                                @endif

                                                                <td>
                                                                    <strong>Status:</strong>
                                                                </td>
                                                                <td>
                                                                    <input type="radio" name="status" value="1"
                                                                        {{ $billing->status == '1' ? 'checked' : '' }}> Approve
                                                                    <input type="radio" name="status" value="0"
                                                                        {{ $billing->status == '0' ? 'checked' : '' }}> Unapprove
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-4">
                                                Payment Detail
                                                <div class="row col-md-2">

                                                        <label for="due_amount">Due Amount</label>
                                                        <input type="text" class="form-control due_amt" value="{{$billing->billing_credit->credit_amount ?? 0}}" readonly>

                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-4">
                                                <div class="row">
                                                    <div class="row col-md-6">
                                                        <div class="col-md-6">
                                                            <label for="payment_type">Payment Type</label>
                                                            <select name="payment_type" id="payment_type" class="form-control payment_type">
                                                                <option value="paid"
                                                                    {{ $billing->payment_infos[0]->payment_type == 'paid' ? 'selected' : '' }}>
                                                                    Paid</option>
                                                                <option value="partially_paid"
                                                                    {{ $billing->payment_infos[0]->payment_type == 'partially_paid' ? 'selected' : '' }}>
                                                                    Partially Paid</option>
                                                                <option value="unpaid"
                                                                    {{ $billing->payment_infos[0]->payment_type == 'unpaid' ? 'selected' : '' }}>
                                                                    Unpaid</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="payment_amount">Payment Amount</label>
                                                            <input type="text" name="payment_amount" id="payment_amount"
                                                                readonly="{{$billing->payment_infos[0]->payment_type == 'partially_paid' ? 'false' : 'readonly'}}"
                                                                class="form-control payment_amount" placeholder="Enter Paid Amount"
                                                                value="{{ $billing->payment_infos[0]->payment_amount }}" required>
                                                            <p class="off text-danger">Payment can't be more than that of Grand Total
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="row col-md-6 paymentMethod">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="Payment Type">Payment Method:</label>
                                                                <select name="payment_method" class="form-control payment_method">
                                                                    <option value="">--Select One--</option>
                                                                    @foreach ($payment_methods as $method)
                                                                        <option value="{{ $method->id }}"{{ $method->id == $billing->payment_method ? 'selected' : '' }}>{{ $method->payment_mode }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3" id="online_payment" @if ($billing->online_portal_id == null) style="display: none;" @endif>
                                                            <div class="form-group">
                                                                <label for="Bank">Select a portal:</label>
                                                                <select name="online_portal" class="form-control" id="online_portal">
                                                                    <option value="">--Select a portal--</option>
                                                                    @foreach ($online_portals as $portal)
                                                                        <option value="{{ $portal->id }}" @if ($billing->online_portal_id != null) {{ $portal->id == $billing->online_portal_id ? 'selected' : '' }} @endif>{{ $portal->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3" id="customer_portal_id" @if ($billing->customer_portal_id == null) style="display: none;" @endif>
                                                            <div class="form-group">
                                                                <label for="">Portal Id:</label>
                                                                <input type="text" class="form-control" placeholder="Portal Id"
                                                                    name="customer_portal_id" @if ($billing->customer_portal_id != null) value="{{ $billing->customer_portal_id }}" @endif>
                                                            </div>
                                                        </div>

                                                        @if ($billing->bank_id == null)
                                                            <div class="col-md-3" id="bank" style="display: none;">
                                                                <div class="form-group">
                                                                    <label for="Bank">From Bank:</label>
                                                                    <select name="bank_id" class="form-control" id="bank_info">
                                                                        <option value="">--Select a bank--</option>
                                                                        @foreach ($banks as $bank)
                                                                            <option value="{{ $bank->id }}">{{ $bank->bank_name }} ({{ $bank->head_branch }})</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col-md-3" id="bank">
                                                                <div class="form-group">
                                                                    <label for="Bank">From Bank:</label>
                                                                    <select name="bank_id" class="form-control" id="bank_info">
                                                                        <option value="">--Select a bank--</option>
                                                                        @foreach ($banks as $bank)
                                                                            <option value="{{ $bank->id }}"{{ $bank->id == $billing->bank_id ? 'selected' : '' }}>{{ $bank->bank_name }} ({{ $bank->head_branch }})</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($billing->cheque_no == null)
                                                            <div class="col-md-3" id="cheque" style="display: none;">
                                                                <div class="form-group">
                                                                    <label for="cheque no">Cheque no.:</label>
                                                                    <input type="text" class="form-control" placeholder="Cheque No." name="cheque_no">
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col-md-3" id="cheque">
                                                                <div class="form-group">
                                                                    <label for="cheque no">Cheque no.:</label>
                                                                    <input type="text" class="form-control" placeholder="Cheque No." name="cheque_no" value="{{ $billing->cheque_no }}">
                                                                </div>
                                                            </div>
                                                        @endif
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

                                            <div class="col-md-12 mt-4">
                                                <div class="form-gorup">
                                                    <label for="remarks">Remarks: </label>
                                                    <textarea name="remarks"
                                                        class="form-control" rows="5">{{ $billing->remarks }}</textarea>
                                                    <p class="text-danger">
                                                        {{ $errors->first('remarks') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <button class="btn btn-primary submit ml-auto" type="submit">Submit</button>
                                            </div>
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
        $table.find("tbody tr, thead tr")
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
        $table.find("tbody tr, thead tr")
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
    <script src="{{ asset('backend/dist/js/jquery.saleseditinvoice.js') }}"></script>
    <script src="{{ asset('backend/dist/js/mousetrap/sales.js') }}"></script>
    <script type="text/javascript">
        var mainInput = document.getElementById("entry_date_nepali");
        mainInput.nepaliDatePicker({
            onChange: function() {
                var nepdate = mainInput.value;
                var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(NepaliFunctions
                    .BS2AD(neptodaydateformat), "YYYY-MM-DD");
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

    </script>
    <script>
        $(function() {

            // var tablerow = $('table#salestbl tr');
            var particulars = $('#table input[particulars]')

            $(document).on('input', 'input.creditPrice', function() {
                $(this).parent().siblings().find('input.debitPrice').attr('readonly',
                    'readonly');
            });
            $(document).on('focus', '.discountamt', function() {
                $(this).siblings('.modal').modal({
                    show: true
                });
            });

            $(document).on('focus', '.taxamt', function() {
                $(this).siblings('.modal').modal({
                    show: true
                });
            });

            $(document).on('focus', '.alldiscount', function() {
                $(this).siblings('.modal').modal({
                    show: true
                });
            });

            $(document).on('focus', '.gtaxamount', function() {
                $(this).siblings('.modal').modal({
                    show: true
                });
            });
            $(document).ready(function() {
                $(".item").select2();
            });
            $(document).on('focus', '.serial_no', function() {
                $(this).siblings('.modal').modal({
                    show: true
                });
            });
            $(document).ready(function() {
                $(".client_info").select2();
                $('.serial_numbers').select2({
                    placeholder: '--Select Godown First--',
                });
                $('.preserial_numbers').select2({
                    placeholder: '--Select Godown First--',
                });
            });

        });

        $(document).on('change','.client_info',function(){
            var url = "{{route('client.show','id')}}";

            var url = url.replace("id", $(this).val());
            $.get(url,function(response){
                $('.panVat').val(response.pan_vat);
                $('.total_credit').val(response.total_credit);
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
    </script>
    <script>
        $(function(){
            var paid_amount = '{{ $billing->payment_infos[0]->payment_amount }}';
            $('.payment_type').change(function(){
                $('.paymentMethod').show();
                $('.creditDate').hide();
                var payment_type = $(this).find(':selected').val();
                var payment_amount = $('.payment_amount');
                var grandtotal = $('#grandTotal').val();
                if(payment_type == 'partially_paid'){
                    $('.creditDate').show();
                    payment_amount.attr('readonly', false);
                    payment_amount.val(paid_amount);
                }else if(payment_type == 'paid'){
                    payment_amount.attr('readonly', 'readonly');
                    payment_amount.val(grandtotal);
                }else if(payment_type == 'unpaid'){
                    $('.paymentMethod').hide();
                    $('.creditDate').show();
                    payment_amount.attr('readonly', 'readonly');
                    payment_amount.val('0');
                }
            })
        });
    </script>
    <script>
        $('#client').change(function(){
            $('body').click();
        });
        $(function(){
            var godown = $('#godown').find(":selected").val();
            var warehouses = window.warehouses;
            var warehousecount = warehouses.length;
            var serialoptions = '';
            var selectedserialoptions = '';
            var tr = $('.item-row');
            var billing_id = "{{$billing->id}}";
            tr.each(function(value,index){


                var tritem = $(this).find('.item');
                var trpreserial_number = $(this).find('.preserial_numbers');
                var trqtyoption = $(this).find('.qtyoption');
                var trserialcount = $(this).find('.serialcount');
                var trpreserialcount = $(this).find('.preserialcount');

                // console.log("the data is", tritem.find(":selected").val());
                if(typeof(tritem.find(":selected").val()) != 'undefined'){

                    setData(this, tr, godown, warehouses,warehousecount,serialoptions, selectedserialoptions,billing_id );

                }
            })

            function setData(event, tr, godown, warehouses,warehousecount,serialoptions,selectedserialoptions,billing_id)
            {
                var tritem = $(event).find('.item');
                var trpreserial_number = $(event).find('.preserial_numbers');
                var trqtyoption = $(event).find('.qtyoption');
                var trserialcount = $(event).find('.serialcount');
                var trpreserialcount = $(event).find('.preserialcount');

                for(let x = 0; x<warehousecount; x++){
                    if(warehouses[x].id == godown){
                        var warehouseproducts = warehouses[x].godownproduct;

                        // console.log("the data uis " ,JSON.stringify(warehouseproducts));
                        var warehouseproductlen = warehouseproducts.length;
                        for(let z = 0; z < warehouseproductlen; z++){
                            // console.log(tritem.find(":selected").val());
                            if(tritem.find(":selected").val() == warehouseproducts[z].product_id ){
                                var allserialnumbers = warehouseproducts[z].serialnumbers;
                                var serialnumberscount = allserialnumbers.length;
                                var allserialnumbersforedit = warehouseproducts[z].allserialnumbersforedit;
                                var allserialnumbersforeditcount = allserialnumbersforedit.length;
                                if(allserialnumbersforeditcount > 0){
                                    for(let s = 0; s<allserialnumbersforeditcount; s++){
                                        if(allserialnumbersforedit[s].billing_id == billing_id){
                                            selectedserialoptions+=`<option value="${allserialnumbersforedit[s].serial_number}" selected="selected">
                                            ${allserialnumbersforedit[s].serial_number}
                                            </option>`;
                                        }
                                        trpreserial_number.html(selectedserialoptions);
                                    }
                                }
                                trpreserialcount.val(allserialnumbersforeditcount);
                                // trpreserialcount.val()
                                if(serialnumberscount > 0){
                                    for(let y = 0; y<serialnumberscount; y++){
                                        serialoptions+=`<option value="${allserialnumbers[y].serial_number}">
                                        ${allserialnumbers[y].serial_number}
                                        </option>`;
                                    }
                                    trqtyoption.html(serialoptions);
                                }
                                trserialcount.val(serialnumberscount);
                            }
                        }
                    }
                }
            }
        })
    </script>
    <script>
        jQuery(document).ready(function() {
            jQuery().invoice({
                addRow: "#addRow",
                delete: ".delete",
                parentClass: ".item-row",

                godown: "#godown",
                client: "#client",
                item: '.item',
                stock: '.stock',
                danger: '.danger',
                unit: ".unit",
                rate: ".rate",
                qty: ".qty",
                qtyoption: '.qtyoption',
                preqtyoption:'.preqtyoption',
                preserial_numbers: '.preserial_numbers',
                preserialcount: '.preserialcount',
                serialcount: '.serialcount',
                discountamt: 'input.discountamt',
                discounttype: 'select.discounttype',
                dtamt: 'input.dtamt',
                taxamt: '.taxamt',
                taxtype: '.taxtype',
                taxper: '.taxper',
                total: ".total",
                totalQty: "#totalQty",
                alltaxtype: ".alltaxtype",
                alltaxper: ".alltaxper",
                itemtax: ".itemtax",
                gtaxamount: ".gtaxamount",

                subtotal: "#subtotal",
                discountpercent: "#discountpercent",
                discount: "#discount",
                // tax: "#tax",
                taxamount: "#taxamount",
                shipping: "#shipping",
                grandTotal: "#grandTotal",
                submit: ".submit",
                payment_amount: ".payment_amount",
                payment_type: ".payment_type"
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('body').trigger('click');
            $('body').trigger('click');
        });
        $('.alldiscounttype').change(function(){
            $('body').click();
        })
    </script>
    <script>
        window.warehouses = @php echo  json_encode($godowns); @endphp;
    </script>
    <script>
        $(document).on('change', '.item', function() {
            let rate = $(this).find(':selected').data('rate');
            let ratebox = $(this).closest('tr').find('.rate');
            let clientratepercenttype = typeof $('#client').find(":selected").data("dealer_percent");
            if (clientratepercenttype == "undefined") {
                var newrate = rate;
            } else {
                clientratepercent = $('#client').find(":selected").data("dealer_percent");
                var newrate = rate - (rate * clientratepercent) / 100;
            }
            ratebox.val(newrate);
        })

        // Calculate Total
        let calctotal = function() {
            let rtaxper = $(this).closest('tr').find('.taxper');
            let selectedtaxper = rtaxper.find(":selected").val();
            let taxtype = $(this).closest('tr').find('.taxtype');
            let selectedtaxtype = taxtype.find(":selected").val();
            let qty = $(this).closest('tr').find('.qty');
            let discountamt = $(this).closest('tr').find('.discountamt');
            let inputtotal = $(this).closest('tr').find('.total');
            let newrate = $(this).closest('tr').find('.rate').val();
            if (selectedtaxtype == "exclusive") {
                var txamt = ((newrate - discountamt.val()) * selectedtaxper) / 100;
                roundtaxamt = Number(txamt.toFixed(2));
                var total = newrate * qty.val() - discountamt.val() * qty.val() + parseFloat(roundtaxamt * qty.val());
                total = self.roundNumber(total, 2);

                inputtotal.val(total);
            } else if (selectedtaxtype == "inclusive") {
                var total = (newrate - discountamt.val()) * qty.val();

                total = self.roundNumber(total, 2);

                inputtotal.val(total);
            }
            $('body').click();
            $('body').click();
        }

        $(document).on('keyup', ".qty, .rate, .dtamt", calctotal);
        $(document).on('change', ".taxtype, .taxper, .discounttype", calctotal);
        // $(document).on('load', '.item', calctotal);
        // Calculate Rate
        $(document).on('keyup', '.total', function(){
            // console.log('rame');
            let rtaxper = $(this).closest('tr').find('.taxper');
            let selectedtaxper = rtaxper.find(":selected").val();
            let taxtype = $(this).closest('tr').find('.taxtype');
            let selectedtaxtype = taxtype.find(":selected").val();
            let qty = $(this).closest('tr').find('.qty');
            let dt = $(this).closest('tr').find('.discounttype').find(':selected').val();
            let d = $(this).closest('tr').find('.dtamt').val();
            let inputtotal = $(this).closest('tr').find('.total');
            var newtotal = $(this).val();
            var tot = Number(newtotal)/Number(qty.val());
            let rate = $(this).closest('tr').find('.rate');
            if (selectedtaxtype == "exclusive") {
                if(dt == "percent"){
                    // var r = (100*Number(tot))/(100 + Number(selectedtaxper) - Number(d));
                    var r = (10000*Number(tot))/((100+Number(selectedtaxper))*(100-Number(d)));
                    r = parseFloat(r.toFixed(2));
                    rate.val(r);
                }else{
                    // var r = ((100*Number(tot) + 100*(Number(d)))/(100 + Number(selectedtaxper)));
                    var r = ((100 * Number(tot))/(100+Number(selectedtaxper))) - Number(d);
                    r = parseFloat(r.toFixed(2));
                    rate.val(r);
                }
            }
            else if (selectedtaxtype == "inclusive") {
                // var total = (newrate - discountamt.val()) * qty.val();

                // total = self.roundNumber(total, 2);

                // inputtotal.val(total);
                if(dt == "percent"){
                    // var r = (100*Number(tot))/(100-Number(d));
                    var r = ((100*Number(tot))+Number(d))/100;
                    r = parseFloat(r.toFixed(2));
                    rate.val(r);
                }else{
                    // var r = Number(tot) + Number(d);
                    var r = Number(tot) + Number(d);
                    r = parseFloat(r.toFixed(2));
                    rate.val(r);
                }
            }

            $('body').click();
        })
    </script>
    <script>
        window.billing_id = "{{$billing->id}}";
        window.taxes = <?php echo json_encode($taxes); ?>;
        window.categories = <?php echo json_encode($categories); ?>
    </script>
@endpush

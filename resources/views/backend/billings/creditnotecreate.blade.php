@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Entry Credit Note</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('billings.report', $billing_type_id) }}" class="global-btn">View All Credit
                            Notes</a>
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
                        <h2 class="s-title">Credit Note</h2>
                    </div>
                    <div class="card-body">
                        <div class="ibox">
                            <div class="row ibox-body">
                                <div class="col-12">
                                    <form action="{{ route('billings.creditnotestore') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="billing_type_id" value="6">
                                        <input type="hidden" name="billing_id" value="{{$purchase_inv->id}}">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="fiscal-year">Fiscal Year: </label>
                                                    <select name="fiscal_year_id" id="fiscal-year" class="form-control">
                                                        @foreach ($fiscal_years as $fiscalyear)
                                                            <option value="{{ $fiscalyear->id }}">
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
                                                        class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="english_date">Entry Date (A.D)<i
                                                            class="text-danger">*</i></label>
                                                    <input type="date" name="eng_date" id="english"
                                                        class="form-control" value="{{ date('Y-m-d') }}"
                                                        readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="reference_no">Reference Inv No: </label>
                                                    <input type="text" class="form-control reference_invoice_no"
                                                        name="reference_invoice_no" readonly="readonly"
                                                        value="{{ $purchase_inv->reference_no }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('reference_invoice_no') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="client_name">Customer Name: </label>
                                                    <select name="client_id" class="form-control selclient" disabled>
                                                        @foreach ($clients as $client)
                                                            <option value="{{ $client->id }}"
                                                                {{ $client->id == $purchase_inv->client_id ? 'selected' : '' }}>
                                                                {{ $client->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="client_id" class="form-control cli"
                                                        readonly="readonly" value="">
                                                    <p class="text-danger">
                                                        {{ $errors->first('client_id') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="pan_vat">Customer Pan/Vat</label>
                                                    <input type="text" class="form-control panVat" readonly value="{{$purchase_inv->client->pan_vat}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="ledger_no">VAT Bill No: </label>
                                                    <input type="text" class="form-control" name="ledger_no"
                                                        placeholder="VAT Bill no." required>
                                                    <p class="text-danger">
                                                        {{ $errors->first('ledger_no') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="file_no">File No (Optional): </label>
                                                    <input type="text" name="file_no" class="form-control"
                                                        placeholder="File no.">
                                                    <p class="text-danger">
                                                        {{ $errors->first('file_no') }}
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="col-md-2">
                                                <div class="form-gorup">
                                                    <label for="godown">Select Godown</label>
                                                    <select name="godown" id="godown"
                                                        class="form-control godown_info" readonly="readonly">
                                                        <option value="{{ $selgodown->id }}" selected>
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
                                                            class="form-control" value=""
                                                            placeholder="Declaratoin form no" style="width:91%;">

                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive mt-2">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light">
                                                            <tr class="item-row">
                                                                <th style="width: 30%">Particulars</th>
                                                                <th>Quantity</th>
                                                                <th>Unit</th>
                                                                <th>Rate</th>
                                                                <th>Discount</th>
                                                                <th>Individual Tax</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <!-- Here should be the item row -->
                                                            @foreach ($billingextras as $billingextra)
                                                                <tr class="item-row">
                                                                    <td>
                                                                        <input type="hidden" class="form-control itemid"
                                                                            placeholder="Particulars" type="text"
                                                                            name="particulars[]"
                                                                            value="{{ $billingextra->particulars }}">
                                                                        <div class="delete-btn">
                                                                            <select name="part" class="form-control item"
                                                                                disabled>
                                                                                <option value="">--Select Option--</option>
                                                                                @foreach ($categories as $category)
                                                                                    <option class="title" disabled
                                                                                        value="">
                                                                                        {{ $category->category_name }}
                                                                                    </option>
                                                                                    @foreach ($category->products as $product)
                                                                                        <option
                                                                                            value="{{ $product->id }}"
                                                                                            data-rate="{{ $product->product_price }}"
                                                                                            data-stock="{{ $product->stock }}"
                                                                                            data-priunit="{{ $product->primary_unit }}"
                                                                                            {{ $product->id == $billingextra->particulars ? 'selected' : '' }}>
                                                                                            {{ $product->product_name }}({{ $product->product_code }}){{(!empty($product->brand->brand_name) ? '-'.$product->brand->brand_name: "")}}
                                                                                        </option>
                                                                                    @endforeach
                                                                                @endforeach
                                                                            </select><a class="delete"
                                                                                href="javascript:;" title="Remove row">X</a>
                                                                        </div>

                                                                        <p class="text-danger">
                                                                            {{ $errors->first('particulars') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control qty"
                                                                            placeholder="Quantity" type="text"
                                                                            name="quantity[]"
                                                                            value="{{ $billingextra->quantity }}">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('quantity') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control unit" placeholder="Unit"
                                                                            type="text" name="unit[]"
                                                                            value="{{ $billingextra->unit }}" readonly>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('unit') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control rate" placeholder="Rate"
                                                                            type="text" name="rate[]"
                                                                            value="{{ $billingextra->rate }}">
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
                                                                        <div class="modal fade" tabindex="-1"
                                                                            role="dialog"
                                                                            aria-labelledby="exampleModalLabel"
                                                                            aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title">Discount
                                                                                            Details
                                                                                        </h5>
                                                                                        <button type="button"
                                                                                            class="close"
                                                                                            data-dismiss="modal"
                                                                                            aria-label="Close">
                                                                                            <span
                                                                                                aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="form-group">
                                                                                            <div class="row">
                                                                                                <div
                                                                                                    class="col-3">
                                                                                                    <label
                                                                                                        for="discounttype"
                                                                                                        class="txtleft">Discount
                                                                                                        Type:</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="col-9">
                                                                                                    <select
                                                                                                        name="discounttype[]"
                                                                                                        class="form-control discounttype">
                                                                                                        <option
                                                                                                            value="percent"
                                                                                                            {{ $billingextra->discounttype == 'percent' ? 'selected' : '' }}>
                                                                                                            Percent
                                                                                                            %</option>
                                                                                                        <option
                                                                                                            value="fixed"
                                                                                                            {{ $billingextra->discounttype == 'fixed' ? 'selected' : '' }}>
                                                                                                            Fixed
                                                                                                        </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <div class="row">
                                                                                                <div
                                                                                                    class="col-3">
                                                                                                    <label
                                                                                                        for="dtamt">Discount:</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="col-9">
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
                                                                                            class="btn btn-primary"
                                                                                            data-dismiss="modal">Submit</button>
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
                                                                        <div class="modal fade" tabindex="-1"
                                                                            role="dialog"
                                                                            aria-labelledby="exampleModalLabel"
                                                                            aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title">
                                                                                            Individual Tax
                                                                                            Details
                                                                                        </h5>
                                                                                        <button type="button"
                                                                                            class="close"
                                                                                            data-dismiss="modal"
                                                                                            aria-label="Close">
                                                                                            <span
                                                                                                aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="form-group">
                                                                                            <div class="row">
                                                                                                <div
                                                                                                    class="col-3">
                                                                                                    <label for="taxtype"
                                                                                                        class="txtleft">Tax
                                                                                                        Type:</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="col-9">
                                                                                                    <select name="taxtype[]"
                                                                                                        class="form-control taxtype">
                                                                                                        <option
                                                                                                            value="exclusive"
                                                                                                            {{ $billingextra->taxtype == 'exclusive' ? 'selected' : '' }}>
                                                                                                            Exclusive
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="inclusive"
                                                                                                            {{ $billingextra->taxtype == 'inclusive' ? 'selected' : '' }}>
                                                                                                            Inclusive
                                                                                                        </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <div class="row">
                                                                                                <div
                                                                                                    class="col-3">
                                                                                                    <label
                                                                                                        for="dtamt">Tax%:</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="col-9">
                                                                                                    <select name="tax[]"
                                                                                                        class="form-control taxper">
                                                                                                        @foreach ($taxes as $tax)
                                                                                                            <option
                                                                                                                value="{{ $tax->percent }}"
                                                                                                                {{ $billingextra->tax == $tax->percent ? 'selected' : '' }}>
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
                                                                                            class="btn btn-primary"
                                                                                            data-dismiss="modal">Submit</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input name="total[]" class="form-control total"
                                                                            value="{{$billingextra->total}}" readonly="readonly">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('total') }}
                                                                        </p>
                                                                    </td>
                                                                </tr>

                                                            @endforeach
                                                            <tr>
                                                                <td><strong>Total Quantity: </strong></td>
                                                                <td><span id="totalQty"
                                                                        style="color: red; font-weight: bold">0</span> Units
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>Sub Total</strong></td>
                                                                <td>
                                                                    <input type="text" name="subtotal" id="subtotal"
                                                                        value="0" class="form-control"
                                                                        readonly="readonly">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('subtotal') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:none">
                                                                </td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td><strong>Discount</strong>
                                                                <td>
                                                                    <input name="discountamount"
                                                                        class="form-control alldiscount" id="discount"
                                                                        value="0" type="text">
                                                                    <div class="modal fade" tabindex="-1" role="dialog"
                                                                        aria-labelledby="exampleModalLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">OverAll
                                                                                        Discount
                                                                                    </h5>
                                                                                    <button type="button"
                                                                                        class="close"
                                                                                        data-dismiss="modal"
                                                                                        aria-label="Close">
                                                                                        <span
                                                                                            aria-hidden="true">&times;</span>
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
                                                                                                <select
                                                                                                    name="alldiscounttype"
                                                                                                    class="form-control alldiscounttype">
                                                                                                    <option value="percent"
                                                                                                        {{ $purchase_inv->alldiscounttype == 'percent' ? 'selected' : '' }}>
                                                                                                        Percent
                                                                                                        %</option>
                                                                                                    <option value="fixed"
                                                                                                        {{ $purchase_inv->alldiscounttype == 'fixed' ? 'selected' : '' }}>
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
                                                                                                    name="alldtamt"
                                                                                                    class="form-control alldtamt"
                                                                                                    placeholder="Discount"
                                                                                                    value="{{ $purchase_inv->alldtamt }}">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-primary"
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
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
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
                                                                        aria-labelledby="exampleModalLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">Individual
                                                                                        Tax
                                                                                        Details
                                                                                    </h5>
                                                                                    <button type="button"
                                                                                        class="close"
                                                                                        data-dismiss="modal"
                                                                                        aria-label="Close">
                                                                                        <span
                                                                                            aria-hidden="true">&times;</span>
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
                                                                                                    <option
                                                                                                        value="exclusive"
                                                                                                        {{ $purchase_inv->alltaxtype == 'exclusive' ? 'selected' : '' }}>
                                                                                                        Exclusive</option>
                                                                                                    <option
                                                                                                        value="inclusive"
                                                                                                        {{ $purchase_inv->alltaxtype == 'inclusive' ? 'selected' : '' }}>
                                                                                                        Inclusive</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <div class="row">
                                                                                            <div class="col-3">
                                                                                                <label
                                                                                                    for="dtamt">Tax%:</label>
                                                                                            </div>
                                                                                            <div class="col-9">
                                                                                                <select name="alltax"
                                                                                                    class="form-control alltaxper">
                                                                                                    @foreach ($taxes as $tax)
                                                                                                        <option
                                                                                                            value="{{ $tax->percent }}"
                                                                                                            {{ $tax->percent == $purchase_inv->alltax ? 'selected' : '' }}>
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
                                                                                        class="btn btn-pirmary"
                                                                                        data-dismiss="modal">Submit</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td><strong>Shipping</strong></td>
                                                                <td>
                                                                    <input name="shipping" class="form-control"
                                                                        id="shipping"
                                                                        value="{{ $purchase_inv->shipping }}"
                                                                        type="text">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('shipping') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td><strong>Grand Total</strong></td>
                                                                <td>
                                                                    <input type="text" name="grandtotal"
                                                                        class="form-control" id="grandTotal" value="0"
                                                                        readonly="readonly">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('grandtotal') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td style="border:none"></td>
                                                                <td><strong>Refundable VAT</strong></td>
                                                                <td><input type="text" name="vat_refundable"
                                                                        class="form-control" value="0"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                @if ($ird_sync == 1)
                                                                    <td>
                                                                        <strong>IRD Sync<i class="text-danger">*</i>:
                                                                        </strong>
                                                                    </td>
                                                                    <td>
                                                                        <input type="radio" name="sync_ird" value="1"
                                                                            checked> Yes
                                                                        <input type="radio" name="sync_ird" value="0"> No
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                    <td></td>
                                                                @endif
                                                                <td>
                                                                    Status:
                                                                </td>
                                                                <td>
                                                                    <input type="radio" name="status" value="1" checked>
                                                                    Approve
                                                                    <input type="radio" name="status" value="0"> Unapprove
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-4">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="payment_type">Payment Type</label>
                                                        <select name="payment_type" id="payment_type"
                                                            class="form-control payment_type">
                                                            <option value="">--Select an option--</option>
                                                            <option value="paid">Paid</option>
                                                            <option value="partially_paid">Partially Paid</option>
                                                            <option value="unpaid">Unpaid</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="payment_amount">Payment Amount</label>
                                                        <input type="text" name="payment_amount" id="payment_amount"
                                                            class="form-control payment_amount"
                                                            placeholder="Enter Paid Amount" required>
                                                        <p class="off text-danger">Payment can't be more than that of Grand
                                                            Total
                                                        </p>
                                                    </div>
                                                    <div class="col-md-2">

                                                            <label for="Payment Type">Payment Method:</label>
                                                            <select name="payment_method" class="form-control payment_method">
                                                                @foreach ($payment_methods as $method)
                                                                    <option value="{{ $method->id }}">
                                                                        {{ $method->payment_mode }}</option>
                                                                @endforeach
                                                            </select>

                                                    </div>

                                                    <div class="col-md-2" id="online_payment" style="display: none;">

                                                            <label for="Bank">Select a portal:</label>
                                                            <div class="row">
                                                                <div class="col-md-9 pr-0">
                                                                    <select name="online_portal"
                                                                        class="form-control online_portal_class" id="online_portal">
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3" style="padding-left:7px;">
                                                                    <button type="button" data-toggle='modal'
                                                                        data-target='#onlinePortalAdd' data-toggle='tooltip'
                                                                        data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                                        title="Add New Portal"><i
                                                                            class="fas fa-plus"></i></button>
                                                                </div>
                                                            </div>

                                                    </div>

                                                    <div class="col-md-2" id="customer_portal_id" style="display: none;">

                                                            <label for="">Portal Id:</label>
                                                            <input type="text" class="form-control" placeholder="Portal Id"
                                                                name="customer_portal_id">

                                                    </div>

                                                    <div class="col-md-2" id="bank" style="display: none;">

                                                            <label for="Bank">From Bank:</label>
                                                            <div class="row">
                                                                <div class="col-md-9 pr-0">
                                                                    <select name="bank_id" class="form-control bank_info_class"
                                                                        id="bank_info">
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

                                                    <div class="col-md-2" id="cheque" style="display: none;">

                                                            <label for="cheque no">Cheque no.:</label>
                                                            <input type="text" class="form-control" placeholder="Cheque No."
                                                                name="cheque_no">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <div class="form-gorup">
                                                    <label for="remarks">Remarks: </label>
                                                    <textarea name="remarks" class="form-control" placeholder="Remarks.."
                                                        rows="5"></textarea>
                                                    <p class="text-danger">
                                                        {{ $errors->first('remarks') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12 btn-bulk d-flex justify-content-end">
                                                <button class="btn btn-primary submit" type="submit">Submit</button>
                                                <button type="submit" class="btn btn-secondary btn-large" name="saveandcontinue" value="1">Submit And Continue</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class='modal fade text-left' id='bankinfoadd' tabindex='-1' role='dialog'
                                aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document' style="max-width: 1000px;">
                                    <div class='modal-content'>
                                        <div class='modal-header text-center'>
                                            <h2 class='modal-title' id='exampleModalLabel'>Add New Bank</h2>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
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
                                                                                class="form-control" id="bank_name"
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
                                                                                class="form-control" id="head_branch">
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
                                                                                    <option value="{{ $province->id }}">
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
                                                                                <i class="text-danger">*</i>:</label>
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
                                                                            <input type="text" name="bank_local_address"
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
                                                                            <input type="text" class="form-control"
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
                                                                                <i class="text-danger">*</i>:</label>
                                                                            <input type="text" class="form-control"
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
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
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
                                                                                class="form-control" id="portal_name"
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
                                                                            <input type="text" class="form-control"
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
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script src="{{ asset('backend/dist/js/jquery.salesinvoice.js') }}"></script>
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

            mainInput.nepaliDatePicker({
                onChange: function() {
                    var nepdate = mainInput.value;
                    var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                    document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(
                        NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                }
            });
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

            var normal = $('input.normal');
            normal.click(function() {
                if (normal.is(':checked')) {
                    $('.reference_invoice_no').attr('readonly', 'readonly');
                }
            })
            $('input.reference').click(function() {
                $('.reference_invoice_no').removeAttr('readonly');
            })

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
            $(".cli").val($('.selclient').find(":selected").val());
        });

        $(document).ready(function() {
            $(".godown_info").select2();
        });

        $(document).ready(function() {
            $(".selclient").select2();
        });
    </script>
    <script>
        $(function() {
            $('.payment_type').change(function() {
                var payment_type = $(this).find(':selected').val();
                var payment_amount = $('.payment_amount');
                var grandtotal = $('#grandTotal').val();
                if (payment_type == 'partially_paid') {
                    payment_amount.attr('readonly', false);
                } else if (payment_type == 'paid') {
                    payment_amount.attr('readonly', 'readonly');
                    payment_amount.val(grandtotal);
                } else if (payment_type == 'unpaid') {
                    payment_amount.attr('readonly', 'readonly');
                    payment_amount.val('0');
                }
            })
        });

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
                } else if (payment == 4) {
                    document.getElementById("bank").style.display = "none";
                    document.getElementById("cheque").style.display = "none";
                    document.getElementById("online_payment").style.display = "block";
                    document.getElementById("customer_portal_id").style.display = "block";
                } else {
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
                        document.getElementById("online_portal").innerHTML =
                            '<option value=""> --Select option-- </option>' +
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
        jQuery(document).ready(function() {
            jQuery().invoice({
                addRow: "#addRow",
                delete: ".delete",
                parentClass: ".item-row",

                item: '.item',
                stock: '.stock',
                danger: '.danger',
                unit: ".unit",
                rate: ".rate",
                qty: ".qty",
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
                submit: ".submit"
            });
        });
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
        });
    </script>
    <script>
        $(document).ready(function() {
            $('body').trigger('click');
            $('body').trigger('click');
        });
    </script>
    <script>
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


        // Calculate Rate
        // $(document).on('keyup', '.total', function(){
        //     // console.log('rame');
        //     let rtaxper = $(this).closest('tr').find('.taxper');
        //     let selectedtaxper = rtaxper.find(":selected").val();
        //     let taxtype = $(this).closest('tr').find('.taxtype');
        //     let selectedtaxtype = taxtype.find(":selected").val();
        //     let qty = $(this).closest('tr').find('.qty');
        //     let dt = $(this).closest('tr').find('.discounttype').find(':selected').val();
        //     let d = $(this).closest('tr').find('.dtamt').val();
        //     let inputtotal = $(this).closest('tr').find('.total');
        //     var newtotal = $(this).val();
        //     var tot = Number(newtotal)/Number(qty.val());
        //     let rate = $(this).closest('tr').find('.rate');
        //     if (selectedtaxtype == "exclusive") {
        //         if(dt == "percent"){
        //             // var r = (100*Number(tot))/(100 + Number(selectedtaxper) - Number(d));
        //             var r = (10000*Number(tot))/((100+Number(selectedtaxper))*(100-Number(d)));
        //             r = parseFloat(r.toFixed(2));
        //             rate.val(r);
        //         }else{
        //             // var r = ((100*Number(tot) + 100*(Number(d)))/(100 + Number(selectedtaxper)));
        //             var r = ((100 * Number(tot))/(100+Number(selectedtaxper))) - Number(d);
        //             r = parseFloat(r.toFixed(2));
        //             rate.val(r);
        //         }
        //     }
        //     else if (selectedtaxtype == "inclusive") {
        //         // var total = (newrate - discountamt.val()) * qty.val();

        //         // total = self.roundNumber(total, 2);

        //         // inputtotal.val(total);
        //         if(dt == "percent"){
        //             // var r = (100*Number(tot))/(100-Number(d));
        //             var r = ((100*Number(tot))+Number(d))/100;
        //             r = parseFloat(r.toFixed(2));
        //             rate.val(r);
        //         }else{
        //             // var r = Number(tot) + Number(d);
        //             var r = Number(tot) + Number(d);
        //             r = parseFloat(r.toFixed(2));
        //             rate.val(r);
        //         }
        //     }

        //     $('body').click();
        // })
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
        window.categories = <?php echo json_encode($categories); ?>
    </script>
@endpush

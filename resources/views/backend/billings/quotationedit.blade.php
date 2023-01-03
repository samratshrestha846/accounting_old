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
                        <h1>Edit Quotation Bill </h1>
                        <div class="btn-bulk">
                            <a href="{{ route('billings.report', $billing_type_id) }}" class="global-btn">View All Quotation</a>
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
                        <h2>Quotation</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ route('billings.update', $billing->id) }}" method="POST" id="quotationupdate">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="billing_type_id" value="7">
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
                                                <label for="ledger_no">VAT Bill no.: </label>
                                                <input type="text" class="form-control" name="ledger_no"
                                                    value="{{ $billing->ledger_no }}" >
                                                <p class="text-danger">
                                                    {{ $errors->first('ledger_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="file_no">File No (Optional): </label>
                                            <input type="text" name="file_no" class="form-control"
                                                value="{{ $billing->file_no }}" placeholder="File no.">
                                            <p class="text-danger">
                                                {{ $errors->first('file_no') }}
                                            </p>
                                        </div>

                                        <div class="col-md-2">
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

                                        <div class="col-md-2" id="online_payment" @if ($billing->online_portal_id == null) style="display: none;" @endif>
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

                                        <div class="col-md-2" id="customer_portal_id" @if ($billing->customer_portal_id == null) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label for="">Portal Id:</label>
                                                <input type="text" class="form-control" placeholder="Portal Id"
                                                    name="customer_portal_id" @if ($billing->customer_portal_id != null) value="{{ $billing->customer_portal_id }}" @endif>
                                            </div>
                                        </div>

                                        @if ($billing->bank_id == null)
                                            <div class="col-md-2" id="bank" style="display: none;">
                                                <div class="form-group">
                                                    <label for="Bank">From Bank:</label>
                                                    <select name="bank_id" class="form-control" id="bank_info">
                                                        <option value="">--Select a bank--</option>
                                                        @foreach ($banks as $bank)
                                                            <option value="{{ $bank->id }}">{{ $bank->bank_name }} (A/C Holder - {{ $bank->account_name }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-2" id="bank">
                                                <div class="form-group">
                                                    <label for="Bank">From Bank:</label>
                                                    <select name="bank_id" class="form-control" id="bank_info">
                                                        <option value="">--Select a bank--</option>
                                                        @foreach ($banks as $bank)
                                                            <option value="{{ $bank->id }}"{{ $bank->id == $billing->bank_id ? 'selected' : '' }}>{{ $bank->bank_name }} (A/C Holder - {{ $bank->account_name }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($billing->cheque_no == null)
                                            <div class="col-md-2" id="cheque" style="display: none;">
                                                <div class="form-group">
                                                    <label for="cheque no">Cheque no.:</label>
                                                    <input type="text" class="form-control" placeholder="Cheque No." name="cheque_no">
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-2" id="cheque">
                                                <div class="form-group">
                                                    <label for="cheque no">Cheque no.:</label>
                                                    <input type="text" class="form-control" placeholder="Cheque No." name="cheque_no" value="{{ $billing->cheque_no }}">
                                                </div>
                                            </div>
                                        @endif
                                        @if($billing_type_id != 7)
                                        <div class="col-md-2">
                                            <div class="form-gorup">
                                                <label for="godown">Select Godown</label>
                                                <select name="godown" id="godown" class="form-control" readonly="readonly">
                                                    <option value="{{ $selgodown->id ?? '' }}"
                                                        {{ $selgodown->id == $billing->godown ? 'selected' : '' }}>
                                                        {{ $selgodown->godown_name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-2">
                                            <div class="form-gorup">
                                                <label for="godown">Select Godown</label>
                                                <select name="godown" id="godown" class="form-control">
                                                    @foreach($godowns as $godown)
                                                    <option value="{{ $godown->id }}">
                                                        {{ $godown->godown_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-md-12 text-right">
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

                                    <div class="col-md-12 mt-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center">
                                                <thead class="thead-light">
                                                    <tr class="item-row">
                                                        <th style="width: 32%">Particulars</th>
                                                        <th
                                                            style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                                            Picture</th>
                                                        <th
                                                            style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                                            Brand</th>
                                                        <th
                                                            style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                                            Model</th>
                                                        <th style="width: 10%">Quantity</th>
                                                        <th class="stock">Stock</th>
                                                        <th class="unit">Unit</th>
                                                        <th class="rate">Rate</th>
                                                        <th class="discount" style="width: 10%">Discount</th>
                                                        <th class="tax" style="width: 10%">Individual Tax</th>
                                                        <th class="total">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($billing->billingextras as $billingextra)
                                                        <tr class="item-row">
                                                            <td class="item-name">
                                                                <div class="delete-btn">
                                                                    <select name="particulars[]" class="form-control item"
                                                                        readonly="readonly">
                                                                        <option value="">--Select Option--</option>
                                                                        @foreach ($selgodown->godownproduct as $prod)

                                                                            <option value="{{ $prod->product_id }}"
                                                                                {{ $prod->product_id == $billingextra->particulars ? 'selected' : '' }}
                                                                                data-stock="{{ $prod->stock }}"
                                                                                data-rate="{{ $prod->product->product_price }}"
                                                                                data-priunit="{{ $prod->product->primary_unit }}"
                                                                                data-brand="{{ $prod->product->brand->brand_name }}"
                                                                                data-model="{{ $prod->product->series->series_name }}"
                                                                                data-image="{{ Storage::disk('uploads')->url($prod->product->product_images[0]->location) }}">
                                                                                {{ $prod->product->product_name }}({{ $prod->product->product_code }})
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
                                                            <td
                                                                style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                                                <img src="" alt="" class="product_image"
                                                                    style="max-height: 50px;">
                                                            </td>
                                                            <td
                                                                style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                                                <span class="text-center brand"></span>
                                                            </td>
                                                            <td
                                                                style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                                                <span class="text-center model"></span>
                                                            </td>
                                                            <td>
                                                                <input class="form-control qty" placeholder="Quantity"
                                                                    type="text" name="quantity[]"
                                                                    value="{{ $billingextra->quantity }}">
                                                                {{-- <span class="stock">123</span> --}}
                                                                <p class="text-danger">
                                                                    {{ $errors->first('quantity') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <span class="stock"></span>
                                                                <p class="text-danger danger"></p>
                                                            </td>

                                                            <td>
                                                                <input class="form-control unit" placeholder="Unit"
                                                                    type="hidden" name="unit[]"
                                                                    value="{{ $billingextra->unit }}" readonly>
                                                                <span class="unit"></span>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('unit') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input class="form-control rate" placeholder="Rate" name="rate[]" value="{{$billingextra->rate}}">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('rate') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input class="form-control discountamt"
                                                                    placeholder="Discount per unit" type="text"
                                                                    name="discountamt[]" value="0">
                                                                {{-- <span class="unit"></span> --}}
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
                                                        <td colspan="15" class="text-right">
                                                            <div class="btn-bulk justify-content-end">
                                                                <a id="addRow" href="javascript:;" title="Add a row"
                                                                class="btn btn-primary">+ Add</a>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td
                                                            style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                                        </td>
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
                                                        <td><strong>Total Quantity: </strong>
                                                        </td>
                                                        <td>
                                                            <span id="totalQty"
                                                                style="color: red; font-weight: bold">0</span> Units
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td
                                                            style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td></td>
                                                        <td></td>
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
                                                        <td
                                                            style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                                        </td>
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
                                                        <td
                                                            style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                                        </td>
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
                                                        <td
                                                            style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                                        </td>
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
                                                        <td
                                                            style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td
                                                            style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                                        </td>
                                                        <td style="border:none;"></td>
                                                        <td style="border:none;"></td>
                                                        <td style="border:none;"></td>
                                                        <td style="border:none;"></td>
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

                                    <div class="col-md-12 mt-3">
                                        <div class="form-gorup">
                                            <label for="remarks">Remarks: </label>
                                            <textarea name="remarks"
                                                class="form-control" rows="5">{{ $billing->remarks }}</textarea>
                                            <p class="text-danger">
                                                {{ $errors->first('remarks') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12 btn-bulk text-right ">
                                        <button class="btn btn-primary submit ml-auto" type="submit">Update</button>
                                        <button class="btn btn-secondary btn-large convert_to_sale" type="button">Convert To Sale</button>
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
{{-- as.k --}}
<script>
    $('.convert_to_sale').click(function(e){
        e.preventDefault();
        $('#quotationupdate').prop('action',"{{route('billings.salesstore')}}");
        $('input[name="billing_type_id"]').val('1');
        $('input[name="_method"]').val('POST');
        if(confirm("Are you Sure")){
            $("#quotationupdate").prepend('<input type="hidden" name="sync_ird" value="1">');
            $("#quotationupdate")[0].submit();

        }else{
            var billing_id = @php echo json_decode($billing->id) @endphp;
            var url = "{{route('billings.update',':id')}}";
            url = url.replace(':id',billing_id);
            $('#quotationupdate').prop('action',url);
            $('input[name="billing_type_id"]').val('7');
            $('input[name="_method"]').val('PUT');
        }

    });
</script>
{{-- end as.k --}}

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


    <script src="{{ asset('backend/dist/js/jquery.editinvoice.js') }}"></script>
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
                product_image: '.product_image',
                brand: '.brand',
                model: '.model',
                stock: '.stock',
                danger: '.danger',
                unit: ".unit",
                spanunit: 'span.unit',
                rate: ".rate",
                spanrate: 'span.rate',
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

            $(document).ready(function() {
                $(".client_info").select2();
            });
        });
    </script>
    {{-- <script>
        $(function(){
            var selgodown = $('#godown').find(":selected").val();
            var warehouses = @php echo  json_encode($godowns); @endphp;
            var warehousecount = warehouses.length;
            // console.log(warehouses);
            function wareproducts(){

                for(let i=0; i<warehousecount; i++){
                    if(warehouses[i].id == selgodown){
                        var godownpros = warehouses[i].godownproduct;
                        var gdpcount = godownpros.length;
                        console.log(godownpros);
                        var prodoptions = '';
                        for(let s = 0; s<gdpcount; s++){
                            var stock = godownpros[s].stock;
                            var proname = godownpros[s].product.product_name;
                            var rate = godownpros[s].product.product_price;
                            var primary_unit = godownpros[s].product.primary_unit;
                            var procode = godownpros[s].product.product_code;
                            var proid = godownpros[s].product_id;
                            prodoptions += `<option value="${proid}"
                                            data-rate="${rate}"
                                            data-stock="${stock}"
                                            data-priunit = "${primary_unit}">
                                            ${proname}(${procode})
                                        </option>`;
                        }

                    }
                }
                return prodoptions;
            }
            $('.item').html(wareproducts());
        })
    </script> --}}
    <script>
        window.aurl = {!! json_encode(url('/')) !!} + '/';

        $('#godown').change(function() {
            var selgodown = $('#godown').find(":selected").val();
            var warehouses = @php echo  json_encode($godowns); @endphp;
            var warehousecount = warehouses.length;

            function wareproducts() {

                for (let i = 0; i < warehousecount; i++) {
                    if (warehouses[i].id == selgodown) {
                        var godownpros = warehouses[i].godownproduct;
                        var gdpcount = godownpros.length;
                        console.log(godownpros);
                        var prodoptions = '';
                        for (let s = 0; s < gdpcount; s++) {
                            var stock = godownpros[s].stock;
                            var proname = godownpros[s].product.product_name;
                            var rate = godownpros[s].product.product_price;
                            var primary_unit = godownpros[s].product.primary_unit;
                            var procode = godownpros[s].product.product_code;
                            var proid = godownpros[s].product_id;
                            var pimg = godownpros[s].product.product_images[0].location;


                            var app_url = {!! json_encode(url('/')) !!} + '/';
                            window.img_url = app_url + 'uploads/' + pimg;

                            prodoptions += `<option value="${proid}"
                                                data-rate="${rate}"
                                                data-stock="${stock}"
                                                data-priunit = "${primary_unit}"
                                                data-image = "${window.img_url}">
                                                ${proname}(${procode})
                                            </option>`;
                        }

                    }
                }
                return prodoptions;
            }

            $('.item').html(wareproducts());

        })
    </script>
    <script>
        $(document).ready(function() {
            $('body').trigger('click');
            $('body').trigger('click');
        });
    </script>

<script>
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
    $('.alldiscounttype').change(function(){
        $('body').click();
    })

    $(document).on('change', '.item', function() {
        let rate = $(this).find(':selected').data('rate');
        let ratebox = $(this).closest('tr').find('.rate');
        let clientratepercenttype = typeof $('#client').find(":selected").data("dealer_percent");
        if (clientratepercenttype == "undefined") {
            ratebox.val(rate);
        } else {
            clientratepercent = $('#client').find(":selected").data("dealer_percent");
            rate_after_dealer_percent = rate - (rate * clientratepercent) / 100;
            ratebox.val(rate_after_dealer_percent);
        }
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
        window.warehouses = @php echo  json_encode($godowns); @endphp;
        window.quotationsetting = @php echo  json_encode($quotationsetting); @endphp;
    </script>
    <script>
        window.taxes = <?php echo json_encode($taxes); ?>;
        window.categories = <?php echo json_encode($categories); ?>
    </script>
@endpush

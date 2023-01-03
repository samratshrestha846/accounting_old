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
                    <h1>Update Debit Note</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('billings.report', $billing_type_id) }}" class="global-btn">View All Debit
                            Notes</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="col-sm-12">
                        <div class="alert  alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="col-sm-12">
                        <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h2>Debit Note</h2>
                    </div>

                    <div class="card-body">
                        <div class="ibox">
                            <div class="row ibox-body">
                                <div class="col-12">
                                    <form action="{{ route('billings.update', $billing->id)}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="billing_type_id" value="5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fiscal-year">Fiscal Year: </label>
                                                    <select name="fiscal_year_id" id="fiscal-year" class="form-control">
                                                        @foreach ($fiscal_years as $fiscalyear)
                                                            <option value="{{ $fiscalyear->id }}" {{$fiscalyear->id == $billing->fiscal_year_id ? 'selected' : ''}}>{{ $fiscalyear->fiscal_year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('fiscal_year_id') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="entry_date">Entry Date (B.S)<i class="text-danger">*</i></label>
                                                            <input type="text" name="nep_date" id="entry_date_nepali"
                                                                class="form-control" value="{{$billing->nep_date}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="english_date">Entry Date (A.D)<i class="text-danger">*</i></label>
                                                            <input type="date" name="eng_date" id="english"
                                                                class="form-control" value="{{$billing->eng_date}}" readonly="readonly">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="transaction_no">Transaction No:</label>
                                                    <input type="text" class="form-control" name="transaction_no">
                                                    <p class="text-danger">
                                                        {{ $errors->first('transaction_no') }}
                                                    </p>
                                                </div>
                                            </div> --}}
                                            {{-- <div class="col-md-3">
                                                <label for="transaction_no"></label>
                                                <div class="form-group">
                                                    <input type="radio" name="noname" value="reference" class="reference" checked>
                                                    Reference
                                                    <input type="radio" name="noname" value="normal" class="normal"> Normal Entry

                                                </div>
                                            </div> --}}
                                            {{-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="reference_no">Reference No: </label>
                                                    <input type="text" class="form-control" name="reference_no">
                                                    <p class="text-danger">
                                                        {{ $errors->first('reference_no') }}
                                                    </p>
                                                </div>
                                            </div> --}}
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="reference_no">Reference Inv No: </label>
                                                    <input type="text" class="form-control reference_invoice_no"
                                                        name="reference_invoice_no" readonly="readonly" value="{{$billing->reference_no}}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('reference_invoice_no') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="party_name">Party Name: </label>
                                                    <select name="vendor" class="form-control selvendor" disabled>
                                                        @foreach ($vendors as $vendor)
                                                        <option value="{{$vendor->id}}" {{$vendor->id == $billing->vendor_id ? 'selected' : ''}}>{{$vendor->company_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="vendor_id" class="form-control vend" readonly="readonly" value="">
                                                    <p class="text-danger">
                                                        {{ $errors->first('vendor_id') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="pan_vat">Supplier Pan/Vat</label>
                                                    <input type="text" class="form-control panVat" value="{{ $billing->suppliers->pan_vat}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="ledger_no">Party Bill No: </label>
                                                    <input type="text" class="form-control" name="ledger_no" value="{{$billing->ledger_no}}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('ledger_no') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="file_no">File No (Optional): </label>
                                                    <input type="text" name="file_no" class="form-control" value="{{$billing->file_no}}" placeholder="File no.">
                                                    <p class="text-danger">
                                                        {{ $errors->first('file_no') }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if ($currentcomp->company->is_importer == 1)
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="declaration_form_no">Decleration Form no<i
                                                                class="text-danger"></i> :</label>
                                                        <input type="text" id="declaration_form_no" name="declaration_form_no"
                                                            class="form-control" value="{{$billing->declaration_form_no}}"
                                                            placeholder="Declaratoin form no" style="width:91%;">

                                                    </div>
                                                </div>
                                            @endif
                                            {{-- <div class="col-md-3">
                                                <div class='form-group'>
                                                    <label for='reason'>Reason:</label>
                                                    <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">

                                                <div class='form-group'>
                                                    <label for='description'>Description: </label>
                                                    <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                </div>
                                            </div> --}}

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
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
                                                            @foreach ($billing->billingextras as $billingextra)
                                                            <tr class="item-row">
                                                                <td>
                                                                    <input type="hidden" class="form-control itemid"
                                                                        placeholder="Particulars" type="text" name="particulars[]" value="">
                                                                        <div class="delete-btn">
                                                                            <select name="part" class="form-control item" disabled>
                                                                            <option value="">--Select Option--</option>
                                                                            @foreach ($categories as $category)
                                                                                <option class="title" disabled value="">{{$category->category_name}}</option>
                                                                                @foreach ($category->products as $product)
                                                                                    <option value="{{$product->id}}"
                                                                                        data-rate="{{$product->product_price}}"
                                                                                        data-stock="{{$product->stock}}"
                                                                                        data-priunit = "{{$product->primary_unit}}" {{$product->id == $billingextra->particulars ? "selected" : ""}}>
                                                                                    {{$product->product_name}}({{$product->product_code}})</option>
                                                                                @endforeach
                                                                            @endforeach
                                                                        </select><a class="delete" href="javascript:;" title="Remove row">X</a>
                                                                        </div>

                                                                    <p class="text-danger">
                                                                        {{ $errors->first('particulars') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control qty" placeholder="Quantity"
                                                                        type="text" name="quantity[]" value="{{$billingextra->quantity}}">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('quantity') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control unit" placeholder="Unit" type="text"
                                                                        name="unit[]" value="{{$billingextra->unit}}" readonly>
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('unit') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control rate" placeholder="Rate" type="text"
                                                                        name="rate[]" value="{{$billingextra->rate}}">
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

                                                            @endforeach

                                                            {{-- <tr id="hiderow">
                                                                <td colspan="5" class="text-left">
                                                                    <a id="addRow" href="javascript:;" title="Add a row"
                                                                        class="btn btn-primary">Add a row</a>
                                                                </td>
                                                            </tr> --}}
                                                            <tr>
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
                                                                <td><span id="totalQty"
                                                                    style="color: red; font-weight: bold">0</span> Units</td>
                                                                <td></td>
                                                                <td><strong>Discount</strong>
                                                                    <td>
                                                                        <input name="discountamount" class="form-control alldiscount" id="discount"
                                                                            value="0" type="text">
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
                                                                                                        <option value="percent" {{$billing->alldiscounttype=="percent" ? "selected" : ""}}>Percent
                                                                                                            %</option>
                                                                                                        <option value="fixed" {{$billing->alldiscounttype=="fixed" ? "selected" : ""}}>Fixed
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
                                                                                                        value="{{$billing->alldtamt}}">
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
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>Tax Amount</strong></td>
                                                                <td>
                                                                    <input type="text" name="taxamount" class="gtaxamount form-control" value="0">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('taxamount') }}
                                                                    </p>
                                                                    <div class="modal fade" tabindex="-1" role="dialog"
                                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">Individual Tax Details
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
                                                                                                    <option value="exclusive" {{$billing->alltaxtype=="exclusive" ? "selected" : ""}}>
                                                                                                        Exclusive</option>
                                                                                                    <option value="inclusive" {{$billing->alltaxtype=="inclusive" ? "selected" : ""}}>
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
                                                                                                            value="{{ $tax->percent }}" {{$tax->percent == $billing->alltax ? "selected" : ""}}>
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
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>Shipping</strong></td>
                                                                <td>
                                                                    <input name="shipping" class="form-control" id="shipping"
                                                                        value="{{$billing->shipping}}" type="text">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('shipping') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
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
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>Refundable VAT</strong></td>
                                                                <td><input type="text" name="vat_refundable" class="form-control" value="{{$billing->vat_refundable}}"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                @if($ird_sync == 1)
                                                                <td>
                                                                    <strong>IRD Sync<i class="text-danger">*</i>: </strong>
                                                                </td>
                                                                <td>
                                                                    <input type="radio" name="sync_ird" value="1" {{$billing->sync_ird == 1 ? "checked" : ''}}> Yes
                                                                    <input type="radio" name="sync_ird" value="0"  {{$billing->sync_ird == 0 ? "checked" : ''}}> No
                                                                </td>
                                                                @else
                                                                <td></td>
                                                                <td></td>
                                                                @endif

                                                                <td>
                                                                    <b>Status</b>:
                                                                </td>
                                                                <td>
                                                                    <input type="radio" name="status" value="1" {{$billing->status == 1 ? "checked" : ""}}> Approve
                                                                    <input type="radio" name="status" value="0" {{$billing->status == 0 ? "checked" : ""}}> Unapprove
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
                                                    <input type="text" class="form-control due_amt" value="{{$billing->billing_credit->credit_amount ?? 0 }}" readonly>

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
                                                                    Credit</option>
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
                                                                            <option value="{{ $bank->id }}">{{ $bank->bank_name }} (A/C Holder - {{ $bank->account_name }})</option>
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
                                                                            <option value="{{ $bank->id }}"{{ $bank->id == $billing->bank_id ? 'selected' : '' }}>{{ $bank->bank_name }} (A/C Holder - {{ $bank->account_name }})</option>
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
                                                    <textarea name="remarks" class="form-control" rows="5">{{$billing->remarks}}</textarea>
                                                    <p class="text-danger">
                                                        {{ $errors->first('remarks') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-secondary" type="submit">Submit</button>
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
    <script src="{{ asset('backend/dist/js/jquery.noteinvoice.js') }}"></script>
    <script type="text/javascript">
        var mainInput = document.getElementById("entry_date_nepali");
        mainInput.nepaliDatePicker({
            onChange: function() {
                var nepdate = mainInput.value;
                var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
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
                $(".vend").val($('.selvendor').find(":selected").val());
            });

            $(document).ready(function() {
                $(".selvendor").select2();
            });
    </script>
    <script>
        jQuery(document).ready(function() {
            jQuery().invoice({
                addRow: "#addRow",
                delete: ".delete",
                parentClass: ".item-row",

                item : ".item",
                itemid : ".itemid",
                unit: ".unit",
                rate: ".rate",
                qty: ".qty",
                total: ".total",
                totalQty: "#totalQty",

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
        $(document).ready(function(){
            $('body').trigger('click');
            $('body').trigger('click');
        });
        $(document).on('keyup', '.qty, .rate', function(){
            $('body').click();
            $('body').click();
        })
        $(document).on('change', '.alldiscounttype', function(){
            $('body').click();
        })
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
        window.categories = <?php echo json_encode($categories) ?>

    </script>
@endpush

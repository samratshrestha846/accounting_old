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
                    <h1>Convert To Purchase </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('billings.report', $billing_type_id) }}" class="global-btn">View
                            All Purchases</a>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
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
                @if ($errors->any())
                    {{ implode('', $errors->all('<div>:message</div>')) }}
                @endif

                <div class="card">
                    <div class="card-header">
                        <h2>Purchase</span></h2>
                    </div>
                    <div class="card-body">
                        <div class="ibox">
                            <div class="row ibox-body">
                                <div class="col-12">
                                    <form action="{{ route('billings.store') }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="billing_type_id" value="2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fiscal-year">Fiscal Year: </label>
                                                    <select name="fiscal_year_id" id="fiscal-year" class="form-control">
                                                        @foreach ($fiscal_years as $fiscalyear)
                                                            <option value="{{ $fiscalyear->id }}"
                                                                {{ $fiscalyear->id == $purchaseOrder->fiscal_year_id ? 'selected' : '' }}>
                                                                {{ $fiscalyear->fiscal_year }}
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
                                                            <label for="entry_date">Entry Date (B.S)<i
                                                                    class="text-danger">*</i></label>
                                                            <input type="text" name="nep_date" id="entry_date_nepali"
                                                                class="form-control" value="{{ $purchaseOrder->nep_date }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="english_date">Entry Date (A.D)<i
                                                                    class="text-danger">*</i></label>
                                                            <input type="date" name="eng_date" id="english" class="form-control"
                                                                value="{{ $purchaseOrder->eng_date }}" readonly="readonly">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="transaction_no">Transaction No:</label>
                                                    <input type="text" class="form-control" name="transaction_no" value="{{$billing->transaction_no}}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('transaction_no') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="reference_no">Reference No: </label>
                                                    <input type="text" class="form-control" name="reference_no" value="{{$billing->reference_no}}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('reference_no') }}
                                                    </p>
                                                </div>
                                            </div> --}}
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="party_name">Party Name: </label>
                                                    <div class="v-add">
                                                        <select name="vendor_id" id="vendor" class="form-control vendor_info"
                                                            style="font-size: 18px; padding: 5px;" required>
                                                            @foreach ($vendors as $vendor)
                                                                <option value="{{ $vendor->id }}"
                                                                    {{ $vendor->id == $purchaseOrder->vendor_id ? 'selected' : '' }}>
                                                                    {{ $vendor->company_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="text-danger">
                                                            {{ $errors->first('vendor_id') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="ledger_no">Ledger No: </label>
                                                    <input type="text" class="form-control" name="ledger_no" value=""
                                                        placeholder="Enter Ledger No." required>
                                                    <p class="text-danger">
                                                        {{ $errors->first('ledger_no') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="file_no">File No: </label>
                                                    <input type="text" name="file_no" class="form-control" value=""
                                                        placeholder="Enter File no." required>
                                                    <p class="text-danger">
                                                        {{ $errors->first('file_no') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="Payment Type">Payment Method:</label>
                                                    <select name="payment_method" class="form-control payment_method">
                                                        <option value="">--Select One--</option>
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
                                                        <div class="col-md-10 pr-0">
                                                            <select name="online_portal" class="form-control online_portal_class" id="online_portal">
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
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
                                                        <div class="col-md-10 pr-0">
                                                            <select name="bank_id" class="form-control bank_info_class" id="bank_info">
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-center">
                                                        <thead class="thead-light">
                                                            <tr class="item-row">
                                                                <th style="width: 40%">Particulars</th>
                                                                <th>Quantity</th>
                                                                <th>Unit</th>
                                                                <th>Rate</th>
                                                                <th style="width:20%">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <!-- Here should be the item row -->
                                                            @foreach ($purchaseOrder->purchaseOrderExtras as $purchseOrderExtra)
                                                                <tr class="item-row">
                                                                    <td class="item-name">
                                                                        <div class="delete-btn">
                                                                            <select name="particulars[]" class="form-control item">
                                                                                <option value="">--Select Option--</option>
                                                                                @foreach ($categories as $category)
                                                                                    <option class="title" disabled
                                                                                        value="">{{ $category->category_name }}
                                                                                    </option>
                                                                                    @foreach ($category->products as $product)
                                                                                        <option value="{{ $product->id }}"
                                                                                            data-rate="{{ $product->total_cost }}"
                                                                                            data-priunit="{{ $product->primary_unit }}"
                                                                                            {{ $purchseOrderExtra->particulars == $product->id ? 'selected' : '' }}>
                                                                                            {{ $product->product_name }}({{ $product->product_code }})
                                                                                        </option>
                                                                                    @endforeach
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
                                                                            value="{{ $purchseOrderExtra->quantity }}">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('quantity') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control unit" placeholder="Unit"
                                                                            type="text" name="unit[]"
                                                                            value="{{ $purchseOrderExtra->unit }}" readonly>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('unit') }}
                                                                        </p>
                                                                    </td>

                                                                    <td>
                                                                        <input class="form-control rate" placeholder="Rate"
                                                                            type="text" name="rate[]">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('rate') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <input name="total[]" class="form-control total"
                                                                            readonly="readonly">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('total') }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                            <tr id="hiderow">
                                                                <td colspan="5" class="text-right">
                                                                    <div class="btn-bulk" style="justify-content: center;">
                                                                    <a id="addRow" href="javascript:;" title="Add a row"
                                                                        class="btn btn-primary">Add a row</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>Sub Total</strong></td>
                                                                <td>
                                                                    <input type="text" name="subtotal" id="subtotal"
                                                                        class="form-control" readonly="readonly"
                                                                        value="{{ $purchaseOrder->subtotal }}">
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
                                                                <td><strong>Discount</strong></td>
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
                                                                                                    placeholder="Discount" value="">
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
                                                                <td><strong>Tax %</strong></td>
                                                                <td>
                                                                    <input name="taxpercent" class="form-control" id="tax" value="{{$billing->taxpercent}}" type="text">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('taxpercent') }}
                                                                    </p>
                                                                </td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
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
                                                                                                    <option value="exclusive"
                                                                                                        {{ $purchaseOrder->alltaxtype == 'exclusive' ? 'selected' : '' }}>
                                                                                                        Exclusive</option>
                                                                                                    <option value="inclusive"
                                                                                                        {{ $purchaseOrder->alltaxtype == 'inclusive' ? 'selected' : '' }}>
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
                                                                                                            {{ $purchaseOrder->alltax == $tax->percent ? 'selected' : '' }}>
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
                                                                        value="0" type="text">
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
                                                                        id="grandTotal" value="{{ $purchaseOrder->grandtotal }}"
                                                                        readonly="readonly">
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
                                                                <td><input type="text" name="vat_refundable" class="form-control"
                                                                        value="0"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td>
                                                                    <strong>IRD Sync: </strong>
                                                                </td>
                                                                <td>
                                                                    <input type="radio" name="sync_ird" value="1" checked> Yes
                                                                    <input type="radio" name="sync_ird" value="0"> No
                                                                </td>
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
                                                    <div class="col-md-6">
                                                        <label for="payment_type">Payment Type</label>
                                                        <select name="payment_type" id="payment_type" class="form-control">
                                                            <option value="paid">Paid</option>
                                                            <option value="partially_paid">Partially Paid</option>
                                                            <option value="unpaid">Unpaid</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="payment_amount">Payment Amount</label>
                                                        <input type="text" id="payment_amount" name="payment_amount"
                                                            class="form-control" placeholder="Enter Paid Amount" required>
                                                        <p class="off text-danger">Payment can't be more than that of Grand Total
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <div class="form-gorup">
                                                    <label for="remarks">Remarks: </label>
                                                    <textarea name="remarks"
                                                        class="form-control">{{ $purchaseOrder->remarks }}</textarea>
                                                    <input type="hidden" name="purchaseOrder" value="{{ $purchaseOrder->id }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('remarks') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-secondary" type="submit" name="convertToBill">Submit</button>
                                                </td>
                                            </div>
                                        </div>

                                    </form>
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

                                                                <div class="col-md-6">
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

                                                                <div class="col-md-6">
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
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script src="{{ asset('backend/dist/js/jquery.editpurchase.js') }}"></script>

    <script>
        window.onload = function() {

            function fillSelectbank_info(bank_info) {
                document.getElementById("bank_info").innerHTML = '<option value=""> --Select option-- </option>' +
                    bank_info.reduce((tmp, x) =>
                        `${tmp}<option value='${x.id}'>${x.bank_name} (${x.head_branch})</option>`, '');
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

        $(document).on('focus', '.gtaxamount', function() {
            $(this).siblings('.modal').modal({
                show: true
            });
        });
        $(document).ready(function() {
            $(".item").select2();
        });
        $(document).ready(function() {
            $(".vendor_info").select2();
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
                                `${tmp}<option value='${x.id}'>${x.bank_name} (${x.head_branch})</option>`,
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
                    payment_id: $("#payment_id").val()
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
        jQuery(document).ready(function() {
            jQuery().invoice({
                addRow: "#addRow",
                delete: ".delete",
                parentClass: ".item-row",

                item: ".item",
                unit: ".unit",
                rate: ".rate",
                qty: ".qty",
                total: ".total",
                totalQty: "#totalQty",

                subtotal: "#subtotal",
                discountpercent: "#discountpercent",
                discount: "#discount",
                tax: "#tax",
                taxamount: "#taxamount",
                shipping: "#shipping",
                grandTotal: "#grandTotal"
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('body').trigger('click');
            $('body').trigger('click');
        });
    </script>
    <script>
        window.categories = <?php echo json_encode($categories); ?>
    </script>
@endpush

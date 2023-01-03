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
                    <h1>New Sales Invoice</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('salesinvoice', 1) }}" class="global-btn">All
                            Sales Invoices</a>
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
                        <h2>Sales Invoice</h2>
                    </div>
                    <div class="card-body">
                        <div class="ibox">
                            <div class="row ibox-body">
                                <div class="col-12">
                                    <form action="{{ route('billings.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="billing_type_id" value="1">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fiscal-year">Fiscal Year<i class="text-danger">*</i>: </label>
                                                    <select name="fiscal_year_id" id="fiscal-id" class="form-control">
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
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="entry_date">Entry Date (B.S)<i
                                                                    class="text-danger">*</i>:</label>
                                                            <input type="text" name="nep_date" id="entry_date_nepali"
                                                                class="form-control" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="english_date">Entry Date (A.D)<i
                                                                    class="text-danger">*</i>:</label>
                                                            <input type="date" name="eng_date" id="english" class="form-control"
                                                                value="{{ date('Y-m-d') }}" readonly="readonly">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="ledger_no">VAT Bill No: </label>
                                                    <input type="text" class="form-control" name="ledger_no"
                                                        placeholder="VAT Bill No." >
                                                    <p class="text-danger">
                                                        {{ $errors->first('ledger_no') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="file_no">File No (Optional) : </label>
                                                <input type="text" name="file_no" class="form-control" placeholder="File No.">
                                                <p class="text-danger">
                                                    {{ $errors->first('file_no') }}
                                                </p>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="row">
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
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="client">Customer<i class="text-danger">*</i>: </label>
                                                    <div class="row">
                                                        <div class="col-md-10 pr-0">
                                                            <select name="client_id" id="client" class="form-control client_info"
                                                                required>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" data-toggle='modal' data-target='#clientadd'
                                                                data-toggle='tooltip' data-placement='top' class="btn btn-primary icon-btn"
                                                                title="Add New Client"><i class="fas fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group" id="credit-info">
                                                </div>
                                            </div>

                                            <div class="col-md-12" id="expire_message">
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-gorup">
                                                    <label for="godown">Select Godown<i class="text-danger">*</i>: </label>
                                                    <select name="godown" id="godown" class="form-control godown_info" required>
                                                        <option value="">--Select Option--</option>
                                                        @foreach ($godowns as $godown)
                                                            <option value="{{ $godown->id }}">{{ $godown->godown_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light text-center">
                                                            <tr class="item-row">
                                                                <th style="width: 28%">Particulars</th>
                                                                <th>Quantity</th>
                                                                <th>Stock</th>
                                                                <th>Unit</th>
                                                                <th>Rate</th>
                                                                <th>Discount</th>
                                                                <th>Individual Tax</th>
                                                                <th style="width: 15%">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="item-row">
                                                                <td>
                                                                    {{-- <input type="text" class="form-control item"
                                                                        placeholder="Particulars" type="text" name="particulars[]"> --}}
                                                                    <select name="particulars[]" class="form-control item"
                                                                        id="item">
                                                                        <option value="">--Select Option--</option>
                                                                        {{-- @foreach ($services as $service)
                                                                            <option value="{{ $service->id }}">
                                                                                {{ $service->service_name }}</option>
                                                                        @endforeach --}}
                                                                    </select>
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('particulars') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control qty" placeholder="Quantity"
                                                                        type="text" name="quantity[]">
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
                                                                    <input class="form-control unit" placeholder="Unit" type="text"
                                                                        name="unit[]" readonly>
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('unit') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control rate" placeholder="Rate" type="text"
                                                                        name="rate[]">
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
                                                                                    <h5 class="modal-title">Discount Details</h5>
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
                                                                                                <input type="text" name="dtamt[]"
                                                                                                    class="form-control dtamt"
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
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="taxamt[]" class="form-control taxamt"
                                                                        value="0"> <input type="text" name="itemtax[]"
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
                                                                                                <select name="taxtype[]"
                                                                                                    class="form-control taxtype">
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
                                                                                                <select name="tax[]"
                                                                                                    class="form-control taxper">
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
                                                                <td>
                                                                    <input name="total[]" class="form-control total" value="0"
                                                                        readonly="readonly">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('total') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr id="hiderow">
                                                                <td colspan="8" class="text-right">
                                                                    <div class="btn-bulk justify-content-center">
                                                                        <a id="addRow" href="javascript:;" title="Add a row"
                                                                        class="btn btn-primary addRow">Add a row</a>
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
                                                                <td>
                                                                </td>
                                                                <td>
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>Discount</strong>
                                                                <td>
                                                                    <input name="discountamount" class="form-control alldiscount"
                                                                        id="discount" value="0" type="text">
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
                                                                                    <button type="button" class="btn btn-secondary"
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
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
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
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
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
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>Refundable VAT</strong></td>
                                                                <td><input type="text" name="vat_refundable" class="form-control"
                                                                        value="0"></td>
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
                                                                    <input type="radio" name="sync_ird" value="1" checked> Yes
                                                                    <input type="radio" name="sync_ird" value="0"> No
                                                                </td>
                                                                @else
                                                                <td></td>
                                                                <td></td>
                                                                @endif
                                                                <td>
                                                                    <strong>Status<i class="text-danger">*</i>: </strong>
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
                                                        <label for="payment_type">Payment Type<i
                                                                class="text-danger">*</i>:</label>
                                                        <select name="payment_type" id="payment_type" class="form-control payment_type">
                                                            <option value="">--Select an option--</option>
                                                            <option value="partially_paid">Partially Paid</option>
                                                            <option value="unpaid">Unpaid</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="payment_amount">Payment Amount<i
                                                                class="text-danger">*</i>:</label>
                                                        <input type="text" name="payment_amount" id="payment_amount"
                                                            class="form-control payment_amount" placeholder="Enter Paid Amount" required>
                                                        <p class="off text-danger">Payment can't be equal or more than that of
                                                            Grand Total
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="form-gorup">
                                                    <label for="remarks">Remarks<i class="text-danger">*</i>: </label>
                                                    <textarea name="remarks" class="form-control"
                                                        placeholder="Remarks..." rows="5"></textarea>
                                                    <p class="text-danger">
                                                        {{ $errors->first('remarks') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-secondary submit" type="submit">Submit</button>
                                                <button type="submit" class="btn btn-secondary btn-large" name="saveandcontinue" value="1">Submit And Continue</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class='modal fade text-left' id='clientadd' tabindex='-1' role='dialog'
                                        aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document' style="max-width: 1000px;">
                                            <div class='modal-content'>
                                                <div class='modal-header text-center'>
                                                    <h2 class='modal-title' id='exampleModalLabel'>Add New Customer</h2>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <form action="" method="POST" id="client_add_form">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <p class="card-title">Customer Details</p>
                                                            </div>
                                                            <div class="card-body">

                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="client_type">Customer Type<i
                                                                                    class="text-danger">*</i></label>
                                                                            <select name="client_type" id="client_type"
                                                                                class="form-control">
                                                                                <option value="company">Company</option>
                                                                                <option value="person">Person</option>
                                                                            </select>
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('client_type') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="dealer_type_id">Dealer Type<i
                                                                                    class="text-danger">*</i></label>
                                                                            <select name="dealer_type_id" id="dealer_type_id" class="form-control">
                                                                                @foreach ($dealerTypes as $dealertype)
                                                                                <option value="{{$dealertype->id}}" {{$dealertype->is_default == 1 ? 'selected' : ''}}>{{$dealertype->title}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('dealer_type_id') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="name">Full Name<i
                                                                                    class="text-danger">*</i></label>
                                                                            <input type="text" name="name" class="form-control"
                                                                                value="{{ old('name') }}"
                                                                                placeholder="Enter Client's Name" id="name">
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('name') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="name">Customer Code (Code has to be
                                                                                unique)</label>
                                                                            <input type="text" name="client_code"
                                                                                class="form-control"
                                                                                value="{{ $client_code }}"
                                                                                placeholder="Enter Customer Code" id="client_code">
                                                                            <p class="text-danger clientcode_error hide">Code is
                                                                                already used. Use Different code.</p>
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('client_code') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="email">Customer Email</label>
                                                                            <input type="email" name="email" class="form-control"
                                                                                value="{{ old('email') }}"
                                                                                placeholder="Enter Customer Email" id="email">
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('email') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="phone">Customer Phone</label>
                                                                            <input type="text" name="phone" class="form-control"
                                                                                value="{{ old('phone') }}"
                                                                                placeholder="Enter Customer Contact no." id="phone">
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('phone') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="pan_vat">PAN No./VAT No. (Optional)</label>
                                                                            <input type="text" name="pan_vat"
                                                                                class="form-control"
                                                                                value="{{ old('pan_vat') }}"
                                                                                placeholder="Enter Company PAN or VAT No."
                                                                                id="pan_vat">
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="province">Province no.</label>
                                                                            <select name="province"
                                                                                class="form-control client_province" id="province">
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

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="district">Districts</label>
                                                                            <select name="district" class="form-control"
                                                                                id="client_district">
                                                                                <option value="">--Select a province first--
                                                                                </option>
                                                                            </select>
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('district') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="local_address">Customer Local
                                                                                Address</label>
                                                                            <input type="text" name="local_address"
                                                                                class="form-control"
                                                                                value="{{ old('local_address') }}"
                                                                                placeholder="Customer Local Address"
                                                                                id="local_address">
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('local_address') }}
                                                                            </p>
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
                                                                        <div class="form-group">
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
                                                                        <div class="form-group">
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
                                                                        <div class="form-group">
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
                                                                        <div class="form-group">
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
                                                        <div class="form-group text-center">
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

                                    {{-- Credit Info --}}
                                    <div class="modal fade" id="mediumModal" tabindex="-1" role="dialog"
                                        aria-labelledby="mediumModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3>Credit Information</h3>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" id="mediumBody">
                                                    <div id="filler">
                                                        <!-- the result to be displayed apply here -->
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

            function fillSelectClient(clients) {
                document.getElementById("client").innerHTML = '<option value=""> --Select an option-- </option>' +
                    clients.reduce((tmp, x) => `${tmp}<option value='${x.id}' data-dealer_percent = '${x.dealertype.percent}'>${x.name}</option>`, '');
            }

            function fetchclients() {
                $.ajax({
                    url: "{{ route('apiclient') }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var clients = response;
                        fillSelectClient(clients);
                    }
                });
            }
            fetchclients();
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

            $(document).ready(function() {
                $(".godown_info").select2();
            });
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

                godown: "#godown",
                client:"#client",
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
        $('.client_province').change(function() {
            var province_no = $(this).children("option:selected").val();

            function fillClientSelect(districts) {
                document.getElementById("client_district").innerHTML =
                    districts.reduce((tmp, x) =>
                        `${tmp}<option value='${x.id}'>${x.dist_name}</option>`, '');
            }

            function fetchClientRecords(province_no) {
                var uri = "{{ route('getdistricts', ':no') }}";
                uri = uri.replace(':no', province_no);
                $.ajax({
                    url: uri,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var districts = response;
                        fillClientSelect(districts);
                    }
                });
            }
            fetchClientRecords(province_no);
        })
        $(function() {
            $("#client_add_form").submit(function(event) {
                var clientData = {
                    client_type: $("#client_type").val(),
                    dealer_type_id: $("#dealer_type_id"),
                    name: $("#name").val(),
                    client_code: $("#client_code").val(),
                    email: $("#email").val(),
                    phone: $("#phone").val(),
                    local_address: $("#local_address").val(),
                    province: $("#province").val(),
                    district: $("#district").val(),
                    pan_vat: $("#pan_vat").val(),
                    concerned_name: $("#concerned_name").val(),
                    concerned_email: $("#concerned_email").val(),
                    concerned_phone: $("#concerned_phone").val(),
                    designation: $("#designation").val(),
                    opening_balance: $('#client_add_form').find(".opening_balance").val(),
                    behaviour: $('#client_add_form').find(".behaviour").find(':selected').val(),
                };
                $.ajax({
                    type: "POST",
                    url: "{{ route('post.apiclient') }}",
                    data: clientData,
                    dataType: "json",
                    encode: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {

                    function fillSelectClients(clients) {
                        document.getElementById("client").innerHTML =
                            '<option value=""> --Select an option-- </option>' +
                            clients.reduce((tmp, x) =>
                                `${tmp}<option value='${x.id}' data-dealer_percent = '${x.dealertype.percent}'>${x.name}</option>`, '');
                    }

                    function fetchclients() {
                        $.ajax({
                            url: "{{ route('apiclient') }}",
                            type: 'get',
                            dataType: 'json',
                            success: function(response) {
                                var clients = response;
                                fillSelectClients(clients);
                            }
                        });
                    }
                    fetchclients();
                    $("#client_add_form").html(
                        '<div class="alert alert-success">Successfully added.</div>'
                    );
                });
                event.preventDefault();
            });
            var clientcodes = @php echo json_encode($allclientcodes) @endphp;
            $("#client_code").change(function() {
                var productcategoryval = $(this).val();
                if ($.inArray(productcategoryval, clientcodes) != -1) {
                    $('.clientcode_error').addClass('show');
                    $('.clientcode_error').removeClass('hides');
                } else {
                    $('.clientcode_error').removeClass('show');
                    $('.clientcode_error').addClass('hide');
                }
            })
        });
    </script>

    <script>
        $('#godown').change(function() {
            var selgodown = $('#godown').find(":selected").val();
            var warehouses = @php echo  json_encode($godowns); @endphp;
            var warehousecount = warehouses.length;
            // console.log(warehouses);
            function wareproducts() {

                for (let i = 0; i < warehousecount; i++) {
                    if (warehouses[i].id == selgodown) {
                        var godownpros = warehouses[i].godownproduct;
                        var gdpcount = godownpros.length;

                        var prodoptions = '<option>--Select Option--</option>';
                        for (let s = 0; s < gdpcount; s++) {
                            var stock = godownpros[s].stock;
                            var proname = godownpros[s].product.product_name;
                            var rate = godownpros[s].product.product_price;
                            var primary_unit = godownpros[s].product.primary_unit;
                            var procode = godownpros[s].product.product_code;
                            var proid = godownpros[s].product_id;
                            prodoptions += `<option value="${proid}"
                                            data-rate="${rate}"
                                            data-stock="${stock}"
                                            data-priunit="${primary_unit}">
                                            ${proname}(${procode})
                                        </option>`;
                        }

                    }
                }
                return prodoptions;
            }
            $('.item').html(wareproducts());
            return 1;
        })
    </script>

    <script>
        $(function() {
            $('.client_info').change(function() {
                var client_id = $(this).children("option:selected").val();

                function fillAnotherSelect() {
                    document.getElementById("credit-info").innerHTML = `<label for="">Credit Info:</label>
                                                                <h5>No credit provided yet.</h5>`;
                }

                function getInfoData(info){
                    var table = '';
                    for(let a=0; a<info['credited_bills']; a++){
                        table += `<tr>
                                    <td>
                                        ${info['credits'][a]['invoice_id']}
                                    </td>

                                    <td>
                                        ${info['credits'][a]['bill_nep_date']}
                                    </td>
                                    <td>
                                        ${info['credits'][a]['bill_expire_nep_date']}
                                    </td>
                                    <td>Bill count: ${info['credits'][a]['credited_bills']}</td>
                                    <td>Rs. ${info['credits'][a]['credited_amount']}</td>
                                </tr>`;
                    }

                    return table;
                }

                function fillSelect(info) {
                    document.getElementById("credit-info").innerHTML =
                        `<a href="#" class="btn btn-primary mt-4" data-toggle="modal" id="mediumButton" data-target="#mediumModal">View Credit Info</a>`;

                    if (info.credited_bills >= info.allocated_bills) {
                        document.getElementById("godown").setAttribute("disabled", true);
                        $('.submit').addClass("disabled");
                        $('.addRow').addClass("disabled");
                        document.getElementById('expire_message').innerHTML =
                            `<p class='text-danger'>Credit Bills limit exceeded. Cannot provide more credit.</p>`;
                        document.getElementById("filler").innerHTML = `<h5><b>Due Date</b>: ${info.bill_nep_date}</h5>
                                                                    <h5><b>Credit Bills</b>: ${info.credited_bills} Bills</h5>
                                                                    <h5><b>Total credited Amount</b>: Rs. ${info.credited_amount}</h5>
                                                                `;
                    } else if (info.credited_amount >= info.allocated_amount) {
                        document.getElementById("godown").setAttribute("disabled", true);
                        $('.submit').addClass("disabled");
                        $('.addRow').addClass("disabled");
                        document.getElementById('expire_message').innerHTML =
                            `<p class='text-danger'>Credit Amount limit exceeded. Cannot provide more credit</p>`;
                        document.getElementById("filler").innerHTML = `<h5><b>Due Date</b>: ${info.bill_nep_date}</h5>
                                                                    <h5><b>Credit Bills</b>: ${info.credited_bills} Bills</h5>
                                                                    <h5><b>Total credited Amount</b>: Rs. ${info.credited_amount}</h5>
                                                                `;
                    } else if (today >= bill_date) {
                        document.getElementById("godown").setAttribute("disabled", true);
                        $('.submit').addClass("disabled");
                        $('.addRow').addClass("disabled");
                        document.getElementById('expire_message').innerHTML =
                            `<p class='text-danger'>Credit days exceeded. Cannot provide more credit.</p>`;
                        document.getElementById("filler").innerHTML = `<h5><b>Due Date</b>: ${info.bill_nep_date}</h5>
                                                                    <h5><b>Credit Bills</b>: ${info.credited_bills} Bills</h5>
                                                                    <h5><b>Total credited Amount</b>: Rs. ${info.credited_amount}</h5>
                                                                `;
                    } else {
                        document.getElementById("filler").innerHTML = `<h5><b>Due Date</b>: ${info.bill_nep_date}</h5>
                                                                    <h5><b>Credit Bills</b>: ${info.credited_bills} Bills</h5>
                                                                    <h5><b>Total credited Amount</b>: Rs. ${info.credited_amount}</h5>
                                                                `;
                    }
                }

                function fetchinfo(client_id) {
                    var today = new Date();
                    var uri = "{{ route('getCreditInfo', ':id') }}"
                    uri = uri.replace(":id", client_id);
                    $.ajax({
                        url: uri,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var info = response;


                            if (info['credited_bills'] == 1 && info['credited_total_amount'] == 0) {
                                fillAnotherSelect();
                            }
                            else if(info['credits'][0]['allocated_amount'] < info['credited_total_amount']) {
                                document.getElementById("godown").setAttribute("disabled", true);
                                $('.submit').addClass("disabled");
                                $('.addRow').addClass("disabled");
                                document.getElementById('expire_message').innerHTML = `<p class='text-danger'>Credit Amount limit exceeded. Cannot provide more credit.</p>`;
                                fillSelect(info);
                            }
                            else if(info['credits'][0]['allocated_bills'] <= info['credited_bills']) {
                                document.getElementById("godown").setAttribute("disabled", true);
                                $('.submit').addClass("disabled");
                                $('.addRow').addClass("disabled");
                                document.getElementById('expire_message').innerHTML = `<p class='text-danger'>Number of bills limit exceeded. Cannot provide more credit.</p>`;
                                fillSelect(info);
                            }
                            else {
                                for(let a=0; a < info['credited_bills']; a++)
                                {
                                    var expire_date = new Date(info['credits'][a]['bill_expire_eng_date']);

                                    if (today > expire_date) {
                                        document.getElementById("godown").setAttribute("disabled", true);
                                        $('.submit').addClass("disabled");
                                        $('.addRow').addClass("disabled");
                                        document.getElementById('expire_message').innerHTML = `<p class='text-danger'>Credit Bill due date has expired on ${info['credits'][a]['bill_expire_nep_date']}. Cannot provide more credit.</p>`;
                                        fillSelect(info);
                                    }
                                }
                                fillSelect(info);
                            }
                        }
                    });
                }
                fetchinfo(client_id);
            })
        });
    </script>

    <script>
        $('#payment_amount').change(function() {
            var payment_amount = $(this).val();
            var grandtotal = $("#grandTotal").val();

            if (parseFloat(payment_amount) > parseFloat(grandtotal) - 1) {
                $(this).parent().find('.text-danger').removeClass('off');
                $('.submit').addClass("disabled");
            } else {
                $(this).parent().find('.text-danger').addClass('off');
                $('.submit').removeClass("disabled");
            }
        });
    </script>

    <script>
        $(function(){
            $('.payment_type').change(function(){
                var payment_type = $(this).find(':selected').val();
                var payment_amount = $('.payment_amount');
                var grandtotal = $('#grandTotal').val();
                if(payment_type == 'partially_paid'){
                    payment_amount.attr('readonly', false);
                }else if(payment_type == 'paid'){
                    payment_amount.attr('readonly', 'readonly');
                    payment_amount.val(grandtotal);
                }else if(payment_type == 'unpaid'){
                    payment_amount.attr('readonly', 'readonly');
                    payment_amount.val('0');
                }
            })
        });
    </script>
    <script>
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
    </script>

    <script>
        window.warehouses = @php echo  json_encode($godowns); @endphp;
    </script>
    <script>
        window.taxes = <?php echo json_encode($taxes); ?>;
        window.categories = <?php echo json_encode($categories); ?>
    </script>
@endpush

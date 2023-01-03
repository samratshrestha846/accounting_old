@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
    <style>
        .coloradd{
             color: red;
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
                    <h1 class="m-0">New Service Purchase Bills </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('service_sales.index') }}" class="global-btn">View Purchase Bills</a>
                        <a href="#" class="global-btn">Unapproved Purchase Bills</a>
                        <a href="#" class="global-btn">Cancelled Purchase Bills</a>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}

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
                        <h2>Purchase Bills Info</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <form action="{{ route('service_sales.store') }}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <input type="hidden" name="billing_type_id" value="2">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="fiscal-year">Fiscal Year<i class="text-danger">*</i>:
                                                </label>
                                                <select name="fiscal_year_id" id="fiscal-id" class="form-control">
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

                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="entry_date">Entry Date (B.S)<i
                                                                class="text-danger">*</i>:</label>
                                                        <input type="text" name="nep_date" id="entry_date_nepali"
                                                            class="form-control" value="{{ $nepali_today }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="english_date">Entry Date (A.D)<i
                                                                class="text-danger">*</i>:</label>
                                                        <input type="date" name="eng_date" id="english"
                                                            class="form-control" value="{{ date('Y-m-d') }}"
                                                            readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="party_name">Party Name: </label>
                                                <div class="v-add">
                                                    <select name="vendor_id" id="vendor" class="form-control vendor_info" required>
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

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="pan_vat">Supplier Pan/Vat</label>
                                                <input type="text" class="form-control panVat" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ledger_no">VAT Bill No<i class="text-danger">*</i>: </label>
                                                <input type="text" class="form-control" name="ledger_no"
                                                    placeholder="VAT Bill No." required>
                                                <p class="text-danger">
                                                    {{ $errors->first('ledger_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="file_no">File No (Optional): </label>
                                            <input type="text" name="file_no" class="form-control" placeholder="File No.">
                                            <p class="text-danger">
                                                {{ $errors->first('file_no') }}
                                            </p>
                                        </div>

                                        {{-- <div class="col-md-12">
                                            <div class="row">

                                            </div>
                                        </div> --}}
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-center" id="myTable">
                                                    <thead class="thead-light">
                                                        <tr class="item-row">
                                                            <th style="width: 30%">Particulars</th>
                                                            <th>Quantity</th>
                                                            <th>Unit</th>
                                                            <th>Rate</th>
                                                            <th class="discount">Discount</th>
                                                            <th class="tax">Individual Tax</th>
                                                            <th style="width: 20%">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="item-row" id="tr_row_1">
                                                            <td>
                                                                <select name="particulars[]" class="form-control item" data-id="1"
                                                                    id="item_1" onchange="showrate(1)" required>
                                                                    <option value=""> --Select an option-- </option>
                                                                    <option value="secondoption" class="coloradd" style="color:red"> + Add new Service </option>
                                                                </select>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('particulars') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input class="form-control qty" placeholder="Quantity"
                                                                    type="text" name="quantity[]" value="">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('quantity') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input class="form-control unit" placeholder="Unit"
                                                                    type="text" name="unit[]" value="">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('unit') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input class="form-control rate" placeholder="Rate"
                                                                    type="text" name="rate[]" id="rate_1">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('rate') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input class="form-control discountamt" placeholder="Discount per unit"
                                                                    type="text" name="discountamt[]" value="0">
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
                                                                                                placeholder="Discount" value="0">
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
                                                                <input type="text" name="taxamt[]" class="form-control taxamt" value="0">
                                                                <input type="text" name="itemtax[]" class="form-control itemtax" value="0"
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
                                                                                            <label for="taxtype" class="txtleft">Tax
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
                                                                                                    <option value="{{ $tax->percent }}">
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
                                                                <input name="total[]" class="form-control total" value="0">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('total') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr id="hiderow">
                                                            <td colspan="7" class="text-right">
                                                                <div class="btn-bulk justify-content-end">
                                                                    <a id="addRow" href="javascript:;" title="Add a row"
                                                                        class="btn btn-primary">+Add</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Total Quantity: </strong></td>
                                                            <td><span id="totalQty" style="color: red; font-weight: bold;font-size:12px;">0</span> Units
                                                            </td>
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
                                                                                                placeholder="Discount" value="0">
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

                                                          {{-- //as.k --}}
                                                          <tr>
                                                            <td style="border:none;"></td>
                                                            <td style="border:none;"></td>
                                                            <td style="border:none;"></td>
                                                            <td style="border:none;"></td>
                                                            <td style="border:none;"></td>
                                                            <td><strong>Service Charge</strong>
                                                            <td>
                                                                <input name="service_charge" class="form-control servicecharge"
                                                                    id="service_charge" value="0" type="text">
                                                                <div class="modal fade" tabindex="-1" role="dialog"
                                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">Service Charge
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
                                                                                                class="txtleft">Charge
                                                                                                Type:</label>
                                                                                        </div>
                                                                                        <div class="col-9">
                                                                                            <select name="alldiscounttypeservice"
                                                                                                class="form-control alldiscounttypeservice">
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
                                                                                            <input type="text" name="servicediscountamount"
                                                                                                class="form-control alldtamtservice"
                                                                                                placeholder="Discount" value="0">
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
                                                                {{-- <p class="text-danger">
                                                                    {{ $errors->first('discountamount') }}
                                                                </p> --}}
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
                                                            <td><strong>Tax Amount</strong></td>
                                                            <td>
                                                                <input type="text" name="taxamount" id="taxamount"
                                                                    class="form-control off" value="0">
                                                                <input type="text" name="taxamount" class="gtaxamount form-control on"
                                                                    value="0">
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
                                                            <td style="border:none;"></td>
                                                            <td style="border:none;"></td>
                                                            <td><strong>Shipping</strong></td>
                                                            <td>
                                                                <input name="shipping" class="form-control" id="shipping" value="0"
                                                                    type="text">
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
                                                            <td><strong>Refundable VAT</strong></td>
                                                            <td><input type="text" name="vat_refundable" class="form-control"
                                                                    value="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            @if ($ird_sync == 1)
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
                                                        <p class="off text-danger">Payment can't be more than that of Grand
                                                            Total
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
                                                                <div class="col-md-10 pr-0">
                                                                    <select name="online_portal" class="form-control online_portal_class" id="online_portal">
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button type="button" data-toggle='modal'
                                                                        data-target='#onlinePortalAdd' data-toggle='tooltip'
                                                                        data-placement='top'
                                                                        class="btn btn-primary icon-btn btn-sm"
                                                                        title="Add New Portal"><i
                                                                            class="fas fa-plus"></i></button>
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
                                                                    <select name="bank_id" class="form-control bank_info_class"
                                                                        id="bank_info">
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button type="button" data-toggle='modal'
                                                                        data-target='#bankinfoadd' data-toggle='tooltip'
                                                                        data-placement='top'
                                                                        class="btn btn-primary icon-btn btn-sm"
                                                                        title="Add New Bank"><i
                                                                            class="fas fa-plus"></i></button>
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
                                                <textarea name="remarks" class="form-control" placeholder="Remarks.."
                                                    rows="5"></textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('remarks') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-end">
                                            <button class="btn btn-primary submit ml-auto" type="submit">Submit</button>
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

                                <div class='modal fade text-left' id='serviceinfoadd' tabindex='-1' role='dialog'
                                    aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document' style="max-width: 1000px;">
                                        <div class='modal-content'>
                                            <div class='modal-header text-center'>
                                                <h2 class='modal-title' id='exampleModalLabel'>Add New Service</h2>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                <form action="" method="POST" id="service_add_form">
                                                    @csrf
                                                    @method("POST")
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="service_name">Service Name<i
                                                                                        class="text-danger">*</i>
                                                                                    :</label>
                                                                                <input type="text" id="service_name"
                                                                                    name="service_name"
                                                                                    class="form-control"
                                                                                    value="{{ old('service_name') }}"
                                                                                    placeholder="Service Name">
                                                                                <p class="text-danger">
                                                                                    {{ $errors->first('service_name') }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="item_no" id="item_no" value="">
                                                                        <div class="col-md-6"></div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="service_code">Service Code (Code
                                                                                    must be unique)<i
                                                                                        class="text-danger">*</i>
                                                                                    :</label>
                                                                                <input type="text" id="service_code"
                                                                                    name="service_code"
                                                                                    class="form-control"
                                                                                    value="{{ old('service_code', $service_code) }}"
                                                                                    placeholder="Service Code">
                                                                                <p
                                                                                    class="text-danger service_code_error hide">
                                                                                    Code is already used. Use
                                                                                    Different code.</p>
                                                                                <p class="text-danger">
                                                                                    {{ $errors->first('service_code') }}
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="category">Service Category<i
                                                                                        class="text-danger">*</i>
                                                                                    :</label>
                                                                                <select name="category"
                                                                                    class="form-control category"
                                                                                    id="category_service">
                                                                                    <option value="">--Select a category--
                                                                                    </option>
                                                                                    @foreach ($categories as $category)
                                                                                        <option
                                                                                            value="{{ $category->id }}"
                                                                                            {{ old('category') == $category->id ? 'selected' : '' }}>
                                                                                            {{ $category->category_name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <p class="text-danger">
                                                                                    {{ $errors->first('category') }}
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="cost_price">Cost Price (Rs.)<i
                                                                                        class="text-danger">*</i>
                                                                                    :</label>
                                                                                <input type="number" step="any"
                                                                                    name="cost_price"
                                                                                    class="form-control" id="cost_price"
                                                                                    value="{{ old('cost_price') }}"
                                                                                    placeholder="Cost Price in Rs.">
                                                                                <p class="text-danger">
                                                                                    {{ $errors->first('cost_price') }}
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="selling_price">Selling Price
                                                                                    (Rs.)<i class="text-danger">*</i>
                                                                                    :</label>
                                                                                <input type="number" step="any"
                                                                                    name="selling_price"
                                                                                    class="form-control"
                                                                                    id="selling_price"
                                                                                    value="{{ old('selling_price') }}"
                                                                                    placeholder="Selling Price in Rs.">
                                                                                <p class="text-danger">
                                                                                    {{ $errors->first('selling_price') }}
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6 mt-3">
                                                                            <div class="form-group">
                                                                                <label for="Status">Status: </label>
                                                                                <span
                                                                                    style="margin-left: 5px; font-size: 15px;">
                                                                                    Disable </span>
                                                                                <label class="switch pt-0">
                                                                                    <input type="checkbox" name="status"
                                                                                        id="status" value="1"
                                                                                        {{ old('status') == 1 ? 'checked' : '' }}>
                                                                                    <span class="slider round"></span>
                                                                                </label>
                                                                                <span
                                                                                    style="margin-left: 5px; font-size: 15px;">Enable</span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12 mb-3 mt-3 text-center">
                                                                            <h3>Service Details For invoice</h3>
                                                                            <hr>
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <textarea name="description"
                                                                                    class="form-control" cols="30"
                                                                                    rows="10" id="description"
                                                                                    placeholder="Something about service..."
                                                                                    value="">{{ old('description') }}</textarea>
                                                                                <p class="text-danger">
                                                                                    {{ $errors->first('description') }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <hr>
                                                                            <h2>Ledger Details</h2>
                                                                            <div class="row">
                                                                                <div class="col-md-3">
                                                                                    <div class="form-group">
                                                                                        <label for="opening_balance">Opening Balance</label>
                                                                                        <input type="number" name="opening_balance" min="" class="form-control opening_balance" id="opening_balance" value="{{ @old('opening_balance') ?? 0 }}" step=".01">
                                                                                        <p class="text-danger">
                                                                                            {{ $errors->first('opening_balance') }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="form-group">
                                                                                        <label for="behaviour">Opening Balance behaviour (Optional) </label>
                                                                                        <select name="behaviour" class="form-control behaviour" id="behaviour">
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
        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection
@push('scripts')

    <script src="{{ asset('backend/dist/js/jquery.servicesales.js') }}"></script>
    <script src="{{ asset('backend/dist/js/mousetrap/servicepurchase.js') }}"></script>
    <script type="text/javascript">
        var mainInput = document.getElementById("entry_date_nepali");

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

        $(document).ready(function() {
            $(".item").select2({
                templateResult: function (data, container) {
                if (data.element) {
                    $(container).addClass($(data.element).attr("class"));
                }
                return data.text;
                }
            });

            $('.item').each(function(){
                $(this).css('color','red');
            })
        });


        $(function() {
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

            $(document).on('focus', '.servicecharge', function() {
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
                $(".category").select2();
            });
        });

        // Fetch online portals
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

        // Fetch vendor
        function fillSelectSuppliers(suppliers) {
                document.getElementById("vendor").innerHTML = '<option value=""> --Select an option-- </option>' +
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

        //fetch service categories
        function fetchServiceCategories() {
            $.ajax({
                url: "{{ route('apiServiceCategories') }}",
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var serviceCategories = response;
                    fetchServiceFromCategory(serviceCategories);
                }
            });
        }

        function fetchServiceFromCategory(serviceCategories) {

            var cateoptions = "";
            for (let i = 0; i < serviceCategories.length; i++) {
                var serviceUri = "{{ route('apiServiceFromCategories', ':id') }}";
                serviceUri = serviceUri.replace(':id', serviceCategories[i].id);
                $.ajax({
                    url: serviceUri,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var services = response;
                        var service_options = "";
                        for (let j = 0; j < services.length; j++) {
                            service_options += `<option value="${services[j].id}">
                                                    ${services[j].service_name} (${services[j].service_code})
                                                </option>`;
                        }
                        cateoptions += `<option value='' class='title' disabled>${serviceCategories[i].category_name}</option>
                                            ${service_options}
                                        `;

                        document.getElementById('item_1').innerHTML =
                            `<option value="">--Select an option--</option>
                            <option value="secondoption" class="coloradd"> + Add new Service </option>`+
                            cateoptions;


                    }
                })
            }
        }

        // Fetch clients
        // function fetchclients() {
        //     $.ajax({
        //         url: "{{ route('apiclient') }}",
        //         type: 'get',
        //         dataType: 'json',
        //         success: function(response) {
        //             var clients = response;
        //             fillSelectClient(clients);
        //         }
        //     });
        // }

        // Fetch Bank Infos
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

        // Fill client information on fields
        function fillSelectClient(clients) {
            document.getElementById("client").innerHTML = '<option value=""> --Select an option-- </option>' +
                clients.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.name}</option>`, '');
        }

        // Fill Bank information on form
        function fillSelectbank_info(bank_info) {
            document.getElementById("bank_info").innerHTML = '<option value=""> --Select option-- </option>' +
                bank_info.reduce((tmp, x) =>
                    `${tmp}<option value='${x.id}'>${x.bank_name} (${x.head_branch})</option>`, '');
        }

        // Fill online portal info
        function fillOnlinePortal(online_portal) {
            document.getElementById("online_portal").innerHTML = '<option value=""> --Select option-- </option>' +
                online_portal.reduce((tmp, x) =>
                    `${tmp}<option value='${x.id}'>${x.name}</option>`, '');
        }

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

        window.onload = function() {
            fetchvendors();
            fetchbanks();
            fetchOnlinePortals();
            fetchServiceCategories();
        }

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
                fetchRecords(province_no);
            })
        });

        // $('.client_province').change(function() {
        //     var province_no = $(this).children("option:selected").val();
        //     fetchClientRecords(province_no);
        // })

        // function fillClientSelect(districts) {
        //     document.getElementById("client_district").innerHTML =
        //         districts.reduce((tmp, x) =>
        //             `${tmp}<option value='${x.id}'>${x.dist_name}</option>`, '');
        // }

        // function fetchClientRecords(province_no) {
        //     var uri = "{{ route('getdistricts', ':no') }}";
        //     uri = uri.replace(':no', province_no);
        //     $.ajax({
        //         url: uri,
        //         type: 'get',
        //         dataType: 'json',
        //         success: function(response) {
        //             var districts = response;
        //             fillClientSelect(districts);
        //         }
        //     });
        // }

        // $(function() {
        //     $("#client_add_form").submit(function(event) {
        //         var clientData = {
        //             client_type: $("#client_type").val(),
        //             dealer_type_id: $('#dealer_type_id').val(),
        //             name: $("#name").val(),
        //             client_code: $("#client_code").val(),
        //             email: $("#email").val(),
        //             phone: $("#phone").val(),
        //             local_address: $("#local_address").val(),
        //             province: $("#province").val(),
        //             district: $("#district").val(),
        //             pan_vat: $("#pan_vat").val(),
        //             concerned_name: $("#concerned_name").val(),
        //             concerned_email: $("#concerned_email").val(),
        //             concerned_phone: $("#concerned_phone").val(),
        //             designation: $("#designation").val()
        //         };
        //         $.ajax({
        //             type: "POST",
        //             url: "{{ route('post.apiclient') }}",
        //             data: clientData,
        //             dataType: "json",
        //             encode: true,
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         }).done(function(data) {
        //             fetchclients();
        //             $("#client_add_form").html(
        //                 '<div class="alert alert-success">Successfully added.</div>'
        //             );
        //         });
        //         event.preventDefault();
        //     });

        // });

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
                    $('.paymentMethod').hide();
                    $('.creditDate').show();
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
                // unit: ".unit",
                // product_image: ".product_image",
                rate: ".rate",
                qty: ".qty",
                discountamt: "input.discountamt",
                serviceamt: "input.servicecharge",
                discounttype: "select.discounttype",
                dtamt: "input.dtamt",
                taxamt: ".taxamt",
                taxtype: ".taxtype",
                taxper: ".taxper",
                itemtax: ".itemtax",
                total: ".total",
                totalQty: "#totalQty",

                subtotal: "#subtotal",
                discountpercent: "#discountpercent",
                alldiscounttype: '.alldiscounttype',
                alldtamt: '.alldtamt',
                alldiscounttypeservice:'.alldiscounttypeservice',
                alldtamtservice: ".alldtamtservice",
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
    </script>
    {{-- asish --}}
     <script>
        $(document).on('change','select[name="particulars[]"]',function(){
            if($(this).val() == 'secondoption'){
                $('#serviceinfoadd').modal("show");
            }
        })
    </script>
    {{-- end ashish --}}
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

        function showrate(number)
        {
            var value = document.getElementById("item_" + number).value;

            if (value != "secondoption")
            {

                // document.getElementById("item_no").value = number;
                // $('#serviceinfoadd').modal("show");


                function fetchServices(value) {
                    var uri = "{{ route('apiService', ':no') }}";
                    uri = uri.replace(':no', value);

                    // var uri = "{{ route('apiServices') }}";
                    $.ajax({
                        url: uri,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            document.getElementById("rate_" + number).value = response.cost_price;
                        }
                    });
                }
                fetchServices(value);
             }
        }

        function fetchServiceCategoriesAfterAdd(number)
        {
            $.ajax({
                url: "{{ route('apiServiceCategories') }}",
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var serviceCategories = response;
                    fetchServiceFromCategoryAfterAdd(serviceCategories, number);
                }
            });
        }

        function fetchServiceFromCategoryAfterAdd(serviceCategories, number) {
            var cateoptions = "";
            for (let i = 0; i < serviceCategories.length; i++) {
                var serviceUri = "{{ route('apiServiceFromCategories', ':id') }}";
                serviceUri = serviceUri.replace(':id', serviceCategories[i].id);
                $.ajax({
                    url: serviceUri,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var services = response;
                        var service_options = "";
                        for (let j = 0; j < services.length; j++) {
                            service_options += `<option value="${services[j].id}">
                                                    ${services[j].service_name} (${services[j].service_code})
                                                </option>`;
                        }
                        cateoptions += `<option value='' class='title' disabled>${serviceCategories[i].category_name}</option>
                                            ${service_options}
                                        `;

                        var html = `<option value="">--Select an option--</option>
                        <option value="secondoption" class="coloradd"> + Add new Service </option>`+
                            cateoptions;
                            $('select[name="particular[]"]').html(html);
                    }
                })
            }
        }
    </script>
    <script>
        $(function() {
            var servicecodes = @php echo json_encode($allservicecodes) @endphp;
            $("#service_code").change(function() {
                var servicecodeval = $(this).val();
                if ($.inArray(servicecodeval, servicecodes) != -1) {
                    $('.service_code_error').addClass('show');
                    $('.service_code_error').removeClass('hides');
                } else {
                    $('.service_code_error').removeClass('show');
                    $('.service_code_error').addClass('hide');

                }
            })
        })
    </script>

    <script>
         $(function()
            {
                $("#service_add_form").submit(function(event) {
                    var number = $("#item_no").val();
                    var serviceData = {
                        service_name: $("#service_name").val(),
                        service_code: $("#service_code").val(),
                        category: $("#category_service").val(),
                        cost_price: $("#cost_price").val(),
                        selling_price: $("#selling_price").val(),
                        description: $("#description").val(),
                        status: $("#status").val(),
                        opening_balance: $('#service_add_form').find(".opening_balance").val(),
                        behaviour: $('#service_add_form').find(".behaviour").find(':selected').val(),
                    };

                    $.ajax({
                        type: "POST",
                        url: "{{ route('storeServices') }}",
                        data: serviceData,
                        dataType: "json",
                        encode: true,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function(data) {
                        fetchServiceCategoriesAfterAdd(number);
                        var option = '<option value='+data.id+' selected="selected">'+data.service_name+'('+data.service_code+')</option>';
                        $('select[name="particulars[]"]').last().append(option);

                        // $("#service_add_form").html(
                        //     '<div class="alert alert-success">Successfully added.</div>'
                        // );
                        $('#serviceinfoadd').find('form')[0].reset();

                        $("#serviceinfoadd").modal('hide');

                        $('select[name="particulars[]"]').trigger('change');
                        $(".category").select2();
                    });
                    event.preventDefault();
                });
            });
    </script>

<script>
    // $(document).on('change', '.item', function() {
    //     let rate = $(this).find(':selected').data('rate');
    //     let ratebox = $(this).closest('tr').find('.rate');
    //     let clientratepercenttype = typeof $('#client').find(":selected").data("dealer_percent");
    //     if (clientratepercenttype == "undefined") {
    //         var newrate = rate;
    //     } else {
    //         clientratepercent = $('#client').find(":selected").data("dealer_percent");
    //         var newrate = rate - (rate * clientratepercent) / 100;
    //     }
    //     ratebox.val(newrate);
    // })

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
     $(document).on('change','.vendor_info',function(){
            var url = "{{route('vendors.show','id')}}";

            if($(this).val() == ""){
                $('.panVat').val(" ");
                return;
            }
            var url = url.replace("id", $(this).val());
            $.get(url,function(response){
                $('.panVat').val(response);
            })
        })
</script>

    <script>
        window.taxes = @php echo json_encode($taxes); @endphp;
        window.categories = @php echo json_encode($categories); @endphp;
    </script>
@endpush

@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
    <style>
        .icon-rtl {
            padding-right: 20px;
            background: url('/uploads/image.jpg') no-repeat right;
            background-size: 14px;
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
                    <h1>Create Sales Bill</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('billings.report', $billing_type_id) }}" class="global-btn">View All
                            Sales</a>
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ route('billings.salesstore') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="billing_type_id" value="1">
                                    <div class="row">
                                        <div class="col-md-2">
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
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="entry_date">Entry Date (B.S)<i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" name="nep_date" id="entry_date_nepali"
                                                    class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="english_date">Entry Date (A.D)<i
                                                        class="text-danger">*</i>:</label>
                                                <input type="date" name="eng_date" id="english"
                                                    class="form-control" value="{{ date('Y-m-d') }}"
                                                    readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="client">Customer<i class="text-danger">*</i>: </label>
                                                <div class="row">
                                                    <div class="col-md-9 pr-0">
                                                        <select name="client_id" id="client"
                                                            class="form-control client_info" required>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3" style="padding-left:7px;">
                                                        <button type="button" data-toggle='modal' data-target='#clientadd'
                                                            data-toggle='tooltip' data-placement='top'
                                                            class="btn btn-primary icon-btn" title="Add New Client"><i
                                                                class="fas fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="pan_vat">Customer Pan/Vat</label>
                                                <input type="text" class="form-control panVat" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="total_credit">Total Receivable Credit</label>
                                                <input type="text" class="form-control total_credit" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="total_pay_credit">Total Payable Credit</label>
                                                <input type="text" class="form-control total_pay_credit" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="ledger_no">PAT/VAT Bill No<i class="text-danger">*</i>: </label>
                                                <input type="text" class="form-control" name="ledger_no"
                                                    placeholder="VAT Bill No.">
                                                <p class="text-danger">
                                                    {{ $errors->first('ledger_no') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="file_no">File No (Optional): </label>
                                            <input type="text" name="file_no" class="form-control" placeholder="File No.">
                                            <p class="text-danger">
                                                {{ $errors->first('file_no') }}
                                            </p>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-gorup">
                                                <label for="godown">Select Godown<i class="text-danger">*</i>:
                                                </label>
                                                <select name="godown" id="godown" class="form-control godown_info"
                                                    required>
                                                    <option value="">--Select Option--</option>
                                                    @foreach ($godowns as $godown)
                                                        <option value="{{ $godown->id }}"
                                                            {{ $godown->is_default == 1 ? 'selected' : '' }}>
                                                            {{ $godown->godown_name }}
                                                        </option>
                                                    @endforeach
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
                                        <div class="col-md-12 mt-2">
                                            <div class="row" style="align-items:flex-end;">
                                                @include('backend.billings._includes.barcodereader')
                                                <div class="text-right col-md-8">
                                                    <input type="hidden" name="selected_filter_option" id="SelectedFilter">
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
                                                                            value='stock' checked />
                                                                        Stock
                                                                    </label>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#">
                                                                    <label>
                                                                        <input name='filter_cols[]' type="checkbox"
                                                                            onclick="showHide(this)" class="option justone"
                                                                            value='unit' checked />
                                                                        Unit
                                                                    </label>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#">
                                                                    <label>
                                                                        <input name='filter_cols[]' type="checkbox"
                                                                            onclick="showHide(this)" class="option justone"
                                                                            value='rate' checked />
                                                                        Rate
                                                                    </label>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#">
                                                                    <label>
                                                                        <input name='filter_cols[]' onclick="showHide(this)"
                                                                            type="checkbox" class="option justone"
                                                                            value='discount' checked />
                                                                        Discount
                                                                    </label>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#">
                                                                    <label>
                                                                        <input name='filter_cols[]' type="checkbox"
                                                                            onclick="showHide(this)" class="option justone"
                                                                            value='tax' checked />
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
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="table-responsive inv-table">
                                                <table class="table table-bordered">
                                                    <thead class="thead-light text-center">
                                                        <tr class="item-row">
                                                            <th style="width: 25%">Particulars</th>
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
                                                        <tr class="item-row">
                                                            <td>
                                                                <select name="particulars[]" class="form-control item"
                                                                    id="item">
                                                                    <option value="">--Select Option--</option>
                                                                </select>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('particulars') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input class="form-control qty" placeholder="Quantity"
                                                                    type="text" name="quantity[]">
                                                                {{-- <span class="stock">123</span> --}}
                                                                </p>
                                                                <div class="modal fade" tabindex="-1" role="dialog"
                                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">Select Serial
                                                                                    Numbers</h5>
                                                                                <button type="button"
                                                                                    class="close"
                                                                                    data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group text-left">
                                                                                    <label for="serial_No">Serial
                                                                                        No:</label>
                                                                                    <select name="serial_No[]"
                                                                                        class="form-control serial_numbers qtyoption"
                                                                                        multiple="multiple">
                                                                                    </select>
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
                                            </div>
                                            <input type="hidden" class="serialcount" value="0">
                                            <p class="text-danger">
                                                {{ $errors->first('quantity') }}
                                            </p>
                                            </td>

                                            <td>
                                                <input type="text" name="stock[]" class="form-control stock"
                                                    placeholder="Stock" readonly="readonly">
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
                                                <td><span id="totalQty" style="color: red; font-weight: bold;font-size:12px;">0</span> Units
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
                                                <td style="border:none;"></td>
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
                                                        value="0"></td>
                                            </tr>
                                            <tr>
                                                <td></td>
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
                                            <div class="col-md-4 row">
                                                <div class="col-md-6">
                                                    <label for="payment_type">Payment Type<i
                                                            class="text-danger">*</i>:</label>
                                                    <select name="payment_type" id="payment_type"
                                                        class="form-control payment_type">
                                                        <option value="">--Select an option--</option>
                                                        <option value="paid">Paid</option>
                                                        <option value="partially_paid">Partially Paid</option>
                                                        <option value="unpaid">Credit</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="payment_amount">Payment Amount<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="text" name="payment_amount" id="payment_amount"
                                                        class="form-control payment_amount" placeholder="Enter Paid Amount"
                                                        required>
                                                    <p class="off text-danger">Payment can't be more than that of Grand Total
                                                    </p>
                                                </div>
                                           </div>
                                            <div class="paymentMethod col-md-6 row">
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
                                                                <select name="online_portal"
                                                                    class="form-control online_portal_class" id="online_portal">
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3" style="padding-left:7px;">
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
                                                                <select name="bank_id" class="form-control bank_info_class"
                                                                    id="bank_info">
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3" style="padding-left:7px;">
                                                                <button type="button" data-toggle='modal' data-target='#bankinfoadd'
                                                                    data-toggle='tooltip' data-placement='top'
                                                                    class="btn btn-primary icon-btn btn-sm" title="Add New Bank"><i
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
                                            <label for="remarks">Remarks<i class="text-danger">*</i>: </label>
                                            <textarea name="remarks" class="form-control" placeholder="Remarks..."
                                                rows="5"></textarea>
                                            <p class="text-danger">
                                                {{ $errors->first('remarks') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="btn-bulk mt-2 col-md-12 d-flex justify-content-end">
                                        <button class="btn btn-primary submit" type="submit">Submit</button>
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
                                                                    <select name="dealer_type_id" id="dealer_type_id"
                                                                        class="form-control">
                                                                        @foreach ($dealerTypes as $dealertype)
                                                                            <option value="{{ $dealertype->id }}"
                                                                                {{ $dealertype->is_default == 1 ? 'selected' : '' }}>
                                                                                {{ $dealertype->title }}</option>
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
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="is_vendor">Is Vendor:</label><br>
                                                                    <span style="margin-right: 5px; font-size: 12px;">NO</span>
                                                                        <label class="switch pt-0">
                                                                            <input type="checkbox" name="is_vendor" value="1">
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
    </div>
    </section>
    <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection
@push('scripts')
<script src="{{ asset('backend/dist/js/mousetrap/sale.js') }}"></script>
    <script type="text/javascript">
        var selected_filter_array = new Array();
        $(".dropdown-menu input[type=checkbox]:checked").each(function() {
            selected_filter_array.push(this.value);
        });

        $('#SelectedFilter').val(selected_filter_array);
        window.selected_filter_array = selected_filter_array;

        function showHide(that) {
            if ($(that).is(":checked")) {
                var column = "table ." + $(that).val();
                target = column;
                var $el = $(target);
                var $cell = $el.closest('th,td');
                var $table = $cell.closest('table');

                // get cell location
                var colIndex = $cell[0].cellIndex + 1;

                // find and hide col index
                $table.find("tbody tr, thead tr")
                    .children(":nth-child(" + colIndex + ")")
                    .toggle();

            } else {
                var column = "table ." + $(that).val();
                target = column;
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

            $(".dropdown-menu input[type=checkbox]:checked").each(function() {
                selected_filter_array.push(this.value);
            });
            $('#SelectedFilter').val(selected_filter_array);

            window.selected_filter_array = selected_filter_array;
        }
    </script>
    <script src="{{ asset('backend/dist/js/jquery.salesinvoice.js') }}"></script>
    <script src="{{ asset('backend/dist/js/mousetrap/sales.js') }}"></script>
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

            var mainInputCr = document.getElementById("entry_date_nepaliCr");
            var engtodayobjCr = NepaliFunctions.ConvertToDateObject(today, "YYYY-MM-DD");
            var neptoday = NepaliFunctions.ConvertDateFormat(NepaliFunctions.AD2BS(engtodayobjCr), "YYYY-MM-DD");
            document.getElementById('entry_date_nepaliCr').value = neptoday;

            mainInput.nepaliDatePicker({
                onChange: function() {
                    var nepdate = mainInput.value;
                    var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                    document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(
                        NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                }
            });

            mainInputCr.nepaliDatePicker({
                onChange: function() {
                    var nepdate = mainInputCr.value;
                    var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                    document.getElementById('englishCr').value = NepaliFunctions.ConvertDateFormat(
                        NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                }
            });

            function fillSelectClient(clients) {
                document.getElementById("client").innerHTML = '<option value=""> --Select an option-- </option>' +
                    clients.reduce((tmp, x) =>
                        `${tmp}<option value='${x.id}' data-dealer_percent = '${x.dealertype.percent}'>${x.name}</option>`,
                        '');
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
    <script>
        $(function() {

            // var tablerow = $('table#salestbl tr');
            var particulars = $('#table input[particulars]')

            $(document).on('input', 'input.creditPrice', function() {
                $(this).parent().siblings().find('input.debitPrice').attr('readonly',
                    'readonly');
            });
            $(document).on('focus', '.serial_no', function() {
                $(this).siblings('.modal').modal({
                    show: true
                });
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

            $(document).on('change','.client_info',function(){
                var url = "{{route('client.show','id')}}";

                var url = url.replace("id", $(this).val());
                $.get(url,function(response){
                    // console.log(response);
                    $('.panVat').val(response.pan_vat);
                    $('.total_credit').val(response.total_credit);
                    $('.total_pay_credit').val(response.total_payable_amount);
                })
            })

            $(document).ready(function() {
                $(".godown_info").select2();
                $('.serial_numbers').select2({
                    placeholder: '--Select serial numbers--',
                });
            });
        });
    </script>

    <script>
        jQuery(document).ready(function() {
            jQuery().invoice({
                addRow: "#addRow",
                delete: ".delete",
                parentClass: ".item-row",

                godown: "#godown",
                product_barcode: "#product_barcode",
                client: "#client",
                item: '.item',
                stock: '.stock',
                danger: '.danger',
                unit: ".unit",
                rate: ".rate",
                qty: ".qty",
                qtyoption: '.qtyoption',
                serialcount: '.serialcount',
                removebutton: '.select2-selection__choice__remove',
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
                if ($(this).find("input[type='checkbox']").is(":checked"))
                {
                    var vendor_check = 1
                }else{
                    var vendor_check = 0
                }
                var clientData = {
                    client_type: $("#client_type").val(),
                    dealer_type_id:$('#dealer_type_id').val(),
                    name: $("#name").val(),
                    client_code: $("#client_code").val(),
                    email: $("#client_email").val(),
                    phone: $("#client_phone").val(),
                    local_address: $("#client_local_address").val(),
                    province: $("#client_province").val(),
                    district: $("#client_district").val(),
                    pan_vat: $("#client_pan_vat").val(),
                    concerned_name: $("#client_concerned_name").val(),
                    concerned_email: $("#client_concerned_email").val(),
                    concerned_phone: $("#client_concerned_phone").val(),
                    designation: $("#client_designation").val(),
                    opening_balance: $('#client_add_form').find(".opening_balance").val(),
                    behaviour: $('#client_add_form').find(".behaviour").find(':selected').val(),
                    is_vendor: vendor_check
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
                                `${tmp}<option value='${x.id}' data-dealer_percent = '${x.dealertype.percent}'>${x.name}</option>`,
                                '');
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
                    payment_id: $("#payment_id").val(),
                    opening_balance: $('#online_portal_add').find(".opening_balance").val(),
                    behaviour: $('#online_portal_add').find(".behaviour").find(':selected').val()
                };

                // console.log(formData)

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
                    payment_amount.attr('readonly', 'readonly');
                    payment_amount.val('0');
                    $('.paymentMethod').hide();
                    $('.creditDate').show();
                }
            })
        });
    </script>
    <script>
        $(function() {
            var selgodown = $('#godown').find(":selected").val();
            var warehouses = @php echo  json_encode($godowns); @endphp;
            var warehousecount = warehouses.length;

            function wareproducts() {
                for (let i = 0; i < warehousecount; i++) {
                    if (warehouses[i].id == selgodown) {
                        var godownpros = warehouses[i].godownproduct;
                        var gdpcount = godownpros.length;

                        var prodoptions = '<option value="">--Select Product--</option>';
                        for (let s = 0; s < gdpcount; s++) {
                            var stock = godownpros[s].stock;
                            var proname = godownpros[s].product.product_name;
                            var rate = godownpros[s].product.product_price;
                            var primary_unit = godownpros[s].product.primary_unit;
                            var proserialnumber = godownpros[s].product.has_serial_number;
                            var procode = godownpros[s].product.product_code;
                            var brand = godownpros[s].product.brand ? godownpros[s].product.brand.brand_name :'';
                            var proid = godownpros[s].product_id;
                            prodoptions += `<option value="${proid}"
                                            data-rate="${rate}"
                                            data-stock="${stock}"
                                            data-priunit = "${primary_unit}"
                                            data-has_serial_number = "${proserialnumber}"
                                        >
                                            ${proname}(${procode})${brand != null ? '-':''}${brand}
                                        </option>`;
                        }

                    }
                }
                return prodoptions;
            }
            $('.item').html(wareproducts());
            $('body').click();
            return 1;
        })

        $('#godown').change(function() {
            var selgodown = $('#godown').find(":selected").val();
            var warehouses = @php echo  json_encode($godowns); @endphp;
            var warehousecount = warehouses.length;

            function wareproducts() {

                for (let i = 0; i < warehousecount; i++) {
                    if (warehouses[i].id == selgodown) {
                        var godownpros = warehouses[i].godownproduct;
                        var gdpcount = godownpros.length;

                        var prodoptions = '<option value="">--Select Product--</option>';
                        for (let s = 0; s < gdpcount; s++) {
                            var stock = godownpros[s].stock;
                            var proname = godownpros[s].product.product_name;
                            var rate = godownpros[s].product.product_price;
                            var primary_unit = godownpros[s].product.primary_unit;
                            var proserialnumber = godownpros[s].product.has_serial_number;
                            var procode = godownpros[s].product.product_code;


                            var proid = godownpros[s].product_id;
                            var serialnumbers = godownpros[s].allserialnumbers;
                            prodoptions += `<option value="${proid}"
                                            data-rate="${rate}"
                                            data-stock="${stock}"
                                        data-priunit = "${primary_unit}"
                                        data-procode = "${procode}"
                                        data-has_serial_number = "${proserialnumber}"
                                        data-serialnumbers = "${serialnumbers}">
                                            ${proname}(${procode})
                                        </option>`;
                        }

                    }
                }
                return prodoptions;
            }
            $('.item').html(wareproducts());
            $('body').click();
            return 1;
        })
        $('#client').change(function() {
            $('body').click();
        });
        $('.alldiscounttype').change(function(){
            $('body').click();
        })
        $(function() {
            var godown_id = $('#godown').find(":selected").val();
            // console.log(godown_id);
            var item_id = $(this).find(':selected').val();
            var warehouses = @php echo  json_encode($godowns); @endphp;
            var warehousecount = warehouses.length;
            var serialoptions = '';
            for (let x = 0; x < warehousecount; x++) {
                if (warehouses[x].id == godown_id) {
                    var warehouseproducts = warehouses[x].godownproduct;
                    var warehouseproductlen = warehouseproducts.length;
                    for (let z = 0; z < warehouseproductlen; z++) {
                        if (warehouseproducts[z].product_id == item_id) {
                            var allserialnumbers = warehouseproducts[z].serialnumbers;
                            var serialnumberscount = allserialnumbers.length;
                            if (serialnumberscount > 0) {
                                for (let y = 0; y < serialnumberscount; y++) {
                                    serialoptions += `<option value="${allserialnumbers[y].serial_number}">
                                    ${allserialnumbers[y].serial_number}
                                    </option>`;
                                }
                                $('.qtyoption').html(serialoptions);
                            }
                            $('.serialcount').val(serialnumberscount);
                        }
                    }
                }
            }
            $('.item').on('change', function() {
                $('body').click();
                var godown_id = $('#godown').find(":selected").val();
                var item_id = $(this).find(':selected').val();
                var warehouses = @php echo  json_encode($godowns); @endphp;
                // console.log(warehouses);
                var warehousecount = warehouses.length;
                var serialoptions = '';
                for (let x = 0; x < warehousecount; x++) {
                    if (warehouses[x].id == godown_id) {
                        var warehouseproducts = warehouses[x].godownproduct;
                        var warehouseproductlen = warehouseproducts.length;
                        for (let z = 0; z < warehouseproductlen; z++) {
                            if (warehouseproducts[z].product_id == item_id) {
                                var allserialnumbers = warehouseproducts[z].serialnumbers;
                                var serialnumberscount = allserialnumbers.length;
                                if (serialnumberscount > 0) {
                                    for (let y = 0; y < serialnumberscount; y++) {
                                        serialoptions += `<option value="${allserialnumbers[y].serial_number}">
                                        ${allserialnumbers[y].serial_number}
                                        </option>`;
                                    }
                                    $('.qtyoption').html(serialoptions);
                                }
                                $('.serialcount').val(serialnumberscount);
                            }
                        }
                    }
                }
            })
        })
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
    </script>
    <script>
        window.taxes = <?php echo json_encode($taxes); ?>;
        window.categories = <?php echo json_encode($categories); ?>
    </script>
@endpush

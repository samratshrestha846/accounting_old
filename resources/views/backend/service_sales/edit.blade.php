@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
    @endpush
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="sec-header">
                @php
                    $billing_type_id = $serviceSale->billing_type_id;
                @endphp
                <div class="container-fluid">
                    @if ($billing_type_id == 1)
                    <div class="sec-header-wrap">
                        <h1 class="m-0">Update Service Sales Bills </h1>
                        <div class="btn-bulk">
                            <a href="{{ route('service_sales.index') }}" class="global-btn">View Sales Bills</a>
                            <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>1]) }}" class="global-btn">Unapproved Sales Bills</a>
                            <a href="{{ route('cancelledServiceBills',['billing_type_id'=>1]) }}" class="global-btn">Cancelled Sales Bills</a>
                        </div>
                    </div>
                    @elseif ($billing_type_id == 2)
                    <div class="sec-header-wrap">
                        <h1 class="m-0">Update Service Purchase Bills </h1>
                        <div class="btn-bulk">
                            <a href="{{ route('service_sales.index', ['billing_type_id'=>2]) }}" class="global-btn">View Purchases Bills</a>
                            <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>2]) }}" class="global-btn">Unapproved Purchases Bills</a>
                            <a href="{{ route('cancelledServiceBills',['billing_type_id'=>2]) }}" class="global-btn">Cancelled Purchases Bills</a>
                        </div>
                    </div>
                    @elseif ($billing_type_id == 5)
                    <div class="sec-header-wrap">
                        <h1 class="m-0">Update Service Debit Note Bills </h1>
                        <div class="btn-bulk">
                            <a href="{{ route('service_sales.index', ['billing_type_id'=>5]) }}" class="global-btn">View Debit Note Bills</a>
                            <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>5]) }}" class="global-btn">Unapproved Debit Note Bills</a>
                            <a href="{{ route('cancelledServiceBills',['billing_type_id'=>5]) }}" class="global-btn">Cancelled Debit Note Bills</a>
                        </div>
                    </div>
                    @elseif ($billing_type_id == 6)
                    <div class="sec-header-wrap">
                        <h1 class="m-0">Update Service Credit Note Bills </h1>
                        <div class="btn-bulk">
                            <a href="{{ route('service_sales.index', ['billing_type_id'=>6]) }}" class="global-btn">View Credit Note Bills</a>
                            <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>6]) }}" class="global-btn">Unapproved Credit Note Bills</a>
                            <a href="{{ route('cancelledServiceBills',['billing_type_id'=>6]) }}" class="global-btn">Cancelled Credit Note Bills</a>
                        </div>
                    </div>
                    @endif
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
                            <h2>Service Billing Info</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <form action="{{ route('service_sales.update', $serviceSale->id) }}" method="POST">
                                        @csrf
                                        @method("PUT")
                                        <div class="row">
                                            {{-- <input type="hidden" name="vendor_id" value="{{$serviceSale->vendor_id}}"> --}}
                                            <input type="hidden" name="billing_type_id" value="{{$billing_type_id}}">
                                            <input type="hidden" name="reference_invoice_no" value="{{$serviceSale->reference_invoice_no}}">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="fiscal-year">Fiscal Year<i class="text-danger">*</i>:
                                                    </label>
                                                    <select name="fiscal_year_id" id="fiscal-id" class="form-control" @if($serviceSale->billing_type_id == 5) disabled @endif>
                                                        @foreach ($fiscal_years as $fiscalyear)
                                                            <option value="{{ $fiscalyear->id }}" {{ $serviceSale->fiscal_year_id == $fiscalyear->id ? 'selected' : '' }}>
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
                                                        class="form-control" value="{{ $serviceSale->nep_date }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="english_date">Entry Date (A.D)<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="date" name="eng_date" id="english"
                                                        class="form-control" value="{{ $serviceSale->eng_date }}"
                                                        readonly="readonly">
                                                </div>
                                            </div>
                                            @if ($billing_type_id == 1 || $billing_type_id == 6)
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="client_name">Customer Name: </label>
                                                    <div class="v-add">
                                                        <select name="client_id" id="client" class="form-control client_info"
                                                            style="font-size: 18px; padding: 5px;" required @if($serviceSale->billing_type_id == 5) disabled @endif>
                                                            <option value="">--Select an option--</option>
                                                            @foreach ($clients as $client)
                                                                <option value="{{ $client->id }}"
                                                                    {{ $client->id == $serviceSale->client_id ? 'selected' : '' }}>
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
                                                    <input type="text" class="form-control panVat" readonly value="{{$serviceSale->client->pan_vat}}">
                                                </div>
                                            </div>
                                            @elseif ($billing_type_id == 2 || $billing_type_id == 5)
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="party_name">Party Name: </label>
                                                    <div class="v-add">
                                                        <select name="vendor_id" id="vendor" class="form-control vendor_info" required>
                                                            @foreach ($vendors as $vendor)
                                                                <option value="{{ $vendor->id }}"
                                                                    {{ $vendor->id == $serviceSale->vendor_id ? 'selected' : '' }}>
                                                                    {{ $vendor->company_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="text-danger">
                                                            {{ $errors->first('vendor_id') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="pan_vat">Supplier Pan/Vat</label>
                                                    <input type="text" class="form-control panVat" readonly value="{{$serviceSale->suppliers->pan_vat}}">
                                                </div>
                                            </div>
                                            @endif


                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="ledger_no">VAT Bill No<i class="text-danger">*</i>: </label>
                                                    <input type="text" class="form-control" name="ledger_no" value="{{ $serviceSale->ledger_no }}"
                                                        placeholder="VAT Bill No." required>
                                                    <p class="text-danger">
                                                        {{ $errors->first('ledger_no') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="file_no">File No (Optional): </label>
                                                <input type="text" name="file_no" class="form-control" placeholder="File No." value="{{ $serviceSale->file_no }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('file_no') }}
                                                </p>
                                            </div>


                                        </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive mt-2">
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
                                                        @foreach ($serviceSale->serviceSalesExtra as $key => $item)
                                                            <tr class="item-row" id="tr_row_{{ $key+1 }}">
                                                                <td>
                                                                    <div class="delete-btn">
                                                                        <select name="particulars[]" class="form-control item" id="item_{{ $key+1 }}" onchange="showrate({{ $key+1 }})" required @if($serviceSale->billing_type_id == 5) disabled @endif>
                                                                            <option value="">--Select Option--</option>
                                                                            @foreach ($categories as $category)
                                                                                <option class="title" disabled value="">
                                                                                    {{ $category->category_name }}</option>
                                                                                @foreach ($category->services as $service)
                                                                                    <option value="{{ $service->id }}" {{ $service->id == $item->particulars ? 'selected' : '' }}
                                                                                        data-rate="{{ $service->sale_price }}">
                                                                                        {{ $service->service_name }} ({{ $service->service_code }})
                                                                                    </option>
                                                                                @endforeach
                                                                            @endforeach
                                                                        </select>
                                                                        <a class="delete" data-rowId="tr_row_{{ $key+1 }}" href="javascript:;" title="Remove row">X</a>
                                                                    </div>
                                                                    @if($serviceSale->billing_type_id == 5)
                                                                        <input type="hidden" name="particulars[]"  value="{{$item->particulars}}">
                                                                    @endif

                                                                    <p class="text-danger">
                                                                        {{ $errors->first('particulars') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control qty" placeholder="Quantity"
                                                                        type="text" name="quantity[]" value="{{ $item->quantity }}">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('quantity') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control unit" placeholder="Unit" type="text"
                                                                        name="unit[]" value="{{ $item->unit }}">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('unit') }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control rate" placeholder="Rate" type="text" value="{{ $item->rate }}"
                                                                        name="rate[]" id="rate_{{ $key }}">
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
                                                                                                        {{ $item->discounttype == 'percent' ? 'selected' : '' }}>
                                                                                                        Percent
                                                                                                        %</option>
                                                                                                    <option value="fixed"
                                                                                                        {{ $item->discounttype == 'fixed' ? 'selected' : '' }}>
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
                                                                                                    value="{{ $item->dtamt }}">
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
                                                                                                        {{ $item->taxtype == 'exclusive' ? 'selected' : '' }}>
                                                                                                        Exclusive</option>
                                                                                                    <option value="inclusive"
                                                                                                        {{ $item->taxtype == 'inclusive' ? 'selected' : '' }}>
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
                                                                                                            {{ $item->tax == "$tax->percent" ? 'selected' : '' }}>
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
                                                                    <input name="total[]" class="form-control total" value="{{ $item->total }}"
                                                                        readonly="readonly">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('total') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @if($serviceSale->billing_type_id != 5)
                                                        <tr id="hiderow">
                                                            <td colspan="7" class="text-right">
                                                                <div class="btn-bulk justify-content-center">
                                                                <a id="addRow" href="javascript:;" title="Add a row"
                                                                    class="btn btn-primary">Add a row</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <td>
                                                                <strong>Total Quantity: </strong>
                                                            </td>
                                                            <td>
                                                                <span id="totalQty"
                                                                    style="color: red; font-weight: bold">0</span> Units
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
                                                            <td style="border:none;">
                                                            </td>
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
                                                                                                <option value="percent" {{ $serviceSale->alldiscounttype == 'percent' ? 'selected' : '' }}>
                                                                                                    Percent
                                                                                                    %</option>
                                                                                                <option value="fixed"{{ $serviceSale->alldiscounttype == 'fixed' ? 'selected' : '' }}>
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
                                                                                            {{-- @if ($serviceSale->alldiscounttype == 'percent')
                                                                                                <input type="text" name="alldtamt" class="form-control alldtamt" placeholder="Discount" value="{{ $serviceSale->discountpercent }}">
                                                                                            @elseif ($serviceSale->alldiscounttype == 'fixed')
                                                                                                <input type="text" name="alldtamt" class="form-control alldtamt" placeholder="Discount" value="{{ $serviceSale->discountamount }}">
                                                                                            @endif --}}
                                                                                            <input type="text" name="alldtamt" class="form-control alldtamt" placeholder="Discount" value="{{ $serviceSale->alldtamt }}">

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
                                                                    id="service_charge" value="{{$serviceSale->service_charge}}" type="text">
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
                                                                                                <option value="percent" @if($serviceSale->servicediscounttype == 'percent') selected @endif>Percent
                                                                                                    %</option>
                                                                                                <option value="fixed" @if($serviceSale->servicediscounttype == 'fixed') selected @endif>Fixed
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <div class="row">
                                                                                        <div class="col-3">
                                                                                            <label for="dtamt">Charge:</label>
                                                                                        </div>
                                                                                        <div class="col-9">
                                                                                            <input type="text" name="servicediscountamount"
                                                                                                class="form-control alldtamtservice"
                                                                                                placeholder="Discount" value="{{ $serviceSale->servicediscountamount }}">
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
                                                                                                    {{ $serviceSale->alltaxtype == 'exclusive' ? 'selected' : '' }}>
                                                                                                    Exclusive</option>
                                                                                                <option value="inclusive"
                                                                                                    {{ $serviceSale->alltaxtype == 'inclusive' ? 'selected' : '' }}>
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
                                                                                                        {{ $serviceSale->alltax == $tax->percent ? 'selected' : '' }}>
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
                                                            <td><strong>Shipping</strong></td>
                                                            <td>
                                                                <input name="shipping" class="form-control" id="shipping"
                                                                    value="{{ $serviceSale->shipping }}" type="text">
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
                                                                    id="grandTotal" readonly="readonly">
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
                                                                    value="{{ $serviceSale->vat_refundable }}"></td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            @if($ird_sync == 1)
                                                            <td>
                                                                <strong>IRD Sync<i class="text-danger">*</i>: </strong>
                                                            </td>
                                                            <td>
                                                                <input type="radio" name="sync_ird" value="1"
                                                                    {{ $serviceSale->sync_ird == 1 ? 'checked' : '' }}> Yes
                                                                <input type="radio" name="sync_ird" value="0"
                                                                    {{ $serviceSale->sync_ird == 0 ? 'checked' : '' }}> No
                                                        </td>
                                                            @else
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            @endif
                                                            <td>
                                                                <strong>Status:</strong>
                                                            </td>
                                                            <td>
                                                                <input type="radio" name="status" value="1"
                                                                        {{ $serviceSale->status == '1' ? 'checked' : '' }}> Approve
                                                                    <input type="radio" name="status" value="0"
                                                                        {{ $serviceSale->status == '0' ? 'checked' : '' }}> Unapprove
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
                                                    <input type="text" class="form-control due_amt" value="{{$serviceSale->billing_credit_service->credit_amount ?? 0}}" readonly>

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
                                                            <option value="paid"
                                                                {{ $serviceSale->payment_type == 'paid' ? 'selected' : '' }}>
                                                                Paid</option>
                                                            <option value="partially_paid"
                                                                {{ $serviceSale->payment_type == 'partially_paid' ? 'selected' : '' }}>
                                                                Partially Paid</option>
                                                            <option value="unpaid"
                                                                {{ $serviceSale->payment_type == 'unpaid' ? 'selected' : '' }}>
                                                                Unpaid</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="payment_amount">Payment Amount</label>
                                                        <input type="text" name="payment_amount" id="payment_amount"
                                                            readonly="{{$serviceSale->payment_type == 'partially_paid' ? 'false' : 'readonly'}}"
                                                            class="form-control payment_amount" placeholder="Enter Paid Amount"
                                                            value="{{ $serviceSale->payment_amount }}" required>
                                                        <p class="off text-danger">Payment can't be more than that of Grand Total
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row col-md-6 paymentMethod">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="Payment Type">Payment Method:</label>
                                                            <select name="payment_method" class="form-control payment_method">
                                                                @foreach ($payment_methods as $method)
                                                                    <option value="{{ $method->id }}"{{ $method->id == $serviceSale->payment_method ? 'selected' : '' }}>{{ $method->payment_mode }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" id="online_payment" @if ($serviceSale->online_portal_id == null) style="display: none;" @endif>
                                                        <div class="form-group">
                                                            <label for="Bank">Select a portal:</label>
                                                            <select name="online_portal" class="form-control" id="online_portal">
                                                                <option value="">--Select a portal--</option>
                                                                @foreach ($online_portals as $portal)
                                                                    <option value="{{ $portal->id }}" @if ($serviceSale->online_portal_id != null) {{ $portal->id == $serviceSale->online_portal_id ? 'selected' : '' }} @endif>{{ $portal->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" id="customer_portal_id" @if ($serviceSale->customer_portal_id == null) style="display: none;" @endif>
                                                        <div class="form-group">
                                                            <label for="">Portal Id:</label>
                                                            <input type="text" class="form-control" placeholder="Portal Id"
                                                                name="customer_portal_id" @if ($serviceSale->customer_portal_id != null) value="{{ $serviceSale->customer_portal_id }}" @endif>
                                                        </div>
                                                    </div>

                                                    @if ($serviceSale->bank_id == null)
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
                                                                        <option value="{{ $bank->id }}"{{ $bank->id == $serviceSale->bank_id ? 'selected' : '' }}>{{ $bank->bank_name }} ({{ $bank->head_branch }})</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($serviceSale->cheque_no == null)
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
                                                                <input type="text" class="form-control" placeholder="Cheque No." name="cheque_no" value="{{ $serviceSale->cheque_no }}">
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
                                        <div class="col-md-12 mt-3">
                                            <div class="form-gorup">
                                                <label for="remarks">Remarks: </label>
                                                <textarea name="remarks" class="form-control" placeholder="Remarks.." rows="5">{{ $serviceSale->remarks }}</textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('remarks') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <button class="btn btn-primary submit ml-auto" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
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
                $(".item").select2();
            });

            $(document).ready(function() {
                $(".client_info").select2();
            });
            $(document).on('change','.client_info',function(){
                var url = "{{route('client.show','id')}}";
                if($(this).val() == ""){
                    $('.panVat').val(" ");
                    return;
                }
                var url = url.replace("id", $(this).val());
                $.get(url,function(response){
                    $('.panVat').val(response);
                })
            })

            $(document).ready(function() {
                $(".category").select2();
            });
        });

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

                        document.getElementById('item_'+number).innerHTML =
                            `<option value="">--Select an option--</option>` +
                            cateoptions + `<option value="secondoption"> + Add new Service </option>`;
                    }
                })
            }
        }
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
                   $("#service_add_form").html(
                       '<div class="alert alert-success">Successfully added.</div>'
                   );
               });
               event.preventDefault();
           });
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
            alldiscounttypeservice:'.alldiscounttypeservice',
            alldtamtservice: ".alldtamtservice",
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
    $(document).ready(function() {
        $('body').trigger('click');
        $('body').trigger('click');
    });
    $('.alldiscounttype').change(function(){
        $('body').click();
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


    function showrate(number)
    {
        var value = document.getElementById("item_"+number).value;

        if (value == "secondoption")
        {
            document.getElementById("item_no").value = number;
            $('#serviceinfoadd').modal("show");
        }

        function fetchServices(value) {
            var uri = "{{ route('apiService', ':no') }}";
            var billing_id = '{{ $billing_type_id }}';
            uri = uri.replace(':no', value);
            $.ajax({
                url: uri,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    if(billing_id == 2){
                        document.getElementById("rate_"+number).value = response.cost_price;
                    }else{

                    document.getElementById("rate_"+number).value = response.sale_price;
                    }
                }
            });
        }
        fetchServices(value);
    }
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
    window.taxes = @php echo json_encode($taxes); @endphp;
    window.categories = @php echo json_encode($categories); @endphp;
</script>
@endpush

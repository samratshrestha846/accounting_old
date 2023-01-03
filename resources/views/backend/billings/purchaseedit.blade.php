@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha256-aAr2Zpq8MZ+YA/D6JtRD3xtrwpEz2IqOS+pWD/7XKIw=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" /> --}}
    <style type="text/css">
        .bootstrap-tagsinput span {
        margin-bottom: 10px;
        }
        .bootstrap-tagsinput{
            width: 100% !important;
            display: flex !important;
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
                    <h1>Edit Purchase</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('billings.report', $billing_type_id) }}" class="global-btn">View All
                            Purchases</a>
                    </div>
                </div>
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
                        <h2>Purchase</h2>
                    </div>
                    <div class="card-body">
                        <div class="ibox">
                            <div class="row ibox-body">
                                <div class="col-12">
                                    <form action="{{ route('billings.update', $billing->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="billing_type_id" value="2">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="fiscal-year">Fiscal Year: </label>
                                                    <select name="fiscal_year_id" id="fiscal-year" class="form-control">
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
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="party_name">Party Name: </label>
                                                    <div class="v-add">
                                                        <select name="vendor_id" id="vendor" class="form-control vendor_info"
                                                            style="font-size: 18px; padding: 5px;" required>
                                                            @foreach ($vendors as $vendor)
                                                                <option value="{{ $vendor->id }}"
                                                                    {{ $vendor->id == $billing->vendor_id ? 'selected' : '' }}>
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
                                                    <input type="text" class="form-control panVat" readonly value="{{$billing->suppliers->pan_vat}}">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="ledger_no">Party Bill No: </label>
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
                                                        value="{{ $billing->file_no }}" placeholder="File no.">
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
                                                        class="form-control" value="{{ $billing->declaration_form_no }}"
                                                        placeholder="Declaratoin form no" style="width:91%;">

                                                </div>
                                            </div>
                                            @endif
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
                                                            @foreach ($billing->billingextras as $billingextra)
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
                                                                                            data-has_serial_number="{{($product->has_serial_number == 1) ? 1 : 0}}"
                                                                                            {{ $billingextra->particulars == $product->id ? 'selected' : '' }}>
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
                                                                            value="{{ $billingextra->quantity }}">
                                                                            <div class="modal fade" tabindex="-1" role="dialog"
                                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
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
                                                                                                <?php

                                                                                                    $grodownproduct_id = App\Models\GodownProduct::where('product_id',$billingextra->particulars)->where('godown_id',$godown->id)->first()->id ?? '';
                                                                                                    $serial_numbers =  App\Models\GodownSerialNumber::where('godown_product_id',$grodownproduct_id)->where('purchase_billing_id',$billingextra->billing_id)->get();
                                                                                                    $arrayserialno = [];
                                                                                                    foreach($serial_numbers as $numbers){
                                                                                                        array_push($arrayserialno,$numbers->serial_number);
                                                                                                    }
                                                                                                ?>
                                                                                                @if(!empty($arrayserialno))
                                                                                                <div class="col-md-6 mb-2 forProductHavingSerialNo">
                                                                                                    <input type="text" name="serial_product[{{$godown->id}}]" class="serial_product" placeholder="eg;-abcd,1234" value="{{implode(',',$arrayserialno)}}" data-role="tagsinput">

                                                                                                </div>
                                                                                                @endif
                                                                                                <div class="col-md-3 mb-2">
                                                                                                    <input type="number" name="godown_qty[{{$godown->id}}][]" class="form-control godown_qty" step=".01" value="{{App\Models\ProductStock::where('billing_id',$billingextra->billing_id)->where('product_id',$billingextra->particulars)->where('godown_id',$godown->id)->first()->added_stock ?? 0}}">
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
                                                                        <input class="form-control unit" placeholder="Unit"
                                                                            type="text" name="unit[]"
                                                                            value="{{ $billingextra->unit }}" readonly>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('unit') }}
                                                                        </p>
                                                                    </td>

                                                                    <td>
                                                                        <input class="form-control rate" placeholder="Rate"
                                                                            type="text" name="rate[]" value="{{ $billingextra->rate }}">
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
                                                                    <div class="btn-bulk justify-content-end">
                                                                        <a id="addRow" href="javascript:;" title="Add a row"
                                                                        class="btn btn-primary">+ Add</a>
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
                                                                        value="{{ $billing->subtotal }}">
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
                                                                <td><strong>Tax %</strong></td>
                                                                <td>
                                                                    <input name="taxpercent" class="form-control" id="tax" value="{{$billing->taxpercent}}" type="text">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('taxpercent') }}
                                                                    </p>
                                                                </td>
                                                            </tr> --}}
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
                                                                <td><strong>Grand Total</strong></td>
                                                                <td>
                                                                    <input type="text" name="grandtotal" class="form-control"
                                                                        id="grandTotal" value="{{ $billing->grandtotal }}"
                                                                        readonly="readonly">
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
                                                                        value="{{ $billing->vat_refundable }}"></td>
                                                            </tr>
                                                            <tr>
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
                                                                        {{ $billing->status == 1 ? 'checked' : '' }}> Approve
                                                                    <input type="radio" name="status" value="0"
                                                                        {{ $billing->status == 0 ? 'checked' : '' }}> Unapprove
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

                                            <div class="col-md-12">
                                                <button class="btn btn-primary ml-auto" type="submit">Submit</button></td>
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
    <script src="{{ asset('backend/dist/js/jquery.editpurchase.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js" integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg==" crossorigin="anonymous"></script> --}}

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

        // $(document).on('focus', '.qty', function() {
        //     $(this).siblings('.modal').modal({
        //         show: true
        //     });
        // });

          $(document).on('click', '.qty', function() {
           var godowns = @php echo  json_encode($godowns); @endphp;
           var enablestockchange = @php echo json_encode($billing->enable_stock_change); @endphp;

           var serialnumber = $(this).closest('tr').find('select[name="particulars[]"] option:selected').attr('data-has_serial_number');
             if( godowns.length > 1 && enablestockchange == 1 || serialnumber == 1){


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

        // $(document).on('beforeItemAdd','.serial_product', function(event) {
        //     var tag = event.item;
        //     $.get('{{route('godownserialnumber')}}',{number:last},function(response){

        //     });

        // });

        $(document).on('itemAdded','.serial_product', function (event){
            var thisinput = $(this);
            var number = event.item;
            $.get('{{route('godownserialnumber')}}',{number:number},function(response){
                if(response == 'exist'){
                    //  tagsinput('remove', number);
                    thisinput.closest('div').find('.tag').last().remove();
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

        $(document).ready(function() {
            $(".item").select2();
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
    </script>
    <script>
        jQuery(document).ready(function() {
            jQuery().invoice({
                addRow: "#addRow",
                delete: ".delete",
                parentClass: ".item-row",

                item: ".item",
                unit: ".unit",
                godown: ".godown",
                godown_qty: '.godown_qty',
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
        $(document).on('change', '.item', function() {
            let rate = $(this).find(':selected').data('rate');
            let ratebox = $(this).closest('tr').find('.rate');
            ratebox.val(rate);

        })
        $('.alldiscounttype').change(function(){
            $('body').click();
        })
        $('.alldtamt, .qty, .godown_qty').keyup(function(){
            $('body').click();
            $('body').click();
        })
    </script>
     <script>
        window.godowns = @php echo  json_encode($godowns); @endphp;
    </script>
    <script>
        window.categories = <?php echo json_encode($categories); ?>
    </script>
@endpush

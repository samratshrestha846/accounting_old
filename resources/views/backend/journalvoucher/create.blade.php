@extends('backend.layouts.app')
@push('styles')
<style>
    .coloradd{
        color:red;
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
                    <h1>Entry Journal Voucher </h1>
                    <div class="bulk-btn">
                        <a href="{{ route('journals.index') }}" class="global-btn">Exhibit
                            Vouchers
                        </a>
                    </div>
                </div>
            </div>
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
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('journals.store') }}" id="validate"
                                    enctype="multipart/form-data" method="POST">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="entry_date">Entry Date (B.S)<i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" name="entry_date_nepali" id="entry_date_nepali"
                                                    class="form-control" value="{{ old('entry_date_nepali') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="english_date">Entry Date (A.D)<i
                                                        class="text-danger">*</i>:</label>
                                                <input type="date" name="english_date" id="english"
                                                    class="form-control" value="{{ old('entry_date_nepali', date('Y-m-d')) }}"
                                                    readonly="readonly">
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="status">Suppliers: </label>
                                                <div class="row">
                                                    <div class="col-md-9 pr-0">
                                                        <select name="vendor_id" id="vendor"
                                                            class="form-control supplier_info">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3" style="padding-left:7px;">
                                                        <button type="button" data-toggle='modal'
                                                            data-target='#supplieradd' data-toggle='tooltip'
                                                            data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                            title="Add New Supplier"><i
                                                                class="fas fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="client">Customers: </label>
                                                <div class="row">
                                                    <div class="col-md-9 pr-0">
                                                        <select name="client_id" id="client"
                                                            class="form-control client_info">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3" style="padding-left:7px;">
                                                        <button type="button" data-toggle='modal'
                                                            data-target='#clientadd' data-toggle='tooltip'
                                                            data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                            title="Add New Client"><i
                                                                class="fas fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="Payment Type">Payment Method:</label>
                                                <select name="payment_method" class="form-control payment_method">
                                                    <option value="">--Select One--</option>
                                                    @foreach ($payment_methods as $method)
                                                        <option value="{{ $method->id }}"{{ old('payment_method') == $method->id ? 'selected' : '' }}>
                                                            {{ $method->payment_mode }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="receipt_payment">Receipt / Payment:</label>
                                                <select name="receipt_payment" class="form-control">
                                                    <option value="">--Select an option--</option>
                                                    <option value="0">Receipt</option>
                                                    <option value="1">Payment</option>
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-2" id="online_payment" style="display: none;">
                                            <div class="form-group">
                                                <label for="Bank">Select a portal:</label>
                                                <div class="row">
                                                    <div class="col-md-9 pr-0">
                                                        <select name="online_portal" class="form-control online_portal_class" id="online_portal">
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
                                        <div class="col-md-2" id="customer_portal_id" style="display: none;">
                                            <div class="form-group">
                                                <label for="">Portal Id:</label>
                                                <input type="text" class="form-control" placeholder="Portal Id"
                                                    name="customer_portal_id" value="{{ old('customer_portal_id') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2" id="bank" style="display: none;">
                                            <div class="form-group">
                                                <label for="Bank">From Bank:</label>
                                                <div class="row">
                                                    <div class="col-md-9 pr-0">
                                                        <select name="bank_id" class="form-control bank_info_class" id="bank_info">
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
                                        </div>
                                        <div class="col-md-2" id="cheque" style="display: none;">
                                            <div class="form-group">
                                                <label for="cheque no">Cheque no.:</label>
                                                <input type="text" class="form-control" placeholder="Cheque No."
                                                    name="cheque_no" value="{{ old('cheque_no') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="file">File (Bills, etc.) (Opt):</label>
                                                <input type="file" name="file[]" id="file" class="form-control"
                                                    onchange="loadFile(event)" multiple>
                                                <p class="text-danger">
                                                    {{ $errors->first('file') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <img id="output" style="height: 50px;">
                                        </div>
                                        @if (Auth::user()->can('unapproved-journals'))
                                            <div class="col-md-3">
                                                <div class="form-group" style="font-size:11px;">
                                                    <label for="status">Status<i
                                                            class="text-danger">*</i>:</label><br>
                                                    <input type="radio" name="status" value="1" checked> Approved
                                                    <input type="radio" name="status" value="0"> To be Approved
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="status">Status<i
                                                            class="text-danger">*</i></label>:<br>
                                                    <input type="radio" name="status" value="1" disabled> Approved
                                                    <input type="radio" name="status" value="0" checked disabled> To be
                                                    Approved
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="table-responsive mt-2">
                                        <table class="table table-bordered table-hover" id="debtAccVoucher">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-center"> Account Name<i class="text-danger">*</i></th>
                                                    <th class="text-center"> Remarks</th>
                                                    <th class="text-center"> Debit</th>
                                                    <th class="text-center"> Credit</th>
                                                    <th class="text-center"> Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="debitvoucher">
                                                <tr class="test">
                                                    <td class="account_names" width=" 300px">
                                                        <select name="child_account_id[]" id="accountName_1"
                                                            class="form-control account_head" required>
                                                            <option value="">--Select One--</option>
                                                            <option value="addchildaccount" class="coloradd">+ Add Child Account</option>
                                                            @foreach ($accounts as $account) <option value="" class="title" disabled>
                                                                    {{ $account->title }}</option>
                                                                @php
                                                                    $sub_accounts = $account->sub_accounts;
                                                                @endphp

                                                                @foreach ($sub_accounts as $sub_account)
                                                                    @php
                                                                        $child_accounts = $sub_account->child_accounts;
                                                                    @endphp
                                                                    @foreach ($child_accounts as $child_account)
                                                                        <option value="{{ $child_account->id }}">
                                                                            {{ $child_account->title }} -
                                                                            {{ $sub_account->title }}</option>
                                                                    @endforeach
                                                                @endforeach
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input type="text" name="remarks[]"
                                                            class="form-control all_remarks" id="remarks_1" value="">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="debitAmount[]" value="0"
                                                            class="form-control debitPrice text-right"
                                                            id="debitAmount_1" onkeyup="calculateTotal(1)" step=".01">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="creditAmount[]" value="0"
                                                            class="form-control creditPrice text-right"
                                                            id="creditAmount_1" onkeyup="calculateTotal(1)" step=".01">
                                                    </td>

                                                    <td style="text-align: center;">
                                                        <button class="btn btn-primary icon-btn btn-sm m-auto" type="button"
                                                            value="Delete" onclick="deleteRowContravoucher(this)"><i
                                                                class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    {{-- <td></td> --}}
                                                    {{-- <td></td> --}}
                                                    <td colspan="2" class="text-right">
                                                        <label for="reason" class="col-form-label">Total</label>
                                                    </td>
                                                    <td class="text-right">
                                                        <input type="text" id="debitTotal"
                                                            class="form-control text-right" name="debitTotal" value="{{ old('debitTotal') }}"
                                                            readonly="readonly" value="0" />
                                                    </td>
                                                    <td class="text-right">
                                                        <input type="text" id="creditTotal"
                                                            class="form-control text-right " name="creditTotal" value="{{ old('creditTotal') }}"
                                                            readonly="readonly" value="0" />
                                                    </td>
                                                    {{-- <td></td> --}}
                                                    <td style="text-align: center;">
                                                        <a id="add_more" class="btn btn-primary icon-btn btn-sm m-auto"
                                                            name="add_more"
                                                            onClick="addaccountContravoucher('debitvoucher')"><i
                                                                class="fa fa-plus"></i></a>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="narration">Narration<i class="text-danger">*</i>:</label>
                                            <textarea name="narration" id="narration" class="form-control" cols="30"
                                                rows="5" placeholder="Being something..." required>{{ old('narration') }}</textarea>
                                            <p class="text-danger">
                                                {{ $errors->first('narration') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12 text-center btn-bulk d-flex justify-content-end">
                                            <input type="submit" id="add_receive" class="btn btn-primary" name="save"
                                                value="Save" tabindex="9" />
                                                <button type="submit" class="btn btn-secondary btn-large" name="saveandcontinue" value="1">Submit And Continue</button>

                                        </div>
                                        <div class="col-md-12">
                                            <p class="text-danger toti hide w-100 text-right mt-2">Debit Total and Credit Total must be equal
                                            </p>
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
                                                                            <option value="{{$dealertype->id}}" data-make_user="{{$dealertype->make_user}}" {{$dealertype->is_default == 1 ? 'selected' : ''}}>{{$dealertype->title}}</option>
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
                                                                            placeholder="Enter Client's Name" id="fullname">
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
                                                                            placeholder="Enter Customer Email" id="client_email">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('email') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                {{-- <div class="col-md-4 display">
                                                                    <div class="form-group">
                                                                        <label for="password">Password<i class="text-danger">*</i></label>
                                                                        <input type="password" name="password" class="form-control password" value="{{ old('password') }}" placeholder="Enter Password">
                                                                        <p class="text-danger">
                                                                            {{$errors->first('password')}}
                                                                        </p>
                                                                    </div>
                                                                </div> --}}

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="phone">Customer Phone</label>
                                                                        <input type="text" name="phone" class="form-control"
                                                                            value="{{ old('phone') }}"
                                                                            placeholder="Enter Customer Contact no." id="client_phone">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('phone') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="province">Province no.</label>
                                                                        <select name="province"
                                                                            class="form-control client_province" id="client_province">
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
                                                                        <input type="text" name="client_local_address"
                                                                            class="form-control"
                                                                            value="{{ old('local_address') }}"
                                                                            placeholder="Customer Local Address"
                                                                            id="local_address">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('local_address') }}
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
                                                                            id="client_pan_vat">
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
                                                                            placeholder="Enter Phone" id="client_concerned_phone">
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
                                                                            placeholder="Enter Email" id="client_concerned_email">
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
                                                                            placeholder="Enter Designation" id="client_designation">
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

                                <div class='modal fade text-left' id='supplieradd' tabindex='-1' role='dialog'
                                    aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document' style="max-width: 1000px;">
                                        <div class='modal-content'>
                                            <div class='modal-header text-center'>
                                                <h2 class='modal-title' id='exampleModalLabel'>Add Supplier</h2>
                                                <button type='button' class='close' data-dismiss='modal'
                                                    aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                <form action="" method="POST" id="supplier_form">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <p class="card-title">Company Details</p>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group"
                                                                        id="company_name_group">
                                                                        <label for="name">Company Name<i
                                                                                class="text-danger">*</i></label>
                                                                        <input type="text" name="company_name"
                                                                            class="form-control"
                                                                            value="{{ old('company_name') }}"
                                                                            placeholder="Enter Company Name"
                                                                            id="company_name" required>
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
                                                                            placeholder="Enter Company Code"
                                                                            id="company_code">
                                                                        <p class="text-danger companycode_error hide">
                                                                            Code is already used. Use Different code.
                                                                        </p>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('supplier_code') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group"
                                                                        id="company_email_group">
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
                                                                    <div class="form-group"
                                                                        id="company_phone_group">
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
                                                                        <label for="pan_vat">PAN No./VAT No.
                                                                            (Optional)</label>
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
                                                                        <select name="province"
                                                                            class="form-control province"
                                                                            id="province_id">
                                                                            <option value="">--Select a province--
                                                                            </option>
                                                                            @foreach ($provinces as $province)
                                                                                <option value="{{ $province->id }}">
                                                                                    {{ $province->eng_name }}
                                                                                </option>
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
                                                                    <div class="form-group"
                                                                        id="company_address_group">
                                                                        <label for="company_address">Company Local
                                                                            Address</label>
                                                                        <input type="text" name="company_address"
                                                                            class="form-control"
                                                                            value="{{ old('company_address') }}"
                                                                            placeholder="Company Address"
                                                                            id="company_address">
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
                                                                    <div class="form-group"
                                                                        id="concerned_name_group">
                                                                        <label for="concerned_name">Name</label>
                                                                        <input type="text" name="concerned_name"
                                                                            class="form-control"
                                                                            value="{{ old('concerned_name') }}"
                                                                            placeholder="Enter Name"
                                                                            id="concerned_name">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('concerned_name') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group"
                                                                        id="concerned_phone_group">
                                                                        <label for="concerned_phone">Phone</label>
                                                                        <input type="text" name="concerned_phone"
                                                                            class="form-control"
                                                                            value="{{ old('concerned_phone') }}"
                                                                            placeholder="Enter Phone"
                                                                            id="concerned_phone">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('concerned_phone') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group"
                                                                        id="concerned_email_group">
                                                                        <label for="concerned_email">Email</label>
                                                                        <input type="email" name="concerned_email"
                                                                            class="form-control"
                                                                            value="{{ old('concerned_email') }}"
                                                                            placeholder="Enter Email"
                                                                            id="concerned_email">
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
                                                                            placeholder="Enter Designation"
                                                                            id="designation">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('designation') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit"
                                                            class="btn btn-secondary btn-sm">Submit</button>
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
                                                                                <input type="text" name="name"
                                                                                    class="form-control"
                                                                                    id="name"
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

                        <input type="hidden" id="headoption" value="<option value=''>--Select One--</option><option value='addchildaccount' class='coloradd'>+ Add Child Account</option
                            @foreach ($accounts as $account)
                                <option value='' class='title' disabled>{{ $account->title }}</option>
                                @php
                                    $sub_accounts = $account->sub_accounts;
                                @endphp
                                    @foreach ($sub_accounts as $sub_account)
                                        @php
                                            $child_accounts = $sub_account->child_accounts;
                                        @endphp
                                        @foreach ($child_accounts as $child_account)
                                            <option value='{{ $child_account->id }}'>{{ $child_account->title }} -
                                                {{ $sub_account->title }}</option>
                                        @endforeach
                                    @endforeach
                            @endforeach"
                        name="">
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <div class="modal fade addchildac" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <input type="hidden" name="has_serial_number[]" value="">
        <div class="modal-dialog modalstock" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add child Account</h5>
                    <button type="button" class="close"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Title</label>
                            <input type="text" name="title" id="" class="form-control ">
                            <div class="error title">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Embedded Sub-Account Head*</label>
                            @php
                                $subaccounts = App\Models\SubAccount::select('id','title')->get();
                           @endphp
                            <select name="sub_account_id" id="" class="form-control subacc">
                                <option value="">--Select Sub Account--</option>
                                @foreach($subaccounts as $acc)
                                    <option value="{{$acc->id}}">{{$acc->title}}</option>
                                @endforeach
                            </select>
                            <div class="error sub_account_id">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Fiscal Year*</label>
                            @php
                                $fiscalyear = App\Models\FiscalYear::select('id','fiscal_year')->get();
                           @endphp
                            <select name="fiscal_year_id" id="" class="form-control">
                                <option value="">--Fiscal Year--</option>
                                @foreach($fiscalyear as $year)
                                    <option value="{{$year->id}}">{{$year->fiscal_year}}</option>
                                @endforeach
                            </select>
                            <div class="error fiscal_year_id">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Opening Balance Behaviour</label>

                            <select name="behaviour" id="" class="form-control">
                                <option value="debit">Debit</option>
                                <option value="credit">Credit</option>

                            </select>

                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Opening Balance</label>
                            <input type="text" name="opening_balance" id="" class="form-control opening_balance">
                        </div>

                    </div>
                    <div class="row text-center">
                        <div class="form-group">
                            <button type="button"
                                 class="btn btn-secondary btn-sm submitchildac">Submit</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
{{-- <script>
    // Hide and Show Username and Password
    $(function(){
        var make_user = $('#dealer_type_id').find(':selected').data('make_user');
        if(make_user == 1){
            $('.display').show();
            $('.email').attr('required',true);
            $('.password').attr('required',true);
        }else{
            $('.display').hide();
            $('.email').attr('required',false);
            $('.password').attr('required',false);
        }
    })

    $('#dealer_type_id').change(function(){
        $(function(){
            var make_user = $('#dealer_type_id').find(':selected').data('make_user');
            if(make_user == 1){
                $('.display').show();
                $('.email').attr('required',true);
                $('.password').attr('required',true);
            }else{
                $('.display').hide();
                $('.email').attr('required',false);
                $('.password').attr('required',false);
            }
        })
    })
</script> --}}
    <script>
        var thisrow = "";
        $(document).on('change','.account_head',function(){
             thisrow = $(this).attr('id');
            if($(this).val() == "addchildaccount"){
                $('.addchildac').modal('show');
            }
            $(".subacc").select2({ dropdownParent: ".addchildac" });
        })

        $('.submitchildac').click(function(){

            $.post('{{route('child_account.store')}}',{
                title:$('input[name="title"]').val(),
                sub_account_id:$('select[name="sub_account_id"]').val(),
                opening_balance:$('input[name="opening_balance"]').val(),
                fiscal_year_id:$('select[name="fiscal_year_id"]').val(),
                behaviour:$('select[name="behaviour"]').val(),
                "_token":"{{csrf_token()}}",

            }).done(function(response){
                $('.account_head').each(function(){
                    $(this).find('option').eq(2).after($('<option class="addedOptionChildac"></option>').val(response.id).text(response.title));
                    if($(this).attr('id') == thisrow){
                        $('.addedOptionChildac').attr('selected','selected');
                    }
                })
                // $(document).find(".account_head option").eq(2).after($('<option></option>').val(response.id).text(response.title));
                $('.addchildac').modal('hide');

            }).fail(function(error){
                $.each(error.responseJSON.errors,function(k,v){
                    var errorhtml = '<p style="color:red">'+v+'</p>';
                    $('.'+k).html(errorhtml);
                })
                console.log(error);
            })
        })

        function addaccountContravoucher(divName) {
            var optionval = $("#headoption").val();
            var row = $("#debtAccVoucher tbody tr").length;
            var count = row + 1;
            var limits = 500;
            var tabin = 0;
            if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
            else {
                var newdiv = document.createElement('tr');
                var tabin = "accountName_" + count;
                var tabindex = count * 2;
                newdiv = document.createElement("tr");
                newdiv.innerHTML = "<td> <select name='child_account_id[]' id='accountName_" + count +
                    "' class='form-control account_head' required></select></td><td><input type='text' name='remarks[]' class='form-control all_remarks' value='' id='remarks_" +
                    count +
                    "' ></td><td><input type='number' step='.01' name='debitAmount[]' class='form-control debitPrice text-right' value='0' id='debitAmount_" +
                    count + "' onkeyup='calculateTotal(" + count +
                    ")' ></td><td><input type='number' step='.01' name='creditAmount[]' class='form-control creditPrice text-right' id='debitAmount1_" +
                    count + "' value='0' onkeyup='calculateTotal(" + count +
                    ")'></td><td style='text-align:center;'><button  class='btn btn-primary icon-btn btn-sm m-auto' type='button'  onclick='deleteRowContravoucher(this)'><i class='fa fa-trash'></i></button></td>";
                document.getElementById(divName).appendChild(newdiv);
                document.getElementById(tabin).focus();
                $("#accountName_" + count).html(optionval);
                count++;
                $(".account_head").select2({
                templateResult: function (data, container) {
                    if (data.element) {
                        $(container).addClass($(data.element).attr("class"));
                    }
                    return data.text;
                    }
                });
            }
        }

        function dbtvouchercalculation(sl) {
            var gr_tot = 0;
            $(".debitPrice").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
            });
            $("#debitTotal").val(gr_tot.toFixed(2, 2));
        }

        function calculateTotal(sl) {
            var gr_tot1 = 0;
            var gr_tot = 0;
            $(".debitPrice").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
            });
            $(".creditPrice").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot1 += parseFloat(this.value))
            });
            $("#debitTotal").val(gr_tot.toFixed(2, 2));
            $("#creditTotal").val(gr_tot1.toFixed(2, 2));

            if ($(".debitPrice").value != 0) {
                $(".creditPrice").attr('disabled');
            }

            if ($("#debitTotal").val() != $("#creditTotal").val()) {
                $('#add_receive').attr('disabled', true);
                $('.toti').removeClass('hide');
            } else {
                $('#add_receive').attr('disabled', false);
                $('.toti').addClass('hide');
            }
        }

        function deleteRowContravoucher(e) {
            var t = $("#debtAccVoucher > tbody > tr").length;
            if (1 == t) alert("There only one row you can't delete.");
            else {
                var a = e.parentNode.parentNode;
                a.parentNode.removeChild(a)
            }
            calculateTotal()
        }
    </script>

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

            let supplierId = "{{ old('vendor_id') }}";
            function fillSelectSuppliers(suppliers) {
                document.getElementById("vendor").innerHTML = '<option value=""> --Select an option-- </option>' +
                    suppliers.reduce((tmp, x) => `${tmp}<option value='${x.id}' ${x.id == supplierId ? 'selected' : ''}>${x.company_name}</option>`, '');
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

            let clientId = "{{ old('client_id') }}";
            function fillSelectClient(clients) {
                document.getElementById("client").innerHTML = '<option value=""> --Select an option-- </option>' +
                    clients.reduce((tmp, x) => `${tmp}<option value='${x.id}'${x.id == clientId ? 'selected' : ''}>${x.name}</option>`, '');
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

            let bankId = "{{ old('bank_id') }}";
            function fillSelectbank_info(bank_info) {
                document.getElementById("bank_info").innerHTML = '<option value=""> --Select option-- </option>' +
                    bank_info.reduce((tmp, x) =>
                        `${tmp}<option value='${x.id}' ${x.id == bankId ? 'selected' : ''}>${x.bank_name} (A/C Holder - ${x.account_name})</option>`, '');
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

            let onlinePortalId = "{{ old('online_portal') }}";
            function fillOnlinePortal(online_portal) {
                document.getElementById("online_portal").innerHTML = '<option value=""> --Select option-- </option>' +
                    online_portal.reduce((tmp, x) =>
                        `${tmp}<option value='${x.id}' ${x.id == onlinePortalId ? 'selected' : ''}>${x.name}</option>`, '');
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

            addaccountContravoucher('debitvoucher');
        };
    </script>

    <script>
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
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
        $(document).on('input', 'input.debitPrice', function() {
            $(this).parent().siblings().find('input.creditPrice').attr('readonly',
                'readonly');
        });
        $(document).on('input', 'input.creditPrice', function() {
            $(this).parent().siblings().find('input.debitPrice').attr('readonly',
                'readonly');
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".account_head").select2({
            templateResult: function (data, container) {
                if (data.element) {
                    $(container).addClass($(data.element).attr("class"));
                }
                return data.text;
                }
            });
        });
        $(document).ready(function() {
            $(".supplier_info").select2();
        });
        $(document).ready(function() {
            $(".client_info").select2();
        });
        // $(document).ready(function() {
        //     $(".bank_info_class").select2();
        // });

        // $(document).ready(function() {
        //     $(".online_portal_class").select2();
        // });
    </script>

    <script>
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
                    name: $("#name").val(),
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
                   var account = '<option value="'+data.child_acc_id+'">'+data.child_acc_title+'-'+data.sub_acc_title+'</option>';
                   $('select[name="child_account_id[]"]').append(account);
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
                    name: $("#fullname").val(),
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

                console.log(clientData);
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
                    var account = '<option value="'+data.child_acc_id+'">'+data.child_acc_title+'-'+data.sub_acc_title+'</option>';
                   $('select[name="child_account_id[]"]').append(account);
                    function fillSelectClients(clients) {
                        document.getElementById("client").innerHTML =
                            '<option value=""> --Select an option-- </option>' +
                            clients.reduce((tmp, x) =>
                                `${tmp}<option value='${x.id}'>${x.name}</option>`, '');
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

            var suppliercodes = @php echo json_encode($allsuppliercodes) @endphp;
            $("#company_code").change(function() {
                var supplierval = $(this).val();
                if ($.inArray(supplierval, suppliercodes) != -1) {
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
        $(function(){
            $('#vendor').change(function(){
                var vendorval = $(this).val();
                if(!vendorval == ''){
                    $('#client').attr('disabled', true);
                }else{
                    $('#client').attr('disabled', false);
                }
            });

            $('#client').change(function(){
                var clientval = $(this).val();
                if(!clientval == ''){
                    $('#vendor').attr('disabled', true);
                }else{
                    $('#vendor').attr('disabled', false);
                }
            });
        })
    </script>
@endpush

@extends('backend.layouts.app')
@push('styles')
    <style>
        form .cabin-data {
            display: none;
        }

        div.card-header {
            background-color: #ebf3fb !important;
        }

        .text-red {
            color: red;
        }

        .lh-0-6 {
            line-height: 0.6 !important;
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
                    <h1>Avaliable Table </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-reservation.index') }}" class="global-btn">View Hotel Table
                            Reservation</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">

                    <div class="col-md-12">
                        <form method="get" autocomplete="off" action="{{ route('hotel-reservation.avaliable_table') }}">



                            {{-- Todo --}}
                            {{-- Filter from: Date Time, to: Date Time --}}

                            <div class="row card-header">
                                <div class="col-md-4">
                                    <div class="input-group mb-0">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text lh-0-6" id="basic-addon1">Start Date Time</span>
                                        </div>
                                        <input autocomplete="off" type="text" id="datetimepicker_start"
                                            name="date_time_start"
                                            value="{{ isset($filterOption)? $filterOption['date_time_start']: (old('date_time_start')? old('date_time_start'): '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group mb-0">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text lh-0-6" id="basic-addon1">End Date Time</span>
                                        </div>
                                        <input autocomplete="off"
                                            value="{{ isset($filterOption) ? $filterOption['date_time_end'] : (old('date_time_end') ? old('date_time_end') : '') }}"
                                            type="text" id="datetimepicker_end" name="date_time_end" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-0">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text lh-0-6" id="basic-addon1">No of People</span>
                                        </div>
                                        <input autocomplete="off" type="number" min="1" name="no_of_person"
                                            value="{{ isset($filterOption) ? $filterOption['no_of_person'] : (old('no_of_person') ? old('no_of_person') : '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="m-0">
                                        <button type="submit" class="btn btn-primary icon-btn"><i
                                                class="fa fa-search"></i> </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 table-responsive mt">
                        <table class="table table-bordered yajra-datatable text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Floor</th>
                                    <th class="text-center">Room</th>
                                    <th class="text-center">Table Name</th>
                                    <th class="text-center">Table No.</th>
                                    <th class="text-center">Cabin</th>
                                    <th class="text-center">Max Capacity</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($avaliableTable as $table)
                                    <tr>
                                        <td>{{ $table->floor ? $table->floor->name : '-' }}</td>
                                        <td>{{ $table->room ? $table->room->name : '-' }}</td>
                                        <td>{{ $table->name }}</td>
                                        <td>{{ $table->code }}</td>
                                        <td>
                                            {{ $table->cabin_type ? $table->cabin_type->name : '-' }}
                                            <br>
                                            <small>{{ $table->cabin_type ? 'Charge: ' . $table->cabin_charge : '' }}</small>
                                        </td>
                                        <td>{{ $table->max_capacity }}</td>
                                        <td>
                                            @switch($table->status)
                                                @case('1')
                                                    <badge class="badge badge-danger">Closed</badge>
                                                @break
                                                @default
                                                    <badge class="badge badge-success">Open</badge>
                                            @endswitch
                                        </td>

                                        <td style="width: 120px;">
                                            <div class="btn-bulk justify-content-center">
                                                <a href='#exampleModal' onclick="bookTable(this)"
                                                    data-id="{{ $table->id }}" data-name="{{ $table->name }}"
                                                    data-code="{{ $table->code }}"
                                                    data-floor="{{ $table->floor ? $table->floor->name : '-' }}"
                                                    data-room="{{ $table->room ? $table->room->name : '-' }}"
                                                    data-cabin-type-name="{{ $table->cabin_type ? $table->cabin_type->name : '-' }}"
                                                    data-cabin-charge="{{ $table->cabin_charge ?? 0 }}"
                                                    data-table-capacity="{{ $table->max_capacity ?? 0 }}"
                                                    data-toggle="modal" data-target="#exampleModal"
                                                    class='edit btn btn-primary
                                                                                                                                                                                    icon-btn btn-sm'
                                                    title='BooK Table'><i class='fa fa-calendar'></i></a>

                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">Table Not Found. Please Filter with Different Time</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content ">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Book Table</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="post" action="{{ route('hotel-reservation.store') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <ul class="list-group">
                                                            <div class="list-group-item card-header text-bold">Table Information
                                                            </div>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                Floor <span id="Floor"> </span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                Room <span id="Room"> </span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                Name <span id="TableName"> </span>
                                                            </li>
                                                            <li id="CabinInfo"
                                                                class="list-group-item d-flex justify-content-between">
                                                                Cabin <span id="Cabin"> </span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                Capacity <span id="TableCapacity"> </span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="hidden" name="id" id="Id">
                                                        <div id="clientAddedMsq"></div>
                                                        <div class="form-group row">
                                                            <label for="client" class="col-sm-3 col-form-label">Select
                                                                Client</label>
                                                            <div class="col-sm-8">
                                                                <select id="client" class="form-control select2"
                                                                    name="client_id" required>
                                                                    <option value=""> -- Select Client -- </option>
                                                                    @foreach ($clients as $client)
                                                                        <option value="{{ $client->id }}">
                                                                            {{ $client->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <a href="#clientadd" class="btn btn-secondary"
                                                                    title="Add New Client" data-toggle="modal"
                                                                    data-target="#clientadd">
                                                                    <i class="fa fa-plus"></i>
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="inputEmail3"
                                                                class="col-sm-3 col-form-label">From</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="date_time_start" class="form-control"
                                                                    id="inputEmail3" placeholder="from time"
                                                                    value="{{ isset($filterOption) ? date('d M Y H:i A', strtotime($filterOption['date_time_start'])) : '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-3 col-form-label">To</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="date_time_end" class="form-control"
                                                                    id="inputEmail3" placeholder="To time"
                                                                    value="{{ isset($filterOption) ? date('d M Y H:i A', strtotime($filterOption['date_time_end'])) : '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-3 col-form-label">Number of
                                                                People</label>
                                                            <div class="col-sm-9">
                                                                <input type="number" min="1" class="form-control"
                                                                    name="number_of_people" id="NOOFPEOPLE"
                                                                    value="{{ isset($filterOption) ? $filterOption['no_of_person'] : '1' }}"
                                                                    placeholder="Number Of People">
                                                            </div>
                                                        </div>

                                                        <fieldset class="form-group">
                                                            <div class="row">
                                                                <label class="col-form-label col-sm-3 pt-0">Payment
                                                                    Method</label>
                                                                <div class="col-sm-9 form-inline">
                                                                    @foreach ($paymentMode as $payment)
                                                                        <div class="form-check pr-2">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="payment_by" id="gridRadios1"
                                                                                value="{{ $payment->id }}" required>
                                                                            <label class="form-check-label" for="gridRadios1">
                                                                                {{ $payment->payment_mode }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="form-group">
                                                            <div class="row">
                                                                <label class="col-form-label col-sm-3 pt-0">Is Paid</label>
                                                                <div class="col-sm-9 form-inline">
                                                                    <div class="form-check pr-2">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="is_paid" id="gridRadios1" value="0" required>
                                                                        <label class="form-check-label" for="gridRadios1">
                                                                            Not Paid
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check disabled">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="is_paid" id="check" value="1" required>
                                                                        <label class="form-check-label" for="gridRadios3">
                                                                            Paid
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <div class="form-group row" id="DateToPaid">
                                                            <label class="col-sm-3 col-form-label">Date To Paid</label>
                                                            <div class="col-sm-9">
                                                                <input type="date" class="form-control" name="date_to_paid"
                                                                    placeholder="Date To Paid">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row" id="Amount">
                                                            <label class="col-sm-3 col-form-label">Amount</label>
                                                            <div class="col-sm-9">
                                                                <input type="number" min="1" dec="2" class="form-control"
                                                                    name="amount" value="{{ old('amount') }}"
                                                                    placeholder="Amount">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputEmail3"
                                                                class="col-sm-3 col-form-label">Status</label>
                                                            <div class="col-sm-9">
                                                                <select name="status" class="form-control" required>
                                                                    <option value=""> -- Select Status -- </option>
                                                                    <option value="0">Free</option>
                                                                    <option value="1">Booked</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Book Now</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->



        <div class='modal fade text-left' id='clientadd' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel'
            aria-hidden='true'>
            <div class='modal-dialog modal-xl' role='document'>
                <div class='modal-content'>
                    <div class='modal-header text-center'>
                        <h2 class='modal-title' id='exampleModalLabel'>Add New Client</h2>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <form action="#" method="POST" id="client_add_form">
                            <div class="card">
                                <div class="card-header">
                                    <p class="card-title">Customer Details</p>
                                </div>
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="client_type">Customer Type<i class="text-danger">*</i></label>
                                                <select name="client_type" id="client_type" class="form-control">
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
                                                <label for="dealer_type_id">Dealer Type<i class="text-danger">*</i></label>
                                                <select name="dealer_type_id" id="dealer_type_id" class="form-control select2">
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
                                                <label for="name">Full Name<i class="text-danger">*</i></label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ old('name') }}" placeholder="Enter Client's Name" id="name">
                                                <p class="text-danger" id="NameError">
                                                    {{ $errors->first('name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Customer Code (Code has to be
                                                    unique)</label>
                                                <input type="text" name="client_code" class="form-control"
                                                    value="{{ $client_code }}" placeholder="Enter Customer Code"
                                                    id="client_code">
                                                <p class="text-danger clientcode_error hide">Code is
                                                    already used. Use Different code.</p>
                                                <p class="text-danger" id="client_codeError">
                                                    {{ $errors->first('client_code') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email">Customer Email</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ old('email') }}" placeholder="Enter Customer Email" id="email">
                                                <p class="text-danger" id="emailError">
                                                    {{ $errors->first('email') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone">Customer Phone</label>
                                                <input type="text" name="phone" class="form-control"
                                                    value="{{ old('phone') }}" placeholder="Enter Customer Contact no."
                                                    id="phone">
                                                <p class="text-danger" id="phoneError">
                                                    {{ $errors->first('phone') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="pan_vat">PAN No./VAT No. (Optional)</label>
                                                <input type="text" name="pan_vat" class="form-control"
                                                    value="{{ old('pan_vat') }}" placeholder="Enter Company PAN or VAT No."
                                                    id="pan_vat">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="province">Province no.</label>
                                                <select name="province" class="form-control client_province select2"
                                                    id="province">
                                                    <option value="">--Select a province--</option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}">
                                                            {{ $province->eng_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger" id="provinceError">
                                                    {{ $errors->first('province') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="district">Districts</label>
                                                <select name="district" class="form-control select2" id="client_district">
                                                    <option value="">--Select a province first--
                                                    </option>
                                                </select>
                                                <p class="text-danger" id="client_districtError">
                                                    {{ $errors->first('district') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="local_address">Customer Local
                                                    Address</label>
                                                <input type="text" name="local_address" class="form-control"
                                                    value="{{ old('local_address') }}" placeholder="Customer Local Address"
                                                    id="local_address">
                                                <p class="text-danger" id="local_addressError">
                                                    {{ $errors->first('local_address') }}
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
                                                <input type="text" name="concerned_name" class="form-control"
                                                    value="{{ old('concerned_name') }}" placeholder="Enter Name"
                                                    id="concerned_name">
                                                <p class="text-danger">
                                                    {{ $errors->first('concerned_name') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="concerned_phone">Phone</label>
                                                <input type="text" name="concerned_phone" class="form-control"
                                                    value="{{ old('concerned_phone') }}" placeholder="Enter Phone"
                                                    id="concerned_phone">
                                                <p class="text-danger">
                                                    {{ $errors->first('concerned_phone') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="concerned_email">Email</label>
                                                <input type="email" name="concerned_email" class="form-control"
                                                    value="{{ old('concerned_email') }}" placeholder="Enter Email"
                                                    id="concerned_email">
                                                <p class="text-danger">
                                                    {{ $errors->first('concerned_email') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="designation">Designation</label>
                                                <input type="text" name="designation" class="form-control"
                                                    value="{{ old('designation') }}" placeholder="Enter Designation"
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
                                <button type="button" id="client_add_button" class="btn btn-secondary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @push('scripts')
        <script>
            $('#check').on('change', function() {
                var val = this.checked ? this.value : '';
                if (val == '') {
                    $('#Amount').hide();
                } else {
                    $('#Amount').show();
                }
            });

            //booking table
            function bookTable(that) {
                let id, floor, room, name, tableNum, cabinTypeName, cabinCharge, capacity = null;
                id = $(that).data('id');
                floor = $(that).data('floor');
                room = $(that).data('room');
                name = $(that).data('name');
                tableNum = $(that).data('code');
                cabinTypeName = $(that).data('cabin-type-name');
                cabinCharge = $(that).data('cabin-charge');
                capacity = $(that).data('table-capacity');

                $('#Id').val(id);
                $('#Floor').html(floor);
                $('#Room').html(room);
                $('#TableName').html(name + '(' + tableNum + ')');
                if ($(that).data('cabin-charge') != 0) {
                    $('#Cabin').html(cabinTypeName + '(Charge : ' + cabinCharge + ')');
                    $('#CabinInfo').css('display', 'block !important');
                } else {
                    $('#CabinInfo').css('display', 'none !important');
                }
                $('#TableCapacity').html(capacity);
                $('#NOOFPEOPLE').attr('max', capacity);
            }




            $(function() {
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

                $("#client_add_button").click(function(event) {
                    var clientData = {
                        client_type: $("#client_type").val(),
                        dealer_type_id: $("#dealer_type_id").val(),
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
                        designation: $("#designation").val()
                    };
                    if (clientData.name == '') {
                        $("#name").css('border-color', 'red');
                        $("#NameError").html('Please Enter Name');
                        return
                    } else {
                        $("#name").css('border-color', '#e1e1e1');
                        $("#NameError").html('');
                    }

                    if (clientData.email == '') {
                        $("#email").css('border-color', 'red');
                        $("#emailError").html('Please Enter Email');
                        return
                    } else {
                        $("#email").css('border-color', '#e1e1e1');
                        $("#emailError").html('');
                    }

                    if (clientData.phone == '') {
                        $("#phone").css('border-color', 'red');
                        $("#phoneError").html('Please Enter Phone');
                        return
                    } else {
                        $("#phone").css('border-color', '#e1e1e1');
                        $("#phoneError").html('');
                    }

                    if (clientData.province == '') {
                        $("#province").css('border-color', 'red');
                        $("#provinceError").html('Please Choose Province');
                        return
                    } else {
                        $("#province").css('border-color', '#e1e1e1');
                        $("#provinceError").html('');
                    }




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
                        $('#clientAddedMsq').html(
                            '<badge role="alert" class="alert  alert-success alert-dismissible fade show">Client Successfully added.' +
                            '<button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button></badge>'
                        );
                        $('#clientadd').modal('toggle');
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
            $(document).ready(function() {
                $(".select2").select2();
            });
        </script>
        <script>
            $(document).ready(function() {
                changePaid();

                $("input[name='is_paid']").change(function() {
                    changePaid();
                });

                function changePaid() {
                    var radioValue = $("input[name='is_paid']:checked").val();
                    if (radioValue == 0) {
                        $('#DateToPaid').show();
                    } else {
                        $('#DateToPaid').hide();
                    }
                }
            });
        </script>
        <script src="{{ asset('backend/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
        <script>
            $(function() {
                $('#datetimepicker_start').datetimepicker({
                    icons: {
                        time: 'fas fa-clock',
                        date: 'fas fa-calendar',
                        up: 'fas fa-arrow-up',
                        down: 'fas fa-arrow-down',
                        previous: 'fas fa-chevron-left',
                        next: 'fas fa-chevron-right',
                        today: 'fas fa-calendar-check-o',
                        clear: 'fas fa-trash',
                        close: 'fas fa-times'
                    }
                });
                $('#datetimepicker_end').datetimepicker({
                    icons: {
                        time: 'fas fa-clock',
                        date: 'fas fa-calendar',
                        up: 'fas fa-arrow-up',
                        down: 'fas fa-arrow-down',
                        previous: 'fas fa-chevron-left',
                        next: 'fas fa-chevron-right',
                        today: 'fas fa-calendar-check-o',
                        clear: 'fas fa-trash',
                        close: 'fas fa-times'
                    },
                    useCurrent: false, //Important! See issue #1075

                });
                $("#datetimepicker_start").on("dp.change", function(e) {
                    $('#datetimepicker_end').data("DateTimePicker").minDate(e.date);
                });
                $("#datetimepicker_end").on("dp.change", function(e) {
                    $('#datetimepicker_start').data("DateTimePicker").maxDate(e.date);
                });
            });
        </script>

    @endpush

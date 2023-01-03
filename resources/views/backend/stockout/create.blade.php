@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Create Stock Out </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('paymentmode.index') }}" class="global-btn">View Stock Out</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('stockout.store') }}" method="POST" class="bg-light p-3">
                                    @csrf
                                    @method('POST')
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="client">Customers: </label>
                                                <div class="row">
                                                    <div class="col-md-10 pr-0">
                                                        <select name="client_id" id="client"
                                                            class="form-control client_info">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2" style="padding-left:7px;">
                                                        <button type="button" data-toggle='modal'
                                                            data-target='#clientadd' data-toggle='tooltip'
                                                            data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                            title="Add New Client"><i
                                                                class="fas fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="stock_out_date">Stock Out Date</label>
                                                <input type="date" name="stock_out_date" class="form-control">
                                                @error('stock_out_date')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="godown_id">Select Godown</label>
                                                <select name="godown_id" id="godown" class="form-control">
                                                    @foreach ($godowns as $godown)
                                                    <option value="{{$godown->id}}" {{$godown->is_default == 1 ? 'selected' : ''}}>{{$godown->godown_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive mt-2">
                                                <table class="table table-bordered table-hover text-center"
                                                    id="product_godown">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="text-center" style="width: 65%;"> Product</th>
                                                            <th class="text-center" style="width: 30%;"> Quantity</th>
                                                            <th class="text-center" style="width: 5%;"> Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="godown_body">
                                                        <tr class="test">
                                                            <td class="" width=" 200px">
                                                                <select name="product_id[]" class="form-control product">
                                                                </select>
                                                                @error('product_id')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="number" name="quantity[]" value
                                                                    placeholder="How Much quantity???"
                                                                    class="form-control godown_quantity text-right"
                                                                    id="quantity_1" onkeyup="calculateTotal(1)">
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-danger red icon-btn btn-sm" type="button"
                                                                    value="Delete" onclick="deletegodownrow(this)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            {{-- <td class="text-right">
                                                                <label for="reason" class="col-form-label">Total</label>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" id="quantityTotal"
                                                                    class="form-control text-right" name="quantityTotal"
                                                                    value="" readonly="readonly" value />
                                                            </td> --}}
                                                            <td></td>
                                                            <td></td>
                                                            <td>
                                                                <a id="add_more" style="margin:auto;" class="btn btn-primary icon-btn btn-sm"
                                                                    name="add_more"
                                                                    onClick="addGodownRow('godown_body')"><i
                                                                        class="fa fa-plus"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Status: </label>
                                                <input type="radio" name="status" value="1" checked> Approve
                                                <input type="radio" name="status" value="0"> Unapprove
                                                @error('percent')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button type="submit" style="margin-left:auto;" class="btn btn-primary btn-sm">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='modal fade text-left' id='clientadd' tabindex='-1' role='dialog'
                                        aria-labelledby='exampleModalLabel' aria-hidden='true'>
                    <div class='modal-dialog' role='document' style="max-width: 1000px;">
                        <div class='modal-content'>
                            <div class='modal-header text-center'>
                                <h2 class='modal-title' id='exampleModalLabel'>Add New Client</h2>
                                <button type='button' class='close' data-dismiss='modal'
                                    aria-label='Close'>
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
                                                        <label for="name">Full Name<i
                                                                class="text-danger">*</i></label>
                                                        <input type="text" name="name"
                                                            class="form-control"
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
                                                            placeholder="Enter Customer Code"
                                                            id="client_code">
                                                        <p class="text-danger clientcode_error hide">
                                                            Code is already used. Use Different code.
                                                        </p>
                                                        <p class="text-danger">
                                                            {{ $errors->first('client_code') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">Customer Email</label>
                                                        <input type="email" name="email"
                                                            class="form-control"
                                                            value="{{ old('email') }}"
                                                            placeholder="Enter Customer Email"
                                                            id="email">
                                                        <p class="text-danger">
                                                            {{ $errors->first('email') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="phone">Customer Phone</label>
                                                        <input type="text" name="phone"
                                                            class="form-control"
                                                            value="{{ old('phone') }}"
                                                            placeholder="Enter Customer Contact no."
                                                            id="phone">
                                                        <p class="text-danger">
                                                            {{ $errors->first('phone') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="pan_vat">PAN No./VAT No.
                                                            (Optional)</label>
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
                                                            class="form-control client_province"
                                                            id="province">
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

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="district">Districts</label>
                                                        <select name="district"
                                                            class="form-control client_district"
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
                                                            placeholder="Enter Name"
                                                            id="concerned_name">
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
                                                            placeholder="Enter Phone"
                                                            id="concerned_phone">
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
                                                            placeholder="Enter Email"
                                                            id="concerned_email">
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
                                        <button type="submit" class="btn btn-secondary btn-sm">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
<script>
    function addGodownRow(divName) {
        var optionval = $("#headoption").val();
        var row = $("#product_godown tbody tr").length;
        var count = row + 1;
        var limits = 500;
        if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
        else {
            var newdiv = document.createElement('tr');
            var tabindex = count * 2;
            newdiv = document.createElement("tr");
            newdiv.innerHTML = "<td><select name='product_id[]' class='form-control product' required></select></td><td><input type='number' name='quantity[]' class='form-control godown_quantity text-right' value placeholder='How Much Quantity???' id='quantity_" +
                count + "' onkeyup='calculateTotal(" + count +
                ")' ></td><td><button  class='btn btn-danger icon-btn btn-sm red' type='button'  onclick='deletegodownrow(this)'><i class='fa fa-trash'></i></button></td>";
            document.getElementById(divName).appendChild(newdiv);
            $("#goDown_" + count).html(optionval);
            count++;
            $("select.form-control:not(.dont-select-me)").select2({
                // placeholder: "--Select One--",
                // allowClear: true
            });
        }
    }
    function dbtvouchercalculation(sl) {
        var gr_tot = 0;
        $(".godown_stock").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });
        $("#quantityTotal").val(gr_tot.toFixed(0, 2));
    }
    function calculateTotal(sl) {
        var gr_tot1 = 0;
        var gr_tot = 0;
        $(".godown_stock").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });
        $(".creditPrice").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot1 += parseFloat(this.value))
        });
        $("#quantityTotal").val(gr_tot.toFixed(2, 2));
        $("#creditTotal").val(gr_tot1.toFixed(2, 2));
        if ($(".godown_stock").value != 0) {
            $(".creditPrice").attr('disabled');
        }
    }
    function deletegodownrow(e) {
        var t = $("#product_godown > tbody > tr").length;
        if (1 == t) alert("There only one row you can't delete.");
        else {
            var a = e.parentNode.parentNode;
            a.parentNode.removeChild(a)
        }
        calculateTotal()
    }


</script>

<script>
    $(function(){
        var selgodown = $('#godown').find(":selected").val();
        var warehouses = @php echo  json_encode($godowns); @endphp;
        var warehousecount = warehouses.length;
        function wareproducts() {
            for (let i = 0; i < warehousecount; i++) {
                if (warehouses[i].id == selgodown) {
                    var godownpros = warehouses[i].godownproduct;
                    var gdpcount = godownpros.length;
                    var prodoptions = '';
                    for (let s = 0; s < gdpcount; s++) {
                        var stock = godownpros[s].stock + ' ' + godownpros[s].product.primary_unit;
                        var proname = godownpros[s].product.product_name;
                        var rate = godownpros[s].product.product_price;
                        // var primary_unit = godownpros[s].product.primary_unit;
                        var procode = godownpros[s].product.product_code;
                        var proid = godownpros[s].product_id;
                        prodoptions += `<option value="${proid}"
                                        data-rate="${rate}"
                                        data-stock="${stock}">
                                        ${proname}(${procode})
                                    </option>`;
                    }
                }
            }
            return prodoptions;
        }
        $('.product').html(wareproducts());
    });
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
                    var prodoptions = '';
                    for (let s = 0; s < gdpcount; s++) {
                        var stock = godownpros[s].stock + ' ' + godownpros[s].product.primary_unit;
                        var proname = godownpros[s].product.product_name;
                        var rate = godownpros[s].product.product_price;
                        // var primary_unit = godownpros[s].product.primary_unit;
                        var procode = godownpros[s].product.product_code;
                        var proid = godownpros[s].product_id;
                        prodoptions += `<option value="${proid}"
                                        data-rate="${rate}"
                                        data-stock="${stock}">
                                        ${proname}(${procode})
                                    </option>`;
                    }
                }
            }
            return prodoptions;
        }
        $('.product').html(wareproducts());
        return 1;
    })
    $("#add_more").click(function(){
        var selgodown = $('#godown').find(":selected").val();
        var warehouses = @php echo  json_encode($godowns); @endphp;
        var warehousecount = warehouses.length;
        // console.log(warehouses);
        function wareproducts() {
            for (let i = 0; i < warehousecount; i++) {
                if (warehouses[i].id == selgodown) {
                    var godownpros = warehouses[i].godownproduct;
                    var gdpcount = godownpros.length;
                    var prodoptions = '';
                    for (let s = 0; s < gdpcount; s++) {
                        var stock = godownpros[s].stock;
                        var proname = godownpros[s].product.product_name;
                        var rate = godownpros[s].product.product_price;
                        // var primary_unit = godownpros[s].product.primary_unit;
                        var procode = godownpros[s].product.product_code;
                        var proid = godownpros[s].product_id;
                        prodoptions += `<option value="${proid}"
                                        data-rate="${rate}"
                                        data-stock="${stock}">
                                        ${proname}(${procode})
                                    </option>`;
                    }
                }
            }
            return prodoptions;
        }
        $('.product').html(wareproducts());
        return 1;
    })


    function fillSelectClient(clients) {
        document.getElementById("client").innerHTML = '<option value=""> --Select an option-- </option>' +
            clients.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.name}</option>`, '');
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

    $(document).ready(function() {
            $("#client_add_form").submit(function(event) {
                var clientData = {
                    client_type: $("#client_type").val(),
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

        $(document).ready(function() {
            $(".client_info").select2();
            $(".product").select2();
        });
</script>
@endpush

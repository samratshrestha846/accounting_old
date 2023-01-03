@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Transfer from Warehouse to POS </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('outlettransfer.index') }}" class="global-btn">View Transfers</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{route('outlettransfer.store')}}" method="POST" class="">
                                    @csrf
                                    @method('POST')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="transfer_nep_date">Entry Date (B.S)<i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" name="transfer_nep_date" id="entry_date_nepali"
                                                    class="form-control" value="{{ old('transfer_nep_date') }}">
                                                @error('transfer_nep_date')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="english_date">Entry Date (A.D)<i
                                                        class="text-danger">*</i>:</label>
                                                <input type="date" name="transfer_eng_date" id="english" class="form-control"
                                                    value="{{ old('transfer_eng_date', date('Y-m-d')) }}" readonly="readonly">
                                                @error('transfer_eng_date')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="godown">Godown: </label>
                                                <select name="godown_id" class="form-control godown" id="godown">
                                                    <option value="">--Select Option--</option>
                                                    @foreach ($godowns as $godown)
                                                        <option value="{{$godown->id}}"{{ old('godown_id') == $godown->id ? 'selected' : ''}}>{{$godown->godown_name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('godown_id')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="outlet">Outlet: </label>
                                                <select name="outlet_id" class="form-control outlet" id="outlet">
                                                    <option value="">--Select Option--</option>
                                                    @foreach ($outlets as $outlet)
                                                        <option value="{{$outlet->id}}"{{ old('outlet_id') == $outlet->id ? 'selected' : ''}}>{{$outlet->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('outlet_id')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mt-2">
                                            <h4 class="text-left">
                                                Transferred Product Information
                                            </h4>
                                            <div class="table-responsive mt-2">
                                                <table class="table table-bordered table-hover text-center"
                                                    id="product_godown">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="text-center" style="width: 45%;"> Product</th>
                                                            <th class="text-center" style="width: 25%;"> Stock Alert</th>
                                                            {{-- <th class="text-center" style="width: 20%;"> Seconday Stock Alert</th> --}}
                                                            <th class="text-center" style="width: 25%;"> Stock</th>
                                                            <th class="text-center" style="width: 5%;"> Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="godown_body">
                                                        {{-- <tr class="test">
                                                            <td>
                                                                <select name="product_id[]" id="product_1" class="form-control product">
                                                                    <option value="">--Select a godown first--</option>
                                                                </select>
                                                                @error('product_id')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="number" name="primary_alert[]" value
                                                                    placeholder="Primary Alert Stock???"
                                                                    class="form-control text-right"
                                                                    id="primary_alert_1" >
                                                            </td>
                                                            <td>
                                                                <input type="number" name="secondary_alert[]" value
                                                                    placeholder="Secondary Alert Stock???"
                                                                    class="form-control text-right"
                                                                    id="secondary_alert_1" >
                                                            </td>
                                                            <td>
                                                                <input type="number" name="stock[]" value
                                                                    placeholder="How Much Stock???"
                                                                    class="form-control godown_stock text-right"
                                                                    id="stock_1" onkeyup="calculateTotal(1)">
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-primary red btn-sm" type="button"
                                                                    value="Delete" onclick="deletegodownrow(this)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr> --}}
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            {{-- <td></td> --}}
                                                            <td></td>
                                                            <td class="text-right">
                                                                <label for="reason" class="col-form-label">Total</label>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" id="stockTotal"
                                                                    class="form-control text-right" name="stockTotal"
                                                                    value="" readonly="readonly" value />
                                                            </td>
                                                            <td>
                                                                <a id="add_more" class="btn btn-primary icon-btn btn-sm m-auto"
                                                                    name="add_more"
                                                                    onClick="addGodownRow('godown_body')"><i
                                                                        class="fa fa-plus"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="remarks">Remarks</label>
                                                <textarea name="remarks" id="10" class="form-control" cols="30" rows="5" placeholder="Remarks....">{{ old('remarks') }}</textarea>
                                                @error('remarks')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary btn-sm ml-auto">Submit</button>
                                        </div
                                    </div>
                                </form>
                            </div>
                        </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('scripts')
<script>
    window.onload = function() {
        addGodownRow('godown_body');
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

        $('.submitSerialNumber').click(function() {
            let dataId = $(this).attr('data-id');
            alert(dataId);
        });
    };
</script>

<script>
    var warehouses = @php echo  json_encode($godowns); @endphp;
    function addGodownRow(divName) {
        var selgodown = $('#godown').find(":selected").val();
        var row = $("#product_godown tbody tr").length;
        var count = row + 1;
        var limits = 500;
        var tabin = 0;
        if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
        else {
            var newdiv = document.createElement('tr');
            var tabin = "product_" + count;
            var tabindex = count * 2;
            let serialModelHtml = serialModelForm(count);
            newdiv.innerHTML = `<td>
                                    <select name='product_id[]' id='product_${count}' class='form-control product' onchange=product_change(${count})>
                                        <option value=''>--Select godown first--</option>
                                    </select>
                                </td>
                                <td>
                                    <input type='number' name='primary_alert[]' class='form-control text-right' value placeholder='Alert' id='primary_alert_${count}' >
                                </td>
                                <td>
                                    <input type='number' name='stock[]' class='form-control godown_stock text-right' value placeholder='Stock' id='stock_${count}' onkeyup='calculateTotal(${count})'>
                                    ${serialModelHtml}
                                </td>
                                <td>
                                    <button  class='btn btn-primary btn-sm icon-btn m-auto' type='button'  onclick='deletegodownrow(this)'><i class='fa fa-trash'></i></button>
                                </td>`
                        ;

            document.getElementById(divName).appendChild(newdiv);
            $("select.form-control:not(.dont-select-me)").select2({});


            if (selgodown > 0)
            {
                var warehousecount = warehouses.length;
                function godownProducts() {
                    for (let i = 0; i < warehousecount; i++) {
                        if (warehouses[i].id == selgodown) {
                            var godownpros = warehouses[i].godownproduct;
                            var gdpcount = godownpros.length;

                            var prodoptions = '<option value="">--Select a product--</option>';

                            for (let s = 0; s < gdpcount; s++) {
                                var proname = godownpros[s].product.product_name;
                                var procode = godownpros[s].product.product_code;
                                var proid = godownpros[s].product_id;
                                prodoptions += `<option value="${proid}">
                                                ${proname} (${procode})
                                            </option>`;
                            }

                        }
                    }
                    document.getElementById("product_"+count).innerHTML = prodoptions;
                }
                godownProducts();

            }
        }
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
        $("#stockTotal").val(gr_tot.toFixed(2, 2));
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

    $(document).ready(function() {
        $(".godown").select2();
        $(".outlet").select2();
        $(".product").select2();
        $('.serial_numbers').select2({
                placeholder: '--Select Godown First--',
                allowClear: true
            });
    });

    function product_change(number)
    {
        var value = document.getElementById("product_"+number).value;
        var selgodown = $('#godown').find(":selected").val();

        function fetchProduct(value) {
            var uri = "{{ route('apiproduct', ':no') }}";
            uri = uri.replace(':no', value);
            $.ajax({
                url: uri,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var product = response;
                    var stock_input = document.getElementById("stock_"+number);
                    stock_input.value = "0";
                    if (product.has_serial_number == 1)
                    {
                        stock_input.setAttribute("data-toggle", "modal");
                        stock_input.setAttribute("data-target", "#stock_add_"+number);
                        var serial_number_body = document.getElementById("serial_numbers_"+number);
                        serial_number_body.innerHTML = '';
                        // initializedSelectSerialNumberEvent();

                        var serial_numbers_uri = "{{ route('serialNumbers', [':godown_id', ':product_id']) }}";
                        serial_numbers_uri = serial_numbers_uri.replace(':godown_id', selgodown).replace(':product_id', value);
                        $.ajax({
                            url: serial_numbers_uri,
                            type: 'get',
                            dataType: 'json',
                            success: function(response) {
                                var serialNumbers = response;
                                serial_number_body.innerHTML = serialNumbers.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.serial_number}</option>`, '');
                                initializedSelectSerialNumberEvent();
                            }
                        });
                    }
                    else if(product.has_serial_number == 0)
                    {
                        stock_input.setAttribute("data-toggle", "none");
                        stock_input.setAttribute("data-target", "none");
                    }
                }
            });
        }
        fetchProduct(value);
    }

    function serialModelForm(count)
    {
        return `<div class='modal fade text-left' id='stock_add_${count}' data-id='${count}' tabindex='-1' role='dialog'
            aria-labelledby='exampleModalLabel' aria-hidden='true'>
            <div class='modal-dialog' role='document' style='max-width: 600px;'>
                <div class='modal-content'>
                    <div class='modal-header text-center'>
                        <h2 class='modal-title' id='exampleModalLabel'>Choose serial numbers to transfer</h2>
                        <button type='button' class='close' data-dismiss='modal'
                            aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body' id='modal_body_${count}'>
                        <select name="serial_numbers_${count}[]" class="form-control serial_numbers" id="serial_numbers_${count}" data-id="${count}" multiple="multiple">
                        </select>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>`;
    }

    function initializedSelectSerialNumberEvent() {

        $('.serial_numbers').on('select2:close', function (e) {

            let dataId= $(this).attr('data-id');
            var uldiv = $(this).siblings('span.select2').find('ul')
            var count = $(this).select2('data').length

            $('#stock_'+dataId).val(count);
            calculateTotal(count);
        });
    }

</script>

<script>
    $('#godown').change(function() {
        var selgodown = $('#godown').find(":selected").val();

        var warehouses = @php echo  json_encode($godowns); @endphp;
        var warehousecount = warehouses.length;

        function wareproducts() {
            for (let i = 0; i < warehousecount; i++) {
                if (warehouses[i].id == selgodown) {
                    var godownpros = warehouses[i].godownproduct;
                    var gdpcount = godownpros.length;

                    var prodoptions = '<option value="">--Select a product--</option>';
                    for (let s = 0; s < gdpcount; s++)
                    {
                        var proname = godownpros[s].product.product_name;
                        var procode = godownpros[s].product.product_code;
                        var proid = godownpros[s].product_id;
                        prodoptions += `<option value="${proid}">
                                        ${proname} (${procode})
                                    </option>`;
                    }

                }
            }
            return prodoptions;
        }
        $('.product').html(wareproducts());
        return 1;
    })
</script>
@endpush

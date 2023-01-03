@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
    <style>
        .submit.disabled {
            pointer-events: none;
            cursor: default;
            text-decoration: none;
            color: black;
        }
        .off{
            display: none;
        }
    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Add Stock of {{$product->product_name}}</h1>
                    <a href="javascript:void(0)" onclick="goBack()" class="global-btn">Go Back</a>
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-12">
                            <form  id="productForm" action="{{route('addstock.store')}}" method="POST">
                                @csrf
                                @method('POST')
                                <input type="checkbox" name="check_serial_number" id="serial_number" {{$product->has_serial_number == 1 ? 'checked' : ''}} onchange="updateTableRow(this)" style="display:none;">

                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                <div class="col-md-12 mt-4 mb-4">
                                    <hr>
                                    <h4 class="text-center">
                                        Godown To Product Information
                                    </h4>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-center"
                                            id="product_godown">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-center" style="width: 55%;"> Godown</th>
                                                    <th class="text-center" style="width: 40%;"> Stock</th>
                                                    <th class="text-center" style="width: 5%;"> Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="godown_body">
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td class="text-right">
                                                        <label for="reason" class="col-form-label">Total</label>
                                                    </td>
                                                    <td class="text-right">
                                                        <input type="text" id="stockTotal"
                                                            class="form-control text-right" name="stockTotal"
                                                            value="" readonly="readonly" value />
                                                    </td>
                                                    <td>
                                                        <a id="add_more" class="btn btn-primary iocn-btn"
                                                            name="add_more"
                                                            onClick="addGodownRow('godown_body')"><i
                                                                class="fa fa-plus"></i></a>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <hr>
                                </div>
                                 <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                        <input type="hidden" id="headoption" value="<option value=''>--Select an option--</option>
                                        @foreach ($godowns as $godown)
                    <option value='{{ $godown->id }}'>{{ $godown->godown_name }}</option>
                    @endforeach"
                    name="">
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script>
        function goBack() {
           window.history.back();
        }
    </script>
    <script>
        function addGodownRow(divName) {
            let checkedSerialNumber = $("input[name='check_serial_number']").is(":checked");
            var optionval = $("#headoption").val();
            var row = $("#product_godown tbody tr").length;
            var count = row + 1;
            var limits = 500;
            var tabin = 0;
            if (count == limits)
            {
                alert("You have reached the limit of adding " + count + " inputs");
            }
            else
            {
                var newdiv = document.createElement('tr');
                var tabin = "goDown_" + count;
                var tabindex = count * 2;
                let serialModelHtml = serialModelForm(count);
                let dataToggleHtml = checkedSerialNumber ? `data-toggle='modal' data-target="#stock_add_${count}"` : '';
                newdiv = document.createElement("tr");
                newdiv.setAttribute('data-id', count);
                newdiv.innerHTML = `<td>
                                        <select name='godown[]' id='goDown_${count}' class='form-control godown' required>
                                        </select>
                                    </td>
                                    <td>
                                        <input type='number' name='stock[]' class='form-control godown_stock text-right' value placeholder='How Much Stock???' id='stock_${count}' onkeyup='calculateTotal(${count})' ${dataToggleHtml} step=".01">
                                        ${serialModelHtml}
                                    </td>
                                    <td>
                                        <button  class='btn btn-primary icon-btn btn-sm' type='button' data-id='${count}'  onclick='deletegodownrow(this)'>
                                            <i class='fa fa-trash'></i>
                                        </button>
                                    </td>`;

                document.getElementById(divName).appendChild(newdiv);
                document.getElementById(tabin).focus();
                $("#goDown_" + count).html(optionval);
                count++;
                $("select.form-control:not(.dont-select-me)").select2({
                    // placeholder: "--Select One--",
                    // allowClear: true
                });
            }

            initializedSerialNumberModalEvent();
            initializedSerialNumberClickEvent();

            $('input.godown_stock').each((index, value) => {
                let element = $(value);

                let hasModal = $('#stock_add_'+(index+1))[0] ? true : false;

                // element.val(0);

                if(!checkedSerialNumber) {
                    element.attr('data-toggle','none');
                    element.attr('data-target','none');
                    element.attr('readonly', false);
                    $('#stock_add_'+(index+1)).remove();
                } else {
                    element.attr('data-toggle','modal');
                    element.attr('data-target','#stock_add_'+(index+1));
                    element.attr('readonly', true);


                    if(!hasModal) {
                        element.after(serialModelForm(index+1));
                    }
                }
            });
        }

        function dbtvouchercalculation(sl) {
            var gr_tot = 0;
            $(".godown_stock").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
            });
            $("#stockTotal").val(gr_tot.toFixed(0, 2));
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

            let dataId = $(e).attr('data-id');
            var t = $("#product_godown > tbody > tr").length;
            if (1 == t) alert("There only one row you can't delete.");
            else {
                var a = e.parentNode.parentNode;
                a.parentNode.removeChild(a)
            }

            $("input[name='serial_numbers_"+dataId+"[]']").remove();
            calculateTotal()
        }

        function updateTableRow(event) {
            let checkedSerialNumber = $(event).is(":checked");

            $('input.godown_stock').each((index, value) => {
                let element = $(value);

                let hasModal = $('#stock_add_'+(index+1))[0] ? true : false;

                // element.val(0);

                if(!checkedSerialNumber) {
                    element.attr('data-toggle','none');
                    element.attr('data-target','none');
                    element.attr('readonly', false);
                    $('#stock_add_'+(index+1)).remove();
                } else {
                    element.attr('data-toggle','modal');
                    element.attr('data-target','#stock_add_'+(index+1));
                    element.attr('readonly', true);


                    if(!hasModal) {
                        element.after(serialModelForm(index+1));
                    }
                }
            });
        }

        function serialModelForm(count) {

            return `<div class='modal fade text-left serial-number__modal' id='stock_add_${count}' data-id='${count}' tabindex='-1' role='dialog'
                aria-labelledby='exampleModalLabel' aria-hidden='true'>
                <div class='modal-dialog' role='document' style='max-width: 600px;'>
                    <div class='modal-content'>
                        <div class='modal-header text-center'>
                            <h2 class='modal-title' id='exampleModalLabel'>Enter serial number</h2>
                            <button type='button' class='close' data-dismiss='modal'
                                aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <div class='modal-body' id='modal_body_${count}'>
                            <div class='row serial_number_list'>
                                <div class='col-md-9'>
                                    <input type='text' class='form-control input__serial-number' name='serial_numbers_${count}[]' placeholder='Write a serial number'>
                                </div>
                            </div>

                            <div class='mt-2'>
                                <a href='' class='btn btn-primary icon-btn btn-sm' title='New Serial Number' onclick='addNewModalRow(${count})'><i class='fas fa-plus'></i></a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-id='${count}' class="btn btn-primary submitSerialNumber">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>`
        }

        function addNewModalRow(count, value)
        {
            value = value ? value : '';
            event.preventDefault();
            var x = document.getElementById("modal_body_"+count);
            var new_row = document.createElement("div");
            new_row.className = `row serial_number_list`;
            new_row.innerHTML = `
                                <div class='col-md-9 mt-3'>
                                    <input type='text' class='form-control input__serial-number' value='${value}' placeholder='Write a serial number'>
                                </div>
                                <div class='col-md-3 mt-3 pl-0'>
                                        <button  class='btn btn-primary' type='button' onclick='removeDom(this,".serial_number_list")'>
                                            <i class='fa fa-trash'></i>
                                        </button>
                                </div>
                                `;
            var pos = x.childElementCount + 1;
            x.insertBefore(new_row, x.childNodes[pos]);
        }

        //watch for serial_number model open
        function initializedSerialNumberModalEvent() {
            $('.serial-number__modal').on('show.bs.modal', function (event) {
                var modal = $(this);
                let dataId = modal.attr('data-id');

                $(modal).find('.serial_number_list').remove();
                $('#productForm').find("input[name='serial_numbers_"+dataId+"[]']").each((index,value) => {
                    addNewModalRow(dataId, $(value).val());
                });
            })
        }

        //remove dom element
        function removeDom(event,element)
        {
            $(event).parents(element).remove();
        }

        function initializedSerialNumberClickEvent() {
            //submit serial_number button
            $('.submitSerialNumber').click(function() {

                let dataId = $(this).attr('data-id');
                let hasErrors = false;
                let serialNumbers = [];
                let inputSerialNumberHtml = '';

                $(this).parents('#stock_add_' + dataId).find('.input__serial-number').each((index, value)  => {

                    let inputValue = $(value).val();

                    console.log("i am ", serialNumbers.includes(inputValue));
                    console.log("valeu is ", inputValue);

                    if(serialNumbers.includes(inputValue)){
                        hasErrors = true;
                        $(value).after(`<div class='invalid-error text-danger'><span>Serial Number already exist</span></div>`);
                    }

                    inputSerialNumberHtml += `<input type="hidden" class="hidden__serial-number" name="serial_numbers_${dataId}[]" value="${inputValue}" readonly>`;

                    //if the value is not empty
                    if(inputValue){
                        serialNumbers.push(inputValue);
                    }
                });

                if(hasErrors) return;

                $("input[name='serial_numbers_"+dataId+"[]']").remove();
                $("#stock_"+dataId).val(serialNumbers.length);
                $('#productForm').append(inputSerialNumberHtml)
                calculateTotal(dataId);
                $('#stock_add_'+dataId).modal('hide');
            });
        }

    </script>

    <script>
        $(document).ready(function() {
            $(".godown").select2();
            addGodownRow('godown_body');

            let checkedSerialNumber = $("input[name='check_serial_number']").is(":checked");

            $('input.godown_stock').each((index, value) => {
                let element = $(value);

                let hasModal = $('#stock_add_'+(index+1))[0] ? true : false;

                // element.val(0);

                if(!checkedSerialNumber) {
                    element.attr('data-toggle','none');
                    element.attr('data-target','none');
                    element.attr('readonly', false);
                    $('#stock_add_'+(index+1)).remove();
                } else {
                    element.attr('data-toggle','modal');
                    element.attr('data-target','#stock_add_'+(index+1));
                    element.attr('readonly', true);


                    if(!hasModal) {
                        element.after(serialModelForm(index+1));
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#unit_store_form").submit(function(event) {

                var formData = {
                    unit: $("#unit").val(),
                    short_form: $("#short_form").val(),
                    unit_code: $("#unit_code").val(),
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('post.apiunits') }}",
                    data: formData,
                    dataType: "json",
                    encode: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {

                    function fillSelectunits(units) {
                        document.getElementById("primary_unit").innerHTML =
                            '<option value=""> -Primary unit- </option>' +
                            units.reduce((tmp, x) =>
                                `${tmp}<option value='${x.id}'>${x.unit}(${x.short_form} - ${x.unit_code})</option>`,
                                '');

                        document.getElementById("secondary_unit").innerHTML =
                            '<option value=""> -Secondary unit- </option>' +
                            units.reduce((tmp, x) =>
                                `${tmp}<option value='${x.id}'>${x.unit}(${x.short_form} - ${x.unit_code})</option>`,
                                '');
                    }

                    function fetchunits() {
                        $.ajax({
                            url: "{{ route('apiunits') }}",
                            type: 'get',
                            dataType: 'json',
                            success: function(response) {
                                var units = response;
                                fillSelectunits(units);
                            }
                        });
                    }
                    fetchunits();
                    $("#unit_store_form").html(
                        '<div class="alert alert-success">Successfully added.</div>'
                    );
                });
                event.preventDefault();
            });
        });
    </script>
@endpush

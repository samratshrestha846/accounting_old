@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Edit Journal Voucher </h1>
                    <div class="bulk-btn">
                        <a href="{{ route('journals.index') }}" class="global-btn">Exhibit
                            Vouchers
                        </a>
                    </div>
                </div>
            </div>
        </div>

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
                                    <form action="{{ route('journals.update', $journalVouchers->id) }}" id="validate"
                                        enctype="multipart/form-data" method="POST">
                                        @csrf
                                        @method("PUT")
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="journal_voucher_no">Voucher No<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="text" name="journal_voucher_no" id="voucher_no"
                                                        value="{{ $journalVouchers->journal_voucher_no }}"
                                                        class="form-control" readonly required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="entry_date">Entry Date (B.S)<i class="text-danger">*</i>:</label>
                                                    <input type="text" name="entry_date" id="entry_date_nepali"
                                                        class="form-control datepicker"
                                                        value="{{ $journalVouchers->entry_date_nepali }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="english_date">Entry Date (A.D)<i class="text-danger">*</i>:</label>
                                                    <input type="date" name="english_date" id="english"
                                                        class="form-control" value="{{ $journalVouchers->entry_date_english }}" readonly="readonly">
                                                </div>
                                            </div>


                                            @if (Auth::user()->can('view-journals'))
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="status">Status<i class="text-danger">*</i> :</label><br>
                                                        <input type="radio" name="status" value="1"
                                                            {{ $journalVouchers->status == 1 ? 'checked' : '' }}> Approved
                                                        <input type="radio" name="status" value="0"
                                                            {{ $journalVouchers->status == 0 ? 'checked' : '' }}> To be Approved
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="status">Status<i class="text-danger">*</i>: </label><br>
                                                        <input type="radio" name="status" value="1"
                                                            {{ $journalVouchers->status == 1 ? 'checked' : '' }} disabled> Approved
                                                        <input type="radio" name="status" value="0"
                                                            {{ $journalVouchers->status == 0 ? 'checked' : '' }} disabled> To be Approved
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="status">Suppliers: </label>
                                                    <select name="vendor_id" id="vendor" class="form-control supplier_info" @if ($journalVouchers->vendor_id == null) disabled @endif>
                                                        <option value="">--Select Option--</option>
                                                        @foreach ($vendors as $vendor)
                                                            <option value="{{$vendor->id}}" {{$journalVouchers->vendor_id == $vendor->id ? 'selected' : ''}}>{{$vendor->company_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="client">Customers: </label>
                                                    <select name="client_id" id="client" class="form-control client_info" @if ($journalVouchers->client_id == null) disabled @endif>
                                                        <option value="">--Select Option--</option>
                                                        @foreach ($clients as $client)
                                                            <option value="{{$client->id}}" {{$client->id == $journalVouchers->client_id ? "selected" : ''}}>{{$client->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="Payment Type">Payment Method:</label>
                                                    <select name="payment_method" class="form-control payment_method">
                                                        <option value="">--Select One--</option>
                                                        @foreach ($payment_methods as $method)
                                                            <option value="{{ $method->id }}"{{ $method->id == $journalVouchers->payment_method ? 'selected' : '' }}>{{ $method->payment_mode }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="receipt_payment">Receipt / Payment:</label>
                                                    <select name="receipt_payment" class="form-control">
                                                        <option value="">--Select an option--</option>
                                                        <option value="0" {{ $journalVouchers->receipt_payment == 0 ? 'selected' : '' }}>Receipt</option>
                                                        <option value="1" {{ $journalVouchers->receipt_payment == 1 ? 'selected' : '' }}>Payment</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2" id="online_payment" @if ($journalVouchers->online_portal_id == null) style="display: none;" @endif>
                                                <div class="form-group">
                                                    <label for="Bank">Select a portal:</label>
                                                    <div class="row">
                                                        <select name="online_portal" class="form-control" id="online_portal">
                                                            <option value="">--Select a portal--</option>
                                                            @foreach ($online_portals as $portal)
                                                                <option value="{{ $portal->id }}" @if ($journalVouchers->online_portal_id != null) {{ $portal->id == $journalVouchers->online_portal_id ? 'selected' : '' }} @endif>{{ $portal->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2" id="customer_portal_id" @if ($journalVouchers->customer_portal_id == null) style="display: none;" @endif>
                                                <div class="form-group">
                                                    <label for="">Portal Id:</label>
                                                    <input type="text" class="form-control" placeholder="Portal Id"
                                                        name="customer_portal_id" @if ($journalVouchers->customer_portal_id != null) value="{{ $journalVouchers->customer_portal_id }}" @endif>
                                                </div>
                                            </div>

                                            @if ($journalVouchers->bank_id == null)
                                                <div class="col-md-2" id="bank" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="Bank">From Bank:</label>
                                                        <select name="bank_id" class="form-control" id="bank_info">
                                                            <option value="">--Select a bank--</option>
                                                            @foreach ($banks as $bank)
                                                                <option value="{{ $bank->id }}">{{ $bank->bank_name }} ({A/C Holder - {{ $bank->account_name }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-2" id="bank">
                                                    <div class="form-group">
                                                        <label for="Bank">From Bank:</label>
                                                        <select name="bank_id" class="form-control" id="bank_info">
                                                            <option value="">--Select a bank--</option>
                                                            @foreach ($banks as $bank)
                                                                <option value="{{ $bank->id }}"{{ $bank->id == $journalVouchers->bank_id ? 'selected' : '' }}>{{ $bank->bank_name }} (A/C Holder - {{ $bank->account_name }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($journalVouchers->cheque_no == null)
                                                <div class="col-md-2" id="cheque" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="cheque no">Cheque no.:</label>
                                                        <input type="text" class="form-control" placeholder="Cheque No." name="cheque_no">
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-2" id="cheque">
                                                    <div class="form-group">
                                                        <label for="cheque no">Cheque no.:</label>
                                                        <input type="text" class="form-control" placeholder="Cheque No." name="cheque_no" value="{{ $journalVouchers->cheque_no }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <hr>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="debtAccVoucher">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center"> Account Name<i class="text-danger">*</i>
                                                        </th>
                                                        {{-- <th class="text-center"> Code<i class="text-danger">*</i></th> --}}
                                                        <th class="text-center"> Remarks</th>
                                                        <th class="text-center"> Debit</th>
                                                        <th class="text-center"> Credit</th>
                                                        <th class="text-center"> Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="debitvoucher">
                                                    @foreach ($journal_extras as $jextra)
                                                        <tr>
                                                            <td class="" width="300px">
                                                                <select name="child_account_id[]" id="accountName_{{ $jextra->id }}" class="form-control account_head" required>
                                                                    @foreach ($accounts as $account)
                                                                        <option value="" class="title" disabled>
                                                                            {{ $account->title }}</option>
                                                                        @php
                                                                            $sub_accounts = $account->sub_accounts;
                                                                        @endphp

                                                                        @foreach ($sub_accounts as $sub_account)
                                                                            @php
                                                                                $child_accounts = $sub_account->child_accounts;
                                                                            @endphp
                                                                            @foreach ($child_accounts as $child_account)
                                                                                <option value="{{ $child_account->id }}"
                                                                                    {{ $jextra->child_account_id == $child_account->id ? 'selected' : '' }}>
                                                                                    {{ $child_account->title }} -
                                                                                    {{ $sub_account->title }}</option>
                                                                            @endforeach
                                                                        @endforeach
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="text" name="remarks[]"  class="form-control "  id="remarks_1" value="{{ $jextra->remarks }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" step=".01" name="debitAmount[]"
                                                                    value="{{ $jextra->debitAmount }}"
                                                                    class="form-control debitPrice text-right"
                                                                    id="debitAmount_1" onkeyup="calculateTotal(1)">
                                                            </td>
                                                            <td>
                                                                <input type="number" step=".01" name="creditAmount[]"
                                                                    value="{{ $jextra->creditAmount }}"
                                                                    class="form-control creditPrice text-right"
                                                                    id="creditAmount_1" onkeyup="calculateTotal(1)">
                                                            </td>
                                                            <td>
                                                                <button class="btn-primary icon-btn btn-sm m-auto" type="button"
                                                                    value="Delete" onclick="deleteRowContravoucher(this)"><i
                                                                        class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2" class="text-right">
                                                            <label for="reason" class="  col-form-label">Total</label>
                                                        </td>
                                                        <td class="text-right">
                                                            <input type="text" id="debitTotal"
                                                                class="form-control text-right " name="debitTotal"
                                                                value="{{ $journalVouchers->debitTotal }}"
                                                                readonly="readonly" value="0" />
                                                        </td>
                                                        <td class="text-right">
                                                            <input type="text" id="creditTotal"
                                                                class="form-control text-right " name="creditTotal"
                                                                value="{{ $journalVouchers->creditTotal }}"
                                                                readonly="readonly" value="0" />
                                                        </td>
                                                        <td>
                                                            <a id="add_more" class="btn btn-primary icon-btn btn-sm m-auto" name="add_more"
                                                                onClick="addaccountContravoucher('debitvoucher')"><i
                                                                    class="fa fa-plus"></i></a>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <hr>

                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <label for="narration">Narration<i class="text-danger">*</i>:</label>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea name="narration" id="narration" class="form-control"
                                                cols="20" rows="5">{{ $journalVouchers->narration }}</textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('narration') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <div class="col-sm-12 text-center">
                                                <input type="submit" id="add_receive" class="btn btn-primary ml-auto"
                                                    name="save" value="Update" tabindex="9" />
                                            </div>
                                        </div>
                                    </form>

                            <input type="hidden" id="headoption" value="<option value=''>--Select One--</option>
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

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{ route('journals.update', $journalVouchers->id) }}"
                                        id="validate" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        @method("PUT")

                                        <div class="form-group">
                                            <label for="file">Attach file (Bills, etc.) (Optional)</label>
                                            <input type="file" name="file[]" id="file" class="form-control"
                                                onchange="loadFile(event)" multiple>
                                        </div>
                                        {{-- <p class="text-danger">Note*: Don't upload new if you want previously uploaded file.</p> --}}
                                        <div class="text-center">
                                            <input type="submit" id="add_receive" class="btn btn-primary btn-large"
                                                name="update" value="Save" />
                                        </div>

                                    </form>
                                </div>

                                <div class="col-md-6">
                                    @if (count($journalimage) > 0)
                                        <div class="row">
                                            @foreach ($journalimage as $jv)
                                                <div class="col-md-3 wrapp">
                                                    <img src="{{ Storage::disk('uploads')->url($jv->location) }}"
                                                        alt="" style="height:50px;">
                                                    {{-- <form action="{{ route('journalimage.destroy', $jv->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" data-id="{{ $jv->id }}"
                                                            class="btn btn-danger py-0 px-1 absolutebtn"
                                                            class="remove">x</button>
                                                    </form> --}}
                                                    <a href="#" data-id="{{ $jv->id }}" class="btn btn-danger py-0 px-1 absolutebtn delete-image remove">X</a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <img src="{{ Storage::disk('uploads')->url('noimage.jpg') }}" alt=""
                                            style="height: 50px;" id="output">
                                    @endif
                                    {{-- <img id="output" style="height: 100px;"> --}}
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
  <script>
      $(function(){
            $('.delete-image').click(function(e){
                event.preventDefault();
                let id = $(this).attr('data-id');
                if(confirm('Are you sure you want to delete this photo?')){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'DELETE',
                        url:"/journalimage/"+id,
                        // data: {
                        //     id: id,
                        // },
                        success: function(response){
                            if(response){
                                $(this).parents('.wrapp').slideUp.remove();
                            }
                        }.bind(this)
                    })
                    $(this).parents('.wrapp').hide();
                }
            })
        })
  </script>

  <script>
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
                ")'></td><td><button  class='btn-primary icon-btn btn-sm m-auto' type='button'  onclick='deleteRowContravoucher(this)'><i class='fa fa-trash'></i></button></td>";
            document.getElementById(divName).appendChild(newdiv);
            document.getElementById(tabin).focus();
            $("#accountName_" + count).html(optionval);
            count++;
            $("select.form-control:not(.dont-select-me)").select2({
                // placeholder: "--Select One--",
                // allowClear: true
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

        if($(".debitPrice").value != 0) {
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

        var mainInput = document.getElementById("entry_date_nepali");
        mainInput.nepaliDatePicker({
                        onChange: function() {
                            var nepdate = mainInput.value;
                            var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                            document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                        }
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
            $('.account_head').select2();
        });

        $(document).ready(function() {
            $(".supplier_info").select2();
        });

        $(document).ready(function() {
            $(".client_info").select2();
        });

        // $(document).ready(function() {
        //     $("#bank_info").select2();
        // });
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

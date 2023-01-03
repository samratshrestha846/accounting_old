@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
    <style>
        .btn-primary {
            color: white !important;
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
                    @php

                    @endphp
                    @if($is_sale_service == 1)
                        <h1>Edit Payment Information of {{$billingCredit->sale_bill->reference_no}} on {{$billingCredit->due_date_eng}}</h1>
                    @else
                        <h1>Edit Payment Information of {{$billingCredit->billing->reference_no}} on {{$billingCredit->due_date_eng}}</h1>
                    @endif

                    <div class="btn-bulk">
                        <a href="javascript:void(0)" onclick="goBack()" class="btn btn-primary">Go Back</a>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

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
                            @php
                            if($is_sale_service == 1){
                                $grandtotal = $billingCredit->sale_bill->grandtotal;
                                $paid_amount = $billingCredit->sale_bill->payment_amount;
                                $due_amount = $grandtotal - $paid_amount;
                                $url = route('paymentinfo.update', $billingCredit->id);
                            }else{
                                $grandtotal = $billingCredit->billing->grandtotal;
                                $payment_infos = $billingCredit->billing->payment_infos;
                                $lastpaymentinfo = $billingCredit->billing->payment_info_last->first();
                                $paid_amount = $payment_infos->sum('payment_amount');
                                $due_amount = $billingCredit->billing->grandtotal - $paid_amount;
                                $url = route('paymentinfo.update', $lastpaymentinfo->id);
                            }

                            @endphp
                            <p><span class="badge badge-primary mr-2 p-1"><b>Total Amount: </b>Rs.{{$grandtotal}}</span> <span class="badge badge-success mr-2 p-1"><b>Paid Amount: </b>Rs.{{$paid_amount}}</span><span class="badge badge-danger dueamount mr-2 p-1" data-dueamount="{{$due_amount}}"><b>Due Amount Except this payment: </b>Rs.{{$due_amount}}</span></p>
                            <form action="{{ $url }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            @if($is_sale_service == 1)
                                            <input type="hidden" name="billing_id" value="{{$billingCredit->sale_bill->id}}">
                                            @else
                                            <input type="hidden" name="billing_id" value="{{$billingCredit->billing->id}}">
                                            @endif

                                            <input type="hidden" name="is_billingcredit" value="1">
                                            @if($is_sale_service == 1)
                                            <input type="hidden" name="is_sale_service" value="1">
                                            @endif
                                            <label for="payment_type">Payment Type</label>
                                            <select name="payment_type" id="payment_type" class="form-control" required>
                                                <option value="">--Select an option--</option>
                                                <option value="paid" >Paid</option>
                                                <option value="partially_paid">Partially Paid</option>
                                                <option value="Credit">Unpaid</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="creditDate col-md-6 row" style="display: none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="entry_date">Due Date (B.S)<i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" name="due_date_nep" id="entry_date_nepaliCr"
                                                    class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="english_date">Due Date (A.D)<i
                                                        class="text-danger">*</i>:</label>
                                                <input type="date" name="payment_date" id="englishCr"
                                                    class="form-control" value="{{ date('Y-m-d') }}"
                                                    readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="payment_amount">Payment Amount</label>
                                            <input type="number" name="payment_amount" id="payment_amount" value="" class="form-control" placeholder="Enter Paid Amount" required>
                                            <p class="off text-danger">Payment can't be more than that of Due Amount</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary submit">Submit</button>
                            </form>
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
        function goBack() {
           window.history.back();
        }
    </script>
    <script>
        $('#payment_amount').keyup(function(){
            var payment_amount = $(this).val();
            var dueamount = $('.dueamount').data('dueamount');


            if(parseFloat(payment_amount) > parseFloat(dueamount)){
                $(this).parent().find('.text-danger').removeClass('off');
                $('.submit').addClass("disabled");
            }else{
                $(this).parent().find('.text-danger').addClass('off');
                $('.submit').removeClass("disabled");
            }
        });

        $('#payment_type').change(function(){
            $('.creditDate').hide();
            if($(this).val() == 'paid'){

                var dueamount = $('.dueamount').data('dueamount');
                $('#payment_amount').val(dueamount);
            }else{
                $('.creditDate').show();
                $('#payment_amount').val('');
            }
        })

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
@endpush

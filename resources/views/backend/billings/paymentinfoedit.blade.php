@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Edit Payment Information of {{$billing->reference_no}} on {{$paymentinfo->payment_date}}</h1>
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
                                $paid_amount = [];
                                $payments = $billing->payment_infos;
                                $paymentcount = count($payments);
                                for($x = 0; $x < $paymentcount; $x++){
                                    $payment_amount = round($payments[$x]->payment_amount, 2);
                                    array_push($paid_amount, $payment_amount);
                                }
                                $totpaid = array_sum($paid_amount);

                                $dueamt = round($billing->grandtotal,2) - ($totpaid - $paymentinfo->payment_amount);
                            @endphp
                            <p><span class="badge badge-primary mr-2 p-1"><b>Total Amount: </b>Rs.{{$billing->grandtotal}}</span> <span class="badge badge-success mr-2 p-1"><b>Paid Amount: </b>Rs.{{$totpaid}}</span><span class="badge badge-danger dueamount mr-2 p-1" data-dueamount="{{$dueamt}}"><b>Due Amount Except this payment: </b>Rs.{{$dueamt}}</span></p>
                            <form action="{{route('paymentinfo.update', $paymentinfo->id)}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="hidden" name="billing_id" value="{{$paymentinfo->billing_id}}">
                                            <label for="payment_type">Payment Type</label>
                                            <select name="payment_type" id="payment_type" class="form-control" required>
                                                <option value="">--Select an option--</option>
                                                <option value="paid" {{$paymentinfo->payment_type == "paid" ? "selected" : ''}}>Paid</option>
                                                <option value="partially_paid" {{$paymentinfo->payment_type == "partially_paid" ? "selected" : ''}}>Partially Paid</option>
                                                <option value="Unpaid" {{$paymentinfo->payment_type == "paid" ? "selected" : ''}}>Unpaid</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="payment_date">Payment Date</label>
                                            <input type="date" value="{{ $paymentinfo->payment_date }}" id="payment_date" name="payment_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="payment_amount">Payment Amount</label>
                                            <input type="text" name="payment_amount" id="payment_amount" value="{{$paymentinfo->payment_amount}}" class="form-control" placeholder="Enter Paid Amount" required>
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
        $('#payment_amount').change(function(){
            var payment_amount = $(this).val();
            var dueamount = $('.dueamount').data('dueamount');
            console.log(dueamount);

            if(parseFloat(payment_amount) > parseFloat(dueamount)){
                $(this).parent().find('.text-danger').removeClass('off');
                $('.submit').addClass("disabled");
            }else{
                $(this).parent().find('.text-danger').addClass('off');
                $('.submit').removeClass("disabled");
            }
        });
    </script>
@endpush

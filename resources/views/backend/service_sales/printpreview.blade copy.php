@extends('backend.layouts.app')
@push('styles')
    <style>
        .full{
            width: 100%;
            margin-bottom: 5px;
            height: 200px;
        }
        .left{
            width:10%;
            float: left;
            height: 100%;
        }
        .right{
            width: 90%;
            float: right;
            height: 100%;
        }
    </style>
@endpush

@section('content')

    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content p-5">
            <div class="container-fluid">
                <div class="ibox">
                    <div class="full">
                        <div class="left">
                            <img src="{{ Storage::disk('uploads')->url($currentcomp->company->company_logo) }}" alt="{{ $currentcomp->company->name }}" style="height: 120px;">
                        </div>

                        <div class="right">
                            <h1 class="m-0 text-center" style="color: #583949;">{{ $currentcomp->company->name }} </h1><br>
                            <p class="text-center">{{ $currentcomp->company->local_address }}, {{ $currentcomp->company->provinces->eng_name }}</p>
                            <p class="text-center">{{$currentcomp->company->email}}</p>
                            <p class="text-center">{{$currentcomp->company->phone}}</p>
                            <b class="text-center" style="background: #583949;
                                        text-align: center;
                                        color: #fff;
                                        display: block;
                                        padding: 5px 8px;
                                        font-size: 9px;
                                        font-weight: 500;
                                        letter-spacing: .3px;
                                        border-radius: 4px;
                                        text-transform: uppercase;
                                        max-width: 93px;
                                        margin-left: auto;
                                        margin-right: auto;">
                                @php
                                    if($serviceSaleBill->downloadcount > 0 || $serviceSaleBill->printcount > 0){
                                        echo "Copy of Tax Invoice";
                                    }else{
                                        echo "Tax Invoice";
                                    }
                                @endphp
                            </b>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p><b>Invoice No: </b>{{$serviceSaleBill->reference_no}}</p>
                                        <p><b> VAT Bill No:</b> {{ $serviceSaleBill->ledger_no }} </p>
                                        <p class="text-left"><b>Customer Name: </b>{{$serviceSaleBill->client->name}}</p>
                                        @if(!$serviceSaleBill->client->local_address == null)
                                            <p class="text-left"><b>Customer Address :</b>{{$serviceSaleBill->client->local_address}}
                                            </p>
                                            @endif
                                        <p class="text-left"><b>Payment Mode: </b>
                                            @if ($serviceSaleBill->payment_method == 2)
                                                Cheque ({{ $serviceSaleBill->bank->bank_name }} / Cheque no.: {{ $serviceSaleBill->cheque_no }})
                                            @elseif($serviceSaleBill->payment_method == 3)
                                                Bank Deposit ({{ $serviceSaleBill->bank->bank_name }})
                                            @elseif($serviceSaleBill->payment_method == 4)
                                                Online Portal ({{ $serviceSaleBill->online_portal->name }} / Portal Id: {{ $serviceSaleBill->customer_portal_id }})
                                            @else
                                                Cash
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-4 offset-2">
                                        <p class="text-left"><b>Fiscal-Year: </b>{{$serviceSaleBill->fiscal_year->fiscal_year}}</p>
                                        <p class="text-left"><b>Date: </b>{{$serviceSaleBill->eng_date}}/{{$serviceSaleBill->nep_date}}</p>
                                        <p class="text-left"><b>Printed By: </b>{{Auth::user()->name}}</p>
                                        <p class="text-left"><b>Printed On: </b>{{date('F j, Y')}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Particulars</th>
                                                    <th class="text-nowrap">Quantity</th>
                                                    <th class="text-nowrap">Rate</th>
                                                    <th class="text-nowrap">Discount(Per Unit)</th>
                                                    <th class="text-nowrap">Tax(Per Unit)</th>
                                                    <th class="text-nowrap" style="width: 15%;">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($serviceSaleBill->serviceSalesExtra as $serviceSaleBillextra)
                                                    <tr>
                                                        <td>
                                                            {{ $serviceSaleBillextra->service->service_name }}
                                                        </td>
                                                        <td>{{ $serviceSaleBillextra->quantity }}</td>
                                                        <td>Rs. {{ $serviceSaleBillextra->rate }}</td>
                                                        <td>
                                                            @if ($serviceSaleBillextra->discountamt == 0)
                                                                -
                                                            @else
                                                                Rs. {{ $serviceSaleBillextra->discountamt }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($serviceSaleBillextra->taxamt == 0)
                                                                -
                                                            @else
                                                                Rs. {{ $serviceSaleBillextra->taxamt }}
                                                            @endif
                                                        </td>
                                                        <td>Rs. {{ $serviceSaleBillextra->total }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Sub Total</b></td>
                                                    <td>Rs. {{ $serviceSaleBill->subtotal }}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Discount Amount</b></td>
                                                    <td>
                                                        @if ($serviceSaleBill->alldiscounttype == "fixed")
                                                            Rs. {{ $serviceSaleBill->discountamount }}
                                                        @elseif ($serviceSaleBill->alldiscounttype == "percent")
                                                            {{ $serviceSaleBill->discountpercent }} %
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Tax Amount({{ $serviceSaleBill->taxpercent == null ? '0%' : $serviceSaleBill->taxpercent }})</b>
                                                    </td>

                                                    <td>
                                                        @if ($serviceSaleBill->taxamount == 0)
                                                            -
                                                        @else
                                                            Rs. {{ $serviceSaleBill->taxamount }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Shipping</b></td>
                                                    <td>
                                                        @if ($serviceSaleBill->shipping == 0)
                                                            -
                                                        @else
                                                            Rs. {{ $serviceSaleBill->shipping }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Grand Total</b></td>
                                                    <td>Rs. {{ $serviceSaleBill->grandtotal }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p><b>Remarks: </b>{{$serviceSaleBill->remarks}}</p>
                                    </div>
                                    <div class="col-3 offset-9">
                                        <p class="text-center p-0 m-0">.......................................</p>
                                        <p class="text-center p-0 m-0">Signature</p>
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
@endsection
@push('scripts')
    <script>
        (
            function() {
                var beforePrint = function()
                {
                    console.log('Functionality to run before printing.');
                };
                var afterPrint = function() {
                    var user = @php echo $user; @endphp;
                    var printcount = @php echo $serviceSaleBill->printcount; @endphp;
                    var bill_id = @php echo $serviceSaleBill->id; @endphp;
                    newprintcount = printcount +1;
                    var uri = "{{route('serviceSalesBillPrinted', ':bill_id')}}";
                    uri=uri.replace(':bill_id', bill_id);
                    $.ajax({
                        url: uri,
                        type:"POST",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            user_id: user,
                            nprintcount: newprintcount,
                        },
                        success:function(response){
                            message("Completed");
                        },
                    });
                };

                if (window.matchMedia) {
                    var mediaQueryList = window.matchMedia('print');
                    mediaQueryList.addListener(function(mql) {
                        if (mql.matches) {
                            beforePrint();
                        } else {
                            afterPrint();
                        }
                    });
                }

                window.onbeforeprint = beforePrint;
                window.onafterprint = afterPrint;
            }
        ());
    </script>
@endpush

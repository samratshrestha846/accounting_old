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
                                    if($billing->downloadcount > 0 || $billing->printcount > 0){
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
                                        <p><b>Invoice No: </b>{{$billing->reference_no}}</p>
                                        @if ($billing_type->id == 2 || $billing_type->id == 5)
                                            <p><b> Party Bill No:</b> {{ $billing->ledger_no }} </p>
                                        @else
                                            <p><b> VAT Bill No:</b> {{ $billing->ledger_no }} </p>
                                        @endif
                                        @if (!$billing->vendor_id == null)
                                            <p class="text-left"><b>Supplier Name: </b>{{$billing->suppliers->company_name}}</p>
                                            @if(!$billing->suppliers->company_address == null)<p class="text-left"><b>Company Address :</b>{{$billing->suppliers->company_address}}</p>@endif
                                            <p class="text-left"><b>Payment Mode:
                                            </b>

                                                @if ($billing->payment_method == 2)
                                                    Cheque ({{ $billing->bank->bank_name }} / Cheque no.: {{ $billing->cheque_no }})
                                                @elseif($billing->payment_method == 3)
                                                    Bank Deposit ({{ $billing->bank->bank_name }})
                                                @elseif($billing->payment_method == 4)
                                                    Online Portal ({{ $billing->online_portal->name }} / Portal Id: {{ $billing->customer_portal_id }})
                                                @else
                                                    Cash
                                                @endif
                                            </p>
                                        @endif
                                        @if (!$billing->client_id == null)
                                            <p class="text-left"><b>Customer Name: </b>{{$billing->client->name}}</p>
                                            @if(!$billing->client->local_address == null)<p class="text-left"><b>Customer Address :</b>{{$billing->client->local_address}}</p>@endif
                                            <p class="text-left"><b>Payment Mode: </b>
                                                @if ($billing->payment_method == 2)
                                                    Cheque ({{ $billing->bank->bank_name }} / Cheque no.: {{ $billing->cheque_no }})
                                                @elseif($billing->payment_method == 3)
                                                    Bank Deposit ({{ $billing->bank->bank_name }})
                                                @elseif($billing->payment_method == 4)
                                                    Online Portal ({{ $billing->online_portal->name }} / Portal Id: {{ $billing->customer_portal_id }})
                                                @else
                                                    Cash
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-4 offset-2">
                                        <p class="text-left"><b>Fiscal-Year: </b>{{$billing->fiscal_year->fiscal_year}}</p>
                                        <p class="text-left"><b>Date: </b>{{$billing->eng_date}}/{{$billing->nep_date}}</p>
                                        <p class="text-left"><b>Printed By: </b>{{Auth::user()->name}}</p>
                                        <p class="text-left"><b>Printed On: </b>{{date('F j, Y')}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered">
                                            @if ($billing->billing_type_id == 7)
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-nowrap">Particulars</th>
                                                        <th class="text-nowrap" style="{{$quotationsetting->show_picture == 0 ? "display:none" : ""}}">Pictures</th>
                                                        <th class="text-nowrap" style="{{$quotationsetting->show_brand == 0 ? "display:none" : ""}}">Brand</th>
                                                        <th class="text-nowrap" style="{{$quotationsetting->show_model == 0 ? "display:none" : ""}}">Model</th>
                                                        <th class="text-nowrap">Quantity</th>
                                                        <th class="text-nowrap">Rate</th>
                                                        <th class="text-nowrap" style="width: 20%;">Discount Amount (per unit)</th>
                                                        <th class="text-nowrap" style="width: 20%;">Tax Amount (per unit)</th>
                                                        <th class="text-nowrap" style="width: 15%;">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($billing->billingextras as $billingextra)
                                                    <tr>
                                                        @php
                                                            $product_image = App\Models\ProductImages::latest()->where('product_id', $billingextra->product->id)->first();
                                                        @endphp

                                                        <td>{{$billingextra->product->product_name}}</td>
                                                        <td style="{{$quotationsetting->show_picture == 0 ? "display:none" : ""}}">
                                                            <img src="{{ Storage::disk('uploads')->url($product_image->location) }}" alt="{{ $billingextra->product->product_name }}" style="max-height: 50px;max-width: 50px;">
                                                        </td>
                                                        <td style="{{$quotationsetting->show_brand == 0 ? "display:none" : ""}}">{{$billingextra->product->brand->brand_name}}</td>
                                                        <td style="{{$quotationsetting->show_model == 0 ? "display:none" : ""}}">{{$billingextra->product->series->series_name}}</td>
                                                        <td>{{$billingextra->quantity}} {{$billingextra->unit}}</td>
                                                        <td>{{$billingextra->rate}}</td>
                                                        <td>{{$billingextra->discountamt}}</td>
                                                        <td>{{$billingextra->taxamt}}</td>
                                                        <td>Rs. {{$billingextra->total}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td style="{{$quotationsetting->show_picture == 0 ? "display:none" : ""}}"></td>
                                                        <td style="{{$quotationsetting->show_brand == 0 ? "display:none" : ""}}"></td>
                                                        <td style="{{$quotationsetting->show_model == 0 ? "display:none" : ""}}"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Sub Total</b></td>
                                                        <td>Rs. {{$billing->subtotal}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td style="{{$quotationsetting->show_picture == 0 ? "display:none" : ""}}"></td>
                                                        <td style="{{$quotationsetting->show_brand == 0 ? "display:none" : ""}}"></td>
                                                        <td style="{{$quotationsetting->show_model == 0 ? "display:none" : ""}}"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Discount Amount</b></td>
                                                        <td>Rs. {{$billing->discountamount}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td style="{{$quotationsetting->show_picture == 0 ? "display:none" : ""}}"></td>
                                                        <td style="{{$quotationsetting->show_brand == 0 ? "display:none" : ""}}"></td>
                                                        <td style="{{$quotationsetting->show_model == 0 ? "display:none" : ""}}"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Tax Amount({{$billing->taxpercent == null ? "0%" : $billing->taxpercent}})</b></td>
                                                        <td>Rs. {{$billing->taxamount}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td style="{{$quotationsetting->show_picture == 0 ? "display:none" : ""}}"></td>
                                                        <td style="{{$quotationsetting->show_brand == 0 ? "display:none" : ""}}"></td>
                                                        <td style="{{$quotationsetting->show_model == 0 ? "display:none" : ""}}"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Shipping</b></td>
                                                        <td>Rs. {{$billing->shipping}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td style="{{$quotationsetting->show_picture == 0 ? "display:none" : ""}}"></td>
                                                        <td style="{{$quotationsetting->show_brand == 0 ? "display:none" : ""}}"></td>
                                                        <td style="{{$quotationsetting->show_model == 0 ? "display:none" : ""}}"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Grand Total</b></td>
                                                        <td>Rs. {{$billing->grandtotal}}</td>
                                                    </tr>
                                                </tfoot>
                                                @elseif($billing->billing_type_id == 1 || $billing->billing_type_id == 2 || $billing->billing_type_id == 5 || $billing->billing_type_id == 6)
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-nowrap">Particulars</th>
                                                        <th class="text-nowrap">Quantity</th>
                                                        <th class="text-nowrap">Rate</th>
                                                        <th class="text-nowrap" style="width: 20%;">Discount Amount (per unit)</th>
                                                        <th class="text-nowrap" style="width: 20%;">Tax Amount (per unit)</th>
                                                        <th class="text-nowrap" style="width: 15%;">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($billing->billingextras as $billingextra)
                                                    <tr>
                                                        <td>{{$billingextra->product->product_name}}</td>
                                                        <td>{{$billingextra->quantity}} {{$billingextra->unit}}</td>
                                                        <td>{{$billingextra->rate}}</td>
                                                        <td>{{$billingextra->discountamt}}</td>
                                                        <td>{{$billingextra->taxamt}}</td>
                                                        <td>Rs. {{$billingextra->total}}</td>
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
                                                        <td>Rs. {{$billing->subtotal}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Discount Amount</b></td>
                                                        <td>Rs. {{$billing->discountamount}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Tax Amount({{$billing->taxpercent == null ? "0%" : $billing->taxpercent}})</b></td>
                                                        <td>Rs. {{$billing->taxamount}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Shipping</b></td>
                                                        <td>Rs. {{$billing->shipping}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Grand Total</b></td>
                                                        <td>Rs. {{$billing->grandtotal}}</td>
                                                    </tr>
                                                </tfoot>
                                            @elseif ($billing->billing_type_id == 3 || $billing->billing_type_id == 4)
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-nowrap">Particulars</th>
                                                        <th class="text-nowrap">Cheque No.</th>
                                                        <th class="text-nowrap">Narration</th>
                                                        <th class="text-nowrap">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($billing->billingextras as $billingextra)
                                                    <tr>
                                                        <td>{{$billingextra->particulars}}</td>
                                                        <td>{{$billingextra->cheque_no}}</td>
                                                        <td>{{$billingextra->narration}}</td>
                                                        <td>Rs. {{$billingextra->total}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Sub Total</b></td>
                                                        <td>Rs. {{$billing->subtotal}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Discount Amount</b></td>
                                                        <td>Rs. {{$billing->discountamount}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Tax Amount({{$billing->taxpercent == null ? "0%" : $billing->taxpercent}})</b></td>
                                                        <td>Rs. {{$billing->taxamount}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Shipping</b></td>
                                                        <td>Rs. {{$billing->shipping}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Grand Total</b></td>
                                                        <td>Rs. {{$billing->grandtotal}}</td>
                                                    </tr>
                                                </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p><b>Remarks: </b>{{$billing->remarks}}</p>
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
                    var printcount = @php echo $billing->printcount; @endphp;
                    var bill_id = @php echo $billing->id; @endphp;
                    newprintcount = printcount +1;
                    var uri = "{{route('billing.print', ':bill_id')}}";
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

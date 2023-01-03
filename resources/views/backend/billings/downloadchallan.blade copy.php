<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $billing_type->billing_types }} Invoice</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <style>
        *{
            padding: 0;
            margin:0;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
            font-size: 13px;
        }
        body{
            padding:50px;
        }
    </style>
</head>
<body>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
                <div class="row">
                    <div class="col-md-1" style="float: left;">
                        <img src="{{ $path_img }}" alt="" style="height: 100px;">
                    </div>
                    <div class="col-md-11" style="text-align: center;">
                        <b style="font-size: 30px;display: block;font-weight: 600;margin-bottom: -10px;color: #583949;">{{ $currentcomp->company->name }}</b> <br>
                        {{ $currentcomp->company->local_address }}, {{ $currentcomp->company->provinces->eng_name }} <br>
                        {{$currentcomp->company->email}}<br>
                        {{$currentcomp->company->phone}}
                    </div>
                </div>
            </div>
            <br><br>

            <div class="row" style="width: 100%;">
                <div class="col-md-12">
                    <div style="float: left; width:40%; font-size:13px;">
                        <p><b>Invoice No: </b>{{$billing->reference_no}}</p>

                        @if ($billing_type->id == 2 || $billing_type->id == 5)
                            <p><b> Party Bill No: </b>{{ $billing->ledger_no }}</p>
                        @else
                            <p><b> VAT Bill No: </b>{{ $billing->ledger_no }}</p>
                        @endif

                        @if (!$billing->vendor_id == null)
                            <p class="text-left"><b>Supplier Name: </b>{{$billing->suppliers->company_name}}</p>
                            @if(!$billing->suppliers->company_address == null)<p class="text-left"><b>Company Address :</b>{{$billing->suppliers->company_address}}</p>@endif
                        @endif
                        @if (!$billing->client_id == null)
                            <p class="text-left"><b>Customer Name: </b>{{$billing->client->name}}</p>
                            @if(!$billing->client->local_address == null)<p class="text-left"><b>Customer Address :</b>{{$billing->client->local_address}}</p>@endif
                        @endif
                        <p><b>Payment Mode: </b>
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
                    </div>

                    <div style="float: left; width:25%">
                        <b style="background: #583949;
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
                            Challan
                        </b>
                    </div>

                    <div style="float: left; width:35%; font-size:13px; padding-right:10px;">
                        <p style="text-align: right;"><b>English Date: </b>{{$billing->eng_date}}</p>
                        <p style="text-align: right;"><b>Nepali Date: </b>{{$billing->nep_date}}</p>
                        <p style="text-align: right;"><b>Printed By: </b>{{Auth::user()->name}}</p>
                        <p style="text-align: right;"><b>Printed On: </b>{{date('F j, Y')}}</p>
                    </div>
                </div>
            </div>
            <br>

            <div class="row" style="margin-top: 100px;">
                <div class="col-md-12">
                    <table style="width: 100%;">
                        @if ($billing->billing_type_id == 1)
                            <thead style="background-color: #343a40; color: #ffffff; font-size:13px;">
                                <tr>
                                    <th class="text-nowrap" style="width: 30%;">Particulars</th>
                                    <th class="text-nowrap" style="width: 10%;">Quantity</th>
                                    <th class="text-nowrap" style="width: 10%;">Rate/Unit</th>
                                    <th class="text-nowrap" style="width: 10%;">Discount Amount/Unit</th>
                                    <th class="text-nowrap" style="width: 10%;">Tax Amount/Unit</th>
                                    <th class="text-nowrap" style="width: 30%;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($billing->billingextras as $billingextra)
                                <tr>
                                    <td>
                                        {{$billingextra->product->product_name}}
                                    </td>

                                    <td>{{$billingextra->quantity}} {{$billingextra->product->primary_unit}}</td>
                                    <td>Rs. {{$billingextra->rate}}</td>
                                    <td>
                                        @if ($billingextra->discountamt == 0)
                                            -
                                        @else
                                            Rs. {{$billingextra->discountamt}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($billingextra->taxamt == 0)
                                            -
                                        @else
                                            Rs. {{$billingextra->taxamt}}
                                        @endif
                                    </td>
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
                                    <td>
                                        @if ($billing->discountamount == 0)
                                            -
                                        @else
                                            Rs. {{$billing->discountamount}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b>Tax Amount({{$billing->taxpercent == null ? "0%" : $billing->taxpercent}})</b></td>
                                    <td>
                                        @if ($billing->taxamount == 0)
                                            -
                                        @else
                                            Rs. {{$billing->taxamount}}
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
                                        @if ($billing->shipping == 0)
                                            -
                                        @else
                                            Rs. {{$billing->shipping}}
                                        @endif
                                    </td>
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
                        @endif
                    </table>
                </div>
            </div>
            <br><hr>

            <div class="row" style="margin-top: 50px;">
                <div class="col-md-12" style="float: left; font-size:13px;">
                    <p><b>Remarks: </b>{{$billing->remarks}}</p>
                </div>
                <div class="col-md-12" style="text-align: right; font-size:13px;">
                    <p><b>Signature: </b>...............................</p>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    </body>
</html>

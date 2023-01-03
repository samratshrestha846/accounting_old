<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Order</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <style>
        * {
            padding: 0;
            margin: 0;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
            font-size: 13px;
            text-align: center;
        }

        body {
            padding: 50px;
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
                    <b
                        style="font-size: 30px;display: block;font-weight: 600;margin-bottom: -10px;color: #583949;">{{ $currentcomp->company->name }}</b>
                    <br>
                    {{ $currentcomp->company->local_address }}, {{ $currentcomp->company->provinces->eng_name }}
                    <br>
                    {{ $currentcomp->company->email }}<br>
                    {{ $currentcomp->company->phone }}
                </div>
            </div>
            <br><br>

            <div class="row" style="width: 100%;">
                <div class="col-md-12">
                    <div style="float: left; width:40%; font-size:13px;">
                        <p><b>Purchase Order No: </b>{{ $purchaseOrder->purchase_order_no }}</p>
                            <p class="text-left"><b>Supplier Name: </b>{{ $purchaseOrder->suppliers->company_name }}</p>
                            @if (!$purchaseOrder->suppliers->company_address == null)<p class="text-left"><b>Company Address :</b>{{ $purchaseOrder->suppliers->company_address }}</p>@endif
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
                            Purchase Order {{ $purchaseOrder->purchase_order_no }}
                        </b>
                    </div>

                    <div style="float: left; width:35%; font-size:13px; padding-right:10px;">
                        <p style="text-align: right;"><b>English Date: </b>{{ $purchaseOrder->eng_date }}</p>
                        <p style="text-align: right;"><b>Nepali Date: </b>{{ $purchaseOrder->nep_date }}</p>
                        <p style="text-align: right;"><b>Printed By: </b>{{ Auth::user()->name }}</p>
                        <p style="text-align: right;"><b>Printed On: </b>{{ date('F j, Y') }}</p>
                    </div>
                </div>
            </div>
            <br>

            <div class="row" style="margin-top: 100px;">
                <div class="col-md-12">
                    <table style="width: 100%">
                            <thead style="background-color: #343a40; color: #ffffff; font-size:13px;">
                                <tr>
                                    <th style="width: 40%">Particulars</th>
                                    <th class="text-nowrap">Quantity</th>
                                    <th class="text-nowrap">Rate/Unit</th>
                                    <th style="width: 15%;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrder->purchaseOrderExtras as $purchaseOrderExtra)
                                    <tr>
                                        <td>
                                            {{ $purchaseOrderExtra->particulars }}
                                        </td>
                                        <td>{{ $purchaseOrderExtra->quantity }} {{ $purchaseOrderExtra->unit }}</td>
                                        <td>Rs. {{ $purchaseOrderExtra->rate }}</td>
                                        <td>Rs. {{ $purchaseOrderExtra->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><b>Sub Total</b></td>
                                    <td>Rs. {{ $purchaseOrder->subtotal }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><b>Tax
                                            Amount({{ $purchaseOrder->taxpercent == null ? '0%' : $purchaseOrder->taxpercent }})</b>
                                    </td>
                                    <td>
                                        @if ($purchaseOrder->taxamount == 0)
                                            -
                                        @else
                                            Rs. {{ $purchaseOrder->taxamount }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><b>Grand Total</b></td>
                                    <td>Rs. {{ $purchaseOrder->grandtotal }}</td>
                                </tr>
                            </tfoot>
                    </table>
                </div>
            </div>
            <br>
            <hr>

            <div class="row" style="margin-top: 50px;">
                <div class="col-md-12" style="float: left; font-size:13px;">
                    <p><b>Remarks: </b>{{ $purchaseOrder->remarks }}</p>
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

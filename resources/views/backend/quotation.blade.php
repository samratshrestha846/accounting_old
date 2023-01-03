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
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
            font-size: 13px;
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
                        <b style="font-size: 30px;display: block;font-weight: 600;margin-bottom: -10px;color: #583949;">{{ $setting->company_name }}</b> <br>
                                    {{ $setting->address }}, {{ $setting->province->eng_name }} <br>
                                    {{ $setting->company_email }}<br>
                                    {{ $setting->company_phone }}
                    </div>
                </div>
                <br><br>

                <div class="row" style="width: 100%;">
                    <div class="col-md-12">
                        <div style="float: left; width:30%; font-size:13px;">
                            <p><b>Invoice No: </b>{{$billing->reference_no}}</p>
                            @if (!$billing->vendor_id == null)
                                <p><b>Customer Name: </b>{{$billing->suppliers->company_name}}</p>
                                <p><b>Adress: </b>{{$billing->suppliers->company_address}}</p>
                            @endif
                            <p><b>Payment Mode: </b>{{$billing->payment_modes->payment_mode}}</p>
                        </div>

                        <div style="float: left; width:40%">
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
                                @php
                                    if($billing->downloadcount > 0 || $billing->printcount > 0){
                                        echo "Copy of Tax Invoice";
                                    }else{
                                        echo "Tax Invoice";
                                    }
                                @endphp
                            </b>
                        </div>

                        <div style="float: left; width:30%; font-size:13px; padding-right:10px;">
                            <p style="text-align: right;"><b>English Date: </b>{{$billing->eng_date}}</p>
                            <p style="text-align: right;"><b>Nepali Date: </b>{{$billing->nep_date}}</p>
                            <p style="text-align: right;"><b>Printed By: </b>{{Auth::user()->name}}</p>
                            <p style="text-align: right;"><b>Printed On: </b>{{date('F j, Y')}}</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row" style="width: 100%; margin-top: 150px;">
                    <div class="col-md-12">
                        <table>

                            @if ($billing->billing_type_id == 1 || $billing->billing_type_id == 2 || $billing->billing_type_id == 5 || $billing->billing_type_id == 6 || $billing->billing_type_id == 7)
                                <thead style="background-color: #343a40; color: #ffffff; font-size:13px;">
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Picture</th>
                                        <th>Brand</th>
                                        <th>Model</th>
                                        <th>Quantity</th>
                                        <th>Rate/Unit</th>
                                        <th style="width: 10%;">Discount Amount/Unit</th>
                                        <th style="width: 10%;">Tax Amount/Unit</th>
                                        <th style="width: 25%;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($billing->billingextras as $billingextra)
                                    <tr>
                                        @php
                                            $product_image = App\Models\ProductImages::latest()->where('product_id', $billingextra->product->id)->first();

                                            $opciones_ssl=array(
                                                "ssl"=>array(
                                                "verify_peer"=>false,
                                                "verify_peer_name"=>false,
                                                ),
                                            );

                                            $image_path = 'uploads/' . $product_image->location;
                                            $extencion = pathinfo($image_path, PATHINFO_EXTENSION);
                                            $data = file_get_contents($image_path, false, stream_context_create($opciones_ssl));
                                            $img_base_64 = base64_encode($data);
                                            $productImage = 'data:image/' . $extencion . ';base64,' . $img_base_64;
                                        @endphp
                                        <td>
                                            {{$billingextra->product->product_name}}
                                        </td>
                                        <td>
                                            @if ($product_image)
                                                <img src="{{ $productImage }}" alt="{{ $billingextra->product->product_name }}" style="max-height: 50px;max-width: 50px;">
                                            @endif
                                        </td>

                                        <td>{{$billingextra->product->brand->brand_name}}</td>
                                        <td>{{$billingextra->product->series->series_name}}</td>

                                        <td>{{$billingextra->quantity}} {{$billingextra->unit}}</td>
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

                <div class="row" style="margin-top: 10px;">
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

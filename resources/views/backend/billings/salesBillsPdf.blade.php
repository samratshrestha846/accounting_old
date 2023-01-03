<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if ($pos_data == 1)
        <title>POS Sales Bills</title>
    @else
        <title>{{ $billing_type->billing_types }} Bills</title>
    @endif
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
            font-size: 12px;
            text-align:  center;
        }

        .page-break {
            page-break-after: always;
        }

    </style>
</head>

<body>
    @foreach ($billings as $billing)
        <!-- Main content -->
        <section class="content" style="margin-top: 30px;">
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
                        <div style="float: left; width:45%; font-size:13px;">
                            <p><b>Invoice No: </b>{{ $billing->reference_no }}</p>
                            @if (!$billing->vendor_id == null)
                                <p class="text-left"><b>Supplier Name: </b>{{ $billing->suppliers->company_name }}
                                </p>
                                @if (!$billing->suppliers->company_address == null)<p class="text-left"><b>Company Address: </b>{{ $billing->suppliers->company_address }}</p>@endif
                            @endif
                            @if (!$billing->client_id == null)
                                <p class="text-left"><b>Customer Name: </b>{{ $billing->client->name }}</p>
                                @if (!$billing->client->local_address == null)<p class="text-left"><b>Customer Address: </b>{{ $billing->client->local_address }}</p>@endif
                            @endif
                            <p>
                                <b>Payment Mode: </b>
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
                                @php
                                    if ($billing->downloadcount > 0 || $billing->printcount > 0) {
                                        echo 'Copy of Tax Invoice';
                                    } else {
                                        echo 'Tax Invoice';
                                    }
                                @endphp
                            </b>
                        </div>

                        <div style="float: left; width:30%; font-size:13px; padding-right:10px;">
                            <p style="text-align: right;"><b>English Date: </b>{{ $billing->eng_date }}</p>
                            <p style="text-align: right;"><b>Nepali Date: </b>{{ $billing->nep_date }}</p>
                            <p style="text-align: right;"><b>Printed By: </b>{{ Auth::user()->name }}</p>
                            <p style="text-align: right;"><b>Printed On: </b>{{ date('F j, Y') }}</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row" style="margin-top: 150px;">
                    <div class="col-md-12">
                        <table style="width: 100%">
                            @if ($billing->billing_type_id == 1 || $billing->billing_type_id == 2 || $billing->billing_type_id == 5 || $billing->billing_type_id == 6)
                                <thead style="background-color: #343a40; color: #ffffff; font-size:13px;">
                                    <tr>
                                        @if ($pos_data == 1)
                                            <th class="text-nowrap">Outlet</th>
                                        @endif
                                        <th style="width: 25%">Particulars</th>
                                        <th class="text-nowrap">Quantity</th>
                                        <th class="text-nowrap">Rate/Unit</th>
                                        <th class="text-nowrap">Discount Amount/Unit</th>
                                        <th class="text-nowrap">Tax Amount/Unit</th>
                                        <th style="width: 20%">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($billing->billingextras as $billingextra)
                                        <tr>
                                            @if ($pos_data == 1)
                                                <td>{{ $billing->outlet->name }}</td>
                                            @endif
                                            <td>
                                                {{ $billingextra->product->product_name }}
                                            </td>

                                            @if ($pos_data == 1)
                                                <td>{{ $billingextra->quantity }} {{ $billingextra->purchase_unit }}</td>
                                            @else
                                                <td>{{ $billingextra->quantity }} {{ $billingextra->product->primary_unit }}</td>
                                            @endif
                                            <td>Rs. {{ $billingextra->rate }}</td>
                                            <td>
                                                @if ($billingextra->discountamt == 0)
                                                    -
                                                @else
                                                    Rs. {{ $billingextra->discountamt }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($billingextra->taxamt == 0)
                                                    -
                                                @else
                                                    Rs. {{ $billingextra->taxamt }}
                                                @endif
                                            </td>
                                            <td>Rs. {{ $billingextra->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        @if ($pos_data == 1)
                                            <td></td>
                                        @endif
                                        <td></td>
                                        <td></td>
                                        <td><b>Sub Total</b></td>
                                        <td>Rs. {{ $billing->subtotal }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        @if ($pos_data == 1)
                                            <td></td>
                                        @endif
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Discount Amount</b></td>
                                        <td>
                                            @if ($billing->discountamount == 0)
                                                -
                                            @else
                                                Rs. {{ $billing->discountamount }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        @if ($pos_data == 1)
                                            <td></td>
                                        @endif
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Tax
                                                Amount({{ $billing->taxpercent == null ? '0%' : $billing->taxpercent }})</b>
                                        </td>
                                        <td>
                                            @if ($billing->taxamount == 0)
                                                -
                                            @else
                                                Rs. {{ $billing->taxamount }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        @if ($pos_data == 1)
                                            <td></td>
                                        @endif
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Shipping</b></td>
                                        <td>
                                            @if ($billing->shipping == 0)
                                                -
                                            @else
                                                Rs. {{ $billing->shipping }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        @if ($pos_data == 1)
                                            <td></td>
                                        @endif
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Grand Total</b></td>
                                        <td>Rs. {{ $billing->grandtotal }}</td>
                                    </tr>
                                </tfoot>
                            @elseif($billing->billing_type_id == 7)
                                <thead style="background-color: #343a40; color: #ffffff; font-size:13px;">
                                    <tr>
                                        <th style="width: 30%">Particulars</th>
                                        <th class="text-nowrap"
                                            style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                            Picture
                                        </th>
                                        <th class="text-nowrap"
                                            style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                            Brand
                                        </th>
                                        <th class="text-nowrap"
                                            style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                            Model
                                        </th>
                                        <th class="text-nowrap">Quantity</th>
                                        <th class="text-nowrap">Rate/Unit</th>
                                        <th class="text-nowrap" style="width: 10%;">Discount Amount/Unit</th>
                                        <th class="text-nowrap" style="width: 10%;">Tax Amount/Unit</th>
                                        <th class="text-nowrap" style="width: 20%;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($billing->billingextras as $billingextra)
                                        <tr>
                                            @php
                                                $product_image = App\Models\ProductImages::latest()
                                                    ->where('product_id', $billingextra->product->id)
                                                    ->first();

                                                $opciones_ssl = [
                                                    'ssl' => [
                                                        'verify_peer' => false,
                                                        'verify_peer_name' => false,
                                                    ],
                                                ];

                                                $image_path = 'uploads/' . $product_image->location;
                                                $extencion = pathinfo($image_path, PATHINFO_EXTENSION);
                                                $data = file_get_contents($image_path, false, stream_context_create($opciones_ssl));
                                                $img_base_64 = base64_encode($data);
                                                $productImage = 'data:image/' . $extencion . ';base64,' . $img_base_64;
                                            @endphp
                                            <td>
                                                {{ $billingextra->product->product_name }}
                                            </td>
                                            <td
                                                style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                                @if ($product_image)
                                                    <img src="{{ $productImage }}"
                                                        alt="{{ $billingextra->product->product_name }}"
                                                        style="max-height: 50px;max-width: 50px;">
                                                @endif
                                            </td>

                                            <td
                                                style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                                {{ $billingextra->product->brand->brand_name }}</td>
                                            <td
                                                style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                                {{ $billingextra->product->series->series_name }}</td>

                                            <td>{{ $billingextra->quantity }} {{ $billingextra->unit }}</td>
                                            <td>Rs. {{ $billingextra->rate }}</td>
                                            <td>
                                                @if ($billingextra->discountamt == 0)
                                                    -
                                                @else
                                                    Rs. {{ $billingextra->discountamt }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($billingextra->taxamt == 0)
                                                    -
                                                @else
                                                    Rs. {{ $billingextra->taxamt }}
                                                @endif
                                            </td>
                                            <td>Rs. {{ $billingextra->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Sub Total</b></td>
                                        <td>Rs. {{ $billing->subtotal }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Discount Amount</b></td>
                                        <td>
                                            @if ($billing->discountamount == 0)
                                                -
                                            @else
                                                Rs. {{ $billing->discountamount }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Tax
                                                Amount({{ $billing->taxpercent == null ? '0%' : $billing->taxpercent }})</b>
                                        </td>
                                        <td>
                                            @if ($billing->taxamount == 0)
                                                -
                                            @else
                                                Rs. {{ $billing->taxamount }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Shipping</b></td>
                                        <td>
                                            @if ($billing->shipping == 0)
                                                -
                                            @else
                                                Rs. {{ $billing->shipping }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="{{ $quotationsetting->show_picture == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td style="{{ $quotationsetting->show_brand == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td style="{{ $quotationsetting->show_model == 0 ? 'display:none' : '' }}">
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Grand Total</b></td>
                                        <td>Rs. {{ $billing->grandtotal }}</td>
                                    </tr>
                                </tfoot>
                            @elseif ($billing->billing_type_id == 3 || $billing->billing_type_id == 4)
                                <thead>
                                    <tr>
                                        <th style="width: 35%">Particulars</th>
                                        <th class="text-nowrap">Cheque No.</th>
                                        <th class="text-nowrap">Narration</th>
                                        <th style="width: 20%">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($billing->billingextras as $billingextra)
                                        <tr>
                                            <td>{{ $billingextra->particulars }}</td>
                                            <td>{{ $billingextra->cheque_no }}</td>
                                            <td>{{ $billingextra->narration }}</td>
                                            <td>Rs. {{ $billingextra->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><b>Sub Total</b></td>
                                        <td>Rs. {{ $billing->subtotal }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><b>Discount Amount</b></td>
                                        <td>Rs. {{ $billing->discountamount }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><b>Tax
                                                Amount({{ $billing->taxpercent == null ? '0%' : $billing->taxpercent }})</b>
                                        </td>
                                        <td>Rs. {{ $billing->taxamount }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><b>Shipping</b></td>
                                        <td>Rs. {{ $billing->shipping }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><b>Grand Total</b></td>
                                        <td>Rs. {{ $billing->grandtotal }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
                <br>
                <hr>

                <div class="row" style="margin-top: 50px;">
                    <div class="col-md-12" style="float: left; font-size:13px;">
                        <p><b>Remarks: </b>{{ $billing->remarks }}</p>
                    </div>
                    <div class="col-md-12" style="text-align: right; font-size:13px;">
                        <p><b>Signature: </b>...............................</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->

        <div class="page-break"></div>
    @endforeach

</body>

</html>

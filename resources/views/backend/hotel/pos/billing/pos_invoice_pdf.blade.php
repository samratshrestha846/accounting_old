<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if ($billing->is_pos_data == 1 && $billing->outlet_id != null)
        <title>POS Bill</title>
    @else
        <title>{{ $billing_type->billing_types }} Invoice</title>
    @endif
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
                        <p><b>Invoice No: </b>{{ $billing->reference_no }}</p>

                        @if ($billing_type->id == 2 || $billing_type->id == 5)
                            <p><b> Party Bill No:</b> {{ $billing->ledger_no }} </p>
                        @else
                            <p><b> VAT Bill No:</b> {{ $billing->ledger_no }} </p>
                        @endif
                        @if (!$billing->vendor_id == null)
                            <p class="text-left"><b>Supplier Name: </b>{{ $billing->suppliers->company_name }}
                            </p>
                            @if (!$billing->suppliers->company_address == null)<p class="text-left"><b>Company Address :</b>{{ $billing->suppliers->company_address }}</p>@endif
                        @endif
                        @if (!$billing->client_id == null)
                            <p class="text-left"><b>Customer Name: </b>{{ $billing->client->name }}</p>
                            @if (!$billing->client->local_address == null)<p class="text-left"><b>Customer Address :</b>{{ $billing->client->local_address }}</p>@endif
                        @endif
                        <p><b>Payment Mode: </b>
                            @if ($billing->payment_method == 2)
                                Cheque ({{ $billing->bank->bank_name }} / Cheque no.: {{ $billing->cheque_no }})
                            @elseif($billing->payment_method == 3)
                                Bank Deposit ({{ $billing->bank->bank_name }})
                            @elseif($billing->payment_method == 4)
                                Online Portal ({{ $billing->online_portal->name }} / Portal Id:
                                {{ $billing->customer_portal_id }})
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

                    <div style="float: left; width:35%; font-size:13px; padding-right:10px;">
                        <p style="text-align: right;"><b>English Date: </b>{{ $billing->eng_date }}</p>
                        <p style="text-align: right;"><b>Nepali Date: </b>{{ $billing->nep_date }}</p>
                        <p style="text-align: right;"><b>Printed By: </b>{{ Auth::user()->name }}</p>
                        <p style="text-align: right;"><b>Printed On: </b>{{ date('F j, Y') }}</p>
                    </div>
                </div>
            </div>
            <br>

            <div class="row" style="margin-top: 100px;">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-nowrap"></th>
                                <th class="text-nowrap">Items</th>
                                <th class="text-nowrap">Quantity</th>
                                <th class="text-nowrap">Rate</th>
                                <th class="text-nowrap">Discount</th>
                                <th class="text-nowrap">Tax</th>
                                <th class="text-nowrap">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($billing->billingextras as $item)
                                <tr>

                                    @php
                                        $product_image = $item->food->food_image;
                                        
                                        $opciones_ssl = [
                                            'ssl' => [
                                                'verify_peer' => false,
                                                'verify_peer_name' => false,
                                            ],
                                        ];
                                        
                                        $image_path = Storage::disk('uploads')->url($item->food->food_image);
                                        $extencion = pathinfo($image_path, PATHINFO_EXTENSION);
                                        $data = file_get_contents($image_path, false, stream_context_create($opciones_ssl));
                                        $img_base_64 = base64_encode($data);
                                        $productImage = 'data:image/' . $extencion . ';base64,' . $img_base_64;
                                    @endphp

                                    <td>
                                        <img src="{{ $productImage }}" alt="{{ $item->food->food_name }}"
                                            width="60">
                                    </td>
                                    <td>
                                        {{ $item->food->food_name }}
                                    </td>
                                    <td>
                                        {{ $item->quantity }}
                                    </td>
                                    <td>Rs. {{ $item->food->food_price }}</td>
                                    <td class="text-nowrap">
                                        {{ $item->discount_value ? $item->discount_value : '-' }}
                                    </td>
                                    <td class="text-nowrap">
                                        {{ $item->tax_type ? $item->tax_value : '-' }}
                                    </td>
                                    <td>Rs. {{ $item->quantity * $item->food->food_price }}</td>
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
                                <td><b>Sub Total</b></td>
                                <td>Rs. {{ $billing->subtotal }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Discount Amount</b></td>
                                <td>
                                    {{ $billing->discountamount ? $billing->discountamount : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Tax</b></td>
                                <td>
                                    {{ $billing->taxamount ? $billing->taxamount : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Service Charge</b></td>
                                <td>
                                    {{ $billing->servicechargeamount ? $billing->servicechargeamount : '0' }}
                                </td>
                            </tr>
                            @if ($billing->is_cabin)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b>Cabin Charge</b></td>
                                    <td>
                                        {{ $billing->cabinchargeamount ? $billing->cabinchargeamount : '0' }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Grand Total</b></td>
                                <td>Rs. {{ $billing->grandtotal }}</td>
                            </tr>
                        </tfoot>
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
</body>

</html>

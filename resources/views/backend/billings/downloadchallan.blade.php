<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>{{ $billing_type->billing_types }} Challan Bill</title>
    @php
    $invoice_color = App\Models\Company::where('id',$currentcomp->company_id)->first()->invoice_color ?? ""
    @endphp
	<style>
		body{
			margin: 0;
			padding: 0;
			line-height: 20px;
			color: #000;
		}
		img{
			width: auto;
			height: 50px;
		}
		table{
			width: 100%;
			font-size: 12px;
			font-family: "Gill Sans Extrabold", sans-serif;
			border-collapse: collapse;
		}
		table th{
			padding: 5px;
		}
		table td{
			padding: 5px;
		}
		a{
			display: block;
		}
		hr{
			margin: 15px 5px;
			height: 1px;
			background: #dbdbdb;
		}
		.items-table th {
			background:@php echo $invoice_color; @endphp;
			color: #fff;
			padding: 4px 10px;
			font-size: 14px;
		}
		.items-table tbody tr td{
			padding: 5px 10px;
		}
	</style>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th width="50%" style="text-align: left;">
					<b style="font-size: 15px;">{{ $currentcomp->company->name }}</b> <br>
					Phone no.:   {{$currentcomp->company->phone}}<br>
					Email:  {{$currentcomp->company->email}}
				</th>
				<th width="50%" style="text-align: right;">
					<a href="#" title="logo"><img src="{{ $path_img }}" alt="logo"></a>
				</th>
			</tr>
		</thead>
	</table>
	<table style="margin-top:20px;">
		<thead>
			<tr>
				<th width="30%" style="text-align: center;">
					<b style="
					font-size:30px;
					text-transform:uppercase;
					font-weight:bold;
					letter-spacing: 2px;
					color:{{$invoice_color}};
					">Challan Bill</b>
				</th>
			</tr>
		</thead>
	</table>
	<table style="text-align:left;margin-top:20px;">
		<thead>
			<tr>
				<th width="33.3333%" style="font-size:15px;text-transform: uppercase;">Bill To:</th>
				<th width="33.3333%" style="font-size:15px;text-transform: uppercase;">{{--Ship To:--}}</th>
				<th width="33.3333%" style="font-size:15px;text-transform: uppercase;text-align:right;">Invoice No.:{{$billing->reference_no}}</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="vertical-align: top;">
					<table>
						<tbody>
                            @if (!$billing->vendor_id == null)
							<tr>
								<td style="padding:5px 0;line-height:normal;font-size:12px;"><b>{{$billing->suppliers->company_name}}</b></td>
							</tr>
                            <tr>
								<td style="padding:5px 0;line-height:normal;font-size:12px;"><b>Phone:</b>{{$billing->suppliers->company_phone}}</td>
							</tr>
                            <tr>
								<td style="padding:5px 0;line-height:normal;font-size:12px;"><b>Email:</b>{{$billing->suppliers->company_email}}</td>
							</tr>
                            <tr>
								<td style="padding:5px 0;line-height:normal;font-size:12px;"><b>Address:</b>{{$billing->suppliers->company_address}}</td>
							</tr>
                            <tr>
								<td style="padding:5px 0;line-height:normal;font-size:12px;"><b>Pan/Vat:</b>{{$billing->suppliers->pan_vat}}</td>
							</tr>
                            @endif
                            @if (!$billing->client_id == null)
                            <tr>
                               <td style="padding:5px 0;line-height:normal;font-size:12px;"><b>{{$billing->client->name}}</b></td>
                            </tr>
                            <tr>
								<td style="padding:5px 0;line-height:normal;font-size:12px;"><b>Phone:</b>{{$billing->client->phone}}</td>
							</tr>
                            <tr>
								<td style="padding:5px 0;line-height:normal;font-size:12px;"><b>Email:</b>{{$billing->client->email}}</td>
							</tr>
                            <tr>
								<td style="padding:5px 0;line-height:normal;font-size:12px;"><b>Address:</b>{{$billing->client->address}}</td>
							</tr>
                            <tr>
								<td style="padding:5px 0;line-height:normal;font-size:12px;"><b>Pan/Vat:</b>{{$billing->client->pan_vat}}</td>
							</tr>
                            @endif

						</tbody>
					</table>
				</td>
				<td style="vertical-align: top;">
					{{-- <table>
						<tbody>
							<tr>
								<td style="padding:5px 0;line-height:normal;font-size:12px;"><b>Nectar Digit</b></td>
							</tr>
							<tr>
								<td style="padding:5px 0;line-height:normal;"><b>Email:</b> support@gmail.com</td>
							</tr>
							<tr>
								<td style="padding:5px 0;line-height:normal;"><b>Phone:</b> +977 123 456 789</td>
							</tr>
							<tr>
								<td style="padding:5px 0;line-height:normal;"><b>Address:</b> Kathmandu, Nepal</td>
							</tr>
						</tbody>
					</table> --}}
				</td>
				<td style="vertical-align: top;">
					<table>
						<tbody>
							<tr>
								<td style="padding:5px 0;line-height:normal;text-align:right;"><b>Date:</b>{{$billing->eng_date}}</td>
							</tr>
							<tr>
								<td style="padding:5px 0;line-height:normal;text-align:right;"><b>Nepali Date:</b> {{$billing->nep_date}}</td>
							</tr>
							<tr>
								<td style="padding:5px 0;line-height:normal;text-align:right;"><b>Printed By:</b>{{Auth::user()->name}}</td>
							</tr>
							<tr>
								<td style="padding:5px 0;line-height:normal;text-align:right;"><b>Printed No.:</b> {{date('F j, Y')}}</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<table style="text-align: left;margin-top:20px;" class="items-table">
        @if ($billing->billing_type_id == 1)
		<thead>
			<tr>
				<th>#</th>
				<th>Particular</th>
				<th>Quantity</th>
				<th>Rate/Unit</th>
				<th>Discount Amount/Unit</th>
				<th>Tax Amount/Unit</th>
				<th style="text-align: right;">Total</th>
			</tr>
		</thead>
		<tbody>
            @foreach ($billing->billingextras as $key=>$billingextra)
                <tr>
                    <td>{{$key+1}}</td>
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
                    <td style="text-align: right;">Rs. {{$billingextra->total}}</td>
                </tr>
            @endforeach


			<tr style="border-top:1px solid {{$invoice_color}};border-bottom:1px solid {{$invoice_color}};">
				<td></td>
				<td><b style="font-size:12px;">Total</b></td>
				<td></td>
				<td><b style="font-size:12px;"></b></td>
				<td></td>
				<td></td>
				<td style="text-align: right;"><b style="font-size:12px;">Rs. {{$billing->subtotal}}</b></td>
			</tr>
            <tr>
                <td colspan="5" rowspan="5" style="vertical-align: top;padding-top:20px;">
                    <b style="display: block;font-size:14px;">Invoice Amount in Words</b>
                    @php

                        echo ucwords(convertNumberToWord($billing->subtotal).' only');
                    @endphp
                    {{-- One Lakh Thirteen Thousand Nine Hundred and
                    Ninty Four Rupees and Forty Paisa only --}}
                    <b style="display: block;font-size:14px;">Remarks</b>
                    {{$billing->remarks}}
                    <b style="display: block;font-size:14px;margin-top:10px;">Terms and Conditions</b>
                    Thank you for doing business with us
                </td>
                <td style="padding-left:0;padding-top:20px;"><b>Sub Total</b></td>
                <td style="text-align: right;padding-top:20px;">Rs.  {{$billing->subtotal}}</td>
                </tr>
                <tr>
                    <td style="padding-left:0;"><b>Discount</b></td>
                    <td style="text-align: right;">Rs. {{$billing->discountamount}}</td>
                </tr>
                <tr>
                    <td style="padding-left:0;"><b>Tax amount</b></td>
                    <td style="text-align: right;">Rs. {{$billing->taxamount}}</td>
                </tr>
                <tr>
                    <td style="padding-left:0;"><b>Shipping</b></td>
                    <td style="text-align: right;">Rs. {{$billing->shipping}}</td>
                </tr>
                {{-- <tr>
                    <td style="padding-left:0;"><b>VAT 13%@13.0%</b></td>
                    <td style="text-align: right;">Rs. 1450.00</td>
                </tr> --}}
                <tr style="background:{{$invoice_color}};color:#fff;">

                    <td><b>Total</b></td>
                    <td style="text-align: right;">Rs. {{$billing->grandtotal}}</td>
                </tr>

		</tbody>
        @endif
	</table>
	<table style="margin-top:20px;">
		<tbody>
			<tr>
				<td><b>Buyer: </b> @if(!$billing->vendor_id == null) {{$billing->suppliers->company_name}} @endif
                    @if(!$billing->client_id == null) {{$billing->client->name}} @endif
                </td>
				<td style="text-align: right;"><b>Issued By: </b>{{ $currentcomp->company->name }}</td>
			</tr>
			<tr>
				<td width="50%" style="padding-top:30px;">
					<b style="display: block;">---------------------------------------------------------------</b>
					Signature & Stamp
				</td>
				<td width="50%" style="padding-top:30px;text-align: right;">
					<b style="display: block;">---------------------------------------------------------------</b>
					Authorized Signatory
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>

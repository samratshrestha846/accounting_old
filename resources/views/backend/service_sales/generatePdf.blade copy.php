<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<style>
		body{
			margin: 0;
			padding: 0;
			line-height: 20px;
			color: #000;
		}
		/* img{
			width: 100%;
			max-width: 300px;
		} */
		table{
			width: 100%;
			font-size: 9px;
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
		}
		table th{
			padding: 5px;
		}
		table td{
			padding: 5px;
		}
		ul{
			margin: 0;
			padding: 0;
			margin-top: 15px;
		}
		li{
			list-style: none;
			display: inline-block;
			position: relative;
			padding-left: 18px;
			text-align: right;
		}
		li+li{
			margin-left: 25px;
		}
		li span{
			display: block;
		}
		li img{
			position: absolute;
			height: 10px;
			width: auto;
			left: 0;
			top: 8px;
			width: auto;
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
			background: #df4c1b;
			color: #fff;
			padding: 5px 10px;
		}
		.items-table tbody tr+tr {
			border-top: 2px solid #dbdbdb;
		}
		.items-table tbody tr td{
			padding: 5px 10px;
		}
        .logoimage{
            max-height: 75px;
        }
	</style>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th width="50%" style="text-align: left;">
					<a href="#" title="logo"><img class="logoimage" src="{{ $path_img }}" alt="logo"></a>
				</th>
                <th width="50%" style="text-align: right;">
					<b style="background:#df4c1b;
					font-size:18px;
					text-transform:uppercase;
					font-weight:bold;
					letter-spacing: 1px;
					color:#fff;
					padding:7px 20px;
                    text-align:center;
                    line-height:normal;
                    display:inline-block;
					">Invoice</b>
				</th>
			</tr>
		</thead>
	</table>
	<table style="">
		<thead>
			<tr>
				<th style="text-align: center;">
					<b style="font-size: 20px;
					color:#df4c1b;
					text-transform: uppercase;
                    line-height:1.2;
					font-weight:bold;
					"
					>{{ $currentcomp->company->name }}</b>
					<ul>
						<li>
							<img src="{{ $path_img_address }}" alt="images">
							<span>{{ $currentcomp->company->local_address }}, {{ $currentcomp->company->provinces->eng_name }}</span>
						</li>
						<li>
							<img src="{{ $path_img_phn }}" alt="images">
							<span>{{$currentcomp->company->phone}}</span>
							{{-- <span>+977 123 456 789</span> --}}
						</li>
						<li>
							<img src="{{ $path_img_web }}" alt="images">
							<span>{{$currentcomp->company->email}}</span>
							{{-- <span>info@gmail.com</span> --}}
						</li>
					</ul>
				</th>
			</tr>
		</thead>
	</table>
	<hr>
	<table style="text-align:left;margin-top:20px;">
		<thead>
			<tr>
                @if(!$billing->client_id==null)
                <th width="33.3333%" style="font-size:12px;text-transform: uppercase;text-align:left;">Invoice To:{{$billing->client->name}}</th>
                @endif
                @if(!$billing->vendor_id==null)
                <th width="33.3333%" style="font-size:12px;text-transform: uppercase;">Invoice of:{{$billing->suppliers->company_name}}</th>
                @endif
				<th width="33.3333%" style="font-size:12px;text-transform: uppercase;"></th>
				<th width="33.3333%" style="font-size:12px;text-transform: uppercase;"></th>

			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<table>
						<tbody>
                            <tr>
								<td style="padding:5px 0;line-height:normal;">Invoice No:</td>
								<td style="padding:5px 0;line-height:normal;">{{$billing->reference_no}}</td>
							</tr>
                            <tr>
								<td style="padding:5px 0;line-height:normal;">Date:</td>
								<td style="padding:5px 0;line-height:normal;">{{$billing->eng_date}}/{{$billing->nep_date}}</td>
							</tr>
							@if(!$billing->client_id==null)
                            <tr>
								<td style="padding:5px 0;line-height:normal;">Customer Address:</td>
								<td style="padding:5px 0;line-height:normal;">{{$billing->client->local_address ?? ''}}</td>
							</tr>
                            @endif
                            @if(!$billing->vendor_id==null)
                            <tr>
								<td style="padding:5px 0;line-height:normal;">Supplier Address:</td>
								<td style="padding:5px 0;line-height:normal;">{{$billing->suppliers->company_address ?? ''}}</td>
							</tr>
                            @endif


						</tbody>
					</table>
				</td>
                <td></td>
				<td>
					<table>
						<tbody>
                            <tr>
								<td style="padding:5px 0;line-height:normal;">Payment Mode:</td>
								<td style="padding:5px 0;line-height:normal;">@if($billing->payment_method == 2)
                                        Cheque ({{ $billing->bank->bank_name ?? '' }} / Cheque no.: {{ $billing->cheque_no }})
                                    @elseif($billing->payment_method == 3)
                                        Bank Deposit ({{ $billing->bank->bank_name ?? '' }})
                                    @elseif($billing->payment_method == 4)
                                        Online Portal ({{ $billing->online_portal->name ?? '' }} / Portal Id: {{ $billing->customer_portal_id }})
                                    @else
                                        Cash
                                    @endif
                                 </td>
							</tr>
                            <tr>
								<td style="padding:5px 0;line-height:normal;">Vat Bill No:</td>
								<td style="padding:5px 0;line-height:normal;">{{ $billing->ledger_no }}</td>
							</tr>
                            <tr>
								<td style="padding:5px 0;line-height:normal;">Fiscal-Year:</td>
								<td style="padding:5px 0;line-height:normal;">{{$billing->fiscal_year->fiscal_year}}</td>
							</tr>

						</tbody>
					</table>
				</td>

			</tr>
		</tbody>
	</table>
	<table style="text-align: left;margin-top:20px;" class="items-table">
		<thead>
			<tr>
				<th>S.N.</th>
				<th>Particulars</th>
				<th>Quantity</th>
				<th>Rate</th>
				<th>Discount(Per Unit)</th>
				<th>Tax(Per Unit)</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($billing->serviceSalesExtra as $key=>$serviceSaleBillextra)
                <tr>
                    <td>{{$key+1}}</td>
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

			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><b style="font-size:12px;">Subtotal</b></td>
				<td><b style="font-size:12px;">Rs. {{ $billing->subtotal }}</b></td>
			</tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Discount Amount</b></td>
                <td>
                    @if ($billing->alldiscounttype == "fixed")
                        Rs. {{ $billing->discountamount }}
                    @elseif ($billing->alldiscounttype == "percent")
                        {{ $billing->discountpercent }} %
                    @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Tax Amount({{ $billing->taxpercent == null ? '0%' : $billing->taxpercent }})</b>
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
                <td></td>
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
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Grand Total</b></td>
                <td>Rs. {{ $billing->grandtotal }}</td>
            </tr>
		</tbody>
	</table>
	<table style="margin-top:20px;">

		<tbody>
            <tr>
				<td><b>Remarks: </b>{{$billing->remarks}}</td>

			</tr>
			<tr>
				<td><b>Buyer: </b> Nectar Digit Private Limited</td>
				<td><b>Issued By: </b> Nectar Digit Private Limited</td>
			</tr>
			<tr>
				<td width="50%" style="padding-top:30px;">
					<b style="display: block;">---------------------------------------------------------------</b>
					Signature & Stamp
				</td>
				<td width="50%" style="padding-top:30px;">
					<b style="display: block;">---------------------------------------------------------------</b>
					Signature & Stamp
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>


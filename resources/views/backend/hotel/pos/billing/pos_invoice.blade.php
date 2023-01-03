<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('css/pos.css') }}">
        <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('backend/dist/css/adminlte.min.css')}}">
        <!-- keyboard -->
        <link href="{{ asset('css/keyboard.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/metisMenu.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('css/line-awesome.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/animate.css')}}">

        <style>
            body {
                text-align: center;
                color: #000;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                line-height: 1;
            }

            .mt-3{
                margin-top: 12px !important;
            }
            .wrapper{
                width: 280px;
                margin: 0 auto;
            }
            .text-right{
                text-align: right;
            }
            .text-left{
                text-align: left;
            }
            p{
                font-size: 12px;
                color: #000;
            }
            table {
                width: 100%;
            }
            .table thead th{
                padding: 0;
            }
            .brand-logo{
                max-width: 250px;
                width: auto;
                text-align: center;
            }
            .company-name{
                text-transform:uppercase;
                font-size: 0.9rem;
                font-weight: bold;
                text-align: center;
            }

            .company-address{
                text-align: center;
            }
            .col{
                width: 100%;
                display: flex;
                padding-left: 0;
                padding-right: 0;
                margin-bottom: 4px;
            }
            .col .col-6{
                flex-grow: 1;
                padding-left: 0;
            }
            .col .col-12{
                padding-left: 0;
            }
            .product-table{
                border-bottom: 1px solid #111;
            }
            .product-table th{
                padding-top: 2px;
                padding-bottom: 2px;
                border: none;
                border-bottom: 1px solid #111;
            }

            table {
                table-layout: fixed;
            }
            .table td, .table th {
                word-wrap: break-word;
            }
            .btn {
                width:100%;
                cursor:pointer;
                font-size:12px;
                text-align: center;
                border:1px solid #FFA93C;
                padding: 10px 1px;
                font-weight:bold;
                border-radius: 0;
            }
            .btn-success {
                color: rgb(0, 0, 0);
                background-color: rgb(79, 169, 80);
                border: 2px solid rgb(79, 169, 80);
            }
            .btn-warning {
                background-color: rgb(255, 169, 60);
                color: rgb(0, 0, 0);
                border: 1px solid rgb(255, 169, 60);
            }

            .btn-primary{
                color: rgb(255, 255, 255);
                background-color: rgb(0, 127, 255);
                border: 2px solid rgb(0, 127, 255);
            }
        </style>
        @stack('styles')
    </head>

    <body id="invoiceBill" class="font-sans antialiased">
        <div class="wrapper">
            @php
                $company = $userCompany->company;
                $customer = $billing->client;
                $grandtotal = $billing->grandtotal;
                $totalItems = count($billing->billingextras);
                $totalPaidAmt = collect($billing->payment_infos)->sum('total_paid_amount');
                $changeAmt = $totalPaidAmt <= $grandtotal ? 0 : ($totalPaidAmt - $grandtotal);
            @endphp
            <img class="brand-logo" src="{{url('/')}}/uploads/{{$company->company_logo}}" alt="Biller logo">
            <h3 class="company-name">{{$company->name}}</h3>
            <p class="company-address">{{$company->local_address}}, {{$company->districts->dist_name}}, Nepal</p>
            <div class="col">
                <div class="col-6 text-left">
                    <span>TAX/PAN Number: {{$company->pan_vat}}</span>
                </div>
                <div class="col-6 text-right">
                    <span>Tel: {{$company->phone}}</span>
                </div>
            </div>
            <div class="col">
                <div class="col-12 text-left">
                    <span>Reference No: {{$billing->reference_no}}</span>
                </div>
            </div>
            <div class="col">
                <div class="col-12 text-left">
                    <span>Pos Header Name: Nectargit</span>
                </div>
            </div>
            <div class="col">
                <div class="col-6 text-left">
                    <span>Customer: {{$customer->name}}</span>
                </div>
                <div class="col-6 text-right">
                    <span>Date: {{$billing->eng_date}}</span>
                </div>
            </div>
            <div class="mt-3">
                <table class="table" cellspacing="0" border="0">
                    <thead>
                        <tr class="product-table">
                            <th><em>#</em></th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billing->billingextras as $key => $billingExtra)
                        <tr>
                            <td style="text-align:center; width:30px;">{{$key+1}}</td>
                            <td style="text-align:left; width:180px;">{{$billingExtra->food ? $billingExtra->food->food_name : 'Undefined'}}</td>
                            <td style="text-align:center; width:50px;">{{$billingExtra->quantity}}</td>
                            <td style="text-align:right; width:55px; ">{{$billingExtra->rate}}</td>
                            <td style="text-align:right; width:65px;">{{$billingExtra->total}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <table class="totals" cellspacing="0" border="0">
                    <tbody>
                        <tr>
                            <td style="text-align:left;">Total Items</td>
                            <td style="text-align:right; padding-right:1.5%; border-right: 1px solid #999;font-weight:bold;">{{$totalItems}}</td>
                            <td style="text-align:left; padding-left:1.5%;">Total</td>
                            <td style="text-align:right;font-weight:bold;">{{$billing->subtotal}}</td>
                        </tr>
                        <tr>
                            <td></td> <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left;">Discount</td>
                            <td colspan="2" style="text-align:right;font-weight:bold;">{{$billing->discountamount}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left;">Tax</td>
                            <td colspan="2" style="text-align:right;font-weight:bold;">{{$billing->taxamount}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left;">Service Charge({{$billing->service_charge ?? 0}}%)</td>
                            <td colspan="2" style="text-align:right;font-weight:bold;">{{$billing->servicechargeamount ?? 0}}</td>
                        </tr>
                        @if($billing->is_cabin)
                        <tr>
                            <td colspan="2" style="text-align:left;">Cabin Charge</td>
                            <td colspan="2" style="text-align:right;font-weight:bold;">{{$billing->cabinchargeamount ?? 0}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="2" style="text-align:left; font-weight:bold; border-top:1px solid #000; padding-top:10px;">Total Payable</td>
                            <td colspan="2" style="border-top:1px solid #000; padding-top:10px; text-align:right; font-weight:bold;">{{$billing->grandtotal}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">Paid</td>
                            <td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">{{$totalPaidAmt}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">Change</td>
                            <td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">{{$changeAmt}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @yield('content')
        </div>
        <!-- jQuery -->
        <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset('backend/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script src="{{ asset('backend/plugins/jquery/jquery.printPage.js') }}"></script>

        @stack('scripts')
    </body>
</html>

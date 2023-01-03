<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $setting->company_name }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            padding: 8px;
            /* font-size: 11px; */
        }
        body {
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
                        <b style="font-size: 25px;display: block;font-weight: 500;margin-bottom: -10px;color: #583949;">{{ $currentcomp->company->name }}</b> <br>
                        {{ $currentcomp->company->local_address }}, {{ $currentcomp->company->provinces->eng_name }} <br>
                        {{$currentcomp->company->email}}<br>
                        {{$currentcomp->company->phone}}
                    </div>
                </div>
                <br><br><br><br>

                <div class="row">
                    <div class="col-md-12">
                        <div style="float: left;">
                                <b>Customers<br><br>

                        </div>

                    </div>
                </div> <br><br><br><br>

                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                        <table class="table mt-5" style="width: 100%;">
                            <thead style="background-color: #343a40;">
                                <tr>
                                    <th style="width: 250px; color:aliceblue">Customer Name</th>
                                    <th style="width: 20px; color:aliceblue">Bill Amount</th>
                                    <th style="color:aliceblue;">Paid Amount</th>
                                    <th style="color:aliceblue;">Remaining Amount</th>


                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>
                                        Rs. {{ $customer->salesBillings->sum('grandtotal') - $customer->salesReturnBillings->sum('grandtotal') }}
                                    </td>
                                    <td>
                                        @php
                                            $total_paid_amount = 0;
                                            $total_received_amount = 0;
                                            foreach ($customer->salesBillings as $billing)
                                            {
                                                $paid_amount_sum = $billing->payment_infos->sum('total_paid_amount');
                                                $total_paid_amount += $paid_amount_sum;
                                            }
                                            foreach ($customer->salesReturnBillings as $returnedBilling)
                                            {
                                                $received_amount_sum = $returnedBilling->payment_infos->sum('total_paid_amount');
                                                $total_received_amount += $received_amount_sum;
                                            }
                                        @endphp
                                        Rs. {{ $total_paid_amount - $total_received_amount }}
                                    </td>
                                    <td>
                                        Rs. {{ ($customer->salesBillings->sum('grandtotal') - $total_paid_amount) - ($customer->salesReturnBillings->sum('grandtotal') - $total_received_amount) }}
                                    </td>

                                </tr>
                            @empty
                                <tr><td colspan="5">No any customers.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <br><br><br>


                <hr>

                <br><br><br>

                <div class="row">
                    <div class="col-md-12">
                        <div style="float: left; width:35%">
                            <b>Prepared By</b><br><br>
                            Name:<br><br>
                            Date:<br><br>
                            Authorized Signature:<br><br>
                        </div>
                        <div style="float: left; width:35%">
                            <b>Received By</b><br><br>
                            Name:<br><br>
                            Date:<br><br>
                            Authorized Signature:<br><br>

                        </div>
                        <div style="float: left; width:30%">
                            <b>Approved By</b><br><br>
                            Name:<br><br>
                            Date:<br><br>
                            Authorized Signature:<br><br>
                        </div>
                    </div>
                </div>
        </div>
    </section>
    <!-- /.content -->
</body>
</html>

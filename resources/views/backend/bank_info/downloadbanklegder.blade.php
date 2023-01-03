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
                                <b>Bank Ledgers<br><br>

                        </div>

                    </div>
                </div> <br><br><br><br>

                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                        <table class="table mt-5" style="width: 100%;">
                            <thead style="background-color: #343a40;">
                                <tr>
                                    <th style="color:aliceblue;">Bank Name</th>
                                    <th style="color:aliceblue;">Account Holder</th>
                                    <th style="color:aliceblue;">Received Amount</th>
                                    <th style="color:aliceblue;">Paid Amount</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($banks as $bank)
                                            <tr>
                                                <td>{{ $bank->bank_name }}</td>
                                                <td>{{ $bank->account_name }}</td>
                                                <td>
                                                    Rs. {{ $bank->salesBillings->sum('grandtotal') + $bank->purchaseReturnBillings->sum('grandtotal') + $bank->receiptBillings->sum('grandtotal') }}
                                                </td>
                                                <td>
                                                    Rs. {{ $bank->purchaseBillings->sum('grandtotal') + $bank->salesReturnBillings->sum('grandtotal') + $bank->paymentBillings->sum('grandtotal') }}
                                                </td>

                                            </tr>
                                        @empty
                                            <tr><td colspan="5">No any banks.</td></tr>
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

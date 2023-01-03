<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $payroll->staff->name }} Payroll</title>
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
                    <div style="float: left; width:50%; font-size:13px;">
                        <p><b>Staff Name: </b>{{ $payroll->staff->name }}</p><br>
                        <p><b>Designation: </b>{{ $payroll->staff->position->name }}</p>
                    </div>

                    <div style="float: left; width:50%; font-size:13px; padding-right:10px;">
                        <p style="text-align: right;"><b>English Date: </b>{{ $payroll->date }}</p><br>
                        <p style="text-align: right;"><b>Nepali Date: </b>{{ $payroll->nepali_date }}</p><br>
                        <p style="text-align: right;"><b>Payment Type:
                            </b>{{ $payroll->advance_regular == 1 ? 'Advance' : 'Regular' }}</p>
                    </div>
                </div>
            </div>
            <br>

            <div class="row" style="margin-top: 100px;">
                <div class="col-md-12">
                    <table style="width: 100%">
                        <thead style="background-color: #343a40; color: #ffffff; font-size:13px;">
                            <tr>
                                <th style="width: 50%">Particulars</th>
                                <th style="width: 50%;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    {{ $payroll->advance_regular == 1 ? 'Advance' : 'Regular' }} Payment
                                    <br><br><br>
                                </td>
                                <td>Rs. {{ $payroll->amount_paid }}<br><br><br></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <hr>

            <div class="row" style="margin-top: 30px;">
                <div class="col-md-12" style="float: left; font-size:13px;">
                    <p><b>Paid By: </b>............................</p>
                </div>
                <div class="col-md-12" style="text-align: right; font-size:13px;">
                    <p><b>Received By: </b>...............................</p>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</body>

</html>

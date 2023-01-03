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
    <link rel="stylesheet"
        href="{{ asset('backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

    <style>
        table,
        th,
        td {
            border-collapse: collapse;
            text-align: left;
            padding: 5px;
            vertical-align: top;
        }

        body {
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

    </style>
</head>

<body>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width:33.3333%;text-align:left;">
                            <img src="{{ $path_img }}" alt="" style="height: 80px;width:auto;">
                        </td>
                        <td style="width:33.3333%;line-height:20px;text-align:center;">
                            <b
                                style="font-size: 20px;display: block;font-weight: 500;color: #583949;margin-bottom:5px;">{{ $currentcomp->company->name }}</b>
                            <span style="display: block;">{{ $currentcomp->company->local_address }},
                                {{ $currentcomp->company->provinces->eng_name }}</span>
                            <span style="display: block;">{{ $currentcomp->company->email }}</span>
                            <span style="display: block;">{{ $currentcomp->company->phone }}</span>
                        </td>
                        <td style="width:33.3333%;"></td>
                    </tr>
                </tbody>
            </table>
            <table style="width:100%;">
                <tbody>
                    <tr>
                        <td style="width:50%;vertical-align:top;padding:0;">
                            <table>
                                <tbody>
                                    @if (!$journalVoucher->vendor_id == null)
                                        <tr>
                                            <td style="text-align: left;padding-left:0;"><b>Supplier:</b></td>
                                            <td style="text-align: left;">{{ $journalVoucher->supplier->company_name }}</td>
                                        </tr>
                                    @endif
                                    @if (!$journalVoucher->client_id == null)
                                        <tr>
                                            <td style="text-align: left;"><b>Customer:</b></td>
                                            <td style="text-align: left;">{{ $journalVoucher->client->name }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </td>
                        <td style="width:50%;vertical-align:top;padding:0;text-align:left;">
                            <table style="width:100%;">
                                <tbody>
                                    <tr>
                                        <td style="text-align:left;"><b>Date:</b></td>
                                        <td style="text-align:left;">{{ $journalVoucher->entry_date_nepali }} /
                                            {{ $journalVoucher->entry_date_english }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;"><b>Journal Voucher no.:</b></td>
                                        <td style="text-align:left;">{{ $journalVoucher->journal_voucher_no }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 250px;border:1px solid #000;">Particulars</th>
                        <th style="width: 40px;border:1px solid #000;">LF no.</th>
                        <th style="border:1px solid #000;">Debit Amount</th>
                        <th style="border:1px solid #000;">Credit Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border:1px solid #000;line-height:24px;">
                            @php
                                $particulars = '';
                                foreach ($journal_extras as $extra) {
                                    $child_account = \App\Models\ChildAccount::where('id', $extra->child_account_id)->first();
                                    if ($extra->debitAmount == 0) {
                                        $to = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To ';
                                    } else {
                                        $to = '';
                                    }
                                    $particulars = $particulars . $to . $child_account->title . '<br>';
                                }
                                echo $particulars;
                            @endphp
                        </td>

                        <td style="border:1px solid #000;line-height:24px;"></td>
                        <td style="border:1px solid #000;line-height:24px;">
                            @php
                                $debit_amounts = '';
                                foreach ($journal_extras as $extra) {
                                    if ($extra->debitAmount == 0) {
                                        $debit_amounts = $debit_amounts . '-' . '<br>';
                                    } else {
                                        $debit_amounts = $debit_amounts . 'Rs. ' . $extra->debitAmount . '<br>';
                                    }
                                }
                                echo $debit_amounts;
                            @endphp
                        </td>

                        <td style="border:1px solid #000;line-height:24px;">
                            @php
                                $credit_amounts = '';
                                foreach ($journal_extras as $extra) {
                                    if ($extra->creditAmount == 0) {
                                        $credit_amounts = $credit_amounts . '-' . '<br>';
                                    } else {
                                        $credit_amounts = $credit_amounts . 'Rs. ' . $extra->creditAmount . '<br>';
                                    }
                                }
                                echo $credit_amounts;
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000;" colspan="2"><b>Total</b></td>
                        <td style="border:1px solid #000;">Rs. {{ $journalVoucher->debitTotal }}</td>
                        <td style="border:1px solid #000;">Rs. {{ $journalVoucher->creditTotal }}</td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="padding-bottom:0;padding-top:10px;">
                            <b>Narration:</b> ( {{ $journalVoucher->narration }} )
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <hr>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="width:100%;">
                <thead>
                    <tr>
                        <th>Prepared By ....................</th>
                        <th>Submitted By ....................</th>
                        <th>Approved By ....................</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Designation:</td>
                        <td>Designation:</td>
                        <td>Designation:</td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td>Date:</td>
                        <td>Date:</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <!-- /.content -->
</body>

</html>

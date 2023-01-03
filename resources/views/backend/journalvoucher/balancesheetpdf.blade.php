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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 3px 7px;
            font-size:14px;
            text-align: left;
        }
    </style>
</head>
<body>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
                <div class="row">
                    <div class="col-md-1" style="float: left;">
                        <img src="{{ $path_img }}" alt="" style="height: 80px;width:auto;">
                    </div>
                    <div class="col-md-11" style="text-align: right;line-height:18px;font-size:14px;">
                        <b style="font-size: 22px;display: block;font-weight: 600;color: #dd442c;margin-bottom:8px;">{{ $currentcomp->company->name }}</b>
                        <span style="display: block;">{{ $currentcomp->company->local_address }}, {{ $currentcomp->company->provinces->eng_name }}</span>
                        <span style="display: block;">{{$currentcomp->company->email}}</span>
                        <span style="display: block;">{{$currentcomp->company->phone}}</span>
                    </div>
                </div>
                <div style="text-align: center">
                    <span style="display: block;margin-top:15px;font-size:20px;font-weight:bold;margin-bottom:10px;">Balance Sheet</span>
                    <span style="display: block;margin-bottom:10px;">As on {{ $today_date_nepali }}</span>
                </div>
                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                        <table style="width: 100%">
                            <thead style="background-color: #de4a32;color:#fff;">
                                <tr>
                                    <th style="text-align: left;">Accounts</th>
                                    <th style="text-align: left;">Total Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr style="background-color: #ebf3fb;">
                                    <td style="font-size: 15px;">
                                        Equity and Liailities
                                    </td>
                                    <td></td>
                                </tr>

                                @php
                                    $equity_calculation_array = [];
                                    array_push($equity_calculation_array, $retained_earnings);
                                    $assets_calculation_array = [];
                                    $shareholder_equity = \App\Models\SubAccount::where('sub_account_id', null)->where('account_id', $main_accounts[2]->id)->where('slug', 'shareholders-equity')->first();
                                    $inside_shareholder_equity = \App\Models\SubAccount::where('sub_account_id', $shareholder_equity->id)->get();
                                    $child_accounts_equity = \App\Models\ChildAccount::where('sub_account_id', $shareholder_equity->id)->get();
                                @endphp

                                <tr>
                                    <td style="padding-left: 5%;font-size:13px;font-weight:500;">
                                        <b>{{ $shareholder_equity->title }}</b>
                                    </td>
                                    <td></td>
                                </tr>

                                @foreach ($inside_shareholder_equity as $subAccount)
                                    @php
                                        $total_amount = 0;
                                        $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subAccount->id)->get();
                                        foreach($child_accounts as $child_account){
                                            $child_account_closing_balance = $child_account->this_year_opening_balance->closing_balance ?? 0;
                                            $total_amount += $child_account_closing_balance ?? 0;
                                        }

                                        array_push($equity_calculation_array, $total_amount);
                                    @endphp
                                    <tr>
                                        <td style="padding-left:10%;">
                                            <em><b>{{$subAccount->title}}</b></em>
                                        </td>
                                        <td>
                                            @if ($total_amount < 0)
                                                Rs. {{number_format($total_amount * -1, 2)}} Cr
                                            @else
                                                Rs. {{number_format($total_amount, 2)}} Dr
                                            @endif
                                        </td>
                                    </tr>
                                    @foreach ($child_accounts as $child_account)
                                    <tr>
                                        <td style="padding-left: 20%;">
                                            <em>{{$child_account->title}}</em>
                                        </td>
                                        <td>
                                            @php
                                                $closing_balance = $child_account->this_year_opening_balance->closing_balance ?? 0;
                                            @endphp
                                            @if ($closing_balance < 0)
                                                Rs. {{number_format($closing_balance * 1, 2)}} Cr
                                            @else
                                                Rs. {{number_format($closing_balance, 2)}} Dr
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach

                                @foreach ($child_accounts_equity as $child_account)
                                    <tr>
                                        <td style="padding-left: 10%;">
                                            <em>{{ $child_account->title }}</em>
                                        </td>
                                            @php
                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                                                        $q->where('is_cancelled', 0)->where('status', 1);
                                                    })
                                                    ->where('child_account_id', $child_account->id)->get();
                                                    $debitamount = [];
                                                    $creditamount = [];
                                                    foreach ($journal_extras as $jextra) {
                                                        array_push($debitamount, $jextra->debitAmount);
                                                        array_push($creditamount, $jextra->creditAmount);
                                                    }
                                                    $debitsum = array_sum($debitamount);
                                                    $creditsum = array_sum($creditamount);
                                                    $child_opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;

                                                    $diff_amount = $child_opening_balance - $creditsum + $debitsum;

                                                    array_push($equity_calculation_array, $diff_amount);
                                            @endphp

                                        <td>
                                            @if ($diff_amount < 0)
                                                Rs. {{ number_format($diff_amount * -1, 2) }} Cr
                                            @elseif ($diff_amount > 0)
                                                Rs. {{ number_format($diff_amount, 2) }} Dr
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td style="padding-left: 10%;"><em>Net Profit / Loss</em></em></td>
                                    <td>
                                        @if ($retained_earnings < 0)
                                            Rs. {{ number_format($retained_earnings * -1, 2) }} Cr
                                        @elseif ($retained_earnings > 0)
                                            Rs. {{ number_format($retained_earnings, 2) }} Dr
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>

                                @php
                                    $sub_accounts = \App\Models\SubAccount::where('sub_account_id', null)->where('account_id', $main_accounts[1]->id)->get();
                                @endphp

                                @foreach ($sub_accounts as $sub_account)
                                    @php
                                        $inside_sub_accounts = \App\Models\SubAccount::where('sub_account_id', $sub_account->id)->get();
                                    @endphp
                                    <tr>
                                        <td style="padding-left: 5%;">
                                            <b>{{ $sub_account->title }}</b>
                                        </td>
                                        <td></td>
                                    </tr>
                                    @foreach ($inside_sub_accounts as $subAccount)
                                        @php
                                            $total_amount = 0;
                                            $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subAccount->id)->get();
                                            foreach($child_accounts as $child_account){
                                                $child_account_closing_balance = $child_account->this_year_opening_balance->closing_balance ?? 0;
                                                $total_amount += $child_account_closing_balance ?? 0;
                                            }

                                            array_push($equity_calculation_array, $total_amount);
                                        @endphp
                                        <tr>
                                            <td style="padding-left:10%;">
                                                <em><b>{{$subAccount->title}}</b></em>
                                            </td>
                                            <td>
                                                @if ($total_amount < 0)
                                                    Rs. {{number_format($total_amount * -1, 2)}} Cr
                                                @else
                                                    Rs. {{number_format($total_amount, 2)}} Dr
                                                @endif
                                            </td>
                                        </tr>
                                        @foreach ($child_accounts as $child_account)
                                        <tr>
                                            <td style="padding-left: 20%;">
                                                <em>{{$child_account->title}}</em>
                                            </td>
                                            <td>
                                                @php
                                                    $closing_balance = $child_account->this_year_opening_balance->closing_balance ?? 0;
                                                @endphp
                                                @if ($closing_balance < 0)
                                                    Rs. {{number_format($closing_balance * 1, 2)}} Cr
                                                @else
                                                    Rs. {{number_format($closing_balance, 2)}} Dr
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach

                                    @php
                                        $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $sub_account->id)->get();
                                    @endphp

                                    @foreach ($child_accounts as $child_account)
                                        <tr>
                                            <td style="padding-left: 10%;">
                                                <em>{{ $child_account->title }}</em>
                                            </td>
                                                @php
                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year)
                                                        {
                                                            $q->where('is_cancelled', 0)->where('status', 1);
                                                        })
                                                        ->where('child_account_id', $child_account->id)->get();
                                                        $debitamount = [];
                                                        $creditamount = [];
                                                        foreach ($journal_extras as $jextra) {
                                                            array_push($debitamount, $jextra->debitAmount);
                                                            array_push($creditamount, $jextra->creditAmount);
                                                        }
                                                        $debitsum = array_sum($debitamount);
                                                        $creditsum = array_sum($creditamount);
                                                        $child_opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;

                                                        $diff_amount = $child_opening_balance - $creditsum + $debitsum;
                                                        array_push($equity_calculation_array, $diff_amount);
                                                @endphp

                                            <td>
                                                @if ($diff_amount < 0)
                                                    Rs. {{ number_format($diff_amount * -1, 2) }} Cr
                                                @elseif ($diff_amount > 0)
                                                    Rs. {{ number_format($diff_amount, 2) }} Dr
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach


                                <tr style="background-color: #0078d4; color: white;">
                                    <th>Total Equity and Liabilities</th>
                                    <th>
                                        Rs. {{ array_sum($equity_calculation_array) < 0 ? number_format(array_sum($equity_calculation_array) * -1, 2) . ' Cr' : number_format(array_sum($equity_calculation_array), 2). ' Dr'}}
                                    </th>
                                </tr>


                                <tr style="background-color: #ebf3fb;">
                                    <td style="font-size: 16px;">
                                        Assets
                                    </td>
                                    <td></td>
                                </tr>

                                @php
                                    $sub_accounts = \App\Models\SubAccount::where('sub_account_id', null)->where('account_id', $main_accounts[0]->id)->get();
                                @endphp

                                @foreach ($sub_accounts as $sub_account)
                                    @php
                                        $inside_sub_accounts = \App\Models\SubAccount::where('sub_account_id', $sub_account->id)->get();
                                    @endphp
                                    <tr>
                                        <td style="padding-left: 5%;">
                                            <b>{{ $sub_account->title }}</b>
                                        </td>
                                        <td></td>
                                    </tr>

                                    @foreach ($inside_sub_accounts as $subAccount)
                                        @php
                                            $total_amount = 0;
                                            $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subAccount->id)->get();
                                            foreach($child_accounts as $child_account){
                                                $child_account_closing_balance = $child_account->this_year_opening_balance->closing_balance ?? 0;
                                                $total_amount += $child_account_closing_balance ?? 0;
                                            }

                                            array_push($assets_calculation_array, $total_amount);
                                        @endphp
                                        <tr>
                                            <td style="padding-left:10%;">
                                                <em><b>{{$subAccount->title}}</b></em>
                                            </td>
                                            <td>
                                                @if ($total_amount < 0)
                                                    Rs. {{number_format($total_amount * -1, 2)}} Cr
                                                @else
                                                    Rs. {{number_format($total_amount, 2)}} Dr
                                                @endif
                                            </td>
                                        </tr>
                                        @foreach ($child_accounts as $child_account)
                                        <tr>
                                            <td style="padding-left: 20%;">
                                                <em>{{$child_account->title}}</em>
                                            </td>
                                            <td>
                                                @php
                                                    $closing_balance = $child_account->this_year_opening_balance->closing_balance ?? 0;
                                                @endphp
                                                @if ($closing_balance < 0)
                                                    Rs. {{number_format($closing_balance * 1, 2)}} Cr
                                                @else
                                                    Rs. {{number_format($closing_balance, 2)}} Dr
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach

                                    @php
                                        $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $sub_account->id)->get();
                                    @endphp

                                    @foreach ($child_accounts as $child_account)
                                        <tr>
                                            <td style="padding-left: 10%;">
                                                <em>{{ $child_account->title }}</em>
                                            </td>
                                                @php
                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year)
                                                        {
                                                            $q->where('is_cancelled', 0)->where('status', 1);
                                                        })
                                                        ->where('child_account_id', $child_account->id)->get();
                                                        $debitamount = [];
                                                        $creditamount = [];
                                                        foreach ($journal_extras as $jextra) {
                                                            array_push($debitamount, $jextra->debitAmount);
                                                            array_push($creditamount, $jextra->creditAmount);
                                                        }
                                                        $debitsum = array_sum($debitamount);
                                                        $creditsum = array_sum($creditamount);
                                                        $child_opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;
                                                        $diff_amount = $child_opening_balance + $debitsum - $creditsum;
                                                        array_push($assets_calculation_array, $diff_amount);
                                                @endphp

                                            <td>
                                                @if ($diff_amount < 0)
                                                    (Rs. {{ number_format($diff_amount * -1, 2) }}) Cr
                                                @elseif ($diff_amount > 0)
                                                    Rs. {{ number_format($diff_amount, 2) }} Dr
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach

                                <tr style="background-color: #0078d4; color: white;">
                                    <th>Total Assets</th>
                                    <th>
                                        {{-- Rs. {{ array_sum($assets_calculation_array) }} --}}
                                        @php
                                        $asset_cal = array_sum($assets_calculation_array);
                                        if($asset_cal < 0){
                                            $asset_cal_bef = $asset_cal * -1;
                                        }
                                         @endphp
                                         Rs. {{ $asset_cal < 0 ? number_format($asset_cal_bef, 2). ' Cr' : number_format($asset_cal, 2).' Dr' }}
                                    </th>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </section>
    <!-- /.content -->
    </body>
</html>

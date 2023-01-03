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
            padding: 3px 5px;
            font-size:13px;
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
                        <b style="font-size: 22px;display: block;font-weight: 600;margin-bottom: 8px;color: #dd442c;">{{ $currentcomp->company->name }}</b>
                        <span style="display: block;">{{ $currentcomp->company->local_address }}, {{ $currentcomp->company->provinces->eng_name }}</span>
                        <span style="display: block;">{{$currentcomp->company->email}}</span>
                        <span style="display: block;">{{$currentcomp->company->phone}}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        <span style="display: block;margin-top:15px;font-size:20px;font-weight:bold;margin-bottom:10px;">Trial Balance</span>
                        <span style="display: block;margin-bottom:15px;">For the fiscal year {{ $current_fiscal_year->fiscal_year }} ({{ $starting_date }} to {{ $ending_date }})</span>
                    </div>
                </div>
                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                        <table style="width: 100%">
                            <thead style="background-color: #dd442c; color: #ffffff;font-size:13px;">
                                <tr>
                                    <th rowspan="2" style="width: 170px;">Accounts</th>
                                    <th colspan="2" class="text-center">Opening Balance</th>
                                    <th colspan="2" class="text-center">Transactions</th>
                                    <th colspan="2" class="text-center">Closing Balance</th>
                                </tr>
                                <tr>
                                    <th>Dr Amount</th>
                                    <th>Cr Amount</th>
                                    <th>Dr Amount</th>
                                    <th>Cr Amount</th>
                                    <th>Dr Amount</th>
                                    <th>Cr Amount</th>
                                </tr>
                            </thead>

                            @php
                                $debittotal = [];
                                $credittotal = [];
                                $openingdebittotal = [];
                                $openingcredittotal = [];
                                $closingdebittotal = [];
                                $closingcredittotal = [];
                            @endphp
                            @foreach ($mainaccounts as $account)
                                @php
                                    $subaccounts = $account->sub_accounts;
                                    $mainchildaccounts = [];
                                    foreach ($subaccounts as $subaccount) {
                                        $everymainchildaccounts = $subaccount->child_accounts;
                                        array_push($mainchildaccounts, $everymainchildaccounts);
                                    }
                                    $collapsedmainsubaccounts = Arr::collapse($mainchildaccounts);
                                    $journalextras = [];
                                    foreach ($collapsedmainsubaccounts as $collapsedmain) {
                                        $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year, $start_date, $end_date){
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                    $q->where('entry_date_english', '>=', $start_date);
                                                    $q->where('entry_date_english', '<=', $end_date);
                                                })
                                            ->where('child_account_id', $collapsedmain->id)
                                            ->get();
                                        array_push($journalextras, $everyjournalextras);
                                    }
                                    $collapsedjextras = Arr::collapse($journalextras);
                                    $mainDebitAmounts = [];
                                    $mainCreditAmounts = [];
                                    foreach ($collapsedjextras as $collapsedjextras) {
                                        $mainDebit = $collapsedjextras->debitAmount;
                                        $mainCredit = $collapsedjextras->creditAmount;
                                        array_push($mainDebitAmounts, $mainDebit);
                                        array_push($mainCreditAmounts, $mainCredit);
                                    }

                                    $mainDebitsum = array_sum($mainDebitAmounts);
                                    $mainCreditsum = array_sum($mainCreditAmounts);

                                    $opening_debit_balances = [];
                                    $opening_credit_balances = [];
                                    $closing_debit_balances = [];
                                    $closing_credit_balances = [];
                                    foreach($account->sub_accounts as $subaccount){
                                        foreach($subaccount->child_account as $childacc){
                                            $child = $childacc->custom_year($current_fiscal_year->id, $childacc->id);
                                            if(!$child == null){
                                                $openingbalance = $child->opening_balance;
                                                $closingbalance = $child->closing_balance;
                                                if($openingbalance < 0){
                                                    array_push($opening_credit_balances, $child->opening_balance);
                                                }else{
                                                    array_push($opening_debit_balances, $child->opening_balance);
                                                }

                                                if($closingbalance < 0){
                                                    array_push($closing_credit_balances, $child->closing_balance);
                                                }else{
                                                    array_push($closing_debit_balances, $child->closing_balance);
                                                }
                                            }
                                        }
                                    }
                                    $opening_debit_balances_sum = array_sum($opening_debit_balances);

                                    $opening_credit_balances_sum = array_sum($opening_credit_balances);
                                    $closing_debit_balances_sum = array_sum($closing_debit_balances);
                                    $closing_credit_balances_sum = array_sum($closing_credit_balances);

                                    array_push($debittotal, $mainDebitsum);
                                    array_push($credittotal, $mainCreditsum);
                                    array_push($openingdebittotal, $opening_debit_balances_sum);
                                    array_push($openingcredittotal, $opening_credit_balances_sum);
                                    array_push($closingdebittotal, $closing_debit_balances_sum);
                                    array_push($closingcredittotal, $closing_credit_balances_sum);
                                @endphp
                                <tbody>
                                    <tr style="background-color: #ebf3fb; color: black;font-size:13px;">
                                        <td><b>{{ $account->title }}</b></td>
                                        <td><b>{{number_format($opening_debit_balances_sum, 2)}}</b></td>
                                        <td><b>{{number_format($opening_credit_balances_sum * -1, 2)}}</b></td>
                                        <td><b>{{ $mainDebitsum == 0 ? '-' : number_format($mainDebitsum, 2) }}</b></td>
                                        <td><b>{{ $mainCreditsum == 0 ? '-' : number_format($mainCreditsum, 2) }}</b></td>
                                        <td><b>{{number_format($closing_debit_balances_sum, 2)}}</b></td>
                                        <td><b>{{number_format($closing_credit_balances_sum * -1, 2)}}</b></td>
                                    </tr>
                                    @foreach ($account->main_sub_accounts as $subAccount)
                                        @php
                                            $journalextras = [];
                                            $subChild = $subAccount->child_accounts;
                                            $subaccountsinside = $subAccount->sub_accounts_inside($subAccount->id);
                                            foreach ($subChild as $subchildaccount) {
                                                $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year, $start_date, $end_date){
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                    $q->where('entry_date_english', '>=', $start_date);
                                                    $q->where('entry_date_english', '<=', $end_date);
                                                })
                                                    ->where('child_account_id', $subchildaccount->id)
                                                    ->get();
                                                array_push($journalextras, $everyjournalextras);
                                            }

                                            $collapsedjextras = Arr::collapse($journalextras);
                                            $subDebitAmounts = [];
                                            $subCreditAmounts = [];
                                            foreach ($collapsedjextras as $collapsedjextras) {
                                                $subDebit = $collapsedjextras->debitAmount;
                                                $subCredit = $collapsedjextras->creditAmount;
                                                array_push($subDebitAmounts, $subDebit);
                                                array_push($subCreditAmounts, $subCredit);
                                            }
                                            $subDebitsum = array_sum($subDebitAmounts);
                                            $subCreditsum = array_sum($subCreditAmounts);

                                            // Opening Balance Sum of Sub Accounts
                                            $sub_opening_debit_balances = [];
                                            $sub_opening_credit_balances = [];
                                            $sub_closing_debit_balances = [];
                                            $sub_closing_credit_balances = [];
                                            foreach($subAccount->child_account as $childacc){
                                                $child = $childacc->custom_year($current_fiscal_year->id, $childacc->id);
                                                if(!$child == null){
                                                    $openingbalance = $child->opening_balance;
                                                    $closingbalance = $child->closing_balance;
                                                    if($openingbalance < 0){
                                                        array_push($sub_opening_credit_balances, $openingbalance);
                                                    }else{
                                                        array_push($sub_opening_debit_balances, $openingbalance);
                                                    }

                                                    if($closingbalance < 0){
                                                        array_push($sub_closing_credit_balances, $closingbalance);
                                                    }else{
                                                        array_push($sub_closing_debit_balances, $closingbalance);
                                                    }
                                                }
                                            }

                                            $sub_opening_debit_balance_sum = array_sum($sub_opening_debit_balances);
                                            $sub_opening_credit_balance_sum = array_sum($sub_opening_credit_balances);
                                            $sub_closing_debit_balance_sum = array_sum($sub_closing_debit_balances);
                                            $sub_closing_credit_balance_sum = array_sum($sub_closing_credit_balances);


                                            // Sub Inside Account
                                            $subinsidedebittotal = 0;
                                            $subinsidecredittotal = 0;
                                            $subinsideopeningdebittotal = 0;
                                            $subinsideopeningcredittotal = 0;
                                            $subinsideclosingdebittotal = 0;
                                            $subinsideclosingcredittotal = 0;
                                            foreach ($subaccountsinside as $subaccountinside){
                                                $journalextras = [];
                                                $subinsideChild = $subaccountinside->child_accounts;
                                                foreach ($subinsideChild as $subinsidechildaccount) {
                                                    $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year, $start_date, $end_date) {
                                                        $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                            ->where('is_cancelled', 0)
                                                            ->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                    })
                                                        ->where('child_account_id', $subinsidechildaccount->id)

                                                        ->get();
                                                    array_push($journalextras, $everyjournalextras);
                                                }

                                                $collapsedjextras = Arr::collapse($journalextras);
                                                $subinsideDebitAmounts = [];
                                                $subinsideCreditAmounts = [];
                                                foreach ($collapsedjextras as $collapsedjextras) {
                                                    $subinsideDebit = $collapsedjextras->debitAmount;
                                                    $subinsideCredit = $collapsedjextras->creditAmount;
                                                    array_push($subinsideDebitAmounts, $subinsideDebit);
                                                    array_push($subinsideCreditAmounts, $subinsideCredit);
                                                }

                                                $sub_inside_opening_debit_balances = [];
                                                $sub_inside_opening_credit_balances = [];
                                                $sub_inside_closing_debit_balances = [];
                                                $sub_inside_closing_credit_balances = [];

                                                foreach($subaccountinside->child_account as $childacc){
                                                    $child = $childacc->custom_year($current_fiscal_year->id, $childacc->id);
                                                    if(!$child == null){
                                                        $openingbalance = $child->opening_balance;
                                                        $closingbalance = $child->closing_balance;
                                                        if($openingbalance < 0){
                                                            array_push($sub_inside_opening_credit_balances, $openingbalance);
                                                        }else{
                                                            array_push($sub_inside_opening_debit_balances, $openingbalance);
                                                        }

                                                        if($closingbalance < 0){
                                                            array_push($sub_inside_closing_credit_balances, $closingbalance);
                                                        }else{
                                                            array_push($sub_inside_closing_debit_balances, $closingbalance);
                                                        }

                                                    }
                                                }

                                                $sub_inside_opening_debit_balance_sum = array_sum($sub_inside_opening_debit_balances);
                                                $sub_inside_opening_credit_balance_sum = array_sum($sub_inside_opening_credit_balances);
                                                $sub_inside_closing_debit_balance_sum = array_sum($sub_inside_closing_debit_balances);
                                                $sub_inside_closing_credit_balance_sum = array_sum($sub_inside_closing_credit_balances);

                                                $subinsideDebitsum = array_sum($subinsideDebitAmounts);
                                                $subinsideCreditsum = array_sum($subinsideCreditAmounts);

                                                $subinsidedebittotal += $subinsideDebitsum;
                                                $subinsidecredittotal += $subinsideCreditsum;
                                                $subinsideopeningdebittotal += $sub_inside_opening_debit_balance_sum;
                                                $subinsideopeningcredittotal += $sub_inside_opening_credit_balance_sum;
                                                $subinsideclosingdebittotal += $sub_inside_closing_debit_balance_sum;
                                                $subinsideclosingcredittotal += $sub_inside_closing_credit_balance_sum;


                                            }
                                            // Sub Account Sum
                                            $sub_opening_debit_balance_sum += $subinsideopeningdebittotal ?? 0;
                                            $sub_opening_credit_balance_sum += $subinsideopeningcredittotal ?? 0;
                                            $sub_closing_debit_balance_sum += $subinsideclosingdebittotal ?? 0;
                                            $sub_closing_credit_balance_sum += $subinsideclosingcredittotal ?? 0;

                                            $subDebitsum += $subinsidedebittotal ?? 0;
                                            $subCreditsum += $subinsidecredittotal ?? 0;
                                        @endphp
                                        <tr class="sub display" style="font-size:13px;">
                                            <td style="padding-left: 15%">{{ $subAccount->title }}</td>
                                            <td>{{number_format($sub_opening_debit_balance_sum, 2)}}</td>
                                            <td>{{number_format($sub_opening_credit_balance_sum * -1, 2)}}</td>
                                            <td>{{ $subDebitsum == 0 ? '-' : number_format($subDebitsum, 2) }}</td>
                                            <td>{{ $subCreditsum == 0 ? '-' : number_format($subCreditsum, 2) }}</td>
                                            <td>{{number_format($sub_closing_debit_balance_sum, 2)}}</td>
                                            <td>{{number_format($sub_closing_credit_balance_sum * -1, 2)}}</td>
                                        </tr>
                                        @foreach ($subaccountsinside as $subaccountinside)
                                            @php
                                                $journalextras = [];
                                                $subinsideChild = $subaccountinside->child_accounts;
                                                foreach ($subinsideChild as $subinsidechildaccount) {
                                                    $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year, $start_date, $end_date) {
                                                        $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                            ->where('is_cancelled', 0)
                                                            ->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                    })
                                                        ->where('child_account_id', $subinsidechildaccount->id)

                                                        ->get();
                                                    array_push($journalextras, $everyjournalextras);
                                                }

                                                $collapsedjextras = Arr::collapse($journalextras);
                                                $subinsideDebitAmounts = [];
                                                $subinsideCreditAmounts = [];
                                                foreach ($collapsedjextras as $collapsedjextras) {
                                                    $subinsideDebit = $collapsedjextras->debitAmount;
                                                    $subinsideCredit = $collapsedjextras->creditAmount;
                                                    array_push($subinsideDebitAmounts, $subinsideDebit);
                                                    array_push($subinsideCreditAmounts, $subinsideCredit);
                                                }

                                                $sub_inside_opening_debit_balances = [];
                                                $sub_inside_opening_credit_balances = [];
                                                $sub_inside_closing_debit_balances = [];
                                                $sub_inside_closing_credit_balances = [];

                                                foreach($subaccountinside->child_account as $childacc){
                                                    $child = $childacc->custom_year($current_fiscal_year->id, $childacc->id);
                                                    if(!$child == null){
                                                        $openingbalance = $child->opening_balance;
                                                        $closingbalance = $child->closing_balance;
                                                        if($openingbalance < 0){
                                                            array_push($sub_inside_opening_credit_balances, $openingbalance);
                                                        }else{
                                                            array_push($sub_inside_opening_debit_balances, $openingbalance);
                                                        }

                                                        if($closingbalance < 0){
                                                            array_push($sub_inside_closing_credit_balances, $closingbalance);
                                                        }else{
                                                            array_push($sub_inside_closing_debit_balances, $closingbalance);
                                                        }

                                                    }
                                                }

                                                $sub_inside_opening_debit_balance_sum = array_sum($sub_inside_opening_debit_balances);
                                                $sub_inside_opening_credit_balance_sum = array_sum($sub_inside_opening_credit_balances);
                                                $sub_inside_closing_debit_balance_sum = array_sum($sub_inside_closing_debit_balances);
                                                $sub_inside_closing_credit_balance_sum = array_sum($sub_inside_closing_credit_balances);

                                                $subinsideDebitsum = array_sum($subinsideDebitAmounts);
                                                $subinsideCreditsum = array_sum($subinsideCreditAmounts);
                                            @endphp
                                            <tr class="subinside display" style="font-size:13px;">
                                                <td style="padding-left: 30%">{{ $subaccountinside->title }}</td>
                                                <td>{{number_format($sub_inside_opening_debit_balance_sum, 2)}}</td>
                                                <td>{{number_format($sub_inside_opening_credit_balance_sum * -1, 2)}}</td>
                                                <td>{{ $subinsideDebitsum == 0 ? '-' : number_format($subinsideDebitsum, 2) }}</td>
                                                <td>{{ $subinsideCreditsum == 0 ? '-' : number_format($subinsideCreditsum, 2) }}</td>
                                                <td>{{number_format($sub_inside_closing_debit_balance_sum, 2)}}</td>
                                                <td>{{number_format($sub_inside_closing_credit_balance_sum * -1, 2)}}</td>
                                            </tr>
                                            @foreach ($subaccountinside->child_accounts as $insidechildAccount)
                                            @php
                                                $debitAmount = [];
                                                $creditAmount = [];
                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year, $start_date, $end_date) {
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                        ->where('is_cancelled', 0)
                                                        ->where('status', 1);
                                                    $q->where('entry_date_english', '>=', $start_date);
                                                    $q->where('entry_date_english', '<=', $end_date);
                                                })
                                                    ->where('child_account_id', $insidechildAccount->id)
                                                    ->get();
                                                foreach ($journal_extras as $journalExtra) {
                                                    array_push($debitAmount, $journalExtra->debitAmount);
                                                    array_push($creditAmount, $journalExtra->creditAmount);
                                                }

                                                $child_opening_balance = $insidechildAccount->custom_year($current_fiscal_year->id, $childacc->id)->opening_balance ?? 0;
                                                $child_closing_balance = $insidechildAccount->custom_year($current_fiscal_year->id, $childacc->id)->closing_balance ?? 0;


                                                $debitSum = array_sum($debitAmount);
                                                $creditSum = array_sum($creditAmount);


                                            @endphp
                                                <tr class="insidesingle display" style="font-size:13px;">
                                                    <td style="padding-left: 45%;"><em>{{ $insidechildAccount->title }}</em></td>
                                                    <td><em>{{$child_opening_balance >= 0 ? number_format($child_opening_balance, 2) : '-'}}</em></td>
                                                    <td><em>{{$child_opening_balance < 0 ? number_format($child_opening_balance * -1, 2) : '-'}}</em></td>
                                                    <td><em>{{ $debitSum == 0 ? '-' : number_format($debitSum, 2) }}</em></td>
                                                    <td><em>{{ $creditSum == 0 ? '-' : number_format($creditSum, 2) }}</em></td>
                                                    <td><em>{{$child_closing_balance >= 0 ? number_format($child_closing_balance, 2) : '-'}}</em></td>
                                                    <td><em>{{$child_closing_balance < 0 ? number_format($child_closing_balance * -1, 2) : '-'}}</em></td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                        @foreach ($subAccount->child_accounts as $childAccount)
                                            @php
                                                $debitAmount = [];
                                                $creditAmount = [];
                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year, $start_date, $end_date){
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                    $q->where('entry_date_english', '>=', $start_date);
                                                    $q->where('entry_date_english', '<=', $end_date);
                                                })
                                                    ->where('child_account_id', $childAccount->id)
                                                    ->get();
                                                    // dd($journal_extras);
                                                foreach ($journal_extras as $journalExtra) {
                                                    array_push($debitAmount, $journalExtra->debitAmount);
                                                    array_push($creditAmount, $journalExtra->creditAmount);
                                                }
                                                // dd($debitAmount);
                                                $debitSum = array_sum($debitAmount);
                                                $creditSum = array_sum($creditAmount);

                                                $child = $childAccount->custom_year($current_fiscal_year->id, $childAccount->id);
                                                $child_opening_balance = $child->opening_balance ?? 0;
                                                $child_closing_balance = $child->closing_balance ?? 0;
                                            @endphp
                                            <tr class="single display" style="font-size:13px;">
                                                <td style="padding-left: 30%;"><em>{{ $childAccount->title }}</em></td>
                                                <td><em>{{$child_opening_balance >= 0 ? number_format($child_opening_balance, 2) : '-'}}</em></td>
                                                <td><em>{{$child_opening_balance < 0 ? number_format($child_opening_balance * -1, 2) : '-'}}</em></td>
                                                <td><em>{{ $debitSum == 0 ? '-' : number_format($debitSum, 2) }}</em></td>
                                                <td><em>{{ $creditSum == 0 ? '-' : number_format($creditSum, 2) }}</em></td>
                                                <td><em>{{$child_closing_balance >= 0 ? number_format($child_closing_balance, 2) : '-'}}</em></td>
                                                <td><em>{{$child_closing_balance < 0 ? number_format($child_closing_balance * -1, 2) : '-'}}</em></td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>

                            @endforeach
                            @php
                                $mainDebitsum = array_sum($debittotal);
                                $mainCreditsum = array_sum($credittotal);
                                $openingdebitsum = array_sum($openingdebittotal);
                                $openingcreditsum = array_sum($openingcredittotal);
                                $closingdebitsum = array_sum($closingdebittotal);
                                $closingcreditsum = array_sum($closingcredittotal);
                            @endphp
                            <tbody>
                                <tr style="background:#0078d4;color:#fff;">
                                    <th>Total</th>
                                    <th>{{ number_format($openingdebitsum, 2) }}</th>
                                    <th>{{ number_format($openingcreditsum * -1, 2) }}</th>
                                    <th>{{ number_format($mainDebitsum, 2) }}</th>
                                    <th>{{ number_format($mainCreditsum, 2) }}</th>
                                    <th>{{ number_format($closingdebitsum, 2) }}</th>
                                    <th>{{ number_format($closingcreditsum * -1, 2) }}</th>
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

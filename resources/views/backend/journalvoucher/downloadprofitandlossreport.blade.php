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
            font-size:14px;
            padding: 3px 7px;
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
                        <span style="display: block;">{{ $currentcomp->company->local_address }}, {{ $currentcomp->company->provinces->eng_name }} </span>
                        <span style="display: block;">{{$currentcomp->company->email}}</span>
                        <span style="display: block;">{{$currentcomp->company->phone}}</span>
                    </div>
                </div>
                <div style="text-align: center">
                    <span style="display: block;margin-top:15px;font-size:20px;font-weight:bold;margin-bottom:10px;">Profit and Loss Account</span>
                    <span style="display: block;">For the fiscal year of {{ $selected_fiscal_year->fiscal_year }}</span>
                    <span style="display: block;margin-bottom:15px;">({{ $starting_date }} to {{ $ending_date }})</span>
                </div>
                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                        <table style="width: 100%">
                            <thead style="background-color: #dd442c; color: #ffffff;">
                                <tr>
                                    <th>Accounts</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>
                                        <b style="font-size:15px;">Income</b>
                                    </td>
                                    <td></td>
                                </tr>

                                @php
                                    $income_sum = 0;
                                @endphp
                                @for ($i = 0; $i < 3; $i++)
                                    <tr style="background-color: #ebf3fb;">

                                        <td style="padding-left: 5%;">
                                            <b>
                                                {{ $sub_accounts[$i]->title }}
                                            </b>
                                        </td>

                                        <td>
                                            @php
                                                $child_sub_accounts = \App\Models\SubAccount::where('sub_account_id', $sub_accounts[$i]->id)->get();
                                                $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $sub_accounts[$i]->id)->get();
                                                $sub_account_amount = 0;
                                                $child_opening_balances = [];
                                                $debitadd = [];
                                                $creditadd = [];

                                                // SubChild Accounts

                                                $subchild_opening_total = 0;
                                                $subchild_debittotal = 0;
                                                $subchild_credittotal = 0;
                                                foreach ($child_sub_accounts as $subaccount){
                                                    $subchild_child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subaccount->id)->get();
                                                    $subchild_account_amount = 0;
                                                    $subchild_child_opening_balances = [];
                                                    $subchild_debitadd = [];
                                                    $subchild_creditadd = [];

                                                    foreach ($subchild_child_accounts as $child_account) {
                                                        $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                            $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                                ->where('is_cancelled', 0)
                                                                ->where('status', 1);
                                                                $q->where('entry_date_english', '>=', $start_date);
                                                                $q->where('entry_date_english', '<=', $end_date);
                                                            })
                                                            ->where('child_account_id', $child_account->id)
                                                            ->get();
                                                        $subchild_debitamount = [];
                                                        $subchild_creditamount = [];
                                                        foreach ($journal_extras as $jextra) {
                                                            array_push($subchild_debitamount, $jextra->debitAmount);
                                                            array_push($subchild_creditamount, $jextra->creditAmount);
                                                        }
                                                        $debitsum = array_sum($subchild_debitamount);
                                                        $creditsum = array_sum($subchild_creditamount);
                                                        array_push($subchild_debitadd, $debitsum);
                                                        array_push($subchild_creditadd, $creditsum);

                                                        array_push($subchild_child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);
                                                    }
                                                    $subdebitsum = array_sum($subchild_debitadd);
                                                    $subcreditsum = array_sum($subchild_creditadd);

                                                    $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                                    $subchild_opening_total += $subchild_child_opening_balance_sum ?? 0;
                                                    $subchild_debittotal += $subdebitsum ?? 0;
                                                    $subchild_credittotal += $subcreditsum ?? 0;

                                                    $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                                }
                                                foreach ($child_accounts as $child_account)
                                                {
                                                    $journal_extras =   \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($selected_fiscal_year, $start_date, $end_date){
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
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
                                                    array_push($debitadd, $debitsum);
                                                    array_push($creditadd, $creditsum);
                                                    array_push($child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);
                                                }
                                                $subdebitsum = array_sum($debitadd) + $subchild_debittotal;
                                                $subcreditsum = array_sum($creditadd) + $subchild_credittotal;

                                                $child_opening_balance_sum = array_sum($child_opening_balances) + $subchild_opening_total;

                                                $diff = $child_opening_balance_sum + $subdebitsum - $subcreditsum;
                                            @endphp

                                            @if ($diff < 0)
                                                <b>
                                                    Rs. {{ number_format($diff * -1,2) }} Cr
                                                </b>
                                            @else
                                                <b>
                                                    Rs. {{ number_format($diff,2) }} Dr
                                                </b>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $sub_accounts[$i]->id)->get();
                                    @endphp

                                    @foreach ($child_sub_accounts as $subaccount)
                                        @php
                                            $subchild_child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subaccount->id)->get();
                                            $subchild_account_amount = 0;
                                            $subchild_child_opening_balances = [];
                                            $subchild_debitadd = [];
                                            $subchild_creditadd = [];

                                            foreach ($subchild_child_accounts as $child_account) {
                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                    $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                        ->where('is_cancelled', 0)
                                                        ->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                })
                                                    ->where('child_account_id', $child_account->id)
                                                    ->get();
                                                $subchild_debitamount = [];
                                                $subchild_creditamount = [];
                                                foreach ($journal_extras as $jextra) {
                                                    array_push($subchild_debitamount, $jextra->debitAmount);
                                                    array_push($subchild_creditamount, $jextra->creditAmount);
                                                }
                                                $debitsum = array_sum($subchild_debitamount);
                                                $creditsum = array_sum($subchild_creditamount);
                                                array_push($subchild_debitadd, $debitsum);
                                                array_push($subchild_creditadd, $creditsum);

                                                array_push($subchild_child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);
                                            }
                                            $subdebitsum = array_sum($subchild_debitadd);
                                            $subcreditsum = array_sum($subchild_creditadd);

                                            $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                            $subchild_opening_total ?? 0;
                                            $subchild_debittotal ?? 0;
                                            $subchild_credittotal ?? 0;

                                            $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                        @endphp
                                        <tr>
                                            <td style = "padding-left: 9%;">
                                                <em>{{ $subaccount->title }}</em>
                                            </td>
                                            <td>
                                                @if ($subchild_diff < 0)
                                                    <b>
                                                        Rs. {{ number_format($subchild_diff * -1,2) }} Cr
                                                    </b>
                                                @else
                                                    <b>
                                                        Rs. {{ number_format($subchild_diff,2) }} Dr
                                                    </b>
                                                @endif
                                            </td>
                                        </tr>
                                        @foreach ($subchild_child_accounts as $child_account)
                                        <tr>
                                            <td style="padding-left: 18%;">
                                                <em>{{ $child_account->title }}</em>
                                            </td>
                                            <td>
                                                @php
                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                            ->where('is_cancelled', 0)
                                                            ->where('status', 1);
                                                            $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                    })
                                                        ->where('child_account_id', $child_account->id)
                                                        ->get();

                                                    $debitamts = [];
                                                    $creditamts = [];


                                                    foreach($journal_extras as $jextra){
                                                        array_push($debitamts, $jextra->debitAmount);
                                                        array_push($creditamts, $jextra->creditAmount);
                                                    }
                                                    $child_opening_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0;
                                                    // dd($child_opening_balance);
                                                    $debitsum = array_sum($debitamts);
                                                    $creditsum = array_sum($creditamts);

                                                    $amount = $child_opening_balance + $debitsum - $creditsum;

                                                    if($amount < 0){
                                                        echo 'Rs'. number_format($amount * -1, 2) . ' Cr';
                                                    }else{
                                                        echo 'Rs'. number_format($amount, 2) . ' Dr';
                                                    }
                                                @endphp
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach

                                    @foreach ($child_accounts as $child_account)
                                        <tr>
                                            <td style="padding-left: 9%;"><em>{{ $child_account->title }}</em></td>
                                            <td>
                                                @php
                                                    $journal_extras =   \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($selected_fiscal_year, $start_date, $end_date){
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                    })
                                                                        ->where('child_account_id', $child_account->id)->get();
                                                   $child_opening_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0;
                                                    // dd($child_opening_balance);
                                                    $debitamts = [];
                                                    $creditamts = [];
                                                    foreach($journal_extras as $jextra){
                                                        array_push($debitamts, $jextra->debitAmount);
                                                        array_push($creditamts, $jextra->creditAmount);
                                                    }
                                                    $debitsum = array_sum($debitamts);
                                                    $creditsum = array_sum($creditamts);

                                                    $amount = $child_opening_balance + $debitsum - $creditsum;

                                                    if($amount < 0){
                                                        echo 'Rs '. number_format($amount * -1, 2) . ' Cr';
                                                    }else{
                                                        echo 'Rs '. number_format($amount, 2) . ' Dr';
                                                    }
                                                @endphp
                                            </td>
                                        </tr>
                                    @endforeach

                                    @php
                                        $income_sum = $income_sum + $diff;
                                    @endphp
                                @endfor

                                <tr style="background-color: #0078d4; color: white;">
                                    <td class="text-center">
                                        <b style="font-size: 15px;">Total Income</b>
                                    </td>
                                    <td>
                                        <b>
                                            @if ($income_sum < 0)
                                                <b>
                                                    Rs. {{ number_format($income_sum * -1,2) }} Cr
                                                </b>
                                            @else
                                                <b>
                                                    Rs. {{ number_format($income_sum,2) }} Dr
                                                </b>
                                            @endif
                                        </b>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <b style="font-size: 15px;">Less: Cost Of Goods Sold</b>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr style="background-color: #ebf3fb;">
                                    <td style="padding-left: 5%;"><b>{{ $sub_accounts[5]->title }}</b></td>
                                    <td>
                                        @php
                                            $child_sub_accounts = \App\Models\SubAccount::where('sub_account_id', $sub_accounts[5]->id)->get();
                                            $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $sub_accounts[5]->id)->get();
                                            $sub_account_amount = 0;
                                            $child_opening_balances = [];
                                            $debitadd = [];
                                            $creditadd = [];

                                            // SubChild Accounts

                                            $subchild_opening_total = 0;
                                            $subchild_debittotal = 0;
                                            $subchild_credittotal = 0;
                                            foreach ($child_sub_accounts as $subaccount){
                                                $subchild_child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subaccount->id)->get();
                                                $subchild_account_amount = 0;
                                                $subchild_child_opening_balances = [];
                                                $subchild_debitadd = [];
                                                $subchild_creditadd = [];

                                                foreach ($subchild_child_accounts as $child_account) {
                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                            ->where('is_cancelled', 0)
                                                            ->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                    })
                                                        ->where('child_account_id', $child_account->id)
                                                        ->get();
                                                    $subchild_debitamount = [];
                                                    $subchild_creditamount = [];
                                                    foreach ($journal_extras as $jextra) {
                                                        array_push($subchild_debitamount, $jextra->debitAmount);
                                                        array_push($subchild_creditamount, $jextra->creditAmount);
                                                    }
                                                    $debitsum = array_sum($subchild_debitamount);
                                                    $creditsum = array_sum($subchild_creditamount);
                                                    array_push($subchild_debitadd, $debitsum);
                                                    array_push($subchild_creditadd, $creditsum);

                                                    array_push($subchild_child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);
                                                }
                                                $subdebitsum = array_sum($subchild_debitadd);
                                                $subcreditsum = array_sum($subchild_creditadd);

                                                $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                                $subchild_opening_total += $subchild_child_opening_balance_sum ?? 0;
                                                $subchild_debittotal += $subdebitsum ?? 0;
                                                $subchild_credittotal += $subcreditsum ?? 0;

                                                $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                            }
                                            foreach ($child_accounts as $child_account)
                                            {
                                                $journal_extras =\App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($selected_fiscal_year, $start_date, $end_date){
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
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
                                                array_push($debitadd, $debitsum);
                                                array_push($creditadd, $creditsum);
                                                array_push($child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);
                                            }
                                            // dd($creditadd);
                                            $subdebitsum = array_sum($debitadd) + $subchild_debittotal;
                                            $subcreditsum = array_sum($creditadd) + $subchild_credittotal;

                                            $child_opening_balance_sum = array_sum($child_opening_balances) + $subchild_opening_total;

                                            $diff = $child_opening_balance_sum + $subdebitsum - $subcreditsum;
                                        @endphp
                                        @if ($diff < 0)
                                            <b>Rs. {{ number_format($diff * -1,2) }} Cr</b>
                                        @else
                                            <b>Rs. {{ number_format($diff,2) }} Dr</b>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $sub_accounts[5]->id)->get();
                                @endphp

                                @foreach ($child_sub_accounts as $subaccount)
                                    @php
                                        $subchild_child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subaccount->id)->get();
                                        $subchild_account_amount = 0;
                                        $subchild_child_opening_balances = [];
                                        $subchild_debitadd = [];
                                        $subchild_creditadd = [];

                                        foreach ($subchild_child_accounts as $child_account) {
                                            $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                    ->where('is_cancelled', 0)
                                                    ->where('status', 1);
                                                $q->where('entry_date_english', '>=', $start_date);
                                                $q->where('entry_date_english', '<=', $end_date);
                                            })
                                                ->where('child_account_id', $child_account->id)
                                                ->get();
                                            $subchild_debitamount = [];
                                            $subchild_creditamount = [];
                                            foreach ($journal_extras as $jextra) {
                                                array_push($subchild_debitamount, $jextra->debitAmount);
                                                array_push($subchild_creditamount, $jextra->creditAmount);
                                            }
                                            $debitsum = array_sum($subchild_debitamount);
                                            $creditsum = array_sum($subchild_creditamount);
                                            array_push($subchild_debitadd, $debitsum);
                                            array_push($subchild_creditadd, $creditsum);

                                            array_push($subchild_child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);
                                        }
                                        $subdebitsum = array_sum($subchild_debitadd);
                                        $subcreditsum = array_sum($subchild_creditadd);

                                        $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                        $subchild_opening_total ?? 0;
                                        $subchild_debittotal ?? 0;
                                        $subchild_credittotal ?? 0;

                                        $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                    @endphp
                                    <tr>
                                        <td style = "padding-left: 9%;">
                                            <em>{{ $subaccount->title }}</em>
                                        </td>
                                        <td>
                                            @if ($subchild_diff < 0)
                                                <b>
                                                    Rs. {{ number_format($subchild_diff * -1,2) }} Cr
                                                </b>
                                            @else
                                                <b>
                                                    Rs. {{ number_format($subchild_diff,2) }} Dr
                                                </b>
                                            @endif
                                        </td>
                                    </tr>
                                    @foreach ($subchild_child_accounts as $child_account)
                                    <tr>
                                        <td style="padding-left: 18%;">
                                            <em>{{ $child_account->title }}</em>
                                        </td>
                                        <td>
                                            @php
                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                    $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                        ->where('is_cancelled', 0)
                                                        ->where('status', 1);
                                                    $q->where('entry_date_english', '>=', $start_date);
                                                    $q->where('entry_date_english', '<=', $end_date);
                                                })
                                                    ->where('child_account_id', $child_account->id)
                                                    ->get();

                                                $debitamts = [];
                                                $creditamts = [];


                                                foreach($journal_extras as $jextra){
                                                    array_push($debitamts, $jextra->debitAmount);
                                                    array_push($creditamts, $jextra->creditAmount);
                                                }
                                                $child_opening_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0;
                                                // dd($child_opening_balance);
                                                $debitsum = array_sum($debitamts);
                                                $creditsum = array_sum($creditamts);

                                                $amount = $child_opening_balance + $debitsum - $creditsum;

                                                if($amount < 0){
                                                    echo 'Rs'. number_format($amount * -1, 2) . ' Cr';
                                                }else{
                                                    echo 'Rs'. number_format($amount, 2) . ' Dr';
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach

                                @foreach ($child_accounts as $child_account)
                                    <tr>
                                        <td style="padding-left: 9%;"><em>{{ $child_account->title }}</em></td>
                                        <td>
                                            @php
                                                $journal_extras =\App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($selected_fiscal_year, $start_date, $end_date){
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                })
                                                                        ->where('child_account_id', $child_account->id)->get();

                                                $child_opening_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0;
                                                // dd($child_opening_balance);
                                                $debitamts = [];
                                                $creditamts = [];
                                                foreach($journal_extras as $jextra){
                                                    array_push($debitamts, $jextra->debitAmount);
                                                    array_push($creditamts, $jextra->creditAmount);
                                                }

                                                $debitsum = array_sum($debitamts);
                                                $creditsum = array_sum($creditamts);

                                                $amount = $child_opening_balance + $debitsum - $creditsum;

                                                if($amount < 0){
                                                    echo 'Rs. '. number_format($amount * -1, 2) . ' Cr';
                                                }else{
                                                    echo 'Rs. '. number_format($amount, 2) . ' Dr';
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                @endforeach

                                <tr style="background-color: #0078d4; color: white;">
                                    <td class="text-center">
                                        <b style="font-size: 15px;">Total Cost of Sales</b>
                                    </td>
                                    <td>
                                        <b>
                                            @if ($diff < 0)
                                                <b>Rs. {{ number_format($diff * -1,2) }} Cr</b>
                                            @else
                                                <b>Rs. {{ number_format($diff,2) }} Dr</b>
                                            @endif
                                        </b>
                                    </td>
                                </tr>

                                <tr style="background-color: #0078d4; color: white;">
                                    <td class="text-center">
                                        <b style="font-size: 15px;">Gross Profit / Loss</b>
                                    </td>
                                    <td>
                                        <b>
                                            @php
                                                $gross_profit = $income_sum + $diff;
                                            @endphp
                                            @if ($gross_profit < 0)
                                                <b>Rs. {{ number_format($gross_profit *-1,2)}} Cr</b>
                                            @else
                                                <b>Rs. {{ number_format($gross_profit,2) }} Dr</b>
                                            @endif
                                        </b>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <b style="font-size: 15px;">Less: Operating Expenses</b>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr style="background-color: #ebf3fb;">
                                    <td style="padding-left: 5%;"><b>{{ $sub_accounts[3]->title }}</b></td>
                                    <td>
                                        @php
                                            $child_sub_accounts = \App\Models\SubAccount::where('sub_account_id', $sub_accounts[3]->id)->get();
                                            $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $sub_accounts[3]->id)->get();
                                            $sub_account_amount = 0;
                                            $child_opening_balances = [];
                                            $debitadd = [];
                                            $creditadd = [];

                                            // SubChild Accounts

                                            $subchild_opening_total = 0;
                                            $subchild_debittotal = 0;
                                            $subchild_credittotal = 0;
                                            foreach ($child_sub_accounts as $subaccount){
                                                $subchild_child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subaccount->id)->get();
                                                $subchild_account_amount = 0;
                                                $subchild_child_opening_balances = [];
                                                $subchild_debitadd = [];
                                                $subchild_creditadd = [];

                                                foreach ($subchild_child_accounts as $child_account) {
                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                            ->where('is_cancelled', 0)
                                                            ->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                    })
                                                        ->where('child_account_id', $child_account->id)
                                                        ->get();
                                                    $subchild_debitamount = [];
                                                    $subchild_creditamount = [];
                                                    foreach ($journal_extras as $jextra) {
                                                        array_push($subchild_debitamount, $jextra->debitAmount);
                                                        array_push($subchild_creditamount, $jextra->creditAmount);
                                                    }
                                                    $debitsum = array_sum($subchild_debitamount);
                                                    $creditsum = array_sum($subchild_creditamount);
                                                    array_push($subchild_debitadd, $debitsum);
                                                    array_push($subchild_creditadd, $creditsum);

                                                    array_push($subchild_child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);
                                                }
                                                $subdebitsum = array_sum($subchild_debitadd);
                                                $subcreditsum = array_sum($subchild_creditadd);

                                                $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                                $subchild_opening_total += $subchild_child_opening_balance_sum ?? 0;
                                                $subchild_debittotal += $subdebitsum ?? 0;
                                                $subchild_credittotal += $subcreditsum ?? 0;

                                                $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                            }
                                            foreach ($child_accounts as $child_account)
                                            {
                                                $journal_extras =\App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($selected_fiscal_year, $start_date, $end_date){
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
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
                                                array_push($debitadd, $debitsum);
                                                array_push($creditadd, $creditsum);
                                                array_push($child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);

                                            }
                                            // dd($creditadd);
                                            $subdebitsum = array_sum($debitadd) + $subchild_debittotal;
                                            $subcreditsum = array_sum($creditadd) + $subchild_credittotal;

                                            $child_opening_balance_sum = array_sum($child_opening_balances) + $subchild_opening_total;

                                            $diff1 = $child_opening_balance_sum + $subdebitsum - $subcreditsum;
                                        @endphp
                                        @if ($diff1 < 0)
                                            <b>Rs. {{ number_format($diff1 * -1,2) }} Cr</b>
                                        @else
                                            <b>Rs. {{ number_format($diff1,2) }} Dr</b>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $sub_accounts[3]->id)->get();
                                @endphp

                                @foreach ($child_sub_accounts as $subaccount)
                                    @php
                                        $subchild_child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subaccount->id)->get();
                                        $subchild_account_amount = 0;
                                        $subchild_child_opening_balances = [];
                                        $subchild_debitadd = [];
                                        $subchild_creditadd = [];

                                        foreach ($subchild_child_accounts as $child_account) {
                                            $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                    ->where('is_cancelled', 0)
                                                    ->where('status', 1);
                                                    $q->where('entry_date_english', '>=', $start_date);
                                                    $q->where('entry_date_english', '<=', $end_date);
                                            })
                                                ->where('child_account_id', $child_account->id)
                                                ->get();
                                            $subchild_debitamount = [];
                                            $subchild_creditamount = [];
                                            foreach ($journal_extras as $jextra) {
                                                array_push($subchild_debitamount, $jextra->debitAmount);
                                                array_push($subchild_creditamount, $jextra->creditAmount);
                                            }
                                            $debitsum = array_sum($subchild_debitamount);
                                            $creditsum = array_sum($subchild_creditamount);
                                            array_push($subchild_debitadd, $debitsum);
                                            array_push($subchild_creditadd, $creditsum);

                                            array_push($subchild_child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);
                                        }
                                        $subdebitsum = array_sum($subchild_debitadd);
                                        $subcreditsum = array_sum($subchild_creditadd);

                                        $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                        $subchild_opening_total ?? 0;
                                        $subchild_debittotal ?? 0;
                                        $subchild_credittotal ?? 0;

                                        $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                    @endphp
                                    <tr>
                                        <td style = "padding-left: 9%;">
                                            <em>{{ $subaccount->title }}</em>
                                        </td>
                                        <td>
                                            @if ($subchild_diff < 0)
                                                <b>
                                                    Rs. {{ number_format($subchild_diff * -1,2) }} Cr
                                                </b>
                                            @else
                                                <b>
                                                    Rs. {{ number_format($subchild_diff,2) }} Dr
                                                </b>
                                            @endif
                                        </td>
                                    </tr>
                                    @foreach ($subchild_child_accounts as $child_account)
                                    <tr>
                                        <td style="padding-left: 18%;">
                                            <em>{{ $child_account->title }}</em>
                                        </td>
                                        <td>
                                            @php
                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                    $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                        ->where('is_cancelled', 0)
                                                        ->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                })
                                                    ->where('child_account_id', $child_account->id)
                                                    ->get();

                                                $debitamts = [];
                                                $creditamts = [];


                                                foreach($journal_extras as $jextra){
                                                    array_push($debitamts, $jextra->debitAmount);
                                                    array_push($creditamts, $jextra->creditAmount);
                                                }
                                                $child_opening_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0;
                                                // dd($child_opening_balance);
                                                $debitsum = array_sum($debitamts);
                                                $creditsum = array_sum($creditamts);

                                                $amount = $child_opening_balance + $debitsum - $creditsum;

                                                if($amount < 0){
                                                    echo 'Rs'. number_format($amount * -1, 2) . ' Cr';
                                                }else{
                                                    echo 'Rs'. number_format($amount, 2) . ' Dr';
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach

                                @foreach ($child_accounts as $child_account)
                                    <tr>
                                        <td style="padding-left: 9%;"><em>{{ $child_account->title }}</em></td>
                                        <td>
                                            @php
                                                $journal_extras =\App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($selected_fiscal_year, $start_date, $end_date){
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                })
                                                                        ->where('child_account_id', $child_account->id)->get();


                                                $child_opening_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0;
                                                // dd($child_opening_balance);
                                                $debitamts = [];
                                                $creditamts = [];
                                                foreach($journal_extras as $jextra){
                                                    array_push($debitamts, $jextra->debitAmount);
                                                    array_push($creditamts, $jextra->creditAmount);
                                                }

                                                $debitsum = array_sum($debitamts);
                                                $creditsum = array_sum($creditamts);

                                                $amount = $child_opening_balance + $debitsum - $creditsum;

                                                if($amount >= 0){
                                                    echo 'Rs. '. number_format($amount, 2) . ' Dr';
                                                }else{
                                                    echo 'Rs. '. number_format($amount * -1, 2) . ' Cr';
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td>
                                        <b style="font-size: 15px;">Less: Non-Operating Expenses</b>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr style="background-color: #ebf3fb;">
                                    <td style="padding-left: 5%;"><b>{{ $sub_accounts[4]->title }}</b></td>
                                    <td>
                                        @php
                                            $child_sub_accounts = \App\Models\SubAccount::where('sub_account_id', $sub_accounts[4]->id)->get();
                                            $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $sub_accounts[4]->id)->get();
                                            $sub_account_amount = 0;
                                            $child_opening_balances = [];
                                            $debitadd = [];
                                            $creditadd = [];

                                            // SubChild Accounts

                                            $subchild_opening_total = 0;
                                            $subchild_debittotal = 0;
                                            $subchild_credittotal = 0;
                                            foreach ($child_sub_accounts as $subaccount){
                                                $subchild_child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subaccount->id)->get();
                                                $subchild_account_amount = 0;
                                                $subchild_child_opening_balances = [];
                                                $subchild_debitadd = [];
                                                $subchild_creditadd = [];

                                                foreach ($subchild_child_accounts as $child_account) {
                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                            ->where('is_cancelled', 0)
                                                            ->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                    })
                                                        ->where('child_account_id', $child_account->id)
                                                        ->get();
                                                    $subchild_debitamount = [];
                                                    $subchild_creditamount = [];
                                                    foreach ($journal_extras as $jextra) {
                                                        array_push($subchild_debitamount, $jextra->debitAmount);
                                                        array_push($subchild_creditamount, $jextra->creditAmount);
                                                    }
                                                    $debitsum = array_sum($subchild_debitamount);
                                                    $creditsum = array_sum($subchild_creditamount);
                                                    array_push($subchild_debitadd, $debitsum);
                                                    array_push($subchild_creditadd, $creditsum);

                                                    array_push($subchild_child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);
                                                }
                                                $subdebitsum = array_sum($subchild_debitadd);
                                                $subcreditsum = array_sum($subchild_creditadd);

                                                $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                                $subchild_opening_total += $subchild_child_opening_balance_sum ?? 0;
                                                $subchild_debittotal += $subdebitsum ?? 0;
                                                $subchild_credittotal += $subcreditsum ?? 0;

                                                $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                            }
                                            foreach ($child_accounts as $child_account)
                                            {
                                                $journal_extras =\App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($selected_fiscal_year, $start_date, $end_date){
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
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
                                                array_push($debitadd, $debitsum);
                                                array_push($creditadd, $creditsum);
                                                array_push($child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);

                                            }
                                            // dd($creditadd);
                                            $subdebitsum = array_sum($debitadd) + $subchild_debittotal;
                                            $subcreditsum = array_sum($creditadd) + $subchild_credittotal;

                                            $child_opening_balance_sum = array_sum($child_opening_balances) + $subchild_opening_total;

                                            $diff2 = $child_opening_balance_sum + $subdebitsum - $subcreditsum;
                                        @endphp
                                        @if ($diff2 < 0)
                                            <b>Rs. {{ number_format($diff2 * -1,2) }} Cr</b>
                                        @else
                                            <b>Rs. {{ number_format($diff2,2) }} Dr</b>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $sub_accounts[4]->id)->get();
                                @endphp

                                @foreach ($child_sub_accounts as $subaccount)
                                    @php
                                        $subchild_child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subaccount->id)->get();
                                        $subchild_account_amount = 0;
                                        $subchild_child_opening_balances = [];
                                        $subchild_debitadd = [];
                                        $subchild_creditadd = [];

                                        foreach ($subchild_child_accounts as $child_account) {
                                            $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                    ->where('is_cancelled', 0)
                                                    ->where('status', 1);
                                                $q->where('entry_date_english', '>=', $start_date);
                                                $q->where('entry_date_english', '<=', $end_date);
                                            })
                                                ->where('child_account_id', $child_account->id)
                                                ->get();
                                            $subchild_debitamount = [];
                                            $subchild_creditamount = [];
                                            foreach ($journal_extras as $jextra) {
                                                array_push($subchild_debitamount, $jextra->debitAmount);
                                                array_push($subchild_creditamount, $jextra->creditAmount);
                                            }
                                            $debitsum = array_sum($subchild_debitamount);
                                            $creditsum = array_sum($subchild_creditamount);
                                            array_push($subchild_debitadd, $debitsum);
                                            array_push($subchild_creditadd, $creditsum);

                                            array_push($subchild_child_opening_balances , $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0);
                                        }
                                        $subdebitsum = array_sum($subchild_debitadd);
                                        $subcreditsum = array_sum($subchild_creditadd);

                                        $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                        $subchild_opening_total ?? 0;
                                        $subchild_debittotal ?? 0;
                                        $subchild_credittotal ?? 0;

                                        $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                    @endphp
                                    <tr>
                                        <td style = "padding-left: 9%;">
                                            <em>{{ $subaccount->title }}</em>
                                        </td>
                                        <td>
                                            @if ($subchild_diff < 0)
                                                <b>
                                                    Rs. {{ number_format($subchild_diff * -1,2) }} Cr
                                                </b>
                                            @else
                                                <b>
                                                    Rs. {{ number_format($subchild_diff,2) }} Dr
                                                </b>
                                            @endif
                                        </td>
                                    </tr>
                                    @foreach ($subchild_child_accounts as $child_account)
                                    <tr>
                                        <td style="padding-left: 18%;">
                                            <em>{{ $child_account->title }}</em>
                                        </td>
                                        <td>
                                            @php
                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                    $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                        ->where('is_cancelled', 0)
                                                        ->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                })
                                                    ->where('child_account_id', $child_account->id)
                                                    ->get();

                                                $debitamts = [];
                                                $creditamts = [];


                                                foreach($journal_extras as $jextra){
                                                    array_push($debitamts, $jextra->debitAmount);
                                                    array_push($creditamts, $jextra->creditAmount);
                                                }
                                                $child_opening_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0;
                                                // dd($child_opening_balance);
                                                $debitsum = array_sum($debitamts);
                                                $creditsum = array_sum($creditamts);

                                                $amount = $child_opening_balance + $debitsum - $creditsum;

                                                if($amount < 0){
                                                    echo 'Rs'. number_format($amount * -1, 2) . ' Cr';
                                                }else{
                                                    echo 'Rs'. number_format($amount, 2) . ' Dr';
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach

                                @foreach ($child_accounts as $child_account)
                                    <tr>
                                        <td style="padding-left: 9%;"><em>{{ $child_account->title }}</em></td>
                                        <td>
                                            @php
                                                $journal_extras =\App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($selected_fiscal_year, $start_date, $end_date){
                                                        $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                        $q->where('entry_date_english', '>=', $start_date);
                                                        $q->where('entry_date_english', '<=', $end_date);
                                                })
                                                                        ->where('child_account_id', $child_account->id)->get();


                                                $child_opening_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0;
                                                // dd($child_opening_balance);
                                                $debitamts = [];
                                                $creditamts = [];
                                                foreach($journal_extras as $jextra){
                                                    array_push($debitamts, $jextra->debitAmount);
                                                    array_push($creditamts, $jextra->creditAmount);
                                                }
                                                $debitsum = array_sum($debitamts);
                                                $creditsum = array_sum($creditamts);

                                                $amount = $child_opening_balance + $debitsum - $creditsum;

                                                if($amount >= 0){
                                                    echo 'Rs. '. number_format($amount, 2) . ' Dr';
                                                }else{
                                                    echo 'Rs. '. number_format($amount * -1, 2) . ' Cr';
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                @endforeach

                                <tr style="background-color: #0078d4; color: white;">
                                    <td class="text-center">
                                        <b style="font-size: 15px;">Total Expenses</b>
                                    </td>
                                    <td>
                                        <b>
                                            @php
                                                $total_expenses = $diff1 + $diff2;
                                            @endphp
                                            @if($total_expenses < 0)
                                            Rs. {{ number_format($total_expenses * -1,2) }} Cr
                                            @else
                                            Rs. {{ number_format($total_expenses,2) }} Dr
                                            @endif
                                        </b>
                                    </td>
                                </tr>

                                <tr style="background-color: #0078d4; color: white;">
                                    <td class="text-center">
                                        <b style="font-size: 15px;">Net Profit / Loss</b>
                                    </td>
                                    <td>
                                        <b>
                                            @php
                                                $net_profit = $gross_profit + $total_expenses;
                                            @endphp
                                            @if ($net_profit < 0)
                                                <b>Rs. {{ number_format($net_profit *-1,2) }}Cr</b>
                                            @else
                                                <b>Rs. {{ number_format($net_profit,2) }} Dr</b>
                                            @endif
                                        </b>
                                    </td>
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

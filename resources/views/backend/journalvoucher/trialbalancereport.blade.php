@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Trial Balance</h1>
                </div><!-- /.row -->

                <div class="card">
                    <div class="card-header">
                        <h2>Generate Report</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('trialextra') }}" method="GET">
                            @csrf
                            @method("GET")
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Fiscal Year</label>
                                        <select name="fiscal_year" class="form-control fiscal">
                                            @foreach ($fiscal_years as $fiscal_year)
                                                <option value="{{ $fiscal_year->fiscal_year }}"
                                                    {{ $fiscal_year->id == $current_fiscal_year->id ? 'selected' : '' }}>
                                                    {{ $fiscal_year->fiscal_year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Starting date</label>
                                        <input type="text" name="starting_date" class="form-control startdate"
                                            id="starting_date" value="{{ $actual_year[0] . '-04-01' }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Ending date</label>
                                        <input type="text" name="ending_date" class="form-control enddate" id="ending_date"
                                            value="">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary">Generate Report</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif
                <div class="btn-bulk">
                    <a href="{{ route('pdf.generateTrialBalanceReport', ['id' => $id, 'starting_date' => $starting_date, 'ending_date' => $ending_date]) }}"
                        class="global-btn">Export (PDF)</a> <a
                        href="{{ route('exportfiltertrialbalance', ['id' => $id, 'start_date' => $start_date, 'end_date' => $end_date]) }}"
                        class="global-btn">Export(CSV)</a>
                </div>

                <div class="row mt">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>Trial Balance</h2>
                            </div>
                            <div class="card-body mid-body">
                                <h4>For the fiscal year {{ $current_fiscal_year->fiscal_year }} ({{ $starting_date }}
                                    to {{ $ending_date }})</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th rowspan="2" style="width: 350px;">Accounts</th>
                                                    <th colspan="2" class="text-center">Opening Balance</th>
                                                    <th colspan="2" class="text-center">Transactions</th>
                                                    <th colspan="2" class="text-center">Closing Balance</th>
                                                </tr>
                                                <tr>
                                                    <th>Debit Amount</th>
                                                    <th>Credit Amount</th>
                                                    <th>Debit Amount</th>
                                                    <th>Credit Amount</th>
                                                    <th>Debit Amount</th>
                                                    <th>Credit Amount</th>
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
                                                        $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year, $start_date, $end_date) {
                                                            $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                ->where('is_cancelled', 0)
                                                                ->where('status', 1);
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

                                                    // For Dynamic opening Balance
                                                    $dynjournalextras = [];
                                                    foreach ($collapsedmainsubaccounts as $collapsedmain) {
                                                        $dyneveryjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year, $start_date, $actual_eng_date) {
                                                            $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                ->where('is_cancelled', 0)
                                                                ->where('status', 1);
                                                            $q->where('entry_date_english', '>=', $actual_eng_date);
                                                            $q->where('entry_date_english', '<=', $start_date);
                                                        })
                                                            ->where('child_account_id', $collapsedmain->id)
                                                            ->get();
                                                        array_push($dynjournalextras, $dyneveryjournalextras);
                                                    }
                                                    $dyncollapsedjextras = Arr::collapse($dynjournalextras);
                                                    $dynmainDebitAmounts = [];
                                                    $dynmainCreditAmounts = [];
                                                    foreach ($dyncollapsedjextras as $dyncollapsedjextras) {
                                                        $dynmainDebit = $dyncollapsedjextras->debitAmount;
                                                        $dynmainCredit = $dyncollapsedjextras->creditAmount;
                                                        array_push($dynmainDebitAmounts, $dynmainDebit);
                                                        array_push($dynmainCreditAmounts, $dynmainCredit);
                                                    }
                                                    $dynmainDebitsum = array_sum($dynmainDebitAmounts);
                                                    $dynmainCreditsum = array_sum($dynmainCreditAmounts);
                                                    // Dynamic opening Balance Ends Here

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
                                                    $opening_debit_balances_sum = array_sum($opening_debit_balances) + $dynmainDebitsum;

                                                    $opening_credit_balances_sum = array_sum($opening_credit_balances) - $dynmainCreditsum;
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
                                                    <tr class="main tr-light" style="background-color: #e9ecef;">
                                                        <td>
                                                            <a href="#" class="drop"><i class="main far fa-folder"></i></a>
                                                            <b style="margin-left:5px;">{{ $account->title }}</b>
                                                        </td>
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
                                                                $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year, $start_date, $end_date) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
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
                                                        <tr class="sub display">
                                                            <td><a href="#" class="sub-drop">
                                                                <i class="subicon far fa-folder-open" style="margin-right:5px;"></i></a>
                                                                {{ $subAccount->title }}
                                                            </td>
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
                                                            <tr class="subinside display">
                                                                <td><a href="#" class="subinside-drop"><i class="subinsideicon far fa-folder" style="margin-left:5px;"></i></a>
                                                                    {{ $subaccountinside->title }}
                                                                </td>
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
                                                                <tr class="insidesingle display">
                                                                    <td>
                                                                        <a href="#" class="insidesingle-drop"><i
                                                                                class="las la-file-alt" style="margin-right:3px;font-size:13px;"></i></a>
                                                                        <em>{{ $insidechildAccount->title }}</em>
                                                                    </td>
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
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year, $start_date, $end_date) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
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
                                                            <tr class="single display">
                                                                <td>
                                                                    <a href="#" class="single-drop"><i
                                                                            class="las la-file-alt" style="margin-right:3px;font-size:13px;"></i></a>
                                                                    <em>{{ $childAccount->title }}</em>
                                                                </td>
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
                                                <tr>
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script>
        $(function() {
            var main = $('tr.main');
            var sub = $("tr.sub");
            var drop = $("tr.main a.drop");
            var single = $('tr.single');
            $('td a').click(function(e) {
                e.preventDefault();
            })

            drop.click(function() {
                $(this).parent().parent().parent().find('.sub').toggleClass('display');
                $(this).parent().parent().parent().find('.single').addClass('display');
                $('i.main').toggleClass('fa-folder');
                $('i.main').toggleClass('fa-folder-open');
            })
            $('.sub-drop').click(function() {
                $(this).parent().parent().parent().find('.single').toggleClass('display');
                $(this).parent().parent().parent().find('.subinside').toggleClass('display');
                $('i.subicon').toggleClass('fa-folder');
                $('i.subicon').toggleClass('fa-folder-open');
            })
            $('.subinside-drop').click(function() {
                $(this).parent().parent().parent().find('.insidesingle').toggleClass('display');
                $('i.subinsideicon').toggleClass('fa-folder');
                $('i.subinsideicon').toggleClass('fa-folder-open');
            })
        })
    </script>
    <script>
        $(function() {
            $('.fiscal').change(function() {
                var fiscal_year = $(this).children("option:selected").val();
                var array_date = fiscal_year.split("/");

                var starting_date = document.getElementById("starting_date");
                var starting_full_date = array_date[0] + '-04-01';
                starting_date.value = starting_full_date;
                starting_date.nepaliDatePicker();

                var ending_date = document.getElementById("ending_date");
                var ending_year = array_date[1];
                var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
                var ending_full_date = ending_year + '-03-' + days_count;
                ending_date.value = ending_full_date;

                ending_date.nepaliDatePicker();
            })
        })
    </script>


    <script type="text/javascript">
        window.onload = function() {
            var starting_date = document.getElementById("starting_date");
            var ending_date = document.getElementById("ending_date");
            var ending_year = "{{ $actual_year[1] }}";

            var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
            starting_date.nepaliDatePicker();

            var ending_full_date = ending_year + '-03-' + days_count;
            ending_date.value = ending_full_date;

            ending_date.nepaliDatePicker();
        };
    </script>
@endpush

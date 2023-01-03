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
                    <h1>Profit and Loss </h1>
                </div><!-- /.row -->

                <div class="card">
                    <div class="card-header">
                        <h2>Generate Report</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('journals.profitandlossfilter') }}" method="POST">
                            @csrf
                            @method("POST")
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
                                        <button type="submit" class="btn btn-primary btn-sm">Generate Report</button>
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
                @if (session('success'))
                    <div class="alert  alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="btn-bulk">
                    <a href="{{ route('pdf.generateProfitandLoss') }}" class="global-btn">Export (PDF)</a>
                    <a href="{{ route('exportprofitandloss', $current_fiscal_year->id) }}"
                        class="global-btn">Export(CSV)</a>
                </div>

                <div class="row mt">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>Profit and Loss Account</h2>
                            </div>
                            <div class="card-body mid-body text-center">
                                <h4>For the fiscal year of {{ $current_fiscal_year->fiscal_year }}</h4>
                                <h5>As on {{ $date_in_nep }}</h5>
                            </div>

                            <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Accounts</th>
                                                    <th>Total Amount</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h4>Income</h4>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                @php
                                                    $income_sum = 0;
                                                @endphp
                                                @for ($i = 0; $i < 3; $i++)
                                                    <tr class="sub">

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
                                                                        $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                            $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                                ->where('is_cancelled', 0)
                                                                                ->where('status', 1);
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

                                                                        array_push($subchild_child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);
                                                                    }
                                                                    $subdebitsum = array_sum($subchild_debitadd);
                                                                    $subcreditsum = array_sum($subchild_creditadd);

                                                                    $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                                                    $subchild_opening_total += $subchild_child_opening_balance_sum ?? 0;
                                                                    $subchild_debittotal += $subdebitsum ?? 0;
                                                                    $subchild_credittotal += $subcreditsum ?? 0;

                                                                    $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                                                    // dd($subchild_diff);
                                                                }
                                                                foreach ($child_accounts as $child_account) {
                                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                        $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                            ->where('is_cancelled', 0)
                                                                            ->where('status', 1);
                                                                    })
                                                                        ->where('child_account_id', $child_account->id)
                                                                        ->get();
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

                                                                    array_push($child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);

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
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
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
                                                                array_push($subchild_child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);
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
                                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                        $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                            ->where('is_cancelled', 0)
                                                                            ->where('status', 1);
                                                                    })
                                                                        ->where('child_account_id', $child_account->id)
                                                                        ->get();

                                                                    $debitamts = [];
                                                                    $creditamts = [];


                                                                    foreach($journal_extras as $jextra){
                                                                        array_push($debitamts, $jextra->debitAmount);
                                                                        array_push($creditamts, $jextra->creditAmount);
                                                                    }
                                                                    $child_opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;
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
                                                            <td style="padding-left: 9%;">
                                                                <em>{{ $child_account->title }}</em>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                        $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                            ->where('is_cancelled', 0)
                                                                            ->where('status', 1);
                                                                    })
                                                                        ->where('child_account_id', $child_account->id)
                                                                        ->get();


                                                                    $debitamts = [];
                                                                    $creditamts = [];


                                                                    foreach($journal_extras as $jextra){
                                                                        array_push($debitamts, $jextra->debitAmount);
                                                                        array_push($creditamts, $jextra->creditAmount);
                                                                    }
                                                                    $child_opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;
                                                                    // dd($child_opening_balance);
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

                                                <tr class="tr-light">
                                                    <td class="text-center">
                                                        <b style="font-size: 14px;font-weight:600;">Total Income</b>
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
                                                        <h4><span style="font-size: 14px;font-weight:600;">Less:</span> Cost Of Goods Sold
                                                        </h4>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr class="sub">
                                                    <td style="padding-left: 5%;"><b>{{ $sub_accounts[5]->title }}</b>
                                                    </td>
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
                                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                        $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                            ->where('is_cancelled', 0)
                                                                            ->where('status', 1);
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

                                                                    array_push($subchild_child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);
                                                                }
                                                                $subdebitsum = array_sum($subchild_debitadd);
                                                                $subcreditsum = array_sum($subchild_creditadd);

                                                                $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                                                $subchild_opening_total += $subchild_child_opening_balance_sum ?? 0;
                                                                $subchild_debittotal += $subdebitsum ?? 0;
                                                                $subchild_credittotal += $subcreditsum ?? 0;

                                                                $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                                            }
                                                            foreach ($child_accounts as $child_account) {
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
                                                                })
                                                                    ->where('child_account_id', $child_account->id)
                                                                    ->get();
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
                                                                array_push($child_opening_balances , $childaccount->this_year_opening_balance->opening_balance ?? 0);
                                                            }
                                                            $subdebitsum = array_sum($debitadd) + $subchild_debittotal;
                                                            $subcreditsum = array_sum($creditadd) + $subchild_credittotal;

                                                            $child_opening_balance_sum = array_sum($child_opening_balances) + $subchild_opening_total;

                                                            $diff = $child_opening_balance_sum + $subdebitsum - $subcreditsum;
                                                        @endphp
                                                        @if ($diff < 0)
                                                            <b>Rs. {{ number_format($diff * -1, 2) }} Cr</b>
                                                        @else
                                                            <b>Rs. {{ number_format($diff, 2) }} Dr</b>
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
                                                            $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                    ->where('is_cancelled', 0)
                                                                    ->where('status', 1);
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

                                                            array_push($subchild_child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);
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
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
                                                                })
                                                                    ->where('child_account_id', $child_account->id)
                                                                    ->get();

                                                                $debitamts = [];
                                                                $creditamts = [];

                                                                foreach($journal_extras as $jextra){
                                                                    array_push($debitamts, $jextra->debitAmount);
                                                                    array_push($creditamts, $jextra->creditAmount);
                                                                }
                                                                $child_opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;
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
                                                        <td style="padding-left: 9%;">
                                                            <em>{{ $child_account->title }}</em>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
                                                                })
                                                                    ->where('child_account_id', $child_account->id)
                                                                    ->get();

                                                                $debitamts = [];
                                                                $creditamts = [];
                                                                foreach($journal_extras as $jextra){
                                                                    array_push($debitamts, $jextra->debitAmount);
                                                                    array_push($creditamts, $jextra->creditAmount);
                                                                }
                                                                $child_opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;
                                                                // dd($child_opening_balance);
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

                                                <tr class="tr-light">
                                                    <td class="text-center">
                                                        <b style="font-size: 14px;font-weight:600;">Total Cost of Sales</b>
                                                    </td>
                                                    <td>
                                                        <b>
                                                            @if ($diff < 0)
                                                                <b>Rs. {{ number_format($diff * -1, 2) }} Cr</b>
                                                            @else
                                                                <b>Rs. {{ number_format($diff, 2) }} Dr</b>
                                                            @endif
                                                        </b>
                                                    </td>
                                                </tr>

                                                <tr class="tr-light">
                                                    <td class="text-center">
                                                        <b style="font-size: 14px;font-weight:600;">Gross Profit / Loss</b>
                                                    </td>
                                                    <td>
                                                        <b>
                                                            @php
                                                                $gross_profit = $income_sum + $diff;
                                                            @endphp
                                                            @if ($gross_profit < 0)
                                                                <b>Rs. {{ number_format($gross_profit * -1,2) }} Cr</b>
                                                            @else
                                                                <b>Rs. {{ number_format($gross_profit,2) }} Dr</b>
                                                            @endif
                                                        </b>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <h4><span style="font-size: 14px;">Less:</span> Operating Expenses
                                                        </h4>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr class="sub">
                                                    <td style="padding-left: 5%;"><b>{{ $sub_accounts[3]->title }}</b>
                                                    </td>
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
                                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                        $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                            ->where('is_cancelled', 0)
                                                                            ->where('status', 1);
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

                                                                    array_push($subchild_child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);
                                                                }
                                                                $subdebitsum = array_sum($subchild_debitadd);
                                                                $subcreditsum = array_sum($subchild_creditadd);

                                                                $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                                                $subchild_opening_total += $subchild_child_opening_balance_sum ?? 0;
                                                                $subchild_debittotal += $subdebitsum ?? 0;
                                                                $subchild_credittotal += $subcreditsum ?? 0;

                                                                $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                                            }
                                                            foreach ($child_accounts as $child_account) {
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
                                                                })
                                                                    ->where('child_account_id', $child_account->id)
                                                                    ->get();
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
                                                                array_push($child_opening_balances , $childaccount->this_year_opening_balance->opening_balance ?? 0);
                                                            }
                                                            // dd($creditadd);
                                                            $subdebitsum = array_sum($debitadd) + $subchild_debittotal;
                                                            $subcreditsum = array_sum($creditadd) + $subchild_credittotal;

                                                            $child_opening_balance_sum = array_sum($child_opening_balances) + $subchild_opening_total;
                                                            $diff1 = $child_opening_balance_sum + $subdebitsum - $subcreditsum;
                                                        @endphp
                                                        @if ($diff1 < 0)
                                                            <b>Rs. {{ number_format($diff1 * -1, 2) }} Cr</b>
                                                        @else
                                                            <b>Rs. {{ number_format($diff1, 2) }} Dr</b>
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
                                                            $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                    ->where('is_cancelled', 0)
                                                                    ->where('status', 1);
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

                                                            array_push($subchild_child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);
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
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
                                                                })
                                                                    ->where('child_account_id', $child_account->id)
                                                                    ->get();

                                                                $debitamts = [];
                                                                $creditamts = [];


                                                                foreach($journal_extras as $jextra){
                                                                    array_push($debitamts, $jextra->debitAmount);
                                                                    array_push($creditamts, $jextra->creditAmount);
                                                                }
                                                                $child_opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;
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
                                                        <td style="padding-left: 9%;">
                                                            <em>{{ $child_account->title }}</em>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
                                                                })
                                                                    ->where('child_account_id', $child_account->id)
                                                                    ->get();

                                                                $debitamts = [];
                                                                $creditamts = [];

                                                                foreach($journal_extras as $jextra){
                                                                    array_push($debitamts, $jextra->debitAmount);
                                                                    array_push($creditamts, $jextra->creditAmount);
                                                                }
                                                                $child_opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;
                                                                // dd($child_opening_balance);
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
                                                        <h4><span style="font-size: 14px;">Less:</span> Non-Operating
                                                            Expenses</h4>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr class="sub">
                                                    <td style="padding-left: 5%;"><b>{{ $sub_accounts[4]->title }}</b>
                                                    </td>
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
                                                                    $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                        $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                            ->where('is_cancelled', 0)
                                                                            ->where('status', 1);
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

                                                                    array_push($subchild_child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);
                                                                }
                                                                $subdebitsum = array_sum($subchild_debitadd);
                                                                $subcreditsum = array_sum($subchild_creditadd);

                                                                $subchild_child_opening_balance_sum = array_sum($subchild_child_opening_balances);

                                                                $subchild_opening_total += $subchild_child_opening_balance_sum ?? 0;
                                                                $subchild_debittotal += $subdebitsum ?? 0;
                                                                $subchild_credittotal += $subcreditsum ?? 0;

                                                                $subchild_diff = $subchild_child_opening_balance_sum + $subchild_debittotal - $subchild_credittotal;
                                                            }

                                                            foreach ($child_accounts as $child_account) {
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
                                                                })
                                                                    ->where('child_account_id', $child_account->id)
                                                                    ->get();
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
                                                                array_push($child_opening_balances , $childaccount->this_year_opening_balance->opening_balance ?? 0);
                                                            }
                                                            // dd($creditadd);
                                                            $subdebitsum = array_sum($debitadd) + $subchild_debittotal;
                                                            $subcreditsum = array_sum($creditadd) + $subchild_credittotal;

                                                            $child_opening_balance_sum = array_sum($child_opening_balances) + $subchild_opening_total;

                                                            $diff2 = $child_opening_balance_sum + $subdebitsum - $subcreditsum;
                                                        @endphp
                                                        @if ($diff2 < 0)
                                                            <b>Rs. {{ number_format($diff2 * -1, 2) }} Cr</b>
                                                        @else
                                                            <b>Rs. {{ number_format($diff2, 2) }} Dr</b>
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
                                                            $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                    ->where('is_cancelled', 0)
                                                                    ->where('status', 1);
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

                                                            array_push($subchild_child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);
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
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
                                                                })
                                                                    ->where('child_account_id', $child_account->id)
                                                                    ->get();

                                                                $debitamts = [];
                                                                $creditamts = [];


                                                                foreach($journal_extras as $jextra){
                                                                    array_push($debitamts, $jextra->debitAmount);
                                                                    array_push($creditamts, $jextra->creditAmount);
                                                                }
                                                                $child_opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;
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
                                                        <td style="padding-left: 9%;">
                                                            <em>{{ $child_account->title }}</em>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1);
                                                                })
                                                                    ->where('child_account_id', $child_account->id)
                                                                    ->get();

                                                                $debitamts = [];
                                                                $creditamts = [];
                                                                foreach($journal_extras as $jextra){
                                                                    array_push($debitamts, $jextra->debitAmount);
                                                                    array_push($creditamts, $jextra->creditAmount);
                                                                }
                                                                $child_opening_balance = $child_account->this_year_opening_balance->opening_balance ?? 0;
                                                                // dd($child_opening_balance);
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

                                                <tr class="tr-light">
                                                    <td class="text-center">
                                                        <b style="font-size: 14px;">Total Expenses</b>
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

                                                <tr class="tr-light">
                                                    <td class="text-center">
                                                        <b style="font-size: 14px;">Net Profit / Loss</b>
                                                    </td>
                                                    <td>
                                                        <b>
                                                            @php
                                                                $net_profit = $gross_profit + $total_expenses;
                                                            @endphp
                                                            @if ($net_profit < 0)
                                                                <b>Rs. {{ number_format($net_profit * -1,2) }} Cr</b>
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

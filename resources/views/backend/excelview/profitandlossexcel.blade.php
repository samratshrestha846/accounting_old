<table>
    <thead>
        <tr>
            <th>Profit and Loss Account</th>
        </tr>
        <tr>
            <th>For the fiscal year of {{ $current_fiscal_year->fiscal_year }}</th>
        </tr>
        <tr>
            <th>As on {{ $date_in_nep }}</th>
        </tr>
        <tr>
            <th></th>
        </tr>
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
            <tr>

                <td>
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
                        }
                        foreach ($child_accounts as $child_account)
                        {
                            $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
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
                <td style = "padding-left: 15px;">
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
                <td style="padding-left: 30px;">
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
                    <td><em>{{ $child_account->title }}</em></td>
                    <td>
                        @php
                            $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                })
                                                ->where('child_account_id', $child_account->id)->get();


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

        <tr>
            <td>
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
                <h4><span style="font-size: 15px;">Less:</span> Cost Of Goods Sold</h4>
            </td>
            <td></td>
        </tr>

        <tr>
            <td><b>{{ $sub_accounts[5]->title }}</b></td>
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
                    foreach ($child_accounts as $child_account)
                    {
                        $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
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
                        array_push ($creditadd, $creditsum);
                        array_push($child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);

                    }
                    $subdebitsum = array_sum($debitadd) + $subchild_debittotal;
                    $subcreditsum = array_sum($creditadd) + $subchild_credittotal;

                    $child_opening_balance_sum = array_sum($child_opening_balances) + $subchild_opening_total;

                    $diff = $child_opening_balance_sum + $subdebitsum - $subcreditsum;
                @endphp
                @if ($diff < 0)
                    <b>Rs. {{ number_format($diff * -1,2) }}</b> Cr
                @else
                    <b>Rs. {{ number_format($diff,2) }}</b> Dr
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
                <td style = "padding-left: 15px;">
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
                <td style="padding-left: 30px;">
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
                <td style="padding-left: 15px;"><em>{{ $child_account->title }}</em></td>
                <td>
                    @php
                        $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                })
                                                ->where('child_account_id', $child_account->id)->get();

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

        <tr>
            <td>
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

        <tr>
            <td>
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
                <h4><span style="font-size: 15px;">Less:</span> Operating Expenses</h4>
            </td>
            <td></td>
        </tr>

        <tr>
            <td style="padding-left: 10px;"><b>{{ $sub_accounts[3]->title }}</b></td>
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
                    foreach ($child_accounts as $child_account)
                    {
                        $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
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
                        array_push($child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);
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
                <td style = "padding-left: 15px;">
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
                <td style="padding-left: 30px;">
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
                <td style="padding-left: 15px;"><em>{{ $child_account->title }}</em></td>
                <td>
                    @php
                        $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                })
                                                ->where('child_account_id', $child_account->id)->get();

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
                <h4><span style="font-size: 15px;">Less:</span> Non-Operating Expenses</h4>
            </td>
            <td></td>
        </tr>

        <tr>
            <td style="padding-left: 10px;"><b>{{ $sub_accounts[4]->title }}</b></td>
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
                    foreach ($child_accounts as $child_account)
                    {
                        $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
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
                        array_push($child_opening_balances , $child_account->this_year_opening_balance->opening_balance ?? 0);

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
                <td style = "padding-left: 15px;">
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
                <td style="padding-left: 30px;">
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
                <td style="padding-left: 15px;"><em>{{ $child_account->title }}</em></td>
                <td>
                    @php
                        $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                })
                                                ->where('child_account_id', $child_account->id)->get();

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
                <b style="font-size: 15px;">Total Expenses</b>
            </td>
            <td>
                <b>
                    @php
                        $total_expenses = $diff1 + $diff2;
                    @endphp
                    @if($total_expenses < 0)
                    Rs. {{ number_format($total_expenses,2) }} Cr
                    @else
                    Rs. {{ number_format($total_expenses,2) }} Dr
                    @endif

                </b>
            </td>
        </tr>

        <tr>
            <td>
                <b style="font-size: 15px;">Net Profit / Loss</b>
            </td>
            <td>
                <b>
                    @php
                        $net_profit = $gross_profit + $total_expenses;
                    @endphp
                    @if ($net_profit < 0)
                        <b>Rs. {{ number_format($net_profit *-1,2)}} Cr</b>
                    @else
                        <b>Rs. {{ number_format($net_profit,2) }} Dr</b>
                    @endif
                </b>
            </td>
        </tr>
    </tbody>

</table>

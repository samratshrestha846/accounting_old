<table>
    <thead>
        <tr>
            <th>Trial Balance</th>
        </tr>
        <tr>
            <th>For the fiscal year {{ $current_fiscal_year->fiscal_year }}</th>
        </tr>
        <tr></tr>
        {{-- <tr>
            <th>Accounts</th>
            <th>Opening Balance</th>
            <th>Debit Amount</th>
            <th>Credit Amount</th>
            <th>Closing Balance</th>
        </tr> --}}
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
            $opening_balances = [];
            $closing_balances = [];

            foreach ($subaccounts as $subaccount){
                $everymainchildaccounts = $subaccount->child_accounts;
                array_push($mainchildaccounts, $everymainchildaccounts);
            }
            $collapsedmainsubaccounts = Arr::collapse($mainchildaccounts);
            $journalextras = [];
            foreach ($collapsedmainsubaccounts as $collapsedmain) {
                $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                            $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
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
            // Opening Balance Sum of Main Accounts
            $opening_debit_balances = [];
            $opening_credit_balances = [];
            $closing_debit_balances = [];
            $closing_credit_balances = [];
            foreach($account->sub_accounts as $subaccount){
                foreach($subaccount->child_account as $childacc){
                    $child = $childacc->this_year_opening_balance;
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
            $mainDebitsum = array_sum($mainDebitAmounts);
            $mainCreditsum = array_sum($mainCreditAmounts);

            array_push($debittotal, $mainDebitsum);
            array_push($credittotal, $mainCreditsum);
            array_push($openingdebittotal, $opening_debit_balances_sum);
            array_push($openingcredittotal, $opening_credit_balances_sum);
            array_push($closingdebittotal, $closing_debit_balances_sum);
            array_push($closingcredittotal, $closing_credit_balances_sum);
        @endphp
        <tbody>
            <tr>
                <td>{{ $account->title }}</td>
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
                        $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                            $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
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
                    // Opening Balance Sum of Sub Accounts
                    $sub_opening_debit_balances = [];
                    $sub_opening_credit_balances = [];
                    $sub_closing_debit_balances = [];
                    $sub_closing_credit_balances = [];
                    foreach($subAccount->child_account as $childacc){
                        $child = $childacc->this_year_opening_balance;
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

                    $subDebitsum = array_sum($subDebitAmounts);
                    $subCreditsum = array_sum($subCreditAmounts);

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
                            $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                    ->where('is_cancelled', 0)
                                    ->where('status', 1);
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
                            $child = $childacc->this_year_opening_balance;
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
                <tr>
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
                            $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                    ->where('is_cancelled', 0)
                                    ->where('status', 1);
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
                            $child = $childacc->this_year_opening_balance;
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
                        <td style="padding-left: 30%;">{{ $subaccountinside->title }}</td>
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
                        $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                            $q->where('fiscal_year_id', '=', $current_fiscal_year->id)
                                ->where('is_cancelled', 0)
                                ->where('status', 1);
                        })
                            ->where('child_account_id', $insidechildAccount->id)
                            ->get();
                        foreach ($journal_extras as $journalExtra) {
                            array_push($debitAmount, $journalExtra->debitAmount);
                            array_push($creditAmount, $journalExtra->creditAmount);
                        }

                        $child_opening_balance = $insidechildAccount->this_year_opening_balance->opening_balance ?? 0;
                        $child_closing_balance = $insidechildAccount->this_year_opening_balance->closing_balance ?? 0;


                        $debitSum = array_sum($debitAmount);
                        $creditSum = array_sum($creditAmount);


                    @endphp
                        <tr>
                            <td style="padding-left:45%">{{ $insidechildAccount->title }}</td>
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
                        $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                            $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                        })
                            ->where('child_account_id', $childAccount->id)
                            ->get();
                            // dd($journal_extras);
                        foreach ($journal_extras as $journalExtra) {
                            array_push($debitAmount, $journalExtra->debitAmount);
                            array_push($creditAmount, $journalExtra->creditAmount);
                        }

                        $child_opening_balance = $childAccount->this_year_opening_balance->opening_balance ?? 0;
                        $child_closing_balance = $childAccount->this_year_opening_balance->closing_balance ?? 0;

                        // dd($debitAmount);
                        $debitSum = array_sum($debitAmount);
                        $creditSum = array_sum($creditAmount);
                    @endphp
                    <tr>
                        <td style="padding-left:30%">{{ $childAccount->title }}</td>
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

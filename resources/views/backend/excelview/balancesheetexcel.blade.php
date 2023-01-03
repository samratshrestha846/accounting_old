<table>
    <thead>
        <tr>
            <th>Balance Sheet</th>
        </tr>
        <tr>
            <th>As on {{ $today_date_nepali }}</th>
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
            <td>
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
                <td>
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

                            $diff_amount = $child_opening_balance + $creditsum - $debitsum;

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
            <td><em>Net Profit / Loss</em></td>
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
                <td>
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
                    <td>
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

                                $diff_amount = $child_opening_balance + $creditsum - $debitsum;
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


        <tr style="background-color: grey; color: white;">
            <th class="text-center">Total Equity and Liabilities</th>
            <th>
                Rs. {{ array_sum($equity_calculation_array) < 0 ? number_format(array_sum($equity_calculation_array) * -1, 2) . ' Cr' : number_format(array_sum($equity_calculation_array), 2). ' Dr'}}
            </th>
        </tr>


        <tr>
            <td>
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
                <td>
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
                    <td>
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

        <tr style="background-color: grey; color: white;">
            <th class="text-center">Total Assets</th>
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

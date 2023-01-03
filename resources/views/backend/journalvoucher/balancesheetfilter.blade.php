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
                    <h1>Balance Sheet </h1>
                </div><!-- /.row -->


                <div class="card">
                    <div class="card-header">
                        <h2>Generate Report</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('journals.balancesheetfilter') }}" method="POST">
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
                    <a href="{{ route('pdf.generateBalanceSheetReport', ['id' => $selected_fiscal_year->id, 'starting_date' => $starting_date, 'ending_date' => $ending_date]) }}"
                        class="global-btn">Export (PDF)</a> <a
                        href="{{ route('exportfilterbalancesheet', ['id' => $selected_fiscal_year->id, 'starting_date' => $starting_date, 'ending_date' => $ending_date]) }}"
                        class="global-btn">Export(CSV)</a>
                </div>

                <div class="row mt">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header text-center">
                                <h2>Balance Sheet</h2>
                            </div>
                            <div class="card-body mid-body">
                                <h4 class="mb-1">For the fiscal Year {{ $selected_fiscal_year->fiscal_year }}</h4>
                                <h4>{{ $starting_date }} to {{ $ending_date }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Accounts</th>
                                                <th>Total Balance</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr class="sub">
                                                <td style="font-size: 14px;font-weight:600;">
                                                    Equity and Liabilities
                                                </td>
                                                <td></td>
                                            </tr>

                                            @php
                                                $equity_calculation_array = [];
                                                array_push($equity_calculation_array, $retained_earnings);
                                                $assets_calculation_array = [];
                                                $shareholder_equity = \App\Models\SubAccount::where('sub_account_id', null)->where('account_id', $main_accounts[2]->id)
                                                    ->where('slug', 'shareholders-equity')
                                                    ->first();
                                                $inside_shareholder_equity = \App\Models\SubAccount::where('sub_account_id', $shareholder_equity->id)->get();
                                                $child_accounts_equity = \App\Models\ChildAccount::where('sub_account_id', $shareholder_equity->id)->get();
                                            @endphp

                                            <tr>
                                                <td style="padding-left: 5%;">
                                                    <b>{{ $shareholder_equity->title }}</b>
                                                </td>
                                                <td></td>
                                            </tr>

                                            @foreach ($inside_shareholder_equity as $subAccount)
                                                @php
                                                    $total_amount = 0;
                                                    $child_accounts = \App\Models\ChildAccount::where('sub_account_id', $subAccount->id)->get();
                                                    foreach($child_accounts as $child_account){
                                                        $child_account_closing_balance = $child_account->custom_year($current_fiscal_year->id, $childacc->id)->closing_balance;
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
                                                            $closing_balance = $child_account->custom_year($current_fiscal_year->id, $childacc->id)->closing_balance;
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
                                                        $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                            $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                                ->where('is_cancelled', 0)
                                                                ->where('status', 1);
                                                            $q->where('entry_date_english', '>=', $start_date);
                                                            $q->where('entry_date_english', '<=', $end_date);
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
                                                        $child_opening_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0;

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
                                                <td style="padding-left: 10%;"><em>Net Profit / Loss</em></td>
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
                                                            $child_account_closing_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->closing_balance ?? 0;
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
                                                                $closing_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->closing_balance ?? 0;
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
                                                            $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                                $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                                    ->where('is_cancelled', 0)
                                                                    ->where('status', 1);
                                                                $q->where('entry_date_english', '>=', $start_date);
                                                                $q->where('entry_date_english', '<=', $end_date);
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
                                                            $child_opening_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0;

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


                                            <tr class="tr-light">
                                                <td class="text-center" style="font-size:12px;font-weight:600;">Total Equity and Liabilities</td>
                                                <td style="font-size:12px;font-weight:600;">
                                                    Rs. {{ array_sum($equity_calculation_array) < 0 ? number_format(array_sum($equity_calculation_array) * -1, 2) . ' Cr' : number_format(array_sum($equity_calculation_array), 2). ' Dr'}}
                                                </td>
                                            </tr>


                                            <tr class="sub">
                                                <td style="font-size: 14px;font-weight:600;">
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
                                                            $child_account_closing_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->closing_balance ?? 0;
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
                                                                $closing_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->closing_balance ?? 0;
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
                                                            $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use ($selected_fiscal_year, $start_date, $end_date) {
                                                                $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)
                                                                    ->where('is_cancelled', 0)
                                                                    ->where('status', 1);
                                                                $q->where('entry_date_english', '>=', $start_date);
                                                                $q->where('entry_date_english', '<=', $end_date);
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
                                                            $child_opening_balance = $child_account->custom_year($selected_fiscal_year->id, $child_account->id)->opening_balance ?? 0;

                                                            $diff_amount = $child_opening_balance + $debitsum - $creditsum;
                                                            array_push($assets_calculation_array, $diff_amount);
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

                                            <tr class="tr-light">
                                                <td class="text-center" style="font-size:12px;font-weight:600;">Total Assets</td>
                                                <td style="font-size:12px;font-weight:600;">
                                                    @php
                                                        $asset_cal = array_sum($assets_calculation_array);
                                                        if($asset_cal < 0){
                                                            $asset_cal_bef = $asset_cal * -1;
                                                        }
                                                    @endphp
                                                    Rs. {{ $asset_cal < 0 ? number_format($asset_cal_bef, 2). ' Cr' : number_format($asset_cal, 2).' Dr' }}
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

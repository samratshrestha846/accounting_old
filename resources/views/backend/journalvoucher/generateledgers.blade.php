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
                    <h1>Accounting Ledgers </h1>
                </div><!-- /.row -->

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

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>Generate Ledgers</h2>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('generateledgers') }}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Account Heads:</label>
                                                <select name="child_account_id" class="form-control account_head" required>
                                                    <option value="">--Select an Account Head--</option>
                                                    @foreach ($accounts as $account)
                                                        <option value="" class="title" disabled>
                                                            {{ $account->title }}</option>
                                                        @php
                                                            $sub_accounts = $account->sub_accounts;
                                                        @endphp

                                                        @foreach ($sub_accounts as $sub_account)
                                                            @php
                                                                $child_accounts = $sub_account->child_accounts;
                                                            @endphp
                                                            @foreach ($child_accounts as $child_account)
                                                                <option value="{{ $child_account->id }}">
                                                                    {{ $child_account->title }} -
                                                                    {{ $sub_account->title }}</option>
                                                            @endforeach
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
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
                                                <input type="text" name="ending_date" class="form-control enddate"
                                                    id="ending_date" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary ml-auto">Generate</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <div class="btn-bulk">
                            <a href="{{ route('pdf.generateLedgerReport', ['fiscal_year_id' => $selected_year->id, 'id' => $childAccount->id, 'starting_date' => $starting_date, 'ending_date' => $ending_date]) }}"
                                class="global-btn">
                                Export (PDF)
                            </a> <a
                                href="{{ route('exportfilterledger', ['fiscal_year_id' => $selected_year->id, 'id' => $childAccount->id, 'starting_date' => $starting_date, 'ending_date' => $ending_date]) }}"
                                class="global-btn">
                                Export (CSV)
                            </a>
                        </div>
                        <div class="card mt-3">
                            <div class="card-header text-center">
                                <h2>{{ $childAccount->title }} A/C</h2>
                            </div>
                            <div class="card-body mid-body text-center">
                                <p>Fiscal Year: {{ $selected_year->fiscal_year }} ({{ $starting_date }} to
                                    {{ $ending_date }})</p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center mt-2">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-nowrap">Date</th>
                                                <th class="text-nowrap">J.V no.</th>
                                                <th class="text-nowrap">Related Supplier</th>
                                                <th class="text-nowrap">Debit Amount</th>
                                                <th class="text-nowrap">Credit Amount</th>
                                                <th class="text-nowrap">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $starting_date }}</td>
                                                <td>Opening Balance</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>
                                                    @if ($main_opening_balance < 0)
                                                        (Rs. {{ $main_opening_balance * -1 }})
                                                    @else
                                                        Rs. {{ $main_opening_balance }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @if (count($journal_extras) == 0)
                                                {{-- <tr>
                                                <td colspan="6"><h4>No any records..</h4></td>
                                            </tr> --}}
                                            @else
                                                @foreach ($journal_extras as $extra)
                                                    @php
                                                        $related_journal = \App\Models\JournalVouchers::where('fiscal_year_id', $selected_year->id)
                                                            ->where('id', $extra->journal_voucher_id)
                                                            ->where('entry_date_english', '>=', $start_date)
                                                            ->where('entry_date_english', '<=', $end_date)
                                                            ->where('is_cancelled', 0)
                                                            ->where('status', 1)
                                                            ->first();
                                                    @endphp
                                                    @if ($related_journal)
                                                        <tr>
                                                            <td>
                                                                {{ $related_journal->entry_date_nepali }}
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('journals.show', $related_journal->id) }}"
                                                                    target="_blank" class="journal_number"
                                                                    style="text-decoration: none;">
                                                                    {{ $related_journal->journal_voucher_no }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                @if ($related_journal->vendor_id == null)
                                                                    -
                                                                @else
                                                                    @php
                                                                        $vendor = \App\Models\Vendor::where('id', $related_journal->vendor_id)->first();
                                                                    @endphp
                                                                    {{ $vendor->company_name }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($extra->debitAmount > 0)
                                                                    Rs. {{ $extra->debitAmount }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>

                                                            <td>
                                                                @if ($extra->creditAmount > 0)
                                                                    Rs. {{ $extra->creditAmount }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($extra->debitAmount > 0)
                                                                    @php
                                                                        $main_opening_balance += $extra->debitAmount;
                                                                    @endphp
                                                                    @if ($main_opening_balance < 0)
                                                                        Rs. {{ $main_opening_balance * -1 }} .Cr
                                                                    @else
                                                                        Rs. {{ $main_opening_balance }} .Dr
                                                                    @endif
                                                                @else
                                                                    @php
                                                                        $main_opening_balance -= $extra->creditAmount;
                                                                    @endphp
                                                                    @if ($main_opening_balance < 0)
                                                                        Rs. {{ $main_opening_balance * -1 }} .Cr
                                                                    @else
                                                                        Rs. {{ $main_opening_balance }} .Dr
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td>{{ $ending_date }}</td>
                                                <td>Closing Balance</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>
                                                    @if ($main_opening_balance < 0)
                                                        Rs. {{ $main_opening_balance * -1 }} .Cr
                                                    @else
                                                        Rs. {{ $main_opening_balance }} .Dr
                                                    @endif
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
    </div>
    </div>

    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(".account_head").select2();
        });
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

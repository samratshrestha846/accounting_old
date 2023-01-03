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
                    <span style="display: block;margin-top:15px;font-size:20px;font-weight:bold;margin-bottom:10px;">{{ $childAccount->title }} A/C</span>
                    <span style="display: block;">For the fiscal year {{ $selected_fiscal_year->fiscal_year }} </span>
                    <span style="display: block;margin-bottom:10px;">({{ $starting_date }} to {{ $ending_date }})</span>
                </div>
                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                        <table style="width: 100%">
                            <thead style="background-color: #de4a32; color: #ffffff;">
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
                                            $related_journal = \App\Models\JournalVouchers::where('fiscal_year_id', $selected_fiscal_year->id)
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
                                                    <a href="{{ route('journals.show', $related_journal->id) }}" target="_blank" class="journal_number" style="text-decoration: none;">
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
                                    {{-- <tr>
                                        <td colspan="3"><b>Total</b></td>
                                        @foreach ($collection as $item)

                                        @endforeach
                                        <td>
                                            {{ dd($related_journal) }}{{ $related_journal->debitTotal }}</td>
                                        <td>{{ $related_journal->creditTotal }}</td>
                                    </tr> --}}
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
    </section>
    <!-- /.content -->
    </body>
</html>

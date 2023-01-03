<table>
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th colspan="2">
                {{ $childAccount->title }} A/C
            </th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th colspan="2">
                For the fiscal year {{ $selected_fiscal_year->fiscal_year }} ({{ $starting_date }} to {{ $ending_date }})
            </th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th style="width: 15%">Date</th>
            <th>J.V no.</th>
            <th>Related Supplier</th>
            <th>Debit Amount</th>
            <th>Credit Amount</th>
            <th>Balance</th>
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
                                    (Rs. {{ $main_opening_balance * -1 }})
                                @else
                                    Rs. {{ $main_opening_balance }}
                                @endif
                            @else
                                @php
                                    $main_opening_balance -= $extra->creditAmount;
                                @endphp
                                @if ($main_opening_balance < 0)
                                    (Rs. {{ $main_opening_balance * -1 }})
                                @else
                                    Rs. {{ $main_opening_balance }}
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
                    (Rs. {{ $main_opening_balance * -1 }})
                @else
                    Rs. {{ $main_opening_balance }}
                @endif
            </td>
        </tr>
    </tbody>
</table>

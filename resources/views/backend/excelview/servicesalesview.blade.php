<table>
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
                <th>Service Sales Bills</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>For the fiscal year {{ $fiscal_year }}</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>

        <tr></tr>
        <tr>
            <th>Date</th>
            <th>Reference No.</th>
            <th>Transaction No.</th>
            <th>Customer</th>
            <th>Payment Mode</th>
            <th>Sub Total</th>
            <th>Tax Amount</th>
            <th>Discount Amount</th>
            <th>Shipping</th>
            <th>Grand Total</th>
            <th>Entried By</th>
            <th>Approved by</th>
            <th>Edited By</th>
            <th>Cancelled By</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($billings as $billing)
            <tr>
                <td>{{$billing->nep_date}}</td>
                <td>
                    {{$billing->reference_no}}
                </td>
                <td>
                    {{$billing->transaction_no}}
                </td>
                <td>{{ $billing->client_id == null ? '-' : $billing->client->name }}</td>
                <td>
                    @if ($billing->payment_method == 2)
                        Cheque ({{ $billing->bank->bank_name }} / Cheque no.: {{ $billing->cheque_no }})
                    @elseif($billing->payment_method == 3)
                        Bank Deposit ({{ $billing->bank->bank_name }})
                    @elseif($billing->payment_method == 4)
                        Online Portal ({{ $billing->online_portal->name }} / Portal Id: {{ $billing->customer_portal_id }})
                    @else
                        Cash
                    @endif
                </td>
                <td>Rs. {{$billing->subtotal}}</td>
                <td>Rs. {{$billing->taxamount}}</td>
                <td>Rs. {{$billing->discountamount}}</td>
                <td>Rs. {{$billing->shipping}}</td>
                <td>Rs. {{$billing->grandtotal}}</td>
                <td>{{$billing->user_entry->name}}</td>
                <td>
                    @if (!$billing->approved_by == null)
                        {{$billing->user_approve->name}}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if (!$billing->edited_by == null)
                        {{$billing->user_edit->name}}
                    @else
                        -
                    @endif
                </td>

                <td>
                    @if ($billing->is_cancelled == 0)
                        -
                    @else
                        {{$billing->user_cancel->name}}
                    @endif
                </td>
                <td>
                    {{$billing->remarks}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

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
            @if ($pos_data == 1)
                <th style="width: 90px;">POS Sales Bills</th>
            @else
                <th style="width: 90px;">{{ $billing_type->billing_types }} Bills</th>
            @endif
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
            @if ($pos_data == 1)
                <th>Outlet</th>
            @endif
            <th>Reference No.</th>
            <th>Transaction No.</th>
            <th>Vat Bill No.</th>
            @if ($billing_type->id == 1 || $billing_type->id == 6 || $billing_type->id == 7)
                <th>Customer</th>
                <th>PAN / VAT No.</th>
            @endif
            @if ($billing_type->id == 2 || $billing_type->id == 3 || $billing_type->id == 4 || $billing_type->id == 5)
                <th>Supplier</th>
                <th>PAN / VAT No.</th>
            @endif
            <th>Particulars</th>
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
                <td>{{ $billing->nep_date }}</td>
                @if ($pos_data == 1)
                    <th>{{ $billing->outlet->name }}</th>
                @endif
                <td>
                    {{ $billing->reference_no }}
                </td>
                <td>
                    {{ $billing->transaction_no }}
                </td>
                <td>
                    {{ $billing->ledger_no ?? 'Not given' }}
                </td>
                @if ($billing_type->id == 1 || $billing_type->id == 6 || $billing_type->id == 7)
                    <td>{{ $billing->client_id == null ? '-' : $billing->client->name }}</td>
                    <td>{{ $billing->client->pan_vat == null ? 'Not given' : $billing->client->pan_vat   }}</td>
                @endif
                @if ($billing_type->id == 2 || $billing_type->id == 3 || $billing_type->id == 4 || $billing_type->id == 5)
                    <td>{{ $billing->vendor_id == null ? '-' : $billing->suppliers->company_name }}</td>
                    <td>{{ $billing->suppliers->pan_vat == null ? 'Not given' : $billing->suppliers->pan_vat   }}</td>
                @endif
                <td>
                    @foreach ($billing->billingextras as $billingextra)
                        {{ $billingextra->product->product_name }},
                    @endforeach
                </td>
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
                <td>Rs. {{ $billing->subtotal }}</td>
                <td>Rs. {{ $billing->taxamount }}</td>
                <td>Rs. {{ $billing->discountamount }}</td>
                <td>Rs. {{ $billing->shipping }}</td>
                <td>Rs. {{ $billing->grandtotal }}</td>
                <td>{{ $billing->user_entry->name }}</td>
                <td>
                    @if (!$billing->approved_by == null)
                        {{ $billing->user_approve->name }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if (!$billing->edited_by == null)
                        {{ $billing->user_edit->name }}
                    @else
                        -
                    @endif
                </td>

                <td>
                    @if ($billing->is_cancelled == 0)
                        -
                    @else
                        {{ $billing->user_cancel->name }}
                    @endif
                </td>
                <td>
                    {{ $billing->remarks }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

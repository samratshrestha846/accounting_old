<table>
    <thead>
        <tr>

            <th></th>
            <th>Suppliers</th>
            <th></th>
            <th></th>
        </tr>

        <tr></tr>
        <tr>

            <th style="text-nowrap">Supplier Name</th>
            <th style="text-nowrap">Bill Amount</th>
            <th style="text-nowrap">Paid Amount</th>
            <th style="text-nowrap">Remaining Amount</th>

        </tr>
    </thead>
    <tbody>
        @forelse ($vendors as $vendor)
            <tr>
                <td>{{ $vendor->company_name }}</td>
                <td>
                    Rs. {{ $vendor->purchaseBillings->sum('grandtotal') - $vendor->purchaseReturnBillings->sum('grandtotal') }}
                </td>
                <td>
                    @php
                        $total_paid_amount = 0;
                        $total_returned_amount = 0;
                        foreach ($vendor->purchaseBillings as $billing)
                        {
                            $paid_amount_sum = $billing->payment_infos->sum('total_paid_amount');
                            $total_paid_amount += $paid_amount_sum;
                        }
                        foreach ($vendor->purchaseReturnBillings as $returnedBilling)
                        {
                            $received_amount_sum = $returnedBilling->payment_infos->sum('total_paid_amount');
                            $total_returned_amount += $received_amount_sum;
                        }
                    @endphp
                    Rs. {{ $total_paid_amount - $total_returned_amount }}
                </td>
                <td>
                    Rs. {{ ($vendor->purchaseBillings->sum('grandtotal') - $total_paid_amount) - ($vendor->purchaseReturnBillings->sum('grandtotal') - $total_returned_amount ) }}
                </td>

            </tr>
        @empty
            <tr><td colspan="5">No any vendors.</td></tr>
        @endforelse
    </tbody>
    <tbody>
    </tbody>
</table>

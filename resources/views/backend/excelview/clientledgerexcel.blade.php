<table>
    <thead>

        <tr>

            <th></th>
            <th>Clients</th>
            <th></th>
            <th></th>
        </tr>
        <tr></tr>
        <tr>

            <th>Customer Name</th>
            <th>Bill Amount</th>
            <th>Paid Amount</th>
            <th>Remaining Amount</th>

        </tr>
    </thead>
    <tbody>
        @forelse ($customers as $customer)
        <tr>
            <td>{{ $customer->name }}</td>
            <td>
                Rs. {{ $customer->salesBillings->sum('grandtotal') - $customer->salesReturnBillings->sum('grandtotal') }}
            </td>
            <td>
                @php
                    $total_paid_amount = 0;
                    $total_received_amount = 0;
                    foreach ($customer->salesBillings as $billing)
                    {
                        $paid_amount_sum = $billing->payment_infos->sum('total_paid_amount');
                        $total_paid_amount += $paid_amount_sum;
                    }
                    foreach ($customer->salesReturnBillings as $returnedBilling)
                    {
                        $received_amount_sum = $returnedBilling->payment_infos->sum('total_paid_amount');
                        $total_received_amount += $received_amount_sum;
                    }
                @endphp
                Rs. {{ $total_paid_amount - $total_received_amount }}
            </td>
            <td>
                Rs. {{ ($customer->salesBillings->sum('grandtotal') - $total_paid_amount) - ($customer->salesReturnBillings->sum('grandtotal') - $total_received_amount) }}
            </td>

        </tr>
    @empty
        <tr><td colspan="5">No any customers.</td></tr>
    @endforelse
    </tbody>
    <tbody>
    </tbody>
</table>

<table>
    <thead>
        <tr>

            <th></th>
            <th>Journal Vouchers</th>
            <th></th>
            <th></th>
        </tr>
        <tr>

            <th></th>
            <th>{{ $client->name }}({{ ucfirst($client->client_type) }})</th>
            <th></th>
            <th></th>
        </tr>
        <tr></tr>
        <tr>
            <th><b>Bill Ref. No.</b></th>
            <th><b>Grand Total</b></th>
            <th><b>Total Paid</b></th>
            <th><b>Payment Due</b></th>

        </tr>
    </thead>
    <tbody>
        @forelse ($billings as $billing)
        <tr>
            <td>{{ $billing->reference_no }}</td>
            <td>Rs.{{ $billing->grandtotal }}</td>
            @php
            $paid_amount = [];
            $payments = $billing->payment_infos;
            $paymentcount = count($payments);
            for ($x = 0; $x < $paymentcount; $x++) {
                $payment_amount = round($payments[$x]->payment_amount, 2);
                array_push($paid_amount, $payment_amount);
            }
            $totpaid = array_sum($paid_amount);

            $dueamt = round($billing->grandtotal, 2) - $totpaid;
        @endphp
        <td>RS.{{ $totpaid }}</td>
        <td>RS.{{ $dueamt }}</td>
        </tr>
        @endforeach
    </tbody>
    <tbody>
    </tbody>
</table>

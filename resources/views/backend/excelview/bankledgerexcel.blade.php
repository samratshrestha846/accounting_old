<table>
    <thead>

        <tr>

            <th></th>
            <th>Bank Ledgers</th>
            <th></th>
            <th></th>
        </tr>
        <tr></tr>
        <tr>

            <th>Bank Name</th>
            <th>Account Holder</th>
            <th>Received Amount</th>
            <th>Paid Amount</th>

        </tr>
    </thead>
    <tbody>
        @forelse ($banks as $bank)
        <tr>
            <td>{{ $bank->bank_name }}</td>
            <td>{{ $bank->account_name }}</td>
            <td>
                Rs. {{ $bank->salesBillings->sum('grandtotal') + $bank->purchaseReturnBillings->sum('grandtotal') + $bank->receiptBillings->sum('grandtotal') }}
            </td>
            <td>
                Rs. {{ $bank->purchaseBillings->sum('grandtotal') + $bank->salesReturnBillings->sum('grandtotal') + $bank->paymentBillings->sum('grandtotal') }}
            </td>

        </tr>
    @empty
        <tr><td colspan="5">No any banks.</td></tr>
    @endforelse
    </tbody>
    <tbody>
    </tbody>
</table>

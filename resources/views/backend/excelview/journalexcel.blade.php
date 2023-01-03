<table>
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Journal Vouchers</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>For the fiscal year {{ $fiscal_year }}</th>
            <th></th>
            <th></th>
        </tr>

        <tr></tr>
        <tr>
            <th><b>Journal Voucher No</b></th>
            <th><b>Entry Date</b></th>
            <th><b>Particulars</b></th>
            <th><b>Debit amount</b></th>
            <th><b>Credit amount</b></th>
            <th><b>Debit total</b></th>
            <th><b>Credit total</b></th>
            <th><b>Narration</b></th>
            <th><b>Status</b></th>
            <th><b>Cancellation</b></th>
            <th><b>Entried By</b></th>
            <th><b>Approved by</b></th>
            <th><b>Edited By</b></th>
            <th><b>Cancelled By</b></th>
            <th><b>Entry Date</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($journalvouchers as $voucher)
            <tr>
                <td>{{ $voucher->journal_voucher_no }}</td>
                <td>
                    {{ $voucher->entry_date_english }} A.D <br>
                    {{ $voucher->entry_date_nepali }} B.S
                </td>

                @php
                    $journal_extras = DB::table('journal_extras')->where('journal_voucher_id', $voucher->id)->get();
                @endphp
                <td>
                    @php
                        $particulars = '';
                        foreach ($journal_extras as $extra) {
                            $child_account = DB::table('child_accounts')->where('id', $extra->child_account_id)->first();
                            $particulars = $particulars . $child_account->title. '<br>' ;
                        }
                        echo $particulars;
                    @endphp
                </td>
                <td>
                    @php
                        $debit_amounts = '';
                        foreach ($journal_extras as $extra) {
                            if ($extra->debitAmount == 0) {
                                $debit_amounts = $debit_amounts . '-'.'<br>' ;
                            } else {
                                $debit_amounts = $debit_amounts .  'Rs. ' . $extra->debitAmount.'<br>' ;
                            }
                        }
                        echo $debit_amounts;
                    @endphp
                </td>

                <td>
                    @php
                        $credit_amounts = '';
                        foreach ($journal_extras as $extra) {
                            if ($extra->creditAmount == 0) {
                                $credit_amounts = $credit_amounts . '-'.'<br>' ;
                            } else {
                                $credit_amounts = $credit_amounts .  'Rs. ' . $extra->creditAmount.'<br>' ;
                            }
                        }
                        echo $credit_amounts;
                    @endphp
                </td>

                <td>Rs. {{ $voucher->debitTotal }}</td>
                <td>Rs. {{ $voucher->creditTotal }}</td>
                <td>{{ $voucher->narration }}</td>

                <td>
                    @php
                        if($voucher->status == '1'){
                            $status = "Approved";
                        }else{
                            $status = "Awating for Approval";
                        }
                        echo $status;
                    @endphp
                </td>
                <td>
                    @php
                        if($voucher->is_cancelled == '1'){
                            $cancellation = "Cancelled";
                        }else{
                            $cancellation = "Running";
                        }
                        echo $cancellation;
                    @endphp
                </td>

                <td>
                    @php
                        if($voucher->entry_by == null)
                        {
                            echo "-";
                        }else {
                            $user = DB::table('users')->where('id', $voucher->entry_by)->first();
                            echo $user->name;
                        }
                    @endphp
                </td>

                <td>
                    @php
                        if($voucher->approved_by == null)
                        {
                            echo "-";
                        }else {
                            $user = DB::table('users')->where('id', $voucher->approved_by)->first();
                            echo $user->name;
                        }
                    @endphp
                </td><td>
                    @php
                        if($voucher->edited_by == null)
                        {
                            echo "-";
                        }else {
                            $user = DB::table('users')->where('id', $voucher->edited_by)->first();
                            echo $user->name;
                        }
                    @endphp
                </td><td>
                    @php
                        if($voucher->cancelled_by == null)
                        {
                            echo "-";
                        }else {
                            $user = DB::table('users')->where('id', $voucher->cancelled_by)->first();
                            echo $user->name;
                        }
                    @endphp
                </td>

                <td>
                    {{ date('d-m-Y', strtotime($voucher->created_at)) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

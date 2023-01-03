@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
            <div class="container-fluid">
                <div class="sec-header">
                    <div class="sec-header-wrap">
                        <h1>Journal Voucher no: {{ $journalVoucher->journal_voucher_no }}</h1>
                        <div class="btn-bulk">
                            <a href="{{ route('journals.index') }}" class="btn btn-primary">Exhibit Vouchers</a>
                        </div>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6" style="line-height: 28px;">
                                            <b>Date:</b> {{ $journalVoucher->entry_date_nepali }} / {{ $journalVoucher->entry_date_english }} <br>
                                            <b>Entried by:</b> {{ $journalVoucher->user_entry->name }}<br>
                                            <b>Edited by:</b> {{ $journalVoucher->edited_by == null ? 'Not edited' : $journalVoucher->user_edit->name }} <br>
                                            <b>Cancelled by:</b> {{ $journalVoucher->cancelled_by == null ? 'Not cancelled' : $journalVoucher->user_cancel->name }} <br>
                                            <b>Approved by:</b> {{ $journalVoucher->approved_by == null ? 'Not approved' : $journalVoucher->user_approved->name }} <br>
                                            @if ($journalVoucher->cancelled_by != null)
                                                @php
                                                    $cancelled_journal = App\Models\CancelledVoucher::where('journalvoucher_id', $journalVoucher->id)->first();
                                                @endphp
                                                <b>Reason for Cancellation:</b> {{ $cancelled_journal->reason }} <br>
                                            @endif
                                        </div>
                                        <div class="col-md-6 text-right" style="line-height: 28px;">
                                            @if (!$journalVoucher->client_id == null)
                                                <b>Customer: {{$journalVoucher->client->name}}</b><br>
                                            @endif
                                            @if (!$journalVoucher->vendor_id == null)
                                                <b>Supplier:</b> {{$journalVoucher->supplier->company_name}}<br>
                                            @endif

                                            <b>{{ $journalVoucher->receipt_payment == 0 ? 'Receipt Details' : 'Payment Details'}}:</b>
                                            @if ($journalVoucher->payment_method == 2)
                                                Cheque ({{ $journalVoucher->bank == null ? '' : $journalVoucher->bank->bank_name }} / Cheque no.: {{ $journalVoucher->cheque_no }})
                                            @elseif($journalVoucher->payment_method == 3)
                                                Bank Deposit ({{ $journalVoucher->bank == null ? '' : $journalVoucher->bank->bank_name }})
                                            @elseif($journalVoucher->payment_method == 4)
                                                Online Portal ({{ $journalVoucher->online_portal->name }} / Portal Id: {{ $journalVoucher->customer_portal_id }})
                                            @else
                                                Cash
                                            @endif
                                        </div>

                                        <div class="col-md-12 table-responsive mt-3">
                                            <table class="table table-bordered text-center">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Particulars</th>
                                                        <th>Remarks</th>
                                                        <th style="width: 100px;">LF no.</th>
                                                        <th>Debit Amount</th>
                                                        <th>Credit Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="line-height: 20px;">
                                                            @php
                                                                $particulars = '';
                                                                foreach ($journal_extras as $extra) {
                                                                    $child_account = \App\Models\ChildAccount::where('id', $extra->child_account_id)->first();
                                                                    if ($extra->debitAmount == 0){
                                                                        $to = '&nbsp&nbsp&nbsp&nbsp&nbsp&nbspTo ';
                                                                    }else{
                                                                        $to = '';
                                                                    }
                                                                    $childac = $child_account->title ?? '';
                                                                    $particulars = $particulars . $to.$childac . '<br>' ;
                                                                }
                                                                echo $particulars;
                                                            @endphp
                                                        </td>

                                                        <td>
                                                            @php
                                                                $remarks = '';
                                                                foreach ($journal_extras as $extra) {
                                                                    if ($extra->remarks == null) {
                                                                        $remarks = $remarks . '-'.'<br>' ;
                                                                    } else {
                                                                        $remarks = $remarks . $extra->remarks.'<br>' ;
                                                                    }
                                                                }
                                                                echo $remarks;
                                                            @endphp
                                                        </td>
                                                        <td></td>
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
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"><b>Total</b></td>
                                                        <td>Rs. {{ $journalVoucher->debitTotal }}</td>
                                                        <td>Rs. {{ $journalVoucher->creditTotal }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-md-2 mt-1">
                                            <b style="font-size: 15px;">Narration:</b>
                                        </div>
                                        <div class="col-md-10 mt-1" style="font-size: 15px;">
                                            ( {{ $journalVoucher->narration }} )
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label for="" style="font-size: 15px;">Attached documents:</label>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            @php
                                                $journal_images = DB::table('journal_images')->where('journalvoucher_id', $journalVoucher->id)->get();
                                            @endphp

                                            @if (count($journal_images) == 0)
                                                <p>No attached documents.</p>
                                            @else
                                                @foreach ($journal_images as $images)
                                                    <a href="{{ Storage::disk('uploads')->url($images->location) }}" target="_blank">
                                                        <img src="{{ Storage::disk('uploads')->url($images->location) }}" alt="{{$journalVoucher->id}}" style="height: 250px;">
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="col-md-12 mt-1">
                                            <div class="btn-bulk">
                                                <a href="{{ route('journals.edit', $journalVoucher->id) }}" class="btn btn-primary">Edit</a>
                                                <a href="{{ route('pdf.generateJournal', $journalVoucher->id) }}" class="btn btn-secondary">Export(as PDF)</a>
                                                <a href="{{ route('journals.print', $journalVoucher->id) }}" class="btn btn-primary btnprn">Print</a>
                                            </div>
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
    <script type="text/javascript">
        $(document).ready(function(){
            $('.btnprn').printPage();
        });
    </script>
@endpush

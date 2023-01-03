@extends('backend.layouts.app')

@section('content')

    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content p-5">
            <div class="container-fluid">
                <div class="ibox">
                    <div class="card-body">
                        <div style="float: left;">
                            <img src="{{ Storage::disk('uploads')->url($currentcomp->company->company_logo) }}" alt="{{ $currentcomp->company->name }}" style="height: 120px;">
                        </div>
                        <div class="">
                            <h1 class="m-0 text-center mb-2" style="color: #583949;">{{ $currentcomp->company->name }} </h1>
                            <p class="text-center mb-1">{{ $currentcomp->company->local_address }}, {{ $currentcomp->company->provinces->eng_name }}</p>
                            <p class="text-center mb-1">{{$currentcomp->company->email}}</p>
                            <p class="text-center mt-0">{{$currentcomp->company->phone}}</p>
                        </div>
                    </div>
                    <div class="card" style="margin-top:30px">
                        <div class="card-body">
                            <table width="100%">
                                <tbody>
                                    <tr>
                                        <td style="width:50%;vertical-align:top;">
                                            <table>
                                                <tbody>
                                                    @if (!$journalVoucher->vendor_id == null)
                                                        <tr>
                                                            <td><b>Supplier:</b></td>
                                                            <td>{{$journalVoucher->supplier->company_name}}</td>
                                                        </tr>
                                                    @endif
                                                    @if (!$journalVoucher->client_id == null)
                                                        <tr>
                                                            <td><b>Customer:</b></td>
                                                            <td>{{$journalVoucher->client->name}}</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style="width:50%;vertical-align:top;">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td><b>Date:</b></td>
                                                        <td>{{ $journalVoucher->entry_date_nepali }} / {{ $journalVoucher->entry_date_english }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Journal Voucher no.:</b></td>
                                                        <td>{{ $journalVoucher->journal_voucher_no }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div style="width: 100%;">
                                {{-- <div style="float: right;">
                                    <b>Date:</b> {{ $journalVoucher->entry_date_nepali }} / {{ $journalVoucher->entry_date_english }}
                                    <b>Journal Voucher no.:</b> {{ $journalVoucher->journal_voucher_no }}<br><br>
                                    @if (!$journalVoucher->vendor_id == null)
                                        <b>Supplier:</b> {{$journalVoucher->supplier->company_name}}<br><br>
                                    @endif
                                </div>
                                <div style="float: left;">
                                    @if (!$journalVoucher->client_id == null)
                                        <b>Customer:</b> {{$journalVoucher->client->name}}<br><br>
                                    @endif
                                </div>
                                <br><br> --}}
                                <table class="table table-bordered text-center" style="margin-top:15px;">
                                    <thead>
                                        <tr>
                                            <th>Particulars</th>
                                            <th style="width: 40px;">LF no.</th>
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
                                                        $particulars = $particulars . $to.$child_account->title. '<br>' ;
                                                    }
                                                    echo $particulars;
                                                @endphp
                                            </td>
                                            <td style="line-height: 20px;"></td>
                                            <td style="line-height: 20px;">
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

                                            <td style="line-height: 20px;">
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
                                            <td colspan="2"><b>Total</b></td>
                                            <td>Rs. {{ $journalVoucher->debitTotal }}</td>
                                            <td>Rs. {{ $journalVoucher->creditTotal }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="narration" style="display: flex;margin-top:20px;align-items:center;margin-bottom:10px;">
                                    <b style="font-size: 20px;">Narration:</b> ( {{ $journalVoucher->narration }} )
                                </div>
                                <hr>
                                <div style="margin-top:30px;">
                                    <table style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>Prepared By ....................</th>
                                                <th>Submitted By ....................</th>
                                                <th>Approved By ....................</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Designation:</td>
                                                <td>Designation:</td>
                                                <td>Designation:</td>
                                            </tr>
                                            <tr>
                                                <td>Date:</td>
                                                <td>Date:</td>
                                                <td>Date:</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection

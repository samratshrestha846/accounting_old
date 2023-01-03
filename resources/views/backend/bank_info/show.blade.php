@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>{{ $bank->bank_name }} </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('bank.index') }}" class="global-btn">View All Banks</a>
                        <a href="{{ route('bank.create') }}" class="global-btn">Create New Bank</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h2>{{ $bank->bank_name }} ({{ ucfirst($bank->head_branch) }})</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 text-center">

                                        <p>
                                            <b>Account Holder:</b>
                                            {{ $bank->account_name }}
                                        </p>
                                        <p>
                                            <b>Account No.:</b>
                                            {{ $bank->account_no }}
                                        </p>
                                        <p>
                                            <b>Account Type:</b>
                                            {{ $bank->account_type->account_type_name }}
                                        </p>
                                        <p>
                                            <b>Address:</b>
                                            {{ $bank->bank_local_address }}, {{ $bank->district->dist_name }}, {{ $bank->province->eng_name }}.
                                        </p>

                                        <a href="{{ route('bank.edit', $bank->id) }}" class="btn btn-secondary">Edit Bank</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <ul class="nav nav-tabs responsive-tabs" id="myTab" role="tablist" style="border : 1px solid #e1e6eb; border-bottom : none;">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-3" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">Sales</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-4" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false">Credit Notes</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-5" data-toggle="tab" href="#tab5" role="tab" aria-controls="tab5" aria-selected="false">Purchase</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-6" data-toggle="tab" href="#tab6" role="tab" aria-controls="tab6" aria-selected="false">Debit Notes</a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-7" data-toggle="tab" href="#tab7" role="tab" aria-controls="tab7" aria-selected="false">Receipts</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-8" data-toggle="tab" href="#tab8" role="tab" aria-controls="tab8" aria-selected="false">Payments</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab3" role="tabpanel" aria-labelledby="tab-3">
                                @php
                                    $allsales = [];
                                    $totalpaid = [];
                                    foreach ($salesBillings as $salesBilling) {
                                        $gtotal = round($salesBilling->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($salesBilling->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allsales, $gtotal);
                                    }
                                    $totalsales = array_sum($allsales);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalsales - $totalpayment;
                                @endphp

                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Sales: Rs.{{ $totalsales }}</span>
                                    <span class="btn global-btn">Total Paid: Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due: Rs.{{ $totaldue }}</span>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered mt text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-nowrap">Bill Ref. No.</th>
                                                <th class="text-nowrap">Grand Total</th>
                                                <th class="text-nowrap">Total Paid</th>
                                                <th class="text-nowrap">Payment Due</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($salesBillings as $salesBilling)
                                                <tr>
                                                    <td scope="row">{{ $salesBilling->reference_no }}</td>
                                                    <td>Rs.{{ $salesBilling->grandtotal }}</td>
                                                    @php
                                                        $paid_amount = [];
                                                        $payments = $salesBilling->payment_infos;
                                                        $paymentcount = count($payments);
                                                        for ($x = 0; $x < $paymentcount; $x++) {
                                                            $payment_amount = round($payments[$x]->payment_amount, 2);
                                                            array_push($paid_amount, $payment_amount);
                                                        }
                                                        $totpaid = array_sum($paid_amount);

                                                        $dueamt = round($salesBilling->grandtotal, 2) - $totpaid;
                                                    @endphp
                                                    <td>Rs.{{ $totpaid }}</td>
                                                    <td>RS.{{ $dueamt }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4">No sales yet.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            | Showing <strong>{{ $salesBillings->firstItem() }}</strong>
                                            to
                                            <strong>{{ $salesBillings->lastItem() }} </strong> of
                                            <strong>
                                                {{ $salesBillings->total() }}</strong>
                                            entries |
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span
                                            class="pagination-sm m-0 float-right">{{ $salesBillings->links() }}</span>
                                    </div>
                                </div>
                                {{-- <h5 class="btn-bulk">
                                    <a href="{{ route('client.sales', $client->id) }}" class="btn btn-primary">View All Sales</a>
                                </h5> --}}
                            </div>

                            <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab-4">
                                @php
                                    $allsalesreturn = [];
                                    $totalpaid = [];
                                    foreach ($creditNoteBillings as $creditNoteBilling) {
                                        $gtotal = round($creditNoteBilling->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($creditNoteBilling->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allsalesreturn, $gtotal);
                                    }
                                    $totalsalesreturn = array_sum($allsalesreturn);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalsalesreturn - $totalpayment;
                                @endphp
                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Credit Note:
                                    Rs.{{ $totalsalesreturn }}</span>
                                    <span class="btn global-btn">Total
                                    Paid:
                                    Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due:
                                    Rs.{{ $totaldue }}</span>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered mt text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-nowrap">Bill Ref. No.</th>
                                                <th class="text-nowrap">Grand Total</th>
                                                <th class="text-nowrap">Total Paid</th>
                                                <th class="text-nowrap">Payment Due</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($creditNoteBillings as $creditNoteBilling)
                                                <tr>
                                                    <td scope="row">{{ $creditNoteBilling->reference_no }}</td>
                                                    <td>Rs.{{ $creditNoteBilling->grandtotal }}</td>
                                                    @php
                                                        $paid_amount = [];
                                                        $payments = $creditNoteBilling->payment_infos;
                                                        $paymentcount = count($payments);
                                                        for ($x = 0; $x < $paymentcount; $x++) {
                                                            $payment_amount = round($payments[$x]->payment_amount, 2);
                                                            array_push($paid_amount, $payment_amount);
                                                        }
                                                        $totpaid = array_sum($paid_amount);

                                                        $dueamt = round($billing->grandtotal, 2) - $totpaid;
                                                    @endphp
                                                    <td>Rs.{{ $totpaid }}</td>
                                                    <td>RS.{{ $dueamt }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4">No credit notes yet.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            | Showing <strong>{{ $creditNoteBillings->firstItem() }}</strong>
                                            to
                                            <strong>{{ $creditNoteBillings->lastItem() }} </strong> of
                                            <strong>
                                                {{ $creditNoteBillings->total() }}</strong>
                                            entries |
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span
                                            class="pagination-sm m-0 float-right">{{ $creditNoteBillings->links() }}</span>
                                    </div>
                                </div>
                                {{-- <h5 class="btn-bulk">
                                    <a href="{{ route('client.creditnote', $client->id) }}" class="btn btn-primary">View All Credit Notes</a>
                                </h5> --}}
                            </div>

                            <div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab-5">
                                @php
                                    $allpurchase = [];
                                    $totalpaid = [];
                                    foreach ($billings as $billing) {
                                        $gtotal = round($billing->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($billing->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allpurchase, $gtotal);
                                    }
                                    $totalpurchase = array_sum($allpurchase);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalpurchase - $totalpayment;
                                @endphp
                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Purchase: Rs.{{ $totalpurchase }}</span>
                                    <span class="btn global-btn">Total Paid: Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due: Rs.{{ $totaldue }}</span>
                                </h5>
                                <table class="table table-bordered mt text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Bill Ref. No.</th>
                                            <th class="text-nowrap">Grand Total</th>
                                            <th class="text-nowrap">Total Paid</th>
                                            <th class="text-nowrap">Payment Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($billings as $billing)
                                            <tr>
                                                <td scope="row">{{ $billing->reference_no }}</td>
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
                                                <td>Rs.{{ $totpaid }}</td>
                                                <td>RS.{{ $dueamt }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4">No purchase bills.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="col-md-5">
                                        <p class="text-sm">
                                            | Showing <strong>{{ $billings->firstItem() }}</strong> to
                                            <strong>{{ $billings->lastItem() }} </strong> of <strong>
                                                {{ $billings->total() }}</strong>
                                            entries |
                                        </p>
                                    </div>
                                    <div class="col-md-7">
                                        <span
                                            class="pagination-sm m-0 float-right">{{ $billings->links() }}</span>
                                    </div>
                                </div>
                                {{-- <h5 class="btn-bulk">
                                    <a href="{{ route('supplier.purchase', $vendor->id) }}" class="btn btn-primary">View All Purchases</a>
                                </h5> --}}
                            </div>

                            <div class="tab-pane fade" id="tab6" role="tabpanel" aria-labelledby="tab-6">
                                @php
                                    $allpurchasereturn = [];
                                    $totalpaid = [];
                                    foreach ($debitNoteBillings as $debitNotebilling) {
                                        $gtotal = round($debitNotebilling->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($debitNotebilling->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allpurchasereturn, $gtotal);
                                    }
                                    $totalpurchasereturn = array_sum($allpurchasereturn);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalpurchasereturn - $totalpayment;
                                @endphp
                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Debit Note:
                                        Rs.{{ $totalpurchasereturn }}</span>
                                    <span class="btn global-btn">Total Paid: Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due: Rs.{{ $totaldue }}</span>
                                </h5>
                                <table class="table table-bordered mt text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Bill Ref. No.</th>
                                            <th class="text-nowrap">Grand Total</th>
                                            <th class="text-nowrap">Total Paid</th>
                                            <th class="text-nowrap">Payment Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($debitNoteBillings as $debitNoteBilling)
                                            <tr>
                                                <td scope="row">{{ $debitNoteBilling->reference_no }}</td>
                                                <td>Rs.{{ $debitNoteBilling->grandtotal }}</td>
                                                @php
                                                    $paid_amount = [];
                                                    $payments = $debitNoteBilling->payment_infos;
                                                    $paymentcount = count($payments);
                                                    for ($x = 0; $x < $paymentcount; $x++) {
                                                        $payment_amount = round($payments[$x]->payment_amount, 2);
                                                        array_push($paid_amount, $payment_amount);
                                                    }
                                                    $totpaid = array_sum($paid_amount);

                                                    $dueamt = round($debitNoteBilling->grandtotal, 2) - $totpaid;
                                                @endphp
                                                <td>Rs.{{ $totpaid }}</td>
                                                <td>RS.{{ $dueamt }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4">No debit notes yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="col-md-5">
                                        <p class="text-sm">
                                            | Showing <strong>{{ $debitNoteBillings->firstItem() }}</strong> to
                                            <strong>{{ $debitNoteBillings->lastItem() }} </strong> of <strong>
                                                {{ $debitNoteBillings->total() }}</strong>
                                            entries |
                                        </p>
                                    </div>
                                    <div class="col-md-7">
                                        <span
                                            class="pagination-sm m-0 float-right">{{ $debitNoteBillings->links() }}</span>
                                    </div>
                                </div>
                                {{-- <h5 class="btn-bulk">
                                    <a href="{{ route('supplier.debitnote', $vendor->id) }}" class="btn btn-primary">View All Debit Notes</a>
                                </h5> --}}
                            </div>

                            <div class="tab-pane fade" id="tab7" role="tabpanel" aria-labelledby="tab-7">
                                @php
                                    $allpurchase = [];
                                    $totalpaid = [];
                                    foreach ($receiptBillings as $receiptBilling) {
                                        $gtotal = round($receiptBilling->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($receiptBilling->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allpurchase, $gtotal);
                                    }
                                    $totalpurchase = array_sum($allpurchase);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalpurchase - $totalpayment;
                                @endphp
                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Purchase: Rs.{{ $totalpurchase }}</span>
                                    <span class="btn global-btn">Total Paid: Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due: Rs.{{ $totaldue }}</span>
                                </h5>
                                <table class="table table-bordered mt text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Bill Ref. No.</th>
                                            <th class="text-nowrap">Grand Total</th>
                                            <th class="text-nowrap">Total Paid</th>
                                            <th class="text-nowrap">Payment Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($receiptBillings as $receiptBilling)
                                            <tr>
                                                <td scope="row">{{ $receiptBilling->reference_no }}</td>
                                                <td>Rs.{{ $receiptBilling->grandtotal }}</td>
                                                @php
                                                    $paid_amount = [];
                                                    $payments = $receiptBilling->payment_infos;
                                                    $paymentcount = count($payments);
                                                    for ($x = 0; $x < $paymentcount; $x++) {
                                                        $payment_amount = round($payments[$x]->payment_amount, 2);
                                                        array_push($paid_amount, $payment_amount);
                                                    }
                                                    $totpaid = array_sum($paid_amount);

                                                    $dueamt = round($receiptBilling->grandtotal, 2) - $totpaid;
                                                @endphp
                                                <td>Rs.{{ $totpaid }}</td>
                                                <td>RS.{{ $dueamt }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4">No receipts bills yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="col-md-5">
                                        <p class="text-sm">
                                            | Showing <strong>{{ $receiptBillings->firstItem() }}</strong> to
                                            <strong>{{ $receiptBillings->lastItem() }} </strong> of <strong>
                                                {{ $receiptBillings->total() }}</strong>
                                            entries |
                                        </p>
                                    </div>
                                    <div class="col-md-7">
                                        <span
                                            class="pagination-sm m-0 float-right">{{ $receiptBillings->links() }}</span>
                                    </div>
                                </div>
                                {{-- <h5 class="btn-bulk">
                                    <a href="{{ route('supplier.purchase', $vendor->id) }}" class="btn btn-primary">View All Purchases</a>
                                </h5> --}}
                            </div>

                            <div class="tab-pane fade" id="tab8" role="tabpanel" aria-labelledby="tab-8">
                                @php
                                    $allpurchasereturn = [];
                                    $totalpaid = [];
                                    foreach ($paymentBillings as $paymentbilling) {
                                        $gtotal = round($paymentbilling->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($paymentbilling->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allpurchasereturn, $gtotal);
                                    }
                                    $totalpurchasereturn = array_sum($allpurchasereturn);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalpurchasereturn - $totalpayment;
                                @endphp
                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Debit Note:
                                        Rs.{{ $totalpurchasereturn }}</span>
                                    <span class="btn global-btn">Total Paid: Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due: Rs.{{ $totaldue }}</span>
                                </h5>
                                <table class="table table-bordered mt text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Bill Ref. No.</th>
                                            <th class="text-nowrap">Grand Total</th>
                                            <th class="text-nowrap">Total Paid</th>
                                            <th class="text-nowrap">Payment Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($paymentBillings as $paymentBilling)
                                            <tr>
                                                <td scope="row">{{ $paymentBilling->reference_no }}</td>
                                                <td>Rs.{{ $paymentBilling->grandtotal }}</td>
                                                @php
                                                    $paid_amount = [];
                                                    $payments = $paymentBilling->payment_infos;
                                                    $paymentcount = count($payments);
                                                    for ($x = 0; $x < $paymentcount; $x++) {
                                                        $payment_amount = round($payments[$x]->payment_amount, 2);
                                                        array_push($paid_amount, $payment_amount);
                                                    }
                                                    $totpaid = array_sum($paid_amount);

                                                    $dueamt = round($paymentBilling->grandtotal, 2) - $totpaid;
                                                @endphp
                                                <td>Rs.{{ $totpaid }}</td>
                                                <td>RS.{{ $dueamt }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4">No payment bills yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="col-md-5">
                                        <p class="text-sm">
                                            | Showing <strong>{{ $paymentBillings->firstItem() }}</strong> to
                                            <strong>{{ $paymentBillings->lastItem() }} </strong> of <strong>
                                                {{ $paymentBillings->total() }}</strong>
                                            entries |
                                        </p>
                                    </div>
                                    <div class="col-md-7">
                                        <span
                                            class="pagination-sm m-0 float-right">{{ $paymentBillings->links() }}</span>
                                    </div>
                                </div>
                                {{-- <h5 class="btn-bulk">
                                    <a href="{{ route('supplier.debitnote', $vendor->id) }}" class="btn btn-primary">View All Debit Notes</a>
                                </h5> --}}
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

@endpush

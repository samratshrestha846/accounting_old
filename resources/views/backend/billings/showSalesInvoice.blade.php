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
                    <h1>Sales Invoice => {{ $billing->reference_no }} </h1>
                    @if ($billing->billing_type_id == 1 || $billing->billing_type_id == 2 || $billing->billing_type_id == 5 || $billing->billing_type_id == 6)
                        <div class="modal fade" id="payment_details" tabindex="-1" role="dialog"
                            aria-labelledby="payment_detailsLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="payment_detailsLabel">
                                            {{ $billing->reference_no }}</h5>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $paid_amount = [];
                                            $payments = $billing->payment_infos;
                                            // dd($payments);
                                            $paymentcount = count($payments);
                                            for ($x = 0; $x < $paymentcount; $x++) {
                                                $payment_amount = round($payments[$x]->payment_amount, 2);
                                                array_push($paid_amount, $payment_amount);
                                            }
                                            $totpaid = array_sum($paid_amount);

                                            $dueamt = round($billing->grandtotal, 2) - $totpaid;
                                        @endphp
                                        <p><span class="badge badge-primary mr-2 p-1"><b>Total Amount:
                                                </b>Rs.{{ $billing->grandtotal }}</span> <span
                                                class="badge badge-success mr-2 p-1"><b>Paid Amount:
                                                </b>Rs.{{ $totpaid }}</span><span
                                                class="badge badge-danger dueamount mr-2 p-1"
                                                data-dueamount="{{ $dueamt }}"><b>Due Amount:
                                                </b>Rs.{{ $dueamt }}</span></p>
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="details-tab" data-toggle="tab"
                                                    href="#details" role="tab" aria-controls="details"
                                                    aria-selected="true">Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="create-tab" data-toggle="tab" href="#create"
                                                    role="tab" aria-controls="create" aria-selected="false">Create
                                                    Payment</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="details" role="tabpanel"
                                                aria-labelledby="details-tab">
                                                <div class="container p-3">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th scope="col">Payment Date</th>
                                                                <th scope="col">Payment Type</th>
                                                                <th scope="col">Amount</th>
                                                                <th scope="col">Paid To</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($billing->payment_infos as $pinfo)
                                                                <tr>
                                                                    <th scope="row">{{ $pinfo->payment_date }}</th>
                                                                    <td>
                                                                        @php
                                                                            if ($pinfo->payment_type == 'paid') {
                                                                                echo 'Paid';
                                                                            } elseif ($pinfo->payment_type == 'unpaid') {
                                                                                echo 'Unpaid';
                                                                            } elseif ($pinfo->payment_type == 'partially_paid') {
                                                                                echo 'Partially Paid';
                                                                            }
                                                                        @endphp
                                                                    </td>
                                                                    <td>{{ $pinfo->payment_amount }}</td>
                                                                    <td>{{ $billing->user_entry->name }}</td>
                                                                    <td><a href='{{ route('paymentinfo.edit', $pinfo->id) }}'
                                                                            class='edit btn btn-primary btn-sm mt-1'
                                                                            data-toggle='tooltip' data-placement='top'
                                                                            title='Edit'><i class='fa fa-edit'></i></a>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr><td colspan="5">No data yet.</td></tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="create" role="tabpanel"
                                                aria-labelledby="create-tab">
                                                <div class="container p-3">
                                                    @if ($dueamt <= 0)
                                                        <p class="bold">Fully Paid</p>
                                                    @else
                                                        <form action="{{ route('paymentinfo.store') }}" method="POST">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="billing_id"
                                                                            value="{{ $billing->id }}">
                                                                        <label for="payment_type">Payment Type</label>
                                                                        <select name="payment_type" id="payme€nt_type"
                                                                            class="form-control" required>
                                                                            <option value="paid">Paid</option>
                                                                            <option value="partially_paid">Partially
                                                                                Paid</option>
                                                                            <option value="Unpaid">Unpaid</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="payment_date">Payment Date</label>
                                                                        <input type="date" value="{{ date('Y-m-d') }}"
                                                                            id="payment_date" name="payment_date"
                                                                            class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="payment_amount">Payment
                                                                            Amount</label>
                                                                        <input type="text" name="payment_amount"
                                                                            id="payment_amount" class="form-control"
                                                                            placeholder="Enter Paid Amount" required>
                                                                        <p class="off text-danger">Payment can't be more
                                                                            than that of Due Amount</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="submit"
                                                                class="btn btn-success submit">Submit</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="btn-bulk">
                        <a href="{{ route('salesinvoice', 1) }}" class="global-btn">Back</a>
                        <a href="javascript:void(0)" class="global-btn" data-toggle="modal"
                            data-target="#payment_details">Payment Details</a>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                                <div class="card-header">
                                    <h2>Bill Reference No: {{ $billing->reference_no }} </h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><b>Transaction No: </b>{{ $billing->transaction_no }}</p>
                                            <p><b>Reference No: </b>{{ $billing->reference_no }}</p>
                                            <p><b>Customer: </b>{{ $billing->client->name }}</p>
                                            <p><b>VAT Bill No: </b>{{ $billing->ledger_no }}</p>
                                            <p><b>File No: </b>{{ $billing->file_no }}</p>
                                            <p><b>Payment Mode: </b>
                                                @if ($billing->payment_method == 2)
                                                    Cheque ({{ $billing->bank->bank_name }} / Cheque no.: {{ $billing->cheque_no }})
                                                @elseif($billing->payment_method == 3)
                                                    Bank Deposit ({{ $billing->bank->bank_name }})
                                                @elseif($billing->payment_method == 4)
                                                    Online Portal ({{ $billing->online_portal->name }} / Portal Id: {{ $billing->customer_portal_id }})
                                                @else
                                                    Cash
                                                @endif
                                            </p>

                                            @if ($billing->cancelled_by != null)
                                                @php
                                                    $cancelled_bills = App\Models\CancelledBilling::where('billing_id', $billing->id)->first();
                                                @endphp
                                                <b>Reason for Cancellation:</b> {{ $cancelled_bills->reason }}
                                                <br><br>
                                            @endif
                                        </div>
                                        <div class="col-6">
                                            <p class="text-right"><b>Fiscal-Year:
                                                </b>{{ $billing->fiscal_year->fiscal_year }}</p>
                                            <p class="text-right"><b>English Date: </b>{{ $billing->eng_date }}
                                            </p>
                                            <p class="text-right"><b>Nepali Date: </b>{{ $billing->nep_date }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-nowrap">Particulars</th>
                                                        <th class="text-nowrap">Quantity</th>
                                                        <th class="text-nowrap">Rate</th>
                                                        <th class="text-nowrap">Discount Amount (per unit)</th>
                                                        <th class="text-nowrap">Tax Amount (per unit)</th>
                                                        <th class="text-nowrap">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($billing->billingextras as $billingextra)
                                                        <tr>
                                                            <td>
                                                                {{ $billingextra->product->product_name }}
                                                            </td>
                                                            <td>{{ $billingextra->quantity }}
                                                                {{ $billingextra->unit }}</td>
                                                            <td>Rs. {{ $billingextra->rate }}</td>
                                                            <td>
                                                                @if ($billingextra->discountamt == 0)
                                                                    -
                                                                @else
                                                                    Rs. {{ $billingextra->discountamt }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($billingextra->taxamt == 0)
                                                                    -
                                                                @else
                                                                    Rs. {{ $billingextra->taxamt }}
                                                                @endif
                                                            </td>
                                                            <td>Rs. {{ $billingextra->total }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Sub Total</b></td>
                                                        <td>Rs. {{ $billing->subtotal }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Discount Amount</b></td>
                                                        <td>
                                                            @if ($billing->discountamount == 0)
                                                                -
                                                            @else
                                                                Rs. {{ $billing->discountamount }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Tax
                                                                Amount({{ $billing->taxpercent == null ? '0%' : $billing->taxpercent }})</b>
                                                        </td>

                                                        <td>
                                                            @if ($billing->taxamount == 0)
                                                                -
                                                            @else
                                                                Rs. {{ $billing->taxamount }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Shipping</b></td>
                                                        <td>
                                                            @if ($billing->shipping == 0)
                                                                -
                                                            @else
                                                                Rs. {{ $billing->shipping }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Grand Total</b></td>
                                                        <td>Rs. {{ $billing->grandtotal }}</td>
                                                    </tr>
                                                </tfoot>

                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>Remarks: </b>{{ $billing->remarks }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Payment Type: </b>
                                                @if ($billing->payment_infos[0]->payment_type == 'partially_paid')
                                                    Partially Paid
                                                @elseif($billing->payment_infos[0]->payment_type == "unpaid")
                                                    Unpaid
                                                @elseif($billing->payment_infos[0]->payment_type == "paid")
                                                    Paid
                                                @endif
                                            </p>
                                            <p><b>Paid Amount: </b>Rs.
                                                {{ $billing->payment_infos[0]->total_paid_amount }}
                                            </p>
                                            <p><b>Due date: </b>{{ $billing->payment_infos[0]->due_date }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-right"><b>Entry By:
                                                </b>{{ $billing->user_entry->name }}
                                            </p>
                                            @if (!$billing->edited_by == null)
                                                <p class="text-right"><b>Edited By:
                                                    </b>{{ $billing->user_edit->name }}</p>
                                            @endif
                                            @if ($billing->is_cancelled == 0)
                                                <p class="text-right"><b>Billing Status:
                                                    </b>{{ $billing->status == 1 ? 'Approved' : 'Waiting For Approval' }}
                                                </p>
                                                @if (!$billing->approved_by == null)
                                                    <p class="text-right"><b>Approved By:
                                                        </b>{{ $billing->user_approve->name }}</p>
                                                @endif
                                            @else
                                                <p class="text-right"><b>Billing Status: </b>Cancelled</p>
                                                <p class="text-right"><b>Cancelled By:
                                                    </b>{{ $billing->user_cancel->name }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            <div class="btn-bulk">
                                                <a href="{{ route('salesInvoiceEdit', $billing->id) }}"
                                                    class="btn btn-primary">Edit</a>
                                                <a href="{{ route('billings.print', $billing->id) }}"
                                                        class="btn btn-primary btnprn">Print</a>

                                                <a href="{{ route('pdf.generateSalesInvoice', $billing->id) }}"
                                                    class="btn btn-primary">Export PDF</a>
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
        $(document).ready(function() {
            $('.btnprn').printPage();
        });
    </script>
    <script>
        $('#payment_amount').change(function() {
            var payment_amount = $(this).val();
            var dueamount = $('.dueamount').data('dueamount');
            console.log(dueamount);

            if (parseFloat(payment_amount) > parseFloat(dueamount)) {
                $(this).parent().find('.text-danger').removeClass('off');
                $('.submit').addClass("disabled");
            } else {
                $(this).parent().find('.text-danger').addClass('off');
                $('.submit').removeClass("disabled");
            }
        });
    </script>
@endpush

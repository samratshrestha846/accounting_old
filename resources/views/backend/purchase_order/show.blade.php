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
                    <h1>Purchase Order no. {{ $purchaseOrder->purchase_order_no }} </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('purchaseOrder.index') }}" class="global-btn">View Purchase Orders</a>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
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
                                    <h2>Purchase Order No: {{ $purchaseOrder->purchase_order_no }}</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><b>Purchase Order No: </b>{{ $purchaseOrder->purchase_order_no }}</p>
                                            <p><b>Supplier: </b>{{ $purchaseOrder->suppliers->company_name }}</p>

                                            @if ($purchaseOrder->cancelled_by != null)
                                                @php
                                                    $cancelled_bills = App\Models\CancelledPurchaseOrders::where('purchase_order_id', $purchaseOrder->id)->first();
                                                @endphp
                                                <b>Reason for Cancellation:</b> {{ $cancelled_bills->reason }} <br><br>
                                            @endif
                                        </div>
                                        <div class="col-6">
                                            <p class="text-right"><b>Fiscal-Year:
                                                </b>{{ $purchaseOrder->fiscal_year->fiscal_year }}</p>
                                            <p class="text-right"><b>English Date: </b>{{ $purchaseOrder->eng_date }}
                                            </p>
                                            <p class="text-right"><b>Nepali Date: </b>{{ $purchaseOrder->nep_date }}
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
                                                        <th class="text-nowrap">Unit</th>
                                                        <th class="text-nowrap">Rate</th>
                                                        <th class="text-nowrap">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($purchaseOrder->purchaseOrderExtras as $purchaseOrderExtra)
                                                        <tr>
                                                            <td>{{ $purchaseOrderExtra->particulars }}</td>
                                                            <td>{{ $purchaseOrderExtra->quantity }}</td>
                                                            <td>{{ $purchaseOrderExtra->unit }}</td>
                                                            <td>Rs. {{ $purchaseOrderExtra->rate }}</td>
                                                            <td>Rs. {{ $purchaseOrderExtra->total }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Sub Total</b></td>
                                                        <td>Rs. {{ $purchaseOrder->subtotal }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Tax
                                                                Amount({{ $purchaseOrder->taxpercent == null ? '0%' : $purchaseOrder->taxpercent }})</b>
                                                        </td>
                                                        <td>
                                                            @if ($purchaseOrder->taxamount == 0)
                                                                -
                                                            @else
                                                                Rs. {{ $purchaseOrder->taxamount }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Grand Total</b></td>
                                                        <td>Rs. {{ $purchaseOrder->grandtotal }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>Remarks: </b>{{ $purchaseOrder->remarks }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <p><b>Entry By: </b>{{ $purchaseOrder->user_entry->name }}</p>

                                            @if (!$purchaseOrder->edited_by == null)
                                                <p><b>Edited By:
                                                    </b>{{ $purchaseOrder->user_edit->name }}</p>
                                            @endif

                                            @if ($purchaseOrder->is_cancelled == 0)
                                                <p><b>Status:
                                                    </b>{{ $purchaseOrder->status == 1 ? 'Approved' : 'Waiting For Approval' }}
                                                </p>
                                                @if (!$purchaseOrder->approved_by == null)
                                                    <p><b>Approved By:
                                                        </b>{{ $purchaseOrder->user_approve->name }}</p>
                                                @endif
                                            @else
                                                <p><b>Status: </b>Cancelled</p>
                                                <p><b>Cancelled By:
                                                    </b>{{ $purchaseOrder->user_cancel->name }}</p>
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <div class="btn-bulk">
                                                <a href="{{ route('purchaseOrder.edit', $purchaseOrder->id) }}"
                                                    class="btn btn-primary">Edit</a>
                                                {{-- <a href="{{ route('purchaseOrders.print', $purchaseOrder->id) }}"
                                                class="btn btn-success btnprn">Print</a> --}}
                                                <a href="{{ route('pdf.generatePurchaseOrder', $purchaseOrder->id) }}"
                                                    class="btn btn-secondary">Export PDF</a>

                                                <a href="{{ route('sendPurchaseOrderEmail', $purchaseOrder->id) }}"
                                                    class="btn btn-primary">Send Mail</a>
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
@endpush

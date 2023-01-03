@extends('customerbackend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>View Purchase Details</h1>
                    <div class="bulk-btn">
                        <a href="{{ route('client.purchases') }}" class="global-btn">All Purchase Details</a>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="col-sm-12">
                        <div class="alert  alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="col-sm-12">
                        <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
                @php
                    $paid_amount = [];
                    $payments = DB::table('payment_infos')->where('billing_id', $purchase->id)->get();
                    // dd($payments);
                    $paymentcount = count($payments);
                    for ($x = 0; $x < $paymentcount; $x++) {
                        $payment_amount = round($payments[$x]->payment_amount, 2);
                        array_push($paid_amount, $payment_amount);
                    }
                    $totpaid = array_sum($paid_amount);

                    $dueamt = round($purchase->grandtotal, 2) - $totpaid;
                @endphp
                <p><span class="badge badge-primary mr-2 p-1"><b>Total Amount:
                        </b>Rs.{{ $purchase->grandtotal }}</span> <span
                        class="badge badge-success mr-2 p-1"><b>Paid Amount:
                        </b>Rs.{{ $totpaid }}</span><span
                        class="badge badge-danger dueamount mr-2 p-1"
                        data-dueamount="{{ $dueamt }}"><b>Due Amount:
                        </b>Rs.{{ $dueamt }}</span></p>

                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <h2>Purchase Bill No: {{$purchase->reference_no}}</h2>
                            </div>
                            <div class="card-body">
                                <p><b>Date: </b>{{ $purchase->nep_date }} (in B.S) <br>
                                    {{ $purchase->eng_date }} (in A.D)</p>
                                <p><b>Remarks: </b>{{$purchase->remarks}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header">
                                <h2>Lists of Items</h2>
                            </div>
                            <div class="card-body">
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
                                        @foreach ($billingextras as $billingextra)
                                        @php
                                            $product = DB::table('products')->where('id', $billingextra->particulars)->first();
                                        @endphp
                                            <tr>
                                                <td>
                                                    {{ $product->product_name }}
                                                </td>
                                                <td>{{ $billingextra->quantity }}
                                                    {{ $product->primary_unit }}</td>
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
                                            <td>Rs. {{ $purchase->subtotal }}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Discount Amount</b></td>
                                            <td>
                                                @if ($purchase->discountamount == 0)
                                                    -
                                                @else
                                                    Rs. {{ $purchase->discountamount }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Tax
                                                    Amount({{ $purchase->taxpercent == null ? '0%' : $purchase->taxpercent }})</b>
                                            </td>

                                            <td>
                                                @if ($purchase->taxamount == 0)
                                                    -
                                                @else
                                                    Rs. {{ $purchase->taxamount }}
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
                                                @if ($purchase->shipping == 0)
                                                    -
                                                @else
                                                    Rs. {{ $purchase->shipping }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Grand Total</b></td>
                                            <td>Rs. {{ $purchase->grandtotal }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
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

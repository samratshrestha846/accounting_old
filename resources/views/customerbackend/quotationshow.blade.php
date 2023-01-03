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
                    <h1>View quotation Details</h1>
                    <div class="bulk-btn">
                        <a href="{{ route('client.quotations') }}" class="global-btn">All quotation Details</a>
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
                <p><span class="badge badge-primary mr-2 p-1"><b>Total Amount:
                        </b>Rs.{{ $quotation->grandtotal }}</span></p>

                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <h2>Quotation Bill No: {{$quotation->reference_no}}</h2>
                            </div>
                            <div class="card-body">
                                <p><b>Date: </b>{{ $quotation->nep_date }} (in B.S) <br>
                                    {{ $quotation->eng_date }} (in A.D)</p>
                                <p><b>Remarks: </b>{{$quotation->remarks}}</p>
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
                                            <td>Rs. {{ $quotation->subtotal }}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Discount Amount</b></td>
                                            <td>
                                                @if ($quotation->discountamount == 0)
                                                    -
                                                @else
                                                    Rs. {{ $quotation->discountamount }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Tax
                                                    Amount({{ $quotation->taxpercent == null ? '0%' : $quotation->taxpercent }})</b>
                                            </td>

                                            <td>
                                                @if ($quotation->taxamount == 0)
                                                    -
                                                @else
                                                    Rs. {{ $quotation->taxamount }}
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
                                                @if ($quotation->shipping == 0)
                                                    -
                                                @else
                                                    Rs. {{ $quotation->shipping }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Grand Total</b></td>
                                            <td>Rs. {{ $quotation->grandtotal }}</td>
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
@endpush

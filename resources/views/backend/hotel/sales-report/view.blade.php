@extends('backend.layouts.app')
@push('styles')
    <style>
        span.text-right {
            float: right;
        }

    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Hotel Sales Report : </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-sales-report') }}" class="global-btn">Back</a>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#payment_details"
                            class="global-btn">Payment Details</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
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
                                <div class="card-header">
                                    <h2>
                                        Bill Reference No: {{ $hotelSale->billing->reference_no }}
                                        <span class="text-right">
                                            <a href="{{ route('hotel-order.show', $hotelSale->id) }}"> Order No:
                                                {{ $hotelSale->id }}
                                            </a>
                                        </span>
                                    </h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><b>Transaction No: </b>{{ $hotelSale->billing->transaction_no }}</p>
                                            <p><b>Customer Name: </b>{{ $hotelSale->customer->name }}</p>
                                            <p><b>Floor : </b>{{ $hotelSale->table->floor->name }}</p>
                                            <p><b>Room : </b>{{ $hotelSale->table->room->name }}</p>
                                            <p><b>Table : </b>{{ $hotelSale->table->name }}</p>
                                        </div>
                                        <div class="col-6">
                                            {{-- <p class="text-right"><b>Reference No: </b>SB-00000001</p> --}}
                                            <p class="text-right"><b>Fiscal-Year:
                                                </b>{{ $current_year }}</p>
                                            <p class="text-right"><b>English Date:
                                                </b>{{ date('Y m d', strtotime($hotelSale->created_at)) }}</p>
                                            <p class="text-right"><b>Nepali Date: </b>{{ $nepali_today }}</p>
                                            <p class="text-right"><b>Payment Mode: </b> Cash </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-nowrap"></th>
                                                        <th class="text-nowrap">Items</th>
                                                        <th class="text-nowrap">Quantity</th>
                                                        <th class="text-nowrap">Rate</th>
                                                        <th class="text-nowrap">Discount</th>
                                                        <th class="text-nowrap">Tax</th>
                                                        <th class="text-nowrap">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($hotelSale->order_items as $item)
                                                        <tr>
                                                            <td>
                                                                <img src="{{ Storage::disk('uploads')->url($item->food->food_image) }}"
                                                                    alt="{{ $item->food->food_image }}" width="60">
                                                            </td>
                                                            <td>
                                                                {{ $item->food_name }}
                                                            </td>
                                                            <td>
                                                                {{ $item->quantity }}
                                                            </td>
                                                            <td>Rs. {{ $item->unit_price }}</td>
                                                            <td class="text-nowrap">
                                                                {{ $item->discount_value ? $item->discount_value : '-' }}
                                                            </td>
                                                            <td class="text-nowrap">
                                                                {{ $item->tax_type ? $item->tax_value : '-' }}
                                                            </td>
                                                            <td>Rs. {{ $item->sub_total }}</td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Sub Total</b></td>
                                                        <td>Rs. {{ $hotelSale->sub_total }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Discount Amount</b></td>
                                                        <td>
                                                            {{ $hotelSale->total_discount ? $hotelSale->total_discount : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Tax ({{ $hotelSale->tax_value ? $hotelSale->tax_value : 0 }} %)</b></td>
                                                        <td>
                                                            {{ $hotelSale->total_tax ? $hotelSale->total_tax : '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Service Charge</b></td>
                                                        <td>
                                                            {{ $hotelSale->service_charge ? $hotelSale->service_charge : '0' }}
                                                        </td>
                                                    </tr>
                                                    @if ($hotelSale->is_cabin)
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><b>Service Charge</b></td>
                                                            <td>
                                                                {{ $hotelSale->cabin_charge ? $hotelSale->cabin_charge : '0' }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Grand Total</b></td>
                                                        <td>Rs. {{ $hotelSale->total_cost }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>Description: </b>{{ $hotelSale->description }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <p><b>Entry By: </b>{{ $hotelSale->createdBy->name }}</p>
                                        </div>
                                        <div class="col-4"></div>
                                        <div class="col-4">
                                            <p class="text-right"><b> Status:
                                                </b>
                                                @switch($hotelSale->status)
                                                    @case(1)
                                                        <badge class="badge badge-primary">Pending</badge>
                                                    @break
                                                    @case(2)
                                                        <badge class="badge badge-info">Ready</badge>
                                                    @break
                                                    @case(3)
                                                        <badge class="badge badge-info">Served</badge>
                                                    @break
                                                    @case(0)
                                                        <badge class="badge badge-danger">Cancelled</badge>
                                                    @break
                                                    @default
                                                        <badge class="badge badge-primary">Pending</badge>
                                                @endswitch
                                            </p>
                                            {{-- <p class="text-right"><b>Approved By:
                                                </b>Nectar Digit</p> --}}
                                        </div>
                                        <div class="col-md-12">
                                            <div class="btn-bulk">
                                                <a href="{{ route('hotel_order.pos.generateinvoicebill', $hotelSale->billing_id) }}"
                                                    class="btn btn-primary btnprn">Print</a>
                                                <a href="{{ route('hotel_order.pos.generateinvoicebill.pdf', $hotelSale->billing_id) }}"
                                                    class="btn btn-primary exportprn">Export PDF</a>
                                                <a href="{{ route('sendEmail', $hotelSale->id) }}"
                                                    class="btn btn-primary ">Send Mail</a>
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

        @if($hotelSale->billing)
            <!-- payment detail model -->
            <div class="modal fade" id="payment_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Payment Detail</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @php
                                $grandTotal = $hotelSale->billing->grandtotal;
                                $totalpaid = collect($hotelSale->billing->payment_infos)->sum('total_paid_amount');
                                $dueamt = round($grandTotal, 2) - $totalpaid;
                            @endphp
                            <p>
                                <span class="badge badge-primary mr-2 p-1"><b>Total Amount:</b>Rs. {{$grandTotal}}</span>
                                <span class="badge badge-success mr-2 p-1"><b>Paid Amount:</b>Rs. {{$totalpaid}}</span>
                                <span data-dueamount="0" class="badge badge-danger dueamount mr-2 p-1"><b>Due Amount:</b>Rs.{{$dueamt}}</span>
                            </p>
                            <ul id="myTab" role="tablist" class="nav nav-tabs">
                                <li class="nav-item">
                                    <a id="details-tab" data-toggle="tab" href="#details" role="tab" class="nav-link active">Details</a>
                                </li>
                                <li class="nav-item">
                                    <a id="create-tab" data-toggle="tab" href="#create" role="tab" class="nav-link">CreatePayment</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div id="details" role="tabpanel" class="tab-pane fade active show">
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
                                                @forelse ($hotelSale->billing->payment_infos as $pinfo)
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
                                                        <td>{{ $pinfo->total_paid_amount }}</td>
                                                        <td>{{ $hotelSale->billing->user_entry->name }}</td>
                                                        <td><a href='{{ route('paymentinfo.edit', $pinfo->id) }}'
                                                                class='edit btn btn-primary btn-sm mt-1'
                                                                data-toggle='tooltip' data-placement='top'
                                                                title='Edit'><i class='fa fa-edit'></i></a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="5">No any data.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="create" role="tabpanel" aria-labelledby="create-tab" class="tab-pane fade">
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
                                                                value="{{ $hotelSale->billing->id }}">
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


    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.btnprn').printPage();
        });
        $(document).ready(function() {
            return $('.exportprn').printPage();
        });
    </script>
@endpush

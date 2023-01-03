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
                    <h1>Order Item </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-order.index') }}" class="global-btn">Back</a>
                        @if(!$orderItem->billing)
                        <button type="button" class="global-btn" @click="$refs.makePaymentModal.openModal()">Make Payment</button>
                        @else
                        <button type="button" class="global-btn" data-toggle="modal" data-target="#payment_details">Payment Detail</button>
                        @endif
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @if (session('success'))
                                <div class="alert  alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <h2>Order Id: {{$orderItem->id}}</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-left">
                                            <b>Customer:</b>
                                            {{$orderItem->customer ? $orderItem->customer->name : '-'}}
                                        </p>
                                        <p class="text-left">
                                            <b>Waiter:</b>
                                            {{$orderItem->waiter ? $orderItem->waiter->name : '-'}}
                                        </p>
                                        <p class="text-left">
                                            <b>Table:</b>
                                            @forelse ($orderItem->tables as $table)
                                            <span class="d-block">
                                            {{ $table->floor ? $table->floor->name : '-' }} /
                                            {{ $table->room ? $table->room->name : '-' }} /
                                            {{ $table->name }}
                                            </span>
                                            @empty
                                            —
                                            @endforelse
                                        </p>
                                        @if($orderItem->order_type_id == \App\Models\HotelOrderType::ONLINE_DELIVERY)
                                        <p class="text-left">
                                            <b>Delivery Partner:</b>
                                            {{$orderItem->delivery_partner ? $orderItem->delivery_partner->name : '-'}}
                                        </p>
                                        @endif
                                        <p class="text-left">
                                            <b>Total Items:</b>
                                            {{$orderItem->total_items}}
                                        </p>
                                        @if($orderItem->billing)
                                        <p><b>Payment Mode: </b>
                                            @if ($orderItem->billing->payment_method == 2)
                                                Cheque ({{ $orderItem->billing->bank->bank_name }} / Cheque no.: {{ $orderItem->billing->cheque_no }})
                                            @elseif($orderItem->billing->payment_method == 3)
                                                Bank Deposit ({{ $orderItem->billing->bank->bank_name }})
                                            @elseif($orderItem->billing->payment_method == 4)
                                                Online Portal ({{ $orderItem->billing->online_portal->name }} / Portal Id: {{ $orderItem->billing->customer_portal_id }})
                                            @else
                                                Cash
                                            @endif
                                        </p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-right">
                                            <b>Order Date:</b>
                                            {{$orderItem->order_at}}
                                        </p>
                                        <p class="text-right">
                                            <b>Order Type:</b>
                                            {{$orderItem->order_type->name ?? '---'}}
                                        </p>
                                        <p class="text-right">
                                            <b>Order Status: </b>{{$orderItem->statusName}}
                                        </p>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Item</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Discount Amount (per unit)</th>
                                                <th>Tax Amount (per unit)</th>
                                                <th>Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orderItem->order_items as $item)
                                            <tr>
                                                <td>{{$item->food_name}}</td>
                                                <td>{{$item->quantity}}</td>
                                                <td>{{$item->unit_price}}</td>
                                                <td>{{$item->total_discount}}</td>
                                                <td>{{$item->total_tax}}</td>
                                                <td>{{$item->total_cost}}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><b>Sub Total</b></td>
                                                <td>{{$orderItem->sub_total}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><b>Discount Amount</b></td>
                                                <td>{{$orderItem->total_discount}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><b>Tax Amount({{$orderItem->tax && $orderItem->tax_type ? ($orderItem->tax_type.' '.$orderItem->tax_value.'%') : ''}})</b></td>
                                                <td>{{$orderItem->total_tax}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><b>Service Charge({{$orderItem->service_charge ?? 0}}%)</b></td>
                                                <td>{{$orderItem->total_service_charge ?? 0}}</td>
                                            </tr>
                                            @if($orderItem->is_cabin)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><b>Cabin Charge</b></td>
                                                <td>{{$orderItem->cabin_charge ?? 0}}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><b>Shipping</b></td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><b>Grand Total</b></td>
                                                <td>{{$orderItem->total_cost}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div  class="row">
                                    @if($orderItem->isCancled())
                                        <div class="col-md-12"><p><b>Remarks: </b>{{$orderItem->reason}}</p></div>
                                        <div class="col-md-12"><p><b>Description: </b>{{$orderItem->description}}</p></div>
                                    @endif
                                    <div class="col-4">
                                        <p><b>Entry By: </b>{{$orderItem->createdBy ? $orderItem->createdBy->name :'-'}}</p>
                                    </div>
                                    <div class="col-4"></div>
                                    <div class="col-4">

                                        @if($orderItem->billing)
                                            <p class="text-right">
                                                <b>Bill Status: </b></b>{{ $orderItem->billing->status == 1 ? 'Approved' : 'Waiting For Approval' }}
                                            </p>

                                            @if($orderItem->billing->isCanceled())
                                            <p class="text-right"><b>Cancelled By:
                                                </b>{{$orderItem->billing->user_cancel ? $orderItem->billing->user_cancel->name: '-'}}
                                            </p>
                                            @else
                                            <p class="text-right"><b>Approved By:
                                                </b>{{$orderItem->billing->user_approve ? $orderItem->billing->user_approve->name: '-'}}
                                            </p>
                                            @endif
                                        @else
                                            <p class="text-right">
                                                <b>Bill Status: </b> Not Paid
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <div class="btn-bulk">
                                            <a href="{{route('hotel_order.pos_invoice.edit', $orderItem->id)}}" class="btn btn-primary">Edit</a>
                                            <a href="{{route('hotel_order.print.kot', $orderItem->id)}}" class="btn btn-secondary btnprn">Print KOT</a>
                                            @if($orderItem->billing)
                                            <a href="{{route('hotel_order.pos.generateinvoicebill',$orderItem->billing->id)}}" class="btn btn-primary btnprn">Print Invoice</a>
                                            @else
                                            <a href="{{route('hotel_order.print.order_invoice', $orderItem->id)}}" class="btn btn-primary btnprn">Print Invoice</a>
                                            @endif
                                            {{-- <a href="http://127.0.0.1:8000/sendEmail/1" class="btn btn-primary">Send Mail</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <div id="main">
            <!-- model action create payment -->
            <make-payment-modal
                ref="makePaymentModal"
                :payment_types="{{json_encode($paymentTypes)}}"
                :tax_types = "{{json_encode($taxTypes)}}"
                :discount_types = "{{json_encode($discountTypes)}}"
                :taxes = "{{json_encode($taxes)}}"
                :order_id ="{{json_encode($orderItem->id)}}"
            >
            </make-payment-modal>
            <!-- end of action create payment -->
            @if($orderItem->billing)
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
                                $grandTotal = $orderItem->billing->grandtotal;
                                $totalpaid = collect($orderItem->billing->payment_infos)->sum('total_paid_amount');
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
                                                @forelse ($orderItem->billing->payment_infos as $pinfo)
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
                                                        <td>{{ $orderItem->billing->user_entry->name }}</td>
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
                                                                value="{{ $orderItem->billing->id }}">
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

            <!-- end of payment detail model
            @endif
        </div>

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
<script src="{{ asset('js/hotel_service.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.btnprn').printPage();
    });
</script>
@endpush

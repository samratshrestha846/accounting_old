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
                    <h1>{{$title}} </h1>
                    @include('backend.hotel.order_item.common.menu')
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
                <div class="card">
                    <div class="card-body">
                        <div class="filter">
                            <form action="{{ route('hotel-order.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-8 pr-0">
                                        <select name="per_page" class="form-control">
                                            @foreach ($perpages as $page)
                                                <option value="{{ $page }}"
                                                    {{ $page == $per_page ? 'selected' : '' }}>{{ $page }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="global-btn">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <form class="form-inline" action="" method="">
                                @if (request()->has('per_page'))
                                    <input type="hidden" name="per_page" value="{{ request()->per_page }}">
                                @endif

                                <div class="form-group mx-sm-3">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search">
                                </div>
                                <button type="submit" class="btn btn-primary icon-btn"><i
                                        class="fa fa-search"></i></button>
                            </form>
                        </div>
                        <div class="table-responsive mt noscroll">
                            <table class="table table-bordered yajra-datatable text-center global-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">Order ID</th>
                                        <th class="text-center">Customer</th>
                                        <th class="text-center">Waiter</th>
                                        <th class="text-center">Table</th>
                                        <th class="text-center">Delivery Partner</th>
                                        <th class="text-center">Order Type</th>
                                        <th class="text-center">Ordered At</th>
                                        <th class="text-center">Total Item</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order_items as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->customer ? $item->customer->name : '-' }}</td>
                                            <td>{{ $item->waiter ? $item->waiter->name : '-' }}</td>
                                            <td>
                                                @forelse ($item->tables as $table)
                                                <span class="d-block">
                                                {{ $table->floor ? $table->floor->name : '-' }} /
                                                {{ $table->room ? $table->room->name : '-' }} /
                                                {{ $table->name }}
                                                </span>
                                                @empty
                                                —
                                                @endforelse
                                            </td>
                                            <td>{{$item->delivery_partner ? $item->delivery_partner->name : '-'}}</td>
                                            <td>{{ $item->order_type ? $item->order_type->name  : '-' }}</td>
                                            <td>{{ $item->order_at }}</td>
                                            <td>{{ $item->total_items }}</td>
                                            <td>{{ $item->total_cost }}</td>
                                            <td>
                                                @if($item->status == \App\Enums\OrderItemStatus::CANCLED)
                                                    <span class="badge badge-danger">Cancled</span>
                                                @elseif ($item->status == \App\Enums\OrderItemStatus::PENDING)
                                                    <span class="badge badge-primary">Pending</span>
                                                @elseif ($item->status == \App\Enums\OrderItemStatus::SERVED)
                                                    <span class="badge badge-success">Served</span>
                                                @elseif ($item->status == \App\Enums\OrderItemStatus::SUSPENDED)
                                                    <span class="badge badge-danger">Suspended</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-bulk">
                                                    @can('hotel-order-print')
                                                        <a href="{{ route('hotel_order.print.kot', $item->id) }}"
                                                            class='edit btn btn-secondary icon-btn btn-sm btnprn'
                                                            title='Print KOT'><i class='fa fa-print'></i></a>
                                                    @endcan
                                                    <a href="{{ route('hotel-order.show', $item->id) }}"
                                                        class='edit btn btn-primary icon-btn btn-sm' title='View'><i
                                                            class='fa fa-eye'></i></a>
                                                    @can('hotel-order-edit')
                                                        <a href='{{ route('hotel_order.pos_invoice.edit', $item->id) }}'
                                                            class='edit btn btn-secondary icon-btn btn-sm' title='Edit'><i
                                                                class='fa fa-edit'></i></a>
                                                    @endcan
                                                    @if ($item->billing)
                                                        @can('hotel-order-print')
                                                            <a href="{{ route('hotel_order.pos.generateinvoicebill', $item->billing->id) }}"
                                                                class="btn btn-primary btnprn" title="Print Invoice"><i
                                                                    class='fas fa-file-invoice'></i></a>
                                                        @endcan
                                                    @else
                                                        @can('hotel-order-print')
                                                            <a href="{{ route('hotel_order.print.order_invoice', $item->id) }}"
                                                                class="btn btn-primary btnprn icon-btn btn-sm"
                                                                title="Print Invoice"><i class='fas fa-file-invoice'></i></a>
                                                        @endcan
                                                        @can('hotel-order-payment')
                                                            <a href="javascript:void(0)" class="btn btn-primary icon-btn btn-sm"
                                                                @click="$refs.makePaymentModal_{{ $item->id }}.openModal()"
                                                                title="Make Payment"><i class='fas fa-credit-card'></i></a>
                                                            <!-- model action create payment -->
                                                            <make-payment-modal class="mr-2"
                                                                ref="makePaymentModal_{{ $item->id }}"
                                                                :payment_types="{{ json_encode($paymentTypes) }}"
                                                                :tax_types="{{ json_encode($taxTypes) }}"
                                                                :discount_types="{{ json_encode($discountTypes) }}"
                                                                :taxes="{{ json_encode($taxes) }}"
                                                                :order_id="{{ json_encode($item->id) }}">
                                                            </make-payment-modal>
                                                            <!-- end of action create payment -->
                                                        @endcan
                                                    @endif
                                                    @can('hotel-order-cancelled')
                                                        @if ($item->status !== \App\Enums\OrderItemStatus::CANCLED)
                                                            <button type='button' class='btn btn-primary icon-btn btn-sm'
                                                                data-toggle='modal'
                                                                data-target='#cancellation{{ $item->id }}'
                                                                data-toggle='tooltip' data-placement='top' title='Cancel'><i
                                                                    class='fa fa-ban'></i></button>
                                                            <!-- Modal -->
                                                            <div class='modal fade text-left'
                                                                id='cancellation{{ $item->id }}'>
                                                                <div class='modal-dialog' role='document'>
                                                                    <div class='modal-content'>
                                                                        <div class='modal-header'>
                                                                            <h5 class='modal-title' id='exampleModalLabel'>Order
                                                                                Cancellation</h5>
                                                                            <button type='button' class='close'
                                                                                data-dismiss='modal' aria-label='Close'>
                                                                                <span aria-hidden='true'>&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class='modal-body'>
                                                                            <p>Please give reason for Cancellation</p>
                                                                            <hr>
                                                                            <form
                                                                                action="{{ route('hotel_order.cancle', $item->id) }}"
                                                                                method='POST'>
                                                                                @csrf
                                                                                <div class='form-group'>
                                                                                    <label for='reason'>Reason:</label>
                                                                                    <input type='text' name='reason' id='reason'
                                                                                        class='form-control'
                                                                                        value="{{ $item->reason }}"
                                                                                        placeholder='Enter Reason for Cancellation'
                                                                                        required>
                                                                                </div>
                                                                                <div class='form-group'>
                                                                                    <label for='description'>Description:
                                                                                    </label>
                                                                                    <textarea name='description'
                                                                                        id='description' cols='30' rows='5'
                                                                                        class='form-control'
                                                                                        placeholder='Enter Detailed Reason'
                                                                                        required>{{ $item->description }}</textarea>
                                                                                </div>
                                                                                <button type='submit'
                                                                                    class='btn btn-danger'>Submit</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- End of Modal -->
                                                        @endif
                                                    @endcan
                                                    @can('hotel-order-suspended')
                                                        @if (!$item->isSuspended())
                                                            <button type='button' class='btn btn-primary icon-btn btn-sm ml-1'
                                                                data-toggle='modal'
                                                                data-target='#suspendation{{ $item->id }}'
                                                                data-toggle='tooltip' data-placement='top' title='Suspend'><i
                                                                    class='fas fa-sync'></i></button>
                                                            <!-- Modal -->
                                                            <div class='modal fade text-left'
                                                                id='suspendation{{ $item->id }}'>
                                                                <div class='modal-dialog' role='document'>
                                                                    <div class='modal-content'>
                                                                        <div class='modal-header'>
                                                                            <h5 class='modal-title' id='exampleModalLabel'>Order
                                                                                Suspendation</h5>
                                                                            <button type='button' class='close'
                                                                                data-dismiss='modal' aria-label='Close'>
                                                                                <span aria-hidden='true'>&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class='modal-body'>
                                                                            <p>Please give reason for Suspendation</p>
                                                                            <hr>
                                                                            <form
                                                                                action="{{ route('hotel_order.suspend', $item->id) }}"
                                                                                method='POST'>
                                                                                @csrf
                                                                                <div class='form-group'>
                                                                                    <label for='reason'>Reason:</label>
                                                                                    <input type='text' name='reason' id='reason'
                                                                                        class='form-control'
                                                                                        value="{{ $item->reason }}"
                                                                                        placeholder='Enter Reason for Cancellation'
                                                                                        required>
                                                                                </div>
                                                                                <div class='form-group'>
                                                                                    <label for='description'>Description:
                                                                                    </label>
                                                                                    <textarea name='description'
                                                                                        id='description' cols='30' rows='5'
                                                                                        class='form-control'
                                                                                        placeholder='Enter Detailed Reason'
                                                                                        required>{{ $item->description }}</textarea>
                                                                                </div>
                                                                                <button type='submit'
                                                                                    class='btn btn-danger'>Submit</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- End of Modal -->
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11">No Order Item yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="position row">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            Showing <strong>{{ $order_items->firstItem() }}</strong> to
                                            <strong>{{ $order_items->lastItem() }} </strong> of <strong>
                                                {{ $order_items->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                seconds to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="pagination-sm m-0 float-right">{{ $order_items->links() }}</span>
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

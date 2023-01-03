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
                    <h1>Sales Report Food : {{ $foodDetails->food_name }}</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-food.index') }}" class="global-btn">Back</a>&nbsp;
                    </div>
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-bordered data-table text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-nowrap">Customer Name</th>
                                                <th class="text-nowrap">Reference No</th>
                                                <th class="text-nowrap">Transaction No</th>
                                                <th class="text-nowrap">Bill Date</th>
                                                <th class="text-nowrap">Entry By</th>
                                                <th class="text-nowrap">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($foodSalesBillings as $hotelSale)
                                                <tr>
                                                    <td>
                                                        {{ $hotelSale->client->name }}
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="{{ route('hotel-sales-report.single', $hotelSale->id) }}">{{ $hotelSale->reference_no }}</a>
                                                    </td>
                                                    <td>{{ $hotelSale->transaction_no }}</td>
                                                    <td>{{ $hotelSale->eng_date }}</td>
                                                    <td>{{ $hotelSale->user_entry->name }}</td>

                                                    <td class="form-inline">
                                                        @if($hotelSale->billing_id)
                                                        <a href="{{ route('hotel_order.pos.generateinvoicebill',$hotelSale->billing_id) }}"
                                                            class="btn btn-secondary icon-btn btnprn" title="Print">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                        @else
                                                        <a href="{{ route('hotel_order.print.order_invoice', $hotelSale->id) }}"
                                                            class="btn btn-secondary icon-btn btnprn" title="Print">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9">No Sales yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="mt-3">
                                        <div class="position row">
                                            <div class="col-md-8">
                                                <p class="text-sm">
                                                    Showing <strong>{{ $foodSalesBillings->firstItem() }}</strong> to
                                                    <strong>{{ $foodSalesBillings->lastItem() }} </strong> of <strong>
                                                        {{ $foodSalesBillings->total() }}</strong>
                                                    entries
                                                    <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                        seconds to
                                                        render</span>
                                                </p>
                                            </div>
                                            <div class="col-md-4">
                                                <span
                                                    class="pagination-sm m-0 float-right">{{ $foodSalesBillings->links() }}</span>
                                            </div>
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

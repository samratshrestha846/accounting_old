@extends('backend.layouts.app')
@push('styles')
    <style>
        /* form .cabin-data {
                display: none;
            } */

    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Hotel Sales Report</h1>
                    <div class="btn-bulk">
                        {{-- <a href="{{ route('hotel-sales-report') }}" class="global-btn">New Hotel Table Reservation</a> --}}
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Generate Report</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('hotel-sales-report.filter') }}" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Fiscal Year</label>
                                        <select name="fiscal_year" class="form-control fiscal">
                                            @foreach ($fiscal_years as $fisicalYear)
                                                <option value="{{ $fisicalYear->fiscal_year }}" selected="">
                                                    {{ $fisicalYear->fiscal_year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Starting date</label>
                                        <input type="text" name="starting_date"
                                            class="form-control startdate ndp-nepali-calendar" id="starting_date"
                                            value="2078-04-01" autocomplete="off">
                                        <input type="hidden" name="billing_type_id" value="1">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Ending date</label>
                                        <input type="text" name="ending_date"
                                            class="form-control enddate ndp-nepali-calendar" id="ending_date" value=""
                                            autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" style="height: 18px;"></label>
                                        <button type="submit" class="btn btn-primary" name="POS_generate">Generate
                                            Report</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12 text-right">
                        <div class="btn-bulk">
                            <a href="javascript:void(0)" class="global-btn csvbilled">Export (CSV)</a>
                            <form style="display: inline;" id="exportcsv"
                                action="http://lekhabidhi.test/exportfiltersalesBills/1/2021-07-16/2022-07-16/1"
                                method="POST">
                                <input type="hidden" name="_token" value="5cyI2DPn1vpupX9v62WwENl0reqaz45xHeSE74IG"> <input
                                    type="hidden" name="export_POS" class="form-control" value="1">
                            </form>
                            <a href="javascript:void(0)" class="global-btn pdfbilled" style="margin-left:5px;">Export
                                (PDF)</a>
                            <form style="display: inline;" id="exportpdf"
                                action="http://lekhabidhi.test/pdf/SalesBillsReport/1/2078-04-01/2079-03-32/1"
                                method="POST">
                                <input type="hidden" name="_token" value="5cyI2DPn1vpupX9v62WwENl0reqaz45xHeSE74IG"> <input
                                    type="hidden" name="export_POS" class="form-control" value="1">
                            </form>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header text-center">
                        <h2>Hotel Sales Bills</h2>
                        <h5>From 2078-04-01 to 2079-03-32</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            {{-- <th class="text-nowrap">Billing Type</th> --}}
                                            <th class="text-nowrap">Customer Name</th>
                                            <th class="text-nowrap">Floor</th>
                                            <th class="text-nowrap">Room</th>
                                            <th class="text-nowrap">Table</th>
                                            {{-- <th class="text-nowrap">Reference No</th>
                                            <th class="text-nowrap">Transaction No</th> --}}
                                            <th class="text-nowrap">Bill Date</th>
                                            <th class="text-nowrap">Waiter</th>
                                            <th class="text-nowrap">Total Items</th>
                                            <th class="text-nowrap">Grand Total</th>
                                            <th class="text-nowrap">Status</th>
                                            <th class="text-nowrap">Created By</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($hotelSales as $hotelSale)
                                            <tr>
                                                {{-- <td>{{ $hotelSale->billing ? $hotelSale->billing->name : '-' }}</td> --}}
                                                <td>{{ $hotelSale->customer ? $hotelSale->customer->name : '-' }}</td>
                                                <td>{{ $hotelSale->table ? $hotelSale->table->floor->name : '-' }}</td>
                                                <td>{{ $hotelSale->table ? $hotelSale->table->room->name : '-' }}</td>
                                                <td>{{ $hotelSale->table ? $hotelSale->table->name : '-' }}</td>

                                                {{-- <td>{{ '-' }}</td>
                                                <td>{{ '-' }}</td> --}}
                                                <td>{{ date('Y-m-d', strtotime($hotelSale->order_at)) }}</td>
                                                <td>{{ $hotelSale->waiter ? $hotelSale->waiter->name : '-' }}</td>
                                                <td>{{ $hotelSale->total_items }}</td>
                                                <td>Rs. {{ $hotelSale->total_cost }}</td>
                                                <td>
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
                                                </td>
                                                <td>{{ $hotelSale->createdBy ? $hotelSale->createdBy->name : '-' }}</td>
                                                <td class="form-inline">
                                                    <a href="{{ route('billing.print', $hotelSale->id) }}"
                                                        class="btn btn-secondary icon-btn btnprn" title="Print">
                                                        <i class="fa fa-print"></i>
                                                    </a>

                                                    <a href='{{ route('hotel-sales-report.single', $hotelSale->id) }}'
                                                        class='edit btn btn-primary icon-btn btn-sm' data-toggle='tooltip'
                                                        data-placement='top' title='View'>
                                                        <i class='fa fa-eye'></i>
                                                    </a>
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
                                        <div class="row">
                                            <div class="col-md-5">
                                                <p class="text-sm">
                                                    Showing <strong>{{ $hotelSales->firstItem() }}</strong> to
                                                    <strong>{{ $hotelSales->lastItem() }} </strong> of <strong>
                                                        {{ $hotelSales->total() }}</strong>
                                                    entries
                                                    <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                        seconds to
                                                        render</span>
                                                </p>
                                            </div>
                                            <div class="col-md-7">
                                                <span class="pagination-sm m-0 float-right">{{ $hotelSales->links() }}</span>
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
            $(function() {
                $('.fiscal').change(function() {
                    var fiscal_year = $(this).children("option:selected").val();
                    var array_date = fiscal_year.split("/");

                    var starting_date = document.getElementById("starting_date");
                    var starting_full_date = array_date[0] + '-04-01';
                    starting_date.value = starting_full_date;
                    starting_date.nepaliDatePicker();

                    var ending_date = document.getElementById("ending_date");
                    var ending_year = array_date[1];
                    var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
                    var ending_full_date = ending_year + '-03-' + days_count;
                    ending_date.value = ending_full_date;

                    ending_date.nepaliDatePicker();
                })
            })
        </script>

        <script type="text/javascript">
            window.onload = function() {
                var starting_date = document.getElementById("starting_date");
                var ending_date = document.getElementById("ending_date");
                var ending_year = "{{ $actual_year[1] }}";

                var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
                starting_date.nepaliDatePicker();

                var ending_full_date = ending_year + '-03-' + days_count;
                ending_date.value = ending_full_date;

                ending_date.nepaliDatePicker();
            };
        </script>
    @endpush

@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="container-fluid">
            <div class="sec-header">
                <div class="sec-header-wrap">
                    <h1>Customers Purchase Orders</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('purchaseOrder.create') }}" class="global-btn">Create Purchase
                            Order</a>
                        <a href="{{ route('unapprovedPurchaseOrders') }}" class="global-btn">Unapproved
                            Purchase
                            Orders</a>
                        <a href="{{ route('cancelledPurchaseOrders') }}" class="global-btn">Cancelled Purchase
                            Orders</a>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
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

                {{-- <div class="card">
                    <div class="card-header">
                        <h2>Generate Report</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('purchaseOrderReport') }}" method="GET">
                            @csrf
                            @method("GET")
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Fiscal Year</label>
                                        <select name="fiscal_year" class="form-control fiscal">
                                            @foreach ($fiscal_years as $fiscal_year)
                                                <option value="{{ $fiscal_year->fiscal_year }}"
                                                    {{ $fiscal_year->id == $current_fiscal_year->id ? 'selected' : '' }}>
                                                    {{ $fiscal_year->fiscal_year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Starting date</label>
                                        <input type="text" name="starting_date" class="form-control startdate"
                                            id="starting_date" value="{{ $actual_year[0] . '-04-01' }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Ending date</label>
                                        <input type="text" name="ending_date" class="form-control enddate" id="ending_date"
                                            value="">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-sm">Generate Report</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> --}}

                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="col-md-12">
                                        <h2>Customers Purchase Orders</h2>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="filter">
                                        <form>

                                        </form>
                                        <div class="search-filter">
                                            <form class="form-inline" action="{{ route('clientOrder.search') }}"
                                                method="POST">
                                                @csrf
                                                <div class=" mr-2 ">
                                                    <label for="search" class="sr-only">Search</label>
                                                    <input type="text" class="form-control" id="search" name="search"
                                                        placeholder="Search">
                                                </div>
                                                <button type="submit" class="btn btn-primary icon-btn btn-sm"><i
                                                        class="fa fa-search"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="table-responsive noscroll">
                                        <table class="table table-bordered data-table text-center global-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Client</th>
                                                    <th class="text-nowrap">Order No</th>
                                                    <th class="text-nowrap">Bill Date</th>
                                                    <th class="text-nowrap">Remarks</th>
                                                    <th class="text-nowrap">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($clientorders as $purchaseOrder)
                                                    <tr>
                                                        <td>{{ $purchaseOrder->client_id == null ? '-' : $purchaseOrder->client->name }}
                                                        </td>
                                                        <td>{{ $purchaseOrder->order_no }}</td>
                                                        <td>
                                                            {{ $purchaseOrder->nep_date }} (in B.S) <br>
                                                            {{ $purchaseOrder->eng_date }} (in A.D)
                                                        </td>
                                                        <td>{{$purchaseOrder->remarks}}</td>
                                                        <td>
                                                            <div class="btn-bulk">
                                                                <a href="{{ route('pdf.generatePurchaseOrder', $purchaseOrder->id) }}" class="btn btn-secondary icon-btn btnprn" title="Print" ><i class="fa fa-print"></i> </a>
                                                                @php
                                                                    $showurl = route('purchaseOrder.show', $purchaseOrder->id);
                                                                    $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                                ";
                                                                    echo $btn;
                                                                @endphp
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5">No purchase orders.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="text-sm">
                                                        Showing
                                                        <strong>{{ $clientorders->firstItem() }}</strong>
                                                        to
                                                        <strong>{{ $clientorders->lastItem() }} </strong> of
                                                        <strong>
                                                            {{ $clientorders->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $clientorders->links() }}</span>
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
    </div>
    </div>
    </section>
    </div>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')

@endpush

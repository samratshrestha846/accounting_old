@extends('customerbackend.layouts.app')
@push('styles')

@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="container-fluid">
            <div class="sec-header">
                <div class="sec-header-wrap">
                    <h1>Quotations</h1>
                    <div class="btn-bulk" style="margin-top:10px;">
                        <a href="{{ route('purchaseOrder.customercreate') }}" class="global-btn">Create Purchase
                            Order</a>
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
                                        <h2>Quotations</h2>
                                    </div>
                                </div>
                                <div class="card-body">
                                    {{-- <div class="filter">
                                        <div class="search-filter">
                                            <form class="form-inline" action="{{ route('purchaserOrder.search') }}"
                                                method="POST">
                                                @csrf
                                                <div class="form-group mx-sm-3 mb-2">
                                                    <label for="search" class="sr-only">Search</label>
                                                    <input type="text" class="form-control" id="search" name="search"
                                                        placeholder="Search">
                                                </div>
                                                <button type="submit" class="btn btn-primary icon-btn btn-sm mb-2"><i
                                                        class="fa fa-search"></i></button>
                                            </form>
                                        </div>
                                    </div> --}}
                                    <div class="table-responsive">
                                        <table class="table table-bordered data-table text-center">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Reference No</th>
                                                    <th class="text-nowrap">Bill Date</th>
                                                    <th class="text-nowrap">GrandTotal</th>
                                                    <th class="text-nowrap">Remarks</th>
                                                    <th class="text-nowrap">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($quotations as $quotation)
                                                    <tr>
                                                        <td>{{$quotation->reference_no}}</td>
                                                        <td>{{$quotation->ledger_no}}</td>
                                                        <td>
                                                            {{ $quotation->nep_date }} (in B.S) <br>
                                                            {{ $quotation->eng_date }} (in A.D)
                                                        </td>
                                                        <td>Rs.{{ $quotation->grandtotal }}</td>
                                                        <td style="text-align:center; width:120px;">
                                                            <div class="btn-bulk" style="justify-content:center;">
                                                                @php
                                                                    $showurl = route('myquotation.show', $quotation->id);
                                                                    $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                    ";
                                                                    echo $btn;
                                                                @endphp
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6">No quotation Yet.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="text-sm">
                                                        Showing
                                                        <strong>{{ $quotations->firstItem() }}</strong>
                                                        to
                                                        <strong>{{ $quotations->lastItem() }} </strong> of
                                                        <strong>
                                                            {{ $quotations->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $quotations->links() }}</span>
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

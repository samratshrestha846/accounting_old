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
                    <h1>My Due Purchases</h1>
                    @php
                        $allsales = [];
                        $totalpaid = [];
                        foreach ($purchases as $salesBilling) {
                            $gtotal = round($salesBilling->grandtotal, 2);
                            $singlebillpayments = [];
                            $payments = DB::table('payment_infos')->where('billing_id', $salesBilling->id)->get();
                            foreach ($payments as $paymentinfo) {
                                $payments = round($paymentinfo->payment_amount, 2);
                                array_push($singlebillpayments, $payments);
                            }
                            array_push($totalpaid, array_sum($singlebillpayments));
                            array_push($allsales, $gtotal);
                        }
                        $totalsales = array_sum($allsales);
                        $totalpayment = array_sum($totalpaid);
                        $totaldue = $totalsales - $totalpayment;
                    @endphp
                    <div class="btn-bulk" style="margin-top:10px;">
                        <a href="{{ route('purchaseOrder.customercreate') }}" class="global-btn">Create Purchase
                            Order</a>
                            <a href="{{ route('client.purchases') }}" class="global-btn">All Purchase</a>
                            <a href="{{ route('client.paidpurchases') }}" class="global-btn">Paid Purchases</a>
                            <span class="btn global-btn">Total Sales: Rs.{{ $totalsales }}</span>
                        <span class="btn global-btn">Total Paid: Rs.{{ $totalpayment }}</span>
                        <span class="btn global-btn">Total Due: Rs.{{ $totaldue }}</span>
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
                                        <h2>My Due Purchases</h2>
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
                                                    <th class="text-nowrap">VAT Bill No</th>
                                                    <th class="text-nowrap">Bill Date</th>
                                                    <th class="text-nowrap">GrandTotal</th>
                                                    <th class="text-nowrap">Paid Amount</th>
                                                    <th class="text-nowrap">Due Amount</th>
                                                    <th class="text-nowrap">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($purchases as $purchase)
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
                                                @if ($dueamt > 0)
                                                    <tr>
                                                        <td>{{$purchase->reference_no}}</td>
                                                        <td>{{$purchase->ledger_no}}</td>
                                                        <td>
                                                            {{ $purchase->nep_date }} (in B.S) <br>
                                                            {{ $purchase->eng_date }} (in A.D)
                                                        </td>
                                                        <td>Rs.{{ $purchase->grandtotal }}</td>
                                                        <td>Rs.{{ $totpaid }}</td>
                                                        <td>Rs.{{ $dueamt }}</td>
                                                        <td style="text-align: center;width:120px;">
                                                            <div class="btn-bulk" style="justify-content: center;">
                                                                @php
                                                                    $showurl = route('mypurchase.show', $purchase->id);
                                                                    $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                    ";
                                                                    echo $btn;
                                                                @endphp
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif

                                                @empty
                                                    <tr>
                                                        <td colspan="7">No purchase Yet.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="text-sm">
                                                        Showing
                                                        <strong>{{ $purchases->firstItem() }}</strong>
                                                        to
                                                        <strong>{{ $purchases->lastItem() }} </strong> of
                                                        <strong>
                                                            {{ $purchases->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $purchases->links() }}</span>
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

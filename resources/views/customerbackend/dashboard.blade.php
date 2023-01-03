@extends('customerbackend.layouts.app')
@section('content')

    <div class="content-wrapper">
        <div class="content-mains">
            <div class="content-head">
            </div>
            <section class="content">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="boxes">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <!-- small box -->
                                <div class="small-box">
                                    <div class="inner">
                                        <p>Purchase Order</p>
                                        <h3>{{ $total_purchase_orders }}</h3>
                                    </div>
                                    <a href="{{ route('purchaseOrder.customerindex') }}" class="small-box-footer">View Purchase Orders <i
                                            class="las la-long-arrow-alt-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-4 col-md-6">
                                <!-- small box -->
                                <div class="small-box">
                                    <div class="inner">
                                        <p>Purchases</p>
                                        <h3>{{ $total_purchases }}</h3>
                                    </div>
                                    <a href="{{ route('client.purchases') }}" class="small-box-footer">View Purchases <i
                                            class="las la-long-arrow-alt-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-4 col-md-6">
                                <!-- small box -->
                                <div class="small-box">
                                    <div class="inner">
                                        <p>Quotations</p>
                                        <h3>{{ $total_quotations }}</h3>
                                    </div>
                                    <a href="{{ route('client.quotations') }}" class="small-box-footer">View
                                        Quotations <i class="las la-long-arrow-alt-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->
                            {{-- <div class="col-lg-3 col-md-6">
                                <!-- small box -->
                                <div class="small-box">
                                    <div class="inner">
                                        <p>Cancelled Journals</p>
                                        <h3>{{ $cancelled_journals }}</h3>
                                    </div>
                                    <a href="{{ route('journals.cancelled') }}" class="small-box-footer">View
                                        Cancelled <i class="las la-long-arrow-alt-right"></i></a>
                                </div>
                            </div> --}}
                            <!-- ./col -->
                        </div>
                    </div>
                    {{-- Chart Starts here --}}
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <!-- PIE CHART -->
                            <div class="card">
                                <div class="card-header tools-bar">
                                    <h2>Purchase Report</h2>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="las la-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="las la-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="purchasepieChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <div class="col-md-6">
                            <!-- PIE CHART -->
                            <div class="card">
                                <div class="card-header tools-bar">
                                    <h2>Purchase Return Report</h2>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="las la-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="las la-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="purchasereturnpieChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    {{-- REcent Billings starts here --}}
                    <div class=" report-card card mt-2">
                        <div class="card-header">
                            <h2>Recent Billings</h2>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs responsive-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="tab-1" data-toggle="tab" href="#tab1" role="tab"
                                        aria-controls="tab1" aria-selected="true">Recent Purchases</a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab-2" data-toggle="tab" href="#tab2" role="tab"
                                        aria-controls="tab2" aria-selected="false">Recent Quotations</a>
                                </li>


                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab-3" data-toggle="tab" href="#tab3" role="tab"
                                        aria-controls="tab3" aria-selected="false">Recent Purchase Returns</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tab1" role="tabpanel"
                                aria-labelledby="tab-1">
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
                                        @forelse ($recent_purchases as $purchase)
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
                                        @empty
                                            <tr>
                                                <td colspan="7">No purchase Yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7"><a href="{{route('purchaseOrder.customerindex')}}">View All Purchases</a></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                </div>

                                <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab-2">
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
                                                @forelse ($recent_quotations as $quotation)
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
                                            <tfoot>
                                                <tr>
                                                    <td colspan="7"><a href="{{route('client.quotations')}}">View All Quotations</a></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab-3">
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
                                                @forelse ($recent_purchase_returns as $purchase)
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
                                                @empty
                                                    <tr>
                                                        <td colspan="7">No purchase Yet.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="7"><a href="{{route('client.purchasereturns')}}">View All Purchase return</a></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Get context with jQuery - using jQuery's .get() method.
        // var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
        var donutData = {
            labels: ['Total', 'Paid', 'Due'],
            datasets: [{
                data: @php echo json_encode($purchaseChart); @endphp,
                backgroundColor: ['#9A0A3A', '#087A43', '92AA0B'],
            }]
        }
        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#purchasepieChart').get(0).getContext('2d')
        var pieData = donutData;
        var pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        })
    })
</script>
<script>
    $(function() {
        // Get context with jQuery - using jQuery's .get() method.
        // var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
        var donutData = {
            labels: ['Total', 'Paid', 'Due'],
            datasets: [{
                data: @php echo json_encode($purchasereturnChart); @endphp,
                backgroundColor: ['#9A0A3A', '#087A43', '92AA0B'],
            }]
        }
        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#purchasereturnpieChart').get(0).getContext('2d')
        var pieData = donutData;
        var pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        })
    })
</script>
@endpush

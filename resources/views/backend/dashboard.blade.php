@extends('backend.layouts.app')
@section('content')

    <div class="content-wrapper">
        <div class="content-mains">
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
                    @if (Auth::user()->can('view-journals'))
                    <div class="section-header">
                        <div class="sec-header-wrap">
                            <div class="btn-bulk">
                                <a href="javascript:void(0)" class="global-btn" style="margin-left: 5px; ">Cash in Hand:Rs. {{$cashinhand}}
                                   </a>
                                   <a href="javascript:void(0)" class="global-btn" style="margin-left: 5px; ">Cash in Bank:Rs. {{$cashinbank}}
                                </a>
                                <a href="javascript:void(0)" class="global-btn" style="margin-left: 5px; ">Total:Rs. {{$cashinhand + $cashinbank}}
                                </a>
                            </div>
                        </div>
                    </div>
                        <div class="boxes pt-0">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <!-- small box -->
                                    <div class="small-box">
                                        <div class="inner">
                                            <p>Total Journals</p>
                                            <h3>{{ $total_journals }}</h3>
                                        </div>
                                        <a href="{{ route('journals.index') }}" class="small-box-footer">View Journals <i
                                                class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-md-6">
                                    <!-- small box -->
                                    <div class="small-box">
                                        <div class="inner">
                                            <p>Approved Journals</p>
                                            <h3>{{ $approved_journals }}</h3>
                                        </div>
                                        <a href="{{ route('journals.index') }}" class="small-box-footer">View Approved <i
                                                class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-md-6">
                                    <!-- small box -->
                                    <div class="small-box">
                                        <div class="inner">
                                            <p>Unapproved Journals</p>
                                            <h3>{{ $unapproved_journals }}</h3>
                                        </div>
                                        <a href="{{ route('journals.unapproved') }}" class="small-box-footer">View
                                            Unapproved <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-md-6">
                                    <!-- small box -->
                                    <div class="small-box">
                                        <div class="inner">
                                            <p>Cancelled Journals</p>
                                            <h3>{{ $cancelled_journals }}</h3>
                                        </div>
                                        <a href="{{ route('journals.cancelled') }}" class="small-box-footer">View
                                            Cancelled <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            </div>
                        </div>
                    @endif

                    @if (Auth::user()->can('manage-sales') || Auth::user()->can('manage-credit-note') || Auth::user()->can('manage-purchases') || Auth::user()->can('manage-debit-note'))
                        <div class=" report-card card mt-3">
                            <div class="card-header">
                                <h2>Product Billing Reports</h2>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs responsive-tabs" id="myTab" role="tablist">
                                    @if (Auth::user()->can('manage-sales'))
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="tab-1" data-toggle="tab" href="#tab1" role="tab"
                                                aria-controls="tab1" aria-selected="true">Sales Report</a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->can('manage-credit-note'))
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="tab-2" data-toggle="tab" href="#tab2" role="tab"
                                                aria-controls="tab2" aria-selected="false">Sales Return Report</a>
                                        </li>
                                    @endif


                                    @if (Auth::user()->can('manage-purchases'))
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="tab-3" data-toggle="tab" href="#tab3" role="tab"
                                                aria-controls="tab3" aria-selected="false">Purchase Report</a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->can('manage-debit-note'))
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="tab-4" data-toggle="tab" href="#tab4" role="tab"
                                                aria-controls="tab4" aria-selected="false">Purchase Return Report</a>
                                        </li>
                                    @endif
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    @if (Auth::user()->can('manage-sales'))
                                        <div class="tab-pane fade show active" id="tab1" role="tabpanel"
                                        aria-labelledby="tab-1">
                                            <div class="sub-tabs-head">
                                                <h3>Sales Report</h3>
                                                <ul class="nav nav-tabs sub-tabs" id="custom-tabs-two-tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="salestoday-tab" data-toggle="pill"
                                                            href="#salestoday" role="tab" aria-controls="salestoday"
                                                            aria-selected="false">Today</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="salesmonth-tab" data-toggle="pill"
                                                            href="#salesmonth" role="tab" aria-controls="salesmonth"
                                                            aria-selected="true">This Month</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="salesyear-tab" data-toggle="pill"
                                                            href="#salesyear" role="tab" aria-controls="salesyear"
                                                            aria-selected="false">This Year</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-content sub-tab-content" id="custom-tabs-two-tabContent">
                                                <div class="tab-pane fade" id="salesyear" role="tabpanel"
                                                    aria-labelledby="salesyear-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $salesbillcount }}</h3>

                                                                    <p>Total Sales Bills Count</p>
                                                                    <h4>Rs.{{ $totsalesbillamt }}</h4>

                                                                    <p>Total Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 1) }}"
                                                                    class="small-box-footer">View All Sales
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $appsalesbillcount }}</h3>

                                                                    <p>Approved Sales Bills</p>
                                                                    <h4>Rs.{{ $totappsalesbillamt }}</h4>

                                                                    <p>Approved Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 1) }}"
                                                                    class="small-box-footer">View Approved
                                                                    Sales
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $unappsalesbillcount }}</h3>

                                                                    <p>Unapproved Sales Bills</p>
                                                                    <h4>Rs.{{ $totunappsalesbillamt }}
                                                                    </h4>

                                                                    <p>Unapproved Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 1) }}"
                                                                    class="small-box-footer">View
                                                                    Unapproved
                                                                    Sales
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledsalesbillcount }}
                                                                    </h3>

                                                                    <p>Cancelled Sales Bills</p>
                                                                    <h4>Rs.{{ $totcancelledappsalesbillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 1) }}"
                                                                    class="small-box-footer">View Cancelled
                                                                    Sales
                                                                    Bills<i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                    </div>
                                                    <div class="card sub-card mt">
                                                        <div class="card-header tools-bar">
                                                            <h2>Sales Bar Chart</h2>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool"
                                                                    data-card-widget="collapse">
                                                                    <i class="las la-minus"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-tool"
                                                                    data-card-widget="remove">
                                                                    <i class="las la-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="chart">
                                                                <canvas id="barChart"
                                                                    style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                                            </div>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="salesmonth" role="tabpanel"
                                                    aria-labelledby="salesmonth-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $monthlysalesbillcount }}</h3>

                                                                    <p>Total Sales Bills Count</p>
                                                                    <h4>Rs.{{ $totmonthlysalesbillamt }}</h4>

                                                                    <p>Total Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 1) }}"
                                                                    class="small-box-footer">View All Sales Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $appmonthlysalesbillcount }}</h3>

                                                                    <p>Approved Sales Bills Count</p>
                                                                    <h4>Rs.{{ $totappmonthlysalesbillamt }}</h4>

                                                                    <p>Approved Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 1) }}"
                                                                    class="small-box-footer">Approved Sales Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $unappmonthlysalesbillcount }}</h3>

                                                                    <p>Unapproved Sales Bills</p>
                                                                    <h4>Rs.{{ $totunappmonthlysalesbillamt }}
                                                                    </h4>

                                                                    <p>Unapproved Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 1) }}"
                                                                    class="small-box-footer">Unapproved Sales Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledmonthlysalesbillcount }}
                                                                    </h3>

                                                                    <p>Cancelled Sales Bills</p>
                                                                    <h4>Rs.{{ $totcancelledappmonthlysalesbillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 1) }}"
                                                                    class="small-box-footer">Cancelled Sales Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade active show" id="salestoday" role="tabpanel"
                                                    aria-labelledby="salestoday-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $todaysalesbillcount }}</h3>

                                                                    <p>Total Sales Bills Count</p>
                                                                    <h4>Rs.{{ $tottodaysalesbillamt }}</h4>

                                                                    <p>Total Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 1) }}"
                                                                    class="small-box-footer">View All Sales Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $apptodaysalesbillcount }}</h3>

                                                                    <p>Approved Sales Bills Count</p>
                                                                    <h4>Rs.{{ $totapptodaysalesbillamt }}</h4>

                                                                    <p>Approved Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 1) }}"
                                                                    class="small-box-footer">Approved Sales Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $unapptodaysalesbillcount }}</h3>

                                                                    <p>Unapproved Sales Bills</p>
                                                                    <h4>Rs.{{ $totunapptodaysalesbillamt }}</h4>

                                                                    <p>Unapproved Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 1) }}"
                                                                    class="small-box-footer">Unapproved Sales Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledtodaysalesbillcount }}</h3>

                                                                    <p>Cancelled Sales Bills</p>
                                                                    <h4>Rs.{{ $totcancelledapptodaysalesbillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Sales Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 1) }}"
                                                                    class="small-box-footer">Cancelled Sales Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (Auth::user()->can('manage-credit-note'))
                                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab-2">
                                            <div class="sub-tabs-head">
                                                <h3>Sales Return Report</h3>
                                                <ul class="nav nav-tabs sub-tabs" id="custom-tabs-two-tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="salesreturntoday-tab"
                                                            data-toggle="pill" href="#salesreturntoday" role="tab"
                                                            aria-controls="salesreturntoday" aria-selected="false">Today</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="salesreturnmonth-tab"
                                                            data-toggle="pill" href="#salesreturnmonth" role="tab"
                                                            aria-controls="salesreturnmonth" aria-selected="true">This
                                                            Month</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="salesreturnyear-tab"
                                                            data-toggle="pill" href="#salesreturnyear" role="tab"
                                                            aria-controls="salesreturnyear" aria-selected="false">This
                                                            Year</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-content sub-tab-content" id="custom-tabs-two-tabContent">
                                                <div class="tab-pane fade" id="salesreturnyear" role="tabpanel"
                                                    aria-labelledby="salesreturnyear-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $salesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Total Sales Return Bills
                                                                        Count</p>
                                                                    <h4>Rs.{{ $totsalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Total Sales Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 6) }}"
                                                                    class="small-box-footer">View
                                                                    All Sales
                                                                    Return
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $appsalesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Approved Sales Return
                                                                        Bills</p>
                                                                    <h4>Rs.{{ $totappsalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Approved Sales Return
                                                                        Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 6) }}"
                                                                    class="small-box-footer">
                                                                    Approved
                                                                    Sales
                                                                    Return Bills <i
                                                                        class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $unappsalesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Unapproved Sales Return
                                                                        Bills</p>
                                                                    <h4>Rs.{{ $totunappsalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Unapproved Sales Return
                                                                        Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 6) }}"
                                                                    class="small-box-footer">
                                                                    Unapproved
                                                                    Sales
                                                                    Return Bills <i
                                                                        class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledsalesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Cancelled Sales Return
                                                                        Bills</p>
                                                                    <h4>Rs.{{ $totcancelledappsalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Sales Return
                                                                        Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 6) }}"
                                                                    class="small-box-footer">
                                                                    Cancelled
                                                                    Sales
                                                                    Return Bills<i
                                                                        class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                    </div>
                                                    <div class="card sub-card mt">
                                                        <div class="card-header tools-bar">
                                                            <h2>Sales Return Bar Chart</h2>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool"
                                                                    data-card-widget="collapse">
                                                                    <i class="las la-minus"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-tool"
                                                                    data-card-widget="remove">
                                                                    <i class="las la-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="chart">
                                                                <canvas id="salesreturnbarChart"
                                                                    style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                                            </div>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="salesreturnmonth" role="tabpanel"
                                                    aria-labelledby="salesmonth-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $monthlysalesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Total Sales Return Bills Count
                                                                    </p>
                                                                    <h4>Rs.{{ $totmonthlysalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Total Sales Return Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 6) }}"
                                                                    class="small-box-footer">View All
                                                                    Sales Return
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $appmonthlysalesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Approved Sales Return Bills Count
                                                                    </p>
                                                                    <h4>Rs.{{ $totappmonthlysalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Approved Sales Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 6) }}"
                                                                    class="small-box-footer">Approved
                                                                    Sales Return
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $unappmonthlysalesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Unapproved Sales Return Bills</p>
                                                                    <h4>Rs.{{ $totunappmonthlysalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Unapproved Sales Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 6) }}"
                                                                    class="small-box-footer">Unapproved
                                                                    Sales Return
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledmonthlysalesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Cancelled Sales Return Bills</p>
                                                                    <h4>Rs.{{ $totcancelledappmonthlysalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Sales Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 6) }}"
                                                                    class="small-box-footer">Cancelled
                                                                    Sales Return
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade active show" id="salesreturntoday" role="tabpanel"
                                                    aria-labelledby="salestoday-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $todaysalesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Total Sales Return Bills Count
                                                                    </p>
                                                                    <h4>Rs.{{ $tottodaysalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Total Sales Return Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 6) }}"
                                                                    class="small-box-footer">View All
                                                                    Sales Return
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $apptodaysalesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Approved Sales Return Bills Count
                                                                    </p>
                                                                    <h4>Rs.{{ $totapptodaysalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Approved Sales Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 6) }}"
                                                                    class="small-box-footer">Approved
                                                                    Sales Return
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $unapptodaysalesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Unapproved Sales Return Bills</p>
                                                                    <h4>Rs.{{ $totunapptodaysalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Unapproved Sales Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 6) }}"
                                                                    class="small-box-footer">Unapproved
                                                                    Sales Return
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledtodaysalesreturnbillcount }}
                                                                    </h3>

                                                                    <p>Cancelled Sales Return Bills</p>
                                                                    <h4>Rs.{{ $totcancelledapptodaysalesreturnbillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Sales Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 6) }}"
                                                                    class="small-box-footer">Cancelled
                                                                    Sales Return
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (Auth::user()->can('manage-purchases'))
                                        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab-3">
                                            <div class="sub-tabs-head">
                                                <h3>Purchase Report</h3>
                                                <ul class="nav nav-tabs sub-tabs" id="custom-tabs-two-tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="purchasetoday-tab" data-toggle="pill"
                                                            href="#purchasetoday" role="tab" aria-controls="purchasetoday"
                                                            aria-selected="false">Today</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="purchasemonth-tab" data-toggle="pill"
                                                            href="#purchasemonth" role="tab" aria-controls="purchasemonth"
                                                            aria-selected="true">This
                                                            Month</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="purchaseyear-tab" data-toggle="pill"
                                                            href="#purchaseyear" role="tab" aria-controls="purchaseyear"
                                                            aria-selected="false">This
                                                            Year</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-content sub-tab-content" id="custom-tabs-two-tabContent">
                                                <div class="tab-pane fade" id="purchaseyear" role="tabpanel"
                                                    aria-labelledby="purchaseyear-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $purchasebillcount }}
                                                                    </h3>

                                                                    <p>Total Purchase Bills
                                                                        Count</p>
                                                                    <h4>Rs.{{ $totpurchasebillamt }}
                                                                    </h4>

                                                                    <p>Total Purchase Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 2) }}"
                                                                    class="small-box-footer">View
                                                                    All
                                                                    Purchase
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $apppurchasebillcount }}
                                                                    </h3>

                                                                    <p>Approved Purchase Bills
                                                                    </p>
                                                                    <h4>Rs.{{ $totapppurchasebillamt }}
                                                                    </h4>

                                                                    <p>Approved Purchase Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 2) }}"
                                                                    class="small-box-footer">View
                                                                    Approved
                                                                    Purchase
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $unapppurchasebillcount }}
                                                                    </h3>

                                                                    <p>Unapproved Purchase Bills
                                                                    </p>
                                                                    <h4>Rs.{{ $totunapppurchasebillamt }}
                                                                    </h4>

                                                                    <p>Unapproved Purchase Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 2) }}"
                                                                    class="small-box-footer">
                                                                    Unapproved
                                                                    Purchase Bills <i
                                                                        class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledpurchasebillcount }}
                                                                    </h3>

                                                                    <p>Cancelled Purchase Bills
                                                                    </p>
                                                                    <h4>Rs.{{ $totcancelledapppurchasebillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Purchase Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 2) }}"
                                                                    class="small-box-footer">View
                                                                    Cancelled
                                                                    Purchase Bills<i
                                                                        class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                    </div>
                                                    <div class="card sub-card mt">
                                                        <div class="card-header tools-bar">
                                                            <h2>Purchase
                                                                Bar Chart
                                                            </h2>

                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool"
                                                                    data-card-widget="collapse">
                                                                    <i class="las la-minus"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-tool"
                                                                    data-card-widget="remove">
                                                                    <i class="las la-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="chart">
                                                                <canvas id="purchasebarChart"
                                                                    style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                                            </div>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="purchasemonth" role="tabpanel"
                                                    aria-labelledby="salesmonth-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $monthlypurchasebillcount }}
                                                                    </h3>

                                                                    <p>Total Purchase Bills Count</p>
                                                                    <h4>Rs.{{ $totmonthlypurchasebillamt }}
                                                                    </h4>

                                                                    <p>Total Purchase Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 2) }}"
                                                                    class="small-box-footer">View All
                                                                    Purchase
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $appmonthlypurchasebillcount }}
                                                                    </h3>

                                                                    <p>Approved Purchase Bills Count</p>
                                                                    <h4>Rs.{{ $totappmonthlypurchasebillamt }}
                                                                    </h4>

                                                                    <p>Approved Purchase Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 2) }}"
                                                                    class="small-box-footer">Approved
                                                                    Purchase
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $unappmonthlypurchasebillcount }}
                                                                    </h3>

                                                                    <p>Unapproved Purchase Bills</p>
                                                                    <h4>Rs.{{ $totunappmonthlypurchasebillamt }}
                                                                    </h4>

                                                                    <p>Unapproved Purchase Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 2) }}"
                                                                    class="small-box-footer">Unapproved
                                                                    Purchase
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledmonthlypurchasebillcount }}
                                                                    </h3>

                                                                    <p>Cancelled Purchase Bills</p>
                                                                    <h4>Rs.{{ $totcancelledappmonthlypurchasebillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Purchase Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 2) }}"
                                                                    class="small-box-footer">Cancelled
                                                                    Purchase
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade active show" id="purchasetoday" role="tabpanel"
                                                    aria-labelledby="salestoday-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $todaypurchasebillcount }}
                                                                    </h3>

                                                                    <p>Total Purchase Bills Count</p>
                                                                    <h4>Rs.{{ $tottodaypurchasebillamt }}
                                                                    </h4>

                                                                    <p>Total Purchase Bills Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 2) }}"
                                                                    class="small-box-footer">View All
                                                                    Purchase
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $apptodaypurchasebillcount }}
                                                                    </h3>

                                                                    <p>Approved Purchase Bills Count</p>
                                                                    <h4>Rs.{{ $totapptodaypurchasebillamt }}
                                                                    </h4>

                                                                    <p>Approved Purchase Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 2) }}"
                                                                    class="small-box-footer">Approved
                                                                    Purchase
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $unapptodaypurchasebillcount }}
                                                                    </h3>

                                                                    <p>Unapproved Purchase Bills</p>
                                                                    <h4>Rs.{{ $totunapptodaypurchasebillamt }}
                                                                    </h4>

                                                                    <p>Unapproved Purchase Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 2) }}"
                                                                    class="small-box-footer">Unapproved
                                                                    Purchase
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledtodaypurchasebillcount }}
                                                                    </h3>

                                                                    <p>Cancelled Purchase Bills</p>
                                                                    <h4>Rs.{{ $totcancelledapptodaypurchasebillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Purchase Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 2) }}"
                                                                    class="small-box-footer">Cancelled
                                                                    Purchase
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (Auth::user()->can('manage-debit-note'))
                                        <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab-4">
                                            <div class="sub-tabs-head">
                                                <h3>Purchase Return Report</h3>
                                                <ul class="nav nav-tabs sub-tabs" id="custom-tabs-two-tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="purchasereturntoday-tab"
                                                            data-toggle="pill" href="#purchasereturntoday" role="tab"
                                                            aria-controls="purchasereturntoday"
                                                            aria-selected="false">Today</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="purchasereturnmonth-tab"
                                                            data-toggle="pill" href="#purchasereturnmonth" role="tab"
                                                            aria-controls="purchasereturnmonth" aria-selected="true">This
                                                            Month</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="purchasereturnyear-tab"
                                                            data-toggle="pill" href="#purchasereturnyear" role="tab"
                                                            aria-controls="purchasereturnyear" aria-selected="false">This
                                                            Year</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-content sub-tab-content" id="custom-tabs-two-tabContent">
                                                <div class="tab-pane fade" id="purchasereturnyear"
                                                    role="tabpanel" aria-labelledby="purchasereturnyear-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $purchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Total Purchase Return
                                                                        Bills Count</p>
                                                                    <h4>Rs.{{ $totpurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Total Purchase Return
                                                                        Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 5) }}"
                                                                    class="small-box-footer">View
                                                                    All
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $apppurchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Approved Purchase Return
                                                                        Bills</p>
                                                                    <h4>Rs.{{ $totapppurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Approved Purchase Return
                                                                        Bills Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 5) }}"
                                                                    class="small-box-footer">View
                                                                    Approved
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box ">
                                                                <div class="inner">
                                                                    <h3>{{ $unapppurchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Unapproved Purchase
                                                                        Return Bills</p>
                                                                    <h4>Rs.{{ $totunapppurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Unapproved Purchase
                                                                        Return Bills
                                                                        Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 5) }}"
                                                                    class="small-box-footer">View
                                                                    Unapproved
                                                                    Bills <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-3 col-md-6">
                                                            <!-- small box -->
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledpurchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Cancelled Purchase Return
                                                                        Bills</p>
                                                                    <h4>Rs.{{ $totcancelledapppurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Purchase Return
                                                                        Bills
                                                                        Amount
                                                                    </p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 5) }}"
                                                                    class="small-box-footer">View
                                                                    Cancelled
                                                                    Bills<i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                    </div>
                                                    <div class="card sub-card mt">
                                                        <div class="card-header tools-bar">
                                                            <h2>Purchase
                                                                Return Bar
                                                                Chart
                                                            </h2>

                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool"
                                                                    data-card-widget="collapse">
                                                                    <i class="las la-minus"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-tool"
                                                                    data-card-widget="remove">
                                                                    <i class="las la-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="chart">
                                                                <canvas id="purchasereturnbarChart"
                                                                    style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                                            </div>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="purchasereturnmonth" role="tabpanel"
                                                    aria-labelledby="purchasereturnmonth-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $monthlypurchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Total Purchase Return Bills Count
                                                                    </p>
                                                                    <h4>Rs.{{ $totmonthlypurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Total Purchase Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 5) }}"
                                                                    class="small-box-footer">View All
                                                                    Purchase
                                                                    Return
                                                                    Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $appmonthlypurchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Approved Purchase Return Bills
                                                                        Count</p>
                                                                    <h4>Rs.{{ $totappmonthlypurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Approved Purchase Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 5) }}"
                                                                    class="small-box-footer">Approved
                                                                    Purchase
                                                                    Return
                                                                    Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $unappmonthlypurchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Unapproved Purchase Return Bills
                                                                    </p>
                                                                    <h4>Rs.{{ $totunappmonthlypurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Unapproved Purchase Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 5) }}"
                                                                    class="small-box-footer">Unapproved
                                                                    Purchase
                                                                    Return
                                                                    Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledmonthlypurchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Cancelled Purchase Return Bills
                                                                    </p>
                                                                    <h4>Rs.{{ $totcancelledappmonthlypurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Purchase Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 5) }}"
                                                                    class="small-box-footer">Cancelled
                                                                    Purchase
                                                                    Return
                                                                    Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade active show" id="purchasereturntoday" role="tabpanel"
                                                    aria-labelledby="purchasereturntoday-tab">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $todaypurchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Total Purchase Return Bills Count
                                                                    </p>
                                                                    <h4>Rs.{{ $tottodaypurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Total Purchase Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 5) }}"
                                                                    class="small-box-footer">View All
                                                                    Purchase
                                                                    Return
                                                                    Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $apptodaypurchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Approved Purchase Return Bills
                                                                        Count</p>
                                                                    <h4>Rs.{{ $totapptodaypurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Approved Purchase Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.report', 5) }}"
                                                                    class="small-box-footer">Approved
                                                                    Purchase
                                                                    Return
                                                                    Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $unapptodaypurchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Unapproved Purchase Return Bills
                                                                    </p>
                                                                    <h4>Rs.{{ $totunapptodaypurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Unapproved Purchase Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.unapproved', 5) }}"
                                                                    class="small-box-footer">Unapproved
                                                                    Purchase
                                                                    Return
                                                                    Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="small-box">
                                                                <div class="inner">
                                                                    <h3>{{ $cancelledtodaypurchasereturnbillcount }}
                                                                    </h3>

                                                                    <p>Cancelled Purchase Return Bills
                                                                    </p>
                                                                    <h4>Rs.{{ $totcancelledapptodaypurchasereturnbillamt }}
                                                                    </h4>

                                                                    <p>Cancelled Purchase Return Bills
                                                                        Amount</p>
                                                                </div>
                                                                <a href="{{ route('billings.cancelled', 5) }}"
                                                                    class="small-box-footer">Cancelled
                                                                    Purchase
                                                                    Return
                                                                    Bills
                                                                    <i class="las la-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                   @include('backend.dashboard_serviceSale_report')
                    @if (Auth::user()->can('manage-reconciliation-statement'))
                        <div class="card mt-3">
                            <div class="card-header">
                                <h2>Cheque left to cashed out</h2>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered data-table text-center global-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-nowrap">Jounal no.</th>
                                                <th class="text-nowrap">BankName</th>
                                                <th class="text-nowrap">Cheque no / Bank Deposit</th>
                                                <th class="text-nowrap">Receipt / Payment</th>
                                                <th class="text-nowrap">Related Party</th>
                                                <th class="text-nowrap">Amount</th>
                                                <th class="text-nowrap">Cheque cashed on (in B.S.)</th>
                                                <th class="text-nowrap">Cashed out on (in B.S.)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($reconciliations as $reconciliation)
                                                <tr>
                                                    <td class="text-nowrap">
                                                        {{ $reconciliation->jv_id == null ? '-' : $reconciliation->journal->journal_voucher_no }}
                                                    </td>
                                                    <td class="text-nowrap">{{ $reconciliation->bank->bank_name }}</td>
                                                    <td class="text-nowrap">
                                                        {{ $reconciliation->cheque_no == null ? 'Bank Transfer' : $reconciliation->cheque_no }}
                                                    </td>
                                                    <td class="text-nowrap">
                                                        {{ $reconciliation->receipt_payment == 0 ? 'Receipt' : 'Payment' }}
                                                    </td>
                                                    <td class="text-nowrap">
                                                        {{ $reconciliation->vendor_id == null ? $reconciliation->other_receipt : $reconciliation->vendor->company_name }}
                                                    </td>
                                                    <td class="text-nowrap">Rs.{{ $reconciliation->amount }}</td>
                                                    <td class="text-nowrap">{{ $reconciliation->cheque_entry_date }}</td>
                                                    <td class="text-nowrap">
                                                        {{ $reconciliation->cheque_cashed_date == null ? '-' : $reconciliation->cheque_cashed_date }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="8">No statements yet.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <div class="card-footer mt">
                                        <div class="btn-bulk">
                                            <a href="{{ route('bankReconciliationStatement.index') }}" class="btn btn-sm btn-primary ">View All
                                                Cheques</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Product Report --}}
                    @if (Auth::user()->can('view-products'))
                        <div class="card card-product mt-3">
                            <div class="card-header">
                                <h2>Products</h2>
                            </div>
                            <div class="card-body">
                                <!-- Small boxes (Stat box) -->
                                <div class="row">

                                    <div class="col-lg-3 col-md-6">
                                        <!-- small box -->
                                        <div class="small-box">
                                            <div class="inner">
                                                <h3>{{ $product_categories }}</h3>

                                                <p>Total Categories</p>
                                            </div>
                                            <a href="{{ route('category.index') }}" class="small-box-footer">View All
                                                Categories <i class="las la-long-arrow-alt-right"></i></a>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6">
                                        <!-- small box -->
                                        <div class="small-box">
                                            <div class="inner">
                                                <h3>{{ $products }}</h3>

                                                <p>Total Products</p>
                                            </div>
                                            <a href="{{ route('product.index') }}" class="small-box-footer">View All
                                                Products
                                                <i class="las la-long-arrow-alt-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <!-- small box -->
                                        <div class="small-box">
                                            <div class="inner">
                                                <h3>{{ $godowns }}</h3>

                                                <p>Total Godowns</p>
                                            </div>
                                            <a href="{{ route('godown.index') }}" class="small-box-footer">View Godowns
                                                <i class="las la-long-arrow-alt-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-md-6">
                                        <!-- small box -->
                                        <div class="small-box">
                                            <div class="inner">
                                                <h3>{{ $damagedProducts }}</h3>

                                                <p>Damaged Products</p>
                                            </div>
                                            <a href="{{ route('damaged_products.index') }}"
                                                class="small-box-footer">View
                                                Damaged Products <i class="las la-long-arrow-alt-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>

                        <div class="card mt-3 mb-0">
                            <div class="card-header tools-bar">
                                <h2>Latest products</h2>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="las la-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive text-center">
                                    <table class="table m-0 table-bordered global-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Product Code</th>
                                                <th>PRoduct Name</th>
                                                <th>Product Category</th>
                                                <th>In stock (units)</th>
                                                <th>Cost of Product</th>
                                                <th>Product Price</th>
                                                <th>Status</th>
                                                <th>Refundable</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($latest_products as $product)
                                                <tr>
                                                    <td>
                                                        <a
                                                            href="{{ route('product.show', $product->id) }}">{{ $product->product_code }}</a>
                                                    </td>
                                                    <td>
                                                        {{ $product->product_name }}
                                                    </td>
                                                    <td>
                                                        {{ $product->category->category_name }}
                                                    </td>
                                                    <td>
                                                        {{ $product->total_stock }}
                                                        {{ $product->primary_unit }}<br>({{ $product->primary_number }}
                                                        {{ $product->primary_unit }} contains
                                                        {{ $product->secondary_number }}
                                                        {{ $product->secondary_unit }})
                                                    </td>
                                                    <td>
                                                        Rs. {{ $product->cost_of_product }}
                                                    </td>
                                                    <td>
                                                        Rs. {{ $product->product_price }}
                                                    </td>
                                                    <td>
                                                        @if ($product->status == 1)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-danger">Inactive</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($product->refundable == 1)
                                                            <span class="badge badge-danger">Non-Refundable</span>
                                                        @else
                                                            <span class="badge badge-success">Refundable</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8">No products yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer mt">
                                    <div class="btn-bulk">
                                        <a href="{{ route('product.index') }}" class="btn btn-sm btn-primary ">View All
                                            Products</a>
                                        <a href="{{ route('damaged_products.index') }}"
                                            class="btn btn-sm btn-secondary ">View
                                            Damaged
                                            Products</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (Auth::user()->can('manage-sales') || (Auth::user()->can('manage-purchases')))
                        <div class="row mt-3">
                            @if (Auth::user()->can('manage-sales'))
                                <div class="col-md-6">
                                    <!-- PIE CHART -->
                                    <div class="card">
                                        <div class="card-header tools-bar">
                                            <h2>Top 10 Sales By Client</h2>
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
                                            <canvas id="salespieChart"
                                                style="min-height: 180px; height: 180px; max-height: 180px; max-width: 100%;"></canvas>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                            @endif

                            @if (Auth::user()->can('manage-purchases'))
                                <div class="col-md-6">
                                    <!-- PIE CHART -->
                                    <div class="card">
                                        <div class="card-header tools-bar">
                                            <h2>Top 10 Purchase from Suppliers</h2>

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
                                                style="min-height: 180px; height: 180px; max-height: 180px; max-width: 100%;"></canvas>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                            @endif
                        </div>
                    @endif

                    @if (Auth::user()->can('view-products'))
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <!-- PIE CHART -->
                                <div class="card">
                                    <div class="card-header tools-bar">
                                        <h2>Top 10 Frequent Sold Products</h2>

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
                                        <canvas id="productpieChart"
                                            style="min-height: 180px; height: 180px; max-height: 180px; max-width: 100%;"></canvas>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <div class="col-md-6">
                                <!-- PIE CHART -->
                                <div class="card">
                                    <div class="card-header tools-bar">
                                        <h2>Top 10 Grossing Product</h2>

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
                                        <canvas id="grossingpieChart"
                                            style="min-height: 180px; height: 180px; max-height: 180px; max-width: 100%;"></canvas>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    @endif
                </div>

            </section>
        </div>
    </div>
@endsection

@push('scripts')

    {{-- Sales Billing --}}
    <script>
        $(function() {
            //Area Chart Data
            var areaChartData = {
                labels: ['साउन', 'भदौ', 'असोज', 'कार्तिक', 'मंसिर', 'पुष', 'माघ', 'फागुन', 'चैत', 'वैशाख',
                    'जेठ', 'असार'
                ],
                datasets: [{
                        label: 'Total Approved Sales',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: @php echo json_encode($appsales); @endphp
                    },
                    {
                        label: 'Total Unapproved Sales',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: false,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: @php echo json_encode($unappsales); @endphp
                    },
                ]
            }

            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#barChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            var temp0 = areaChartData.datasets[0]
            var temp1 = areaChartData.datasets[1]
            barChartData.datasets[0] = temp1
            barChartData.datasets[1] = temp0

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false
            }

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })
        })
    </script>
    {{-- Sales Return Billing --}}
    <script>
        $(function() {
            //Area Chart Data
            var areaChartData = {
                labels: ['साउन', 'भदौ', 'असोज', 'कार्तिक', 'मंसिर', 'पुष', 'माघ', 'फागुन', 'चैत', 'वैशाख',
                    'जेठ', 'असार'
                ],
                datasets: [{
                        label: 'Total Approved Sales',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: @php echo json_encode($appsalesreturn); @endphp
                    },
                    {
                        label: 'Total Unapproved Sales',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: false,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: @php echo json_encode($unappsalesreturn); @endphp
                    },
                ]
            }

            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#salesreturnbarChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            var temp0 = areaChartData.datasets[0]
            var temp1 = areaChartData.datasets[1]
            barChartData.datasets[0] = temp1
            barChartData.datasets[1] = temp0

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false
            }

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })
        })
    </script>

    {{-- Purchase Billing --}}
    <script>
        $(function() {
            //Area Chart Data
            var areaChartData = {
                labels: ['साउन', 'भदौ', 'असोज', 'कार्तिक', 'मंसिर', 'पुष', 'माघ', 'फागुन', 'चैत', 'वैशाख',
                    'जेठ', 'असार'
                ],
                datasets: [{
                        label: 'Total Approved Purchase',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: @php echo json_encode($apppurchase); @endphp
                    },
                    {
                        label: 'Total Unapproved Purchase',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: false,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: @php echo json_encode($unapppurchase); @endphp
                    },
                ]
            }

            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#purchasebarChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            var temp0 = areaChartData.datasets[0]
            var temp1 = areaChartData.datasets[1]
            barChartData.datasets[0] = temp1
            barChartData.datasets[1] = temp0

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false
            }

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })
        })
    </script>
    {{-- Purchase Return Billing --}}
    <script>
        $(function() {
            //Area Chart Data
            var areaChartData = {
                labels: ['साउन', 'भदौ', 'असोज', 'कार्तिक', 'मंसिर', 'पुष', 'माघ', 'फागुन', 'चैत', 'वैशाख',
                    'जेठ', 'असार'
                ],
                datasets: [{
                        label: 'Total Approved Purchase Return',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: @php echo json_encode($apppurchasereturn); @endphp
                    },
                    {
                        label: 'Total Unapproved Purchase Return',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: false,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: @php echo json_encode($unapppurchasereturn); @endphp
                    },
                ]
            }

            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#purchasereturnbarChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            var temp0 = areaChartData.datasets[0]
            var temp1 = areaChartData.datasets[1]
            barChartData.datasets[0] = temp1
            barChartData.datasets[1] = temp0

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false
            }

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })
        })
    </script>
    <script>
        $(function() {
            // Get context with jQuery - using jQuery's .get() method.
            // var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
            var donutData = {
                labels: @php echo json_encode($eachclientname); @endphp,
                datasets: [{
                    data: @php echo json_encode($salesforclient); @endphp,
                    backgroundColor: @php echo json_encode($colorcode); @endphp,
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
            var pieChartCanvas = $('#salespieChart').get(0).getContext('2d')
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
                labels: @php echo json_encode($eachvendorname); @endphp,
                datasets: [{
                    data: @php echo json_encode($purchasefromsuppliers); @endphp,
                    backgroundColor: @php echo json_encode($purchasecolorcode); @endphp,
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
                labels: @php echo json_encode($productlists); @endphp,
                datasets: [{
                    data: @php echo json_encode($productsalescount); @endphp,
                    backgroundColor: @php echo json_encode($productcolorcode); @endphp,
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
            var pieChartCanvas = $('#productpieChart').get(0).getContext('2d')
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
                labels: @php echo json_encode($productsalesamountlist); @endphp,
                datasets: [{
                    data: @php echo json_encode($productsalsesamount); @endphp,
                    backgroundColor: @php echo json_encode($productamountcolorcode); @endphp,
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
            var pieChartCanvas = $('#grossingpieChart').get(0).getContext('2d')
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

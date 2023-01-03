<div class=" report-card card mt-3">
    <div class="card-header">
        <h2>Service Billing Reports</h2>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs responsive-tabs" id="myTab" role="tablist">
            @if (Auth::user()->can('manage-sales'))
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="tab-1" data-toggle="tab" href="#servicetab1" role="tab"
                        aria-controls="servicetab1" aria-selected="true">Service Sales Report</a>
                </li>
            @endif

            @if (Auth::user()->can('manage-credit-note'))
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="tab-2" data-toggle="tab" href="#servicetab2" role="tab"
                        aria-controls="servicetab2" aria-selected="false">Service Sales Return Report</a>
                </li>
            @endif


            @if (Auth::user()->can('manage-purchases'))
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="tab-3" data-toggle="tab" href="#servicetab3" role="tab"
                        aria-controls="servicetab3" aria-selected="false">Service Purchase Report</a>
                </li>
            @endif

            @if (Auth::user()->can('manage-debit-note'))
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="tab-4" data-toggle="tab" href="#servicetab4" role="tab"
                        aria-controls="servicetab4" aria-selected="false">Service Purchase Return Report</a>
                </li>
            @endif
        </ul>
        <div class="tab-content" id="myTabContent">
            @if (Auth::user()->can('manage-sales'))
                <div class="tab-pane fade show active" id="servicetab1" role="tabpanel"
                aria-labelledby="tab-1">
                    <div class="sub-tabs-head">
                        <h3>Service Sales Report</h3>
                        <ul class="nav nav-tabs sub-tabs" id="custom-tabs-two-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="servicesalestoday-tab" data-toggle="pill"
                                    href="#servicesalestoday" role="tab" aria-controls="servicesalestoday"
                                    aria-selected="false">Today</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="servicesalesmonth-tab" data-toggle="pill"
                                    href="#servicesalesmonth" role="tab" aria-controls="servicesalesmonth"
                                    aria-selected="true">This Month</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="servicesalesyear-tab" data-toggle="pill"
                                    href="#servicesalesyear" role="tab" aria-controls="servicesalesyear"
                                    aria-selected="false">This Year</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content sub-tab-content" id="custom-tabs-two-tabContent">
                        <div class="tab-pane fade" id="servicesalesyear" role="tabpanel"
                            aria-labelledby="servicesalesyear-tab">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <!-- small box -->
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3>{{ $servicesalesbillcount }}</h3>

                                            <p>Total Service Sales Bills Count</p>
                                            <h4>Rs.{{ $totservicesalesbillamt }}</h4>

                                            <p>Total Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('service_sales.index') }}"
                                            class="small-box-footer">View All Service Sales
                                            Bills <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-md-6">
                                    <!-- small box -->
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3>{{ $appservicesalesbillcount }}</h3>

                                            <p>Approved Service Sales Bills</p>
                                            <h4>Rs.{{ $totappservicesalesbillamt }}</h4>

                                            <p>Approved Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('service_sales.index') }}"
                                            class="small-box-footer">View Approved
                                            Service Sales
                                            Bills <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-md-6">
                                    <!-- small box -->
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3>{{ $unappservicesalesbillcount }}</h3>

                                            <p>Unapproved Service Sales Bills</p>
                                            <h4>Rs.{{ $totunappservicesalesbillamt }}
                                            </h4>

                                            <p>Unapproved Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('unapprovedServiceBills') }}"
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
                                            <h3>{{ $cancelledservicesalesbillcount }}
                                            </h3>

                                            <p>Cancelled Service Sales Bills</p>
                                            <h4>Rs.{{ $totcancelledappservicesalesbillamt }}
                                            </h4>

                                            <p>Cancelled Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('cancelledServiceBills') }}"
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
                                        <canvas id="servicebarChart"
                                            style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="tab-pane fade" id="servicesalesmonth" role="tabpanel"
                            aria-labelledby="servicesalesmonth-tab">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3>{{ $monthlyservicesalesbillcount }}</h3>

                                            <p>Total Service Sales Bills Count</p>
                                            <h4>Rs.{{ $totmonthlyservicesalesbillamt }}</h4>

                                            <p>Total Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('service_sales.index') }}"
                                            class="small-box-footer">View All Service Sales Bills
                                            <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3>{{ $appmonthlyservicesalesbillcount }}</h3>

                                            <p>Approved Service Sales Bills Count</p>
                                            <h4>Rs.{{ $totappmonthlyservicesalesbillamt }}</h4>

                                            <p>Approved Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('service_sales.index') }}"
                                            class="small-box-footer">Approved Service Sales Bills
                                            <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3>{{ $unappmonthlyservicesalesbillcount }}</h3>

                                            <p>Unapproved Service Sales Bills</p>
                                            <h4>Rs.{{ $totunappmonthlyservicesalesbillamt }}
                                            </h4>

                                            <p>Unapproved Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('unapprovedServiceBills') }}"
                                            class="small-box-footer">Unapproved Sales Bills
                                            <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3>{{ $cancelledmonthlyservicesalesbillcount }}
                                            </h3>

                                            <p>Cancelled Service Sales Bills</p>
                                            <h4>Rs.{{ $totcancelledappmonthlyservicesalesbillamt }}
                                            </h4>

                                            <p>Cancelled Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('cancelledServiceBills') }}"
                                            class="small-box-footer">Cancelled Service Sales Bills
                                            <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade active show" id="servicesalestoday" role="tabpanel"
                            aria-labelledby="servicesalestoday-tab">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3>{{ $todayservicesalesbillcount }}</h3>

                                            <p>Total Service Sales Bills Count</p>
                                            <h4>Rs.{{ $tottodayservicesalesbillamt }}</h4>

                                            <p>Total Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('service_sales.index') }}"
                                            class="small-box-footer">View All Service Sales Bills
                                            <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3>{{ $apptodayservicesalesbillcount }}</h3>

                                            <p>Approved Service Sales Bills Count</p>
                                            <h4>Rs.{{ $totapptodayservicesalesbillamt }}</h4>

                                            <p>Approved Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('service_sales.index') }}"
                                            class="small-box-footer">Approved Service Sales Bills
                                            <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3>{{ $unapptodayservicesalesbillcount }}</h3>

                                            <p>Unapproved Service Sales Bills</p>
                                            <h4>Rs.{{ $totunapptodayservicesalesbillamt }}</h4>

                                            <p>Unapproved Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('unapprovedServiceBills') }}"
                                            class="small-box-footer">Unapproved Service Sales Bills
                                            <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3>{{ $cancelledtodayservicesalesbillcount }}</h3>

                                            <p>Cancelled Service Sales Bills</p>
                                            <h4>Rs.{{ $totcancelledapptodayservicesalesbillamt }}
                                            </h4>

                                            <p>Cancelled Service Sales Bills Amount</p>
                                        </div>
                                        <a href="{{ route('cancelledServiceBills') }}"
                                            class="small-box-footer">Cancelled Service Sales Bills
                                            <i class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->can('manage-credit-note'))
                <div class="tab-pane fade" id="servicetab2" role="tabpanel" aria-labelledby="tab-2">
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
                                        <a href="{{ route('service_sales.index') }}"
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
                            aria-labelledby="servicesalesmonth-tab">
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
                            aria-labelledby="servicesalestoday-tab">
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
                <div class="tab-pane fade" id="servicetab3" role="tabpanel" aria-labelledby="tab-3">
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
                            aria-labelledby="servicesalesmonth-tab">
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
                            aria-labelledby="servicesalestoday-tab">
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
                <div class="tab-pane fade" id="servicetab4" role="tabpanel" aria-labelledby="tab-4">
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

@push('scripts')
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
                    data: @php echo json_encode($appservicesales); @endphp
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
                    data: @php echo json_encode($unappservicesales); @endphp
                },
            ]
        }

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#servicebarChart').get(0).getContext('2d')
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
@endpush

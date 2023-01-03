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
                    <h1>{{ $billingtype->billing_types }} Bills</h1>
                    <div class="btn-bulk">
                        @if ($billingtype->slug == 'debit-note' || $billingtype->slug == 'credit-note')
                            <a href="{{ route('billings.unapproved', $billingtype->id) }}"
                                class="global-btn">Unapproved {{ $billingtype->billing_types }} Bills</a> <a
                                href="{{ route('billings.cancelled', $billingtype->id) }}" class="global-btn">Cancelled
                                {{ $billingtype->billing_types }} Bills</a>
                            @if ($billingtype->slug != 'quotation') <a href="{{ route('billings.materialized', $billingtype->id) }}" class="global-btn">Materialized {{ $billingtype->billing_types }} Bills</a>  <a href="{{ route('billings.cbms', $billingtype->id) }}" class="global-btn">CBMS Materialized {{ $billingtype->billing_types }} Bills </a>@endif
                        @else
                            @php
                                $slug = $billingtype->slug;
                                $explode = explode('-', $slug);
                                //   dd($explode);
                                $combine = '';
                                $count = count($explode);
                                for ($x = 0; $x < $count; $x++) {
                                    $combine = $combine . $explode[$x];
                                }
                                $url = 'billings.' . $combine . 'create';
                            @endphp
                            <a href="{{ route($url) }}" class="global-btn">Create
                                {{ $billingtype->billing_types }}</a> <a
                                href="{{ route('billings.unapproved', $billingtype->id) }}"
                                class="global-btn">Unapproved {{ $billingtype->billing_types }} Bills</a> <a
                                href="{{ route('billings.cancelled', $billingtype->id) }}"
                                class="global-btn">Cancelled {{ $billingtype->billing_types }} Bills</a>
                            @if ($billingtype->slug != 'quotation') <a href="{{ route('billings.materialized', $billingtype->id) }}" class="global-btn">Materialized {{ $billingtype->billing_types }} Bills</a>  <a href="{{ route('billings.cbms', $billingtype->id) }}" class="global-btn">CBMS Materialized {{ $billingtype->billing_types }} Bills </a>@endif
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content mt-3">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="col-sm-12">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="col-sm-12">
                            <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h2>Generate Report</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('billings.extra') }}" method="GET">
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
                                            <input type="hidden" name="billing_type_id" value="{{ $billing_type_id }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Ending date</label>
                                            <input type="text" name="ending_date" class="form-control enddate"
                                                id="ending_date" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary">Generate Report</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="btn-bulk">
                        <a href="javascript:void(0)" class="global-btn csvbilled">Export (CSV)</a>
                        <form style="display: inline;" id="exportcsv"
                            action="{{ route('exportfiltersalesBills', ['id' => $id, 'start_date' => $start_date, 'end_date' => $end_date, 'billingtype_id' => $billingtype->id]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="selectedcsvid" class="selectedcsvid">
                        </form>
                        <a href="javascript:void(0)" class="global-btn pdfbilled" style="margin-left:5px;">Export (PDF)</a>
                        <form style="display: inline;" id="exportpdf"
                            action="{{ route('pdf.generateSalesBillingReport', ['id' => $id, 'starting_date' => $starting_date, 'ending_date' => $ending_date, 'billingtype_id' => $billingtype->id]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="selectedid" class="selectedid">
                        </form>
                    </div>
                    <div class="card mt">
                        <div class="card-header">
                            <h2>{{ $billingtype->billing_types }} Bills</h2>
                        </div>
                        <div class="card-body mid-body">
                            <h4>From {{ $starting_date }} to {{ $ending_date }}</h4>
                            <form action="" class="form-center">
                                @csrf
                                <div class="form-group" style="margin-bottom:0;">
                                    <input type="checkbox" name="select-all" class="select_all" value="all"> Select
                                    All
                                </div>
                                <div class="btn-bulk">
                                    <button type="submit" name="submit" class="global-btn clicked">
                                        Unapprove Selected</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="filter">
                                <form action="{{ route('generateBillingReport', ['id' => $id, 'starting_date' => $starting_date, 'ending_date' => $ending_date, 'billing_type_id' => $billingtype->id]) }}" method="GET">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8 pr-0">
                                            <select name="number_to_filter" class="form-control">
                                                <option value="5" {{ $number == 5 ? 'selected' : '' }}>5</option>
                                                <option value="10" {{ $number == 10 ? 'selected' : '' }}>10
                                                </option>
                                                <option value="20" {{ $number == 20 ? 'selected' : '' }}>20
                                                </option>
                                                <option value="50" {{ $number == 50 ? 'selected' : '' }}>50
                                                </option>
                                                <option value="100" {{ $number == 100 ? 'selected' : '' }}>100
                                                </option>
                                                <option value="250" {{ $number == 250 ? 'selected' : '' }}>250
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="global-btn"
                                                name="approved">Filter</button>
                                        </div>
                                    </div>
                                </form>
                                <form class="form-inline" action="{{ route('billings.search', $billingtype->id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="form-group mx-sm-3">
                                        <label for="search" class="sr-only">Search</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                            placeholder="Search">
                                    </div>
                                    <button type="submit" class="btn btn-primary icon-btn"><i
                                            class="fa fa-search"></i></button>
                                </form>
                            </div>
                            <div class="table-responsive mt">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Select</th>
                                            <th class="text-nowrap">Billing Type</th>
                                            @if ($billingtype->id == 1 || $billingtype->id == 6 || $billingtype->id == 7)
                                                <th class="text-nowrap">Customer</th>
                                            @endif
                                            @if ($billingtype->id == 2 || $billingtype->id == 3 || $billingtype->id == 4 || $billingtype->id == 5)
                                                <th class="text-nowrap">Supplier</th>
                                            @endif
                                            <th class="text-nowrap">Reference No</th>
                                            <th class="text-nowrap">Transaction No</th>
                                            @if ($billingtype->id == 2 || $billingtype->id == 5)
                                                <th class="text-nowrap">Party Bill No</th>
                                            @else
                                                <th class="text-nowrap">VAT Bill No</th>
                                            @endif
                                            <th class="text-nowrap">Bill Date</th>
                                            <th class="text-nowrap">Grand Total</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($billings as $billing)
                                            <tr>
                                                <td>
                                                    <input name='select[]' type='checkbox' class='select'
                                                        value='{{ $billing->id }}'>
                                                </td>
                                                <td>{{ $billing->billing_types->billing_types }}</td>
                                                @if ($billing->billing_type_id == 1 || $billing->billing_type_id == 6 || $billing->billing_type_id == 7)
                                                    <td>{{ $billing->client_id == null ? '-' : $billing->client->name }}
                                                    </td>
                                                @endif
                                                @if ($billing->billing_type_id == 2 || $billing->billing_type_id == 3 || $billing->billing_type_id == 4 || $billing->billing_type_id == 5)
                                                    <td>{{ $billing->vendor_id == null ? '-' : $billing->suppliers->company_name }}
                                                    </td>
                                                @endif
                                                <td>{{ $billing->reference_no }}</td>
                                                <td>{{ $billing->transaction_no }}</td>
                                                <td>{{ $billing->ledger_no }}</td>
                                                <td>{{ $billing->nep_date }}</td>
                                                <td>Rs.{{ $billing->grandtotal }}</td>
                                                <td style="width: 120px;">
                                                    <div class="btn-bulk justify-content-center">
                                                        <a href="{{ route('billing.print', $billing->id) }}" class="btn btn-secondary icon-btn btnprn" title="Print" ><i class="fa fa-print"></i> </a>
                                                        @php
                                                        $showurl = route('billings.show', $billing->id);
                                                        $editurl = route('billings.edit', $billing->id);
                                                        $billingtype = $billing->billing_type_id;
                                                        $statusurl = route('billings.status', [$billing->id, $billingtype]);
                                                        $cancellationurl = route('billings.cancel', $billingtype);
                                                        if ($billing->status == 1) {
                                                            $btnname = 'fa fa-thumbs-down';
                                                            $btnclass = 'btn btn-secondary icon-btn';
                                                            $title = 'Disapprove';
                                                        } else {
                                                            $btnname = 'fa fa-thumbs-up';
                                                            $btnclass = 'btn btn-secondary icon-btn';
                                                            $title = 'Approve';
                                                        }
                                                        $csrf_token = csrf_token();
                                                        if ($billingtype == 2) {
                                                            $debitnoteurl = route('billings.debitnotecreate', $billing->id);
                                                            $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                        <a href='$debitnoteurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Create Debit Note'><i class='fas fa-credit-card'></i></a>
                                                                        <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                                                        <form action='$statusurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                            <button type='submit' name = '$title' class='btn $btnclass btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                                                        </form>
                                                                        <!-- Modal -->
                                                                            <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                <div class='modal-dialog' role='document'>
                                                                                <div class='modal-content'>
                                                                                    <div class='modal-header'>
                                                                                    <h5 class='modal-title' id='exampleModalLabel'>Billing Cancellation</h5>
                                                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                        <span aria-hidden='true'>&times;</span>
                                                                                    </button>
                                                                                    </div>
                                                                                    <div class='modal-body'>
                                                                                        <p>Please give reason for Cancellation</p>
                                                                                        <hr>
                                                                                        <form action='$cancellationurl' method='POST'>
                                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                                            <input type='hidden' name='billing_id' value='$billing->id'>
                                                                                            <div class='form-group'>
                                                                                                <label for='reason'>Reason:</label>
                                                                                                <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                                                            </div>
                                                                                            <div class='form-group'>
                                                                                                <label for='description'>Description: </label>
                                                                                                <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                                                            </div>
                                                                                            <button type='submit' class='btn btn-danger'>Submit</button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                        ";
                                                        } elseif ($billingtype == 1) {
                                                            $creditnoteurl = route('billings.creditnotecreate', $billing->id);
                                                            $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                        <a href='$creditnoteurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Create Credit Note'><i class='fas far fa-credit-card'></i></a>
                                                                        <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                                                        <form action='$statusurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                            <button type='submit' name = '$title' class='btn $btnclass btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                                                        </form>
                                                                        <!-- Modal -->
                                                                            <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                <div class='modal-dialog' role='document'>
                                                                                <div class='modal-content'>
                                                                                    <div class='modal-header'>
                                                                                    <h5 class='modal-title' id='exampleModalLabel'>Billing Cancellation</h5>
                                                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                        <span aria-hidden='true'>&times;</span>
                                                                                    </button>
                                                                                    </div>
                                                                                    <div class='modal-body'>
                                                                                        <p>Please give reason for Cancellation</p>
                                                                                        <hr>
                                                                                        <form action='$cancellationurl' method='POST'>
                                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                                            <input type='hidden' name='billing_id' value='$billing->id'>
                                                                                            <div class='form-group'>
                                                                                                <label for='reason'>Reason:</label>
                                                                                                <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                                                            </div>
                                                                                            <div class='form-group'>
                                                                                                <label for='description'>Description: </label>
                                                                                                <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                                                            </div>
                                                                                            <button type='submit' class='btn btn-danger'>Submit</button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                        ";
                                                        } else {
                                                            $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                        <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                                                        <form action='$statusurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                            <button type='submit' name = '$title' class='btn $btnclass btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                                                        </form>
                                                                        <!-- Modal -->
                                                                            <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                <div class='modal-dialog' role='document'>
                                                                                <div class='modal-content'>
                                                                                    <div class='modal-header'>
                                                                                    <h5 class='modal-title' id='exampleModalLabel'>Billing Cancellation</h5>
                                                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                        <span aria-hidden='true'>&times;</span>
                                                                                    </button>
                                                                                    </div>
                                                                                    <div class='modal-body'>
                                                                                        <p>Please give reason for Cancellation</p>
                                                                                        <hr>
                                                                                        <form action='$cancellationurl' method='POST'>
                                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                                            <input type='hidden' name='billing_id' value='$billing->id'>
                                                                                            <div class='form-group'>
                                                                                                <label for='reason'>Reason:</label>
                                                                                                <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                                                            </div>
                                                                                            <div class='form-group'>
                                                                                                <label for='description'>Description: </label>
                                                                                                <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                                                            </div>
                                                                                            <button type='submit' class='btn btn-danger'>Submit</button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                        ";
                                                        }

                                                        echo $btn;
                                                    @endphp
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="9">No bills yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p class="text-sm">
                                                Showing <strong>{{ $billings->firstItem() }}</strong> to
                                                <strong>{{ $billings->lastItem() }} </strong> of <strong>
                                                    {{ $billings->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-7">
                                            <span class="pagination-sm m-0 float-right">{{ $billings->links() }}</span>
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
        <script>
            $(function() {
                var all = $('input.select_all');
                all.change(function() {
                    var select = $('input.select');
                    if (this.checked) {
                        select.prop('checked', true);
                    } else {
                        select.prop('checked', false);
                    }

                })
                var selectval = [];
                $('.clicked').click(function(event) {
                    event.preventDefault();
                    $('.select:checked').each(function(i) {
                        selectval[i] = $(this).val();
                    })

                    $.ajax({
                        url: "{{ route('billings.unapprove') }}",
                        type: 'POST',
                        dataType: 'json',
                        encode: true,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: 'application/json',
                        data: {
                            id: selectval,
                        },
                        beforeSend: function(xmlhttp) {
                            xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                            xmlhttp.setRequestHeader('Content-type',
                                'application/x-www-form-urlencoded; charset=UTF-8');
                        },
                        success: function(response) {
                            alert("You will now be redirected.");
                            window.location = "{{ route('billings.unapproved', $billingtype) }}";
                        },
                    });
                })
                $('.pdfbilled').click(function(event) {
                    var billedval = [];
                    event.preventDefault();
                    $('.select:checked').each(function(i) {
                        billedval[i] = $(this).val();
                    })
                    $('input.selectedid').val(billedval);
                    $("#exportpdf").submit();
                })
                $('.csvbilled').click(function(event) {
                    var billedval = [];
                    event.preventDefault();
                    $('.select:checked').each(function(i) {
                        billedval[i] = $(this).val();
                    })
                    $('input.selectedcsvid').val(billedval);
                    $("#exportcsv").submit();
                })
            })
        </script>

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

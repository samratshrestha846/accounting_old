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
                    <h1>Purchase Orders</h1>
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

                <div class="card">
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
                </div>

                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="col-md-12">
                                        <h2>Purchase Orders</h2>
                                    </div>
                                </div>
                                <div class="card-body mid-body">
                                    <form action="" class="form-center">
                                        @csrf
                                        <div class="form-group" style="margin-bottom:0;">
                                            <input type="checkbox" name="select-all" class="select_all" value="all">
                                            Select All
                                        </div>
                                        <div class="bulk-btn d-flex align-items-center">
                                            <button type="submit" name="submit" class="global-btn clicked">
                                                Unapprove Selected</button>
                                                <div class="filter m-0">
                                                    <form>
            
                                                    </form>
                                                    <div class="search-filter">
                                                        <form class="form-inline" action="{{ route('clientOrder.search') }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="form-group mx-sm-3">
                                                                <label for="search" class="sr-only">Search</label>
                                                                <input type="text" class="form-control" id="search" name="search"
                                                                    placeholder="Search">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary icon-btn btn-sm"><i
                                                                    class="fa fa-search"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-body">
                                        <table class="table table-bordered data-table text-center global-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Select</th>
                                                    <th class="text-nowrap">Supplier</th>
                                                    <th class="text-nowrap">Purchase Order No</th>
                                                    <th class="text-nowrap">Bill Date</th>
                                                    <th class="text-nowrap">Grand Total</th>
                                                    <th class="text-nowrap">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($purchaseOrders as $purchaseOrder)
                                                    <tr>
                                                        <td>
                                                            <input name='select[]' type='checkbox' class='select'
                                                                value='{{ $purchaseOrder->id }}'>
                                                        </td>
                                                        <td>{{ $purchaseOrder->vendor_id == null ? '-' : $purchaseOrder->suppliers->company_name }}
                                                        </td>
                                                        <td>{{ $purchaseOrder->purchase_order_no }}</td>
                                                        <td>
                                                            {{ $purchaseOrder->nep_date }} (in B.S) <br>
                                                            {{ $purchaseOrder->eng_date }} (in A.D)
                                                        </td>
                                                        <td>Rs.{{ $purchaseOrder->grandtotal }}</td>
                                                        <td>
                                                            <div class="btn-bulk">
                                                                <a href="{{ route('pdf.generatePurchaseOrder', $purchaseOrder->id) }}" class="btn btn-secondary icon-btn btnprn" title="Print" ><i class="fa fa-print"></i> </a>
                                                                @php
                                                                    $showurl = route('purchaseOrder.show', $purchaseOrder->id);
                                                                    $editurl = route('purchaseOrder.edit', $purchaseOrder->id);
                                                                    if ($purchaseOrder->converted == 0) {
                                                                        $converturl = route('convertToPurchase', $purchaseOrder->id);
                                                                    }
                                                                    $statusurl = route('purchaseOrderStatus', $purchaseOrder->id);
                                                                    $cancellationurl = route('purchaseOrderCancel', $purchaseOrder->id);
                                                                    if ($purchaseOrder->status == 1) {
                                                                        $btnname = 'fa fa-thumbs-down';
                                                                        $btnclass = 'btn btn-primary icon-btn';
                                                                        $title = 'Disapprove';
                                                                    } else {
                                                                        $btnname = 'fa fa-thumbs-up';
                                                                        $btnclass = 'btn btn-primary icon-btn';
                                                                        $title = 'Approve';
                                                                    }
                                                                    $csrf_token = csrf_token();

                                                                    if ($purchaseOrder->converted == 0) {
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
                                                                                                <h5 class='modal-title' id='exampleModalLabel'>purchaseOrder Cancellation</h5>
                                                                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                    <span aria-hidden='true'>&times;</span>
                                                                                                </button>
                                                                                                </div>
                                                                                                <div class='modal-body'>
                                                                                                    <p>Please give reason for Cancellation</p>
                                                                                                    <hr>
                                                                                                    <form action='$cancellationurl' method='POST'>
                                                                                                    <input type='hidden' name='_token' value='$csrf_token'>
                                                                                                        <input type='hidden' name='purchase_order_id' value='$purchaseOrder->id'>
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
                                                                                    <form action='$statusurl' method='POST' style='display:inline-block'>
                                                                                    <input type='hidden' name='_token' value='$csrf_token'>
                                                                                        <button type='submit' name = '$title' class='btn $btnclass btn-sm mt-1' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                                                                    </form>
                                                                                    <!-- Modal -->
                                                                                        <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                            <div class='modal-dialog' role='document'>
                                                                                            <div class='modal-content'>
                                                                                                <div class='modal-header'>
                                                                                                <h5 class='modal-title' id='exampleModalLabel'>purchaseOrder Cancellation</h5>
                                                                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                    <span aria-hidden='true'>&times;</span>
                                                                                                </button>
                                                                                                </div>
                                                                                                <div class='modal-body'>
                                                                                                    <p>Please give reason for Cancellation</p>
                                                                                                    <hr>
                                                                                                    <form action='$cancellationurl' method='POST'>
                                                                                                    <input type='hidden' name='_token' value='$csrf_token'>
                                                                                                        <input type='hidden' name='purchase_order_id' value='$purchaseOrder->id'>
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
                                                    <tr>
                                                        <td colspan="6">No purchase orders.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="text-sm">
                                                        Showing
                                                        <strong>{{ $purchaseOrders->firstItem() }}</strong>
                                                        to
                                                        <strong>{{ $purchaseOrders->lastItem() }} </strong> of
                                                        <strong>
                                                            {{ $purchaseOrders->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $purchaseOrders->links() }}</span>
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
                    url: "{{ route('purchaseOrderUnapprove') }}",
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
                        window.location = "{{ route('purchaseOrder.index') }}";
                    },
                });
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

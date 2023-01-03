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
                    <h1>Unapproved Purchase Orders</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('purchaseOrder.index') }}" class="global-btn">View Purchase
                            Orders</a>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="col-sm-12">
                        <div class="alert  alert-success alert-dismissible fade show" role="alert">
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
                        <h2>Searched Unapproved Purchase Orders</h2>
                    </div>
                    <div class="ibox">
                        <div class="ibox-body">
                            <div class="card-body mid-body">
                                <form action="" class="form-center">
                                    @csrf
                                    <div class="form-group" style='margin-bottom:0;'>
                                        <input type="checkbox" name="select-all" class="select_all" value="all">
                                        Select All
                                    </div>
                                    <div class="bulk-btn">
                                        <button type="submit" name="submit" class="global-btn clicked">
                                            Approve Selected</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="m-0 float-right">
                                            <form class="form-inline"
                                                action="{{ route('purchaserOrder.unapprovesearch') }}" method="POST">
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

                                    <div class="col-md-12 table-responsive mt">
                                        <table class="table table-bordered data-table text-center">
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
                                                            <div class="btn-bulk" style="justify-content: center;">
                                                                <a href="{{ route('pdf.generatePurchaseOrder', $purchaseOrder->id) }}" class="btn btn-secondary btnprn" title="Print" ><i class="fa fa-print"></i> </a>
                                                            @php
                                                                $showurl = route('purchaseOrder.show', $purchaseOrder->id);
                                                                $editurl = route('purchaseOrder.edit', $purchaseOrder->id);
                                                                $statusurl = route('purchaseOrderStatus', $purchaseOrder->id);
                                                                $cancellationurl = route('purchaseOrderCancel', $purchaseOrder->id);
                                                                if ($purchaseOrder->status == 1) {
                                                                    $btnname = 'fa fa-thumbs-down';
                                                                    $btnclass = 'btn-info';
                                                                    $title = 'Disapprove';
                                                                } else {
                                                                    $btnname = 'fa fa-thumbs-up';
                                                                    $btnclass = 'btn-info';
                                                                    $title = 'Approve';
                                                                }
                                                                $csrf_token = csrf_token();

                                                                $btn = "<a href='$showurl' class='edit btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                        <a href='$editurl' class='edit btn btn-secondary btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                                                        <button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                                                        <form action='$statusurl' method='POST' style='display:inline-block'>
                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                            <button type='submit' name = '$title' class='btn $btnclass btn-secondary btn-sm ml-1 text-light' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
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
                                                                                            <input type='hidden' name='purchaseOrder_id' value='$purchaseOrder->id'>
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

                                                                echo $btn;
                                                            @endphp
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="6">No unapproved purchase orders.</td></tr>
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
                    url: "{{ route('purchaseOrderApprove') }}",
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
@endpush

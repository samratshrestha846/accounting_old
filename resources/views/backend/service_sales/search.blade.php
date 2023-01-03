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
                    <h1 class="m-0">Search Results => Service Sale Bills </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('service_sales.create') }}" class="global-btn">Create Sales Bills</a>
                        <a href="#" class="global-btn">Unapproved Sales Bills</a>
                        <a href="#" class="global-btn">Cancelled Sales Bills</a>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
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

                <div class="card mt">
                    <div class="card-body mid-body">
                        <form action="" class="form-center">
                            @csrf
                            <div class="form-group" style="margin-bottom:0;">
                                <input type="checkbox" name="select-all" class="select_all" value="all">
                                Select All
                            </div>
                            <div class="btn-bulk">
                                <button type="submit" name="submit" class="global-btn clicked">
                                    Unapprove Selected</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="filter">
                            <form class="form-inline" action="{{ route('searchResults') }}" method="POST">
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
                        <div class="table-responsive mt">
                            <table class="table table-bordered data-table text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-nowrap">Select</th>
                                        <th class="text-nowrap">Customer</th>
                                        <th class="text-nowrap">Reference No</th>
                                        <th class="text-nowrap">Transaction No</th>
                                        <th class="text-nowrap">VAT Bill No</th>
                                        <th class="text-nowrap">Bill Date</th>
                                        <th class="text-nowrap">Grand Total</th>
                                        <th class="text-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($billings as $billing)
                                        <tr>
                                            <td>
                                                <input name='select[]' type='checkbox' class='select' value='{{ $billing->id }}'>
                                            </td>
                                            <td>
                                                {{ $billing->client->name }}
                                            </td>
                                            <td>{{ $billing->reference_no }}</td>
                                            <td>{{ $billing->transaction_no }}</td>
                                            <td>{{ $billing->ledger_no }}</td>
                                            <td>{{ $billing->nep_date }}</td>
                                            <td>Rs.{{ $billing->grandtotal }}</td>
                                            <td style="width: 120px;">
                                                <div class="btn-bulk justify-content-center">

                                                    <a href="{{ route('serviceSalesBillPrint', $billing->id) }}" class="btn btn-secondary icon-btn btnprn" title="Print" ><i class="fa fa-print"></i> </a>
                                                    <a href="{{ route('service_sales.show', $billing->id) }}" class="btn btn-primary icon-btn btn-sm"  data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>
                                                    <a href="{{ route('service_sales.edit', $billing->id) }}" class="btn btn-secondary icon-btn btn-sm"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                                    <button type="button" class="btn btn-primary icon-btn btn-sm" data-toggle="modal" data-target="#cancellation" data-toggle="tooltip" data-placement="top" title="Cancel"><i class="fa fa-ban"></i></button>
                                                    <form action="#" method="POST" style="display:inline-block; padding:0;" class="btn">
                                                        @csrf
                                                        @method("POST")
                                                        <button type="submit" name="unapprove" class="btn icon-btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Unapprove"><i class="fas fa-thumbs-up"></i></button>
                                                    </form>
                                                    <!-- Modal -->
                                                        <div class="modal fade text-left" id="cancellation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Billing Cancellation</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Please give reason for Cancellation</p>
                                                                    <hr>
                                                                    <form action="#" method="POST">
                                                                        @csrf
                                                                        @method("POST")
                                                                        <div class="form-group">
                                                                            <label for="reason">Reason:</label>
                                                                            <input type="text" name="reason" id="reason" class="form-control" placeholder="Enter Reason for Cancellation" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="description">Description: </label>
                                                                            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Enter Detailed Reason" required></textarea>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-danger">Submit</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">No any bills yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-sm">
                                            Showing <strong>{{ $billings->firstItem() }}</strong> to
                                            <strong>{{ $billings->lastItem() }} </strong> of <strong>
                                                {{ $billings->total() }}</strong>
                                            entries
                                            <span> | Takes
                                                <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                seconds to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
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
<script type="text/javascript">
    $(document).ready(function() {
        $('.btnprn').printPage();
    });
</script>
@endpush

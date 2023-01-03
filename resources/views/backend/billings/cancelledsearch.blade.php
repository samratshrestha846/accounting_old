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
                    @if ($billingtype->slug == 'debit-note' || $billingtype->slug == 'credit-note')
                        <h1>Search Cancelled {{ $billingtype->billing_types }}</h1>
                    @else
                        <h1>Cancelled {{ $billingtype->billing_types }}</h1>
                    @endif
                    <div class="btn-bulk">
                        @if ($billingtype->slug == 'debit-note' || $billingtype->slug == 'credit-note')
                            <a href="{{ route('billings.unapproved', $billingtype->id) }}"
                                class="global-btn">Unapproved {{ $billingtype->billing_types }} Bills</a> <a
                                href="{{ route('billings.cancelled', $billingtype->id) }}" class="global-btn">Cancelled
                                {{ $billingtype->billing_types }} Bills</a>
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
                        @endif
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="m-0 float-right">
                                    <form class="form-inline"
                                        action="{{ route('billings.cancelledsearch', $billingtype->id) }}" method="POST">
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
                            </div>
                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
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
                                                            $reviveurl = route('billings.revive', $billingtype);
                                                            $csrf_token = csrf_token();
                                                            if ($billing->status == 1) {
                                                                $btnname = 'fa fa-thumbs-down';
                                                                $btnclass = 'btn btn-secondary icon-btn';
                                                                $title = 'Disapprove';
                                                                $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                            <form action='$statusurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                                <button type='submit' name = '$title' class='btn $btnclass btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                                                            </form>
                                                                            <form action='$reviveurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                            <input type='hidden' name='billing_id' value='$billing->id'>
                                                                                <button type='submit' class='btn btn-primary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
                                                                            </form>
                                                                        ";
                                                            } else {
                                                                $btnname = 'fa fa-thumbs-up';
                                                                $btnclass = 'btn btn-primary icon-btn';
                                                                $title = 'Approve';
                                                                $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                            <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                                                            <form action='$statusurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                                <button type='submit' name = '$title' class='btn $btnclass btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                                                            </form>
                                                                            <form action='$reviveurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                            <input type='hidden' name='billing_id' value='$billing->id'>
                                                                                <button type='submit' class='btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
                                                                            </form>
                                                                        ";
                                                            }
                                                            echo $btn;
                                                        @endphp
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="8">No bills yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
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
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $billings->links() }}</span>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('.btnprn').printPage();
    });
</script>
@endpush

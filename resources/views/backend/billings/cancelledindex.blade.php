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
                        <h1 class="m-0">Cancelled {{ $billingtype->billing_types }}
                        </h1>
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

                            //   dd($url);

                        @endphp
                        <h1 class="m-0">Cancelled {{ $billingtype->billing_types }} </h1>
                    @endif
                    <div class="btn-bulk">
                        @if ($billingtype->slug == 'debit-note' || $billingtype->slug == 'credit-note')
                            <a href="{{ route('billings.unapproved', $billingtype->id) }}"
                                class="global-btn">Unapproved {{ $billingtype->billing_types }}
                                Bills</a> <a href="{{ route('billings.cancelled', $billingtype->id) }}"
                                class="global-btn">Cancelled {{ $billingtype->billing_types }} Bills</a>
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

                                //   dd($url);

                            @endphp
                            <a href="{{ route($url) }}" class="global-btn">Create
                                {{ $billingtype->billing_types }}</a> <a
                                href="{{ route('billings.unapproved', $billingtype->id) }}"
                                class="global-btn">Unapproved {{ $billingtype->billing_types }}
                                Bills</a> <a href="{{ route('billings.cancelled', $billingtype->id) }}"
                                class="global-btn">Cancelled {{ $billingtype->billing_types }}
                                Bills</a>


                        @endif
                        &nbsp;<a href="#" class="btn btn-primary" style="" disable>Total:Rs. {{number_format($total_sum,2)}}</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-md-12">
                                <div class="m-0 float-right">
                                    <form class="form-inline"
                                        action="{{ route('billings.cancelledsearch', $billingtype->id) }}" method="POST">
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
                            </div> --}}
                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center" id="myTable">
                                    {{-- <thead class="topsearch">
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
                                            <th class="text-nowrap"></th>
                                        </tr>
                                    </thead> --}}
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
                                    {{-- <tbody>
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
                                                <td>
                                                    <div class="btn-bulk justify-content-center">
                                                        <a href="{{ route('billing.print', $billing->id) }}" class="btn btn-secondary btnprn" title="Print" ><i class="fa fa-print"></i> </a>
                                                    @php
                                                        $showurl = route('billings.show', $billing->id);
                                                        $editurl = route('billings.edit', $billing->id);
                                                        $billingtype = $billing->billing_type_id;
                                                        $statusurl = route('billings.status', [$billing->id, $billingtype]);
                                                        $reviveurl = route('billings.revive', $billingtype);
                                                        $csrf_token = csrf_token();
                                                        if ($billing->status == 1) {
                                                            $btnname = 'fa fa-thumbs-down';
                                                            $btnclass = 'btn-secondary';
                                                            $title = 'Disapprove';
                                                            $btn = "<a href='$showurl' class='edit btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                        <form action='$statusurl' method='POST' style='display:inline-block'>
                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                            <button type='submit' name = '$title' class='btn $btnclass btn-sm ml-1' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                                                        </form>
                                                                        <form action='$reviveurl' method='POST' style='display:inline-block'>
                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                        <input type='hidden' name='billing_id' value='$billing->id'>
                                                                            <button type='submit' class='btn btn-primary btn-sm text-light ml-1' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
                                                                        </form>
                                                                        ";
                                                        } else {
                                                            $btnname = 'fa fa-thumbs-up';
                                                            $btnclass = 'btn-primary';
                                                            $title = 'Approve';
                                                            $btn = "<a href='$showurl' class='edit btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                        <a href='$editurl' class='edit btn btn-secondary btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                                                        <form action='$statusurl' method='POST' style='display:inline-block'>
                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                            <button type='submit' name = '$title' class='btn $btnclass btn-sm ml-1' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                                                        </form>
                                                                        <form action='$reviveurl' method='POST' style='display:inline-block'>
                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                        <input type='hidden' name='billing_id' value='$billing->id'>
                                                                            <button type='submit' class='btn btn-secondary btn-sm text-light ml-1' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
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
                                    </tbody> --}}
                                </table>
                                {{-- <div class="mt-3">
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
                                </div> --}}
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
<script src="{{ asset('backend/dist/js/mousetrap/sales.js') }}"></script>
<script src="{{ asset('backend/dist/js/mousetrap/purchase.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.btnprn').printPage();
    });

    $('#myTable thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#myTable thead');

    $(function () {

        var billing_type_id = <?php echo json_encode($billing_type_id); ?>;
        var url = "{{route('billings.cancelled',':id')}}";
        var url = url.replace(':id',billing_type_id);
        if((billing_type_id == 1) || (billing_type_id == 6) || (billing_type_id == 7)){
            var clientorsupplier = 'client.name';
        }else{
            var clientorsupplier = 'suppliers.company_name';
        }

        var table = $('#myTable').DataTable({
            orderCellsTop: true,
                columnDefs: [
                    { width: 500, targets: 0 }
                ],
                fixedColumns: true,
                fixedHeader: true,
                initComplete: function () {
                    var api = this.api();

                    // For each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            // Set the header cell to contain the input element
                            var cell = $('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            var title = $(cell).text();
                            if(title != 'Action'){
                                $(cell).html('<input class="searchcolumn" type="text" placeholder="' + title + '" />');
                            }



                            // On every keypress in this input
                            $(
                                'input',
                                $('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('keyup change', function (e) {
                                    e.stopPropagation();

                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != ''
                                                ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                : '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();

                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });

                        $('.searchcolumn').each(function(k,v){
                            if($(this).attr('placeholder') == "Select"){
                                $(this).replaceWith('<input type="checkbox" name="select-all" class="select_all" value="all">Select All');
                            }


                        });


                },
            searchPanes: {
                    viewTotal: true
                },
            dom: 'Plfrtip',
            processing: false,
            orderCellsTop: true,
                columnDefs: [
                    { width: 100, targets: 0 }
                ],
                fixedColumns: true,
            serverSide: true,
            pageLength:25,
            ajax:url,
            columns: [

                {data: 'billing_types.billing_types'},
                {data: 'billing_type_id',name:clientorsupplier},
                {data: 'reference_no'},
                {data: 'transaction_no'},
                {data: 'ledger_no'},
                {data: 'nep_date'},
                {data: 'grandtotal'},

                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

    });
</script>
@endpush

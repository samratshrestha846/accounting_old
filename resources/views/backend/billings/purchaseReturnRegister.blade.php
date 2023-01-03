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
                    <h1>{{ $billingtype->billing_types }} Return Register Bills  </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('billings.report', $billing_type_id) }}" class="global-btn">View {{ $billingtype->billing_types }}</a>
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
                                <div style="display: flex;justify-content:flex-end;">
                                    <form class="form-inline" action="{{ route('purchaseReturnRegister.search') }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mb-2"><i
                                                class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered data-table text-center global-table" id="myTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Bill Date</th>
                                            <th class="text-nowrap">Supplier</th>
                                            <th class="text-nowrap">Supplier Adress</th>
                                            <th class="text-nowrap">PAN / VAT</th>
                                            <th class="text-nowrap">Reference No</th>
                                            <th class="text-nowrap">Transaction No</th>
                                            <th class="text-nowrap">Party Bill No</th>
                                            <th class="text-nowrap">Tax Amount</th>
                                            <th class="text-nowrap">Grand Total</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        @forelse ($billings as $billing)
                                            <tr>
                                                <td class="text-nowrap">
                                                    {{ $billing->nep_date }}(in B.S) <br>
                                                    {{ $billing->eng_date }}(in A.D)
                                                </td>
                                                <td class="text-nowrap">{{ $billing->suppliers->company_name }}</td>
                                                <td class="text-nowrap">{{ $billing->suppliers->company_address }}</td>
                                                <td class="text-nowrap">
                                                    @if ($billing->suppliers->pan_vat == null)
                                                        Not Provided
                                                    @else
                                                        {{ $billing->suppliers->pan_vat }}
                                                    @endif
                                                </td>
                                                <td class="text-nowrap">{{ $billing->reference_no }}</td>
                                                <td class="text-nowrap">{{ $billing->transaction_no }}</td>
                                                <td class="text-nowrap">{{ $billing->ledger_no }}</td>
                                                <td class="text-nowrap">Rs. {{ $billing->taxamount }}</td>
                                                <td class="text-nowrap">Rs. {{ $billing->grandtotal }}</td>
                                                <td class="text-nowrap">
                                                    @php
                                                        $showurl = route('billings.show',['billing'=>$billing->id,'bill_type'=>$billing->getTable()]);
                                                        $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>";

                                                        echo $btn;
                                                    @endphp
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="10">No return registers.</td></tr>
                                        @endforelse
                                    </tbody> --}}
                                </table>
                                {{-- <div class="mt-3">
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
                                </div> --}}
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
{{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}
<script src="{{asset('js/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('js/datatables/jszip.min.js') }}"></script>
<script src="{{asset('js/datatables/pdfmake.min.js') }}"></script>
<script src="{{asset('js/datatables/vfs_fonts.js') }}"></script>
<script src="{{asset('js/datatables/buttons.html5.min.js') }}"></script>
<script src="{{asset('js/datatables/buttons.print.min.js') }}"></script>
{{-- //startdatatable --}}
<script>


    $('#myTable thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#myTable thead');


        $(function () {

            var table = $('#myTable').DataTable({
                orderCellsTop: true,
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
                },
                searchPanes: {
                    viewTotal: true
                },
                dom: 'Blfrtip',

                buttons: [
                    {
                        extend: 'print',
                        customize: function ( win ) {
                            $(win.document.body)
                                .css( 'font-size', '10pt' )

                            $(win.document.body).find( 'table' )
                                .addClass( 'compact' )
                                .css( 'font-size', 'inherit' );
                        },
                        exportOptions: {
                            columns: function (idx, data, node) {
                                if (node.innerHTML == "Action")
                                    return false;
                                return true;
                           },
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4, 5 ,6,7,8]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4, 5 ,6,7,8]
                        }
                    },
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4, 5 ,6,7,8]
                        }
                    },

                ],

                processing: false,
                serverSide: true,
                pageLength:25,
                ajax:{
                url : "{{route('purchaseReturnRegister')}}",

                },


                columns: [
                    {data: 'bill_date'},
                    {data: 'suppliers.company_name'},
                    {data: 'suppliers.company_address'},
                    {data: 'suppliers.pan_vat'},
                    {data: 'reference_no'},
                    {data: 'transaction_no'},
                    {data: 'ledger_no'},
                    {data: 'taxamount'},
                    {data: 'grandtotal'},
                    {data: 'action'},

                ],

            });


            var z = $("div.dt-buttons");
            z.css("margin-bottom", "12px");
        });

    </script>
    {{-- //enddatatable --}}


@endpush

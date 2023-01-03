@extends('backend.layouts.app')
@push('css')
<style>
    .thead>tr>th{
        padding-right:5px !important;
    }
    </style>
@endpush
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="sec-header">
        <div class="container-fluid">
            <div class="sec-header-wrap ">
                <h1>Billing Credit </h1>
                <div class="btn-bulk">
                    <a href="#" disable="" class="btn btn-primary" style="display: inline-block;"> Total Credit:Rs. {{number_format($total_credit,2)}}</a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
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
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered data-table text-center" id="myTable">
                                <thead class="">


                                    <tr>
                                        <th class="text-nowrap">Refrence No</th>
                                        @if($billing_type_id == 2 || $billing_type_id == 5)
                                        <th class="text-nowrap">Supplier</th>
                                        @endif
                                        @if($billing_type_id == 1 || $billing_type_id == 6)
                                        <th class="text-nowrap">Client</th>
                                        @endif
                                        <th class="text-nowrap">Due English Date</th>
                                        <th class="text-nowrap">Due Nepali Date</th>
                                        <th class="text-nowrap">Total Amount</th>
                                        <th class="text-nowrap">Paid Amount</th>
                                        <th class="text-nowrap">Credit Amount</th>
                                        <th class="text-nowrap">Action</th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@stop
@push('scripts')
{{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
{{-- //startdatatable --}}
<script>


    $('#myTable thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#myTable thead');


        $(function () {

            var billing_type_id = @php echo json_encode($billing_type_id); @endphp;
            var is_service_sale = @php echo json_encode($is_service_sale); @endphp;
            var table = $('#myTable').DataTable({
                orderCellsTop: true,
                columnDefs: [
                    { width: 100, targets: 0 }
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
                            $(cell).html('<input type="text" class="searchcolumn" placeholder="' + title + '" />');

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
                // dom: 'BPlfrtip',

                // buttons: [
                //     'copy', 'csv', 'excel', 'pdf', 'print'
                // ],
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
                            columns: [ 0, 1, 2,3,4, 5 ,6]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4, 5 ,6]
                        }
                    },
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4, 5 ,6]
                        }
                    },

                ],

                processing: false,
                serverSide: true,
                pageLength:25,
                ajax:{
                url : "{{route('billing.billingcredits')}}",
                data:{billing_type_id:billing_type_id,is_service_sale:is_service_sale},
                },


                columns: [
                    {data: 'reference_no'},

                    {data: 'client_or_supplier'},
                    {data: 'due_date_eng'},
                    {data: 'due_date_nep'},
                    {data: 'grandtotal'},
                    {data: 'Payment_amount'},
                    {data: 'credit_amount'},
                    {data: 'action'},

                ],

            });

            table.columns().every( function() {
            var that = this;
                console.log(this.header());
            $('input', this.header()).on('keyup change', function() {
                if (that.search() !== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });
         });


            var z = $("div.dt-buttons");
            z.css("margin-bottom", "12px");
        });

    </script>
    {{-- //enddatatable --}}


@endpush

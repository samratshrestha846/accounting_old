@extends('backend.layouts.app')
@push('styles')
<style>
        *
    {
      box-sizing: border-box;
    }

    #myInput
    {
      background-image: url('/uploads/search.png');
      background-position: 10px 10px;
      background-repeat: no-repeat;
      width: 100%;
      font-size: 13px;
      padding: 12px 20px 12px 40px;
      border: 1px solid #e1e6eb;
      margin-bottom: 12px;
    }
</style>
@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Suppliers Ledger </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('vendors.index') }}" class="global-btn">View All Suppliers</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        {{-- <div class="btn-bulk mb-2 ml-3">
            <a href="{{route('supplierledgerexportexcel')}}" class="global-btn">Export (CSV)</a>

            <form style="display: inline;" id="exportpdf" action="{{route('supplierLedgersgeneratepdf')}}" method="POST">
                @csrf
                <button type="submit" class="global-btn pdfbilled" style="margin-left:5px;">Export
                    (PDF)</button>

            </form>
        </div> --}}
        <!-- Main content -->
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

                            {{-- <div class="col-md-4">
                                <form action="{{ route('supplierLedgers') }}" method="GET">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8 pr-0">
                                            <select name="number_to_filter" class="form-control">
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
                                                <option value="500" {{ $number == 500 ? 'selected' : '' }}>500
                                                </option>
                                                <option value="1000" {{ $number == 1000 ? 'selected' : '' }}>1000
                                                </option>
                                                <option value="1500" {{ $number == 1500 ? 'selected' : '' }}>1500
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="global-btn" name="approved">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div> --}}
                            <div class="col-md-4"></div>
                            {{-- <div class="col-md-4 text-right">
                                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for customer name.." title="Type in a name">
                            </div> --}}
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table text-center global-table" id="myTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Supplier Name</th>
                                            <th class="text-nowrap">Bill Amount</th>
                                            <th class="text-nowrap">Paid Amount</th>
                                            <th class="text-nowrap">Remaining Amount</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>

                                        @forelse ($vendors as $vendor)

                                            @php
                                            $servicePurchaseTotal = $vendor->servicePurchasebillgrandtotal($vendor->id);
                                            $servicePurchasepaidamount = $vendor->servicePurchasebillpaidamount($vendor->id);
                                            $servicePurchaseRemaining = $servicePurchaseTotal - $servicePurchasepaidamount;
                                            @endphp


                                            <tr>
                                                <td>{{ $vendor->company_name }}</td>
                                                <td>
                                                    Rs. {{ $vendor->purchaseBillings->sum('grandtotal') - $vendor->purchaseReturnBillings->sum('grandtotal') + $servicePurchaseTotal }}
                                                </td>
                                                <td>
                                                    @php

                                                        $total_paid_amount = 0;
                                                        $total_returned_amount = 0;
                                                        foreach ($vendor->purchaseBillings as $billing)
                                                        {


                                                            $paid_amount_sum = $billing->payment_infos->sum('total_paid_amount');
                                                            $total_paid_amount += $paid_amount_sum;
                                                        }
                                                        foreach ($vendor->purchaseReturnBillings as $returnedBilling)
                                                        {
                                                            $received_amount_sum = $returnedBilling->payment_infos->sum('total_paid_amount');
                                                            $total_returned_amount += $received_amount_sum;
                                                        }
                                                    @endphp
                                                    Rs. {{ $total_paid_amount - $total_returned_amount + $servicePurchasepaidamount }}
                                                </td>
                                                <td>
                                                    Rs. {{ ($vendor->purchaseBillings->sum('grandtotal') - $total_paid_amount) - ($vendor->purchaseReturnBillings->sum('grandtotal') - $total_returned_amount + $servicePurchaseRemaining) }}
                                                </td>
                                                <td>
                                                    <div class="btn-bulk justify-content-center">
                                                        <a href="{{ route('vendors.show', $vendor->id) }}" class="btn btn-primary icon-btn" title="View Supplier"><i class="fas fa-eye"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5">No any vendors.</td></tr>
                                        @endforelse
                                    </tbody> --}}
                                </table>
                                {{-- <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p class="text-sm">
                                                |Showing <strong>{{ $vendors->firstItem() }}</strong> to
                                                <strong>{{ $vendors->lastItem() }} </strong> of <strong>
                                                    {{ $vendors->total() }}</strong>
                                                entries|
                                            </p>
                                        </div>
                                        <div class="col-md-7">
                                            <span class="pagination-sm m-0 float-right">{{ $vendors->links() }}</span>
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
<script>
    function myFunction()
    {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++)
        {
            td = tr[i].getElementsByTagName("td")[0];
            if (td)
            {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1)
                {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

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
                            if(title != 'Action'){
                                $(cell).html('<input type="text" class="searchcolumn" placeholder="' + title + '" />');
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
                            columns: [ 0, 1, 2]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2]
                        }
                    },
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2]
                        }
                    },

                ],

                processing: false,
                serverSide: true,
                pageLength:25,
                ajax:{
                url : "{{route('supplierLedgers')}}",

                },


                columns: [
                    {data: 'company_name'},
                    {data: 'bill_amount'},
                    {data: 'paid_amount'},
                    {data: 'remaining_amount'},

                    {data: 'action'},

                ],

            });


            var z = $("div.dt-buttons");
            z.css("margin-bottom", "12px");
        });

    </script>
    {{-- //enddatatable --}}


@endpush

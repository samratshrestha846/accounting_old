@extends('backend.layouts.app')
@section('style')

@endsection
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="sec-header">
        <div class="container-fluid">
            <div class="sec-header-wrap">
                <h1>Customer Unpaid Ledger </h1>
                <div class="btn-bulk">
                    <a href="{{ route('client.index') }}" class="global-btn">View All Customers</a>
                </div>
                <!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
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
                            <table class="table table-bordered data-table text-center" id="myTable" style="white-space: nowrap;">
                                <thead class="topsearch">
                                    <tr class="">
                                        <th class="text-nowrap">Client Name</th>
                                        <th class="text-nowrap">Bill Amount</th>
                                        <th class="text-nowrap">Paid Amount</th>
                                        <th class="text-nowrap">Remaining Amount</th>
                                        <th class="text-nowrap">Reference no</th>
                                    </tr>
                                </thead>
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-nowrap">Client Name</th>
                                        <th class="text-nowrap">Bill Amount</th>
                                        <th class="text-nowrap">Paid Amount</th>
                                        <th class="text-nowrap">Remaining Amount</th>
                                        <th class="text-nowrap">Reference no</th>
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
<script>
     $(document).ready(function() {
        $('#myTable thead.topsearch th').each(function() {
            var title = $(this).text();
            if(title != ""){
             $(this).html('<input class="searchcolumn" type="text" placeholder="Search ' + title + '" />');
            }
        });
        });


        $(function () {

            var table = $('#myTable').DataTable({

                searchPanes: {
                    viewTotal: true
                },
                dom: 'Plfrtip',
                processing: false,
                columnDefs: [
                    { width: 100, targets: 0 },
                ],
                fixedColumns: true,
                serverSide: true,
                pageLength:25,
                ajax:"{{route('customersUnpaidLedgers')}}",
                columns: [
                    {data: 'client_name'},
                    {data: 'grandtotal'},
                    {data: 'total_paid_amount'},
                    {data: 'remaining_amount'},
                    {data: 'billing_id'},
                ],

            });

            table.columns().every( function() {
            var that = this;

            $('input', this.header()).on('keyup change', function() {
                if (that.search() !== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });
         });
        });
    </script>
@endpush

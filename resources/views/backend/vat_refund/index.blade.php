@extends('backend.layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="sec-header">
        <div class="container-fluid">
            <div class="sec-header-wrap">
                <h1>All Vat Refunds</h1>
                <div class="btn-bulk">
                    <a href="{{ route('vat_refund.create') }}" class="global-btn">Create New </a>


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

             <div class="ibox">
                <div class="row ibox-body">
                    <div class="col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>All Vat Refunds</h2>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt">
                                    <table class="table table-bordered data-table text-center" id="myTable">
                                        <thead class="topsearch">
                                            <tr class="search">

                                                <th class="text-nowrap">Fiscal Year</th>
                                                <th class="text-nowrap">Amount</th>
                                                <th class="text-nowrap">Due</th>
                                                <th class="text-nowrap">Total Amount</th>
                                                <th class="text-nowrap">Refunded</th>
                                                <th class="text-nowrap">Created At</th>
                                                <th class="text-nowrap"></th>

                                            </tr>
                                        </thead>
                                        <thead class="thead-light">
                                            <tr>

                                                <th class="text-nowrap">Fiscal Year</th>
                                                <th class="text-nowrap">Amount</th>
                                                <th class="text-nowrap">Due</th>
                                                <th class="text-nowrap">Total Amount</th>
                                                <th class="text-nowrap">Refunded</th>
                                                <th>Created At</th>
                                                <th class="text-nowrap">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                            </div>
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
             $(this).html('<input class="" style="width:60px" type="text" placeholder="Search ' + title + '" />');
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
                serverSide: true,
                ajax:"{{ route('vat_refund.index') }}",
                columns: [

                    {data: 'fiscal_year'},
                    {data: 'amount'},
                    {data: 'due'},
                    {data: 'total_amount'},
                    {data: 'isrefunded'},
                    {data: 'created_at'},
                    {data: 'action'},
                ],
            })

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

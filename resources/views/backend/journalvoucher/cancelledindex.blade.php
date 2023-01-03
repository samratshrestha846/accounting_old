@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Content Header (Page header) -->
            <div class="sec-header">
                <div class="sec-header-wrap">
                    <h1>Cancelled Journal Vouchers </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('journals.create') }}" class="global-btn">Entry New Journal</a> <a
                            href="{{ route('journals.index') }}" class="global-btn">View Journals</a>
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
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-md-12 ">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="{{ route('cancelledjournalvoucher.search') }}"
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
                            </div> --}}
                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="topsearch">
                                        <tr>
                                            <th class="text-nowrap">JV no.</th>
                                            <th class="text-nowrap">Entry Date</th>
                                            <th class="text-nowrap">Particulars</th>
                                            <th class="text-nowrap">Debit Amount</th>
                                            <th class="text-nowrap">Credit Amount</th>

                                            <th class="text-nowrap"></th>
                                        </tr>
                                    </thead>
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">JV no.</th>
                                            <th class="text-nowrap">Entry Date</th>
                                            <th class="text-nowrap">Particulars</th>
                                            <th class="text-nowrap">Debit Amount</th>
                                            <th class="text-nowrap">Credit Amount</th>

                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        @forelse ($journalvouchers as $journalvoucher)
                                            <tr>
                                                <td class="text-nowrap">{{ $journalvoucher->journal_voucher_no }}
                                                </td>
                                                <td class="text-nowrap">{{ $journalvoucher->entry_date_nepali }}
                                                </td>
                                                <td class="text-nowrap">
                                                    @php
                                                        $particulars = '';
                                                        foreach ($journalvoucher->journal_extras as $jextra) {
                                                            $particulars = $particulars . $jextra->child_account->title . '<br>';
                                                        }
                                                        echo $particulars;
                                                    @endphp
                                                </td>
                                                <td class="text-nowrap">
                                                    @php
                                                        $debit_amounts = '';
                                                        foreach ($journalvoucher->journal_extras as $jextra) {
                                                            if ($jextra->debitAmount == 0) {
                                                                $debit_amounts = $debit_amounts . '-' . '<br>';
                                                            } else {
                                                                $debit_amounts = $debit_amounts . 'Rs. ' . $jextra->debitAmount . '<br>';
                                                            }
                                                        }
                                                        echo $debit_amounts;
                                                    @endphp
                                                </td>
                                                <td class="text-nowrap">
                                                    @php
                                                        $credit_amounts = '';
                                                        foreach ($journalvoucher->journal_extras as $jextra) {
                                                            if ($jextra->creditAmount == 0) {
                                                                $credit_amounts = $credit_amounts . '-' . '<br>';
                                                            } else {
                                                                $credit_amounts = $credit_amounts . 'Rs. ' . $jextra->creditAmount . '<br>';
                                                            }
                                                        }
                                                        echo $credit_amounts;
                                                    @endphp
                                                </td>
                                                <td class="text-nowrap">
                                                    @php
                                                        if ($journalvoucher->status == '1') {
                                                            $status = 'Approved';
                                                        } else {
                                                            $status = 'Awaiting for Approval';
                                                        }
                                                        echo $status;
                                                    @endphp
                                                </td>
                                                <td class="text-nowrap">
                                                    <div class="btn-bulk justify-content-center">
                                                        <a href="{{ route('journals.print', $journalvoucher->id) }}" class="btn btn-secondary btnprn" title="Print" ><i class="fa fa-print"></i> </a>

                                                    @php
                                                        $showurl = route('journals.show', $journalvoucher->id);
                                                        $editurl = route('journals.edit', $journalvoucher->id);
                                                        $reviveurl = route('journals.revive', $journalvoucher->id);
                                                        $statusurl = route('journals.status', $journalvoucher->id);
                                                        $csrf_token = csrf_token();
                                                        if ($journalvoucher->status == 1) {
                                                            $btnname = 'fa fa-thumbs-down';
                                                            $btnclass = 'btn-info';
                                                            $title = 'Disapprove';
                                                            $btn = "<a href='$showurl' class='edit btn btn-primary btn-sm' data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                            <form action='$reviveurl' method='POST' style='display:inline-block'>
                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                                <button type='submit' class='btn btn-secondary btn-sm text-light ml-1' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
                                                                            </form>
                                                                            <form action='$statusurl' method='POST' style='display:inline-block'>
                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                                <button type='submit' name = '$title' class='btn $btnclass btn-primary ml-1 text-light btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                                                            </form>
                                                                            ";
                                                        } else {
                                                            $btnname = 'fa fa-thumbs-up';
                                                            $btnclass = 'btn-info';
                                                            $title = 'Approve';
                                                            $btn = "<a href='$showurl' class='edit btn btn-primary btn-sm' data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                        <a href='$editurl' class='edit btn btn-secondary btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                                                                <form action='$reviveurl' method='POST' style='display:inline-block'>
                                                                                <input type='hidden' name='_token' value='$csrf_token'>
                                                                                    <button type='submit' class='btn btn-primary btn-sm text-light ml-1' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
                                                                                </form>
                                                                                <form action='$statusurl' method='POST' style='display:inline-block'>
                                                                                <input type='hidden' name='_token' value='$csrf_token'>
                                                                                    <button type='submit' name = '$title' class='btn $btnclass btn-secondary ml-1 text-light btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                                                                </form>
                                                                                ";
                                                        }
                                                        echo $btn;
                                                    @endphp
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">No journals yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody> --}}
                                </table>
                                {{-- <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="text-sm">
                                                Showing <strong>{{ $journalvouchers->firstItem() }}</strong> to
                                                <strong>{{ $journalvouchers->lastItem() }} </strong> of <strong>
                                                    {{ $journalvouchers->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-8">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $journalvouchers->links() }}</span>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- /.content-wrapper -->
@endsection
@push('scripts')
<script type="text/javascript">
        $(document).ready(function() {
            $('.data-table thead.topsearch th').each(function() {
                var title = $(this).text();
                if(title != ""){
                $(this).html('<input class="searchcolumn" type="text" placeholder="Search ' + title + '" />');
                }
            });
        });
        $(function () {
            var table = $('.data-table').DataTable({
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
                ajax: "{{route('journals.cancelled')}}",
                columns: [
                    {data: 'journal_voucher_no', name: 'journal_voucher_no'},
                    {data: 'entry_date_nepali'},
                    {data: 'particulars', name: 'particulars', searchable: true},
                    {data: 'debit', name: 'debit'},
                    {data: 'credit', name: 'credit'},

                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                pages: 5,
                pageLength: 15
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
    $(document).ready(function(){
        $('.btnprn').printPage();
    });
</script>
@endpush

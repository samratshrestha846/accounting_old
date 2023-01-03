@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="container-fluid">
            <div class="sec-header">
                <div class="sec-header-wrap">
                    <h1>Unapproved Journal Vouchers</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('journals.create') }}" class="global-btn">Entry New Journal</a>
                        <a href="{{ route('journals.index') }}" class="global-btn">View Journals</a>
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
                    <div class="card-body mid-body">
                        <form action="" class="form-center">
                            @csrf
                            <div class="form-group" style="margin-bottom:0;">
                                <input type="checkbox" name="select-all" class="select_all" value="all"> Select All
                            </div>
                            <div class="bulk-btn">
                                <button type="submit" name="submit" class="global-btn clicked">Approve
                                    Selected</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-md-12 ">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="{{ route('unapprovejournalvoucher.search') }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-group mx-sm-3 ">
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
                                            <th class="text-nowrap"></th>
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
                                            <th class="text-nowrap">Select</th>
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
                                                <td class="text-nowrap"><input name='select[]' type='checkbox'
                                                        class='select' value='{{ $journalvoucher->id }}'></td>
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
                                                        $statusurl = route('journals.status', $journalvoucher->id);
                                                        $cancellationurl = route('journals.cancel', $journalvoucher->id);
                                                        if ($journalvoucher->status == 1) {
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
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <h5 class='modal-title' id='exampleModalLabel'>Journal Voucher Cancellation</h5>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <div class='modal-body'>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <p>Please give reason for Cancellation</p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <hr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <form action='$cancellationurl' method='POST'>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <input type='hidden' name='journalvoucher_id' value='$journalvoucher->id'>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class='form-group'>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label for='reason'>Reason:</label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class='form-group'>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label for='description'>Description: </label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <button type='submit' name='submit' class='btn btn-danger'>Submit</button>
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
                                            <tr>
                                                <td colspan="8">No journals yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody> --}}
                                </table>
                                {{-- <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-5">
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
                                        <div class="col-md-7">
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
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')

    <script>
        $(document).ready(function() {
            $('.data-table thead.topsearch th').each(function() {
                var title = $(this).text();
                if(title != ""){
                $(this).html('<input class="searchcolumn"  type="text" placeholder="Search ' + title + '" />');
                }
            });
        });
        $(function () {
            var table = $('.data-table').DataTable({
                searchPanes: {
                    viewTotal: true
                },
                dom: 'Plfrtip',
                columnDefs: [
                    { width: 100, targets: 0 }
                ],
                fixedColumns: true,
                processing: false,
                serverSide: true,
                ajax: "{{route('journals.unapproved')}}",
                columns: [
                    {data: 'select'},
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
         })

        });
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
                    url: "{{ route('journals.approve') }}",
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
                        window.location = "{{ route('journals.index') }}";
                    },
                });
            })
        })
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.btnprn').printPage();
        });
    </script>
@endpush

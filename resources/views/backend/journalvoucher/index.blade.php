@extends('backend.layouts.app')
@push('styles')
<style>
    .searchcolumn{
    width: 100px;
}
    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Content Header (Page header) -->
            <div class="sec-header">
                <div class="sec-header-wrap">
                    <h1>Journal Vouchers</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('journals.create') }}" class="global-btn">Entry New Journal</a>
                        <a href="{{ route('journals.cancelled') }}" class="global-btn">Cancelled Journals</a>
                        <a href="{{ route('journals.unapproved') }}" class="global-btn">Unapproved Journals</a>
                        <a href="#" data-toggle='modal' data-target='#update_csv_non_importer' data-toggle='tooltip'
                            data-placement='top' class="global-btn" title="Update Product from CSV">Import Journals</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Generate Report</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('extra') }}" method="GET">
                        @csrf
                        @method("GET")
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Fiscal Year</label>
                                    <select name="fiscal_year" class="form-control fiscal">
                                        @foreach ($fiscal_years as $fiscal_year)
                                            <option value="{{ $fiscal_year->fiscal_year }}"
                                                {{ $fiscal_year->id == $current_fiscal_year->id ? 'selected' : '' }}>
                                                {{ $fiscal_year->fiscal_year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Starting date</label>
                                    <input type="text" name="starting_date" class="form-control startdate"
                                        id="starting_date" value="{{ $actual_year[0] . '-04-01' }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Ending date</label>
                                    <input type="text" name="ending_date" class="form-control enddate" id="ending_date"
                                        value="">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-sm">Generate Report</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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

                <div class="bulk-btn">
                    <a href="{{ route('exportjournal', $current_fiscal_year->id) }}" class="global-btn">Export (CSV)
                    </a>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>Journal Voucher</h2>
                            </div>
                            <div class="card-body mid-body">
                                <form action="" class="form-center">
                                    @csrf
                                    <div class="form-group" style="margin-bottom:0;">
                                        {{-- <input type="checkbox" name="select-all" class="select_all" value="all"> Select
                                        All --}}
                                    </div>
                                    <h4 class="text-center m-0">For the fiscal year
                                        {{ $current_fiscal_year->fiscal_year }}
                                    </h4>
                                    <div class="bulk-btn">
                                        <button type="submit" name="submit" class="global-btn clicked">Unapprove
                                            Selected</button>
                                    </div>
                                </form>
                            </div>

                            <div class="card-body table-responsive">
                                    {{-- <div class="col-md-12">
                                        <div class="m-0 float-right">
                                            <form class="form-inline" action="{{ route('journalvoucher.search') }}"
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
                                        <table class="table table-bordered data-table text-center" id="myTable">
                                            {{-- <thead class="topsearch">
                                                <tr>
                                                    <th class="text-nowrap"></th>
                                                    <th class="text-nowrap">JV no.</th>
                                                    <th class="text-nowrap">Entry Date</th>
                                                    <th class="text-nowrap">Particulars</th>
                                                    <th class="text-nowrap">Debit Amount</th>
                                                    <th class="text-nowrap">Credit Amount</th>
                                                    <th class="text-nowrap">Status</th>
                                                    <th class="text-nowrap"></th>
                                                </tr>
                                            </thead> --}}
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
                                                        <td class="text-nowrap">
                                                            {{ $journalvoucher->journal_voucher_no }}</td>
                                                        <td class="text-nowrap">
                                                            {{ $journalvoucher->entry_date_nepali }}</td>
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
                                                                    $csrf_token = csrf_token();
                                                                    if ($journalvoucher->status == 1) {
                                                                        $btnname = 'fa fa-thumbs-down';
                                                                        $btnclass = 'btn-info';
                                                                        $title = 'Disapprove';
                                                                    } else {
                                                                        $btnname = 'fa fa-thumbs-up';
                                                                        $btnclass = 'btn-info';
                                                                        $title = 'Approve';
                                                                    }

                                                                    $btn = "<a href='$showurl' class='btn btn-primary btn-sm' data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                                <button type='button' class='btn btn-secondary btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                                                                <form action='$statusurl' method='POST' style='display:inline-block'>
                                                                                    <input type='hidden' name='_token' value='$csrf_token'>
                                                                                    <button type='submit' name='$title' class='btn $btnclass btn-primary ml-1 btn-sm text-light' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
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
                                                <div class="col-md-8">
                                                    <p class="text-sm">
                                                        Showing <strong>{{ $journalvouchers->firstItem() }}</strong>
                                                        to
                                                        <strong>{{ $journalvouchers->lastItem() }} </strong> of
                                                        <strong>
                                                            {{ $journalvouchers->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
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

            <div class='modal fade text-left' id='update_csv_non_importer' tabindex='-1' role='dialog'
                aria-labelledby='exampleModalLabel' aria-hidden='true'>
                <div class='modal-dialog' role='document' style="max-width: 800px;">
                    <div class='modal-content'>
                        <div class='modal-header text-center'>
                            <h2 class='modal-title' id='exampleModalLabel'>Update product from CSV</h2>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <div class='modal-body'>
                            <form action="{{ route('journals-import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method("POST")
                                <div class="row">

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="update_csv_file">CSV file<i class="text-danger">*</i> </label>
                                            <input type="file" name="excelFile" class="form-control" required>
                                            <p class="text-danger">
                                                {{ $errors->first('excelFile') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="btn-bulk">
                                    <button type="submit" class="btn btn-primary btn-sm" name="modal_button">Save</button>
                                </div>
                            </form>

                            <form action="{{ route('journals-import-demo') }}" method="post">
                                @csrf
                                <div class="btn-bulk">
                                    <button type="submit" class="btn btn-secondary btn-sm mt-2" name="modal_button"><i class="fas fa-download "> </i> Demo</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script>


        $(function() {
            // var all = $('input.select_all');
            // all.change(function() {
            //     var select = $('input.select');
            //     if (this.checked) {
            //         select.prop('checked', true);
            //     } else {
            //         select.prop('checked', false);
            //     }

            // })
            $(document).on('change','input.select_all',function(){
                var select = $('input.select');
                if (this.checked) {
                    select.prop('checked', true);
                } else {
                    select.prop('checked', false);
                }
            });
            var selectval = [];
            $('.clicked').click(function(event) {
                event.preventDefault();
                $('.select:checked').each(function(i) {
                    selectval[i] = $(this).val();
                })

                $.ajax({
                    url: "{{ route('journals.unapprove') }}",
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
                        window.location = "{{ route('journals.unapproved') }}";
                    },
                });
            })
        })
    </script>
    <script>
        $(function() {
            $('.fiscal').change(function() {
                var fiscal_year = $(this).children("option:selected").val();
                var array_date = fiscal_year.split("/");

                var starting_date = document.getElementById("starting_date");
                var starting_full_date = array_date[0] + '-04-01';
                starting_date.value = starting_full_date;
                starting_date.nepaliDatePicker();

                var ending_date = document.getElementById("ending_date");
                var ending_year = array_date[1];
                var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
                var ending_full_date = ending_year + '-03-' + days_count;
                ending_date.value = ending_full_date;

                ending_date.nepaliDatePicker();
            })
        })
    </script>

    <script type="text/javascript">
        window.onload = function() {
            var starting_date = document.getElementById("starting_date");
            var ending_date = document.getElementById("ending_date");
            var ending_year = "{{ $actual_year[1] }}";

            var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
            starting_date.nepaliDatePicker();

            var ending_full_date = ending_year + '-03-' + days_count;
            ending_date.value = ending_full_date;

            ending_date.nepaliDatePicker();
        };
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.btnprn').printPage();
        });

        $('#myTable thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#myTable thead');
        $(function () {
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


                                $(cell).html('<input class="searchcolumn" type="text" placeholder="' + title + '" />');



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
                            if($(this).attr('placeholder') == "Action"){
                                $(this).hide();
                            }
                            if($(this).attr('placeholder') == "Particulars"){
                                $(this).hide();
                            }
                            if($(this).attr('placeholder') == "Debit Amount"){
                                $(this).hide();
                            }
                            if($(this).attr('placeholder') == "Credit Amount"){
                                $(this).hide();
                            }
                            if($(this).attr('placeholder') == "Status"){
                                $(this).hide();
                            }

                        });

                },
                searchPanes: {
                    viewTotal: true
                },

                dom: 'Plfrtip',
                processing: false,
                serverSide: true,
                ajax:{
                    "url":"{{route('journals.index')}}",

                },

                columns: [
                    {data: 'select'},
                    {data: 'journal_voucher_no'},
                    {data: 'entry_date_nepali'},
                    {data: 'particulars',name:"journal_extras.child_account.title"},
                    {data: 'debit',name:'journal_extras.debitAmount'},
                    {data: 'credit'},

                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                pages: 5,
                pageLength: 15
            });



        });



    </script>
@endpush

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
                    <h1>Journal Vouchers </h1>
                    <div class="btn-bulk" style="margin-top:5px;">
                        <a href="{{ route('journals.create') }}" class="global-btn">Entry New Journal</a>
                        <a href="{{ route('journals.index') }}" class="global-btn">View Journals</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header text-center">
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
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

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

                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('exportfilterjournal', ['id' => $id, 'start_date' => $start_date, 'end_date' => $end_date]) }}"
                            class="global-btn">Export (CSV)</a>
                    </div>
                </div>
                <div class="card mt">
                    <div class="card-header">
                        <h2>Journal Vouchers</h2>
                    </div>
                    <div class="card-body">
                        <h4>For the fiscal year {{ $current_fiscal_year->fiscal_year }} ({{ $starting_date }} to
                            {{ $ending_date }})</h4>
                        <div class="row mt-4">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">JV no.</th>
                                            <th class="text-nowrap">Entry Date</th>
                                            <th class="text-nowrap">Particulars</th>
                                            <th class="text-nowrap">Debit Amount</th>
                                            <th class="text-nowrap">Credit Amount</th>
                                            <th class="text-nowrap">Narration</th>
                                            <th class="text-nowrap">Status</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        @forelse ($journalvouchers as $journalvoucher)
                                            <tr>
                                                <td class="text-nowrap">{{ $journalvoucher->journal_voucher_no }}</td>
                                                <td class="text-nowrap">{{ $journalvoucher->entry_date_nepali }}</td>
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
                                                        $narration = '( ' . $journalvoucher->narration . ' )';
                                                        echo $narration;
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
                                                            $btn = "<a href='$showurl' class='edit btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                        <button type='button' class='btn btn-secondary btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                                                        <form action='$statusurl' method='POST' style='display:inline-block'>
                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                            <button type='submit' name = '$title' class='btn $btnclass btn-primary btn-sm ml-1 text-light' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
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
                                                        } else {
                                                            $btnname = 'fa fa-thumbs-up';
                                                            $btnclass = 'btn-info';
                                                            $title = 'Approve';
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
                                                        }

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
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $journalvouchers->links() }}</span>
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
        $(function () {
            var url = "{{route('generatereport', [$id, $starting_date, $ending_date])}}";
            console.log(url);
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: url,
                columns: [
                    {data: 'journal_voucher_no', name: 'journal_voucher_no'},
                    {data: 'entry_date_nepali'},
                    {data: 'particulars', name: 'particulars', searchable: true},
                    {data: 'debit', name: 'debit'},
                    {data: 'credit', name: 'credit'},
                    {data: 'narration', name: 'narration'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                pages: 5,
                pageLength: 15
            });

        });

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
    </script>
@endpush

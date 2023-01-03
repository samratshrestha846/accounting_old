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
                    <h1>Budget View </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('budgetinfo') }}" class="global-btn">Budget Info</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @if (session('success'))
                            <div class="col-sm-12">
                                <div class="alert  alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="col-sm-12">
                                <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>
                                            Budget info for {{ $child_account->title }} A/C
                                            <a href="{{ route('editbudget', $budget_info->id) }}"
                                                class="btn btn-secondary btn-sm float-right">Edit</a>
                                        </h2>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <b>Fiscal Year:</b>
                                            </div>
                                            <div class="col-md-9">
                                                <p>{{ $budget_info->fiscal_year }}</p>
                                            </div>

                                            <div class="col-md-3">
                                                <b>Allocated From:</b>
                                            </div>
                                            <div class="col-md-9">
                                                <p>{{ $budget_info->starting_date_nepali }}</p>
                                            </div>

                                            <div class="col-md-3">
                                                <b>Allocated upto:</b>
                                            </div>
                                            <div class="col-md-9">
                                                <p>{{ $budget_info->ending_date_nepali }}</p>
                                            </div>

                                            <div class="col-md-3">
                                                <b>Budget Allocated:</b>
                                            </div>
                                            <div class="col-md-9">
                                                <p>Rs. {{ $budget_info->budget_allocated }}</p>
                                            </div>

                                            <div class="col-md-3">
                                                <b>Budget Balance:</b>
                                            </div>
                                            <div class="col-md-9">
                                                <p>Rs. {{ $budget_info->budget_balance }}</p>
                                            </div>

                                            <div class="col-md-3">
                                                <b>Budget Details:</b>
                                            </div>
                                            <div class="col-md-9">
                                                <p>{!! $budget_info->details !!}</p>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-12 table-responsive">
                                                <table class="table table-bordered">
                                                    <thead class="thead-light text-center">
                                                        <tr>
                                                            <th class="text-nowrap">Date</th>
                                                            <th class="text-nowrap">J.V no.</th>
                                                            <th class="text-nowrap">Income</th>
                                                            <th class="text-nowrap">Expense</th>
                                                            <th class="text-nowrap">Balance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-center">
                                                        <tr>
                                                            <td>-</td>
                                                            <td><b>Budget Amount</b></td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>Rs. {{ $budget_info->budget_balance }}</td>
                                                        </tr>

                                                        @if (count($journal_extras) == 0)
                                                            {{-- <tr>
                                                            <td colspan="6"><h4>No any records..</h4></td>
                                                        </tr> --}}
                                                        @else
                                                            @foreach ($journal_extras as $extra)
                                                                @php
                                                                    $related_journal = \App\Models\JournalVouchers::where('fiscal_year_id', $fiscalyear->id)
                                                                        ->where('id', $extra->journal_voucher_id)
                                                                        ->where('is_cancelled', 0)
                                                                        ->where('status', 1)
                                                                        ->first();
                                                                @endphp
                                                                @if ($related_journal)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $related_journal->entry_date_nepali }}
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('journals.show', $related_journal->id) }}"
                                                                                target="_blank" class="journal_number"
                                                                                style="text-decoration: none;">
                                                                                {{ $related_journal->journal_voucher_no }}
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            @if ($extra->debitAmount > 0)
                                                                                Rs. {{ $extra->debitAmount }}
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </td>

                                                                        <td>
                                                                            @if ($extra->creditAmount > 0)
                                                                                (Rs. {{ $extra->creditAmount }})
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($extra->debitAmount > 0)
                                                                                @php
                                                                                    $budget_info->budget_balance += $extra->debitAmount;
                                                                                @endphp
                                                                                @if ($budget_info->budget_balance < 0)
                                                                                    (Rs.
                                                                                    {{ $budget_info->budget_balance * -1 }})
                                                                                @else
                                                                                    Rs.
                                                                                    {{ $budget_info->budget_balance }}
                                                                                @endif
                                                                            @else
                                                                                @php
                                                                                    $budget_info->budget_balance -= $extra->creditAmount;
                                                                                @endphp
                                                                                @if ($budget_info->budget_balance < 0)
                                                                                    (Rs.
                                                                                    {{ $budget_info->budget_balance * -1 }})
                                                                                @else
                                                                                    Rs.
                                                                                    {{ $budget_info->budget_balance }}
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        <tr>
                                                            <td colspan="4"><b>Remaining Budget Balance</b></td>
                                                            <td>
                                                                @if ($budget_info->budget_balance < 0)
                                                                    (Rs. {{ $budget_info->budget_balance * -1 }})
                                                                @else
                                                                    Rs. {{ $budget_info->budget_balance }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

@endpush

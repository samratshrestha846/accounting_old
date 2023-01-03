@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h1 class="m-0">Bank Reconciliation #{{ $reconciliation->id }} <a href="{{ route('bankReconciliationStatement.index') }}" class="btn btn-primary">View Reconciliations</a></h1>
                    </div>

                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Reconciliation</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
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

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <b>Cheque Entried Date:</b>
                            </div>
                            <div class="col-md-9">
                                <p>{{ $reconciliation->cheque_entry_date }} (in B.S)</p>
                            </div>
                            <div class="col-md-3">
                                <b>Journal Number:</b>
                            </div>
                            <div class="col-md-9">
                                @if ($reconciliation->jv_id == null)
                                    <p>Manual</p>
                                @else
                                    <p><a href="{{ route('journals.show', $journalVoucher->id) }}" target="_blank">{{ $journalVoucher->journal_voucher_no }}</a></p>
                                @endif
                            </div>

                            <div class="col-md-3">
                                <b>Bank:</b>
                            </div>
                            <div class="col-md-9">
                                <p>{{ $bank->bank_name }} ( {{ $bank->head_branch }} )</p>
                            </div>

                            @if ($reconciliation->cheque_no == null)
                                <div class="col-md-3">
                                    <b>Method:</b>
                                </div>
                                <div class="col-md-9">
                                    <p>Bank Transfer</p>
                                </div>
                            @else
                                <div class="col-md-3">
                                    <b>Method:</b>
                                </div>
                                <div class="col-md-9">
                                    <p>Cheque</p>
                                </div>

                                <div class="col-md-3">
                                    <b>Cheque no:</b>
                                </div>
                                <div class="col-md-9">
                                    <p>{{ $reconciliation->cheque_no }}</p>
                                </div>
                            @endif

                            <div class="col-md-3">
                                <b>Receipt / Payment:</b>
                            </div>
                            <div class="col-md-9">
                                @if ($reconciliation->receipt_payment == 0)
                                    <p>Receipt</p>
                                @else
                                    <p>Payment</p>
                                @endif
                            </div>


                            @if ($reconciliation->vendor_id == null)
                                <div class="col-md-3">
                                    <b>Related Party:</b>
                                </div>
                                <div class="col-md-9">
                                    <p>{{ $reconciliation->other_receipt }}</p>
                                </div>
                            @else
                                <div class="col-md-3">
                                    <b>Related Party:</b>
                                </div>
                                <div class="col-md-9">
                                    <p>{{ $supplier->company_name }}</p>
                                </div>
                            @endif

                            <div class="col-md-3">
                                <b>Amount:</b>
                            </div>
                            <div class="col-md-9">
                                <p>Rs. {{ $reconciliation->amount }}</p>
                            </div>

                            <div class="col-md-3">
                                <b>Cheque checked out Date:</b>
                            </div>
                            <div class="col-md-9">
                                @if ($reconciliation->cheque_cashed_date == null)
                                    <p>-</p>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#entercash" data-toggle="tooltip" data-placement="top" title="Enter Cashed Out">Set Cashed Date</button>
                                @else
                                    <p>{{ $reconciliation->cheque_cashed_date }} (in B.S)</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-left" id="entercash" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Enter Cheque Cashed out Date:</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body text-center">
                                <form action="{{ route('reconciliationCashedOut', $reconciliation->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method("PUT")
                                    <div class="form-group">
                                        <label for="cheque_cashed_date">Cheque cashed out (B.S):</label>
                                        <input type="text" name="cheque_cashed_date" id="cheque_cashed_date"
                                            class="form-control" value="{{ $nepali_today }}">
                                    </div>
                                    <button type="submit" class="btn btn-success" title="Update">Update</button>
                                </form>
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
    document.getElementById('cheque_cashed_date').nepaliDatePicker({
        container: '#entercash',
      });
</script>
@endpush

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
                    <h1 class="m-0">Search Bank Reconciliation </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('bankReconciliationStatement.create') }}" class="global-btn">Create
                            Reconciliation</a>
                    </div>
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
                            <div class="col-md-12 ">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="{{ route('searchBankReconciliation') }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-group mx-sm-3">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-primary icon-btn"><i
                                                class="fa fa-search"></i></button>
                                    </form>
                                </div>

                            </div>
                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Jounal no.</th>
                                            <th class="text-nowrap">BankName</th>
                                            <th class="text-nowrap">Cheque no / Bank Deposit</th>
                                            <th class="text-nowrap">Receipt / Payment</th>
                                            <th class="text-nowrap">Related Party</th>
                                            <th class="text-nowrap">Amount</th>
                                            <th class="text-nowrap">Cheque cashed on (in B.S.)</th>
                                            <th class="text-nowrap">Cashed out on (in B.S.)</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($reconciliations as $reconciliation)
                                            <tr>
                                                <td class="text-nowrap">
                                                    {{ $reconciliation->jv_id == null ? '-' : $reconciliation->journal->journal_voucher_no }}
                                                </td>
                                                <td class="text-nowrap">{{ $reconciliation->bank->bank_name }}</td>
                                                <td class="text-nowrap">
                                                    {{ $reconciliation->cheque_no == null ? 'Bank Transfer' : $reconciliation->cheque_no }}
                                                </td>
                                                <td class="text-nowrap">
                                                    {{ $reconciliation->receipt_payment == 0 ? 'Receipt' : 'Payment' }}
                                                </td>
                                                <td class="text-nowrap">
                                                    {{ $reconciliation->vendor_id == 'null' ? $reconciliation->other_receipt : $reconciliation->vendor->company_name }}
                                                </td>
                                                <td class="text-nowrap">Rs.{{ $reconciliation->amount }}</td>
                                                <td class="text-nowrap">
                                                    {{ $reconciliation->cheque_cashed_date == null ? '-' : $reconciliation->cheque_cashed_date }}
                                                </td>
                                                <td class="text-nowrap">{{ $reconciliation->cheque_entry_date }}</td>
                                                <td class="text-nowrap" style="width: 120px;">
                                                    <div class="btn-bulk justify-content-center">
                                                        @php
                                                        $editurl = route('bankReconciliationStatement.edit', $reconciliation->id);
                                                        $deleteurl = route('bankReconciliationStatement.destroy', $reconciliation->id);
                                                        $cashed_out_url = route('reconciliationCashedOut', $reconciliation->id);
                                                        $csrf_token = csrf_token();
                                                        if ($reconciliation->cheque_cashed_date == null) {
                                                            $btn = "
                                                                                                                                                                                                                                <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#entercash$reconciliation->id' data-toggle='tooltip' data-placement='top' title='Enter Cashed Out'><i class='fa fa-edit'></i></button>
                                                                                                                                                                                                                                        <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' title='Show'><i class='fa fa-eye'></i></a>
                                                                                                                                                                                                                                        <button type='button' class='btn btn-primart icon-btn btn-sm' data-toggle='modal' data-target='#deletechild$reconciliation->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                                                                                                                                                                                        <!-- Modal -->
                                                                                                                                                                                                                                            <div class='modal fade text-left' id='entercash$reconciliation->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                                                                                                                                                                                <div class='modal-dialog' role='document'>
                                                                                                                                                                                                                                                    <div class='modal-content'>
                                                                                                                                                                                                                                                        <div class='modal-header'>
                                                                                                                                                                                                                                                        <h5 class='modal-title' id='exampleModalLabel'>Enter Cheque Cashed out Date:</h5>
                                                                                                                                                                                                                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                                                            <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                                                        </button>
                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                        <div class='modal-body text-center'>
                                                                                                                                                                                                                                                            <form action='$cashed_out_url' method='POST' style='display:inline-block;'>
                                                                                                                                                                                                                                                                <input type='hidden' name='_token' value='$csrf_token'>
                                                                                                                                                                                                                                                                <input type='hidden' name='_method' value='PUT' />
                                                                                                                                                                                                                                                                <div class='form-group'>
                                                                                                                                                                                                                                                                    <label for='cheque_cashed_date'>Cheque cashed out (B.S):</label>
                                                                                                                                                                                                                                                                    <input type='text' name='cheque_cashed_date' id='cheque_cashed_date'
                                                                                                                                                                                                                                                                        class='form-control' value='$nepali_today'>
                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                <button type='submit' class='btn btn-success' title='Update'>Update</button>
                                                                                                                                                                                                                                                            </form>
                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                <script>
                                                                                                                                                                                                                                                    document.getElementById('cheque_cashed_date').nepaliDatePicker({
                                                                                                                                                                                                                                                        container: '#entercash$reconciliation->id',
                                                                                                                                                                                                                                                    });
                                                                                                                                                                                                                                                </script>
                                                                                                                                                                                                                                            </div>

                                                                                                                                                                                                                                            <div class='modal fade text-left' id='deletechild$reconciliation->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                                                                                                                                                                                <div class='modal-dialog' role='document'>
                                                                                                                                                                                                                                                    <div class='modal-content'>
                                                                                                                                                                                                                                                        <div class='modal-header'>
                                                                                                                                                                                                                                                        <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                                                                                                                                                                                                                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                                                            <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                                                        </button>
                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                        <div class='modal-body text-center'>
                                                                                                                                                                                                                                                            <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                                                                                                                                                                                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                                                                                                                                                                                                            <label for='reason'>Are you sure you want to delete??</label><br>
                                                                                                                                                                                                                                                            <input type='hidden' name='_method' value='DELETE' />
                                                                                                                                                                                                                                                                <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                                                                                                                                                                                                                                                            </form>
                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                        ";
                                                        } else {
                                                            $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Show'><i class='fa fa-eye'></i></a>
                                                                                                                                                                                                                                        <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#deletechild$reconciliation->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                                                                                                                                                                                        <!-- Modal -->
                                                                                                                                                                                                                                            <div class='modal fade text-left' id='deletechild$reconciliation->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                                                                                                                                                                                <div class='modal-dialog' role='document'>
                                                                                                                                                                                                                                                    <div class='modal-content'>
                                                                                                                                                                                                                                                        <div class='modal-header'>
                                                                                                                                                                                                                                                        <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                                                                                                                                                                                                                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                                                            <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                                                        </button>
                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                        <div class='modal-body text-center'>
                                                                                                                                                                                                                                                            <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                                                                                                                                                                                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                                                                                                                                                                                                            <label for='reason'>Are you sure you want to delete??</label><br>
                                                                                                                                                                                                                                                            <input type='hidden' name='_method' value='DELETE' />
                                                                                                                                                                                                                                                                <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
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
                                            <tr><td colspan="9">No search results.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
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

@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Daily Expenses (Trashed)</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('dailyexpenses.index') }}" class="global-btn">View Daily
                            Expenses</a>
                    </div>
                    <!-- /.col -->
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
                    <div class="card-body text-center">
                        <table class="table table-bordered data-table-1 global-table">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-nowrap">Purchased Date</th>
                                    <th class="text-nowrap">Bill Image</th>
                                    <th class="text-nowrap">Purchased From</th>
                                    <th class="text-nowrap">Bill Number</th>
                                    <th class="text-nowrap">Bill Amount</th>
                                    <th class="text-nowrap">Paid Amount</th>
                                    <th class="text-nowrap">Purpose</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dailyexpenses as $dailyexpense)
                                    <tr>
                                        <td>{{ $dailyexpense->date }}</td>
                                        <td><img src="{{ Storage::disk('uploads')->url($dailyexpense->bill_image) }}"
                                                style="height: 50px;" alt=""></td>
                                        <td>{{ $dailyexpense->vendor->company_name }}</td>
                                        <td>{{ $dailyexpense->bill_number }}</td>
                                        <td>{{ $dailyexpense->bill_amount }}</td>
                                        <td>{{ $dailyexpense->paid_amount }}</td>
                                        <td>{{ $dailyexpense->purpose }}</td>
                                        <td>
                                            @php
                                                $restoreurl = route('restoreexpenses', $dailyexpense->id);
                                                $btn = "<button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation$dailyexpense->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                                                                                                                                                                                                                                <!-- Modal -->
                                                                                                                                                                                                                                                    <div class='modal fade text-left' id='cancellation$dailyexpense->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                                                                                                                                                                                        <div class='modal-dialog' role='document'>
                                                                                                                                                                                                                                                            <div class='modal-content'>
                                                                                                                                                                                                                                                                <div class='modal-header'>
                                                                                                                                                                                                                                                                <h5 class='modal-title' id='exampleModalLabel'>Restore Confirmation</h5>
                                                                                                                                                                                                                                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                                                                    <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                                                                </button>
                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                <div class='modal-body text-center'>
                                                                                                                                                                                                                                                                    <label for='reason'>Are you sure you want to restore??</label><br>
                                                                                                                                                                                                                                                                    <a href='$restoreurl' class='edit btn btn-primary btn-sm' title='Restore'>Confirm Restore</a>
                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                ";

                                                echo $btn;
                                            @endphp
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8">No expenses trashed yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-sm">
                                        Showing <strong>{{ $dailyexpenses->firstItem() }}</strong> to
                                        <strong>{{ $dailyexpenses->lastItem() }} </strong> of <strong>
                                            {{ $dailyexpenses->total() }}</strong>
                                        entries
                                        <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds to
                                            render</span>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <span class="pagination-sm m-0 float-right">{{ $dailyexpenses->links() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection
@push('scripts')

@endpush

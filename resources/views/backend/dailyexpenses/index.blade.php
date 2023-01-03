@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Daily Expenses </h1>
                    <div class="btn-bulk" style="margin-top:5px;">
                        <a href="{{ route('dailyexpenses.create') }}" class="global-btn">Entry Daily
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
                        <div class="m-0 d-flex justify-content-end">
                            <form class="form-inline" action="{{ route('dailyexpense.search') }}" method="POST">
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
                        <div class="table-responsive mt noscroll">
                            <table class="table table-bordered data-table global-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-nowrap">Purchased Date</th>
                                        <th class="text-nowrap">Bill Image</th>
                                        <th class="text-nowrap">Purchased From</th>
                                        <th class="text-nowrap">Bill Number</th>
                                        <th class="text-nowrap">Bill Amount</th>
                                        <th class="text-nowrap">Paid Amount</th>
                                        <th class="text-nowrap">Purpose</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dailyexpenses as $dailyexpense)
                                        <tr>
                                            <td>{{ $dailyexpense->date }}</td>
                                            <td><img src="{{ Storage::disk('uploads')->url($dailyexpense->bill_image) }}"
                                                    style="height:30px;width:auto;" alt=""></td>
                                            <td>{{ $dailyexpense->vendor->company_name }}</td>
                                            <td>{{ $dailyexpense->bill_number }}</td>
                                            <td>{{ $dailyexpense->bill_amount }}</td>
                                            <td>{{ $dailyexpense->paid_amount }}</td>
                                            <td>{{ $dailyexpense->purpose }}</td>
                                            <td>
                                                <div class="btn-bulk">
                                                    @php
                                                    $editurl = route('dailyexpenses.edit', $dailyexpense->id);
                                                    $deleteurl = route('dailyexpenses.destroy', $dailyexpense->id);
                                                    $csrf_token = csrf_token();
                                                    $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                                                                                                                                                                                                                                                                                                                            <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#deleteexpenses$dailyexpense->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                                                                                                                                                                                                                                                                                                                            <!-- Modal -->
                                                                                                                                                                                                                                                                                                                                                                                <div class='modal fade text-left' id='deleteexpenses$dailyexpense->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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

                                                    echo $btn;
                                                @endphp
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8">No expenses records yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-5">
                                        <p class="text-sm">
                                            Showing <strong>{{ $dailyexpenses->firstItem() }}</strong> to
                                            <strong>{{ $dailyexpenses->lastItem() }} </strong> of <strong>
                                                {{ $dailyexpenses->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                seconds to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-7">
                                        <span class="pagination-sm m-0 float-right">{{ $dailyexpenses->links() }}</span>
                                    </div>
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

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
                    </div><!-- /.col -->
                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Credit</li>
                        </ol>
                    </div>
                    <div class="col-md-12">
                        <div class="btn-bulk" style="margin-top:5px;">
                            {{-- <a href="{{ route('credit.create') }}"
                                class="btn btn-success btn-sm">Create credit</a> --}}
                        </div>
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

                <div class="card">
                    <div class="card-header text-center">
                        <h1>Search Results for Credit</h1>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="{{ route('crediSearch') }}" method="POST">
                                        @csrf
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mb-2"><i
                                                class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Client</th>
                                            <th class="text-nowrap">Days Limit</th>
                                            <th class="text-nowrap">Bills number limit</th>
                                            <th class="text-nowrap">Total Amount Limit</th>
                                            <th class="text-nowrap">Bill Due Date</th>
                                            <th class="text-nowrap">Bills Number</th>
                                            <th class="text-nowrap">Total Amount</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($credits as $credit)
                                            <tr>
                                                <td>{{ $credit->client->name }}</td>
                                                <td>{{ $credit->allocated_days }} days</td>
                                                <td>{{ $credit->allocated_bills }} Bills</td>
                                                <td>Rs. {{ $credit->allocated_amount }}</td>
                                                <td>
                                                    {{ $credit->bill_eng_date == null ? 'No credit yet.' : $credit->bill_eng_date }}
                                                </td>
                                                <td>{{ $credit->credited_bills == 0 ? 'No credit' : $credit->credited_bills . ' bills'}}</td>
                                                <td>{{ $credit->credited_amount == 0 ? 'No credit' : 'Rs. '. $credit->credited_amount }}</td>
                                                <td>
                                                    @php
                                                        $editurl = route('credit.update', $credit->id);
                                                        $deleteurl = route('credit.destroy', $credit->id);
                                                        $csrf_token = csrf_token();
                                                        $btn = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editcredit$credit->id' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></button>
                                                                    <!-- Modal -->
                                                                        <div class='modal fade text-left' id='editcredit$credit->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                            <div class='modal-dialog' role='document'>
                                                                                <div class='modal-content'>
                                                                                    <div class='modal-header'>
                                                                                        <h5 class='modal-title' id='exampleModalLabel'>Edit Credit</h5>
                                                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                            <span aria-hidden='true'>&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class='modal-body'>
                                                                                        <form action='$editurl' method='POST' style='display:inline-block;'>
                                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                                            <input type='hidden' name='_method' value='PUT' />
                                                                                            <div class='form-group'>
                                                                                                <div class='row'>
                                                                                                    <div class='col-md-6 mt-2'>
                                                                                                        <label for='allocated_days'>Allocated Days<i
                                                                                                                class='text-danger'>*</i>:</label>
                                                                                                    </div>
                                                                                                    <div class='col-md-6'>
                                                                                                        <input type='number' id='allocated_days' name='allocated_days'
                                                                                                            class='form-control' value='$credit->allocated_days' />
                                                                                                    </div>

                                                                                                    <div class='col-md-6 mt-2'>
                                                                                                        <label for='allocated_bills'>Number Of Bills Limit<i
                                                                                                                class='text-danger'>*</i>:</label>
                                                                                                    </div>
                                                                                                    <div class='col-md-6'>
                                                                                                        <input type='number' id='allocated_bills' name='allocated_bills'
                                                                                                            class='form-control'
                                                                                                            value='$credit->allocated_bills' />
                                                                                                    </div>

                                                                                                    <div class='col-md-6 mt-2'>
                                                                                                        <label for='allocated_amount'>Allocated Total Amount (In Rs. )<i
                                                                                                                class='text-danger'>*</i>:</label>
                                                                                                    </div>
                                                                                                    <div class='col-md-6'>
                                                                                                        <input type='number' id='allocated_amount' name='allocated_amount'
                                                                                                            class='form-control'
                                                                                                            value='$credit->allocated_amount' />
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <button type='submit' class='btn btn-success btn-sm' title='Update'>Update</button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    ";
                                                        echo $btn;
                                                    @endphp
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $credits->firstItem() }}</strong> to
                                                <strong>{{ $credits->lastItem() }} </strong> of <strong>
                                                    {{ $credits->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $credits->links() }}</span>
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

@endpush

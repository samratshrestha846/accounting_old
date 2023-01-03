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
                    <h1>Budget Allocation </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('budgetsetup') }}" class="global-btn">Setup Budget</a>
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
                                <div class="card">
                                    <div class="card-body">
                                                <div style="display: flex;justify-content:flex-end;">
                                                    <form class="form-inline" action="{{ route('budget.search') }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="form-group mx-sm-3">
                                                            <label for="search" class="sr-only">Search</label>
                                                            <input type="text" class="form-control" id="search"
                                                                name="search" placeholder="Search">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary icon-btn btn-sm"><i
                                                                class="fa fa-search"></i></button>
                                                    </form>
                                                </div>
                                            <div class="table-responsive mt noscroll">
                                                <table class="table table-bordered data-table text-center global-table">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="text-nowrap">Ledger Head</th>
                                                            <th class="text-nowrap">Fiscal Year</th>
                                                            <th class="text-nowrap">Allocated From</th>
                                                            <th class="text-nowrap">Allcated Upto</th>
                                                            <th class="text-nowrap">Allocated Budget</th>
                                                            <th class="text-nowrap">Budget Balance</th>
                                                            <th width="100px">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($budgets as $budget)
                                                            <tr>
                                                                <td>{{ $budget->childaccount->title }}</td>
                                                                <td>{{ $budget->fiscal_year }}</td>
                                                                <td>{{ $budget->starting_date_nepali }}</td>
                                                                <td>{{ $budget->ending_date_nepali }}</td>
                                                                <td>Rs. {{ $budget->budget_allocated }}</td>
                                                                <td>Rs. {{ $budget->budget_balance }}</td>
                                                                <td>
                                                                    <div class="btn-bulk">
                                                                        @php
                                                                            $editurl = route('editbudget', $budget->id);
                                                                            $showurl = route('budgetview', $budget->id);
                                                                            $btn = "<a href='$showurl' class='edit btn icon-btn btn-secondary' title='Show'><i class='fa fa-eye'></i></a>
                                                                                        <a href='$editurl' class='edit btn icon-btn btn-primary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>";

                                                                            echo $btn;
                                                                        @endphp
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr><td colspan="7">No budgets yet.</td></tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <p class="text-sm">
                                                                Showing <strong>{{ $budgets->firstItem() }}</strong> to
                                                                <strong>{{ $budgets->lastItem() }} </strong> of <strong>
                                                                    {{ $budgets->total() }}</strong>
                                                                entries
                                                                <span> | Takes
                                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                                    seconds to
                                                                    render</span>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <span
                                                                class="pagination-sm m-0 float-right">{{ $budgets->links() }}</span>
                                                        </div>
                                                    </div>
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

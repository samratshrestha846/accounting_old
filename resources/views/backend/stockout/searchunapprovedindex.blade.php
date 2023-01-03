@extends('backend.layouts.app')
@push('styles')
    <style>
        .hide{
            display: none;
        }
        .show{
            display: inline-block;
        }

    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Search Results for {{$search}} </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('stockout.create') }}" class="global-btn">Create Stock Out</a>
                        <a href="{{ route('stockout.index') }}" class="global-btn">Stock Out</a>
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
                    <div class="card-header">
                        <h2>Search Results for {{$search}}</h2>
                    </div>
                    <div class="card-body">
                        <div class="ibox">
                            <div class="row ibox-body">
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="col-md-12 ">
                                                <div class="m-0 d-flex justify-content-end">
                                                    <form class="form-inline" action="{{ route('searchunapprovestockout') }}"
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
                                            </div>
                                            <div class="table-responsive mt">
                                                <table class="table table-bordered data-table text-center">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="text-nowrap">Client Name</th>
                                                            <th class="text-nowrap">Godown</th>
                                                            <th class="text-nowrap">Stock Out Date</th>
                                                            <th class="text-nowrap">Entry By</th>
                                                            <th class="text-nowrap">Status</th>
                                                            <th class="text-nowrap" style="width: 15%;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($stockouts as $stockout)
                                                            <tr>
                                                                <td class="text-nowrap">{{ $stockout->client->name }}</td>
                                                                <td class="text-nowrap">{{ $stockout->godown->godown_name }}</td>
                                                                <td class="text-nowrap">{{ $stockout->stock_out_date }}
                                                                </td>
                                                                <td class="text-nowrap">{{ $stockout->user->name }}</td>
                                                                <td class="text-nowrap">{{ $stockout->status == 1 ? 'Approved' : 'Waiting for Approval' }}</td>
                                                                <td class="text-nowrap" style="width: 120px;">
                                                                    <div class="btn-bulk justify-content-center">
                                                                        @php
                                                                        $unapproveurl = route('stockout.unapprove', $stockout->id);
                                                                        $approveurl = route('stockout.approve', $stockout->id);
                                                                        $showurl = route('stockout.show', $stockout->id);
                                                                        $csrf_token = csrf_token();
                                                                        $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                                <a href='$unapproveurl' class='edit btn btn-secondary icon-btn btn-sm' title='Unapprove'><i class='far fa-thumbs-down'></i></a>
                                                                                    ";

                                                                        echo $btn;
                                                                    @endphp
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr><td colspan="7">No stockouts for <b>{{$search}}</b>.</td></tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <p class="text-sm">
                                                                Showing <strong>{{ $stockouts->firstItem() }}</strong> to
                                                                <strong>{{ $stockouts->lastItem() }} </strong> of <strong>
                                                                    {{ $stockouts->total() }}</strong>
                                                                entries
                                                                <span> | Takes
                                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                                    seconds to
                                                                    render</span>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-7">
                                                            <span
                                                                class="pagination-sm m-0 float-right">{{ $stockouts->links() }}</span>
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
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')

@endpush

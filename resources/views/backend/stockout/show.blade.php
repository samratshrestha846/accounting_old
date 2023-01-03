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
                    <h1>Stock Out Details</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('stockout.create') }}" class="global-btn">Create Stock Out</a>
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
                        <h2>Stock out Details</h2>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <p><b>Client Name: </b> {{$stockout->client->name}}</p>
                                <p><b>Godown Name: </b> {{$stockout->godown->godown_name}}</p>
                                <p><b>Stock Out Date: </b> {{$stockout->stock_out_date}}</p>
                                <p><b>Entry By: </b> {{$stockout->user->name}}</p>
                                <p><b>Status: </b> {{$stockout->status == 1 ? 'Approved' : 'Waiting For Approval'}}</p>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stockout->stockoutproducts as $stockoutproduct)
                                        <tr>
                                            <td>{{$stockoutproduct->product->product_name}}({{$stockoutproduct->product->product_code}})</td>
                                            <td>{{$stockoutproduct->total_stock_out}}</td>
                                        </tr>
                                        @endforeach

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

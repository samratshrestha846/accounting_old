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
                    <h1>Sales Returns History</h1>
                    <div class="btn-bulk">
                        <a href="{{route('product.show', $product->id)}}" class="global-btn">Back</a>
                        <a href="{{route('product.sales', $product->id)}}" class="global-btn">Sales</a>
                        <a href="{{route('product.purchases', $product->id)}}" class="global-btn">Purchases</a>
                        <a href="{{route('product.purchase_returns', $product->id)}}" class="global-btn">Purchase Returns</a>
                        <a href="{{route('product.quotations', $product->id)}}" class="global-btn">Quotations</a>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
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
                <!-- Main content -->
                <section class="content">
                    <div class="ibox">
                        <div class="ibox-body">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h2>{{ $product->product_name }}</h2>

                                </div>
                                <div class="card-body">
                                    <table class="table bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-nowrap">Client</th>
                                                <th class="text-nowrap">Billing Ref.</th>
                                                <th class="text-nowrap">Godown</th>
                                                <th class="text-nowrap">Nep-Date</th>
                                                <th class="text-nowrap">Eng-Date</th>
                                                <th class="text-nowrap">Quantity</th>
                                                <th class="text-nowrap">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($productsales_returns as $productsales_return)
                                            <tr>
                                                <td><a href="{{route('client.show',$productsales_return->client_id)}}">{{$productsales_return->name}}</a></td>
                                                <td><a href="{{route('billings.show', $productsales_return->billing_id)}}">{{$productsales_return->reference_no}}</a></td>
                                                <td>{{$productsales_return->godown_name}}</td>
                                                <td>{{$productsales_return->nep_date}}</td>
                                                <td>{{$productsales_return->eng_date}}</td>
                                                <td>{{$productsales_return->quantity}}</td>
                                                <td><span class="badge badge-{{$productsales_return->status == 1 ? 'success' : 'danger'}}">{{$productsales_return->status == 1 ? 'Yes' : 'No'}}</span></td>
                                            </tr>
                                            @endforeach
                                            @if (count($productsales_returns) == 0)
                                            <tr>
                                                <td colspan="7" class="text-center">No Sales Return of this Product</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <p class="text-sm">
                                                    Showing <strong>{{ $productsales_returns->firstItem() }}</strong> to
                                                    <strong>{{ $productsales_returns->lastItem() }} </strong> of <strong>
                                                        {{ $productsales_returns->total() }}</strong>
                                                    entries
                                                    <span> | Takes
                                                        <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                        seconds to
                                                        render</span>
                                                </p>
                                            </div>
                                            <div class="col-md-7">
                                                <span
                                                    class="pagination-sm m-0 float-right">{{ $productsales_returns->links() }}</span>
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

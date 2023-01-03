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
                    <h1>Products (Trashed) </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('product.index') }}" class="global-btn">View All Items</a>
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive noscroll">
                                        <table class="table table-bordered data-table-1 text-center global-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Product Code</th>
                                                    <th class="text-nowrap">Product Name</th>
                                                    <th class="text-nowrap">Product Category</th>
                                                    <th class="text-nowrap">Brand</th>
                                                    <th class="text-nowrap">Series</th>
                                                    <th class="text-nowrap">In stock (Unit)</th>
                                                    <th class="text-nowrap">Product Cost</th>
                                                    <th class="text-nowrap">Product Price</th>
                                                    <th class="text-nowrap">Status</th>
                                                    <th width="100px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($products as $product)
                                                    <tr>
                                                        <td>{{ $product->product_code }}</td>
                                                        <td>{{ $product->product_name }}</td>
                                                        <td>{{ $product->category->category_name }}</td>
                                                        <td>{{ $product->brand->brand_name }}</td>
                                                        <td>{{ $product->series->series_name }}</td>
                                                        <td>
                                                            {{ $product->total_stock }} {{ $product->primary_unit }}
                                                            <br>
                                                            ({{ $product->primary_number }}
                                                            {{ $product->primary_unit }} contains
                                                            {{ $product->secondary_number }}
                                                            {{ $product->secondary_unit }})
                                                        </td>
                                                        <td>Rs. {{ $product->cost_of_product }}</td>
                                                        <td>Rs. {{ $product->product_price }}</td>
                                                        <td>{{ $product->status == 1 ? 'Approved' : 'Not Approved' }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $restoreurl = route('restoreproduct', $product->id);
                                                                $btn = "<button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation$product->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                                                                                                                                                                                                                                                            <!-- Modal -->
                                                                                                                                                                                                                                                                                <div class='modal fade text-left' id='cancellation$product->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                                                    <tr><td colspan="10">No products trashed yet.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <p class="text-sm">
                                                        Showing <strong>{{ $products->firstItem() }}</strong> to
                                                        <strong>{{ $products->lastItem() }} </strong> of <strong>
                                                            {{ $products->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $products->links() }}</span>
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

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
                    <h1>Damaged Products </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('damaged_products.create') }}" class="global-btn">Entry Damaged
                            Products</a> <a href="{{ route('product.index') }}" class="global-btn">View
                            Products</a>
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
                                <div class="card-header d-flex justify-content-between">
                                    <h2>All Products</h2>
                                    <div class="stock d-flex" style="float:right;">
                                        <span class="badge badge-success btn btn-secondary">Total Stock Valuation:
                                           Rs. {{ number_format($totalproductvalidation) }}</span>

                                    </div>
                                </div>
                                <div class="card-body">
                                        <div class="m-0 d-flex justify-content-end">
                                            <form class="form-inline" action="{{ route('damagedproduct.search') }}"
                                                method="POST">
                                                @csrf
                                                <div class="form-group mx-sm-3">
                                                    <label for="search" class="sr-only">Search</label>
                                                    <input type="text" class="form-control" id="search" name="search"
                                                        placeholder="Search">
                                                </div>
                                                <label>&nbsp;</label>
                                                <button type="submit" class="btn btn-primary icon-btn btn-sm"><i
                                                        class="fa fa-search"></i></button>
                                            </form>
                                        </div>
                                    <div class="table-responsive mt noscroll">
                                        <table class="table table-bordered data-table text-center global-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Document</th>
                                                    <th class="text-nowrap">Product SKU</th>
                                                    <th class="text-nowrap">Product Name</th>
                                                    <th class="text-nowrap">Godown</th>
                                                    <th class="text-nowrap">Damaged stock (Unit)</th>
                                                    <th class="text-nowrap">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($damagedproducts as $damagedproduct)
                                                    <tr>
                                                        <td class="text-nowrap">
                                                            <a href="{{ Storage::disk('uploads')->url($damagedproduct->document) }}" target="_blank"><img src="{{ Storage::disk('uploads')->url($damagedproduct->document) }}" alt="{{ $damagedproduct->product->product_name }}" style="height: 30px;width:auto;"></a>
                                                        </td>
                                                        <td class="text-nowrap">
                                                            {{ $damagedproduct->product->product_code }}</td>
                                                        <td class="text-nowrap">
                                                            {{ $damagedproduct->product->product_name }}</td>
                                                        <td class="text-nowrap">
                                                            {{ $damagedproduct->godown->godown_name }}</td>
                                                        <td class="text-nowrap">
                                                            {{ $damagedproduct->stock }} <br>
                                                            @if($damagedproduct->product->has_serial_number == 1)
                                                                Serial Numbers:
                                                                @php
                                                                    $godown_stock = \App\Models\GodownProduct::where('godown_id', $damagedproduct->godown->id)->where('product_id', $damagedproduct->product->id)->first();
                                                                @endphp

                                                                @foreach($godown_stock->damagedserialnumbers as $productSerialNumber)
                                                                                ({{ $productSerialNumber->serial_number }})
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <div class="btn-bulk justify-content-start">
                                                                @php
                                                                $editurl = route('damaged_products.edit', $damagedproduct->id);
                                                                $deleteurl = route('damaged_products.destroy', $damagedproduct->id);
                                                                $csrf_token = csrf_token();
                                                                $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                            <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#deleteproduct$damagedproduct->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                            <!-- Modal -->
                                                                                <div class='modal fade text-left' id='deleteproduct$damagedproduct->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                                                                                ";
                                                                echo $btn;
                                                            @endphp
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="6">No any products.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="text-sm">
                                                        Showing <strong>{{ $damagedproducts->firstItem() }}</strong> to
                                                        <strong>{{ $damagedproducts->lastItem() }} </strong> of <strong>
                                                            {{ $damagedproducts->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $damagedproducts->links() }}</span>
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

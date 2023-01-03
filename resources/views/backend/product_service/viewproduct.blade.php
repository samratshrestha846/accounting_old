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
                    <h1>Product information</h1>
                    {{-- <div class="modal fade" id="productstockModal" tabindex="-1" role="dialog"
                        aria-labelledby="productstockModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="productstockModalLabel">Add Stock of
                                        {{ $product->product_name }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h5>
                                        <span class="badge badge-primary">Opening Stock:
                                            {{ $product->opening_stock }}{{ $product->primary_unit }}</span>
                                        <span class="badge badge-success">Current Stock:
                                            {{ $product->total_stock }}{{ $product->primary_unit }}</span>
                                    </h5>
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <a class="nav-item nav-link active" id="nav-stockdetails-tab" data-toggle="tab"
                                                href="#nav-stockdetails" role="tab" aria-controls="nav-stockdetails"
                                                aria-selected="true">Stockdetails</a>
                                            <a class="nav-item nav-link" id="nav-addstock-tab" data-toggle="tab"
                                                href="#nav-addstock" role="tab" aria-controls="nav-addstock"
                                                aria-selected="false">Add Stock</a>
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-stockdetails" role="tabpanel"
                                            aria-labelledby="nav-stockdetails-tab">
                                            <div class="container py-2">
                                                <table class="table table-bordered">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="text-nowrap" scope="col">Godown</th>
                                                            <th class="text-nowrap" scope="col">No. of Stock Added
                                                            </th>
                                                            <th class="text-nowrap" scope="col">Stock Added Date
                                                            </th>
                                                            <th class="text-nowrap" scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($productstocks as $pstock)
                                                            <tr>
                                                                <th scope="row">{{ $pstock->godown->godown_name }}
                                                                </th>
                                                                <td>{{ $pstock->added_stock }}</td>
                                                                <td>{{ $pstock->added_date }}</td>
                                                                <td><a href='{{ route('addstock.edit', $pstock->id) }}'
                                                                        class='edit btn btn-primary btn-sm mt-1'
                                                                        data-toggle='tooltip' data-placement='top'
                                                                        title='Edit'><i class='fa fa-edit'></i></a></td>
                                                            </tr>
                                                        @empty
                                                            <tr><td colspan="4" class="text-center">No products.</td></tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-addstock" role="tabpanel"
                                            aria-labelledby="nav-addstock-tab">
                                            <div class="container py-2">
                                                <input type="checkbox" name="check_serial_number" id="serial_number" {{$product->has_serial_number == 1 ? 'checked' : ''}} onchange="updateTableRow(this)">
                                                <form action="{{ route('addstock.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <div class="col-md-12 mt-4 mb-4">
                                                        <hr>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover text-center"
                                                                id="product_godown">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th class="text-center" style="width: 30%;">
                                                                            Godown</th>
                                                                        <th class="text-center" style="width: 10%;"> Floor no.</th>
                                                                        <th class="text-center" style="width: 10%;"> Rack no.</th>
                                                                        <th class="text-center" style="width: 10%;"> Row no.</th>
                                                                        <th class="text-center" style="width: 15%;"> Alert On</th>
                                                                        <th class="text-center" style="width: 15%;">
                                                                            Stock</th>
                                                                        <th class="text-center" style="width: 10%;">
                                                                            Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="godown_body">
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="4"></td>
                                                                        <td class="text-right">
                                                                            <label for="reason"
                                                                                class="col-form-label">Total</label>
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <input type="text" id="stockTotal"
                                                                                class="form-control text-right"
                                                                                name="stockTotal" value=""
                                                                                readonly="readonly" value />
                                                                        </td>
                                                                        <td>
                                                                            <a id="add_more" class="btn btn-primary"
                                                                                name="add_more"
                                                                                onClick="addGodownRow('godown_body')"><i
                                                                                    class="fa fa-plus"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="btn-bulk">
                        <a href="{{route('product.addstockindex', $product->id)}}" class="global-btn">Add Stock Report</a>
                        <a href="{{route('product.sales', $product->id)}}" class="global-btn">Sales</a>
                        <a href="{{route('product.sales_returns', $product->id)}}" class="global-btn">Sales Returns</a>
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
                                    <div class="stock d-flex">
                                        <span class="badge badge-success btn btn-secondary">Stock Valuation:
                                           Rs. {{ number_format($product->total_stock * $product->total_cost,2) }}</span>
                                        <span class="badge badge-primary btn btn-primary">Opening
                                            Stock:
                                            {{ $product->opening_stock }} {{ $product->primary_unit }}</span>
                                        <span class="badge badge-success btn btn-secondary">Current Stock:
                                            {{ $product->total_stock }} {{ $product->primary_unit }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2>Basic Details</h2>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Product Name:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            {{ $product->product_name }}
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Product SKU:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            {{ $product->product_code }}
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Related Godown:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            @foreach ($godown_products as $godown_product)
                                                                <b>{{ $godown_product->godown->godown_name }}</b> ->
                                                                {{ $godown_product->stock }}
                                                                {{ $product->primary_unit }} <br>
                                                                Floor no. ->
                                                                {{ $godown_product->floor_no == null ? 'Not Given' : $godown_product->floor_no }}
                                                                <br>
                                                                Rack no. ->
                                                                {{ $godown_product->rack_no == null ? 'Not Given' : $godown_product->rack_no }}
                                                                <br>
                                                                Row no. ->
                                                                {{ $godown_product->row_no == null ? 'Not Given' : $godown_product->row_no }}
                                                                <br>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Product Category:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            {{ $product->category->category_name }}
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Brand:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            {{ $product->brand_id == null ? '' : $product->brand->brand_name }}
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Series:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            {{ $product->series_id == null ? '' : $product->series->series_name }}
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Available Size:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            {{ $product->size }}
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Available Color:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            {{ $product->color }}
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Available Quantity:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            {{ $product->total_stock }}
                                                            {{ $product->primary_unit }}
                                                            ({{ $product->primary_number }}
                                                            {{ $product->primary_unit }} contains
                                                            {{ $product->secondary_number }}
                                                            {{ $product->secondary_unit }})
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Product Status:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            @if ($product->status == 0)
                                                                Inactive
                                                            @else
                                                                Active
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Product Property:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            @if ($product->refundable == 0)
                                                                Refundable
                                                            @else
                                                                Non-Refundable
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <b>Supplier:</b>
                                                        </div>
                                                        <div class="col-md-7">
                                                            {{ $product->vendor->company_name ?? '' }}
                                                        </div>
                                                    </div>
                                                    <hr>

                                                    <h3>Pricing</h3>
                                                    <hr>
                                                    @if ($currentcomp->company->is_importer == 1)
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <b>Original Supplier Price:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                Rs. {{ $product->original_vendor_price }}
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>+ Changing Rate (%):</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                @if ($product->charging_rate == null)
                                                                    0 %
                                                                @else
                                                                    {{ $product->charging_rate }} %
                                                                @endif

                                                                <hr>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>Final Supplier Price:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                Rs. {{ $product->final_vendor_price }}
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>+ Carrying Cost:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                @if ($product->carrying_cost == null)
                                                                    -
                                                                @else
                                                                    Rs. {{ $product->carrying_cost }}
                                                                @endif
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>+ Transportation Cost:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                @if ($product->transportation_cost == null)
                                                                    -
                                                                @else
                                                                    Rs. {{ $product->transportation_cost }}
                                                                @endif
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>+ Miscellaneous Cost:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                @if ($product->other_cost == null)
                                                                    -
                                                                @else
                                                                    Rs. {{ $product->other_cost }}
                                                                    ({{ $product->miscellaneous_percent }} %)
                                                                @endif
                                                                <hr>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>Cost of Product:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                Rs. {{ $product->cost_of_product }}
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>Custom Duty:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                @if ($product->custom_duty == null)
                                                                    -
                                                                @else
                                                                    ({{ $product->custom_duty }} %)
                                                                @endif
                                                                <hr>
                                                            </div>

                                                            <div class="col-md-5">
                                                            </div>
                                                            <div class="col-md-7">
                                                                Rs. {{ $product->after_custom }}
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>+TAX:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                @if ($product->tax == null)
                                                                    -
                                                                @else
                                                                    ({{ $product->tax }} %)
                                                                @endif
                                                                <hr>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>Total Cost:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                Rs. {{ $product->total_cost }}
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>+ Profit Margin:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                @if ($product->margin_type == "fixed")
                                                                    Rs. {{ $product->margin_value }}
                                                                @else
                                                                    {{ $product->margin_value }} %
                                                                @endif
                                                                <hr>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>Product Price:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                Rs. {{ $product->product_price }}
                                                            </div>
                                                        </div>

                                                    @else
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <b>Purchase Price:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                Rs. {{ $product->original_vendor_price }}
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>+ Profit Margin:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                @if ($product->margin_type == "fixed")
                                                                    Rs. {{ $product->margin_value }}
                                                                @else
                                                                    {{ $product->margin_value }} %
                                                                @endif
                                                                <hr>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <b>Selling Price:</b>
                                                            </div>
                                                            <div class="col-md-7">
                                                                Rs. {{ $product->product_price }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2>Product Images</h2>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @if (count($product_images) == 0)
                                                            <img src="{{ Storage::disk('uploads')->url('noimage.jpg') }}"
                                                                alt="" style="width:200px; max-height: 200px;">
                                                        @else
                                                            @foreach ($product_images as $image)
                                                                <div class="col-md-12 mt-2">
                                                                    <a href="{{ Storage::disk('uploads')->url($image->location) }}"
                                                                        target="_blank">
                                                                        <img src="{{ Storage::disk('uploads')->url($image->location) }}"
                                                                            alt="{{ $product->product_name }}"
                                                                            style="height: 200px; width: 200px;">
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($product->has_serial_number == 0)
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h2>Product Barcode</h2>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row my-3">
                                                            <div class="col-md-6">
                                                                <div class="text-center">

                                                                    <h5 class="bold mb-3">{{ $product->product_name }}
                                                                    </h5>

                                                                    <center>
                                                                        <div>
                                                                            <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($product->product_code, 'C93', 1.5, 45) }}"
                                                                                alt="images">
                                                                        </div>

                                                                    </center>
                                                                    <p class="bold mt-2">
                                                                        Rs.{{ $product->product_price }}
                                                                    </p>
                                                                    <button type="button" class="btn btn-primary"
                                                                        data-toggle="modal" data-target="#barcodprint">Print
                                                                        Barcode</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card"><div class="card-header">
                                                    <h2>Product QRcodes</h2>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row my-3">
                                                        <div class="col-md-6">
                                                            <div class="text-center">
                                                                <h5 class="bold mb-3">{{ $product->product_name }}
                                                                </h5>
                                                                <center>
                                                                    <div>
                                                                        <div class="mb-3">{!! DNS2D::getBarcodeHTML($product->product_code, 'QRCODE') !!}
                                                                        </div>
                                                                    </div>
                                                                </center>
                                                                <p class="bold mt-2">
                                                                    Rs.{{ $product->product_price }}
                                                                </p>
                                                                <button type="button" class="btn btn-primary"
                                                                    data-toggle="modal" data-target="#qrcodprint">Print
                                                                    QRcode</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-md-12">
                                            <div class="btn-bulk">
                                                <a href="{{ route('product.edit', $product->id) }}"
                                                    class="btn btn-primary">Edit</a>
                                                <a href="{{ route('transferproducts', $product->id) }}"
                                                    class="btn btn-secondary">Transfer Stock</a>


                                                @if ($product->has_serial_number == 1)
                                                    <a href="{{ route('viewProductBarCodes', $product->id) }}"
                                                        class="btn btn-primary">View Barcodes </a>
                                                    <a href="{{ route('viewProductQRCodes', $product->id) }}"
                                                        class="btn btn-secondary">View QRcodes</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="modal fade" id="barcodprint" tabindex="-1" role="dialog"
                                            aria-labelledby="barcodprintLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="barcodprintLabel">
                                                            {{ $product->product_name }} Barcode</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" class="pro_id" name="pro_id"
                                                            value="{{ $product->id }}">
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                                <input type="number" class="form-control tot_num"
                                                                    name="tot_num"
                                                                    placeholder="Enter Print Quantity Max(500)">
                                                            </div>
                                                            <div class="col-md-2 pl-0">
                                                                <a href="javascript:void(0)" class="btn btn-primary print"
                                                                    data-dismiss="modal">Print</a>
                                                            </div>
                                                        </div>
                                                        <p class='text-danger msg off'>Quantity can't be more than
                                                            500 </p>
                                                        <a style="display:none;" class="btnprint link btn-primary">click</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="qrcodprint" tabindex="-1" role="dialog"
                                            aria-labelledby="qrcodprintLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="qrcodprintLabel">
                                                            {{ $product->product_name }} Barcode</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" class="pro_id" name="pro_id"
                                                            value="{{ $product->id }}">
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                                <input type="number" class="form-control qrtot_num"
                                                                    name="tot_num"
                                                                    placeholder="Enter Print Quantity Max(500)">
                                                            </div>
                                                            <div class="col-md-2 pl-0">
                                                                <a href="javascript:void(0)"
                                                                    class="btn btn-primary qrprint"
                                                                    data-dismiss="modal">Print</a>
                                                            </div>
                                                        </div>
                                                        <p class='text-danger qrmsg off'>Quantity can't be more than
                                                            500 </p>
                                                        <a style="display:none;" class="qrbtnprint link">click</a>
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
    <script type="text/javascript">
        $('.tot_num').change(function() {
            var totnum = $(this).val();
            if (totnum > 500) {
                $(this).parents().find('.print').attr("disabled", true);
                $(this).parents().find('.msg').removeClass('off');
            } else {
                $(this).parents().find('.print').attr("disabled", false);
                $(this).parents().find('.msg').addClass('off');
            }
        })

        // $('.tot_num_secondary').change(function() {
        //     var totnum = $(this).val();
        //     if (totnum > 500) {
        //         $(this).parents().find('.secondaryPrint').attr("disabled", true);
        //         $(this).parents().find('.secondaryMsg').removeClass('off');
        //     } else {
        //         $(this).parents().find('.secondaryPrint').attr("disabled", false);
        //         $(this).parents().find('.secondaryMsg').addClass('off');
        //     }
        // })

        $('.qrtot_num').change(function() {
            var totnum = $(this).val();
            if (totnum > 500) {
                $(this).parents().find('.qrprint').attr("disabled", true);
                $(this).parents().find('.qrmsg').removeClass('off');
            } else {
                $(this).parents().find('.qrprint').attr("disabled", false);
                $(this).parents().find('.qrmsg').addClass('off');
            }
        })


        // $('.secondary_qrtot_num').change(function() {
        //     var totnum = $(this).val();
        //     if (totnum > 500) {
        //         $(this).parents().find('.secondaryqrprint').attr("disabled", true);
        //         $(this).parents().find('.secondaryqrmsg').removeClass('off');
        //     } else {
        //         $(this).parents().find('.secondaryqrprint').attr("disabled", false);
        //         $(this).parents().find('.secondaryqrmsg').addClass('off');
        //     }
        // })

        $('.print').click(function() {
            var qty = $('.tot_num').val();
            var pro_id = $('.pro_id').val();
            var uri = "{{ url('/product/barcodeprint') }}";
            uri = uri + '/' + pro_id + '/' + qty;

            $('.btnprint').attr('href', uri);
            $('.btnprint').trigger('click');
        })


        // $('.secondaryPrint').click(function() {
        //     var qty = $('.tot_num_secondary').val();
        //     var secondary_pro_id = $('.secondary_pro_id').val();
        //     var uri = "{{ url('/product/secondarybarcodeprint') }}";
        //     uri = uri + '/' + secondary_pro_id + '/' + qty;

        //     $('.secondarybtnprint').attr('href', uri);
        //     $('.secondarybtnprint').trigger('click');
        // })


        $('.qrprint').click(function() {
            var qty = $('.qrtot_num').val();
            var pro_id = $('.pro_id').val();
            var uri = "{{ url('/product/qrcodeprint') }}";
            uri = uri + '/' + pro_id + '/' + qty;

            $('.qrbtnprint').attr('href', uri);
            $('.qrbtnprint').trigger('click');
        })


        // $('.secondaryqrprint').click(function() {
        //     var qty = $('.secondary_qrtot_num').val();
        //     var secondary_pro_id = $('.secondary_pro_id').val();
        //     var uri = "{{ url('/product/secondaryqrcodeprint') }}";
        //     uri = uri + '/' + secondary_pro_id + '/' + qty;

        //     $('.secondaryqrbtnprint').attr('href', uri);
        //     $('.secondaryqrbtnprint').trigger('click');
        // })

        $(document).ready(function() {
            $('.btnprint').printPage();
            $('.qrbtnprint').printPage();
            // $('.secondarybtnprint').printPage();
            // $('.secondaryqrbtnprint').printPage();
        });
    </script>

    <script>
        function addGodownRow(divName) {
            let checkedSerialNumber = $("input[name='check_serial_number']").is(":checked");
            var optionval = $("#headoption").val();
            var row = $("#product_godown tbody tr").length;
            var count = row + 1;
            var limits = 500;
            var tabin = 0;
            if (count == limits){
                alert("You have reached the limit of adding " + count + " inputs")
            }else {
                var newdiv = document.createElement('tr');
                var tabin = "goDown_" + count;
                var tabindex = count * 2;
                let serialModelHtml = serialModelForm(count);
                let dataToggleHtml = checkedSerialNumber ? `data-toggle='modal' data-target="#stock_add_${count}"` : '';
                newdiv = document.createElement("tr");
                newdiv.setAttribute('data-id', count);
                newdiv.innerHTML = `<td>
                                <select name='godown[]' id='goDown_${count}' class='form-control godown' required>
                                </select>
                            </td>
                            <td>
                                <input type='text' name='floor_no[]' placeholder='Floor no.' class='form-control text-center'>
                            </td>
                            <td>
                                <input type='text' name='rack_no[]' placeholder='Rack no.' class='form-control text-center'>
                            </td>
                            <td>
                                <input type='text' name='row_no[]' placeholder='Row no.' class='form-control text-center'>
                            </td>
                            <td>
                                <input type='number' name='alert_on[]' placeholder='Alert On Stock??' class='form-control text-right'>
                            </td>
                            <td>
                                <input type='number' name='stock[]' class='form-control godown_stock text-right' value placeholder='How Much Stock???' id='stock_${count}' onkeyup='calculateTotal(${count})' ${dataToggleHtml} >
                                ${serialModelHtml}
                            </td>
                            <td>
                                <button  class='btn btn-primary icon-btn btn-sm' type='button' data-id='${count}'  onclick='deletegodownrow(this)'>
                                    <i class='fa fa-trash'></i>
                                </button>
                            </td>`;
                document.getElementById(divName).appendChild(newdiv);
                document.getElementById(tabin).focus();
                $("#goDown_" + count).html(optionval);
                count++;
                $("select.form-control:not(.dont-select-me)").select2({
                    // placeholder: "--Select One--",
                    // allowClear: true
                });
            }
            initializedSerialNumberModalEvent();
            initializedSerialNumberClickEvent();
        }

        function dbtvouchercalculation(sl) {
            var gr_tot = 0;
            $(".godown_stock").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
            });
            $("#stockTotal").val(gr_tot.toFixed(0, 2));
        }

        function calculateTotal(sl) {
            var gr_tot1 = 0;
            var gr_tot = 0;
            $(".godown_stock").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
            });
            $(".creditPrice").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot1 += parseFloat(this.value))
            });
            $("#stockTotal").val(gr_tot.toFixed(2, 2));
            $("#creditTotal").val(gr_tot1.toFixed(2, 2));

            if ($(".godown_stock").value != 0) {
                $(".creditPrice").attr('disabled');
            }
        }

        function deletegodownrow(e) {
            var t = $("#product_godown > tbody > tr").length;
            if (1 == t) alert("There only one row you can't delete.");
            else {
                var a = e.parentNode.parentNode;
                a.parentNode.removeChild(a)
            }
            $("input[name='serial_numbers_"+dataId+"[]']").remove();
            calculateTotal()
        }


        function serialModelForm(count) {

            return `<div class='modal fade text-left serial-number__modal' id='stock_add_${count}' data-id='${count}' tabindex='-1' role='dialog'
                aria-labelledby='exampleModalLabel' aria-hidden='true'>
                <div class='modal-dialog' role='document' style='max-width: 600px;'>
                    <div class='modal-content'>
                        <div class='modal-header text-center'>
                            <h2 class='modal-title' id='exampleModalLabel'>Enter serial number</h2>
                            <button type='button' class='close' data-dismiss='modal'
                                aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <div class='modal-body' id='modal_body_${count}'>
                            <div class='row serial_number_list'>
                                <div class='col-md-9'>
                                    <input type='text' class='form-control input__serial-number' name='serial_numbers_${count}[]' placeholder='Write a serial number'>
                                </div>
                            </div>

                            <div class='mt-2'>
                                <a href='' class='btn btn-primary icon-btn btn-sm' title='New Serial Number' onclick='addNewModalRow(${count})'><i class='fas fa-plus'></i></a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-id='${count}' class="btn btn-primary submitSerialNumber">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>`
        }

        function addNewModalRow(count, value){
            value = value ? value : '';
            event.preventDefault();
            var x = document.getElementById("modal_body_"+count);
            var new_row = document.createElement("div");
            new_row.className = `row serial_number_list`;
            new_row.innerHTML = `
                                <div class='col-md-9 mt-3'>
                                    <input type='text' class='form-control input__serial-number' value='${value}' placeholder='Write a serial number'>
                                </div>
                                <div class='col-md-3 mt-3 pl-0'>
                                        <button  class='btn btn-primary' type='button' onclick='removeDom(this,".serial_number_list")'>
                                            <i class='fa fa-trash'></i>
                                        </button>
                                </div>
                                `;
            var pos = x.childElementCount + 1;
            x.insertBefore(new_row, x.childNodes[pos]);
        }

        //watch for serial_number model open
        function initializedSerialNumberModalEvent() {
            $('.serial-number__modal').on('show.bs.modal', function (event) {
                var modal = $(this);
                let dataId = modal.attr('data-id');

                $(modal).find('.serial_number_list').remove();
                $('#productForm').find("input[name='serial_numbers_"+dataId+"[]']").each((index,value) => {
                    addNewModalRow(dataId, $(value).val());
                });
            })
        }

        //remove dom element
        function removeDom(event,element)
        {
        $(event).parents(element).remove();
        }

        function initializedSerialNumberClickEvent() {
        //submit serial_number button
        $('.submitSerialNumber').click(function() {

            let dataId = $(this).attr('data-id');
            let hasErrors = false;
            let serialNumbers = [];
            let inputSerialNumberHtml = '';

            $(this).parents('#stock_add_' + dataId).find('.input__serial-number').each((index, value)  => {

                let inputValue = $(value).val();

                console.log("i am ", serialNumbers.includes(inputValue));
                console.log("value is ", inputValue);

                if(serialNumbers.includes(inputValue)){
                    hasErrors = true;
                    $(value).after(`<div class='invalid-error text-danger'><span>Serial Number already exist</span></div>`);
                }

                inputSerialNumberHtml += `<input type="hidden" class="hidden__serial-number" name="serial_numbers_${dataId}[]" value="${inputValue}" readonly>`;

                //if the value is not empty
                if(inputValue){
                    serialNumbers.push(inputValue);
                }
            });

            if(hasErrors) return;

            $("input[name='serial_numbers_"+dataId+"[]']").remove();
            $("#stock_"+dataId).val(serialNumbers.length);
            $('#productForm').append(inputSerialNumberHtml)
            calculateTotal(dataId);
            $('#stock_add_'+dataId).modal('hide');
        });
        }
    </script>
    <script>
        $(document).ready(function() {
            $(".godown").select2();
        });
        window.onload = function() {
            addGodownRow('godown_body');
        }
    </script>
@endpush

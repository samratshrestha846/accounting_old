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
                    <h1>Filter Products</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('product.create') }}" class="global-btn">Create New Item</a>
                        <a href="{{ route('damaged_products.create') }}" class="global-btn">Entry Damaged
                            Product</a> @if ($currentcomp->company->is_importer == 1) <a href="#" data-toggle='modal' data-target='#upload_csv' data-toggle='tooltip' data-placement='top' class="global-btn" title="Upload Product from CSV">Upload CSV(Product Upload)</a> <a href="#" data-toggle='modal' data-target='#update_csv' data-toggle='tooltip' data-placement='top' class="global-btn" title="Update Product from CSV">Upload CSV(For Product update)</a> @else <a href="#" data-toggle='modal' data-target='#upload_csv_non_importer' data-toggle='tooltip' data-placement='top' class="global-btn" title="Upload Product from CSV">Upload CSV(Product Upload)</a> <a href="#" data-toggle='modal' data-target='#update_csv_non_importer' data-toggle='tooltip' data-placement='top' class="global-btn" title="Update Product from CSV">Upload CSV(For Product update)</a> @endif
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

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>Filter products by Godowns</h2>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('extraProduct') }}" method="POST">
                                                @csrf
                                                @method("POST")
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="Godown">Select a godown to filter:</label>
                                                            <select name="godown" class="form-control godown" required>
                                                                <option value="all">All Godowns</option>
                                                                @foreach ($godowns as $godown)
                                                                    <option value="{{ $godown->id }}">
                                                                        {{ $godown->godown_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <p class="text-danger">
                                                                {{ $errors->first('godown') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                        <label for="">&nbsp;</label>
                                                        <button type="submit" class="btn btn-primary btn-sm"
                                                            name="by_godown">Generate</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>Filter products by Profit Margin / Amount</h2>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('extraProduct') }}" method="POST">
                                                @csrf
                                                @method("POST")
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="margin_amount">Select one option:</label>
                                                            <select name="margin_amount" class="form-control">
                                                                <option value="">--Select one--</option>
                                                                <option value="0">By Low Profit Margin </option>
                                                                <option value="1">By High Profit Margin </option>
                                                                <option value="2">By Lowest Selling Price</option>
                                                                <option value="3">By Highest Selling Price</option>
                                                            </select>
                                                            <p class="text-danger">
                                                                {{ $errors->first('margin_amount') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="">&nbsp;</label>
                                                        <button type="submit" class="btn btn-primary btn-sm"
                                                            name="by_margin">Generate</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h2>Filter Products By {{ $filter }} </h2>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12 ">
                                        <div class="m-0 float-right">
                                            <form class="form-inline" action="{{ route('searchproduct') }}"
                                                method="POST">
                                                @csrf
                                                <div class="form-group mx-sm-3 mb-2">
                                                    <label for="search" class="sr-only">Search</label>
                                                    <input type="text" class="form-control" id="search" name="search"
                                                        placeholder="Search">
                                                </div>
                                                <button type="submit" class="btn btn-primary mb-2 btn-sm"><i
                                                        class="fa fa-search"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered data-table text-center mt-2">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Product SKU</th>
                                                    <th class="text-nowrap">Product Name</th>
                                                    <th class="text-nowrap">Product Category</th>
                                                    <th class="text-nowrap">Brand</th>
                                                    <th class="text-nowrap">Series</th>
                                                    <th class="text-nowrap" style="width: 13%;">In stock (Unit)</th>
                                                    <th class="text-nowrap">Cost of Product</th>
                                                    <th class="text-nowrap">Profit Margin (%)</th>
                                                    <th class="text-nowrap">Selling Price</th>
                                                    {{-- <th class="text-nowrap">Godown</th> --}}
                                                    <th class="text-nowrap">Status</th>
                                                    <th class="text-nowrap" style="width: 15%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($products as $product)
                                                    <tr>
                                                        <td class="text-nowrap">{{ $product->product_code }}</td>
                                                        <td class="text-nowrap">{{ $product->product_name }}</td>
                                                        <td class="text-nowrap">
                                                            {{ $product->category->category_name }}
                                                        </td>
                                                        <td class="text-nowrap">{{ $product->brand_id == null ? '' : $product->brand->brand_name }}</td>
                                                        <td class="text-nowrap">{{ $product->series_id == null ? '' :$product->series->series_name }}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            {{ $product->total_stock }} {{ $product->primary_unit }}
                                                            <br>
                                                            ({{ $product->primary_number }}
                                                            {{ $product->primary_unit }} contains
                                                            {{ $product->secondary_number }}
                                                            {{ $product->secondary_unit }})
                                                        </td>
                                                        <td class="text-nowrap">Rs. {{ $product->cost_of_product }}
                                                        </td>
                                                        <td class="text-nowrap">{{ $product->profit_margin }} %</td>
                                                        <td class="text-nowrap">Rs. {{ $product->product_price }}</td>
                                                        <td class="text-nowrap">
                                                            {{ $product->status == 1 ? 'Approved' : 'Not Approved' }}
                                                        </td>
                                                        <td class="text-nowrap" style="width: 120px;">
                                                            <div class="btn-bulk justify-content-center">
                                                                @php
                                                                $editurl = route('product.edit', $product->id);
                                                                $showurl = route('product.show', $product->id);
                                                                $deleteurl = route('product.destroy', $product->id);
                                                                $csrf_token = csrf_token();
                                                                $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                                                                                                                                                <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                                                                                                                                                <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#deleteproduct$product->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                                                                                                                                                <a href='#' class='edit btn btn-secondary icon-btn btn-sm' title='Barcode' data-toggle='modal' data-target='#barcodeprint$product->id'><i class='fas fa-barcode'></i></a>
                                                                                                                                                                                                <a href='#' class='edit btn btn-primary icon-btn btn-sm' title='QRcode' data-toggle='modal' data-target='#qrcodeprint$product->id'><i class='fas fa-qrcode'></i></a>
                                                                                                                                                                                                <!-- Modal -->
                                                                                                                                                                                                    <div class='modal fade text-left' id='deleteproduct$product->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                                                                                                                                                                                                    <div class='modal fade' id='barcodeprint$product->id' tabindex='-1' role='dialog' aria-labelledby='barcodeprint$product->idLabel' aria-hidden='true'>
                                                                                                                                                                                                        <div class='modal-dialog' role='document'>
                                                                                                                                                                                                            <div class='modal-content'>
                                                                                                                                                                                                                <div class='modal-header'>
                                                                                                                                                                                                                <h5 class='modal-title' id='barcodeprint$product->idLabel'>$product->product_name Barcode</h5>
                                                                                                                                                                                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                    <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                </button>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                <div class='modal-body'>
                                                                                                                                                                                                                <input type='hidden' class='pro_id' name='pro_id' value='$product->id'>
                                                                                                                                                                                                                <div class='row'>
                                                                                                                                                                                                                    <div class='col-md-10'>
                                                                                                                                                                                                                        <input type='number' class='form-control tot_num' name='tot_num' placeholder='Enter Print Quantity Max(500)'>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                    <div class='col-md-2 pl-0'>
                                                                                                                                                                                                                        <a href='javascript:void(0)' class='btn btn-secondary print' data-dismiss='modal'>Print</a>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                <p class='text-danger msg off'>Quantity can't be more than 500 </p>
                                                                                                                                                                                                                <a style='display:none;' class=' btnprint link'>click</a>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                            </div>
                                                                                                                                                                                                        </div>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                    <div class='modal fade' id='qrcodeprint$product->id' tabindex='-1' role='dialog' aria-labelledby='qrcodeprint$product->idLabel' aria-hidden='true'>
                                                                                                                                                                                                        <div class='modal-dialog' role='document'>
                                                                                                                                                                                                            <div class='modal-content'>
                                                                                                                                                                                                                <div class='modal-header'>
                                                                                                                                                                                                                <h5 class='modal-title' id='qrcodeprint$product->idLabel'>$product->product_name QRcode</h5>
                                                                                                                                                                                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                    <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                </button>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                <div class='modal-body'>
                                                                                                                                                                                                                <input type='hidden' class='qrpro_id' name='pro_id' value='$product->id'>
                                                                                                                                                                                                                <div class='row'>
                                                                                                                                                                                                                    <div class='col-md-10'>
                                                                                                                                                                                                                        <input type='number' class='form-control qrtot_num' name='tot_num' placeholder='Enter Print Quantity Max(500)'>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                    <div class='col-md-2 pl-0'>
                                                                                                                                                                                                                        <a href='javascript:void(0)' class='btn btn-secondary qrprint' data-dismiss='modal'>Print</a>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                <p class='text-danger qrmsg off'>Quantity can't be more than 500 </p>
                                                                                                                                                                                                                <a style='display:none;' class=' qrbtnprint link'>click</a>
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
                                                    <tr><td colspan="11">No products.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-5">
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
                                                <div class="col-md-7">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $products->links() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class='modal fade text-left' id='upload_csv' tabindex='-1' role='dialog'
                                aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document' style="max-width: 800px;">
                                    <div class='modal-content'>
                                        <div class='modal-header text-center'>
                                            <h2 class='modal-title' id='exampleModalLabel'>Upload CSV for Product</h2>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                            <form action="{{ route('import') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method("POST")
                                                <div class="row">

                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="csv_file">CSV file<i class="text-danger">*</i>
                                                            </label>
                                                            <input type="file" name="csv_file" class="form-control"
                                                                required>
                                                            <p class="text-danger">
                                                                {{ $errors->first('csv_file') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-success btn-sm"
                                                    name="modal_button">Save</button>
                                                <a href="{{ route('export') }}" class="btn btn-primary btn-sm">Download
                                                    Format</a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class='modal fade text-left' id='upload_csv_non_importer' tabindex='-1' role='dialog'
                                aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document' style="max-width: 800px;">
                                    <div class='modal-content'>
                                        <div class='modal-header text-center'>
                                            <h2 class='modal-title' id='exampleModalLabel'>Upload CSV for Product</h2>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                            <form action="{{ route('importNonImporter') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method("POST")
                                                <div class="row">

                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="csv_file">CSV file<i class="text-danger">*</i>
                                                            </label>
                                                            <input type="file" name="csv_file" class="form-control"
                                                                required>
                                                            <p class="text-danger">
                                                                {{ $errors->first('csv_file') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-success btn-sm"
                                                    name="modal_button">Save</button>
                                                <a href="{{ route('exportNonImporter') }}"
                                                    class="btn btn-primary btn-sm">Download Format</a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class='modal fade text-left' id='update_csv' tabindex='-1' role='dialog'
                                aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document' style="max-width: 800px;">
                                    <div class='modal-content'>
                                        <div class='modal-header text-center'>
                                            <h2 class='modal-title' id='exampleModalLabel'>Update product from CSV</h2>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                            <form action="{{ route('importUpdate') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method("POST")
                                                <div class="row">

                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="update_csv_file">CSV file<i
                                                                    class="text-danger">*</i> </label>
                                                            <input type="file" name="update_csv_file"
                                                                class="form-control" required>
                                                            <p class="text-danger">
                                                                {{ $errors->first('update_csv_file') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-success btn-sm"
                                                    name="modal_button">Save</button>
                                                <a href="{{ route('exportUpdate') }}"
                                                    class="btn btn-primary btn-sm">Download Format</a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class='modal fade text-left' id='update_csv_non_importer' tabindex='-1' role='dialog'
                                aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document' style="max-width: 800px;">
                                    <div class='modal-content'>
                                        <div class='modal-header text-center'>
                                            <h2 class='modal-title' id='exampleModalLabel'>Update product from CSV</h2>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                            <form action="{{ route('importNonUpdate') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method("POST")
                                                <div class="row">

                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="update_csv_file">CSV file<i
                                                                    class="text-danger">*</i> </label>
                                                            <input type="file" name="update_csv_file"
                                                                class="form-control" required>
                                                            <p class="text-danger">
                                                                {{ $errors->first('update_csv_file') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-success btn-sm"
                                                    name="modal_button">Save</button>
                                                <a href="{{ route('exportNonUpdate') }}"
                                                    class="btn btn-primary btn-sm">Download Format</a>
                                            </form>
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

    <script>
        $(document).ready(function() {
            $(".godown").select2();
        });
    </script>
    <script type="text/javascript">
        $('.tot_num').each(function() {
            $(this).change(function() {
                var totnum = $(this).val();
                if (totnum > 500) {
                    $(this).parents('.modal-body').find('.print').attr("disabled", true);
                    $(this).parents('.modal-body').find('.msg').removeClass('off');
                } else {
                    $(this).parents('.modal-body').find('.print').attr("disabled", false);
                    $(this).parents('.modal-body').find('.msg').addClass('off');
                }
            })

        })
        $('.print').each(function() {
            $(this).click(function() {
                var qty = $(this).parents('.modal-body').find('.tot_num').val();
                var pro_id = $(this).parents('.modal-body').find('.pro_id').val();
                var uri = "{{ url('/product/barcodeprint') }}";
                uri = uri + '/' + pro_id + '/' + qty;

                $('.btnprint').attr('href', uri);
                $('.btnprint').trigger('click');
            })
        })
        $('.qrtot_num').each(function() {
            $(this).change(function() {
                var totnum = $(this).val();
                if (totnum > 500) {
                    $(this).parents('.modal-body').find('.qrprint').attr("disabled", true);
                    $(this).parents('.modal-body').find('.qrmsg').removeClass('off');
                } else {
                    $(this).parents('.modal-body').find('.qrprint').attr("disabled", false);
                    $(this).parents('.modal-body').find('.qrmsg').addClass('off');
                }
            })

        })
        $('.qrprint').each(function() {
            $(this).click(function() {
                var qty = $(this).parents('.modal-body').find('.qrtot_num').val();
                var pro_id = $(this).parents('.modal-body').find('.qrpro_id').val();
                var uri = "{{ url('/product/qrcodeprint') }}";
                uri = uri + '/' + pro_id + '/' + qty;

                $('.qrbtnprint').attr('href', uri);
                $('.qrbtnprint').trigger('click');
            })
        })
        $(document).ready(function() {
            $('.btnprint').printPage();
            $('.qrbtnprint').printPage();
        });
    </script>
@endpush

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
                    <h1>Products</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('product.index') }}" class="global-btn">View all Items</a> <a
                            href="{{ route('damaged_products.create') }}" class="global-btn">Entry Damaged
                            Product</a>
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
                                                        <label>&nbsp;</label>
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
                                                        <label>&nbsp;</label>
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
                                <div class="card-header d-flex justify-content-between">
                                    <h2>Products in {{ $related_godown->godown_name }} </h2>
                                    <div class="stock d-flex" style="float:right;">
                                        <span class="badge badge-success btn btn-secondary">Total Stock Validation:
                                           Rs. {{ number_format($productvalidationGodown->total_sum,2) }}</span>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered data-table text-center mt-2" id="myTable">
                                            <thead class="topsearch">
                                                <tr>
                                                    <th class="text-nowrap">Product SKU</th>
                                                    <th class="text-nowrap">Product Name</th>
                                                    <th class="text-nowrap">Product Category</th>
                                                    <th class="text-nowrap">Brand</th>
                                                    <th class="text-nowrap">Series</th>
                                                     <th class="text-nowrap">In stock (Unit)</th>
                                                    <th class="text-nowrap">Cost of Product</th>
                                                    <th class="text-nowrap">Profit Margin</th>
                                                    <th class="text-nowrap">Product Price</th>
                                                    <th class="text-nowrap">Stock Validation</th>
                                                    <th class="text-nowrap">Status</th>
                                                    <th class="text-nowrap" width="15%">Action</th>
                                                </tr>
                                            </thead>
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Product SKU</th>
                                                    <th class="text-nowrap">Product Name</th>
                                                    <th class="text-nowrap">Product Category</th>
                                                    <th class="text-nowrap">Brand</th>
                                                    <th class="text-nowrap">Series</th>
                                                     <th class="text-nowrap">In stock (Unit)</th>
                                                    <th class="text-nowrap">Cost of Product</th>
                                                    <th class="text-nowrap">Profit Margin</th>
                                                    <th class="text-nowrap">Product Price</th>
                                                    <th class="text-nowrap">Stock Validation</th>
                                                    <th class="text-nowrap">Status</th>
                                                    <th class="text-nowrap" width="15%">Action</th>
                                                </tr>
                                            </thead>
                                            {{-- <tbody>
                                                @forelse ($godownproducts as $godownproduct)
                                                    @forelse ($godownproduct->products as $product)
                                                        <tr>
                                                            <td>{{ $product->product_code }}</td>
                                                            <td>{{ $product->product_name }}</td>
                                                            <td>{{ $product->category->category_name }}</td>
                                                            <td class="text-nowrap">{{ $product->brand_id == null ? '' : $product->brand->brand_name }}</td>
                                                            <td class="text-nowrap">{{ $product->series_id == null ? '' :$product->series->series_name }}
                                                            </td>
                                                            <td>
                                                                {{ $godownproduct->stock }}
                                                                {{ $product->primary_unit }}
                                                                <br>
                                                                ({{ $product->primary_number }}
                                                                {{ $product->primary_unit }} =
                                                                {{ $product->secondary_number }}
                                                                {{ $product->secondary_unit }})
                                                            </td>
                                                            <td>Rs. {{ $product->cost_of_product }}</td>
                                                            <td>Rs. {{ $product->product_price }}</td>
                                                            <td>{{ $product->status == 1 ? 'Approved' : 'Not Approved' }}
                                                            </td>
                                                            <td>
                                                            <div class="btn-bulk justify-content-center">
                                                                @php
                                                                    $editajax = route('product.edit', $product->id);
                                                                    $showurl = route('product.show', $product->id);
                                                                    $deleteurl = route('product.destroy', $product->id);
                                                                    $csrf_token = csrf_token();
                                                                    $btn = "<a href='$showurl' class='edit btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                            <a href='$editurl' class='edit btn btn-secondary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                            <button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#deleteproduct$product->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                            <a href='#' class='edit btn btn-secondary btn-sm' title='Barcode' data-toggle='modal' data-target='#barcodeprint$product->id'><i class='fas fa-barcode'></i></a>
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
                                                        <tr><td colspan="9">No products.</td></tr>
                                                    @endforelse
                                                @empty
                                                    <tr><td colspan="9">No products.</td></tr>
                                                @endforelse
                                            </tbody> --}}
                                        </table>
                                        {{-- <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="text-sm">
                                                        Showing <strong>{{ $godownproducts->firstItem() }}</strong> to
                                                        <strong>{{ $godownproducts->lastItem() }} </strong> of <strong>
                                                            {{ $godownproducts->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $godownproducts->links() }}</span>
                                                </div>
                                            </div>
                                        </div> --}}
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
        $(document).ready(function() {
            $('.btnprint').printPage();
        });

        $(document).ready(function() {
        $('#myTable thead.topsearch th').each(function() {
            var title = $(this).text();
            if(title != ""){
             $(this).html('<input class="" style="width:60px" type="text" placeholder="Search ' + title + '" />');
            }
        });
        });
        $(function () {
            var filtergodown_id = <?php echo json_encode($godown_id) ?>;
            var url = "{{ route('filterProducts', ':filtergodown_id') }}";
            url= url.replace(':filtergodown_id', filtergodown_id);

            var table = $('#myTable').DataTable({
                searchPanes: {
                    viewTotal: true
                },
                dom: 'Plfrtip',
                processing: true,
                serverSide: true,
                pageLength:25,
                ajax: url,
                columns: [
                    {data: 'product.product_code'},
                    {data: 'product.product_name'},
                    {data: 'product.category.category_name'},
                    {data: 'brand_name'},
                    {data: 'series_name'},
                    {data: 'total_stock'},
                    {data: 'product.cost_of_product'},
                    {data: 'margin_type', name: 'margin_type'},
                    {data: 'product.product_price'},
                    {data: 'stock_validation'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            table.columns().every( function() {
            var that = this;

            $('input', this.header()).on('keyup change', function() {
                if (that.search() !== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });
         });
        });
    </script>
@endpush

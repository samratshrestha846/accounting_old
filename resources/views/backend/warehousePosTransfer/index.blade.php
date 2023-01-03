@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Warehouse To Outlet Product Transfers </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('outlettransfer.create') }}" class="global-btn">Create Outlet Transfers</a>
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
                    <div class="card-body table-responsive">
                        <table class="table table-bordered data-table text-center global-table">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-nowrap">Outlet</th>
                                    <th class="text-nowrap">Product</th>
                                    <th class="text-nowrap">Opening Stock</th>
                                    <th class="text-nowrap">Stock</th>
                                    <th class="text-nowrap">Stock Alert</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($outletproducts as $outletproduct)
                                    <tr>
                                        <td class="text-nowrap">{{ $outletproduct->outlet->name }}</td>
                                        <td class="text-nowrap">{{ $outletproduct->product->product_name }} <br>({{ $outletproduct->product->product_code }})</td>
                                        <td class="text-nowrap">{{ $outletproduct->primary_opening_stock }} {{ $outletproduct->product->primaryUnit->unit }}</td>
                                        <td class="text-nowrap">{{ $outletproduct->primary_stock }} {{ $outletproduct->product->primaryUnit->unit }}</td>
                                        <td class="text-nowrap">{{ $outletproduct->primary_stock_alert }} {{ $outletproduct->product->primaryUnit->unit }}</td>
                                        <td class="text-nowrap">
                                            <div class="btn-bulk">
                                                <a href="{{ route('outletproduct.transfer', $outletproduct->product_id) }}" class="edit btn btn-primary icon-btn btn-sm" title="Transfer Details"><i class="fa fa-exchange-alt"></i></a>
                                                <a href="{{ route('outletrecord.product', $outletproduct->outlet_id) }}" class="edit btn btn-secondary icon-btn btn-sm" title="Outlet Product Details"><i class="fa fa-store-alt"></i></a>

                                                <button type="button" class="btn btn-primary icon-btn btn-sm" data-toggle="modal" data-target="#editstock{{ $outletproduct->id }}" data-toggle="tooltip" data-placement="top" title="Edit Stock alert"><i class="fa fa-edit"></i></button>
                                                <!-- Modal -->
                                                <div class="modal fade text-left" id="editstock{{ $outletproduct->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Stock Alerts</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <form action="{{ route('outletStockEdit', $outletproduct->id) }}" method="POST" style="display:inline-block;">
                                                                    @csrf
                                                                    @method("POST")
                                                                    <div class="form-group">
                                                                        <label for="reason">Stock Alert: </label><br>
                                                                            <input type="number" name="primary_stock_alert" value="{{ $outletproduct->primary_stock_alert }}" class="form-control" min="0"/>
                                                                        <br>
                                                                        <button type="submit" class="btn btn-secondary" title="Update">Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="button" class="btn btn-secondary icon-btn btn-sm ml-1" onclick="godownProduct({{ $outletproduct->id }})" data-toggle="modal" data-target="#transferstock{{ $outletproduct->id }}" data-toggle="tooltip" data-placement="top" title="Transfer Back to Godown" @if($outletproduct->primary_stock == 0) disabled @endif><i class="fa fa-sync-alt"></i></button>
                                                <!-- Modal -->
                                                <div class="modal fade text-left" id="transferstock{{ $outletproduct->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document" style="max-width: 650px;">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Stock Transfer back to Godown</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('stockTransferToGodown', $outletproduct->id) }}" method="POST">
                                                                    @csrf
                                                                    @method("POST")
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="reason">Select a godown to transfer: </label><br>
                                                                                <select class="form-control godown" id="godown{{ $outletproduct->id }}" name="godown" required>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12" id="stock_transfer_field_{{ $outletproduct->id }}" style="display: none;">
                                                                            <div class="form-group">
                                                                                <label for="reason">Insert stock to transfer: </label><br>
                                                                                <input type="number" name="stock_to_transfer" class="form-control" min="0" id="stock_input_{{ $outletproduct->id }}" placeholder="Enter Stock To transfer" required/>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12" id="serial_number_info_{{ $outletproduct->id }}" style="display: none;">
                                                                            <div class="form-group">
                                                                                <label for="serial numbers">Choose damaged Serial Numbers:</label>
                                                                                <select name="serial_numbers[]" id="serial_numbers_{{ $outletproduct->id }}" class="form-control serial_numbers" required multiple>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12 text-center">
                                                                            <button type="submit" class="btn btn-secondary" title="Update">Update</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">No any records.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-sm">
                                        Showing <strong>{{ $outletproducts->firstItem() }}</strong> to
                                        <strong>{{ $outletproducts->lastItem() }} </strong> of <strong>
                                            {{ $outletproducts->total() }}</strong>
                                        entries
                                        <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds to
                                            render</span>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <span class="pagination-sm m-0 float-right">{{ $outletproducts->links() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
<script>
    function godownProduct(outletproduct_id)
    {
        var product_id = outletproduct_id;
        godownsInfo(product_id);

        var uriProduct = "{{ route('apiproduct', ':id') }}";
        uriProduct = uriProduct.replace(':id', product_id);
        $.ajax({
            url: uriProduct,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                var productdata = response;

                if(productdata.has_serial_number == 1)
                {
                    console.log("This is serialnumber");
                    document.getElementById("serial_number_info_"+product_id).style.display = 'block';
                    document.getElementById("stock_transfer_field_"+product_id).style.display = 'none';
                    $('#stock_input_'+product_id).attr("disabled", true);
                    $('#serial_numbers_'+product_id).attr("disabled", false);

                    fetchSerialNumbers(product_id);
                }
                else if(productdata.has_serial_number == 0)
                {
                    console.log("This is not serialnumber");
                    document.getElementById("serial_number_info_"+product_id).style.display = 'none';
                    document.getElementById("stock_transfer_field_"+product_id).style.display = "block";
                    $('#serial_numbers_'+product_id).attr("disabled", true);
                    $('#stock_input_'+product_id).attr("disabled", false);
                }
            }
        });
    }

    function godownsInfo(product_id)
    {
        var uri = "{{ route('getGodownsProductId', ':id') }}";
        uri = uri.replace(':id', product_id);
        $.ajax({
            url: uri,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                var godownInfo = response.data;
                document.getElementById("godown" + product_id).innerHTML = `<option value=''>--Select a Godown--</option>` +
                    godownInfo.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.godown_name}</option>`, '');
            }
        });
    }

    function fillSerialNumbers(serial_numbers, product_id)
    {
        document.getElementById("serial_numbers_"+product_id).innerHTML = `<option value=''>--Select option--</option>` +
            serial_numbers.reduce((tmp, x) =>
                `${tmp}<option value='${x.id}'>${x.serial_number}</option>`, '');
    }

    function fetchSerialNumbers(product_id)
    {
        var uri = "{{ route('outletSerialNumbers', ':product_id') }}";
        uri = uri.replace(':product_id', product_id);
        $.ajax({
            url: uri,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                var serial_numbers = response;
                fillSerialNumbers(serial_numbers, product_id);
            }
        });
    }

    $(document).ready(function() {
        $(".godown").select2();
        $(".serial_numbers").select2();
    });


</script>
@endpush

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
                    <h1>Enter Damaged Products </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('product.index') }}" class="global-btn">View All Items</a> <a
                            href="{{ route('damaged_products.index') }}" class="global-btn">View Damaged
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
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('damaged_products.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="products">Damaged Product:</label>
                                                <select name="product" class="form-control product">
                                                    <option value="">--Select a Product--</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" {{ old('product') == $product->id ? 'selected' : '' }}>{{ $product->product_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('product') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="stock_info"></div>
                                        <div class="col-md-3" id="godown_info"></div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="godowns">From Godown:</label>
                                                <select name="godown" class="form-control godown" id="godown">
                                                    <option value="">--Select a product first--</option>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('godown') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3" id="damaged_info" style="display: none;">
                                            <div class="form-group">
                                                <label for="stock">Damaged Stock:</label>
                                                <input type="number" class="form-control" value="{{ old('stock') }}"
                                                    name="stock" placeholder="Damaged Stock (in no.)" id="damaged_input" step=".01" required>
                                                <p class="text-danger">
                                                    {{ $errors->first('stock') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3" id="serial_number_info" style="display: none;">
                                            <div class="form-group">
                                                <label for="serial numbers">Choose damaged Serial Numbers:</label>
                                                <select name="serial_numbers[]" id="serial_numbers" class="form-control" required multiple>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('serial_numbers') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="file">Attach documents (Bills, etc.) (Optional):</label>
                                                <input type="file" name="document" id="file" class="form-control" onchange="loadFile(event)">
                                                <p class="text-danger">
                                                    {{ $errors->first('document') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <img id="output" style="height: 50px;">
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Reason:</label>
                                                <textarea name="reason" cols="30" rows="5" placeholder="Reason for damaging..." class="form-control">{{ old('reason') }}</textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('reason') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12 btn-bulk d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                        </div>
                                    </div>
                                </form>
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
            $(".product").select2();
            $(".godown").select2();
            $("#serial_numbers").select2({
                placeholder: '--Select Godown First--'
            });
        });

        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
    <script>
        $(function() {
            function changed() {
                var products = @php echo json_encode($products); @endphp;
                var procount = products.length;
                var selectedproduct = $(this).find(":selected").val();

                var uri = "{{ route('apiproduct', ':id') }}";
                uri = uri.replace(':id', selectedproduct);
                $.ajax({
                    url: uri,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var productdata = response;

                        if(productdata.has_serial_number == 1)
                        {
                            document.getElementById("serial_number_info").style.display = 'block';
                            document.getElementById("damaged_info").style.display = 'none';
                            $('#damaged_input').attr("disabled", true);
                            $('#serial_numbers').attr("disabled", false);
                            document.getElementById("serial_numbers").innerHTML = `<option value=''>--Select Godown First--</option>`;

                            $('.godown').change(function()
                            {
                                var godown = $(this).children("option:selected").val();

                                function fillSerialNumbers(serial_numbers)
                                {
                                    document.getElementById("serial_numbers").innerHTML = `<option value=''>--Select option--</option>` +
                                        serial_numbers.reduce((tmp, x) =>
                                            `${tmp}<option value='${x.id}'>${x.serial_number}</option>`, '');
                                }

                                function fetchSerialNumbers(from_godwon, product_id)
                                {
                                    var uri = "{{ route('serialNumbers', [':godown_id', ':product_id']) }}";
                                    uri = uri.replace(':godown_id', godown).replace(':product_id', product_id);
                                    $.ajax({
                                        url: uri,
                                        type: 'get',
                                        dataType: 'json',
                                        success: function(response) {
                                            var serial_numbers = response;
                                            fillSerialNumbers(serial_numbers);
                                        }
                                    });
                                }

                                fetchSerialNumbers(godown, selectedproduct);
                            })
                        }
                        else if(productdata.has_serial_number == 0)
                        {
                            document.getElementById("serial_number_info").style.display = 'none';
                            document.getElementById("damaged_info").style.display = 'block';
                            $('#serial_numbers').attr("disabled", true);
                            $('#damaged_input').attr("disabled", false);
                        }
                    }
                });

                for (let x = 0; x < procount; x++) {
                    if (selectedproduct == products[x].id) {
                        document.getElementById("stock_info").innerHTML =
                            `<label for="Product Stock Info">Stock Info:</label>
                                    <p>${products[x].total_stock} ${products[x].primary_unit} (${products[x].primary_number } ${products[x].primary_unit} contains ${products[x].secondary_number} ${products[x].secondary_unit})</p>
                                `;

                        var godowns = products[x].godownproduct;
                        var countgodowns = godowns.length;
                        var stock = '';
                        var godown = '';

                        document.getElementById("godown").innerHTML =
                            "<option value=''>--Select a Godown--</option>" +
                            godowns.reduce((tmp, x) =>
                                `${tmp}<option value='${x.godown.id}'>${x.godown.godown_name}</option>`, '');

                        for (let y = 0; y < countgodowns; y++) {
                            var warehouse = godowns[y].godown.godown_name + " has " + godowns[y].stock + " " +
                                products[x].primary_unit;
                            godown += warehouse + ".</br>"
                        }

                        document.getElementById("godown_info").innerHTML = `<p>${godown}</p>`;
                    }
                }
            }
            $(".product").change(changed);
        })
    </script>
@endpush

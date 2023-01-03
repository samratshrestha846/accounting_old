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
                <h1>Transfer Products</h1>
                <div class="btn-bulk">
                    <a href="{{ route('product.index') }}" class="global-btn">View All Products</a>
                </div>
                <!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if (session('success'))
                        <div class="col-sm-12">
                            <div class="alert  alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="col-sm-12">
                            <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $product->product_name }} (Product SKU: {{ $product->product_code }})</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b>Available Stock: </b></p>
                                </div>
                                <div class="col-md-9">
                                    <p>{{ $product->total_stock }} {{ $product->primary_unit }} ({{ $product->primary_number }} {{ $product->primary_unit }} contains {{ $product->secondary_number }} {{ $product->secondary_unit }})</p>
                                    @foreach ($godown_products as $godown_product)
                                        <p>{{ $godown_product->godown->godown_name }} has {{ $godown_product->stock }} {{ $product->primary_unit }}.</p>
                                    @endforeach
                                </div>

                                <div class="col-md-12">
                                    <form action="{{ route('transferNow') }}" method="POST">
                                        @csrf
                                        @method("POST")
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">From godown: </label>
                                                    <input type="hidden" class="form-control" id="product_id" name="product_id" value="{{ $product->id }}">
                                                    <select name="from_godown" class="form-control from_godown" id="from_godown">
                                                        <option value="">--Select a Godown--</option>
                                                        @foreach ($related_godowns as $godown)
                                                            <option value="{{ $godown->id }}"{{ old('from_godown') == $godown->id ? 'selected' : '' }}>{{ $godown->godown_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('from_godown') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">To godown: </label>
                                                    <select name="to_godown" class="form-control to_godown" id="to_godown">
                                                        <option value="">--Select a Godown first--</option>
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('to_godown') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    @if ($product->has_serial_number == 0)
                                                        <label for="stock">Stock to Transfer:</label>
                                                        <input type="number" class="form-control" name="stock" placeholder="Stocks to transfer" required value=""{{ old('stock') }}>
                                                        <p class="text-danger">
                                                            {{ $errors->first('stock') }}
                                                        </p>
                                                    @elseif ($product->has_serial_number == 1)
                                                        <label for="stock">Choose Serial numbers to transfer:</label>
                                                        <select name="serial_numbers[]" plaeholder="--Select a Godown first--" class="form-control serial_numbers" id="serial_numbers"  multiple="multiple" required>
                                                            {{-- <option value="">--Select a Godown first--</option> --}}
                                                        </select>
                                                        <p class="text-danger">
                                                            {{ $errors->first('serial_numbers') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="remarks">Remarks:</label>
                                                    <textarea name="remarks" cols="30" rows="5" class="form-control" placeholder="Remarks for Stock transfer">{{ old('remarks') }}</textarea>
                                                    <p class="text-danger">
                                                        {{ $errors->first('remarks') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
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
            $(".from_godown").select2();
            $(".to_godown").select2();
            $('.serial_numbers').select2({
                placeholder: '--Select Godown First--',
            });
        });

        $(function()
        {
            $('.from_godown').change(function()
            {
                var from_godown = $(this).children("option:selected").val();
                var product_id = $('#product_id').val();

                function checkProduct(product_id)
                {
                    var uri = "{{ route('apiproduct', ':id') }}";
                    uri = uri.replace(':id', product_id);
                    $.ajax({
                        url: uri,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var product = response;
                            if(product.has_serial_number == 1)
                            {
                                document.getElementById("serial_numbers").innerHTML = `<option value=''>--Select option--</option>`;
                                fetchSerialNumbers(from_godown, product_id);
                            }
                        }
                    });
                }

                function fillToGodown(godowns)
                {
                    document.getElementById("to_godown").innerHTML = `<option value=''>--Select an option--</option>` +
                        godowns.reduce((tmp, x) =>
                            `${tmp}<option value='${x.id}'>${x.godown_name}</option>`, '');
                }

                function fillSerialNumbers(serial_numbers)
                {
                    document.getElementById("serial_numbers").innerHTML = `<option value=''>--Select option--</option>` +
                        serial_numbers.reduce((tmp, x) =>
                            `${tmp}<option value='${x.id}'>${x.serial_number}</option>`, '');
                }

                function fetchGodowns(from_godown)
                {
                    var uri = "{{ route('filterGodown', ':no') }}";
                    uri = uri.replace(':no', from_godown);
                    $.ajax({
                        url: uri,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var godowns = response;
                            fillToGodown(godowns);
                        }
                    });
                }

                function fetchSerialNumbers(from_godwon, product_id)
                {
                    var uri = "{{ route('serialNumbers', [':godown_id', ':product_id']) }}";
                    uri = uri.replace(':godown_id', from_godown).replace(':product_id', product_id);
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

                checkProduct(product_id);
                fetchGodowns(from_godown);
            })
        });
    </script>
@endpush

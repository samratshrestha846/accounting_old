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
                    <h1>Update Damaged Products</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('product.index') }}" class="global-btn">View All Items</a> <a
                            href="{{ route('damaged_products.index') }}" class="global-btn">View Damaged Products</a>
                    </div><!-- /.col -->
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
                                <form action="{{ route('damaged_products.update', $damaged_product->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method("PUT")
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="Product Stock Info">Remaining Stock Info:</label>
                                            <p>{{ $damaged_product->product->total_stock }}
                                                {{ $damaged_product->product->primary_unit }}
                                                ({{ $damaged_product->product->primary_number }}
                                                {{ $damaged_product->product->primary_unit }} contains
                                                {{ $damaged_product->product->secondary_number }}
                                                {{ $damaged_product->product->secondary_unit }})</p>
                                            @foreach ($damaged_product->product->godownproduct as $godown_product)
                                                @php
                                                    $godown = \App\Models\Godown::where('id', $godown_product->godown_id)->first();
                                                @endphp
                                                <p>{{ $godown->godown_name }} has {{ $godown_product->stock }}
                                                    {{ $damaged_product->product->primary_unit }}.</p>
                                            @endforeach
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="products">Damaged Product:</label>
                                                <input type="hidden" value="{{ $damaged_product->product_id }}" name="product">
                                                <select name="product_id" class="form-control product" disabled>
                                                    <option value="">--Select a Product--</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            {{ $product->id == $damaged_product->product_id ? 'selected' : '' }}>
                                                            {{ $product->product_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('product') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="godowns">From Godown:</label>
                                                @if ($damaged_product->product->has_serial_number == 0)
                                                    <select name="godown" class="form-control godown" id="godown">
                                                        @foreach ($related_godowns as $godown)
                                                            <option value="{{ $godown->id }}"
                                                                {{ $godown->id == $damaged_product->godown_id ? 'selected' : '' }}>
                                                                {{ $godown->godown_name }}</option>
                                                        @endforeach
                                                    </select>
                                                @elseif($damaged_product->product->has_serial_number == 1)
                                                    <input type="hidden" value="{{ $damaged_product->godown_id }}" name="godown">
                                                    <select name="godown_info" class="form-control godown" id="godown" disabled>
                                                        @foreach ($related_godowns as $godown)
                                                            <option value="{{ $godown->id }}"
                                                                {{ $godown->id == $damaged_product->godown_id ? 'selected' : '' }}>
                                                                {{ $godown->godown_name }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif

                                                <p class="text-danger">
                                                    {{ $errors->first('godown') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                @if ($damaged_product->product->has_serial_number == 0)
                                                    <label for="stock">Damaged Stock:</label>
                                                    <input type="number" class="form-control"
                                                        value="{{ old('stock', $damaged_product->stock) }}" name="stock"
                                                        placeholder="Damaged Stock (in no.)" step=".01" required>
                                                    <p class="text-danger">
                                                        {{ $errors->first('stock') }}
                                                    </p>
                                                @elseif($damaged_product->product->has_serial_number == 1)
                                                    <label for="serial numbers">Choose damaged Serial Numbers:</label>
                                                    <select name="serial_numbers[]" id="serial_numbers" class="form-control" required multiple>
                                                        <option value="">--Select an serial number--</option>
                                                        @foreach ($all_serial_numbers as $serial_number)
                                                            <option value="{{ $serial_number->id }}" {{ in_array($serial_number->id, $damaged_numbers) ? 'selected' : '' }}>
                                                                {{ $serial_number->serial_number }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('serial_numbers') }}
                                                    </p>
                                                @endif
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
                                            <label for="" style="display: block;">Preview Image</label>
                                            <img id="output" src="{{ Storage::disk('uploads')->url($damaged_product->document) }}" style="height: 50px;">
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Reason:</label>
                                                <textarea name="reason" cols="30" rows="5" placeholder="Reason for damaging..." class="form-control">{{ old('reason', $damaged_product->reason) }}</textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('reason') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12 btn-bulk d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Submit</button>
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
            $(".godown").select2();
            $("#serial_numbers").select2();
        });
    </script>
@endpush

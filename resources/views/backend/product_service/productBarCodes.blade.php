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
                    <h1>{{ $product->product_name }} ({{ $product->product_code }})</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('product.show', $product->id) }}" class="global-btn">Go Back</a>
                        <a href="{{ route('product.index') }}" class="global-btn">View All Products</a>
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
                                    <h2>{{ $product->product_name }} Bar Codes</h2>


                                    <div class="btn-bulk">
                                        @if ($code == "Bar")
                                            <a href="{{ route('barcodeprint', ['id' => $product->id, 'quantity' => 501]) }}" class="btn btn-primary btnprint">Print Barcodes</a>
                                        @elseif ($code == "QR")
                                            <a href="{{ route('qrcodeprint', ['id' => $product->id, 'quantity' => 501]) }}" class="btn btn-primary btnprintqr">Print QRcodes</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($product->godownproduct as $singleGodownProduct)
                                            @foreach($singleGodownProduct->serialnumbers as $productSerialNumber)
                                                <div class="col-md-6 mt-2">
                                                    <div class="text-center">
                                                        <center>
                                                            <div class="mb-3">
                                                                @if ($code == "Bar")
                                                                    <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($product->product_code.'/'.$productSerialNumber->serial_number, 'C39', 1.5, 45) }}"
                                                                                alt="images">
                                                                @elseif ($code == "QR")
                                                                    {!! \DNS2D::getBarcodeHTML($product->product_code.'/'.$productSerialNumber->serial_number, 'QRCODE') !!}
                                                                @endif
                                                            </div>
                                                        </center>

                                                        <p class="bold">
                                                            Serial Number: {{ $productSerialNumber->serial_number }} <br>
                                                            ({{ $singleGodownProduct->godown->godown_name }})
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
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
                    $('.btnprint').printPage();
                    $('.btnprintqr').printPage();
                });
            </script>
        @endpush

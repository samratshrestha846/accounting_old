@extends('backend.layouts.app')
@push('styles')
    <style>

    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Service information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('service.index') }}" class="global-btn">View All Services</a>
                    </div>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>Basic Details</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <b>Service Name:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $service->service_name }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <b>Service Code:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $service->service_code }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <b>Service Category:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $service->category->category_name }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <b>Cost Price:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    Rs. {{ $service->cost_price }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <b>Selling Price:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    Rs. {{ $service->sale_price }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <b>Status:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    @if ($service->status == 0)
                                                        Inapproved
                                                    @else
                                                        Approved
                                                    @endif
                                                </div>
                                            </div>

                                            <hr>
                                            <h3>Description</h3>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <b>Service Details:</b>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $service->description }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>Service Images</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @if (count($service_images) == 0)
                                                    <img src="{{ Storage::disk('uploads')->url('noimage.jpg') }}" alt=""
                                                        style="max-width:200px; max-height: 200px;">
                                                @else
                                                    @foreach ($service_images as $image)
                                                        <div class="col-md-12 mt-2">
                                                            <a href="{{ Storage::disk('uploads')->url($image->location) }}"
                                                                target="_blank">
                                                                <img src="{{ Storage::disk('uploads')->url($image->location) }}"
                                                                    alt="{{ $service->service_name }}" style="max-height: 200px; max-width: 200px;">
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="btn-bulk">
                                <a href="{{ route('service.edit', $service->id) }}" class="btn btn-secondary">Edit</a>
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
        $('.print').click(function() {
            var qty = $('.tot_num').val();
            var pro_id = $('.pro_id').val();
            var uri = "{{ url('/service/barcodeprint') }}";
            uri = uri + '/' + pro_id + '/' + qty;

            $('.btnprint').attr('href', uri);
            $('.btnprint').trigger('click');
        })
        $('.qrprint').click(function() {
            var qty = $('.qrtot_num').val();
            var pro_id = $('.pro_id').val();
            var uri = "{{ url('/service/qrcodeprint') }}";
            uri = uri + '/' + pro_id + '/' + qty;

            $('.qrbtnprint').attr('href', uri);
            $('.qrbtnprint').trigger('click');
        })
        $(document).ready(function() {
            $('.btnprint').printPage();
            $('.qrbtnprint').printPage();
        });
    </script>
    <script>
        function addGodownRow(divName) {
            var optionval = $("#headoption").val();
            var row = $("#service_godown tbody tr").length;
            var count = row + 1;
            var limits = 500;
            var tabin = 0;
            if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
            else {
                var newdiv = document.createElement('tr');
                var tabin = "goDown_" + count;
                var tabindex = count * 2;
                newdiv = document.createElement("tr");
                newdiv.innerHTML = "<td> <select name='godown[]' id='goDown_" + count +
                    "' class='form-control godown' required></select></td><td><input type='number' name='stock[]' class='form-control godown_stock text-right' value placeholder='How Much Stock???' id='stock_" +
                    count + "' onkeyup='calculateTotal(" + count +
                    ")' ></td><td><button  class='btn btn-danger red' type='button'  onclick='deletegodownrow(this)'><i class='fa fa-trash'></i></button></td>";
                document.getElementById(divName).appendChild(newdiv);
                document.getElementById(tabin).focus();
                $("#goDown_" + count).html(optionval);
                count++;
                $("select.form-control:not(.dont-select-me)").select2({
                    // placeholder: "--Select One--",
                    // allowClear: true
                });
            }
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
            var t = $("#service_godown > tbody > tr").length;
            if (1 == t) alert("There only one row you can't delete.");
            else {
                var a = e.parentNode.parentNode;
                a.parentNode.removeChild(a)
            }
            calculateTotal()
        }
    </script>
    <script>
        $(document).ready(function() {
            $(".godown").select2();
        });
    </script>
@endpush

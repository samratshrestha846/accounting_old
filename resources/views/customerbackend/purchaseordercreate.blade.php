@extends('customerbackend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>New Purchase Order</h1>
                    <div class="bulk-btn">
                        <a href="{{ route('purchaseOrder.customerindex') }}" class="global-btn">All Purchase Orders</a>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
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
                        <h2>Purchase Order</h2>
                    </div>
                    <div class="card-body">
                        <div class="ibox card">
                            <div class="ibox-body">
                                <form action="{{route('clientpurchaseorder.store')}}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="fiscal-year">Fiscal Year: </label>
                                                <select name="fiscal_year" id="fiscal-year" class="form-control">
                                                    @foreach ($fiscal_years as $fiscalyear)
                                                        <option value="{{ $fiscalyear->id }}">{{ $fiscalyear->fiscal_year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('fiscal_year') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="entry_date">Date (B.S)<i
                                                                class="text-danger">*</i></label>
                                                        <input type="text" name="nep_date" id="entry_date_nepali"
                                                            class="form-control" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="english_date">Date (A.D)<i
                                                                class="text-danger">*</i></label>
                                                        <input type="date" name="eng_date" id="english" class="form-control"
                                                            value="{{ date('Y-m-d') }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-center">
                                                    <thead class="thead-light">
                                                        <tr class="item-row">
                                                            <th style="width: 50%">Particulars</th>
                                                            <th style="width: 30%">Quantity</th>
                                                            <th style="width: 20%">Unit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <!-- Here should be the item row -->
                                                        <tr class="item-row">
                                                            <td>
                                                                <input type="text" name="particulars[]" class="form-control item" placeholder="Enter Product Name">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('particulars') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input class="form-control qty" placeholder="Quantity" type="number"
                                                                    name="quantity[]">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('quantity') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <select name="unit[]" class="form-control unit">
                                                                    @foreach ($units as $unit)
                                                                        <option value="{{$unit->unit}}">{{$unit->unit}}({{$unit->short_form}})</option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('unit') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr id="hiderow">
                                                            <td colspan="8" class="text-right">
                                                                <div class="btn-bulk justify-content-center">
                                                                    <a id="addRow" href="javascript:;" title="Add a row"
                                                                    class="btn btn-primary addRow">Add a row</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <strong>Total Quantity: </strong>
                                                            </td>
                                                            <td>
                                                                <span id="totalQty" style="color: red; font-weight: bold">0</span>
                                                                Units
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="form-gorup">
                                                <label for="remarks">Remarks: </label>
                                                <textarea name="remarks" class="form-control" placeholder="Remarks.." cols="10" rows="5"></textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('remarks') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <button class="btn btn-secondary submit" type="submit">Submit</button>
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
    <script src="{{ asset('backend/dist/js/jquery.customerpurchaseorder.js') }}"></script>
    <script type="text/javascript">
        window.onload = function() {
            const d = new Date();
            var year = d.getFullYear();
            var month = d.getMonth() + 1;
            var day = d.getDate();
            var today = year + '-' + month + '-' + day;
            var mainInput = document.getElementById("entry_date_nepali");
            var engtodayobj = NepaliFunctions.ConvertToDateObject(today, "YYYY-MM-DD");
            var neptoday = NepaliFunctions.ConvertDateFormat(NepaliFunctions.AD2BS(engtodayobj), "YYYY-MM-DD");
            document.getElementById('entry_date_nepali').value = neptoday;

            mainInput.nepaliDatePicker({
                onChange: function() {
                    var nepdate = mainInput.value;
                    var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                    document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(
                        NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                }
            });
        };
    </script>
    <script>
        window.env_url = {!! json_encode(url('/')) !!} + '/';
        jQuery(document).ready(function() {
            jQuery().invoice({
                addRow: "#addRow",
                delete: ".delete",
                parentClass: ".item-row",

                item: ".item",
                unit: ".unit",
                product_image: ".product_image",
                rate: ".rate",
                qty: ".qty",
                total: ".total",
                totalQty: "#totalQty",

                subtotal: "#subtotal",
                // discountpercent: "#discountpercent",
                // alldiscounttype: '.alldiscounttype',
                // alldtamt: '.alldtamt',
                gtaxamount: ".gtaxamount",
                alltaxtype: ".alltaxtype",
                alltaxper: '.alltaxper',
                // discount: "#discount",
                tax: "#tax",
                taxamount: "#taxamount",
                // shipping: "#shipping",
                grandTotal: "#grandTotal"
            });
        });
    </script>
    <script>
        window.units = <?php echo json_encode($units); ?>
    </script>
@endpush

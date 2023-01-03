@extends('backend.layouts.app')
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
                        <a href="{{ route('purchaseOrder.index') }}" class="global-btn">All Purchase Orders</a>
                    </div>
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
                    <div class="card-header">
                        <h2>Purchase Order</h2>
                    </div>
                    <div class="card-body">
                        <div class="ibox card">
                            <div class="ibox-body">
                                <form action="{{ route('purchaseOrder.store') }}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-2">
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
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="entry_date">Entry Date (B.S)<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="nep_date" id="entry_date_nepali"
                                                    class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="english_date">Entry Date (A.D)<i
                                                        class="text-danger">*</i></label>
                                                <input type="date" name="eng_date" id="english" class="form-control"
                                                    value="{{ date('Y-m-d') }}" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="party_name">Supplier Name: </label>
                                                <div class="v-add">
                                                    <select name="supplier" id="vendor" class="form-control vendor_info"
                                                        style="font-size: 18px; padding: 5px;" required>
                                                    </select>
                                                    &nbsp;&nbsp;<button type="button" data-toggle='modal' data-target='#supplieradd'
                                                        data-toggle='tooltip' data-placement='top' class="btn btn-primary icon-btn"
                                                        title="Add New Supplier"><i class="fas fa-plus"></i></button>
                                                    <p class="text-danger">
                                                        {{ $errors->first('supplier') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="purchase_order_no">Purchase Order No: </label>
                                                <input type="text" name="purchase_order_no" class="form-control"
                                                    value="{{ $purchaseOrderNo }}" readonly>
                                                <p class="text-danger">
                                                    {{ $errors->first('purchase_order_no') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive mt-2">
                                                <table class="table table-bordered text-center">
                                                    <thead class="thead-light">
                                                        <tr class="item-row">
                                                            <th style="width: 30%">Particulars</th>
                                                            <th>Quantity</th>
                                                            <th>Unit</th>
                                                            <th>Rate</th>
                                                            <th style="width: 20%">Total</th>
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
                                                            <td>
                                                                <input class="form-control rate" placeholder="Rate" type="number"
                                                                    name="rate[]" min="0" value="0" step=".01">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('rate') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input name="total[]" class="form-control total" value="0"
                                                                    readonly="readonly">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('total') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr id="hiderow">
                                                            <td colspan="8" class="text-right">
                                                                <div class="btn-bulk justify-content-end">
                                                                    <a id="addRow" href="javascript:;" title="Add a row"
                                                                    class="btn btn-primary addRow"+ >Add</a>
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
                                                            <td><strong>Sub Total</strong></td>
                                                            <td>
                                                                <input type="text" name="subtotal" id="subtotal" value="0"
                                                                    class="form-control" readonly="readonly">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('subtotal') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><strong>Tax Amount</strong></td>
                                                            <td>
                                                                <input type="text" name="taxamount" class="gtaxamount form-control"
                                                                    value="0">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('taxamount') }}
                                                                </p>
                                                                <div class="modal fade" tabindex="-1" role="dialog"
                                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">Individual Tax
                                                                                    Details
                                                                                </h5>
                                                                                <button type="button" class="close"
                                                                                    data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="row">
                                                                                        <div class="col-3">
                                                                                            <label for="taxtype"
                                                                                                class="txtleft">Tax
                                                                                                Type:</label>
                                                                                        </div>
                                                                                        <div class="col-9">
                                                                                            <select name="alltaxtype"
                                                                                                class="form-control alltaxtype">
                                                                                                <option value="exclusive">
                                                                                                    Exclusive</option>
                                                                                                <option value="inclusive">
                                                                                                    Inclusive</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <div class="row">
                                                                                        <div class="col-3">
                                                                                            <label for="dtamt">Tax%:</label>
                                                                                        </div>
                                                                                        <div class="col-9">
                                                                                            <select name="alltax"
                                                                                                class="form-control alltaxper">
                                                                                                @foreach ($taxes as $tax)
                                                                                                    <option
                                                                                                        value="{{ $tax->percent }}">
                                                                                                        {{ $tax->title }}({{ $tax->percent }}%)
                                                                                                    </option>
                                                                                                @endforeach

                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary"
                                                                                    data-dismiss="modal">Submit</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        {{-- <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>Shipping</strong></td>
                                                                <td>
                                                                    <input name="shipping" class="form-control" id="shipping"
                                                                        value="0" type="text">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('shipping') }}
                                                                    </p>
                                                                </td>
                                                            </tr> --}}
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><strong>Grand Total</strong></td>
                                                            <td>
                                                                <input type="text" name="grandtotal" class="form-control"
                                                                    id="grandTotal" value="0" readonly="readonly">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('grandtotal') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        {{-- <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>Refundable VAT</strong></td>
                                                                <td><input type="text" name="vat_refundable" class="form-control"
                                                                        value="0"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr> --}}
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>
                                                            <strong>Status:</strong>
                                                        </td>
                                                        <td>
                                                            <input type="radio" name="status" value="1" checked> Approve
                                                            <input type="radio" name="status" value="0"> Unapprove
                                                        </td>
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

                                        <div class="col-md-12">
                                            <div class="btn-bulk d-flex justify-content-end">
                                                <button class="btn btn-primary submit" type="submit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class='modal fade text-left' id='supplieradd' tabindex='-1' role='dialog'
                                    aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document' style="max-width: 1000px;">
                                        <div class='modal-content'>
                                            <div class='modal-header text-center'>
                                                <h2 class='modal-title' id='exampleModalLabel'>Add Supplier</h2>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                <form action="" method="POST" id="supplier_form">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <p class="card-title">Company Details</p>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="company_name_group">
                                                                        <label for="name">Company Name<i
                                                                                class="text-danger">*</i></label>
                                                                        <input type="text" name="company_name"
                                                                            class="form-control"
                                                                            value="{{ old('company_name') }}"
                                                                            placeholder="Enter Company Name" id="company_name"
                                                                            required>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('company_name') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="name">Company Code (Code has to be
                                                                            unique)</label>
                                                                        <input type="text" name="supplier_code"
                                                                            class="form-control" value="{{ $supplier_code }}"
                                                                            placeholder="Enter Company Code" id="company_code">
                                                                        <p class="text-danger companycode_error hide">Code is
                                                                            already used. Use Different code.</p>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('supplier_code') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="company_email_group">
                                                                        <label for="company_email">Company Email</label>
                                                                        <input type="email" name="company_email"
                                                                            class="form-control"
                                                                            value="{{ old('company_email') }}"
                                                                            placeholder="Enter Company Email" id="company_email">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('company_email') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="company_phone_group">
                                                                        <label for="company_phone">Company Phone</label>
                                                                        <input type="text" name="company_phone"
                                                                            class="form-control"
                                                                            value="{{ old('company_phone') }}"
                                                                            placeholder="Enter Company Contact no."
                                                                            id="company_phone">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('company_phone') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="province_id_group">
                                                                        <label for="province">Province no.</label>
                                                                        <select name="province" class="form-control province"
                                                                            id="province_id">
                                                                            <option value="">--Select a province--</option>
                                                                            @foreach ($provinces as $province)
                                                                                <option value="{{ $province->id }}">
                                                                                    {{ $province->eng_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('province') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="district_group">
                                                                        <label for="district">Districts</label>
                                                                        <select name="district" class="form-control"
                                                                            id="district">
                                                                            <option value="">--Select a province first--
                                                                            </option>
                                                                        </select>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('district') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="company_address_group">
                                                                        <label for="company_address">Company Local
                                                                            Address</label>
                                                                        <input type="text" name="company_address"
                                                                            class="form-control"
                                                                            value="{{ old('company_address') }}"
                                                                            placeholder="Company Address" id="company_address">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('company_address') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="pan_vat_group">
                                                                        <label for="pan_vat">PAN No./VAT No. (Optional)</label>
                                                                        <input type="text" name="pan_vat" class="form-control"
                                                                            value="{{ old('pan_vat') }}"
                                                                            placeholder="Enter Company PAN or VAT No." id="pan_vat">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card">
                                                        <div class="card-header">
                                                            <p class="card-title">Concerned Person Details</p>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="concerned_name_group">
                                                                        <label for="concerned_name">Name</label>
                                                                        <input type="text" name="concerned_name"
                                                                            class="form-control"
                                                                            value="{{ old('concerned_name') }}"
                                                                            placeholder="Enter Name" id="concerned_name">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('concerned_name') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="concerned_phone_group">
                                                                        <label for="concerned_phone">Phone</label>
                                                                        <input type="text" name="concerned_phone"
                                                                            class="form-control"
                                                                            value="{{ old('concerned_phone') }}"
                                                                            placeholder="Enter Phone" id="concerned_phone">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('concerned_phone') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="concerned_email_group">
                                                                        <label for="concerned_email">Email</label>
                                                                        <input type="email" name="concerned_email"
                                                                            class="form-control"
                                                                            value="{{ old('concerned_email') }}"
                                                                            placeholder="Enter Email" id="concerned_email">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('concerned_email') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="designation_group">
                                                                        <label for="designation">Designation</label>
                                                                        <input type="text" name="designation"
                                                                            class="form-control"
                                                                            value="{{ old('designation') }}"
                                                                            placeholder="Enter Designation" id="designation">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('designation') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group text-center">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
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
    <script src="{{ asset('backend/dist/js/jquery.purchaseorder.js') }}"></script>
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

            function fillSelectSuppliers(suppliers) {
                document.getElementById("vendor").innerHTML = '<option value=""> --Select an option-- </option>' +
                    suppliers.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.company_name}</option>`, '');
            }

            function fetchvendors() {
                $.ajax({
                    url: "{{ route('apisupplier') }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var suppliers = response;
                        fillSelectSuppliers(suppliers);
                    }
                });
            }
            fetchvendors();
        };
    </script>
    <script>
        // $(document).on('focus', '.alldiscount', function() {
        //     $(this).siblings('.modal').modal({
        //         show: true
        //     });
        // });

        $(document).on('focus', '.gtaxamount', function() {
            $(this).siblings('.modal').modal({
                show: true
            });
        });
        $(document).ready(function() {
            $(".vendor_info").select2();
        });
        $(document).ready(function() {
            $(".unit").select2();
        });
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
        $(function() {
            $('.province').change(function() {
                var province_no = $(this).children("option:selected").val();

                function fillSelect(districts) {
                    document.getElementById("district").innerHTML =
                        districts.reduce((tmp, x) =>
                            `${tmp}<option value='${x.id}'>${x.dist_name}</option>`, '');
                }

                function fetchRecords(province_no) {
                    var url = "{{ route('getdistricts', ':province_no') }}";
                    url = url.replace(':province_no', province_no);
                    $.ajax({
                        url: url,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var districts = response;
                            fillSelect(districts);
                        }
                    });
                }
                fetchRecords(province_no);
            })
            var suppliercodes = @php echo json_encode($allsuppliercodes) @endphp;
            $("#company_code").change(function() {
                var productcategoryval = $(this).val();
                if ($.inArray(productcategoryval, suppliercodes) != -1) {
                    $('.companycode_error').addClass('show');
                    $('.companycode_error').removeClass('hides');
                } else {
                    $('.companycode_error').removeClass('show');
                    $('.companycode_error').addClass('hide');
                }
            })
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#supplier_form").submit(function(event) {

                var formData = {
                    company_name: $("#company_name").val(),
                    company_code: $("#company_code").val(),
                    company_email: $("#company_email").val(),
                    company_phone: $("#company_phone").val(),
                    company_address: $("#company_address").val(),
                    province_id: $("#province_id").val(),
                    district_id: $("#district").val(),
                    pan_vat: $("#pan_vat").val(),
                    concerned_name: $("#concerned_name").val(),
                    concerned_phone: $("#concerned_phone").val(),
                    concerned_email: $("#concerned_email").val(),
                    designation: $("#designation").val(),
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('post.apisupplier') }}",
                    data: formData,
                    dataType: "json",
                    encode: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {

                    function fillSelectSuppliers(suppliers) {
                        document.getElementById("vendor").innerHTML =
                            '<option value=""> --Select an option-- </option>' +
                            suppliers.reduce((tmp, x) =>
                                `${tmp}<option value='${x.id}'>${x.company_name}</option>`, '');
                    }

                    function fetchvendors() {
                        $.ajax({
                            url: "{{ route('apisupplier') }}",
                            type: 'get',
                            dataType: 'json',
                            success: function(response) {
                                var suppliers = response;
                                fillSelectSuppliers(suppliers);
                            }
                        });
                    }
                    fetchvendors();
                    $("#supplier_form").html(
                        '<div class="alert alert-success">Successfully added.</div>'
                    );
                });
                event.preventDefault();
            });
        });
    </script>
    <script>
        $('#payment_amount').change(function() {
            var payment_amount = $(this).val();
            var grandtotal = $("#grandTotal").val();

            if (parseFloat(payment_amount) > parseFloat(grandtotal)) {
                $(this).parent().find('.text-danger').removeClass('off');
                $('.submit').addClass("disabled");
            } else {
                $(this).parent().find('.text-danger').addClass('off');
                $('.submit').removeClass("disabled");
            }
        });
    </script>
    <script>
        window.units = <?php echo json_encode($units); ?>
    </script>
@endpush

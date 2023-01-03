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
                    <h1>Update Purchase Order </h1>
                    <a href="{{ route('purchaseOrder.index') }}" class="global-btn">View Purchase
                        Orders</a>
                </div>
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
                        <div class="ibox">
                            <div class="row ibox-body">
                                <div class="col-12">
                                    <form action="{{ route('purchaseOrder.update', $purchaseOrder->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="fiscal-year">Fiscal Year: </label>
                                                    <select name="fiscal_year" id="fiscal-year" class="form-control">
                                                        @foreach ($fiscal_years as $fiscalyear)
                                                            <option value="{{ $fiscalyear->id }}"
                                                                {{ $fiscalyear->id == $purchaseOrder->fiscal_year_id ? 'selected' : '' }}>
                                                                {{ $fiscalyear->fiscal_year }}
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
                                                        class="form-control" value="{{ $purchaseOrder->nep_date }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="english_date">Entry Date (A.D)<i
                                                            class="text-danger">*</i></label>
                                                    <input type="date" name="eng_date" id="english" class="form-control"
                                                        value="{{ $purchaseOrder->eng_date }}" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="party_name">Supplier Name: </label>
                                                    <div class="v-add">
                                                        <select name="supplier" id="vendor" class="form-control vendor_info"
                                                            style="font-size: 18px; padding: 5px;" required>
                                                            @foreach ($vendors as $vendor)
                                                                <option value="{{ $vendor->id }}"
                                                                    {{ $vendor->id == $purchaseOrder->vendor_id ? 'selected' : '' }}>
                                                                    {{ $vendor->company_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="text-danger">
                                                            {{ $errors->first('supplier') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="purchase_order_no">Purchase Order No: </label>
                                                    <input type="text" name="purchase_order_no" class="form-control"
                                                        value="{{ $purchaseOrder->purchase_order_no }}" readonly>
                                                    <p class="text-danger">
                                                        {{ $errors->first('purchase_order_no') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-center">
                                                        <thead class="thead-light">
                                                            <tr class="item-row">
                                                                <th style="width: 40%">Particulars</th>
                                                                <th>Quantity</th>
                                                                <th>Unit</th>
                                                                <th>Rate</th>
                                                                <th style="width:20%">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <!-- Here should be the item row -->
                                                            @foreach ($purchaseOrder->purchaseOrderExtras as $purchaseOrderExtra)
                                                                <tr class="item-row">
                                                                    <td class="item-name">
                                                                        <div class="delete-btn">
                                                                            <input type="text" name="particulars[]" class="form-control item" placeholder="Enter Product Name" value="{{$purchaseOrderExtra->particulars}}">
                                                                            <a class='delete' href="javascript:;"
                                                                                title="Remove row">X</a>
                                                                        </div>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('particulars') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control qty" placeholder="Quantity"
                                                                            type="text" name="quantity[]"
                                                                            value="{{ $purchaseOrderExtra->quantity }}">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('quantity') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <select name="unit[]" class="form-control unit">
                                                                            @foreach ($units as $unit)
                                                                                <option value="{{$unit->unit}}" {{$unit->unit == $purchaseOrderExtra->unit ? 'selected' : ''}}>{{$unit->unit}}({{$unit->short_form}})</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('unit') }}
                                                                        </p>
                                                                    </td>

                                                                    <td>
                                                                        <input class="form-control rate" placeholder="Rate" type="number"
                                                                        name="rate[]" min="0" step=".01" value="{{$purchaseOrderExtra->rate}}">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('rate') }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <input name="total[]" class="form-control total"
                                                                            readonly="readonly">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('total') }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                            <tr id="hiderow">
                                                                <td colspan="5" class="text-right">
                                                                    <div class="btn-bulk" style="justify-content: end;">
                                                                    <a id="addRow" href="javascript:;" title="Add a row"
                                                                        class="btn btn-primary">+ Add</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Total Quantity: </strong>
                                                                </td>
                                                                <td>
                                                                    <span id="totalQty"
                                                                        style="color: red; font-weight: bold">0</span> Units
                                                                </td>
                                                                <td></td>
                                                                <td><strong>Sub Total</strong></td>
                                                                <td>
                                                                    <input type="text" name="subtotal" id="subtotal"
                                                                        class="form-control" readonly="readonly"
                                                                        value="{{ $purchaseOrder->subtotal }}">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('subtotal') }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            {{-- <tr>
                                                                <td><strong>Total Quantity: </strong>
                                                                </td>
                                                                <td>
                                                                    <span id="totalQty" style="color: red; font-weight: bold">0</span> Units
                                                                </td>
                                                                <td></td>
                                                                <td><strong>Discount</strong></td>
                                                                    <td>
                                                                        <input name="discountamount" class="form-control alldiscount" id="discount"
                                                                            value="0" type="text">
                                                                            <div class="modal fade" tabindex="-1" role="dialog"
                                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title">OverAll Discount</h5>
                                                                                        <button type="button" class="close"
                                                                                            data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="form-group">
                                                                                            <div class="row">
                                                                                                <div class="col-3">
                                                                                                    <label for="alldiscounttype"
                                                                                                        class="txtleft">Discount
                                                                                                        Type:</label>
                                                                                                </div>
                                                                                                <div class="col-9">
                                                                                                    <select name="alldiscounttype"
                                                                                                        class="form-control alldiscounttype">
                                                                                                        <option value="percent" {{$purchaseOrder->alldiscounttype == "percent" ? 'selected' : ''}}>Percent
                                                                                                            %</option>
                                                                                                        <option value="fixed" {{$purchaseOrder->alldiscounttype == "fixed" ? 'selected' : ''}}>Fixed
                                                                                                        </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <div class="row">
                                                                                                <div class="col-3">
                                                                                                    <label for="dtamt">Discount:</label>
                                                                                                </div>
                                                                                                <div class="col-9">
                                                                                                    <input type="text" name="alldtamt"
                                                                                                        class="form-control alldtamt"
                                                                                                        placeholder="Discount"
                                                                                                        value="{{$purchaseOrder->alldtamt}}">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-secondary"
                                                                                            data-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('discountamount') }}
                                                                        </p>
                                                                    </td>
                                                            </tr> --}}
                                                            {{-- <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><strong>Tax %</strong></td>
                                                                <td>
                                                                    <input name="taxpercent" class="form-control" id="tax" value="{{$purchaseOrder->taxpercent}}" type="text">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('taxpercent') }}
                                                                    </p>
                                                                </td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td><strong>Tax Amount</strong></td>
                                                                <td>
                                                                    <input type="text" name="taxamount"
                                                                        class="gtaxamount form-control" value="0">
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
                                                                                                    <option value="exclusive"
                                                                                                        {{ $purchaseOrder->alltaxtype == 'exclusive' ? 'selected' : '' }}>
                                                                                                        Exclusive</option>
                                                                                                    <option value="inclusive"
                                                                                                        {{ $purchaseOrder->alltaxtype == 'inclusive' ? 'selected' : '' }}>
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
                                                                                                            value="{{ $tax->percent }}"
                                                                                                            {{ $purchaseOrder->alltax == $tax->percent ? 'selected' : '' }}>
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
                                                                                        data-dismiss="modal">Close</button>
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
                                                                    <input name="shipping" class="form-control" id="shipping" value="{{$purchaseOrder->shipping}}" type="text">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('shipping') }}
                                                                    </p>
                                                                </td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;"></td>
                                                                <td><strong>Grand Total</strong></td>
                                                                <td>
                                                                    <input type="text" name="grandtotal" class="form-control"
                                                                        id="grandTotal" value="{{ $purchaseOrder->grandtotal }}"
                                                                        readonly="readonly">
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
                                                                <td><input type="text" name="vat_refundable" class="form-control" value="{{$purchaseOrder->vat_refundable}}"></td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td style="border:none;"></td>
                                                                <td style="border:none;">
                                                                    {{-- <strong>IRD Sync: </strong> --}}
                                                                </td>

                                                                <td style="border:none;">
                                                                    {{-- <input type="radio" name="sync_ird" value="1" {{$purchaseOrder->sync_ird == 1 ? "checked" : ''}}> Yes
                                                                    <input type="radio" name="sync_ird" value="0"  {{$purchaseOrder->sync_ird == 0 ? "checked" : ''}}> No --}}
                                                                </td>
                                                                <td>
                                                                    <strong>Status:</strong>
                                                                </td>
                                                                <td>
                                                                    <input type="radio" name="status" value="1"
                                                                        {{ $purchaseOrder->status == 1 ? 'checked' : '' }}>
                                                                    Approve
                                                                    <input type="radio" name="status" value="0"
                                                                        {{ $purchaseOrder->status == 0 ? 'checked' : '' }}>
                                                                    Unapprove
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <div class="form-gorup">
                                                    <label for="remarks">Remarks: </label>
                                                    <textarea name="remarks"
                                                        class="form-control" placeholder="Remarks.." rows="5">{{ $purchaseOrder->remarks }}</textarea>
                                                    <p class="text-danger">
                                                        {{ $errors->first('remarks') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-primary ml-auto" type="submit">Submit</button></td>
                                            </div>
                                        </div>

                                    </form>
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
    <script src="{{ asset('backend/dist/js/jquery.editpurchaseorder.js') }}"></script>

    <script type="text/javascript">
        var mainInput = document.getElementById("entry_date_nepali");
        mainInput.nepaliDatePicker({
            onChange: function() {
                var nepdate = mainInput.value;
                var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(NepaliFunctions
                    .BS2AD(neptodaydateformat), "YYYY-MM-DD");
            }
        });
    </script>
    <script>
        $(function() {
            var particulars = $('#table input[particulars]');
        });

        $(document).on('focus', '.gtaxamount', function() {
            $(this).siblings('.modal').modal({
                show: true
            });
        });
        $(document).ready(function() {
            $(".unit").select2();
        });
        $(document).ready(function() {
            $(".vendor_info").select2();
        });
    </script>
    <script>
        jQuery(document).ready(function() {
            jQuery().invoice({
                addRow: "#addRow",
                delete: ".delete",
                parentClass: ".item-row",

                item: ".item",
                unit: ".unit",
                rate: ".rate",
                qty: ".qty",
                total: ".total",
                totalQty: "#totalQty",

                subtotal: "#subtotal",
                tax: "#tax",
                taxamount: "#taxamount",
                grandTotal: "#grandTotal"
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('body').trigger('click');
            $('body').trigger('click');
        });
    </script>
    <script>
        window.units = <?php echo json_encode($units); ?>
    </script>
@endpush

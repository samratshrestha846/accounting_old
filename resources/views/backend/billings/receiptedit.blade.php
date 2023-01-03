@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{asset('backend/dist/css/custom.css')}}">
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Update Receipt</h1>
                    <div class="btn-bulk">
                        <a href="{{route('billings.report', $billing_type_id)}}" class="global-btn">View All Receipts</a>
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
                        <h2>Receipt => {{ $billing->reference_no }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ route('billings.update', $billing->id)}}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="billing_type_id" value="3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="fiscal-year">Fiscal Year: </label>
                                                <select name="fiscal_year_id" id="fiscal-year" class="form-control">
                                                    @foreach ($fiscal_years as $fiscalyear)
                                                        <option value="{{ $fiscalyear->id }}" {{$fiscalyear->id == $billing->fiscal_year_id ? 'selected' : ''}}>{{ $fiscalyear->fiscal_year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('fiscal_year_id') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="entry_date">Entry Date (B.S)<i class="text-danger">*</i></label>
                                                <input type="text" name="nep_date" id="entry_date_nepali"
                                                    class="form-control" value="{{$billing->nep_date}}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="english_date">Entry Date (A.D)<i class="text-danger">*</i></label>
                                                <input type="date" name="eng_date" id="english"
                                                    class="form-control" value="{{$billing->eng_date}}" readonly="readonly">
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="transaction_no">Transaction No:</label>
                                                <input type="text" class="form-control" name="transaction_no" value="{{$billing->transaction_no}}">
                                                <p class="text-danger">
                                                    {{ $errors->first('transaction_no') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="reference_no">Reference No: </label>
                                                <input type="text" class="form-control" name="reference_no" value="{{$billing->reference_no}}">
                                                <p class="text-danger">
                                                    {{ $errors->first('reference_no') }}
                                                </p>
                                            </div>
                                        </div> --}}

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="ledger_no">VAT Bill No: </label>
                                                <input type="text" class="form-control" name="ledger_no" value="{{$billing->ledger_no}}" >
                                                <p class="text-danger">
                                                    {{ $errors->first('ledger_no') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="status">Suppliers: </label>
                                                <select name="vendor_id" id="vendor" class="form-control supplier_info" @if ($billing->vendor_id == null) disabled @endif>
                                                    <option value="">--Select Option--</option>
                                                    @foreach ($vendors as $vendor)
                                                        <option value="{{$vendor->id}}" {{$billing->vendor_id == $vendor->id ? 'selected' : ''}}>{{$vendor->company_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="client">Customers: </label>
                                                <select name="client_id" id="client" class="form-control client_info" @if ($billing->client_id == null) disabled @endif>
                                                    <option value="">--Select Option--</option>
                                                    @foreach ($clients as $client)
                                                        <option value="{{$client->id}}" {{$client->id == $billing->client_id ? "selected" : ''}}>{{$client->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="Payment Type">Payment Method:</label>
                                                <select name="payment_method" class="form-control payment_method">
                                                    <option value="">--Select One--</option>
                                                    @foreach ($payment_methods as $method)
                                                        <option value="{{ $method->id }}"{{ $method->id == $billing->payment_method ? 'selected' : '' }}>{{ $method->payment_mode }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2" id="online_payment" @if ($billing->online_portal_id == null) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label for="Bank">Select a portal:</label>
                                                <select name="online_portal" class="form-control" id="online_portal">
                                                    <option value="">--Select a portal--</option>
                                                    @foreach ($online_portals as $portal)
                                                        <option value="{{ $portal->id }}" @if ($billing->online_portal_id != null) {{ $portal->id == $billing->online_portal_id ? 'selected' : '' }} @endif>{{ $portal->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2" id="customer_portal_id" @if ($billing->customer_portal_id == null) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label for="">Portal Id:</label>
                                                <input type="text" class="form-control" placeholder="Portal Id"
                                                    name="customer_portal_id" @if ($billing->customer_portal_id != null) value="{{ $billing->customer_portal_id }}" @endif>
                                            </div>
                                        </div>

                                        @if ($billing->bank_id == null)
                                            <div class="col-md-2" id="bank" style="display: none;">
                                                <div class="form-group">
                                                    <label for="Bank">From Bank:</label>
                                                    <select name="bank_id" class="form-control" id="bank_info">
                                                        <option value="">--Select a bank--</option>
                                                        @foreach ($banks as $bank)
                                                            <option value="{{ $bank->id }}">{{ $bank->bank_name }} (A/C Holder - {{ $bank->account_name }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-2" id="bank">
                                                <div class="form-group">
                                                    <label for="Bank">From Bank:</label>
                                                    <select name="bank_id" class="form-control" id="bank_info">
                                                        <option value="">--Select a bank--</option>
                                                        @foreach ($banks as $bank)
                                                            <option value="{{ $bank->id }}"{{ $bank->id == $billing->bank_id ? 'selected' : '' }}>{{ $bank->bank_name }} (A/C Holder - {{ $bank->account_name }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($billing->cheque_no == null)
                                            <div class="col-md-2" id="cheque" style="display: none;">
                                                <div class="form-group">
                                                    <label for="cheque no">Cheque no.:</label>
                                                    <input type="text" class="form-control" placeholder="Cheque No." name="cheque_no">
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-2" id="cheque">
                                                <div class="form-group">
                                                    <label for="cheque no">Cheque no.:</label>
                                                    <input type="text" class="form-control" placeholder="Cheque No." name="cheque_no" value="{{ $billing->cheque_no }}">
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive mt-2">
                                                <table class="table table-bordered">
                                                    <thead class="thead-light">
                                                        <tr class="item-row">
                                                            <th>Particulars</th>
                                                            <th>Narration</th>
                                                            <th>Cheque No</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($billing->billingextras as $billingextra)
                                                        <tr class="item-row">
                                                            <td class="item-name">
                                                                <div class="delete-btn">
                                                                    <input type="text" class="form-control item" placeholder="Particulars" type="text" name="particulars[]" value="{{$billingextra->particulars}}">
                                                                    <a class='delete' href="javascript:;" title="Remove row">X</a>
                                                                </div>
                                                                <p class="text-danger">
                                                                    {{ $errors->first('particulars') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input class="form-control narration" placeholder="Narration" type="text" name="narration[]" value="{{$billingextra->narration}}">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('narration') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input class="form-control particular_cheque_no" placeholder="Cheque No" type="text" name="particular_cheque_no[]" value="{{$billingextra->cheque_no}}">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('particular_cheque_no') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <input name="total[]" class="form-control total" value="{{$billingextra->total}}">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('total') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        <tr id="hiderow">
                                                            <td colspan="4" class="text-right">
                                                                <div class="btn-bulk justify-content-end">
                                                                <a id="addRow" href="javascript:;" title="Add a row"
                                                                    class="btn btn-primary">+ Add</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <!-- Here should be the item row -->
                                                        <!--<tr class="item-row">
                                                            <td><input class="form-control item" placeholder="Item" type="text"></td>
                                                            <td><input class="form-control price" placeholder="Price" type="text"></td>
                                                            <td><input class="form-control qty" placeholder="Quantity" type="text"></td>
                                                            <td><span class="total">0.00</span></td>
                                                        </tr>-->
                                                        <tr>
                                                            <td style="border:none;"></td>
                                                            <td style="border:none;"></td>
                                                            <td class="text-right"><strong>Sub Total</strong></td>
                                                            <td>
                                                                <input type="text" name="subtotal" id="subtotal" class="form-control" readonly="readonly" value="{{$billing->subtotal}}">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('subtotal') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border:none;">
                                                            </td>
                                                            <td style="border:none;"></td>
                                                            <td class="text-right"><strong>Discount</strong></td>
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
                                                                                                    <option value="percent" {{$billing->alldiscounttype == "percent" ? 'selected' : ''}}>Percent
                                                                                                        %</option>
                                                                                                    <option value="fixed" {{$billing->alldiscounttype == "fixed" ? 'selected' : ''}}>Fixed
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
                                                                                                    value="{{$billing->alldtamt}}">
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
                                                        </tr>
                                                        {{-- <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right"><strong>Tax %</strong></td>
                                                            <td>
                                                                <input name="taxpercent" class="form-control" id="tax" value="{{$billing->taxpercent}}" type="text">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('taxpercent') }}
                                                                </p>
                                                            </td>
                                                        </tr> --}}
                                                        <tr>
                                                            <td style="border:none;"></td>
                                                            <td style="border:none;"></td>
                                                            <td class="text-right"><strong>Tax Amount</strong></td>
                                                            <td>
                                                                <input type="text" name="taxamount" class="gtaxamount form-control" value="0">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('taxamount') }}
                                                                </p>
                                                                <div class="modal fade" tabindex="-1" role="dialog"
                                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">Individual Tax Details
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
                                                                                                <option value="exclusive" {{$billing->alltaxtype == "exclusive" ? "selected" : ''}}>
                                                                                                    Exclusive</option>
                                                                                                <option value="inclusive" {{$billing->alltaxtype == "inclusive" ? "selected" : ''}}>
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
                                                                                                        value="{{ $tax->percent }}" {{$billing->alltax == $tax->percent ? "selected" : ''}}>
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
                                                        <tr>
                                                            <td style="border:none;"></td>
                                                            <td style="border:none;"></td>
                                                            <td class="text-right"><strong>Shipping</strong></td>
                                                            <td>
                                                                <input name="shipping" class="form-control" id="shipping" value="{{$billing->shipping}}" type="text">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('shipping') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border:none;"></td>
                                                            <td style="border:none;"></td>
                                                            <td class="text-right"><strong>Grand Total</strong></td>
                                                            <td>
                                                                <input type="text" name="grandtotal" class="form-control" id="grandTotal" value="{{$billing->grandtotal}}" readonly="readonly">
                                                                <p class="text-danger">
                                                                    {{ $errors->first('grandtotal') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border:none;"></td>
                                                            <td style="border:none;"></td>
                                                            <td><strong>Refundable VAT</strong></td>
                                                            <td><input type="text" name="vat_refundable" class="form-control" value="{{$billing->vat_refundable}}"></td>
                                                        </tr>
                                                        <tr>
                                                            @if($ird_sync == 1)
                                                            <td>
                                                                <strong>IRD Sync<i class="text-danger">*</i>: </strong>
                                                            </td>
                                                            <td>
                                                                <input type="radio" name="sync_ird" value="1" {{$billing->sync_ird == 1 ? "checked" : ''}}> Yes
                                                                <input type="radio" name="sync_ird" value="0"  {{$billing->sync_ird == 0 ? "checked" : ''}}> No
                                                            </td>
                                                            @else
                                                            <td></td>
                                                            <td></td>
                                                            @endif
                                                            <td>
                                                                <strong>Status:</strong>
                                                            </td>
                                                            <td>
                                                                <input type="radio" name="status" value="1" {{$billing->status == 1 ? 'checked': ''}}> Approve
                                                                <input type="radio" name="status" value="0" {{$billing->status == 0 ? 'checked': ''}}> Unapprove
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <div class="form-gorup">
                                                <label for="remarks">Remarks: </label>
                                                <textarea name="remarks" class="form-control" rows="5">{{$billing->remarks}}</textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('remarks') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <button class="btn btn-primary ml-auto" type="submit">Submit</button>
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
    <script src="{{ asset('backend/dist/js/jquery.invoiceopt.js') }}"></script>
    <script type="text/javascript">
        var mainInput = document.getElementById("entry_date_nepali");
        mainInput.nepaliDatePicker({
                        onChange: function() {
                            var nepdate = mainInput.value;
                            var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                            document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                        }
                    });
    </script>
    <script>
        $(function() {
            // var tablerow = $('table#salestbl tr');
            var particulars = $('#table input[particulars]')

            $(document).on('input', 'input.creditPrice', function() {
                $(this).parent().siblings().find('input.debitPrice').attr('readonly',
                    'readonly');
            });

        });
    </script>
    <script>
        jQuery(document).ready(function() {
            jQuery().invoice({
                addRow: "#addRow",
                delete: ".delete",
                parentClass: ".item-row",

                narration: ".narration",
                particular_cheque_no: ".particular_cheque_no",
                total: ".total",

                subtotal: "#subtotal",
                discountpercent: "#discountpercent",
                discount: "#discount",
                tax: "#tax",
                taxamount: "#taxamount",
                shipping: "#shipping",
                grandTotal: "#grandTotal"
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $('body').trigger('click');
            $('body').trigger('click');
        });$(function() {
            $('.payment_method').change(function() {
                var payment = $(this).children("option:selected").val();

                if (payment == 2) {
                        document.getElementById("bank").style.display = "block";
                        document.getElementById("cheque").style.display = "block";
                        document.getElementById("online_payment").style.display = "none";
                        document.getElementById("customer_portal_id").style.display = "none";
                    } else if (payment == 3) {
                        document.getElementById("bank").style.display = "block";
                        document.getElementById("cheque").style.display = "none";
                        document.getElementById("online_payment").style.display = "none";
                        document.getElementById("customer_portal_id").style.display = "none";
                    } else if(payment == 4)
                    {
                        document.getElementById("bank").style.display = "none";
                        document.getElementById("cheque").style.display = "none";
                        document.getElementById("online_payment").style.display = "block";
                        document.getElementById("customer_portal_id").style.display = "block";
                    }else {
                        document.getElementById("bank").style.display = "none";
                        document.getElementById("cheque").style.display = "none";
                        document.getElementById("online_payment").style.display = "none";
                        document.getElementById("customer_portal_id").style.display = "none";
                    }
            })
        });
    </script>

    <script>
        $(function(){
            $('#vendor').change(function(){
                var vendorval = $(this).val();
                if(!vendorval == ''){
                    $('#client').attr('disabled', true);
                }else{
                    $('#client').attr('disabled', false);
                }
            });

            $('#client').change(function(){
                var clientval = $(this).val();
                if(!clientval == ''){
                    $('#vendor').attr('disabled', true);
                }else{
                    $('#vendor').attr('disabled', false);
                }
            });
        })

        $(document).ready(function() {
            $(".supplier_info").select2();
        });
        $(document).ready(function() {
            $(".client_info").select2();
        });
        $('.alldiscounttype').change(function(){
            $('body').click();
        })
    </script>
@endpush

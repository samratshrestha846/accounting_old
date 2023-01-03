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
                    <h1>Update Bank Reconciliation </h1>

                    <div class="btn-bulk">
                        <a href="{{ route('bankReconciliationStatement.index') }}" class="global-btn">View
                            Reconciliations</a>
                    </div><!-- /.col -->
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
                    <div class="card-body">
                        <form action="{{ route('bankReconciliationStatement.update', $reconciliation->id) }}"
                            method="POST">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="cheque_entry_date">Cheque entried (B.S):</label>
                                                <input type="text" name="cheque_entry_date" id="cheque_entry_date"
                                                    class="form-control"
                                                    value="{{ $reconciliation->cheque_entry_date }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="cheque_cashed_date">Cheque cashed out (B.S):</label>
                                                <input type="text" name="cheque_cashed_date" id="cheque_cashed_date"
                                                    class="form-control"
                                                    value="{{ $reconciliation->cheque_cashed_date == null ? $nepali_today : $reconciliation->cheque_cashed_date }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="related_party">Related Party:</label>
                                                <select name="vendor_id" class="form-control" id="third_party">
                                                    <option value="">--Select a supplier--</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}"
                                                            {{ $supplier->id == $reconciliation->vendor_id ? 'selected' : '' }}>
                                                            {{ $supplier->company_name }}</option>
                                                    @endforeach
                                                    <option value="other"
                                                        {{ $reconciliation->vendor_id == null ? 'selected' : '' }}>
                                                        Others..</option>
                                                </select>
                                            </div>
                                        </div>
                                        @if ($reconciliation->vendor_id == null)
                                            <div class="col-md-2" id="other_party">
                                                <div class="form-group">
                                                    <label for="others">Other party Name:</label>
                                                    <input type="text" name="others" class="form-control"
                                                        placeholder="Other party's Name"
                                                        value="{{ $reconciliation->other_receipt }}">
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-2" style="display: none;" id="other_party">
                                                <div class="form-group">
                                                    <label for="others">Other party Name:</label>
                                                    <input type="text" name="others" class="form-control"
                                                        placeholder="Other party's Name">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="bank">Bank:</label>
                                        <select name="bank_id" class="form-control bank_info">
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}"
                                                    {{ $bank->id == $reconciliation->bank_id ? 'selected' : '' }}>
                                                    {{ $bank->bank_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="method">Payment Method:</label>
                                        <select name="method" class="form-control" id="payment_method">
                                            <option value="">--Select a method--</option>
                                            <option value="Cheque"
                                                {{ $reconciliation->cheque_no != null ? 'selected' : '' }}>Cheque
                                            </option>
                                            <option value="Bank Transfer"
                                                {{ $reconciliation->cheque_no == null ? 'selected' : '' }}>Bank
                                                Transfer</option>
                                        </select>
                                    </div>
                                </div>

                                @if ($reconciliation->cheque_no == null)
                                    <div class="col-md-2" style="display: none;" id="cheque_no">
                                        <div class="form-group">
                                            <label for="cheque_no">Cheque No:</label>
                                            <input type="text" name="cheque_no" class="form-control"
                                                placeholder="Cheque no.">
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-2" id="cheque_no">
                                        <div class="form-group">
                                            <label for="cheque_no">Cheque No:</label>
                                            <input type="text" name="cheque_no" class="form-control"
                                                placeholder="Cheque no."
                                                value="{{ $reconciliation->cheque_no }}">
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-2">
                                    <label for="receipt_payment">Receipt / Payment:</label>
                                    <select name="receipt_payment" class="form-control">
                                        <option value="">--Select an option--</option>
                                        <option value="0" {{ $reconciliation->receipt_payment == 0 ? 'selected' : '' }}>
                                            Receipt</option>
                                        <option value="1" {{ $reconciliation->receipt_payment == 1 ? 'selected' : '' }}>
                                            Payment</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="amount">Amount:</label>
                                    <input type="number" step="any" class="form-control" name="amount"
                                        placeholder="Amount in Rs." value="{{ $reconciliation->amount }}">
                                </div>

                                <div class="col-md-12 mt-2">
                                    <button type="submit" class="btn btn-primary ml-auto">Update</button>
                                </div>
                            </div>
                        </form>
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
        $(function() {
            $('#third_party').change(function() {
                var this_value = $(this).children("option:selected").val();
                if (this_value === "other") {
                    document.getElementById("other_party").style.display = "block";
                } else {
                    document.getElementById("other_party").style.display = "none";
                }
            })

            $('#payment_method').change(function() {
                var this_value = $(this).children("option:selected").val();
                if (this_value === "Cheque") {
                    document.getElementById("cheque_no").style.display = "block";
                } else {
                    document.getElementById("cheque_no").style.display = "none";
                }
            })
        });
    </script>

    <script type="text/javascript">
        window.onload = function() {
            document.getElementById("cheque_entry_date").nepaliDatePicker();
            document.getElementById("cheque_cashed_date").nepaliDatePicker();
        };

        $(document).ready(function() {
            $(".bank_info").select2();
        });
        $(document).ready(function() {
            $("#third_party").select2();
        });
    </script>

    <script>
        $(function() {
            $('.bank_province').change(function() {
                var province_no = $(this).children("option:selected").val();

                function fillSelect(districts) {
                    document.getElementById("bank_district_no").innerHTML =
                        districts.reduce((tmp, x) =>
                            `${tmp}<option value='${x.id}'>${x.dist_name}</option>`, '');
                }

                function fetchRecords(province_no) {
                    var uri = "{{ route('getdistricts', ':no') }}";
                    uri = uri.replace(':no', province_no);
                    $.ajax({
                        url: uri,
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
        });
    </script>
@endpush

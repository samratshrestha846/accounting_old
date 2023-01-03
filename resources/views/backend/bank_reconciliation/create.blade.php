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
                    <h1>New Bank Reconciliation </h1>
                    <div class="btn-bulk" style="margin-top:5px;">
                        <a href="{{ route('bankReconciliationStatement.index') }}" class="global-btn">View
                            Reconciliations</a>
                    </div>
                    <!-- /.col -->
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
                        <form action="{{ route('bankReconciliationStatement.store') }}" method="POST">
                            @csrf
                            @method("POST")
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cheque_entry_date">Cheque entried (B.S):</label>
                                        <input type="text" name="cheque_entry_date" id="cheque_entry_date"
                                            class="form-control" value="{{ $nepali_today }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="bank">Bank:</label>
                                        <div class="row">
                                            <div class="col-md-9 pr-0">
                                                <select name="bank_id" class="form-control" id="bank_info">
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" data-toggle='modal' data-target='#bankinfoadd'
                                                    data-toggle='tooltip' data-placement='top'
                                                    class="btn btn-primary icon-btn btn-sm" title="Add New Bank"><i
                                                        class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="receipt_payment">Receipt / Payment:</label>
                                        <select name="receipt_payment" class="form-control">
                                            <option value="">--Select an option--</option>
                                            <option value="0">Receipt</option>
                                            <option value="1">Payment</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="amount">Amount:</label>
                                        <input type="number" step="any" class="form-control" name="amount"
                                            placeholder="Amount in Rs.">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="related_party">Related Party:</label>
                                        <select name="vendor_id" class="form-control" id="third_party">
                                            <option value="">--Select a supplier--</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">
                                                    {{ $supplier->company_name }}</option>
                                            @endforeach
                                            <option value="other">Others..</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2" style="display: none;" id="other_party">
                                    <div class="form-group">
                                        <label for="others">Other party Name:</label>
                                        <input type="text" name="others" class="form-control"
                                            placeholder="Other party's Name">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="method">Payment Method:</label>
                                        <select name="method" class="form-control" id="payment_method">
                                            <option value="">--Select a method--</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2" style="display: none;" id="cheque_no">
                                    <div class="form-group">
                                        <label for="cheque_no">Cheque No:</label>
                                        <input type="text" name="cheque_no" class="form-control" placeholder="Cheque no.">
                                    </div>
                                </div>

                                <div class="col-md-12 mt-2">
                                    <button type="submit" class="btn btn-primary btn-sm ml-auto">Submit</button>
                                </div>
                            </div>
                        </form>

                        <div class='modal fade text-left' id='bankinfoadd' tabindex='-1' role='dialog'
                            aria-labelledby='exampleModalLabel' aria-hidden='true'>
                            <div class='modal-dialog' role='document' style="max-width: 1000px;">
                                <div class='modal-content'>
                                    <div class='modal-header text-center'>
                                        <h2 class='modal-title' id='exampleModalLabel'>Add New Bank</h2>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                    <div class='modal-body'>
                                        <form action="" method="POST" id="bank_add_form">
                                            @csrf
                                            @method("POST")
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="bank_name">Bank Name <span
                                                                                class="text-danger">*</span>: </label>
                                                                        <input type="text" name="bank_name"
                                                                            class="form-control" id="bank_name"
                                                                            placeholder="Enter Bank Name"
                                                                            value="{{ old('bank_name') }}">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('bank_name') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="head_branch">Branch <span
                                                                                class="text-danger">*</span>: </label>
                                                                        <select name="head_branch" class="form-control"
                                                                            id="head_branch">
                                                                            <option value="">--Select one option--</option>
                                                                            <option value="Head Office">Head Office</option>
                                                                            <option value="Branch">Branch</option>
                                                                        </select>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('head_branch') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="bank_province_no">Province no. <i
                                                                                class="text-danger">*</i>:</label>
                                                                        <select name="bank_province_no"
                                                                            class="form-control bank_province"
                                                                            id="bank_province_no">
                                                                            <option value="">--Select a province--</option>
                                                                            @foreach ($provinces as $province)
                                                                                <option value="{{ $province->id }}">
                                                                                    {{ $province->eng_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('bank_province_no') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="bank_district_no">Districts <i
                                                                                class="text-danger">*</i>:</label>
                                                                        <select name="bank_district_no"
                                                                            class="form-control" id="bank_district_no">
                                                                            <option value="">--Select a province first--
                                                                            </option>
                                                                        </select>
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('bank_district_no') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="bank_local_address">Local Address <i
                                                                                class="text-danger">*</i>:</label>
                                                                        <input type="text" name="bank_local_address"
                                                                            class="form-control"
                                                                            placeholder="Eg: Chamti tole"
                                                                            id="bank_local_address">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('bank_local_address') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="account_no">Account no. <i
                                                                                class="text-danger">*</i>:</label>
                                                                        <input type="text" class="form-control"
                                                                            name="account_no"
                                                                            placeholder="Enter Account no." id="account_no">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('account_no') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="account_name">Account Name <i
                                                                                class="text-danger">*</i>:</label>
                                                                        <input type="text" class="form-control"
                                                                            name="account_name"
                                                                            placeholder="Enter Account Name"
                                                                            id="account_name">
                                                                        <p class="text-danger">
                                                                            {{ $errors->first('account_name') }}
                                                                        </p>
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-12 text-center">
                                                                    <button type="submit"
                                                                        class="btn btn-primary btn-sm">Submit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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

        $(document).ready(function() {
            $("#bank_info").select2();
        });

        $(document).ready(function() {
            $("#third_party").select2();
        });
    </script>

    <script type="text/javascript">
        window.onload = function() {
            function fillSelectbank_info(bank_info) {
                document.getElementById("bank_info").innerHTML = '<option value=""> --Select option-- </option>' +
                    bank_info.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.bank_name} (${x.head_branch})</option>`, '');
            }

            function fetchbanks() {
                $.ajax({
                    url: "{{ route('apibankinfo') }}",
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var bank_info = response;
                        fillSelectbank_info(bank_info);
                    }
                });
            }
            fetchbanks();

            document.getElementById("cheque_entry_date").nepaliDatePicker();
            // document.getElementById("cheque_cashed_date").nepaliDatePicker();
        };
    </script>

    <script>
        $(document).ready(function() {
            $("#bank_add_form").submit(function(event) {

                var formData = {
                    bank_name: $("#bank_name").val(),
                    head_branch: $("#head_branch").val(),
                    bank_province_no: $("#bank_province_no").val(),
                    bank_district_no: $("#bank_district_no").val(),
                    bank_local_address: $("#bank_local_address").val(),
                    account_no: $("#account_no").val(),
                    account_name: $("#account_name").val(),
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('post.apibankinfo') }}",
                    data: formData,
                    dataType: "json",
                    encode: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {

                    function fillSelectbank_info(bank_info) {
                        document.getElementById("bank_info").innerHTML =
                            '<option value=""> --Select option-- </option>' +
                            bank_info.reduce((tmp, x) =>
                                `${tmp}<option value='${x.id}'>${x.bank_name} (${x.head_branch})</option>`, '');
                    }

                    function fetchbanks() {
                        $.ajax({
                            url: "{{ route('apibankinfo') }}",
                            type: 'get',
                            dataType: 'json',
                            success: function(response) {
                                var bank_info = response;
                                fillSelectbank_info(bank_info);
                            }
                        });
                    }
                    fetchbanks();
                    $("#bank_add_form").html(
                        '<div class="alert alert-success">Successfully added.</div>'
                    );
                });
                event.preventDefault();
            });
        });

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

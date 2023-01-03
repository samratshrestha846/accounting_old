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
                    <h1>Daily Expense Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('dailyexpenses.index') }}" class="global-btn">Daily Expenses</a>
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('dailyexpenses.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method("POST")
                                        <div class="row">

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="date">Purchase Date<i class="text-danger">*</i></label>
                                                    <input type="text" id="entry_date" name="date"
                                                        class="form-control datepicker" value="{{ old('date') }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('date') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="bill_number">Bill number<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="bill_number" class="form-control"
                                                        value="{{ old('bill_number') }}" placeholder="Enter Bill number">
                                                    <p class="text-danger">
                                                        {{ $errors->first('bill_number') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="image">Bill image (optional)</label>
                                                    <input type="file" name="bill_image" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="vendor_name">Purchased From<i
                                                            class="text-danger">*</i></label>
                                                    <div class="row">
                                                        <div class="col-md-10 pr-0">
                                                            <select name="vendor_name" id="vendor"
                                                                class="form-control supplier_info">
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2" style="padding-left:7px;">
                                                            <button type="button" data-toggle='modal'
                                                                data-target='#supplieradd' data-toggle='tooltip'
                                                                data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                                title="Add New Supplier"><i
                                                                    class="fas fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                    <p class="text-danger">
                                                        {{ $errors->first('vendor_name') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="bill_amount">Bill Amount<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="bill_amount" class="form-control"
                                                        value="{{ old('bill_amount') }}" placeholder="Enter Bill Amount">
                                                    <p class="text-danger">
                                                        {{ $errors->first('bill_amount') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="paid_amount">Paid Amount<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="paid_amount" class="form-control"
                                                        value="{{ old('paid_amount') }}" placeholder="Enter Paid Amount">
                                                    <p class="text-danger">
                                                        {{ $errors->first('paid_amount') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="purpose">Particulars<i class="text-danger">*</i></label>
                                                    <textarea name="purpose" class="form-control" style="height:80px;" cols="30" rows="10"
                                                        placeholder="Purpose of expenses"
                                                        value="{{ old('purpose') }}"></textarea>
                                                    <p class="text-danger">
                                                        {{ $errors->first('purpose') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="btn-bulk d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <button type="submit" class="btn btn-secondary btn-large" name="saveandcontinue" value="1">Submit And Continue</button>
                                        </div>
                                    </form>

                                    <div class='modal fade text-left' id='supplieradd' tabindex='-1' role='dialog'
                                        aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document' style="max-width: 1000px;">
                                            <div class='modal-content'>
                                                <div class='modal-header text-center'>
                                                    <h2 class='modal-title' id='exampleModalLabel'>Add Supplier</h2>
                                                    <button type='button' class='close' data-dismiss='modal'
                                                        aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <form action="" method="POST" id="supplier_form">
                                                        @csrf
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
                                                                                placeholder="Enter Company Name"
                                                                                id="company_name" required>
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('company_name') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="name">Company Code (Code has to be
                                                                                unique)<i
                                                                                    class="text-danger">*</i></label>
                                                                            <input type="text" name="supplier_code"
                                                                                class="form-control"
                                                                                value="{{ $supplier_code }}"
                                                                                placeholder="Enter Company Code"
                                                                                id="company_code">
                                                                            <p class="text-danger companycode_error hide">
                                                                                Code is already used. Use Different code.
                                                                            </p>
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('supplier_code') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group"
                                                                            id="company_email_group">
                                                                            <label for="company_email">Company Email</label>
                                                                            <input type="email" name="company_email"
                                                                                class="form-control"
                                                                                value="{{ old('company_email') }}"
                                                                                placeholder="Enter Company Email"
                                                                                id="company_email">
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('company_email') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group"
                                                                            id="company_phone_group">
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
                                                                        <div class="form-group" id="pan_vat_group">
                                                                            <label for="pan_vat">PAN No./VAT No.
                                                                                (Optional)</label>
                                                                            <input type="text" name="pan_vat"
                                                                                class="form-control"
                                                                                value="{{ old('pan_vat') }}"
                                                                                placeholder="Enter Company PAN or VAT No."
                                                                                id="pan_vat">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="province_id_group">
                                                                            <label for="province">Province no.</label>
                                                                            <select name="province"
                                                                                class="form-control province"
                                                                                id="province_id">
                                                                                <option value="">--Select a province--
                                                                                </option>
                                                                                @foreach ($provinces as $province)
                                                                                    <option value="{{ $province->id }}">
                                                                                        {{ $province->eng_name }}
                                                                                    </option>
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
                                                                        <div class="form-group"
                                                                            id="company_address_group">
                                                                            <label for="company_address">Company Local
                                                                                Address</label>
                                                                            <input type="text" name="company_address"
                                                                                class="form-control"
                                                                                value="{{ old('company_address') }}"
                                                                                placeholder="Company Address"
                                                                                id="company_address">
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('company_address') }}
                                                                            </p>
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
                                                                        <div class="form-group"
                                                                            id="concerned_name_group">
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
                                                                        <div class="form-group"
                                                                            id="concerned_phone_group">
                                                                            <label for="concerned_phone">Phone</label>
                                                                            <input type="text" name="concerned_phone"
                                                                                class="form-control"
                                                                                value="{{ old('concerned_phone') }}"
                                                                                placeholder="Enter Phone"
                                                                                id="concerned_phone">
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('concerned_phone') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group"
                                                                            id="concerned_email_group">
                                                                            <label for="concerned_email">Email</label>
                                                                            <input type="email" name="concerned_email"
                                                                                class="form-control"
                                                                                value="{{ old('concerned_email') }}"
                                                                                placeholder="Enter Email"
                                                                                id="concerned_email">
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
                                                                                placeholder="Enter Designation"
                                                                                id="designation">
                                                                            <p class="text-danger">
                                                                                {{ $errors->first('designation') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
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
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')

    {{-- Nepali Date-picker --}}
    <script src="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/js/nepali.datepicker.v3.6.min.js"
        type="text/javascript">
    </script>

    <script type="text/javascript">
        window.onload = function() {
            var mainInput = document.getElementById("entry_date");
            var date = new Date;
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDate();
            let Addate = {
                year: year,
                month: month,
                day: day
            };
            var nepali_date = NepaliFunctions.AD2BS(Addate);
            var nepali_date_string = nepali_date['year'] + '/' + nepali_date['month'] + '/' + nepali_date['day'];
            mainInput.value = nepali_date_string;
            mainInput.nepaliDatePicker();

            let supplierId = "{{ old('vendor_id') }}";
            function fillSelectSuppliers(suppliers) {
                document.getElementById("vendor").innerHTML = '<option value=""> --Select an option-- </option>' +
                    suppliers.reduce((tmp, x) => `${tmp}<option value='${x.id}' ${x.id == supplierId ? 'selected' : ''}>${x.company_name}</option>`, '');
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
        $(document).ready(function() {
            $(".supplier_info").select2();
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
        $(function() {
            $('.province').change(function() {
                var province_no = $(this).children("option:selected").val();

                function fillSelect(districts) {
                    document.getElementById("district").innerHTML =
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
@endpush

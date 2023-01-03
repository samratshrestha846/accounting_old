@extends('backend.layouts.app')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        div#ndp-nepali-box {
            top: 1069px !important;
        }

    </style>
@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>New Company Share </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('company_share.index') }}" class="global-btn">View Company
                            Shares</a>
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

                <form action="{{ route('company_share.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method("POST")
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Share Information</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="quantity_kitta">Quantity (Kitta) <span
                                                        class="text-danger">*</span>: </label>
                                                <input type="number" name="quantity_kitta" class="form-control"
                                                    placeholder="Kitta" step="any" value="{{ old('quantity_kitta') }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('quantity_kitta') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="total_amount">Total Amount <span
                                                        class="text-danger">*</span>: </label>
                                                <input type="number" name="total_amount" class="form-control"
                                                    placeholder="Total Amount (Rs.)" step="any"
                                                    value="{{ old('total_amount') }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('total_amount') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="share_type">Share Type <span class="text-danger">*</span>:
                                                </label>
                                                <select name="share_type" class="form-control">
                                                    <option value="">--Select a type--</option>
                                                    <option value="Promoter">Promoter</option>
                                                    <option value="Ordinary">Ordinary</option>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('share_type') }}
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h2>Shareholder's Information</h2>
                                </div>
                                <div class="card-body" id="newshareholder_info">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="shareholder_name">Name: </label>
                                                <input type="text" name="shareholder_name[]" class="form-control"
                                                    placeholder="Shareholder Name">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="shareholder_email">Email: </label>
                                                <input type="text" name="shareholder_email[]" class="form-control"
                                                    placeholder="Eg: example@gmail.com">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="shareholder_contact">Contact no.: </label>
                                                <input type="text" name="shareholder_contact[]" class="form-control"
                                                    placeholder="Contact no.">
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <label>&nbsp;</label>
                                            <a href="" class="btn btn-primary icon-btn btn-sm" title="Add new Shareholder"
                                                onclick="addShareholderInfo()"><i
                                                    class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h2>Company Information</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_name">Company Name <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="company_name" class="form-control"
                                                    value="{{ old('company_name') }}" placeholder="Company Name">
                                                <p class="text-danger">
                                                    {{ $errors->first('company_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pan_vat_no">PAN / VAT no. <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="pan_vat_no" class="form-control"
                                                    value="{{ old('pan_vat_no') }}" placeholder="PAN / VAT no">
                                                <p class="text-danger">
                                                    {{ $errors->first('pan_vat_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pan_vat_document">PAN / VAT document <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="file" name="pan_vat_document" class="form-control"
                                                    value="{{ old('pan_vat_document') }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('pan_vat_document') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_contact_no">Contact no. <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="company_contact_no" class="form-control"
                                                    value="{{ old('company_contact_no') }}" placeholder="Phone no.">
                                                <p class="text-danger">
                                                    {{ $errors->first('company_contact_no') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_email">Company Email <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="company_email" class="form-control"
                                                    value="{{ old('company_email') }}"
                                                    placeholder="eg:example@gmail.com  ">
                                                <p class="text-danger">
                                                    {{ $errors->first('company_email') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_paidup_capital">Company Paid Up Capital <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="company_paidup_capital" class="form-control"
                                                    value="{{ old('company_paidup_capital') }}"
                                                    placeholder="Paid Up Capital in Rs.">
                                                <p class="text-danger">
                                                    {{ $errors->first('company_paidup_capital') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_capital">Company Total Capital <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="company_capital" class="form-control"
                                                    value="{{ old('company_capital') }}"
                                                    placeholder="Total capital in Rs.">
                                                <p class="text-danger">
                                                    {{ $errors->first('company_capital') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="last_year_audit_report">Last year Audit Report <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="file" name="last_year_audit_report" class="form-control"
                                                    value="{{ old('last_year_audit_report') }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('last_year_audit_report') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="project_field_report">Project Field (Jagga) Report <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="file" name="project_field_report" class="form-control"
                                                    value="{{ old('project_field_report') }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('project_field_report') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <hr>
                                            <h4> Company Registraion Address Details</h4>
                                            <hr>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="registration_date">Resistration Date <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="registration_date" class="form-control"
                                                    value="{{ $nepali_today }}" id="registration_date">
                                                <p class="text-danger">
                                                    {{ $errors->first('registration_date') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="company_registration_no">Company Registration no. <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="company_registration_no" class="form-control"
                                                    value="{{ old('company_registration_no') }}"
                                                    placeholder="Company Registration no.">
                                                <p class="text-danger">
                                                    {{ $errors->first('company_registration_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="registration_documents">Registration Documents <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="file" name="registration_documents" class="form-control"
                                                    value="{{ old('registration_documents') }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('registration_documents') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="registered_province_no">Province no. <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="registered_province_no" class="form-control province">
                                                    <option value="">--Select a province--</option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}">
                                                            {{ $province->eng_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('registered_province_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="registered_district_no">Districts <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="registered_district_no" class="form-control" id="district">
                                                    <option value="">--Select a province first--</option>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('registered_district_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="registered_local_address">Local Address <i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" name="registered_local_address" class="form-control"
                                                    placeholder="Eg: Chamti tole">
                                                <p class="text-danger">
                                                    {{ $errors->first('registered_local_address') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="work_details">Work Details <i
                                                        class="text-danger">*</i>:</label>
                                                <textarea name="work_details" id="summernote" class="form-control"
                                                    cols="30" rows="10"></textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('work_details') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h2>Responsible Person Information</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="request_person_name">Name <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="request_person_name" class="form-control"
                                                    value="{{ old('request_person_name') }}"
                                                    placeholder="Responsible Person">
                                                <p class="text-danger">
                                                    {{ $errors->first('request_person_name') }}
                                                </p>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="designation">Designation <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="designation" class="form-control"
                                                    value="{{ old('designation') }}"
                                                    placeholder="Designation in company">
                                                <p class="text-danger">
                                                    {{ $errors->first('designation') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="request_contact_no">Contact no. <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="request_contact_no" class="form-control"
                                                    value="{{ old('request_contact_no') }}" placeholder="Phone no.">
                                                <p class="text-danger">
                                                    {{ $errors->first('request_contact_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="request_email">Email <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="request_email" class="form-control"
                                                    value="{{ old('request_email') }}"
                                                    placeholder="Eg: example@gmail.com">
                                                <p class="text-danger">
                                                    {{ $errors->first('request_email') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="request_person_citizenship">Citizenship <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="file" name="request_person_citizenship" class="form-control"
                                                    value="{{ old('request_person_citizenship') }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('request_person_citizenship') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <button type="submit" class="btn btn-secondary btn-sm btn-large">Submit</button>
                        </div>
                    </div>
                </form>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <script type="text/javascript">
        $('#summernote').summernote({
            height: 200,
            placeholder: "Work Details Here..."
        });
    </script>

    <script>
        window.onload = function() {
            document.getElementById("registration_date").nepaliDatePicker();
        };

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
        });
    </script>


    <script>
        function addShareholderInfo() {
            event.preventDefault();
            var x = document.getElementById("newshareholder_info");
            var new_row = document.createElement("div");
            new_row.className = "row";


            new_row.innerHTML = `
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shareholder_name">Name: </label>
                                            <input type="text" name="shareholder_name[]" class="form-control" value="{{ old('shareholder_name') }}" placeholder="Shareholder Name">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shareholder_email">Email: </label>
                                            <input type="text" name="shareholder_email[]" class="form-control" value="{{ old('shareholder_email') }}" placeholder="Eg: example@gmail.com">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="shareholder_contact">Contact no.: </label>
                                            <input type="text" name="shareholder_contact[]" class="form-control" value="{{ old('shareholder_contact') }}" placeholder="Contact no.">
                                        </div>
                                    </div>
                                    `;
            var pos = x.childElementCount + 1;
            x.insertBefore(new_row, x.childNodes[pos]);
        }
    </script>

@endpush

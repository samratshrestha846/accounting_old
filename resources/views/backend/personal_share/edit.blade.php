@extends('backend.layouts.app')
@push('styles')
    <style>
        div#ndp-nepali-box {
            top: 865px !important;
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
                    <h1>Update Personal Share </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('personal_share.index') }}" class="global-btn">View Personal Shares</a>
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

                <form action="{{ route('personal_share.update', $personal_share->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Share Info</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="quantity_kitta">Quantity (Kitta) <span
                                                        class="text-danger">*</span>: </label>
                                                <input type="number" name="quantity_kitta" class="form-control"
                                                    placeholder="Kitta" step="any"
                                                    value="{{ $personal_share->quantity_kitta }}">
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
                                                    value="{{ $personal_share->total_amount }}">
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
                                                    <option value="Promoter"
                                                        {{ $personal_share->share_type == 'Promoter' ? 'selected' : '' }}>
                                                        Promoter</option>
                                                    <option value="Ordinary"
                                                        {{ $personal_share->share_type == 'Ordinary' ? 'selected' : '' }}>
                                                        Ordinary</option>
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
                                    <h2>Personal Information</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="shareholder_name">Shareholder's Name <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="shareholder_name" class="form-control"
                                                    value="{{ $personal_share->shareholder_name }}"
                                                    placeholder="Shareholder's Name">
                                                <p class="text-danger">
                                                    {{ $errors->first('shareholder_name') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="grandfather">GrandFather's Name <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="grandfather" class="form-control"
                                                    value="{{ $personal_share->grandfather }}"
                                                    placeholder="GrandFather's Name">
                                                <p class="text-danger">
                                                    {{ $errors->first('grandfather') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="father">Father's Name <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="father" class="form-control"
                                                    value="{{ $personal_share->father }}" placeholder="Father's Name">
                                                <p class="text-danger">
                                                    {{ $errors->first('father') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <hr>
                                            <h4>Permanent address</h4>
                                            <hr>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="permanent_province_no">Province no. <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="permanent_province_no" class="form-control province">
                                                    <option value="">--Select a province--</option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}"
                                                            {{ $province->id == $personal_share->permanent_province_no ? 'selected' : '' }}>
                                                            {{ $province->eng_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('permanent_province_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="permanent_district_no">Districts <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="permanent_district_no" class="form-control" id="district">
                                                    <option value="">--Select a province first--</option>
                                                    @foreach ($districts as $district)
                                                        <option value="{{ $district->id }}"
                                                            {{ $district->id == $personal_share->permanent_district_no ? 'selected' : '' }}>
                                                            {{ $district->dist_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('permanent_district_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="permanent_local_address">Local Address <i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" name="permanent_local_address" class="form-control"
                                                    placeholder="Eg: Chamti tole"
                                                    value="{{ $personal_share->permanent_local_address }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('permanent_local_address') }}
                                                </p>
                                            </div>
                                        </div>


                                        <div class="col-md-12 text-center">
                                            <hr>
                                            <h4>Current address</h4>
                                            <hr>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="temporary_province_no">Province no. <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="temporary_province_no"
                                                    class="form-control temporary_province">
                                                    <option value="">--Select a province--</option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}"
                                                            {{ $province->id == $personal_share->temporary_province_no ? 'selected' : '' }}>
                                                            {{ $province->eng_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('temporary_province_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="temporary_district_no">Districts <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="temporary_district_no" class="form-control"
                                                    id="temporary_district">
                                                    <option value="">--Select a province first--</option>
                                                    @foreach ($temporary_districts as $district)
                                                        <option value="{{ $district->id }}"
                                                            {{ $district->id == $personal_share->temporary_district_no ? 'selected' : '' }}>
                                                            {{ $district->dist_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('temporary_district_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="temporary_local_address">Local Address <i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" name="temporary_local_address" class="form-control"
                                                    placeholder="Eg: Chamti tole"
                                                    value="{{ $personal_share->temporary_local_address }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('temporary_local_address') }}
                                                </p>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <hr>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="citizenship_no">Citizenship no. <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="citizenship_no" class="form-control"
                                                    value="{{ $personal_share->citizenship_no }}"
                                                    placeholder="Citizenship no.">
                                                <p class="text-danger">
                                                    {{ $errors->first('citizenship_no') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="citizenship_issue_date">Issue Date <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="citizenship_issue_date" class="form-control"
                                                    value="{{ $personal_share->citizenship_issue_date }}"
                                                    id="citizenship_issue_date">
                                                <p class="text-danger">
                                                    {{ $errors->first('citizenship_issue_date') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="citizenship">Upload Citzenship (Don't select if you want
                                                    previous one):</label>
                                                <input type="file" name="citizenship" class="form-control">
                                                <p class="text-danger">
                                                    {{ $errors->first('citizenship') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <hr>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="identity_type">Select Identity <span
                                                        class="text-danger">*</span>:</label>
                                                <select name="identity_type" class="form-control">
                                                    <option value="">--Select one--</option>
                                                    <option value="Passport"
                                                        {{ $personal_share->identity_type == 'Passport' ? 'selected' : '' }}>
                                                        Passport</option>
                                                    <option value="Identity Card"
                                                        {{ $personal_share->identity_type == 'Identity Card' ? 'selected' : '' }}>
                                                        Identity Card</option>
                                                    <option value="Voters Card"
                                                        {{ $personal_share->identity_type == 'Voters Card' ? 'selected' : '' }}>
                                                        Voters Card</option>
                                                    <option value="Other"
                                                        {{ $personal_share->identity_type == 'Other' ? 'selected' : '' }}>
                                                        Other</option>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('identity_type') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="identity_document">Upload Identity (Don't select if you want
                                                    previous one):</label>
                                                <input type="file" name="identity_document" class="form-control">
                                                <p class="text-danger">
                                                    {{ $errors->first('identity_document') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-12">
                                            <hr>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="contact_no">Contact no. <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="contact_no" class="form-control"
                                                    value="{{ $personal_share->contact_no }}" placeholder="Phone no.">
                                                <p class="text-danger">
                                                    {{ $errors->first('contact_no') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span>:</label>
                                                <input type="text" name="email" class="form-control"
                                                    value="{{ $personal_share->email }}"
                                                    placeholder="eg:example@gmail.com  ">
                                                <p class="text-danger">
                                                    {{ $errors->first('email') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="occupation">Occupation <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="occupation" class="form-control"
                                                    value="{{ $personal_share->occupation }}" placeholder="Occupation">
                                                <p class="text-danger">
                                                    {{ $errors->first('occupation') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="marital_status">Marital Status <span
                                                        class="text-danger">*</span>:</label>
                                                <select name="marital_status" class="form-control">
                                                    <option value="">--Select one--</option>
                                                    <option value="unmarried"
                                                        {{ $personal_share->marital_status == 'unmarried' ? 'selected' : '' }}>
                                                        Unmarried</option>
                                                    <option value="married"
                                                        {{ $personal_share->marital_status == 'married' ? 'selected' : '' }}>
                                                        Married</option>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('marital_status') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="spouse_name">Spouse Name:</label>
                                                <input type="text" name="spouse_name" class="form-control"
                                                    value="{{ $personal_share->spouse_name }}" placeholder="Spouse Name">
                                                <p class="text-danger">
                                                    {{ $errors->first('spouse_name') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="spouse_contact_no">Spouse contact no.:</label>
                                                <input type="text" name="spouse_contact_no" class="form-control"
                                                    value="{{ $personal_share->spouse_contact_no }}"
                                                    placeholder="Phone no.">
                                                <p class="text-danger">
                                                    {{ $errors->first('spouse_contact_no') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card">
                                <div class="card-header">
                                    <h2>Benefitiary Information</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="benefitiary_name">Benefitiary's Name <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="benefitiary_name" class="form-control"
                                                    value="{{ $personal_share->benefitiary_name }}"
                                                    placeholder="Benefitiary's Name">
                                                <p class="text-danger">
                                                    {{ $errors->first('benefitiary_name') }}
                                                </p>
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="relationship">Relation to Shareholder <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="relationship" class="form-control"
                                                    value="{{ $personal_share->relationship }}"
                                                    placeholder="relationship">
                                                <p class="text-danger">
                                                    {{ $errors->first('relationship') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>

                                        <div class="col-md-12 text-center">
                                            <hr>
                                            <h4>Permanent address</h4>
                                            <hr>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="benefitiary_permanent_province_no">Province no. <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="benefitiary_permanent_province_no"
                                                    class="form-control benefitiary_province">
                                                    <option value="">--Select a province--</option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}"
                                                            {{ $province->id == $personal_share->benefitiary_permanent_province_no ? 'selected' : '' }}>
                                                            {{ $province->eng_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('benefitiary_permanent_province_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="benefitiary_permanent_district_no">Districts <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="benefitiary_permanent_district_no" class="form-control"
                                                    id="benefitiary_district">
                                                    <option value="">--Select a province first--</option>
                                                    @foreach ($benefitiary_permanent_districts as $district)
                                                        <option value="{{ $district->id }}"
                                                            {{ $district->id == $personal_share->benefitiary_permanent_district_no ? 'selected' : '' }}>
                                                            {{ $district->dist_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('benefitiary_permanent_district_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="benefitiary_permanent_local_address">Local Address <i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" name="benefitiary_permanent_local_address"
                                                    class="form-control" placeholder="Eg: Chamti tole"
                                                    value="{{ $personal_share->benefitiary_permanent_local_address }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('benefitiary_permanent_local_address') }}
                                                </p>
                                            </div>
                                        </div>


                                        <div class="col-md-12 text-center">
                                            <hr>
                                            <h4>Current address</h4>
                                            <hr>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="benefitiary_temporary_province_no">Province no. <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="benefitiary_temporary_province_no"
                                                    class="form-control benefitiary_temporary_province">
                                                    <option value="">--Select a province--</option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}"
                                                            {{ $province->id == $personal_share->benefitiary_temporary_province_no ? 'selected' : '' }}>
                                                            {{ $province->eng_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('benefitiary_temporary_province_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="benefitiary_temporary_district_no">Districts <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="benefitiary_temporary_district_no" class="form-control"
                                                    id="benefitiary_temporary_district">
                                                    <option value="">--Select a province first--</option>
                                                    @foreach ($benefitiary_temporary_districts as $district)
                                                        <option value="{{ $district->id }}"
                                                            {{ $district->id == $personal_share->benefitiary_temporary_district_no ? 'selected' : '' }}>
                                                            {{ $district->dist_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('benefitiary_temporary_district_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="benefitiary_temporary_local_address">Local Address <i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" name="benefitiary_temporary_local_address"
                                                    class="form-control" placeholder="Eg: Chamti tole"
                                                    value="{{ $personal_share->benefitiary_temporary_local_address }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('benefitiary_temporary_local_address') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <hr>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="benefitiary_contact_no">Contact no. <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="benefitiary_contact_no" class="form-control"
                                                    value="{{ $personal_share->benefitiary_contact_no }}"
                                                    placeholder="Phone no.">
                                                <p class="text-danger">
                                                    {{ $errors->first('benefitiary_contact_no') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="benefitiary_email">Email <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="benefitiary_email" class="form-control"
                                                    value="{{ $personal_share->benefitiary_email }}"
                                                    placeholder="eg:example@gmail.com  ">
                                                <p class="text-danger">
                                                    {{ $errors->first('benefitiary_email') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="benefitiary_citizenship">Upload Citzenship (Don't select if you
                                                    want previous one):</label>
                                                <input type="file" name="benefitiary_citizenship" class="form-control"
                                                    value="{{ $personal_share->benefitiary_citizenship }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('benefitiary_citizenship') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 text-center mb-4">
                            <button type="submit" class="btn btn-secondary">Update</button>
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

    <script>
        window.onload = function() {
            document.getElementById("citizenship_issue_date").nepaliDatePicker();
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

            $('.temporary_province').change(function() {
                var province_no = $(this).children("option:selected").val();

                function fillSelect(districts) {
                    document.getElementById("temporary_district").innerHTML =
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

        $(function() {
            $('.benefitiary_province').change(function() {
                var province_no = $(this).children("option:selected").val();

                function fillSelect(districts) {
                    document.getElementById("benefitiary_district").innerHTML =
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

            $('.benefitiary_temporary_province').change(function() {
                var province_no = $(this).children("option:selected").val();

                function fillSelect(districts) {
                    document.getElementById("benefitiary_temporary_district").innerHTML =
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

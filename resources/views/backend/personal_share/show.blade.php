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
                    <h1>Personal Share Info </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('personal_share.index') }}" class="global-btn">View Personal Shares</a> <a
                            href="{{ route('personal_share.edit', $personal_share->id) }}" class="global-btn">Edit
                            info</a>
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

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h2>Share Info</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Quantity (Kitta): </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->quantity_kitta }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Total Amount: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">Rs. {{ $personal_share->total_amount }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Share Type: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->share_type }}</p>
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
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Shareholder's Name: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->shareholder_name }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>GrandFather's Name: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->grandfather }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Father's Name: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->father }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Citizenship no.: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->citizenship_no }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Citizenship Issue Date: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->citizenship_issue_date }}</p>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Contact No.: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->contact_no }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Email: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->email }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Occupation: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->occupation }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Marital Status: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->marital_status }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Spouse Name: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->spouse_name }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Spouse contact no.: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->spouse_contact_no }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <hr>
                                                <h4>Permanent address</h4>
                                                <hr>
                                            </div>
                                        </div>

                                        @php
                                            $province = \App\Models\Province::where('id', $personal_share->permanent_province_no)->first();
                                            $district = \App\Models\District::where('id', $personal_share->permanent_district_no)->first();
                                        @endphp

                                        <div class="row">
                                            <div class="col-md-4">
                                                <p style="font-size: 15px;"><b>Province no.: </b> </p>
                                            </div>
                                            <div class="col-md-8">
                                                <p style="font-size: 15px;">{{ $province->eng_name }}</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <p style="font-size: 15px;"><b>District: </b> </p>
                                            </div>
                                            <div class="col-md-8">
                                                <p style="font-size: 15px;">{{ $district->dist_name }}</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <p style="font-size: 15px;"><b>Local Address: </b> </p>
                                            </div>
                                            <div class="col-md-8">
                                                <p style="font-size: 15px;">
                                                    {{ $personal_share->permanent_local_address }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <hr>
                                                <h4>Current address</h4>
                                                <hr>
                                            </div>
                                        </div>

                                        @php
                                            $province = \App\Models\Province::where('id', $personal_share->temporary_province_no)->first();
                                            $district = \App\Models\District::where('id', $personal_share->temporary_district_no)->first();
                                        @endphp

                                        <div class="row">
                                            <div class="col-md-4">
                                                <p style="font-size: 15px;"><b>Province no.: </b> </p>
                                            </div>
                                            <div class="col-md-8">
                                                <p style="font-size: 15px;">{{ $province->eng_name }}</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <p style="font-size: 15px;"><b>District: </b> </p>
                                            </div>
                                            <div class="col-md-8">
                                                <p style="font-size: 15px;">{{ $district->dist_name }}</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <p style="font-size: 15px;"><b>Local Address: </b> </p>
                                            </div>
                                            <div class="col-md-8">
                                                <p style="font-size: 15px;">
                                                    {{ $personal_share->temporary_local_address }}</p>
                                            </div>
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
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Benefitiary Name: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->benefitiary_name }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Relation: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->relationship }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Contact: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->benefitiary_contact_no }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Email: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $personal_share->benefitiary_email }}</p>
                                    </div>
                                </div>


                                @php
                                    $province = \App\Models\Province::where('id', $personal_share->benefitiary_permanent_province_no)->first();
                                    $district = \App\Models\District::where('id', $personal_share->benefitiary_permanent_district_no)->first();
                                    
                                    $benefitiary_temporary_province = \App\Models\Province::where('id', $personal_share->benefitiary_temporary_province_no)->first();
                                    $benefitiary_temporary_district = \App\Models\District::where('id', $personal_share->benefitiary_temporary_district_no)->first();
                                @endphp
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Permanent Address: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">
                                            {{ $personal_share->benefitiary_permanent_local_address }},
                                            {{ $district->dist_name }}, {{ $province->eng_name }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Current Address: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">
                                            {{ $personal_share->benefitiary_temporary_local_address }},
                                            {{ $benefitiary_temporary_district->dist_name }},
                                            {{ $benefitiary_temporary_province->eng_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h2>Shareholder's Citizenship</h2>
                            </div>
                            <div class="card-body">
                                <a href="{{ Storage::disk('uploads')->url($personal_share->citizenship) }}"
                                    target="_blank">
                                    <img src="{{ Storage::disk('uploads')->url($personal_share->citizenship) }}"
                                        style="width: 100%; border-radius: 20%;">
                                </a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h2>Identity Document</h2>
                            </div>
                            <div class="card-body">
                                <a href="{{ Storage::disk('uploads')->url($personal_share->identity_document) }}"
                                    target="_blank">
                                    <img src="{{ Storage::disk('uploads')->url($personal_share->identity_document) }}"
                                        style="width: 100%; border-radius: 20%;">
                                </a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h2>Benefitiary's Citizenship</h2>
                            </div>
                            <div class="card-body">
                                <a href="{{ Storage::disk('uploads')->url($personal_share->benefitiary_citizenship) }}"
                                    target="_blank">
                                    <img src="{{ Storage::disk('uploads')->url($personal_share->benefitiary_citizenship) }}"
                                        style="width: 100%; border-radius: 20%;">
                                </a>
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

@endpush

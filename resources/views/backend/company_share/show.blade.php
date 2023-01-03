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
                    <h1>Company Share Info </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('company_share.index') }}" class="global-btn">View Company Shares</a> <a
                            href="{{ route('company_share.edit', $companyShare->id) }}" class="global-btn">Edit
                            info</a>
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
                                        <p style="font-size: 15px;">{{ $companyShare->quantity_kitta }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Total Amount: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">Rs. {{ $companyShare->total_amount }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Share Type: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->share_type }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h2>Shareholer's Information</h2>
                            </div>
                            <div class="card-body">
                                @if (count($company_shareholders) == 0)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p style="font-size: 15px;"><b>No shareholders...</b></p>
                                        </div>
                                    </div>
                                @endif
                                @foreach ($company_shareholders as $shareholder)
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p style="font-size: 15px;"><b>Name: </b> </p>
                                        </div>
                                        <div class="col-md-9">
                                            <p style="font-size: 15px;">{{ $shareholder->shareholder_name }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <p style="font-size: 15px;"><b>Contact no.: </b> </p>
                                        </div>
                                        <div class="col-md-9">
                                            <p style="font-size: 15px;">{{ $shareholder->shareholder_contact }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <p style="font-size: 15px;"><b>Email: </b> </p>
                                        </div>
                                        <div class="col-md-9">
                                            <p style="font-size: 15px;">{{ $shareholder->shareholder_email }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h2>Company Registration Details</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Registration Date: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->registration_date }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Registration no.: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->company_registration_no }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Registered Address: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->registered_local_address }},
                                            {{ $district->dist_name }}, {{ $province->eng_name }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Work Details: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{!! $companyShare->work_details !!}</p>
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
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Company Name: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->company_name }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Pan / Vat no.: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->pan_vat_no }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Contact no.: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->company_contact_no }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Email: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->company_email }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Paid up capital: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->company_paidup_capital }}</p>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Total Capital: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->company_capital }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h2>Company Registration Details</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Registration Date: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->registration_date }}</p>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Registration no.: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->company_registration_no }}</p>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Registered Address: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->registered_local_address }},
                                            {{ $district->dist_name }}, {{ $province->eng_name }}</p>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Work Details: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{!! $companyShare->work_details !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h2>Responsible Person Information</h2>
                            </div>

                            <div class="card-body">
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Name: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->request_person_name }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Designation: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->designation }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Contact: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->request_contact_no }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="font-size: 15px;"><b>Email: </b> </p>
                                    </div>
                                    <div class="col-md-9">
                                        <p style="font-size: 15px;">{{ $companyShare->request_email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h2>PAN / VAT document</h2>
                            </div>
                            <div class="card-body">
                                <a href="{{ Storage::disk('uploads')->url($companyShare->pan_vat_document) }}"
                                    target="_blank">
                                    <img src="{{ Storage::disk('uploads')->url($companyShare->pan_vat_document) }}"
                                        style="width: 100%; border-radius: 20%;">
                                </a>
                            </div>

                            <div class="card-header">
                                <h2>Registration Document</h2>
                            </div>
                            <div class="card-body">
                                <a href="{{ Storage::disk('uploads')->url($companyShare->registration_documents) }}"
                                    target="_blank">
                                    <img src="{{ Storage::disk('uploads')->url($companyShare->registration_documents) }}"
                                        style="width: 100%; border-radius: 20%;">
                                </a>
                            </div>

                            <div class="card-header">
                                <h2>Last Year Audit Report</h2>
                            </div>
                            <div class="card-body">
                                <a href="{{ Storage::disk('uploads')->url($companyShare->last_year_audit_report) }}"
                                    target="_blank">
                                    <img src="{{ Storage::disk('uploads')->url($companyShare->last_year_audit_report) }}"
                                        style="width: 100%; border-radius: 20%;">
                                </a>
                            </div>

                            <div class="card-header">
                                <h2>Project Field Report</h2>
                            </div>
                            <div class="card-body">
                                <a href="{{ Storage::disk('uploads')->url($companyShare->project_field_report) }}"
                                    target="_blank">
                                    <img src="{{ Storage::disk('uploads')->url($companyShare->project_field_report) }}"
                                        style="width: 100%; border-radius: 20%;">
                                </a>
                            </div>

                            <div class="card-header">
                                <h2>Responsible Person Citizenship</h2>
                            </div>
                            <div class="card-body">
                                <a href="{{ Storage::disk('uploads')->url($companyShare->request_person_citizenship) }}"
                                    target="_blank">
                                    <img src="{{ Storage::disk('uploads')->url($companyShare->request_person_citizenship) }}"
                                        style="width: 100%; border-radius: 20%;">
                                </a>
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

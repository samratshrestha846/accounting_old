@extends('backend.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Staff Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('staff.index') }}" class="global-btn">View
                            Staffs</a>
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-5 text-center">
                                        <img src="{{ Storage::disk('uploads')->url($staff->image) }}"
                                            alt="{{ $staff->name }}" class="img-circle profile_img"
                                            style="max-width: 300px;">
                                    </div>

                                    <div class="col-md-7 text-center">
                                        <b style="font-size: 40px;">{{ $staff->position->name }}</b><br>
                                        <b style="font-size: 30px;">{{ $staff->name }}</b><br>
                                        <p style="font-size: 20px;">{{ $staff->email }}<br>
                                            +977 {{ $staff->phone }}
                                        </p>
                                    </div>
                                    <hr>

                                    <div class="col-md-12 mt">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="font-weight: bolder">Document Name</th>
                                                        <th style="font-weight: bolder">Document Link</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>National Id (pdf)</td>
                                                        <td><a href="{{ Storage::disk('uploads')->url($staff->national_id) }}"
                                                                target="_blank">Click Here</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Documents (Resume/ CV) (pdf)</td>
                                                        <td><a href="{{ Storage::disk('uploads')->url($staff->documents) }}"
                                                                target="_blank">Click Here</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Contract/Agreement (pdf)</td>
                                                        <td><a href="{{ Storage::disk('uploads')->url($staff->contract) }}"
                                                                target="_blank">Click Here</a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="btn-bulk">
                                            <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-secondary">Edit
                                                Info</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

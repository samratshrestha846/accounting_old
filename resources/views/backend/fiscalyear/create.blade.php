@extends('backend.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Fiscal Year</h1>
                    <div class="btn-bulk">
                        <a href="{{route('fiscal_year.index')}}" class="global-btn">View all</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
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
                    <div class="col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-header text-center">
                                <h2>Fill Fiscal Year Information</h2>
                            </div>
                            <div class="card-body">
                                <form id="fiscal_yearform" action="{{ route('fiscal_year.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method("POST")
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="fiscal_year_start">Start Year<i class="text-danger">*</i>
                                                    :</label>
                                                    <input type="text" id="start_year" name="start_year"
                                                    class="form-control" value="{{ old('start_year') }}"
                                                    placeholder="eg:-2078" style="width:91%;">
                                                    <p class="text-danger">
                                                        {{ $errors->first('start_year') }}
                                                    </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="fiscal_end_start">End Year<i class="text-danger">*</i>
                                                    :</label>
                                                    <input type="text" id="end_year" name="end_year"
                                                    class="form-control" value="{{ old('end_year') }}"
                                                    placeholder="eg:-2078" style="width:91%;">
                                                    <p class="text-danger">
                                                        {{ $errors->first('end_year') }}
                                                    </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group  text-left">
                                                <label for="">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-large">Submit</button>
        
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop

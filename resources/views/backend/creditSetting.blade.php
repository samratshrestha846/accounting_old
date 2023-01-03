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
                    <h1>Credit Settings </h1>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content mt">
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('supersetting.update', $supersetting->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method("PUT")
                                        <div class="row mt-2">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="allocated_days">Allocated Days<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="number" id="allocated_days" name="allocated_days"
                                                        class="form-control"
                                                        value="{{ old('allocated_days', $supersetting->allocated_days) }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('allocated_days') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="allocated_bills">Number Of Bills Limit<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="number" id="allocated_bills" name="allocated_bills"
                                                        class="form-control"
                                                        value="{{ old('allocated_bills', $supersetting->allocated_bills) }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('allocated_bills') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="allocated_amount">Allocated Total Amount (In Rs. )<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="number" id="allocated_amount" name="allocated_amount"
                                                        class="form-control"
                                                        value="{{ old('allocated_amount', $supersetting->allocated_amount) }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('allocated_amount') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group text-center">
                                            <button type="submit" class="btn btn-primary ml-auto"
                                                name="creditSetting">Submit</button>
                                        </div>
                                    </form>
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

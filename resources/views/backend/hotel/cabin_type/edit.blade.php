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
                    <h1>Edit Cabin Type</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-floor.index') }}" class="global-btn">View Cabin Type</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @if (session('success'))
                            <div class="col-sm-12">
                                <div class="alert  alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('cabintype.update', $cabintype->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method("PATCH")
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Name<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="Enter Name" value="{{ old('name', $cabintype->name) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="floor_code">Remarks</label>
                                                <textarea name="remarks" class="form-control">{{old('remarks', $cabintype->remarks)}}</textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('remarks') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                                </form>
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

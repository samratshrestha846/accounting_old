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
                    <h1>Test Type </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('test-type.index') }}" class="global-btn">View All Test Type</a>
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
                @if ($testType->id)
                    <form action="{{ route('test-type.update', $testType->id) }}" method="post">
                        @method('PATCH')
                    @else
                        <form action="{{ route('test-type.store') }}" method="post">
                @endif
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Test Type Title"
                                        aria-describedby="helpId" value="{{ old('title', $testType->title) }}">
                                    @error('title')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <label for="publish">Publish</label>
                                <div class="d-flex">
                                        <div class="form-check mr-2">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="publish"
                                                    value="1" @if ($testType->publish == true)
                                                {{ 'checked' }}
                                            @endif>
                                            Yes
                                            </label>
                                        </div>
                                        <div class="form-check mr-2">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="publish"
                                                    value="0" @if ($testType->publish == false)
                                                {{ 'checked' }}
                                            @endif>
                                            No
                                            </label>
                                        </div>
                            </div>
                            @error('gender')
                                <small id="helpId" class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="Reset" class="btn btn-dark ml-2">Reset</button>
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

@endpush

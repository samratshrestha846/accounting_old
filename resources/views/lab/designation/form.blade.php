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
                    <h1>Designations </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hospital-designation.index') }}" class="global-btn">View All Designation</a>
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
                @if ($designation->id)
                    <form action="{{ route('hospital-designation.update', $designation->id) }}" method="post">
                        @method('PATCH')
                    @else
                        <form action="{{ route('hospital-designation.store') }}" method="post">
                @endif
                @csrf
                <div class="card">
                    <div class="card-header">Designation Form</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                        value="{{ old('title', $designation->title) }}" required>
                                </div>
                                @error('title')
                                    <small id="helpId" class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" id="description"
                                        rows="3">{{ old('description', $designation->description) }}</textarea>
                                </div>
                                @error('description')
                                    <small id="helpId" class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="btn-bulk mt-2">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="Reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
        $(document).ready(function() {
            $("#users").select2();
        });
    </script>
@endpush

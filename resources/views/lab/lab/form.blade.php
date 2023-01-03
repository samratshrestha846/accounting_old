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
                    <h1>lab </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('lab.index') }}" class="global-btn">View All lab</a>
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
                @if ($lab->id)
                    <form action="{{ route('lab.update', $lab->id) }}" method="post">
                        @method('PATCH')
                    @else
                        <form action="{{ route('lab.store') }}" method="post">
                @endif
                @csrf
                <div class="card">
                    <div class="card-header">lab Form</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="title">title</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="title"
                                        aria-describedby="helpId" value="{{ old('title', $lab->title) }}">
                                    @error('title')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="users">Incharge</label>
                                    <select class="form-control select2" name="labIncharge" id="users">
                                        @foreach ($users as $user)
                                            <option value="{{ $user['id'] }}" @if ($lab->labIncharge = $user['id'])
                                                {{ 'selected' }}
                                        @endif>{{ $user['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('labIncharge')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
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

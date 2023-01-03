@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Edit Dealer Type</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('tax.index') }}" class="global-btn">View Dealer Type</a>
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
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('dealertype.update', $dealerType->id) }}" method="POST" class="">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="title">Title: </label>
                                                <input type="text" name="title" class="form-control" value="{{ $dealerType->title }}"
                                                    placeholder="Enter Tax title">
                                                @error('title')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="percent">Percent</label>
                                                <input type="number" class="form-control" name="percent"
                                                    value="{{ $dealerType->percent }}" placeholder="Enter Tax Percent">
                                                @error('percent')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="make_user">Make User</label><br>
                                                <input type="radio" name="make_user" value="1" {{$dealerType->make_user == '1' ? 'checked' : ''}}> Yes
                                                <input type="radio" name="make_user" value="0" {{$dealerType->make_user == '0' ? 'checked' : ''}}> No
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-secondary btn-sm">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')

@endpush

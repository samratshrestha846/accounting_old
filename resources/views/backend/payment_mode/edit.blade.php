@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Edit Payment Mode </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('paymentmode.index') }}" class="global-btn">View Payment
                            Mode</a>
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
                                <form action="{{ route('paymentmode.update', $paymentmode->id) }}" method="POST"
                                    class="">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="payment_mode">Payment Mode: </label>
                                                <input type="text" name="payment_mode" class="form-control"
                                                    value="{{ old('payment_mode', $paymentmode->payment_mode) }}" placeholder="Enter Payment Mode">
                                                @error('payment_mode')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="status" style="display: block;">Status: </label>
                                                <input type="radio" name="status" value="1"
                                                    {{ $paymentmode->status == 1 ? 'checked' : '' }}> Approve
                                                <input type="radio" name="status" value="0"
                                                    {{ $paymentmode->status == 0 ? 'checked' : '' }}> Unapprove
                                                @error('percent')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                            </div>
                                        </div>
                                    </div>
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

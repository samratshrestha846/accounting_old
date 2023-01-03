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
                    <h1>Update Online Payment Portal </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('onlinepayment.index') }}"
                                class="global-btn">View Portals</a></h1>
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
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('onlinepayment.update', $onlinePayment->id) }}" method="POST">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Portal Name <i class="text-danger">*</i>:</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Eg: Esewa" value="{{ old('name', $onlinePayment->name) }}">
                                        <p class="text-danger">
                                            {{ $errors->first('name') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="payment_id">Portal Id <i class="text-danger">*</i>:</label>
                                        <input type="text" name="payment_id" class="form-control"
                                            placeholder="Eg: Registered Esewa Id" value="{{ old('payment_id', $onlinePayment->payment_id) }}">
                                        <p class="text-danger">
                                            {{ $errors->first('payment_id') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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

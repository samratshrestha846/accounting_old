@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>{{$dealerUser->client->name}}'s New User </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('client.create') }}" class="global-btn">Add New Customers</a>
                        <a href="{{ route('client.index') }}" class="global-btn">View All Customers</a>
                        <a href="{{ route('client.products', $dealerUser->client->id) }}" class="global-btn">Customers Product</a>
                        <a href="{{ route('clientuser.create', $dealerUser->client->id) }}" class="global-btn">Create {{$dealerUser->client->name}}'s Users</a>
                        <a href="{{ route('clientuser.index', $dealerUser->client->id) }}" class="global-btn">View {{$dealerUser->client->name}}'s Users</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Edit Details
                            </div>
                            <div class="card-body">
                                <form action="{{ route('clientuser.update', $dealerUser->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="client_id" value="{{$dealerUser->id}}">
                                    <div class="form-group">
                                        <label for="name">Full Name<i class="text-danger">*</i>: </label>
                                        <input type="text" name="name" class="form-control" value="{{ $dealerUser->name }}"
                                            placeholder="Enter Full Name">
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email<i class="text-danger">*</i>: </label>
                                        <input type="text" name="email" class="form-control" value="{{ $dealerUser->email }}"
                                            placeholder="E-mail Address">
                                        @error('email')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" name="updatedetails" class="btn btn-secondary btn-sm">Submit</button>
                                </form>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Change Password
                            </div>
                            <div class="card-body">
                                <p class="text-danger">Note*: If you dont want to change password then leave empty.</p>
                                <form action="{{ route('clientuser.update', $dealerUser->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="new_password">New Password<i class="text-danger">*</i>: </label>
                                        <input type="password" name="new_password" class="form-control"
                                            value="{{ @old('new_password') }}" placeholder="New Password">
                                        @error('new_password')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                        @if (session('error'))
                                            <div class="col-sm-12">
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    {{ session('error') }}
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmpassword">Confirm New Password<i class="text-danger">*</i>:
                                        </label>
                                        <input type="password" name="new_password_confirmation" class="form-control"
                                            value="{{ @old('password_confirmation') }}"
                                            placeholder="Re-Enter New Password">
                                        @error('new_password_confirmation')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-secondary btn-sm"
                                        name="updatepassword">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
@endpush

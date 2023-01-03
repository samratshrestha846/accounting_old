@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Edit User</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('user.index') }}" class="global-btn">View Users</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h2>User Details</h2>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('user.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Full Name<i class="text-danger">*</i>: </label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ old('name', $user->name) }}" placeholder="Enter Full Name">
                                                @error('name')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email">Email<i class="text-danger">*</i>: </label>
                                                <input type="text" name="email" class="form-control"
                                                    value="{{ old('email', $user->email) }}" placeholder="E-mail Address">
                                                @error('email')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="role">Role<i class="text-danger">*</i>:</label>
                                                <select class="form-control" name="role_id" id="role">
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}"
                                                            {{ $userrole->role_id == $role->id ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('role_id')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" name="updatedetails"
                                                class="btn btn-primary ml-auto btn-sm">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h2>Change Password</h2>
                            </div>
                            <div class="card-body">
                                <p class="text-danger">Note*: If you dont want to change password then leave empty.</p>
                                <form action="{{ route('user.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
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
                                        </div>
                                        <div class="col-md-6">
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
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-sm ml-auto"
                                        name="updatepassword">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
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

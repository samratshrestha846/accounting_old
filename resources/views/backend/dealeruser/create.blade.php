@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>{{$client->name}}'s New User </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('client.create') }}" class="global-btn">Add New Customers</a>
                        <a href="{{ route('client.index') }}" class="global-btn">View All Customers</a>
                        <a href="{{ route('client.products', $client->id) }}" class="global-btn">Customers Product</a>
                        <a href="{{ route('clientuser.index', $client->id) }}" class="global-btn">View {{$client->name}}'s Users</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('clientuser.store') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="client_id" value="{{$client->id}}">
                                    <div class="form-group">
                                        <label for="name">Full Name<i class="text-danger">*</i>: </label>
                                        <input type="text" name="name" class="form-control" value="{{ @old('name') }}"
                                            placeholder="Enter Full Name">
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email<i class="text-danger">*</i>: </label>
                                        <input type="text" name="email" class="form-control" value="{{ @old('email') }}"
                                            placeholder="E-mail Address">
                                        @error('email')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password<i class="text-danger">*</i>: </label>
                                        <input type="password" name="password" class="form-control"
                                            value="{{ @old('password') }}" placeholder="Password">
                                        @error('password')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="confirmpassword">Confirm Password<i class="text-danger">*</i>:
                                        </label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                            value="{{ @old('password_confirmation') }}" placeholder="Re-Enter Password">
                                        @error('password_confirmation')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-secondary btn-sm">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
@endpush

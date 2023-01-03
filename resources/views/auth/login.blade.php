

@extends('layouts.app')

@section('content')
    <a href="{{route('customer.login')}}" class="btn btn-primary top-btn cust">Login for Customer</a>
    <div class="card card-outline" style="box-shadow: 0px 1px 5px rgb(0 0 0 / 15%);border:none;">
        <div class="card-header text-center" style="padding:20px 20px;background:#fff;">
            <a href="{{ url('/') }}" class="site-logo">
                <img src="{{ asset('logo/logo.png') }}" alt="logo" style="max-width:250px;width:100%;">
            </a>
        </div>
        <div class="card-body" style="background:#f7f7f7;border-bottom-left-radius: 4px;border-bottom-right-radius: 4px;">
            <p class="login-box-msg" style="font-size:14px;font-weight:500;">Sign in to start your session.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group mb-3">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email" style="height: 40px;font-size:14px;">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password" placeholder="Password" style="height: 40px;font-size:14px;">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                        <div class="icheck-primary" style="display: flex;align-items:center;justify-content:space-between;margin-bottom:15px !important;">
                            <div class="chk d-flex align-items-center" style="">
                                <input class="form-check-input" style="margin-left:0;vertical-align:middle;margin-top:0;" type="checkbox" name="remember" id="remember_me"
                                    {{ old('remember_me') ? 'checked' : '' }}>
                                <label for="remember_me" style="padding-left:22px;margin-bottom:0;font-size:14px;">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                    <!-- /.col -->
                        <button type="submit" class="btn btn-primary" style="width: 100%;background-color: #dc3b05;color:white;border-color: #dc3b05;font-size:14px;text-transform:uppercase;">Sign In</button>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
@endsection

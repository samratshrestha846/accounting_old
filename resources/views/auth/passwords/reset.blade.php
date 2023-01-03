@extends('layouts.app')

@section('content')
    <div class="card card-outline" style="box-shadow: 0px 1px 5px rgb(0 0 0 / 15%);border:none;">
        <div class="card-header text-center" style="padding:20px 20px;background:#fff;">
            @php
                $setting = \App\Models\Setting::first();
            @endphp
            <a href="{{ url('/') }}" class="site-logo">
                {{-- {{ $setting->company_name }} --}}
                <img src="{{ asset('logo.png') }}" alt="logo">
            </a>
        </div>
        <div class="card-body" style="background:#f7f7f7;border-bottom-left-radius: 4px;border-bottom-right-radius: 4px;">
            <p class="login-box-msg">{{ __('Reset Password') }}</p>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <div class="input-group mb-3">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
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
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Enter New Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary" style="width: 100%;background-color: #dc3b05;border-color: #dc3b05;font-size:14px;text-transform:uppercase;">Reset Password</button>
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
@endsection








{{-- @extends('layouts.app')

@section('content')
@php
    $setting = \App\Models\Setting::first();
@endphp
<div class="card card-outline card-primary">
    <div class="card-header text-center">
        <a href="{{ url('/') }}" class="h1">{{ $setting->company_name }}</a>
    </div>
    <div class="card-body">
        <p class="login-box-msg">{{ __('Reset Password') }}</p>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <div class="input-group mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
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
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Enter New Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="input-group mb-3">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </div>
        </form>

    </div>
    <!-- /.card-body -->
</div>
@endsection --}}

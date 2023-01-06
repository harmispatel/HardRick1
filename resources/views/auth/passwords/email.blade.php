@extends('admin.layouts.admin-forgot-password')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DLENY</title>
</head>
@section('content')
<div class="auth_main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="auth_inr">
                    <form method="POST" action="{{ route('forget.password.post') }}" aria-label="{{ __('Login') }}">
                        @csrf

                        <div class="text-center">
                            <img alt="logo" src="{{get_site_setting('logo')}}" class="theme-logo">
                            <h4 class="mt-2">D L E N Y</h4>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-gradient-dark">
                                    Send Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

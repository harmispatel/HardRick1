@extends('admin.layouts.admin-forgot-password')

@section('content')
<div class="auth_main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="auth_inr">
                    <form method="POST" action="{{ route('reset.password.post') }}" aria-label="{{ __('Login') }}">
                        @csrf

                        <div class="text-center">
                            <img alt="logo" src="{{get_site_setting('logo')}}" class="theme-logo">
                            <h4 class="mt-2">D L E N Y</h4>
                        </div>

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <label for="password" class="col-form-label text-md-right">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-form-label text-md-right">Confim Password</label>
 
                            <input id="password" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="current-password">

                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                        </div>
                    
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-gradient-dark">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div
@endsection

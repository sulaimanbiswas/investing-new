@extends('admin.layouts.auth')

@section('title', 'Admin | Login')

@section('content')
    <div class="authincation h-100">
      <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
          <div class="col-md-6">
            <div class="authincation-content">
              <div class="row no-gutters">
                <div class="col-xl-12">
                  <div class="auth-form">
                    <div class="text-center mb-3">
                      <a href="{{ url('/') }}">
                        <img src="{{ asset('admin/images/logo-full.png') }}" alt="Logo" />
                      </a>
                    </div>
                    <h4 class="text-center mb-4">Sign in to your account</h4>

                    <form method="POST" action="{{ route('admin.login.store') }}">
                      @csrf
                      <div class="form-group">
                        <label class="mb-1"><strong>Email or Username</strong></label>
                        <input type="text" name="email" class="form-control" value="{{ old('email') }}" required autofocus />
                        @error('email')
                          <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="mb-3 position-relative">
                        <label class="mb-1"><strong>Password</strong></label>
                        <input type="password" name="password" id="dz-password" class="form-control" required />
                        <span class="show-pass eye">
                          <i class="fa fa-eye-slash"></i>
                          <i class="fa fa-eye"></i>
                        </span>
                        @error('password')
                          <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="form-row d-flex justify-content-between flex-wrap mt-4 mb-2">
                        <div class="form-group">
                          <div class="form-check custom-checkbox ms-1">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember me</label>
                          </div>
                        </div>
                        <div class="form-group">
                          <a href="{{ route('password.request') }}">Forgot Password?</a>
                        </div>
                      </div>

                      <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                      </div>
                    </form>

                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

@endsection

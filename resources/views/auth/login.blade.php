@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #121212;
        color: #ffffff;
    }

    .card-dark {
        background-color: #1e1e1e;
        border: 1px solid #333;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.8);
        color: #ffffff;
    }

    label {
        color: #ffffff;
    }

    .form-control,
    .form-check-input {
        background-color: #2a2a2a;
        border: 1px solid #555;
        color: #ffffff;
    }

    .form-control::placeholder {
        color: #ccc;
    }

    .form-control:focus {
        background-color: #2a2a2a;
        border-color: #777;
        box-shadow: none;
        color: #ffffff;
    }

    .btn-primary {
        background-color: #000000;
        border: none;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #ffffff;
    }

    a {
        color: #ffffff;
    }

    a:hover {
        text-decoration: underline;
    }

    .text-danger {
        color: #ffffff !important;
    }
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card card-dark p-4" style="width: 400px;">
        <div class="text-center mb-4">
            <h4 style="color: #ffffff;">Login</h4>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="email">Email Address</label>
                <input id="email" type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input id="password" type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" required>
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>

            <div class="text-center">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

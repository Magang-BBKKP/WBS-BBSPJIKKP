@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <h2 class="auth-title">Password Recovery</h2>
    <p class="auth-subtitle">Forgot your password? No problem. Just let us know your government email address and we will email you a password reset link.</p>

    @if (session('status'))
        <div class="alert alert-success small">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <div class="text-secondary small mb-1">Official ID / Government Email</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center w-100 text-white">
            Email Password Reset Link <i class="bi bi-envelope-check ms-2"></i>
        </button>

        <div class="mt-3 text-center">
            <a href="{{ route('login') }}" class="small text-decoration-none" style="color: #475569;">Back to Login</a>
        </div>
    </form>
</div>
@endsection

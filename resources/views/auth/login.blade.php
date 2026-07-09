@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <h2 class="auth-title">Access Secure Terminal</h2>
    <p class="auth-subtitle">Provide your official credentials to continue.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label d-none">Official ID / Government Email</label>
            <div class="text-secondary small mb-1">Official ID / Government Email</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="e.g. ID-882931" required autofocus autocomplete="username">
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <div class="text-secondary small">Access Password</div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-decoration-none" style="color: #3b82f6;">Forgot Password?</a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••••••" required autocomplete="current-password">
                <button class="btn-toggle-password" type="button" id="togglePassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
            <label class="form-check-label small text-secondary" for="remember_me">
                Remember this terminal for 24 hours
            </label>
        </div>

        <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center w-100 text-white">
            Authorize Session <i class="bi bi-arrow-right ms-2"></i>
        </button>
    </form>

    <div class="mt-4 text-center">
        <span class="small text-secondary">Belum punya akun?</span>
        <a href="{{ route('register') }}" class="small text-decoration-none fw-bold" style="color: #3b82f6;">Sign Up untuk Pendaftaran</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
</script>
@endpush

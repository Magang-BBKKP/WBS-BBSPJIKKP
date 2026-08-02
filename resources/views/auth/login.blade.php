@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 56px; height: 56px; background-color: rgba(10, 66, 130, 0.1);">
            <i class="bi bi-shield-lock-fill fs-3 text-primary"></i>
        </div>
        <h3 class="auth-title">Masuk</h3>
        <p class="auth-subtitle">Silakan masuk menggunakan akun yang telah terdaftar.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" autocomplete="off">
        @csrf

        <div class="mb-3">
            <div class="text-secondary small mb-1">Email</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nama@contoh.com" required autofocus autocomplete="off">
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <div class="text-secondary small">Kata Sandi</div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-decoration-none" style="color: #0a4282; font-weight: 500;">Lupa Kata Sandi?</a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••••••" required autocomplete="new-password">
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
                Ingat Saya
            </label>
        </div>

        <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center w-100 text-white">
            Masuk <i class="bi bi-box-arrow-in-right ms-2"></i>
        </button>
    </form>

    <div class="d-flex align-items-center my-4">
        <div class="flex-grow-1" style="height: 1px; background-color: #e0e0e0;"></div>
        <span class="mx-3 small text-secondary">atau</span>
        <div class="flex-grow-1" style="height: 1px; background-color: #e0e0e0;"></div>
    </div>

    <a href="{{ route('login.google') }}" class="btn btn-outline-secondary d-flex justify-content-center align-items-center w-100">
        <svg class="me-2" width="18" height="18" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
            <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
            <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"/>
            <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238C29.211 35.091 26.715 36 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
            <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.792 2.237-2.231 4.166-4.087 5.571.001-.001.002-.001.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
        </svg>
        Masuk dengan Google
    </a>

    <div class="mt-4 text-center">
        <span class="small text-secondary">Belum memiliki akun?</span>
        <a href="{{ route('register') }}" class="small text-decoration-none fw-bold" style="color: #0a4282;">Daftar Sekarang</a>
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

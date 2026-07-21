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

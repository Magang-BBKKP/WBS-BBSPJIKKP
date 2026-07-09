@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <h2 class="auth-title">Create Account</h2>
    <p class="auth-subtitle">Register your official details to access the terminal.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <div class="text-secondary small mb-1">Full Name</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
            </div>
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="text-secondary small mb-1">Official ID / Government Email</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="e.g. ID-882931" required>
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="text-secondary small mb-1">Access Password</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••••••" required>
                <button class="btn-toggle-password" type="button" id="togglePassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <div class="form-text" style="font-size: 0.75rem;">Must contain at least 8 chars, 1 uppercase, 1 lowercase, 1 number.</div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="text-secondary small mb-1">Confirm Access Password</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center w-100 text-white">
            Register Account <i class="bi bi-person-plus ms-2"></i>
        </button>
    </form>

    <div class="mt-4 text-center">
        <span class="small text-secondary">Sudah punya akun?</span>
        <a href="{{ route('login') }}" class="small text-decoration-none fw-bold" style="color: #3b82f6;">Login di sini</a>
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

@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <h2 class="auth-title">Buat Akun</h2>
    <p class="auth-subtitle">Daftarkan data resmi Anda untuk mengakses sistem.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <div class="text-secondary small mb-1">Nama Lengkap</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap Anda" required autofocus>
            </div>
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="text-secondary small mb-1">Email Resmi / ID Instansi</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="mis. ID-882931" required>
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="text-secondary small mb-1">Nomor WhatsApp Aktif</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="mis. 0812-3456-7890" required>
            </div>
            <div class="form-text" style="font-size: 0.75rem;">Gunakan nomor WhatsApp aktif untuk menerima notifikasi status laporan.</div>
            @error('phone_number')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="text-secondary small mb-1">Kata Sandi</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••••••" required>
                <button class="btn-toggle-password" type="button" id="togglePassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <div class="form-text" style="font-size: 0.75rem;">Harus mengandung minimal 8 karakter, 1 huruf besar, 1 huruf kecil, dan 1 angka.</div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="text-secondary small mb-1">Konfirmasi Kata Sandi</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center w-100 text-white">
            Daftar Akun <i class="bi bi-person-plus ms-2"></i>
        </button>
    </form>

    <div class="mt-4 text-center">
        <span class="small text-secondary">Sudah punya akun?</span>
        <a href="{{ route('login') }}" class="small text-decoration-none fw-bold" style="color: #3b82f6;">Masuk di sini</a>
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

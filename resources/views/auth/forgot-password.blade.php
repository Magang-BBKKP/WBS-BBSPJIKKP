@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <h2 class="auth-title">Pemulihan Kata Sandi</h2>
    <p class="auth-subtitle">Lupa kata sandi? Tidak masalah. Beri tahu kami alamat email kantor Anda dan kami akan mengirimkan tautan untuk mereset kata sandi melalui email.</p>

    @if (session('status'))
        <div class="alert alert-success small">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <div class="text-secondary small mb-1">Email Resmi / ID Instansi</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center w-100 text-white">
            Kirim Tautan Reset Kata Sandi <i class="bi bi-envelope-check ms-2"></i>
        </button>

        <div class="mt-3 text-center">
            <a href="{{ route('login') }}" class="small text-decoration-none" style="color: #475569;">Kembali ke Login</a>
        </div>
    </form>
</div>
@endsection

@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 56px; height: 56px; background-color: rgba(10, 66, 130, 0.1);">
            <i class="bi bi-phone fs-3 text-primary"></i>
        </div>
        <h3 class="auth-title">Lengkapi Nomor WhatsApp</h3>
        <p class="auth-subtitle">Aktivasi WhatsApp aktif untuk menerima notifikasi status laporan.</p>
    </div>

    <form method="POST" action="{{ route('phone.complete') }}">
        @csrf

        <div class="mb-3">
            <div class="text-secondary small mb-1">Nomor WhatsApp Aktif</div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="mis. 0812-3456-7890" required autofocus>
            </div>
            <div class="form-text" style="font-size: 0.75rem;">Nomor ini akan digunakan untuk notifikasi WhatsApp WBS.</div>
            @error('phone_number')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center w-100 text-white">
            Simpan <i class="bi bi-check-lg ms-2"></i>
        </button>
    </form>
</div>
@endsection

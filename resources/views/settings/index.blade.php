@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Pengaturan Sistem</h1>
    <p class="text-muted small mb-0">Konfigurasi informasi dan tampilan Website WBS BBSPJIKKP</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        {{-- Informasi Umum --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h6 class="fw-bold mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Sistem</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Website <span class="text-danger">*</span></label>
                        <input type="text" name="site_name" class="form-control rounded-3 @error('site_name') is-invalid @enderror"
                            value="{{ old('site_name', $settings['site_name']) }}" required>
                        @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Deskripsi Website</label>
                        <textarea name="site_description" class="form-control rounded-3 @error('site_description') is-invalid @enderror"
                            rows="2">{{ old('site_description', $settings['site_description']) }}</textarea>
                        @error('site_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Kontak --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h6 class="fw-bold mb-0"><i class="bi bi-telephone me-2 text-primary"></i>Informasi Kontak</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email Kontak</label>
                        <input type="email" name="contact_email" class="form-control rounded-3 @error('contact_email') is-invalid @enderror"
                            value="{{ old('contact_email', $settings['contact_email']) }}" placeholder="kontak@bbspjikkp.go.id">
                        @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nomor Telepon</label>
                        <input type="text" name="contact_phone" class="form-control rounded-3 @error('contact_phone') is-invalid @enderror"
                            value="{{ old('contact_phone', $settings['contact_phone']) }}" placeholder="(0274) 000-0000">
                        @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small">Alamat Kantor</label>
                        <textarea name="contact_address" class="form-control rounded-3 @error('contact_address') is-invalid @enderror"
                            rows="3" placeholder="Alamat lengkap BBSPJIKKP...">{{ old('contact_address', $settings['contact_address']) }}</textarea>
                        @error('contact_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Logo --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-auto">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h6 class="fw-bold mb-0"><i class="bi bi-image me-2 text-primary"></i>Logo Website</h6>
                </div>
                <div class="card-body px-4 pb-4 text-center">
                    @if($settings['logo_path'])
                        <img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Logo" class="img-fluid rounded-3 mb-3" style="max-height:100px;">
                    @else
                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-3" style="height:100px;">
                            <i class="bi bi-image text-muted fs-2"></i>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-semibold small d-block text-start">Upload Logo Baru</label>
                        <input type="file" name="logo" class="form-control form-control-sm rounded-3 @error('logo') is-invalid @enderror"
                            accept="image/png,image/jpg,image/jpeg,image/svg+xml">
                        <div class="form-text text-start">PNG, JPG, SVG maks. 2MB</div>
                        @error('logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Info SMTP --}}
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h6 class="fw-bold mb-0"><i class="bi bi-envelope me-2 text-primary"></i>Konfigurasi Email (SMTP)</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <p class="text-muted small mb-2">Konfigurasi SMTP dilakukan melalui file <code>.env</code>:</p>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li><code>MAIL_MAILER</code></li>
                        <li><code>MAIL_HOST</code></li>
                        <li><code>MAIL_PORT</code></li>
                        <li><code>MAIL_USERNAME</code></li>
                        <li><code>MAIL_PASSWORD</code></li>
                        <li><code>MAIL_FROM_ADDRESS</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">
            <i class="bi bi-floppy me-2"></i>Simpan Pengaturan
        </button>
    </div>
</form>
@endsection

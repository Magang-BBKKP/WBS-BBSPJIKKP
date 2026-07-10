@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
            <h1 class="h3 text-gray-800 mb-0">Tambah User Baru</h1>
            <p class="text-muted">Isi formulir berikut untuk mendaftarkan akun pengguna baru.</p>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@domain.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-medium">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Minimal 8 karakter">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Phone Number -->
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label fw-medium">Nomor Telepon</label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Contoh: 08123456789">
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Unit Kerja -->
                        <div class="col-md-6 mb-3">
                            <label for="unit_kerja" class="form-label fw-medium">Unit Kerja / Jabatan</label>
                            <input type="text" class="form-control @error('unit_kerja') is-invalid @enderror" id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja') }}" placeholder="Contoh: Divisi IT">
                            @error('unit_kerja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label for="role" class="form-label fw-medium">Role Sistem <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="" disabled selected>Pilih Role Pengguna</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                    {{ str_replace('-', ' ', ucfirst($role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="is_active">Aktifkan Akun (User dapat langsung login setelah ditambahkan)</label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

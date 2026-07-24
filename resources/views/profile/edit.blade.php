@extends('layouts.app')

@push('styles')
<style>
    /* Back to Landing Page Button */
    .btn-back-landing {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #475569;
        background-color: #fff;
        border: 1px solid #cbd5e1;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(10, 66, 130, 0.04);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-back-landing:hover {
        background-color: #f8fafc;
        color: var(--bs-primary);
        border-color: #94a3b8;
        transform: translateX(-4px);
        box-shadow: 0 4px 12px rgba(10, 66, 130, 0.08);
    }

    /* Profile Cover Banner */
    .profile-card {
        overflow: hidden;
        border-radius: 16px !important;
    }
    
    .profile-cover {
        height: 100px;
        background: linear-gradient(135deg, var(--bs-primary), #1a5ea8);
        position: relative;
        margin: -1.5rem -1.5rem 0 -1.5rem;
    }

    /* Profile Photo Card Styling */
    .avatar-upload-container {
        position: relative;
        width: 130px;
        height: 130px;
        margin: -65px auto 15px auto;
        z-index: 2;
    }
    
    .avatar-ring {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        padding: 4px;
        background: #fff;
        box-shadow: 0 8px 20px rgba(10, 66, 130, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }
    
    .avatar-upload-container:hover .avatar-ring {
        transform: scale(1.03);
    }
    
    .avatar-inner {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        background-color: #fff;
        border: 3px solid var(--bs-primary-soft);
    }
    
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-btn {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: var(--bs-primary);
        border: 3px solid #fff;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        transition: all 0.2s ease;
        z-index: 3;
    }
    
    .avatar-btn:hover {
        background-color: var(--bs-info);
        color: #0b192c;
        transform: scale(1.1);
    }

    /* Form Design System */
    .form-label-modern {
        font-weight: 600;
        font-size: 0.8rem;
        color: #475569; /* Slate-600 label */
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control-modern {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1px solid #94a3b8; /* Sharp visible border */
        font-size: 0.95rem;
        color: #1e293b; /* Slate-800 for high readability */
        transition: all 0.2s ease-in-out;
        background-color: #fff;
    }

    .form-control-modern:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(10, 66, 130, 0.15);
        outline: none;
    }

    /* Modern Icon Input Groups */
    .input-group-modern {
        display: flex;
        align-items: stretch;
        width: 100%;
    }

    .input-group-modern .input-group-text {
        background-color: #fff;
        border: 1px solid #94a3b8; /* Sharp border */
        border-right: none;
        color: #64748b; /* Slate-500 */
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        padding-left: 1.1rem;
        padding-right: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .input-group-modern .form-control-modern {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-left: none;
        flex: 1 1 auto;
        width: 1%;
    }

    /* Locked Input Group styling (Distinct Gray contrasting with editable white) */
    .input-group-modern.locked .input-group-text {
        background-color: #f1f5f9; /* Slate-100 distinct light grey */
        border-color: #cbd5e1; /* Slate-300 */
        color: #64748b; /* Slate-500 */
    }

    .input-group-modern.locked .form-control-modern {
        background-color: #f1f5f9; /* Slate-100 distinct light grey */
        border-color: #cbd5e1; /* Slate-300 */
        color: #475569; /* Slate-600 for clear legibility */
        cursor: not-allowed;
    }

    /* High Contrast Helper Text */
    .form-text-contrast {
        font-size: 0.85rem !important;
        color: #475569 !important; /* Slate-600 for higher contrast */
        font-weight: 500;
        margin-top: 0.5rem;
        display: block;
    }

    /* Keyframes and Animations */
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 animate-fade-in">
    
    <!-- 3. Tombol Kembali ke Landing Page -->
    <div class="mb-4">
        <a href="{{ route('home') }}" class="btn-back-landing">
            <i class="bi bi-arrow-left"></i> Kembali ke Landing Page
        </a>
    </div>

    <!-- Success Alert (Modernized) -->
    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        <div class="alert alert-success d-flex align-items-center gap-3 border-0 shadow-sm rounded-4 mb-4 p-3 animate-fade-in" role="alert">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px; flex-shrink: 0;">
                <i class="bi bi-check-lg fs-5 fw-bold"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold text-success-emphasis">
                    {{ session('status') === 'profile-updated' ? 'Profil Berhasil Diperbarui' : 'Password Berhasil Diubah' }}
                </h6>
                <span class="small text-success-emphasis opacity-75">
                    {{ session('status') === 'profile-updated' ? 'Perubahan informasi profil Anda telah berhasil disimpan.' : 'Password akun Anda telah berhasil diperbarui.' }}
                </span>
            </div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ROW 1: Centered Profile Avatar & Combined Account Form (Equal Heights on Desktop) -->
    <div class="row align-items-stretch">
        <!-- LEFT COLUMN: Profile Cover & Quick Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4 bg-white h-100 d-flex flex-column justify-content-between profile-card">
                <div>
                    <!-- SaaS Banner/Cover -->
                    <div class="profile-cover"></div>
                    
                    <!-- Avatar Upload Interface -->
                    <div class="avatar-upload-container">
                        <div class="avatar-ring">
                            <div class="avatar-inner">
                                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : '' }}" 
                                     id="avatarPreview" 
                                     class="avatar-img {{ $user->profile_photo ? '' : 'd-none' }}" 
                                     alt="Foto Profil">
                                
                                <div id="avatarFallback" 
                                     class="w-100 h-100 bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-1 {{ $user->profile_photo ? 'd-none' : '' }}">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Camera overlay trigger -->
                        <div class="avatar-btn" onclick="document.getElementById('profile_photo').click()" title="Ganti Foto Profil">
                            <i class="bi bi-camera-fill fs-6"></i>
                        </div>
                    </div>

                    <!-- User Name (Capitalized as Raqie), Email & Role Badge -->
                    <h5 class="fw-bold text-dark mb-1 text-truncate px-2">{{ ucwords($user->name) }}</h5>
                    <p class="text-muted small mb-3 text-truncate px-2">{{ $user->email }}</p>
                    
                    <div class="mb-3 d-flex flex-column align-items-center gap-2">
                        <span class="badge rounded-pill bg-primary-soft text-primary px-3 py-2 fw-semibold text-capitalize shadow-sm">
                            <i class="bi bi-shield-fill-check me-1"></i>{{ $user->roles->first()->name ?? 'User' }}
                        </span>
                        
                        <!-- Interactive Button to trigger photo select -->
                        <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 mt-1 px-3 py-1.5" onclick="document.getElementById('profile_photo').click()">
                            <i class="bi bi-upload"></i> Ganti Foto Profil
                        </button>
                    </div>

                    <!-- Selected Photo Status Alert -->
                    <div id="photoUnsavedAlert" class="alert alert-warning border-0 p-2 mt-3 mb-0 small rounded-3 d-none align-items-center justify-content-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                        <span>Foto baru terpilih. Simpan profil untuk menerapkan.</span>
                    </div>
                </div>

                <!-- 6. Informasi Tambahan Akun -->
                <div class="mt-4 pt-3 border-top text-start">
                    <div class="d-flex justify-content-between mb-2 small align-items-center">
                        <span class="text-muted">Status Akun</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded">Akun Aktif</span>
                    </div>
                    <div class="d-flex justify-content-between mb-0 small align-items-center">
                        <span class="text-muted">Bergabung Sejak</span>
                        <span class="text-dark fw-semibold">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                    </div>
                    
                    <button type="button" id="btnResetPhoto" class="btn btn-sm btn-outline-danger w-100 mt-3 d-none" onclick="resetPhotoSelection()">
                        <i class="bi bi-x-circle me-1"></i> Batalkan Ubah Foto
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Account & Organization Details Form (Matches Height of Center Card) -->
        <div class="col-lg-8 mb-4">
            
            <!-- CARD 1: Profile Information Fields -->
            <div class="card border-0 shadow-sm p-4 bg-white h-100">
                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm" class="h-100 d-flex flex-column justify-content-between">
                    @csrf
                    @method('patch')

                    <!-- Hidden original file input -->
                    <input class="d-none" type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/jpg">

                    <div>
                        <!-- Group 1: Informasi Akun -->
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="bg-primary-soft text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="bi bi-person-fill fs-5"></i>
                            </div>
                            <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.05rem;">Informasi Akun</h5>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label for="name" class="form-label form-label-modern">Nama Lengkap</label>
                                <div class="input-group-modern">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control form-control-modern @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', ucwords($user->name)) }}" required placeholder="Masukkan nama lengkap Anda">
                                </div>
                                @error('name')
                                    <div class="invalid-feedback mt-1 d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="email" class="form-label form-label-modern">Alamat Email</label>
                                <div class="input-group-modern locked">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="email" class="form-control form-control-modern" id="email" value="{{ $user->email }}" disabled>
                                </div>
                                <div class="form-text-contrast mt-2">
                                    <i class="bi bi-info-circle me-1"></i>Email hanya sebagai informasi dan tidak dapat diubah oleh pengguna.
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="phone_number" class="form-label form-label-modern">Nomor Telepon</label>
                                <div class="input-group-modern">
                                    <span class="input-group-text">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="text" class="form-control form-control-modern @error('phone_number') is-invalid @enderror" 
                                           id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" 
                                           placeholder="Masukkan nomor telepon aktif Anda">
                                </div>
                                @error('phone_number')
                                    <div class="invalid-feedback mt-1 d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        <!-- 7. Informasi Organisasi (Modern Cards Display instead of grey inputs) -->
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="bg-primary-soft text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="bi bi-building fs-5"></i>
                            </div>
                            <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.05rem;">Informasi Organisasi</h5>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 border bg-light d-flex align-items-center gap-3">
                                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center border shadow-sm" style="width: 42px; height: 42px; flex-shrink: 0;">
                                        <i class="bi bi-diagram-3-fill fs-5"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <span class="text-muted small d-block">Unit Kerja</span>
                                        <span class="fw-bold text-dark fs-6 text-truncate d-block">{{ $user->unit_kerja ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded-3 border bg-light d-flex align-items-center gap-3">
                                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center border shadow-sm" style="width: 42px; height: 42px; flex-shrink: 0;">
                                        <i class="bi bi-shield-lock-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Hak Akses / Role</span>
                                        <span class="badge bg-primary-soft text-primary px-2.5 py-1.5 fw-semibold text-capitalize mt-1 shadow-sm">
                                            {{ $user->roles->first()->name ?? 'User' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button Profile Info (Positioned bottom right with margin and spacing, not cut off) -->
                    <div class="d-flex justify-content-end border-top pt-4 mt-3">
                        <button type="submit" id="btnSaveProfile" class="btn btn-primary px-4 py-2.5 rounded-3 d-flex align-items-center gap-2">
                            <i class="bi bi-floppy-fill"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ROW 2: Keamanan / Ganti Password Card -->
    <div class="row mt-4">
        <div class="col-lg-8 offset-lg-4 mb-4">
            <!-- CARD 2: Security & Password Update -->
            <div class="card border-0 shadow-sm p-4 bg-white">
                <form method="post" action="{{ route('profile.password') }}" id="passwordForm">
                    @csrf
                    @method('put')

                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div class="bg-primary-soft text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-shield-lock-fill fs-5"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.05rem;">Ubah Password Keamanan</h5>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="current_password" class="form-label form-label-modern">Password Saat Ini</label>
                            <div class="input-group-modern">
                                <span class="input-group-text">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <input type="password" class="form-control form-control-modern @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required placeholder="Masukkan password saat ini">
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback mt-1 d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="new_password" class="form-label form-label-modern">Password Baru</label>
                            <div class="input-group-modern">
                                <span class="input-group-text">
                                    <i class="bi bi-key-fill"></i>
                                </span>
                                <input type="password" class="form-control form-control-modern @error('password') is-invalid @enderror" 
                                       id="new_password" name="password" required placeholder="Masukkan password baru (min. 8 karakter)">
                            </div>
                            <div class="form-text-contrast mt-2">
                                <i class="bi bi-info-circle me-1"></i>Password harus mengandung minimal 8 karakter, huruf besar, huruf kecil, dan angka.
                            </div>
                            @error('password')
                                <div class="invalid-feedback mt-1 d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-2">
                            <label for="password_confirmation" class="form-label form-label-modern">Konfirmasi Password Baru</label>
                            <div class="input-group-modern">
                                <span class="input-group-text">
                                    <i class="bi bi-key-fill"></i>
                                </span>
                                <input type="password" class="form-control form-control-modern" 
                                       id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password baru Anda">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button Password -->
                    <div class="d-flex justify-content-end border-top pt-3 mt-3">
                        <button type="submit" id="btnSavePassword" class="btn btn-dark px-4 py-2.5 rounded-3 d-flex align-items-center gap-2">
                            <i class="bi bi-key-fill"></i> Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Layout bottom spacing to prevent taskbar overlap -->
    <div style="height: 60px;"></div>
</div>
@endsection

@push('scripts')
<script>
    // Variables for photo handling
    const originalPhotoUrl = @json($user->profile_photo ? asset('storage/' . $user->profile_photo) : null);
    const originalInitials = "{{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}";
    
    // Listen for file changes
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Check file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file foto maksimal 2 MB!');
                this.value = '';
                return;
            }
            
            // Check file extension/type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file foto tidak didukung! Pilih foto berformat JPG, JPEG, atau PNG.');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(event) {
                const avatarPreview = document.getElementById('avatarPreview');
                const avatarFallback = document.getElementById('avatarFallback');
                const btnReset = document.getElementById('btnResetPhoto');
                const infoAlert = document.getElementById('photoUnsavedAlert');
                
                avatarPreview.src = event.target.result;
                avatarPreview.classList.remove('d-none');
                
                if (avatarFallback) {
                    avatarFallback.classList.add('d-none');
                }
                
                btnReset.classList.remove('d-none');
                if (infoAlert) {
                    infoAlert.classList.remove('d-none');
                    infoAlert.classList.add('d-flex');
                }
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Reset photo selection
    function resetPhotoSelection() {
        const fileInput = document.getElementById('profile_photo');
        fileInput.value = ''; // clear input selection
        
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarFallback = document.getElementById('avatarFallback');
        const btnReset = document.getElementById('btnResetPhoto');
        const infoAlert = document.getElementById('photoUnsavedAlert');
        
        if (originalPhotoUrl) {
            avatarPreview.src = originalPhotoUrl;
            avatarPreview.classList.remove('d-none');
            avatarFallback.classList.add('d-none');
        } else {
            avatarPreview.classList.add('d-none');
            avatarFallback.classList.remove('d-none');
        }
        
        btnReset.classList.add('d-none');
        if (infoAlert) {
            infoAlert.classList.remove('d-flex');
            infoAlert.classList.add('d-none');
        }
    }
    
    // Add form submit loading animations
    function handleFormLoading(formId, buttonId, loadingText) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(e) {
                // If standard validation checks pass
                if (form.checkValidity()) {
                    const btn = document.getElementById(buttonId);
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ${loadingText}`;
                    }
                }
            });
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        handleFormLoading('profileForm', 'btnSaveProfile', 'Menyimpan...');
        handleFormLoading('passwordForm', 'btnSavePassword', 'Memperbarui...');
    });
</script>
@endpush

@extends('layouts.landing')

@section('title', 'Buat Laporan - WBS BBSPJIKKP')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@section('content')
<div class="laporan-page py-4">
<div class="container">

    {{-- Alert Errors --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3 rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Terdapat kesalahan pada formulir:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        {{-- ── KIRI: Wizard Form ── --}}
        <div class="col-lg-8">

            {{-- Step Indicator --}}
            <div class="wizard-card mb-3">
                <div class="wizard-steps">
                    <div class="wizard-step-item active" id="step-indicator-1">
                        <div class="wizard-step-circle" id="step-circle-1">1</div>
                        <span class="wizard-step-label">Kategori</span>
                    </div>
                    <div class="wizard-step-item" id="step-indicator-2">
                        <div class="wizard-step-circle" id="step-circle-2">2</div>
                        <span class="wizard-step-label">Deskripsi</span>
                    </div>
                    <div class="wizard-step-item" id="step-indicator-3">
                        <div class="wizard-step-circle" id="step-circle-3">3</div>
                        <span class="wizard-step-label">Bukti</span>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form id="laporanForm" method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- ════ STEP 1: KATEGORI ════ --}}
                <div class="laporan-panel step-panel active fade-in" id="step-1">
                    <div class="laporan-panel-body">
                        <h2>Pilih Kategori</h2>
                        <p class="subtitle">Pilih jenis utama pelanggaran yang ingin Anda laporkan.</p>

                        <div class="category-grid mt-3">
                            @foreach($kategoris as $kat)
                            <label class="category-card-label">
                                <input type="radio" name="kategori_id" value="{{ $kat->id }}"
                                    {{ old('kategori_id') == $kat->id ? 'checked' : '' }}>
                                <div class="category-card">
                                    <div class="category-card-check"><i class="bi bi-check-lg"></i></div>
                                    <div class="category-icon" style="background: {{ $kat->warna }};">
                                        <i class="{{ $kat->icon }}"></i>
                                    </div>
                                    <h5>{{ $kat->nama }}</h5>
                                    <p>{{ $kat->deskripsi }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        @error('kategori_id')
                        <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn-laporan-primary" onclick="goToStep(2)">
                                Lanjutkan <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ════ STEP 2: DESKRIPSI ════ --}}
                <div class="laporan-panel step-panel" id="step-2">
                    <div class="laporan-panel-body">
                        <h2>Detail Laporan</h2>
                        <p class="subtitle">Jelaskan kejadian yang Anda saksikan secara rinci.</p>

                        <div class="row g-3 mt-1">
                            {{-- Judul --}}
                            <div class="col-12">
                                <label class="form-label">Judul Laporan <span class="required-star">*</span></label>
                                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                    placeholder="Ringkasan singkat pelanggaran yang dilaporkan"
                                    value="{{ old('judul') }}">
                                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-12">
                                <label class="form-label">Deskripsi Kejadian <span class="required-star">*</span></label>
                                <textarea name="deskripsi" rows="5"
                                    class="form-control @error('deskripsi') is-invalid @enderror"
                                    placeholder="Ceritakan secara detail: apa yang terjadi, kapan, di mana, siapa yang terlibat, dan bagaimana kejadiannya. (minimal 50 karakter)">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Tanggal & Lokasi --}}
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kejadian</label>
                                <input type="date" name="tanggal_kejadian"
                                    class="form-control @error('tanggal_kejadian') is-invalid @enderror"
                                    value="{{ old('tanggal_kejadian') }}"
                                    max="{{ date('Y-m-d') }}">
                                @error('tanggal_kejadian')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lokasi Kejadian</label>
                                <input type="text" name="lokasi" class="form-control"
                                    placeholder="Gedung / Unit / Lokasi"
                                    value="{{ old('lokasi') }}">
                            </div>

                            {{-- Divider: Data Terlapor --}}
                            <div class="col-12 mt-2">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-semibold text-dark" style="font-size:.9rem;">Data Terlapor</span>
                                    <small class="text-muted">(opsional)</small>
                                </div>
                                <hr class="mt-1 mb-3">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Nama Terlapor</label>
                                <input type="text" name="nama_terlapor" class="form-control"
                                    placeholder="Nama lengkap" value="{{ old('nama_terlapor') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jabatan</label>
                                <input type="text" name="jabatan_terlapor" class="form-control"
                                    placeholder="Jabatan" value="{{ old('jabatan_terlapor') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Unit / Bagian</label>
                                <input type="text" name="unit_terlapor" class="form-control"
                                    placeholder="Unit kerja" value="{{ old('unit_terlapor') }}">
                            </div>

                            {{-- Anonim Toggle --}}
                            <div class="col-12 mt-3">
                                <div class="anonim-card">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="isAnonim"
                                            name="is_anonim" value="1"
                                            {{ old('is_anonim', false) ? 'checked' : '' }}
                                            onchange="toggleAnonimFields(this)">
                                        <label class="form-check-label ms-2" for="isAnonim">
                                            <i class="bi bi-incognito me-1"></i> Lapor Secara Anonim
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1 ms-4 ps-2">
                                        Identitas Anda akan disembunyikan dan tidak diketahui siapapun.
                                    </small>
                                </div>
                            </div>

                            {{-- Identitas Pelapor (muncul jika tidak anonim) --}}
                            <div class="col-12" id="identitasPelapor" style="{{ old('is_anonim', false) ? 'display:none' : '' }}">
                                <div class="row g-3">
                                    <div class="col-12 mt-1">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="fw-semibold text-dark" style="font-size:.9rem;">Identitas Pelapor</span>
                                        </div>
                                        <hr class="mt-1 mb-3">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nama Lengkap <span class="required-star">*</span></label>
                                        <input type="text" name="nama_pelapor" class="form-control @error('nama_pelapor') is-invalid @enderror"
                                            placeholder="Nama lengkap Anda" value="{{ old('nama_pelapor', auth()->user()->name) }}">
                                        @error('nama_pelapor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Email <span class="required-star">*</span></label>
                                        <input type="email" name="email_pelapor" class="form-control @error('email_pelapor') is-invalid @enderror"
                                            placeholder="email@contoh.com" value="{{ old('email_pelapor', auth()->user()->email) }}">
                                        @error('email_pelapor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">No. Telepon</label>
                                        <input type="text" name="telepon_pelapor" class="form-control"
                                            placeholder="08xx-xxxx-xxxx" value="{{ old('telepon_pelapor', auth()->user()->phone_number) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn-laporan-secondary" onclick="goToStep(1)">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </button>
                            <button type="button" class="btn-laporan-primary" onclick="goToStep(3)">
                                Lanjutkan <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ════ STEP 3: BUKTI ════ --}}
                <div class="laporan-panel step-panel" id="step-3">
                    <div class="laporan-panel-body">
                        <h2>Upload Bukti</h2>
                        <p class="subtitle">Lampirkan dokumen, foto, atau file pendukung laporan Anda. (Opsional, maks. 10 file @ 10 MB)</p>

                        <div class="dropzone-area mt-3" id="dropzone">
                            <input type="file" name="bukti[]" id="buktiInput" multiple
                                accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.mp4,.zip">
                            <div class="dropzone-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div class="dropzone-title">Klik atau seret file ke sini</div>
                            <div class="dropzone-subtitle">JPG, PNG, PDF, DOC, XLS, MP4, ZIP — Maks 10 MB per file</div>
                        </div>

                        <div class="file-preview-list" id="filePreviewList"></div>

                        @error('bukti.*')
                        <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror

                        {{-- Review Summary --}}
                        <div class="mt-4 p-3 rounded-3" style="background:#f8fafc; border:1.5px solid #e2e8f0;">
                            <div class="fw-semibold mb-2" style="font-size:.9rem; color:#1e293b;">
                                <i class="bi bi-clipboard-check me-1 text-primary"></i> Ringkasan Laporan
                            </div>
                            <div style="font-size:.85rem; color:#475569; line-height:1.8;">
                                <div><strong>Kategori:</strong> <span id="summaryKategori">—</span></div>
                                <div><strong>Judul:</strong> <span id="summaryJudul">—</span></div>
                                <div><strong>Identitas:</strong> <span id="summaryAnonim">Anonim</span></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn-laporan-secondary" onclick="goToStep(2)">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </button>
                            <button type="submit" class="btn-laporan-submit" id="submitBtn">
                                <i class="bi bi-send-fill"></i> Kirim Laporan
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        {{-- ── KANAN: Sidebar ── --}}
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-3 sticky-top" style="top: 80px;">

                {{-- Anonymity Guaranteed --}}
                <div class="sidebar-card">
                    <div class="sidebar-card-body">
                        <div class="anonymity-icon"><i class="bi bi-shield-lock-fill"></i></div>
                        <h6 class="fw-bold mb-2" style="color:#1e293b;">Anonymity Guaranteed</h6>
                        <p class="text-muted" style="font-size:.875rem; line-height:1.6;">
                            Identitas Anda dienkripsi dan tersembunyi secara default. Kami tidak melacak alamat IP atau metadata yang dapat menghubungkan Anda ke laporan ini.
                        </p>
                        <div class="anonymity-check-item">
                            <i class="bi bi-check-circle-fill"></i> AES-256 Encryption
                        </div>
                        <div class="anonymity-check-item">
                            <i class="bi bi-check-circle-fill"></i> No IP Logging
                        </div>
                        <div class="anonymity-check-item">
                            <i class="bi bi-check-circle-fill"></i> CSRF Protected
                        </div>
                    </div>
                </div>

                {{-- Privacy Notice --}}
                <div class="sidebar-card">
                    <div class="sidebar-card-body">
                        <h6 class="fw-bold mb-3" style="color:#1e293b;">Privacy Notice</h6>
                        <div class="privacy-quote">
                            "Integritas adalah inti dari tata kelola kami. Seluruh laporan diproses sesuai SOP Pengelolaan Whistleblowing System BBSPJIKKP."
                        </div>
                        <a href="#" class="text-primary text-decoration-none small fw-medium d-block mb-1">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Baca Kebijakan Privasi
                        </a>
                        <a href="#" class="text-primary text-decoration-none small fw-medium">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Panduan Etika
                        </a>
                    </div>
                </div>

                {{-- Support Line --}}
                <div class="support-banner">
                    <div class="support-banner-bg"></div>
                    <div class="support-label">Need Help?</div>
                    <div class="support-title">Confidential Support Line</div>
                    <div class="support-number">(0274) XXX-XXXX — BBSPJIKKP</div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
// ── State ──────────────────────────────────────────────────
let currentStep = 1;
let uploadedFiles = [];

// ── Step Navigation ────────────────────────────────────────
function goToStep(step) {
    if (step === 2 && currentStep === 1) {
        const selected = document.querySelector('input[name="kategori_id"]:checked');
        if (!selected) {
            showStepError('Silakan pilih kategori pelanggaran terlebih dahulu.');
            return;
        }
    }
    if (step === 3 && currentStep === 2) {
        const judul = document.querySelector('[name="judul"]').value.trim();
        const desk  = document.querySelector('[name="deskripsi"]').value.trim();
        if (!judul) { showStepError('Judul laporan wajib diisi.'); return; }
        if (desk.length < 50) { showStepError('Deskripsi minimal 50 karakter.'); return; }
        updateSummary();
    }

    // Hide semua panels & update indicator
    document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.wizard-step-item').forEach((el, i) => {
        el.classList.remove('active', 'completed');
        if (i + 1 < step) el.classList.add('completed');
        if (i + 1 === step) el.classList.add('active');
    });
    document.querySelectorAll('.wizard-step-circle').forEach((el, i) => {
        if (i + 1 < step) el.innerHTML = '<i class="bi bi-check-lg"></i>';
        else el.innerHTML = i + 1;
    });

    const panel = document.getElementById('step-' + step);
    panel.classList.add('active', 'fade-in');
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showStepError(msg) {
    let el = document.getElementById('stepError');
    if (!el) {
        el = document.createElement('div');
        el.id = 'stepError';
        el.className = 'alert alert-warning alert-dismissible fade show mt-3';
        el.innerHTML = `<i class="bi bi-exclamation-triangle me-2"></i><span></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        document.querySelector('#step-' + currentStep + ' .laporan-panel-body').appendChild(el);
    }
    el.querySelector('span').textContent = msg;
    el.style.display = 'flex';
    setTimeout(() => { if (el) el.remove(); }, 4000);
}

// ── Anonim Toggle ──────────────────────────────────────────
function toggleAnonimFields(checkbox) {
    const identitas = document.getElementById('identitasPelapor');
    identitas.style.display = checkbox.checked ? 'none' : 'block';
}

// ── Summary Update ─────────────────────────────────────────
function updateSummary() {
    const katEl = document.querySelector('input[name="kategori_id"]:checked');
    if (katEl) {
        const label = katEl.closest('.category-card-label').querySelector('h5').textContent;
        document.getElementById('summaryKategori').textContent = label;
    }
    document.getElementById('summaryJudul').textContent =
        document.querySelector('[name="judul"]').value || '—';
    const isAnonim = document.getElementById('isAnonim').checked;
    document.getElementById('summaryAnonim').textContent = isAnonim ? 'Anonim' : 'Tidak Anonim';
}

// ── File Upload Preview ────────────────────────────────────
const fileInput = document.getElementById('buktiInput');
const dropzone  = document.getElementById('dropzone');

fileInput.addEventListener('change', handleFiles);

dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('dragover'); });
dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.classList.remove('dragover');
    addFiles(Array.from(e.dataTransfer.files));
});

function handleFiles(e) { addFiles(Array.from(e.target.files)); }

function addFiles(newFiles) {
    newFiles.forEach(f => {
        if (uploadedFiles.length >= 10) return;
        if (!uploadedFiles.find(x => x.name === f.name && x.size === f.size)) {
            uploadedFiles.push(f);
        }
    });
    rebuildFileInput();
    renderFilePreview();
}

function removeFile(idx) {
    uploadedFiles.splice(idx, 1);
    rebuildFileInput();
    renderFilePreview();
}

function rebuildFileInput() {
    const dt = new DataTransfer();
    uploadedFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
}

function renderFilePreview() {
    const list = document.getElementById('filePreviewList');
    list.innerHTML = '';
    uploadedFiles.forEach((f, i) => {
        const icon = getFileIcon(f.name);
        const size = f.size > 1048576
            ? (f.size/1048576).toFixed(1)+' MB'
            : (f.size/1024).toFixed(0)+' KB';
        const item = document.createElement('div');
        item.className = 'file-preview-item';
        item.innerHTML = `
            <div class="file-preview-icon"><i class="${icon}"></i></div>
            <span class="file-preview-name">${f.name}</span>
            <span class="file-preview-size">${size}</span>
            <button type="button" class="file-preview-remove" onclick="removeFile(${i})" title="Hapus">
                <i class="bi bi-x-lg"></i>
            </button>`;
        list.appendChild(item);
    });
}

function getFileIcon(name) {
    const ext = name.split('.').pop().toLowerCase();
    const map = {
        pdf: 'bi-file-pdf text-danger',
        doc: 'bi-file-word text-primary', docx: 'bi-file-word text-primary',
        xls: 'bi-file-excel text-success', xlsx: 'bi-file-excel text-success',
        mp4: 'bi-film text-warning',
        zip: 'bi-file-zip text-secondary',
        jpg: 'bi-file-image text-info', jpeg: 'bi-file-image text-info',
        png: 'bi-file-image text-info', gif: 'bi-file-image text-info',
    };
    return map[ext] || 'bi-file-earmark text-muted';
}

// ── Submit Handler ─────────────────────────────────────────
document.getElementById('laporanForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';
});

// ── Auto-goto step on validation error ────────────────────
@if($errors->has('kategori_id'))
    goToStep(1);
@elseif($errors->hasAny(['judul','deskripsi','tanggal_kejadian','nama_pelapor','email_pelapor']))
    goToStep(2);
@elseif($errors->has('bukti') || $errors->has('bukti.*'))
    goToStep(3);
@endif
</script>
@endpush

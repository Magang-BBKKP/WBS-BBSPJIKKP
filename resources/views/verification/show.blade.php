@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb & Back Link -->
    <div class="mb-3">
        <a href="{{ route('verifikasi.index') }}" class="text-decoration-none text-muted small fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Antrean
        </a>
    </div>

    <!-- Header Card -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill small fw-semibold mb-2">
                    {{ $laporan->kategori->nama ?? 'Lainnya' }}
                </span>
                <h4 class="fw-bold text-dark mb-1">{{ $laporan->judul }}</h4>
                <p class="text-muted small mb-0">Nomor Registrasi: <span class="fw-bold text-primary">#{{ $laporan->nomor_registrasi }}</span> • Diajukan pada {{ $laporan->created_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-{{ $laporan->status_color }} px-3 py-2 rounded-pill fw-semibold text-capitalize" style="font-size: 0.85rem;">
                    {{ $laporan->status_label }}
                </span>
            </div>
        </div>
    </div>

    <!-- Alert Validation Errors -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Report Details -->
        <div class="col-lg-8">
            <!-- Details Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="bi bi-info-circle-fill text-primary me-2"></i>Informasi Laporan</h5>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold d-block mb-1">Lokasi Kejadian</label>
                        <span class="text-dark fw-medium">{{ $laporan->lokasi ?? '-' }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold d-block mb-1">Waktu Kejadian</label>
                        <span class="text-dark fw-medium">{{ $laporan->tanggal_kejadian ? $laporan->tanggal_kejadian->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-muted small fw-semibold d-block mb-2">Kronologi / Uraian Kejadian</label>
                    <div class="p-3 bg-light rounded-3 text-dark text-break" style="white-space: pre-line; line-height: 1.6; font-size: 0.95rem;">
                        {{ $laporan->deskripsi }}
                    </div>
                </div>
            </div>

            <!-- Terlapor Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="bi bi-person-badge-fill text-primary me-2"></i>Data Terlapor</h5>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block mb-1">Nama Terlapor</label>
                        <span class="text-dark fw-bold">{{ $laporan->nama_terlapor ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block mb-1">Jabatan</label>
                        <span class="text-dark fw-medium">{{ $laporan->jabatan_terlapor ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block mb-1">Unit Kerja</label>
                        <span class="text-dark fw-medium">{{ $laporan->unit_terlapor ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Pelapor Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="bi bi-person-fill text-primary me-2"></i>Identitas Pelapor</h5>
                
                @if($laporan->is_anonim)
                    <div class="alert alert-secondary border-0 d-flex align-items-center mb-0" role="alert">
                        <i class="bi bi-eye-slash-fill me-3 fs-4 text-muted"></i>
                        <div>
                            <h6 class="alert-heading fw-bold mb-1">Pelapor Anonim</h6>
                            <p class="mb-0 small text-muted">Pelapor memilih untuk menyembunyikan identitas pribadinya. Seluruh data pelapor disanitasi secara otomatis oleh sistem demi menjaga kerahasiaan.</p>
                        </div>
                    </div>
                @else
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block mb-1">Nama Lengkap</label>
                            <span class="text-dark fw-bold">{{ $laporan->nama_pelapor ?? '-' }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block mb-1">Email</label>
                            <span class="text-dark fw-medium">{{ $laporan->email_pelapor ?? '-' }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block mb-1">Nomor Telepon</label>
                            <span class="text-dark fw-medium">{{ $laporan->telepon_pelapor ?? '-' }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Evidence / Bukti Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="bi bi-paperclip text-primary me-2"></i>Lampiran Bukti</h5>
                
                @if($laporan->buktis->count() > 0)
                    <div class="row g-3">
                        @foreach($laporan->buktis as $bukti)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 bg-light-subtle">
                                    <div class="d-flex align-items-center gap-3 overflow-hidden">
                                        @php
                                            $ext = strtolower(pathinfo($bukti->nama_asli, PATHINFO_EXTENSION));
                                            $icon = 'file-earmark-text';
                                            $color = '#6c757d';
                                            if(in_array($ext, ['pdf'])) { $icon = 'file-earmark-pdf'; $color = '#dc3545'; }
                                            elseif(in_array($ext, ['jpg','jpeg','png'])) { $icon = 'file-earmark-image'; $color = '#0d6efd'; }
                                            elseif(in_array($ext, ['doc','docx'])) { $icon = 'file-earmark-word'; $color = '#0dcaf0'; }
                                        @endphp
                                        <i class="bi bi-{{ $icon }} fs-3" style="color: {{ $color }};"></i>
                                        <div class="overflow-hidden">
                                            <div class="fw-semibold text-dark text-truncate small" title="{{ $bukti->nama_asli }}">{{ $bukti->nama_asli }}</div>
                                            <small class="text-muted" style="font-size: 0.75rem;">{{ number_format($bukti->ukuran / 1024, 2) }} KB</small>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($bukti->path_file) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" title="Unduh File">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small py-2">
                        <i class="bi bi-info-circle me-1"></i> Tidak ada file bukti tambahan yang diunggah.
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Verification Actions & Timeline -->
        <div class="col-lg-4">
            
            <!-- Actions Card (only active if report is not final) -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white border-top border-primary border-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-primary me-2"></i>Aksi Verifikasi</h5>
                <p class="text-muted small mb-4">Lakukan evaluasi awal terhadap isi laporan dan kelengkapan bukti sebelum mengambil tindakan berikut.</p>
                
                @if($laporan->status === \App\Models\Laporan::STATUS_SELESAI)
                    <div class="alert alert-success border-0 small mb-0" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> Laporan telah diselesaikan dan ditutup. Status tidak dapat diubah lagi.
                    </div>
                @elseif($laporan->status === \App\Models\Laporan::STATUS_VALID || $laporan->verification_status === 'verified')
                    <div class="alert alert-info border-0 small mb-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> Laporan telah diverifikasi sebagai <strong>Valid</strong>.
                    </div>
                    <!-- Disable actions but keep Reject option if super policy allows (requirement validation prevents other edits) -->
                    <div class="d-flex flex-column gap-2">
                        <button class="btn btn-danger w-100 rounded-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalReject">
                            Tolak Laporan
                        </button>
                    </div>
                @elseif($laporan->status === \App\Models\Laporan::STATUS_DITOLAK || $laporan->verification_status === 'rejected')
                    <div class="alert alert-danger border-0 small mb-0" role="alert">
                        <i class="bi bi-x-circle-fill me-2"></i> Laporan telah <strong>Ditolak</strong>. Status tidak dapat ditindaklanjuti.
                    </div>
                @else
                    <div class="d-flex flex-column gap-2">
                        <!-- Validate Button -->
                        <button class="btn btn-success w-100 rounded-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalValidate">
                            <i class="bi bi-check-lg me-1"></i> Validasi Laporan
                        </button>
                        
                        <!-- Clarify Button -->
                        <button class="btn btn-warning w-100 rounded-3 py-2 fw-semibold text-dark" data-bs-toggle="modal" data-bs-target="#modalClarify">
                            <i class="bi bi-chat-dots me-1"></i> Minta Klarifikasi
                        </button>
                        
                        <!-- Reject Button -->
                        <button class="btn btn-danger w-100 rounded-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalReject">
                            <i class="bi bi-x-circle me-1"></i> Tolak Laporan
                        </button>
                    </div>
                @endif
            </div>

            <!-- Timeline Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Aktivitas</h5>
                
                @if($laporan->timelines->count() > 0)
                    <div class="position-relative ps-3" style="border-left: 2px solid var(--bs-primary-soft);">
                        @foreach($laporan->timelines->sortByDesc('created_at') as $timeline)
                            <div class="position-relative mb-4">
                                <!-- Indicator Dot -->
                                <div class="position-absolute rounded-circle bg-primary" style="width: 12px; height: 12px; left: -20px; top: 4px; border: 2px solid white;"></div>
                                
                                <div class="ps-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="fw-bold text-dark mb-0 small">{{ $timeline->title }}</h6>
                                        <span class="text-muted" style="font-size: 0.7rem;">{{ $timeline->created_at->format('d M H:i') }}</span>
                                    </div>
                                    <p class="text-muted small mb-0 mt-1" style="font-size: 0.8rem; line-height: 1.4;">{{ $timeline->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <span class="text-muted small">Tidak ada riwayat aktivitas.</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: VALIDASI -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalValidate" tabindex="-1" aria-labelledby="modalValidateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 bg-success text-white py-3 rounded-top-4">
                <h5 class="modal-title fw-bold" id="modalValidateLabel"><i class="bi bi-check-circle-fill me-2"></i>Validasi Laporan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('verifikasi.validate', $laporan->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small">Memvalidasi laporan akan mengubah status menjadi **Terverifikasi**, mencatat tanggal verifikasi dan verifikator Anda. Laporan siap diteruskan ke tahap investigasi oleh tim terkait.</p>
                    
                    <div class="mb-3">
                        <label for="verification_note" class="form-label small fw-bold text-dark">Catatan Verifikasi (Opsional)</label>
                        <textarea class="form-control rounded-3" id="verification_note" name="verification_note" rows="4" placeholder="Masukkan catatan hasil pemeriksaan kelayakan/bukti laporan di sini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-toggle="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 fw-semibold">Validasi Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: MINTA KLARIFIKASI -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalClarify" tabindex="-1" aria-labelledby="modalClarifyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 bg-warning text-dark py-3 rounded-top-4">
                <h5 class="modal-title fw-bold" id="modalClarifyLabel"><i class="bi bi-chat-dots-fill me-2"></i>Minta Klarifikasi Pelapor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('verifikasi.clarify', $laporan->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small">Mengirim permintaan klarifikasi akan mengubah status menjadi **Menunggu Klarifikasi**. Pelapor dapat membaca dan menjawab pesan Anda melalui portal pelacakan mereka.</p>
                    
                    <div class="mb-3">
                        <label for="clarification_message" class="form-label small fw-bold text-dark">Pesan Klarifikasi (Wajib)</label>
                        <textarea class="form-control rounded-3" id="clarification_message" name="clarification_message" rows="4" placeholder="Sebutkan secara rinci detail informasi atau dokumen bukti tambahan yang Anda butuhkan dari pelapor..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-toggle="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-3 px-4 fw-semibold text-dark">Kirim Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: PENOLAKAN -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalReject" tabindex="-1" aria-labelledby="modalRejectLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 bg-danger text-white py-3 rounded-top-4">
                <h5 class="modal-title fw-bold" id="modalRejectLabel"><i class="bi bi-x-circle-fill me-2"></i>Tolak Laporan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('verifikasi.reject', $laporan->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small">Menolak laporan akan mengubah status menjadi **Ditolak** secara permanen. Pengaduan dihentikan dan tidak dapat diproses lagi ke tahap investigasi.</p>
                    
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label small fw-bold text-dark">Alasan Penolakan (Wajib)</label>
                        <select class="form-select rounded-3" id="rejection_reason" name="rejection_reason" required>
                            <option value="" disabled selected>Pilih alasan penolakan...</option>
                            <option value="Laporan tidak lengkap/kurang bukti">Laporan tidak lengkap / kurang bukti pendukung</option>
                            <option value="Laporan di luar wewenang BBSPJIKKP">Pengaduan di luar wewenang BBSPJIKKP</option>
                            <option value="Laporan palsu/fitnah/tidak berdasar">Pengaduan palsu / fitnah / tidak berdasar</option>
                            <option value="Laporan duplikat">Pengaduan merupakan duplikasi dari laporan sebelumnya</option>
                            <option value="Lainnya">Alasan lainnya (tuliskan pada catatan tambahan)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="verification_note" class="form-label small fw-bold text-dark">Catatan Tambahan (Opsional)</label>
                        <textarea class="form-control rounded-3" id="verification_note" name="verification_note" rows="3" placeholder="Tambahkan penjelasan pendukung mengapa laporan ditolak..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-toggle="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4 fw-semibold">Tolak Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

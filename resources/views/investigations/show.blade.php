@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb & Back Link -->
    <div class="mb-3">
        <a href="{{ route('investigations.index') }}" class="text-decoration-none text-muted small fw-medium d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Investigasi
        </a>
    </div>

    <!-- Header Card -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill small fw-semibold mb-2">
                    Kategori: {{ $investigation->laporan->kategori->nama ?? 'Lainnya' }}
                </span>
                <h4 class="fw-bold text-dark mb-1">Investigasi: {{ $investigation->laporan->judul }}</h4>
                <p class="text-muted small mb-0">
                    No. Registrasi: <span class="fw-bold text-primary">#{{ $investigation->laporan->nomor_registrasi }}</span> • 
                    Mulai Investigasi: <span class="fw-medium text-dark">{{ $investigation->created_at->translatedFormat('d F Y, H:i') }}</span>
                </p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-{{ $investigation->status_color }} px-3 py-2 rounded-pill fw-semibold text-capitalize" style="font-size: 0.85rem;">
                    Investigasi: {{ $investigation->status_label }}
                </span>
            </div>
        </div>
    </div>

    <!-- Alert Verification Statuses -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>
                    {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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

    <!-- Main Two Column Grid -->
    <div class="row g-4">
        
        <!-- Left Column: Report Details & Stepper Timeline -->
        <div class="col-lg-7">
            
            <!-- Accordion for Original Report Details (Keeps layout clean) -->
            <div class="accordion border-0 shadow-sm rounded-4 mb-4" id="laporanDetailsAccordion">
                <div class="accordion-item border-0 rounded-4 overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button bg-white text-dark fw-bold py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLaporan" aria-expanded="true" aria-controls="collapseLaporan">
                            <i class="bi bi-file-earmark-text-fill text-primary me-2"></i> Detail Laporan Pengaduan Awal
                        </button>
                    </h2>
                    <div id="collapseLaporan" class="accordion-collapse collapse show" data-bs-parent="#laporanDetailsAccordion">
                        <div class="accordion-body bg-white pt-2 px-4 pb-4">
                            
                            <!-- Kronologi -->
                            <div class="mb-4">
                                <label class="text-muted small fw-semibold d-block mb-2">Kronologi / Uraian Aduan</label>
                                <div class="p-3 bg-light rounded-3 text-dark text-break" style="white-space: pre-line; line-height: 1.6; font-size: 0.9rem;">
                                    {{ $investigation->laporan->deskripsi }}
                                </div>
                            </div>

                            <!-- Kejadian -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small fw-semibold d-block mb-1">Lokasi Kejadian</label>
                                    <span class="text-dark fw-semibold small">{{ $investigation->laporan->lokasi ?? '-' }}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small fw-semibold d-block mb-1">Waktu Kejadian</label>
                                    <span class="text-dark fw-semibold small">{{ $investigation->laporan->tanggal_kejadian ? $investigation->laporan->tanggal_kejadian->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                            </div>

                            <!-- Terlapor -->
                            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Identitas Terlapor</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="text-muted small fw-semibold d-block mb-1">Nama Terlapor</label>
                                    <span class="text-dark fw-bold small">{{ $investigation->laporan->nama_terlapor ?? '-' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted small fw-semibold d-block mb-1">Jabatan</label>
                                    <span class="text-dark fw-semibold small">{{ $investigation->laporan->jabatan_terlapor ?? '-' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted small fw-semibold d-block mb-1">Unit Kerja</label>
                                    <span class="text-dark fw-semibold small">{{ $investigation->laporan->unit_terlapor ?? '-' }}</span>
                                </div>
                            </div>

                            <!-- Pelapor -->
                            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Identitas Pelapor</h6>
                            @if($investigation->laporan->is_anonim)
                                <div class="alert alert-secondary border-0 d-flex align-items-center mb-4 py-2 px-3" role="alert">
                                    <i class="bi bi-eye-slash-fill me-2 fs-5 text-muted"></i>
                                    <span class="small text-muted mb-0">Aduan ini dikirimkan secara <strong>Anonim</strong>. Data identitas disembunyikan oleh sistem.</span>
                                </div>
                            @else
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="text-muted small fw-semibold d-block mb-1">Nama Pelapor</label>
                                        <span class="text-dark fw-bold small">{{ $investigation->laporan->nama_pelapor ?? '-' }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small fw-semibold d-block mb-1">Email</label>
                                        <span class="text-dark fw-semibold small">{{ $investigation->laporan->email_pelapor ?? '-' }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small fw-semibold d-block mb-1">Telepon</label>
                                        <span class="text-dark fw-semibold small">{{ $investigation->laporan->telepon_pelapor ?? '-' }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Bukti Pendukung Awal -->
                            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Bukti Pendukung Awal</h6>
                            @if($investigation->laporan->buktis->count() > 0)
                                <div class="row g-2">
                                    @foreach($investigation->laporan->buktis as $bukti)
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center justify-content-between p-2 border rounded-3 bg-light">
                                                <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                    @php
                                                        $ext = strtolower(pathinfo($bukti->nama_asli, PATHINFO_EXTENSION));
                                                        $icon = 'file-earmark-text';
                                                        $color = '#6c757d';
                                                        if(in_array($ext, ['pdf'])) { $icon = 'file-earmark-pdf'; $color = '#dc3545'; }
                                                        elseif(in_array($ext, ['jpg','jpeg','png'])) { $icon = 'file-earmark-image'; $color = '#0d6efd'; }
                                                        elseif(in_array($ext, ['doc','docx'])) { $icon = 'file-earmark-word'; $color = '#0dcaf0'; }
                                                    @endphp
                                                    <i class="bi bi-{{ $icon }} fs-4" style="color: {{ $color }};"></i>
                                                    <div class="overflow-hidden">
                                                        <div class="fw-semibold text-dark text-truncate small" title="{{ $bukti->nama_asli }}">{{ $bukti->nama_asli }}</div>
                                                        <small class="text-muted" style="font-size: 0.7rem;">{{ number_format($bukti->ukuran / 1024, 2) }} KB</small>
                                                    </div>
                                                </div>
                                                <a href="{{ Storage::url($bukti->path_file) }}" target="_blank" class="btn btn-sm btn-link text-secondary" title="Unduh Bukti">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> Tidak ada lampiran bukti awal.</p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="bi bi-clock-history text-primary me-2"></i>Timeline Perkembangan Investigasi</h5>
                
                @if($investigation->status !== 'completed')
                    <!-- Add Timeline Entry Form -->
                    <form action="{{ route('investigations.store-timeline', $investigation->id) }}" method="POST" class="mb-4 p-3 rounded-4 bg-light">
                        @csrf
                        <h6 class="fw-bold text-dark mb-3 small"><i class="bi bi-plus-circle-fill text-primary me-1"></i> Tambah Perkembangan Baru</h6>
                        
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="description" class="form-label small fw-semibold mb-1 text-muted">Deskripsi Kegiatan</label>
                                <textarea name="description" id="description" class="form-control form-control-sm rounded-3" rows="2" placeholder="Contoh: Melakukan wawancara terhadap saksi A." required>{{ old('description') }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label small fw-semibold mb-1 text-muted">Tanggal Perkembangan</label>
                                <input type="datetime-local" name="date" id="date" class="form-control form-control-sm rounded-3" value="{{ old('date', now()->format('Y-m-d\TH:i')) }}" max="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>
                        </div>
                        
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-medium">
                                <i class="bi bi-send-fill me-1" style="font-size: 0.8rem;"></i> Simpan Perkembangan
                            </button>
                        </div>
                    </form>
                @endif

                <!-- Stepper Vertical Timeline list -->
                @if($investigation->timelines->count() > 0)
                    <div class="timeline-stepper ps-3">
                        @foreach($investigation->timelines as $timeline)
                            <div class="timeline-item">
                                <div class="timeline-marker {{ $investigation->status === 'completed' && $loop->first ? 'completed' : '' }}">
                                    @if($investigation->status === 'completed' && $loop->first)
                                        <i class="bi bi-check text-white small" style="font-size: 0.75rem;"></i>
                                    @endif
                                </div>
                                <div class="bg-light-subtle p-3 rounded-3 border mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small text-muted fw-bold"><i class="bi bi-calendar3 me-1"></i> {{ $timeline->date->translatedFormat('d M Y, H:i') }}</span>
                                    </div>
                                    <p class="mb-0 text-dark small">{{ $timeline->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-calendar-x fs-3 d-block mb-2" style="opacity: 0.5;"></i>
                        <span class="small">Belum ada timeline perkembangan yang tercatat.</span>
                    </div>
                @endif

            </div>
        </div>

        <!-- Right Column: Document Uploads & Final Result -->
        <div class="col-lg-5">
            
            <!-- Secure Document Upload Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="bi bi-file-earmark-lock-fill text-primary me-2"></i>Dokumen Investigasi (Rahasia)</h5>
                
                @if($investigation->status !== 'completed')
                    <!-- Secure Upload Form -->
                    <form action="{{ route('investigations.store-document', $investigation->id) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label for="document" class="form-label small fw-semibold text-muted mb-1">Unggah Dokumen Baru</label>
                            <input class="form-control form-control-sm rounded-3" type="file" name="document" id="document" required>
                            <div class="form-text small text-muted">Format: PDF, JPG, PNG, DOC, DOCX. Max: 5MB. File disimpan secara aman di luar direktori publik.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill fw-medium">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Unggah Dokumen
                            </button>
                        </div>
                    </form>
                @endif

                <!-- Uploaded Documents Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-0 mb-0">
                        <thead>
                            <tr class="text-muted small" style="border-bottom: 2px solid #f2f7ff;">
                                <th class="border-0 pb-2">Nama File</th>
                                <th class="border-0 pb-2">Oleh</th>
                                <th class="border-0 pb-2 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($investigation->documents as $doc)
                                <tr style="border-bottom: 1px solid #f2f7ff;">
                                    <td class="py-2 overflow-hidden">
                                        <div class="fw-semibold text-dark text-truncate small" style="max-width: 150px;" title="{{ $doc->file_name }}">
                                            {{ $doc->file_name }}
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 0.65rem;">{{ number_format($doc->file_size / 1024, 2) }} KB</small>
                                    </td>
                                    <td class="py-2 text-muted small" style="font-size: 0.75rem;">
                                        {{ $doc->uploader->name ?? 'System' }}
                                    </td>
                                    <td class="py-2 text-end">
                                        <a href="{{ route('investigations.download-document', ['id' => $investigation->id, 'docId' => $doc->id]) }}" class="btn btn-sm btn-light rounded-circle text-primary border" title="Unduh secara aman">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">
                                        <i class="bi bi-file-earmark-excel fs-4 d-block mb-1 text-muted" style="opacity: 0.5;"></i>
                                        Belum ada dokumen yang diunggah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Final Investigation Result Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="bi bi-file-earmark-check-fill text-primary me-2"></i>Hasil Akhir & Rekomendasi</h5>

                @if($investigation->status === 'completed')
                    <!-- Finished Alert Box showing result -->
                    <div class="p-3 bg-success-subtle border border-success-subtle text-success-emphasis rounded-4 mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                            <h6 class="fw-bold mb-0">Investigasi Selesai</h6>
                        </div>
                        <p class="small mb-0">Pemeriksaan kasus ini telah disimpulkan dan hasil beserta rekomendasi tindakan disiplin/perbaikan sistem telah diajukan ke pimpinan.</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small fw-semibold d-block mb-1">Hasil Temuan Investigasi</label>
                        <div class="p-3 bg-light rounded-3 text-dark text-break small" style="white-space: pre-line; line-height: 1.5;">
                            {{ $investigation->final_result }}
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small fw-semibold d-block mb-1">Rekomendasi Tindak Lanjut</label>
                        <div class="p-3 bg-light rounded-3 text-dark text-break small" style="white-space: pre-line; line-height: 1.5;">
                            {{ $investigation->recommendation }}
                        </div>
                    </div>
                @else
                    <!-- Active form to submit final results -->
                    <form action="{{ route('investigations.update-result', $investigation->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan investigasi ini? Tindakan ini bersifat final dan tidak dapat diubah.');">
                        @csrf
                        <div class="mb-3">
                            <label for="final_result" class="form-label small fw-semibold text-muted mb-1">Hasil Temuan Akhir</label>
                            <textarea name="final_result" id="final_result" class="form-control rounded-3" rows="4" placeholder="Uraikan temuan objektif, bukti material yang diperoleh, dan fakta yang terbukti..." required>{{ old('final_result') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="recommendation" class="form-label small fw-semibold text-muted mb-1">Rekomendasi Tindakan</label>
                            <textarea name="recommendation" id="recommendation" class="form-control rounded-3" rows="3" placeholder="Uraikan rekomendasi hukuman disiplin, teguran, pembinaan, atau perbaikan tata kelola..." required>{{ old('recommendation') }}</textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success rounded-pill fw-semibold py-2">
                                <i class="bi bi-shield-check me-1"></i> Kirim Hasil & Selesaikan Kasus
                            </button>
                        </div>
                    </form>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline-stepper {
        position: relative;
        padding-left: 20px;
    }
    .timeline-stepper::before {
        content: '';
        position: absolute;
        left: 9px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background-color: #eaf1ff;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-marker {
        position: absolute;
        left: -20px;
        top: 5px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #fff;
        border: 3px solid var(--bs-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }
    .timeline-marker.completed {
        border-color: #198754;
        background-color: #198754;
    }
    .accordion-button:not(.collapsed) {
        background-color: var(--bs-primary-soft) !important;
        color: var(--bs-primary) !important;
        box-shadow: none;
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
</style>
@endpush

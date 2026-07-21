@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('tindak-lanjut.index') }}" class="text-muted small text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Tindak Lanjut
        </a>
        <h1 class="h4 fw-bold text-dark mb-1 mt-1">Detail Hasil Investigasi</h1>
        <p class="text-muted small mb-0">{{ $investigation->laporan->nomor_registrasi }}</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Kolom kiri: Info Laporan & Hasil Investigasi --}}
    <div class="col-lg-7">
        {{-- Info Laporan --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Informasi Laporan</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <dl class="row g-2 mb-0">
                    <dt class="col-sm-4 text-muted small">No. Registrasi</dt>
                    <dd class="col-sm-8 small fw-semibold">{{ $investigation->laporan->nomor_registrasi }}</dd>

                    <dt class="col-sm-4 text-muted small">Kategori</dt>
                    <dd class="col-sm-8">
                        <span class="badge rounded-pill" style="background:{{ $investigation->laporan->kategori->warna ?? '#6c757d' }}20; color:{{ $investigation->laporan->kategori->warna ?? '#6c757d' }}">
                            {{ $investigation->laporan->kategori->nama ?? '-' }}
                        </span>
                    </dd>

                    <dt class="col-sm-4 text-muted small">Terlapor</dt>
                    <dd class="col-sm-8 small">{{ $investigation->laporan->nama_terlapor ?? '-' }}
                        @if($investigation->laporan->jabatan_terlapor)
                            — {{ $investigation->laporan->jabatan_terlapor }}
                        @endif
                    </dd>

                    <dt class="col-sm-4 text-muted small">Investigator</dt>
                    <dd class="col-sm-8 small">{{ $investigation->investigator->name ?? '-' }}</dd>

                    <dt class="col-sm-4 text-muted small">Ditugaskan</dt>
                    <dd class="col-sm-8 small">{{ $investigation->assigned_at ? $investigation->assigned_at->format('d M Y') : '-' }}</dd>
                </dl>
            </div>
        </div>

        {{-- Hasil Investigasi --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-search me-2 text-primary"></i>Hasil Investigasi</h6>
            </div>
            <div class="card-body px-4 pb-4">
                @if($investigation->final_result)
                    <p class="small text-dark">{{ $investigation->final_result }}</p>
                @else
                    <p class="text-muted small fst-italic">Hasil investigasi belum diisi.</p>
                @endif

                @if($investigation->recommendation)
                    <h6 class="fw-semibold small mt-3 mb-2">Rekomendasi</h6>
                    <p class="small text-dark mb-0">{{ $investigation->recommendation }}</p>
                @endif
            </div>
        </div>

        {{-- Timeline Investigasi --}}
        @if($investigation->timelines->count())
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Timeline Investigasi</h6>
            </div>
            <div class="card-body px-4 pb-3">
                @foreach($investigation->timelines as $tl)
                <div class="d-flex gap-3 mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-primary-soft rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                            <i class="bi bi-calendar2-check text-primary small"></i>
                        </div>
                    </div>
                    <div>
                        <p class="mb-0 small fw-semibold">{{ $tl->title ?? \Carbon\Carbon::parse($tl->date)->format('d M Y') }}</p>
                        <p class="mb-0 small text-muted">{{ $tl->description ?? $tl->activity }}</p>
                        <p class="mb-0 x-small text-muted" style="font-size:0.75rem;">{{ \Carbon\Carbon::parse($tl->date ?? $tl->created_at)->format('d M Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Kolom kanan: Form Tindak Lanjut / Hasil --}}
    <div class="col-lg-5">
        @if($investigation->tindakLanjut)
        {{-- Sudah ada tindak lanjut --}}
        <div class="card border-0 shadow-sm rounded-4 border-start border-success border-4">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold mb-0 text-success"><i class="bi bi-check-circle-fill me-2"></i>Tindak Lanjut Ditetapkan</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Jenis Tindakan</span>
                    <span class="badge bg-{{ $investigation->tindakLanjut->jenis_color }} fs-6 fw-semibold rounded-3 px-3 py-2">
                        {{ $investigation->tindakLanjut->jenis_label }}
                    </span>
                </div>
                @if($investigation->tindakLanjut->keterangan)
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Keterangan</span>
                    <p class="small text-dark mb-0">{{ $investigation->tindakLanjut->keterangan }}</p>
                </div>
                @endif
                <div>
                    <span class="text-muted small d-block mb-1">Ditetapkan Oleh</span>
                    <p class="small text-dark mb-0">
                        {{ $investigation->tindakLanjut->ditetapkanOleh->name ?? '-' }}
                        — {{ $investigation->tindakLanjut->ditetapkan_pada->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>
        @else
        {{-- Form Penetapan --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Tetapkan Tindak Lanjut</h6>
            </div>
            <div class="card-body px-4 pb-4">
                @if($errors->any())
                    <div class="alert alert-danger rounded-3 small">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('tindak-lanjut.store', $investigation->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Jenis Tindakan <span class="text-danger">*</span></label>
                        <select name="jenis_tindakan" class="form-select rounded-3 @error('jenis_tindakan') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Tindakan --</option>
                            @foreach($jenisList as $key => $label)
                                <option value="{{ $key }}" {{ old('jenis_tindakan') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('jenis_tindakan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Keterangan / Catatan</label>
                        <textarea name="keterangan" class="form-control rounded-3 @error('keterangan') is-invalid @enderror"
                            rows="4" placeholder="Uraian singkat tindak lanjut yang ditetapkan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="alert alert-warning rounded-3 small">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Setelah tindak lanjut ditetapkan, status laporan akan otomatis berubah menjadi <strong>Selesai</strong>.
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-3 fw-semibold">
                            <i class="bi bi-check2-circle me-2"></i>Tetapkan Tindak Lanjut
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

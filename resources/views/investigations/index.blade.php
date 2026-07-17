@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Daftar Investigasi</h3>
            <p class="text-muted small mb-0">Daftar kasus pelanggaran yang ditugaskan kepada Anda untuk dilakukan pemeriksaan mendalam.</p>
        </div>
        <div>
            <span class="badge bg-white text-muted border py-2 px-3 rounded-pill shadow-sm small">
                <i class="bi bi-shield-shaded me-1"></i> Tim Investigasi
            </span>
        </div>
    </div>

    <!-- Alert Success -->
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

    <!-- Table Container -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold text-dark mb-0">Antrean Kasus Investigasi</h5>
            <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill small fw-semibold">
                {{ $investigations->total() }} Kasus Ditugaskan
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle border-0 mb-0">
                <thead>
                    <tr class="text-muted small" style="border-bottom: 2px solid #f2f7ff;">
                        <th class="border-0 pb-3" style="width: 18%;">No. Laporan</th>
                        <th class="border-0 pb-3">Judul Laporan</th>
                        <th class="border-0 pb-3">Kategori</th>
                        <th class="border-0 pb-3">Tanggal Ditugaskan</th>
                        @if(auth()->user()->hasRole('super-admin'))
                            <th class="border-0 pb-3">Investigator</th>
                        @endif
                        <th class="border-0 pb-3">Status Kasus</th>
                        <th class="border-0 pb-3 text-center" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($investigations as $investigation)
                        <tr style="border-bottom: 1px solid #f2f7ff;">
                            <td class="fw-bold text-dark py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-text text-primary"></i>
                                    {{ $investigation->laporan->nomor_registrasi }}
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="fw-semibold text-dark text-truncate" style="max-width: 250px;" title="{{ $investigation->laporan->judul }}">
                                    {{ $investigation->laporan->judul }}
                                </div>
                            </td>
                            <td class="py-3" style="font-size: 0.9rem;">
                                <span class="badge rounded-pill px-3 py-2 fw-medium" style="background-color: {{ ($investigation->laporan->kategori->warna ?? '#0a4282') . '15' }}; color: {{ $investigation->laporan->kategori->warna ?? '#0a4282' }}; border: 1px solid {{ ($investigation->laporan->kategori->warna ?? '#0a4282') . '30' }};">
                                    {{ $investigation->laporan->kategori->nama ?? 'Lainnya' }}
                                </span>
                            </td>
                            <td class="text-muted py-3" style="font-size: 0.9rem;">
                                {{ $investigation->created_at->translatedFormat('d M Y H:i') }}
                            </td>
                            @if(auth()->user()->hasRole('super-admin'))
                                <td class="py-3" style="font-size: 0.9rem;">
                                    <div class="fw-medium text-dark">
                                        {{ $investigation->investigator->name ?? 'Belum Ditugaskan' }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $investigation->investigator->email ?? '-' }}
                                    </div>
                                </td>
                            @endif
                            <td class="py-3">
                                <span class="badge bg-{{ $investigation->status_color }} px-3 py-2 rounded-pill fw-semibold text-capitalize" style="font-size: 0.8rem;">
                                    {{ $investigation->status_label }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <a href="{{ route('investigations.show', $investigation->id) }}" class="btn btn-sm btn-primary px-3 rounded-pill fw-medium d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-search"></i> 
                                    @if($investigation->status === 'pending')
                                        Mulai
                                    @else
                                        Detail
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="bi bi-folder-x fs-1 d-block mb-3 text-muted" style="opacity: 0.5;"></i>
                                    <span class="fw-medium">Belum ada kasus investigasi yang ditugaskan kepada Anda.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $investigations->links() }}
        </div>
    </div>
</div>
@endsection

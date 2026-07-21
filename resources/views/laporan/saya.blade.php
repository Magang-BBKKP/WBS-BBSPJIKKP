@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Laporan Saya</h1>
        <p class="text-muted small mb-0">Daftar seluruh laporan yang pernah Anda buat</p>
    </div>
    <a href="{{ route('laporan.create') }}" class="btn btn-primary rounded-3">
        <i class="bi bi-plus-circle me-1"></i> Buat Laporan Baru
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        @if($laporans->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-2 mb-1">Anda belum pernah membuat laporan.</p>
                <a href="{{ route('laporan.create') }}" class="btn btn-sm btn-primary rounded-3 mt-1">Buat Laporan Pertama</a>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted small fw-semibold">No. Registrasi</th>
                        <th class="py-3 text-muted small fw-semibold">Kategori</th>
                        <th class="py-3 text-muted small fw-semibold">Tanggal Lapor</th>
                        <th class="py-3 text-muted small fw-semibold">Status</th>
                        <th class="py-3 text-muted small fw-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporans as $laporan)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="badge bg-primary-soft text-primary fw-semibold">{{ $laporan->nomor_registrasi }}</span>
                            @if($laporan->is_anonim)
                                <span class="badge bg-secondary-subtle text-secondary small ms-1">Anonim</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <span class="badge rounded-pill" style="background:{{ $laporan->kategori->warna ?? '#6c757d' }}20; color:{{ $laporan->kategori->warna ?? '#6c757d' }}">
                                {{ $laporan->kategori->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 text-muted small">{{ $laporan->created_at->format('d M Y') }}</td>
                        <td class="py-3">
                            <span class="badge bg-{{ $laporan->status_color }}-subtle text-{{ $laporan->status_color }} rounded-pill fw-semibold">
                                {{ $laporan->status_label }}
                            </span>
                        </td>
                        <td class="py-3">
                            <a href="{{ route('track.show', $laporan->tracking_token) }}" class="btn btn-sm btn-outline-primary rounded-3" target="_blank">
                                <i class="bi bi-search me-1"></i>Lacak
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $laporans->links() }}</div>
        @endif
    </div>
</div>
@endsection

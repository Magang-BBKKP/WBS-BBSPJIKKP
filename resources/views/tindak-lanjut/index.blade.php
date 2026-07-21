@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Tindak Lanjut</h1>
        <p class="text-muted small mb-0">Hasil investigasi yang selesai dan menunggu penetapan tindak lanjut</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Search --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control rounded-3" placeholder="Cari nomor registrasi atau judul..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary px-4 rounded-3"><i class="bi bi-search"></i></button>
            @if($search)
                <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-outline-secondary rounded-3">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        @if($investigations->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-clipboard2-check fs-1 text-muted"></i>
                <p class="text-muted mt-2">Belum ada investigasi yang selesai dan menunggu tindak lanjut.</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted small fw-semibold">No. Registrasi</th>
                        <th class="py-3 text-muted small fw-semibold">Kategori</th>
                        <th class="py-3 text-muted small fw-semibold">Investigator</th>
                        <th class="py-3 text-muted small fw-semibold">Status Tindak Lanjut</th>
                        <th class="py-3 text-muted small fw-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($investigations as $inv)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="badge bg-primary-soft text-primary fw-semibold">{{ $inv->laporan->nomor_registrasi }}</span>
                        </td>
                        <td class="py-3">
                            <span class="badge rounded-pill" style="background:{{ $inv->laporan->kategori->warna ?? '#6c757d' }}20; color:{{ $inv->laporan->kategori->warna ?? '#6c757d' }}">
                                {{ $inv->laporan->kategori->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 text-muted small">{{ $inv->investigator->name ?? '-' }}</td>
                        <td class="py-3">
                            @if($inv->tindakLanjut)
                                <span class="badge bg-success-subtle text-success rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i>{{ $inv->tindakLanjut->jenis_label }}
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning rounded-pill">
                                    <i class="bi bi-clock me-1"></i>Menunggu Penetapan
                                </span>
                            @endif
                        </td>
                        <td class="py-3">
                            <a href="{{ route('tindak-lanjut.show', $inv->id) }}" class="btn btn-sm btn-outline-primary rounded-3">
                                <i class="bi bi-eye me-1"></i>
                                @if($inv->tindakLanjut) Lihat @else Tetapkan @endif
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $investigations->links() }}</div>
        @endif
    </div>
</div>
@endsection

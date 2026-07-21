@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Pembentukan Tim Investigasi</h1>
        <p class="text-muted small mb-0">Laporan yang telah terverifikasi dan siap ditugaskan kepada Investigator</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Search --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control rounded-3" placeholder="Cari nomor registrasi, judul, kategori..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary px-4 rounded-3"><i class="bi bi-search"></i></button>
            @if($search)
                <a href="{{ route('kepala.index') }}" class="btn btn-outline-secondary rounded-3">Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        @if($laporans->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-2">Tidak ada laporan valid yang menunggu penugasan investigator.</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted small fw-semibold">No. Registrasi</th>
                        <th class="py-3 text-muted small fw-semibold">Kategori</th>
                        <th class="py-3 text-muted small fw-semibold">Pelapor</th>
                        <th class="py-3 text-muted small fw-semibold">Tanggal</th>
                        <th class="py-3 text-muted small fw-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporans as $laporan)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="badge bg-primary-soft text-primary fw-semibold">{{ $laporan->nomor_registrasi }}</span>
                        </td>
                        <td class="py-3">
                            <span class="badge rounded-pill" style="background:{{ $laporan->kategori->warna ?? '#6c757d' }}20; color:{{ $laporan->kategori->warna ?? '#6c757d' }}">
                                {{ $laporan->kategori->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 text-muted small">{{ $laporan->is_anonim ? 'Anonim' : $laporan->getRealNamaPelaporAttribute() }}</td>
                        <td class="py-3 text-muted small">{{ $laporan->created_at->format('d M Y') }}</td>
                        <td class="py-3">
                            <button class="btn btn-sm btn-primary rounded-3"
                                data-bs-toggle="modal"
                                data-bs-target="#assignModal{{ $laporan->id }}">
                                <i class="bi bi-person-check-fill me-1"></i> Tugaskan
                            </button>
                        </td>
                    </tr>

                    {{-- Modal Assign --}}
                    <div class="modal fade" id="assignModal{{ $laporan->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow rounded-4">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Tugaskan Investigator</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('kepala.assign', $laporan->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="text-muted small mb-3">
                                            Laporan <strong>{{ $laporan->nomor_registrasi }}</strong> — {{ $laporan->kategori->nama ?? '-' }}
                                        </p>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Pilih Investigator <span class="text-danger">*</span></label>
                                            <select name="investigator_id" class="form-select rounded-3" required>
                                                <option value="">-- Pilih Investigator --</option>
                                                @foreach($investigators as $inv)
                                                    <option value="{{ $inv->id }}">{{ $inv->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-3">
                                            <i class="bi bi-person-check-fill me-1"></i> Tugaskan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $laporans->links() }}</div>
        @endif
    </div>
</div>
@endsection

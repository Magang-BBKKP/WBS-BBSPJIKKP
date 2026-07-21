@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Master Data — Kategori Pelanggaran</h1>
        <p class="text-muted small mb-0">Kelola daftar kategori pelanggaran yang tersedia pada formulir laporan</p>
    </div>
    <button class="btn btn-primary rounded-3" data-bs-toggle="modal" data-bs-target="#addKategoriModal">
        <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
    </button>
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
            <input type="text" name="search" class="form-control rounded-3" placeholder="Cari nama atau deskripsi kategori..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary px-4 rounded-3"><i class="bi bi-search"></i></button>
            @if($search) <a href="{{ route('master-data.index') }}" class="btn btn-outline-secondary rounded-3">Reset</a> @endif
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted small fw-semibold">#</th>
                        <th class="py-3 text-muted small fw-semibold">Warna</th>
                        <th class="py-3 text-muted small fw-semibold">Nama Kategori</th>
                        <th class="py-3 text-muted small fw-semibold">Deskripsi</th>
                        <th class="py-3 text-muted small fw-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $k)
                    <tr>
                        <td class="px-4 py-3 text-muted small">{{ $kategoris->firstItem() + $loop->index }}</td>
                        <td class="py-3">
                            <div class="rounded-3" style="width:32px;height:20px;background:{{ $k->warna ?? '#6c757d' }};"></div>
                        </td>
                        <td class="py-3 fw-semibold small">{{ $k->nama }}</td>
                        <td class="py-3 text-muted small">{{ $k->deskripsi ?? '-' }}</td>
                        <td class="py-3 d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $k->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('master-data.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editModal{{ $k->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow rounded-4">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('master-data.update', $k->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Nama <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control rounded-3" value="{{ $k->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control rounded-3" rows="2">{{ $k->deskripsi }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Warna</label>
                                            <input type="color" name="warna" class="form-control form-control-color rounded-3" value="{{ $k->warna ?? '#0a4282' }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-3">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $kategoris->links() }}</div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addKategoriModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master-data.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control rounded-3" placeholder="Nama kategori..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="2" placeholder="Keterangan singkat..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Warna</label>
                        <input type="color" name="warna" class="form-control form-control-color rounded-3" value="#0a4282">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

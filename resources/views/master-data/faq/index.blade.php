@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Master Data — FAQ (Frequently Asked Questions)</h1>
        <p class="text-muted small mb-0">Kelola daftar pertanyaan yang sering diajukan pada halaman depan/landing page</p>
    </div>
    <button class="btn btn-primary rounded-3" data-bs-toggle="modal" data-bs-target="#addFaqModal">
        <i class="bi bi-plus-circle me-1"></i> Tambah FAQ
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">
        <div class="fw-semibold mb-1">Data belum bisa disimpan.</div>
        <ul class="mb-0 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@isset($tabs)
<ul class="nav nav-pills gap-2 mb-4">
    @foreach($tabs as $tab)
        <li class="nav-item">
            <a class="nav-link {{ $tab['active'] ? 'active' : '' }}" href="{{ $tab['url'] }}">
                {{ $tab['title'] }}
            </a>
        </li>
    @endforeach
</ul>
@endisset

{{-- Search --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control rounded-3" placeholder="Cari pertanyaan atau jawaban..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary px-4 rounded-3"><i class="bi bi-search"></i></button>
            @if($search) <a href="{{ route('master-data.faq.index') }}" class="btn btn-outline-secondary rounded-3">Reset</a> @endif
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
                        <th class="py-3 text-muted small fw-semibold">Pertanyaan</th>
                        <th class="py-3 text-muted small fw-semibold">Jawaban</th>
                        <th class="py-3 text-muted small fw-semibold">Urutan</th>
                        <th class="py-3 text-muted small fw-semibold">Status</th>
                        <th class="py-3 text-muted small fw-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $f)
                    <tr>
                        <td class="px-4 py-3 text-muted small">{{ $faqs->firstItem() + $loop->index }}</td>
                        <td class="py-3 fw-semibold small" style="max-width:250px;">{{ $f->question }}</td>
                        <td class="py-3 text-muted small" style="max-width:400px; text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">{{ $f->answer }}</td>
                        <td class="py-3 text-muted small">{{ $f->order }}</td>
                        <td class="py-3">
                            @if($f->is_active)
                                <span class="badge bg-success-soft text-success px-2 py-1 rounded-pill small">Aktif</span>
                            @else
                                <span class="badge bg-secondary-soft text-secondary px-2 py-1 rounded-pill small">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="py-3 d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $f->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('master-data.faq.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Hapus FAQ ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editModal{{ $f->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow rounded-4">
                                <div class="modal-header border-0 px-4 pt-4">
                                    <h5 class="modal-title fw-bold">Edit FAQ</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('master-data.faq.update', $f->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body px-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                                            <input type="text" name="question" class="form-control rounded-3" value="{{ old('question', $f->question) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Jawaban <span class="text-danger">*</span></label>
                                            <textarea name="answer" rows="5" class="form-control rounded-3" required>{{ old('answer', $f->answer) }}</textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold">Urutan Tampil</label>
                                                <input type="number" name="order" class="form-control rounded-3" value="{{ old('order', $f->order) }}" min="0">
                                            </div>
                                            <div class="col-md-6 mb-3 d-flex align-items-center">
                                                <div class="form-check form-switch mt-4">
                                                    <input class="form-check-input" type="checkbox" name="is_active" id="editActiveSwitch{{ $f->id }}" value="1" {{ old('is_active', $f->is_active) ? 'checked' : '' }}>
                                                    <label class="form-check-label ms-2 small fw-semibold" for="editActiveSwitch{{ $f->id }}">FAQ Aktif</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 px-4 pb-4 pt-0">
                                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted small">
                            <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                            Belum ada FAQ yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($faqs->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $faqs->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Tambah FAQ Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master-data.faq.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                        <input type="text" name="question" class="form-control rounded-3" placeholder="Masukkan pertanyaan..." value="{{ old('question') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jawaban <span class="text-danger">*</span></label>
                        <textarea name="answer" rows="5" class="form-control rounded-3" placeholder="Masukkan jawaban..." required>{{ old('answer') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Urutan Tampil</label>
                            <input type="number" name="order" class="form-control rounded-3" value="{{ old('order', 0) }}" min="0">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-center">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="addActiveSwitch" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2 small fw-semibold" for="addActiveSwitch">FAQ Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

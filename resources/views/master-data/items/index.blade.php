@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Master Data &mdash; {{ $meta['title'] }}</h1>
        <p class="text-muted small mb-0">{{ $meta['description'] }}</p>
    </div>
    <button class="btn btn-primary rounded-3" data-bs-toggle="modal" data-bs-target="#addItemModal">
        <i class="bi bi-plus-circle me-1"></i> Tambah {{ $meta['title'] }}
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger rounded-3 border-0 shadow-sm">
        <div class="fw-semibold mb-1">Data belum bisa disimpan.</div>
        <ul class="mb-0 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<ul class="nav nav-pills gap-2 mb-4">
    @foreach($tabs as $tab)
        <li class="nav-item">
            <a class="nav-link {{ $tab['active'] ? 'active' : '' }}" href="{{ $tab['url'] }}">
                {{ $tab['title'] }}
            </a>
        </li>
    @endforeach
</ul>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control rounded-3" placeholder="Cari nama atau deskripsi..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary px-4 rounded-3"><i class="bi bi-search"></i></button>
            @if($search)
                <a href="{{ route('master-data.items.index', $type) }}" class="btn btn-outline-secondary rounded-3">Reset</a>
            @endif
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
                        @if($meta['uses_color'])
                            <th class="py-3 text-muted small fw-semibold">Warna</th>
                        @endif
                        <th class="py-3 text-muted small fw-semibold">Nama</th>
                        <th class="py-3 text-muted small fw-semibold">Deskripsi</th>
                        <th class="py-3 text-muted small fw-semibold">Urutan</th>
                        <th class="py-3 text-muted small fw-semibold">Status</th>
                        <th class="py-3 text-muted small fw-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="px-4 py-3 text-muted small">{{ $items->firstItem() + $loop->index }}</td>
                        @if($meta['uses_color'])
                            <td class="py-3">
                                <div class="rounded-3 border" style="width:32px;height:20px;background:{{ $item->color ?? '#6c757d' }};"></div>
                            </td>
                        @endif
                        <td class="py-3 fw-semibold small">{{ $item->name }}</td>
                        <td class="py-3 text-muted small">{{ $item->description ?? '-' }}</td>
                        <td class="py-3 text-muted small">{{ $item->sort_order }}</td>
                        <td class="py-3">
                            <span class="badge rounded-pill text-bg-{{ $item->is_active ? 'success' : 'secondary' }}">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="py-3 d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('master-data.items.destroy', [$type, $item->id]) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow rounded-4">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-bold">Edit {{ $meta['title'] }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('master-data.items.update', [$type, $item->id]) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        @include('master-data.items.partials.form', ['item' => $item])
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
                    <tr>
                        <td colspan="{{ $meta['uses_color'] ? 7 : 6 }}" class="text-center py-4 text-muted">Belum ada data {{ strtolower($meta['title']) }}.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $items->links() }}</div>
    </div>
</div>

<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah {{ $meta['title'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master-data.items.store', $type) }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('master-data.items.partials.form', ['item' => null])
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

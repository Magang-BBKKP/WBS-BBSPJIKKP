@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Breadcrumb / Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 text-gray-800 mb-0">User Management</h1>
                <p class="text-muted mb-0">Kelola pengguna sistem dan pengaturan role mereka.</p>
            </div>
            @can('create', App\Models\User::class)
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-1"></i> Tambah User Baru
                </a>
            @endcan
        </div>

        <!-- Success/Error Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Card Wrapper -->
        <div class="card shadow-sm">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                <span class="card-title mb-0">Daftar Pengguna</span>
                <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2" style="max-width: 350px; width: 100%;">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau unit..." value="{{ $search }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    @if ($search)
                        <a href="{{ route('users.index') }}" class="btn btn-outline-danger">Reset</a>
                    @endif
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Unit Kerja</th>
                            <th>Role</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="ps-4 text-muted">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                    @if ($user->phone_number)
                                        <small class="text-muted d-block"><i class="bi bi-telephone me-1"></i>{{ $user->phone_number }}</small>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->unit_kerja ?? '-' }}</td>
                                <td>
                                    @foreach ($user->roles as $role)
                                        <span class="badge bg-secondary text-capitalize">{{ str_replace('-', ' ', $role->name) }}</span>
                                    @endforeach
                                    @if ($user->roles->isEmpty())
                                        <span class="badge bg-light text-dark">No Role</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        @can('update', $user)
                                            <button type="submit" class="btn btn-sm border-0 p-0" title="Klik untuk mengubah status">
                                                @if ($user->is_active)
                                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                        <i class="bi bi-circle-fill me-1 small"></i> Aktif
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                                        <i class="bi bi-circle-fill me-1 small"></i> Nonaktif
                                                    </span>
                                                @endif
                                            </button>
                                        @else
                                            @if ($user->is_active)
                                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Aktif</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Nonaktif</span>
                                            @endif
                                        @endcan
                                    </form>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        @can('update', $user)
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $user)
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Action ini tidak dapat dibatalkan.');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    Tidak ada pengguna ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if ($users->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-center">
                        {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

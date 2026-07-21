@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Audit Log</h1>
        <p class="text-muted small mb-0">Seluruh rekam jejak aktivitas pengguna dalam sistem</p>
    </div>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm rounded-3"
                    placeholder="Cari aksi, deskripsi, IP..." value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-3">
                <select name="user_id" class="form-select form-select-sm rounded-3">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ $filterUser == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm rounded-3" value="{{ $dateFrom ?? '' }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm rounded-3" value="{{ $dateTo ?? '' }}">
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary rounded-3 w-100"><i class="bi bi-search"></i></button>
            </div>
        </form>
        @if($search || $filterUser || $dateFrom || $dateTo)
            <div class="mt-2">
                <a href="{{ route('audit-log.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
                    <i class="bi bi-x-circle me-1"></i>Reset Filter
                </a>
            </div>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted small fw-semibold">Waktu</th>
                        <th class="py-3 text-muted small fw-semibold">Pengguna</th>
                        <th class="py-3 text-muted small fw-semibold">Aksi</th>
                        <th class="py-3 text-muted small fw-semibold">Deskripsi</th>
                        <th class="py-3 text-muted small fw-semibold">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="px-4 py-3 text-muted small" style="white-space:nowrap;">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td class="py-3 small">{{ $log->user->name ?? '<em class="text-muted">System</em>' }}</td>
                        <td class="py-3">
                            <span class="badge bg-primary-soft text-primary small fw-semibold">{{ $log->action }}</span>
                        </td>
                        <td class="py-3 small text-muted" style="max-width:300px;">{{ $log->description }}</td>
                        <td class="py-3 small text-muted font-monospace">{{ $log->ip_address }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada log aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $logs->links() }}</div>
    </div>
</div>
@endsection

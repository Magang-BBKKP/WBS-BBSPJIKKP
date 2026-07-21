@extends('layouts.app')

@push('styles')
<style>
.stat-card { transition: transform .2s ease, box-shadow .2s ease; }
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.1) !important; }
.progress { height: 8px; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Monitoring</h1>
    <p class="text-muted small mb-0">Efektivitas, penyelesaian, dan pencegahan laporan WBS</p>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['label'=>'Total Laporan',    'value'=>$stats['total'],       'icon'=>'bi-clipboard2-data',    'color'=>'primary'],
        ['label'=>'Menunggu',         'value'=>$stats['menunggu'],    'icon'=>'bi-hourglass-split',    'color'=>'warning'],
        ['label'=>'Investigasi',      'value'=>$stats['investigasi'], 'icon'=>'bi-search',             'color'=>'info'],
        ['label'=>'Selesai',          'value'=>$stats['selesai'],     'icon'=>'bi-check2-circle',      'color'=>'success'],
        ['label'=>'Ditolak',          'value'=>$stats['ditolak'],     'icon'=>'bi-x-circle',           'color'=>'danger'],
        ['label'=>'Terverifikasi',    'value'=>$stats['valid'],       'icon'=>'bi-shield-check',       'color'=>'primary'],
    ];
    @endphp
    @foreach($cards as $c)
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 stat-card h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle bg-{{ $c['color'] }}-subtle d-inline-flex align-items-center justify-content-center mb-2" style="width:44px;height:44px;">
                    <i class="bi {{ $c['icon'] }} text-{{ $c['color'] }} fs-5"></i>
                </div>
                <h3 class="fw-bold mb-0 text-{{ $c['color'] }}">{{ $c['value'] }}</h3>
                <p class="text-muted small mb-0">{{ $c['label'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Progress Penyelesaian --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body px-4 py-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-semibold small">Tingkat Penyelesaian</span>
            <span class="fw-bold text-success">{{ $pctSelesai }}%</span>
        </div>
        <div class="progress rounded-pill">
            <div class="progress-bar bg-success rounded-pill" style="width: {{ $pctSelesai }}%"></div>
        </div>
        <p class="text-muted small mt-2 mb-0">{{ $stats['selesai'] }} dari {{ $stats['total'] }} laporan telah diselesaikan</p>
    </div>
</div>

{{-- Per Kategori --}}
@if($perKategori->count())
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-0 py-3 px-4">
        <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i>Laporan per Kategori</h6>
    </div>
    <div class="card-body px-4 pb-4">
        @foreach($perKategori as $row)
        @php $pct = $row->total > 0 ? round(($row->selesai / $row->total) * 100) : 0; @endphp
        <div class="mb-3">
            <div class="d-flex justify-content-between mb-1">
                <span class="small fw-semibold">{{ $row->kategori->nama ?? 'Tidak Diketahui' }}</span>
                <span class="small text-muted">{{ $row->selesai }}/{{ $row->total }} ({{ $pct }}%)</span>
            </div>
            <div class="progress rounded-pill">
                <div class="progress-bar rounded-pill" style="width:{{ $pct }}%; background:{{ $row->kategori->warna ?? '#0a4282' }}"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Filter + Tabel Laporan --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-table me-2 text-primary"></i>Daftar Seluruh Laporan</h6>
    </div>
    <div class="card-body px-4 pt-0 pb-3">
        <form method="GET" class="row g-2 mb-3 mt-2">
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm rounded-3">
                    <option value="">Semua Status</option>
                    @foreach($statusList as $key => $s)
                        <option value="{{ $key }}" {{ $filterStatus == $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="kategori_id" class="form-select form-select-sm rounded-3">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ $filterKategori == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary rounded-3 w-100">Filter</button>
            </div>
            @if($filterStatus || $filterKategori)
            <div class="col-md-2">
                <a href="{{ route('monitoring.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 w-100">Reset</a>
            </div>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4 py-3 text-muted small fw-semibold">No. Registrasi</th>
                    <th class="py-3 text-muted small fw-semibold">Kategori</th>
                    <th class="py-3 text-muted small fw-semibold">Tanggal</th>
                    <th class="py-3 text-muted small fw-semibold">Status</th>
                    <th class="py-3 text-muted small fw-semibold">Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $laporan)
                <tr>
                    <td class="px-4 py-3">
                        <span class="badge bg-primary-soft text-primary fw-semibold small">{{ $laporan->nomor_registrasi }}</span>
                    </td>
                    <td class="py-3">
                        <span class="badge rounded-pill" style="background:{{ $laporan->kategori->warna ?? '#6c757d' }}20; color:{{ $laporan->kategori->warna ?? '#6c757d' }}">
                            {{ $laporan->kategori->nama ?? '-' }}
                        </span>
                    </td>
                    <td class="py-3 text-muted small">{{ $laporan->created_at->format('d M Y') }}</td>
                    <td class="py-3">
                        <span class="badge bg-{{ $laporan->status_color }}-subtle text-{{ $laporan->status_color }} rounded-pill">
                            {{ $laporan->status_label }}
                        </span>
                    </td>
                    <td class="py-3 small text-muted">
                        {{ $laporan->investigation->tindakLanjut->jenis_label ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3">{{ $laporans->links() }}</div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Ringkasan WBS</h3>
            <p class="text-muted small mb-0">Selamat datang kembali, {{ auth()->user()->name }}. Berikut adalah ikhtisar sistem pelaporan.</p>
        </div>
        <div class="d-none d-sm-block">
            <span class="badge bg-white text-muted border py-2 px-3 rounded-pill shadow-sm small">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <!-- Cards Statistik -->
    <div class="row">
        <!-- Total Laporan -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 card-hover">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Total Laporan</span>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['total']) }}</h3>
                    </div>
                    <div class="bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-file-earmark-bar-graph-fill fs-4"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-muted small"><i class="bi bi-info-circle me-1"></i> Semua pengaduan masuk</span>
                </div>
            </div>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 card-hover">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Menunggu Verifikasi</span>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['menunggu']) }}</h3>
                    </div>
                    <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-muted small"><i class="bi bi-clock me-1"></i> Perlu verifikasi tim WBS</span>
                </div>
            </div>
        </div>

        <!-- Dalam Investigasi -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 card-hover">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Dalam Investigasi</span>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['investigasi']) }}</h3>
                    </div>
                    <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-search fs-4"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-muted small"><i class="bi bi-shield-shaded me-1"></i> Sedang ditindaklanjuti</span>
                </div>
            </div>
        </div>

        <!-- Selesai -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 card-hover">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Kasus Selesai</span>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['selesai']) }}</h3>
                    </div>
                    <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-muted small"><i class="bi bi-patch-check me-1"></i> Selesai ditangani</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Investigasi Card -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h5 class="fw-bold text-dark mb-2">Progress Penanganan Kasus</h5>
                <p class="text-muted small mb-0">Visualisasi presentase status penanganan laporan dari total pengaduan yang masuk ke dalam sistem WBS.</p>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-semibold text-muted">Kasus Selesai</span>
                        <span class="small fw-bold text-success">{{ $progress['selesai_count'] }} Laporan ({{ $progress['selesai_percentage'] }}%)</span>
                    </div>
                    <div class="progress rounded-pill" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress['selesai_percentage'] }}%" aria-valuenow="{{ $progress['selesai_percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-semibold text-muted">Kasus Dalam Investigasi</span>
                        <span class="small fw-bold text-primary">{{ $progress['investigasi_count'] }} Laporan ({{ $progress['investigasi_percentage'] }}%)</span>
                    </div>
                    <div class="progress rounded-pill" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $progress['investigasi_percentage'] }}%; background-color: var(--bs-primary);" aria-valuenow="{{ $progress['investigasi_percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Row -->
    <div class="row mb-4">
        <!-- Tren Laporan (Line Chart) -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Tren Pengaduan</h5>
                        <p class="text-muted small mb-0">Jumlah laporan masuk per bulan selama 12 bulan terakhir</p>
                    </div>
                </div>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribusi Pelanggaran (Doughnut Chart) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Kategori Pelanggaran</h5>
                        <p class="text-muted small mb-0">Distribusi jenis pelanggaran yang dilaporkan</p>
                    </div>
                </div>
                <div style="position: relative; height: 250px; width: 100%;" class="d-flex align-items-center justify-content-center">
                    @if(count($category_distribution['data']) > 0)
                        <canvas id="categoryDistributionChart"></canvas>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-pie-chart text-muted display-6 d-block mb-2"></i>
                            <span class="text-muted small">Belum ada data kategori</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Laporan Terbaru -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-1">Laporan Terbaru</h5>
                <p class="text-muted small mb-0">Daftar 5 pengaduan terakhir yang masuk ke dalam sistem WBS</p>
            </div>
            <div>
                <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill small fw-semibold">
                    Terbaru
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle border-0 mb-0">
                <thead>
                    <tr class="text-muted small" style="border-bottom: 2px solid #f2f7ff;">
                        <th class="border-0 pb-3" style="width: 25%;">No. Registrasi</th>
                        <th class="border-0 pb-3">Tanggal Masuk</th>
                        <th class="border-0 pb-3">Kategori</th>
                        <th class="border-0 pb-3" style="width: 20%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_reports as $laporan)
                        <tr style="border-bottom: 1px solid #f2f7ff;">
                            <td class="fw-bold text-dark py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-text text-primary"></i>
                                    {{ $laporan->nomor_registrasi }}
                                </div>
                            </td>
                            <td class="text-muted py-3" style="font-size: 0.9rem;">
                                {{ $laporan->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3" style="font-size: 0.9rem;">
                                <span class="badge rounded-pill px-3 py-2 fw-medium" style="background-color: {{ ($laporan->kategori->warna ?? '#0a4282') . '15' }}; color: {{ $laporan->kategori->warna ?? '#0a4282' }}; border: 1px solid {{ ($laporan->kategori->warna ?? '#0a4282') . '30' }};">
                                    {{ $laporan->kategori->nama ?? 'Lainnya' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-{{ $laporan->status_color }} px-3 py-2 rounded-pill fw-semibold text-capitalize" style="font-size: 0.8rem;">
                                    {{ $laporan->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-muted" style="opacity: 0.5;"></i>
                                <span class="small">Belum ada laporan pengaduan masuk.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-hover {
        transition: transform 0.25s ease, box-shadow 0.25s ease !important;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.06) !important;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
    /* Custom table styles */
    .table > :not(caption) > * > * {
        border-bottom-width: 0;
    }
    tr:last-child {
        border-bottom: 0 !important;
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Line Chart (Tren Laporan Per Bulan) ---
        const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
        
        // Data dari backend
        const trendLabels = @json($monthly_trends['labels']);
        const trendData = @json($monthly_trends['data']);
        
        new Chart(monthlyTrendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Jumlah Pengaduan',
                    data: trendData,
                    borderColor: '#0a4282',
                    backgroundColor: 'rgba(10, 66, 130, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#0a4282',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: '#0b192c',
                        titleFont: { family: 'Inter', size: 13 },
                        bodyFont: { family: 'Inter', size: 12 },
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f2f7ff'
                        },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { family: 'Inter', size: 11 }
                        }
                    }
                }
            }
        });

        // --- 2. Doughnut Chart (Distribusi Pelanggaran Kategori) ---
        @if(count($category_distribution['data']) > 0)
            const categoryDistCtx = document.getElementById('categoryDistributionChart').getContext('2d');
            
            const categoryLabels = @json($category_distribution['labels']);
            const categoryData = @json($category_distribution['data']);
            const categoryColors = @json($category_distribution['colors']);
            
            new Chart(categoryDistCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryData,
                        backgroundColor: categoryColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: { family: 'Inter', size: 11 }
                            }
                        },
                        tooltip: {
                            padding: 12,
                            backgroundColor: '#0b192c',
                            titleFont: { family: 'Inter', size: 13 },
                            bodyFont: { family: 'Inter', size: 12 },
                            cornerRadius: 8
                        }
                    },
                    cutout: '65%'
                }
            });
        @endif
    });
</script>
@endpush

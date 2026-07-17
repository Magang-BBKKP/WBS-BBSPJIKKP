@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Verifikasi Laporan</h3>
            <p class="text-muted small mb-0">Daftar laporan masuk yang membutuhkan pemeriksaan dan verifikasi awal oleh Tim WBS.</p>
        </div>
        <div>
            <span class="badge bg-white text-muted border py-2 px-3 rounded-pill shadow-sm small">
                <i class="bi bi-shield-check me-1"></i> Mode Verifikasi
            </span>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>
                    {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search and Filter Bar -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ route('verifikasi.index') }}" method="GET" id="searchForm" class="row g-2 align-items-center">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light text-muted" id="searchIconContainer">
                        <i class="bi bi-search" id="searchIcon"></i>
                        <div class="spinner-border spinner-border-sm text-primary d-none" id="searchSpinner" role="status" style="width: 1rem; height: 1rem; border-width: 0.15em;"></div>
                    </span>
                    <input type="text" name="search" id="searchInput" class="form-control border-0 bg-light py-2" placeholder="Cari berdasarkan nomor registrasi, judul laporan, atau kategori..." value="{{ $search }}" autocomplete="off">
                </div>
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-primary rounded-3 py-2 fw-semibold">
                    Cari Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Table Container for Real-time Search -->
    <div id="tableContainer">
        <!-- Table Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-dark mb-0">Daftar Antrean Laporan</h5>
                <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill small fw-semibold">
                    {{ $laporans->total() }} Menunggu Tindakan
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border-0 mb-0">
                    <thead>
                        <tr class="text-muted small" style="border-bottom: 2px solid #f2f7ff;">
                            <th class="border-0 pb-3" style="width: 18%;">No. Laporan</th>
                            <th class="border-0 pb-3">Judul Laporan</th>
                            <th class="border-0 pb-3">Tanggal Masuk</th>
                            <th class="border-0 pb-3">Kategori</th>
                            <th class="border-0 pb-3">Pelapor</th>
                            <th class="border-0 pb-3">Prioritas</th>
                            <th class="border-0 pb-3">Status</th>
                            <th class="border-0 pb-3 text-center" style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporans as $laporan)
                            <tr style="border-bottom: 1px solid #f2f7ff;">
                                <td class="fw-bold text-dark py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-file-earmark-text text-primary"></i>
                                        {{ $laporan->nomor_registrasi }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 220px;" title="{{ $laporan->judul }}">
                                        {{ $laporan->judul }}
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
                                <td class="py-3" style="font-size: 0.9rem;">
                                    @if($laporan->is_anonim)
                                        <span class="text-muted fw-medium"><i class="bi bi-eye-slash-fill me-1"></i> Anonim</span>
                                    @else
                                        <span class="text-dark fw-medium" title="{{ $laporan->nama_pelapor }}"><i class="bi bi-person-fill me-1"></i> {{ Str::limit($laporan->nama_pelapor, 12) }}</span>
                                    @endif
                                </td>
                                <td class="py-3 text-muted" style="font-size: 0.9rem;">
                                    -
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-{{ $laporan->status_color }} px-3 py-2 rounded-pill fw-semibold text-capitalize" style="font-size: 0.75rem;">
                                        {{ $laporan->status_label }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <a href="{{ route('verifikasi.show', $laporan->id) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-1 fw-semibold">
                                        Detail <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 text-muted opacity-50"></i>
                                    <span class="fw-medium">
                                        @if(!empty($search))
                                            Tidak ada laporan yang sesuai dengan pencarian "{{ $search }}".
                                        @else
                                            Tidak ada laporan yang menunggu verifikasi saat ini.
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $laporans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table > :not(caption) > * > * {
        border-bottom-width: 0;
    }
    tr:last-child {
        border-bottom: 0 !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const tableContainer = document.getElementById('tableContainer');
    const searchIcon = document.getElementById('searchIcon');
    const searchSpinner = document.getElementById('searchSpinner');
    
    let debounceTimer;
    let currentAbortController = null;

    // Fungsi utama untuk melakukan request pencarian & pembaruan data secara AJAX
    function performSearch(query, pageUrl = null) {
        clearTimeout(debounceTimer);
        
        let url;
        if (pageUrl) {
            url = new URL(pageUrl);
        } else {
            url = new URL(window.location.href);
            url.searchParams.set('search', query);
            url.searchParams.delete('page'); // Reset ke halaman pertama jika melakukan input pencarian baru
        }

        // Perbarui URL di address bar browser secara dinamis tanpa memuat ulang
        window.history.pushState({}, '', url);

        // Gagalkan request pencarian sebelumnya yang masih berjalan (menghindari race condition)
        if (currentAbortController) {
            currentAbortController.abort();
        }

        currentAbortController = new AbortController();
        const activeController = currentAbortController;
        const signal = currentAbortController.signal;

        // Tambahkan efek loading visual pada tabel
        tableContainer.style.opacity = '0.5';

        // Tampilkan spinner loading pada input search
        if (searchIcon && searchSpinner) {
            searchIcon.classList.add('d-none');
            searchSpinner.classList.remove('d-none');
        }

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: signal
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.getElementById('tableContainer');
            if (newTable) {
                tableContainer.innerHTML = newTable.innerHTML;
            }
            
            // Sembunyikan spinner jika ini adalah request aktif terakhir
            if (activeController === currentAbortController) {
                if (searchIcon && searchSpinner) {
                    searchIcon.classList.remove('d-none');
                    searchSpinner.classList.add('d-none');
                }
                tableContainer.style.opacity = '1';
            }
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error('Gagal mengambil data:', err);
            }
            // Sembunyikan spinner jika ini adalah request aktif terakhir
            if (activeController === currentAbortController) {
                if (searchIcon && searchSpinner) {
                    searchIcon.classList.remove('d-none');
                    searchSpinner.classList.add('d-none');
                }
                tableContainer.style.opacity = '1';
            }
        });
    }

    // Eksekusi pencarian instan (tanpa jeda) saat form dikirim (misal klik tombol Cari atau tekan Enter)
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch(searchInput.value);
    });

    // Eksekusi debounced search ketika pengguna sedang mengetik
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            performSearch(searchInput.value);
        }, 300); // Debounce 300ms
    });

    // Intersepsi klik tombol halaman pagination agar tetap berjalan secara AJAX
    tableContainer.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            performSearch(null, link.href);
            // Scroll halus kembali ke bagian atas tabel setelah ganti halaman
            tableContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });
});
</script>
@endpush

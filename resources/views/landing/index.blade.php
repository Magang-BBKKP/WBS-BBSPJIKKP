@extends('layouts.landing')

@section('content')

<!-- Hero Section -->
<section class="hero-section hero-background position-relative">
    <div class="container position-relative z-index-2">
        <div class="row align-items-center hero-content">
            <div class="col-lg-8 col-xl-7">
                <h1 class="hero-title mb-3">
                    BBSPJIKKP Bersih<br>
                    <span>Hebat Tanpa Korupsi.</span>
                </h1>
                <p class="hero-copy mb-4">
                    Berintegritas • Siap Melayani. Media pelaporan resmi untuk dugaan pelanggaran dengan jaminan perlindungan dan kerahasiaan identitas pelapor.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="{{ route('laporan.create') }}" class="btn btn-light btn-lg rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2 fw-medium shadow-sm">
                        Buat Laporan <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('track.index') }}" class="btn btn-hero-outline btn-lg rounded-pill px-4 py-2 fw-medium d-inline-flex align-items-center gap-2">
                        <i class="bi bi-search"></i> Lacak Laporan
                    </a>
                </div>
                <div class="hero-trust-list">
                    <span>
                        <i class="bi bi-lock"></i> Enkripsi Data
                    </span>
                    <span>
                        <i class="bi bi-person-slash"></i> Identitas Terlindungi
                    </span>
                    <span>
                        <i class="bi bi-geo-alt-fill"></i> Yogyakarta
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tentang BBSPJIKKP Bersih -->
<section class="py-5 bg-light border-top border-bottom">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Tentang BBSPJIKKP Bersih</h2>
        <p class="text-muted lead mx-auto" style="max-width: 800px;">
            BBSPJIKKP berkomitmen mewujudkan tata kelola pemerintahan yang baik (Good Governance) melalui pembangunan Zona Integritas menuju Wilayah Bebas dari Korupsi (WBK) dan Wilayah Birokrasi Bersih dan Melayani (WBBM).
        </p>
    </div>
</section>

<!-- Statistik Laporan -->
<section class="report-stats-section py-5 my-5">
    <div class="container">
        @php
            $reportStats = $reportStats ?? [
                ['label' => 'Total Pelapor', 'value' => 0, 'percent' => 0, 'icon' => 'bi-people-fill', 'tone' => 'primary'],
                ['label' => 'Disetujui', 'value' => 0, 'percent' => 0, 'icon' => 'bi-check2-circle', 'tone' => 'success'],
                ['label' => 'Sedang Proses', 'value' => 0, 'percent' => 0, 'icon' => 'bi-hourglass-split', 'tone' => 'warning'],
                ['label' => 'Ditolak', 'value' => 0, 'percent' => 0, 'icon' => 'bi-x-circle', 'tone' => 'danger'],
            ];
        @endphp

        <div class="row align-items-end mb-4 g-3">
            <div class="col-lg-7">
                <h2 class="fw-bold mb-3">Grafik Pelaporan</h2>
                <p class="text-muted mb-0">Ringkasan jumlah pelapor berdasarkan status penanganan laporan yang masuk.</p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <span class="report-stats-badge">
                    <i class="bi bi-bar-chart-fill"></i>
                    Data laporan terkini
                </span>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="report-summary-card h-100">
                    <div class="report-summary-icon mb-4">
                        <i class="bi bi-clipboard2-data"></i>
                    </div>
                    <div class="report-summary-label">Total Pelapor</div>
                    <div class="report-summary-value">{{ number_format($reportStats[0]['value']) }}</div>
                    <p class="report-summary-copy mb-0">
                        Laporan yang masuk melalui kanal WBS dan tercatat di sistem.
                    </p>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="report-chart-card">
                    @foreach($reportStats as $item)
                        <div class="report-chart-row report-chart-{{ $item['tone'] }}">
                            <div class="report-chart-meta">
                                <span class="report-chart-icon"><i class="bi {{ $item['icon'] }}"></i></span>
                                <span class="report-chart-label">{{ $item['label'] }}</span>
                                <strong>{{ number_format($item['value']) }}</strong>
                            </div>
                            <div class="report-chart-track">
                                <span class="report-chart-fill" style="width: {{ max($item['percent'], $item['value'] > 0 ? 8 : 0) }}%;"></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jenis Pelanggaran -->
<section class="py-5 bg-white border-top">
    <div class="container text-center mb-5">
        <h2 class="fw-bold mb-3">Jenis Pelanggaran</h2>
        <p class="text-muted">Kategori pelanggaran yang dapat Anda laporkan melalui sistem ini.</p>
    </div>
    <div class="container">
        <div class="row g-3 justify-content-center">
            @php
                $pelanggaran = [
                    ['icon' => 'bi-cash-coin', 'title' => 'Korupsi'],
                    ['icon' => 'bi-wallet2', 'title' => 'Suap'],
                    ['icon' => 'bi-gift', 'title' => 'Gratifikasi'],
                    ['icon' => 'bi-arrow-left-right', 'title' => 'Benturan Kepentingan'],
                    ['icon' => 'bi-exclamation-triangle', 'title' => 'Kecurangan'],
                    ['icon' => 'bi-bag-x', 'title' => 'Pencurian'],
                    ['icon' => 'bi-file-earmark-lock', 'title' => 'Pembocoran Data'],
                    ['icon' => 'bi-hammer', 'title' => 'Pelanggaran Hukum'],
                    ['icon' => 'bi-calculator', 'title' => 'Pelanggaran Akuntansi'],
                    ['icon' => 'bi-person-x', 'title' => 'Pelanggaran Etika'],
                ];
            @endphp
            @foreach($pelanggaran as $item)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm bg-light-blue text-center p-3 rounded-4">
                    <div class="card-body p-2">
                        <i class="bi {{ $item['icon'] }} fs-2 text-primary mb-2"></i>
                        <h6 class="fw-bold mb-0 mt-2">{{ $item['title'] }}</h6>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Cara Melapor (The Process) -->
<section id="process" class="py-5 my-5 bg-light-blue rounded-top-5">
    <div class="container text-center mb-5 pt-4">
        <h2 class="fw-bold mb-3">Cara Melapor</h2>
        <p class="text-muted">Ikuti langkah sederhana berikut untuk mengirimkan laporan.</p>
    </div>
    
    <div class="container pb-5">
        <div class="row position-relative">
            <!-- Connecting Line -->
            <div class="position-absolute top-50 start-50 translate-middle w-75 border-top border-2 border-primary-soft d-none d-md-block" style="z-index: 1;"></div>
            
            <!-- Step 1 -->
            <div class="col-md-4 text-center position-relative z-index-2 mb-5 mb-md-0">
                <div class="step-icon bg-white text-primary border border-2 border-primary-soft rounded-circle d-inline-flex align-items-center justify-content-center mb-4 mx-auto" style="width: 80px; height: 80px;">
                    <i class="bi bi-pencil-square fs-3"></i>
                </div>
                <h5 class="fw-bold mb-3">1. Isi Formulir</h5>
                <p class="text-muted small px-lg-4">Isi data laporan, jelaskan kronologi, dan unggah bukti pendukung (dapat dilakukan secara anonim).</p>
            </div>
            
            <!-- Step 2 -->
            <div class="col-md-4 text-center position-relative z-index-2 mb-5 mb-md-0">
                <div class="step-icon bg-white text-primary border border-2 border-primary-soft rounded-circle d-inline-flex align-items-center justify-content-center mb-4 mx-auto" style="width: 80px; height: 80px;">
                    <i class="bi bi-upc-scan fs-3"></i>
                </div>
                <h5 class="fw-bold mb-3">2. Simpan Nomor Registrasi</h5>
                <p class="text-muted small px-lg-4">Simpan baik-baik Nomor Registrasi yang diberikan. Nomor ini digunakan untuk melacak laporan Anda.</p>
            </div>
            
            <!-- Step 3 -->
            <div class="col-md-4 text-center position-relative z-index-2">
                <div class="step-icon bg-white text-primary border border-2 border-primary-soft rounded-circle d-inline-flex align-items-center justify-content-center mb-4 mx-auto" style="width: 80px; height: 80px;">
                    <i class="bi bi-search fs-3"></i>
                </div>
                <h5 class="fw-bold mb-3">3. Pantau Proses</h5>
                <p class="text-muted small px-lg-4">Gunakan fitur "Track" untuk memantau status, verifikasi, investigasi, hingga tindak lanjut laporan.</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 my-5">
    <div class="container text-center mb-5">
        <h2 class="fw-bold mb-3">Frequently Asked Questions</h2>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion accordion-flush custom-accordion" id="faqAccordion">
                    
                    <div class="accordion-item bg-white border rounded-3 mb-3 px-3 py-2 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold text-dark bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true">
                                Apakah identitas saya benar-benar dirahasiakan?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small pt-0 pb-3 lh-lg">
                                Ya. Kami menyediakan opsi Pelaporan Anonim di mana Anda tidak perlu mengisi data diri Anda. Namun disarankan agar Anda memberikan kontak (email/no HP) untuk keperluan permintaan klarifikasi oleh Tim WBS tanpa mengungkap identitas Anda ke publik.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item bg-white border rounded-3 mb-3 px-3 py-2 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Berapa lama laporan saya akan diproses?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small pt-0 pb-3 lh-lg">
                                Laporan Anda akan segera diverifikasi oleh Tim WBS. Jika dinyatakan valid, Kepala BBSPJIKKP akan membentuk Tim Investigasi. Durasi proses tergantung pada kompleksitas pelanggaran dan bukti yang dilampirkan. Anda dapat terus memantau statusnya di halaman Track.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item bg-white border rounded-3 mb-3 px-3 py-2 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Bukti seperti apa yang harus saya unggah?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small pt-0 pb-3 lh-lg">
                                Anda dapat mengunggah dokumen (PDF, Word, Excel), Foto, maupun Video/Audio yang memperkuat laporan Anda. Sistem mendukung pengunggahan banyak file dengan ukuran maksimal tertentu yang telah ditentukan.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kontak & CTA Bottom Section -->
<section id="kontak" class="py-5 mb-5">
    <div class="container">
        <div class="contact-card">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="contact-eyebrow mb-3">Kontak Resmi</div>
                    <h2 class="contact-title mb-3">Kontak Kami</h2>
                    <p class="contact-copy mb-4">
                        Hubungi BBSPJIKKP untuk informasi layanan atau gunakan kanal pelaporan untuk menyampaikan dugaan pelanggaran secara aman.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('laporan.create') }}" class="btn btn-light btn-lg rounded-pill px-4 py-2 fw-medium d-inline-flex align-items-center gap-2">
                            Buat Laporan <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('track.index') }}" class="btn btn-contact-outline btn-lg rounded-pill px-4 py-2 fw-medium d-inline-flex align-items-center gap-2">
                            <i class="bi bi-search"></i> Lacak Laporan
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="contact-info-panel">
                        <div class="contact-info-item">
                            <span class="contact-info-icon"><i class="bi bi-geo-alt-fill"></i></span>
                            <div>
                                <div class="contact-info-label">Alamat</div>
                                <div class="contact-info-text">Jl. Sokonandi No.9, Yogyakarta, Indonesia 55166</div>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <span class="contact-info-icon"><i class="bi bi-envelope-fill"></i></span>
                            <div>
                                <div class="contact-info-label">Email</div>
                                <div class="contact-info-text">bbkkp_jogja@kemenperin.go.id</div>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <span class="contact-info-icon"><i class="bi bi-telephone-fill"></i></span>
                            <div>
                                <div class="contact-info-label">Telepon</div>
                                <div class="contact-info-text">(0274) 512929</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

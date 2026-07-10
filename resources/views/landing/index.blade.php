@extends('layouts.landing')

@section('content')

<!-- Hero Section -->
<section class="hero-section py-5 position-relative">
    <div class="container py-5">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5">
                <span class="badge bg-primary-soft text-primary rounded-pill px-3 py-2 mb-4 d-inline-flex align-items-center gap-2 fw-medium">
                    <i class="bi bi-shield-check"></i> 100% Rahasia & Aman
                </span>
                <h1 class="display-4 fw-bold mb-3 text-dark">
                    BBSPJIKKP Bersih<br>
                    <span class="text-primary fs-2">Hebat Tanpa Korupsi.</span>
                </h1>
                <p class="lead text-muted mb-4 pe-lg-4 fs-6 lh-lg">
                    Berintegritas • Siap Melayani. Media pelaporan resmi untuk dugaan pelanggaran dengan jaminan perlindungan dan kerahasiaan identitas pelapor.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="{{ route('laporan.create') }}" class="btn btn-primary btn-lg rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2 fw-medium">
                        Buat Laporan <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#track" class="btn btn-outline-primary btn-lg rounded-pill px-4 py-2 fw-medium btn-track">
                        Lacak Laporan
                    </a>
                </div>
                <div class="d-flex flex-wrap gap-4 small text-muted">
                    <span class="d-inline-flex align-items-center gap-2">
                        <i class="bi bi-lock"></i> Enkripsi Data
                    </span>
                    <span class="d-inline-flex align-items-center gap-2">
                        <i class="bi bi-person-slash"></i> Identitas Terlindungi
                    </span>
                </div>
            </div>
            
            <div class="col-lg-6 position-relative">
                <div class="hero-card bg-white p-5 rounded-4 shadow-lg text-center mx-auto">
                    <div class="icon-circle bg-primary-soft text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4">
                        <i class="bi bi-shield-shaded fs-1"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-2">Integritas Terjaga</h3>
                    <p class="text-muted small mb-0">Dipantau langsung oleh Tim WBS BBSPJIKKP</p>
                </div>
                <!-- Decorative blurred shapes -->
                <div class="blob-1 position-absolute rounded-circle bg-primary opacity-10"></div>
                <div class="blob-2 position-absolute rounded-circle bg-info opacity-10"></div>
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

<!-- Komitmen Integritas (Bento Grid) -->
<section class="py-5 my-5">
    <div class="container text-center mb-5">
        <h2 class="fw-bold mb-3">Komitmen Integritas</h2>
        <p class="text-muted">Prinsip utama kami dalam menangani setiap laporan yang masuk.</p>
    </div>
    
    <div class="container">
        <div class="row g-4">
            <!-- Kerahasiaan Identitas -->
            <div class="col-lg-8">
                <div class="bento-card bg-white rounded-4 p-4 p-lg-5 h-100 shadow-sm border position-relative overflow-hidden">
                    <div class="row h-100">
                        <div class="col-md-7 d-flex flex-column justify-content-center">
                            <h3 class="fw-bold mb-3 text-primary letter-spacing-wide">
                                <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill fs-6 fw-bold">R A H A S I A</span>
                            </h3>
                            <h4 class="fw-bold mb-3">Kerahasiaan Identitas</h4>
                            <p class="text-muted small lh-lg mb-0">Anda dapat melapor secara anonim. Sistem kami secara otomatis mengamankan data dan tidak akan mempublikasikan informasi identitas Anda kepada pihak yang dilaporkan.</p>
                        </div>
                        <div class="col-md-5 d-none d-md-block position-relative">
                            <div class="bento-image-placeholder rounded shadow-sm w-100 h-100" style="background: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=500&q=80') center/cover; min-height:180px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Independen -->
            <div class="col-lg-4">
                <div class="bento-card bg-dark-blue text-white rounded-4 p-4 p-lg-5 h-100 shadow-sm position-relative">
                    <div class="icon-sm bg-white-10 rounded d-inline-flex align-items-center justify-content-center mb-4 p-2">
                        <i class="bi bi-person-check text-info"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Investigasi Independen</h4>
                    <p class="text-white-50 small lh-lg mb-5">Laporan ditangani oleh Tim Investigator yang independen dan profesional berdasarkan SOP resmi BBSPJIKKP.</p>
                    <div class="position-absolute bottom-0 start-0 w-100 p-4 p-lg-5 d-flex justify-content-between align-items-center border-top border-secondary border-opacity-25 mt-auto">
                        <span class="small text-white-50">Transparan & Obyektif</span>
                        <span class="status-dot bg-success rounded-circle" style="width:8px;height:8px;"></span>
                    </div>
                </div>
            </div>
            
            <!-- Transparansi -->
            <div class="col-lg-6">
                <div class="bento-card bg-white rounded-4 p-4 shadow-sm border h-100">
                    <div class="icon-sm bg-primary-soft text-primary rounded d-inline-flex align-items-center justify-content-center mb-4 p-2">
                        <i class="bi bi-eye"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Transparansi Proses</h5>
                    <p class="text-muted small lh-lg mb-0">Setiap pelapor dapat memantau status laporannya secara real-time menggunakan Nomor Registrasi yang diberikan, hingga laporan ditindaklanjuti.</p>
                </div>
            </div>
            
            <!-- Perlindungan -->
            <div class="col-lg-6">
                <div class="bento-card bg-white rounded-4 p-4 shadow-sm border h-100">
                    <div class="icon-sm bg-primary-soft text-primary rounded d-inline-flex align-items-center justify-content-center mb-4 p-2">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Perlindungan Pelapor</h5>
                    <p class="text-muted small lh-lg mb-0">BBSPJIKKP memberikan komitmen penuh terhadap perlindungan pelapor dari segala bentuk tindakan balasan atau diskriminasi.</p>
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
        <div class="bg-dark-blue rounded-4 p-5 text-center shadow-lg position-relative overflow-hidden">
            <!-- Decorative Blobs -->
            <div class="position-absolute top-0 start-0 w-50 h-100 bg-white opacity-5 rounded-circle" style="transform: translate(-30%, -20%); blur: 40px;"></div>
            
            <div class="position-relative z-index-2 py-4">
                <h2 class="display-5 fw-bold text-white mb-3">Kontak Kami</h2>
                <p class="text-white-50 mb-4 fs-6 mx-auto" style="max-width: 600px;">
                    Jalan Sokonandi No. 9, Semaki, Umbulharjo, Yogyakarta 55166<br>
                    Email: wbssupport@bbspjekkp.go.id | Telp: (0274) 512929
                </p>
                <div class="d-flex justify-content-center flex-wrap gap-3 mt-5">
                    <a href="{{ route('laporan.create') }}" class="btn btn-light btn-lg rounded-pill px-4 py-2 fw-medium">
                        Buat Laporan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

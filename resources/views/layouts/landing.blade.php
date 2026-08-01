<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WBS BBSPJIKKP - Speak Up, Protect Integrity</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
    
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top">
        <div class="container py-2">
            <a class="navbar-brand p-0 d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-bbspjikkp.png') }}" alt="Logo BBSPJIKKP" style="height: 42px; width: auto;" class="img-fluid">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link fw-medium {{ request()->routeIs('home') ? 'active text-primary' : 'text-muted' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium {{ request()->routeIs('laporan.*') ? 'active text-primary' : 'text-muted' }}" href="{{ route('laporan.create') }}">Lapor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium {{ request()->routeIs('track.*') ? 'active text-primary' : 'text-muted' }}" href="{{ route('track.index') }}">Lacak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium text-muted" href="{{ request()->routeIs('home') ? '#kontak' : route('home').'#kontak' }}">Kontak</a>
                    </li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <button type="button" class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center p-2 text-primary border" data-bs-toggle="offcanvas" data-bs-target="#accessibilityOffcanvas" title="Fitur Aksesibilitas" style="width: 36px; height: 36px;">
                    <i class="bi bi-universal-access fs-5"></i>
                </button>
                
                <div class="d-none d-lg-flex align-items-center ms-1">
                    <span class="text-muted small me-1">ID</span>
                    <i class="bi bi-globe text-muted"></i>
                </div>
                @auth
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none dropdown-toggle text-dark d-flex align-items-center gap-2 p-0 animate-fade-in" type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="rounded-circle object-fit-cover shadow-sm" style="width: 32px; height: 32px;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                </div>
                            @endif
                            <span class="d-none d-md-inline fw-medium text-muted" style="font-size: 0.95rem;">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 mt-2" aria-labelledby="userMenuDropdown">
                            <li>
                                <div class="px-3 py-2 border-bottom">
                                    <p class="mb-0 small text-muted">Masuk sebagai</p>
                                    <p class="mb-0 fw-bold small text-truncate" style="max-width: 180px;">{{ auth()->user()->email }}</p>
                                </div>
                            </li>
                            @can('view-dashboard')
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-muted" href="{{ route('dashboard') }}">
                                        <i class="bi bi-grid-fill text-primary"></i> Dashboard
                                    </a>
                                </li>
                            @endcan
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-muted" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-gear"></i> Profil Saya
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-4 rounded-pill fw-medium ms-2">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer pt-5 pb-4 mt-5 text-white" style="background-color: #2b70f0;">
        <div class="container">
            <div class="row gy-4 mb-4">
                <!-- Column 1 -->
                <div class="col-lg-4 col-md-6 pe-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('images/logo-bbspjikkp.png') }}" alt="Logo BBSPJIKKP" style="height: 40px;" class="bg-white p-1 rounded">
                        <div class="fw-bold lh-sm fs-5">BBSPJIKKP<br><small class="fw-normal" style="font-size: 0.6rem;">Balai Besar Standardisasi dan Pelayanan Jasa<br>Industri Kulit, Karet, dan Plastik</small></div>
                    </div>
                    <p class="small mb-4 opacity-75">
                        Balai Besar Standardisasi dan Pelayanan Jasa Industri Kulit, Karet, dan Plastik
                    </p>
                    <div class="small mb-2 d-flex gap-2">
                        <i class="bi bi-geo-alt-fill mt-1"></i>
                        <span>Jl. Sokonandi No.9, Yogyakarta<br>Indonesia 55166</span>
                    </div>
                    <div class="small mb-2 d-flex gap-2 align-items-center">
                        <i class="bi bi-telephone-fill"></i>
                        <span>+62 274 512 929</span>
                    </div>
                    <div class="small mb-4 d-flex gap-2 align-items-center">
                        <i class="bi bi-envelope-fill"></i>
                        <span>bbkkp_jogja@kemenperin.go.id</span>
                    </div>
                    
                    <div class="card bg-white bg-opacity-10 border-0 rounded-3 text-white p-3">
                        <div class="d-flex align-items-center gap-2 mb-3 fw-bold small">
                            <i class="bi bi-clock"></i> JAM PELAYANAN
                        </div>
                        <ul class="list-unstyled small mb-0 opacity-75" style="font-size: 0.8rem;">
                            <li class="d-flex justify-content-between mb-2">
                                <span><span class="text-success fw-bold me-1">&bull;</span>Senin - Kamis</span> <span>08:00 - 15:30</span>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span><span class="text-success fw-bold me-1">&bull;</span>Jumat</span> <span>08:00 - 16:00</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span><span class="text-danger fw-bold me-1">&bull;</span>Sabtu, Ahad</span> <span>Tutup</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Column 2 - Navigasi -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3 mt-2">NAVIGASI</h6>
                    <ul class="list-unstyled small footer-links lh-lg">
                        <li><a href="{{ route('home') }}" class="text-white text-decoration-none opacity-75 footer-link">Beranda</a></li>
                        <li><a href="{{ route('laporan.create') }}" class="text-white text-decoration-none opacity-75 footer-link">Lapor</a></li>
                        <li><a href="{{ route('track.index') }}" class="text-white text-decoration-none opacity-75 footer-link">Lacak</a></li>
                        <li><a href="{{ route('home') }}#kontak" class="text-white text-decoration-none opacity-75 footer-link">Kontak</a></li>
                    </ul>
                </div>

                <!-- Column 3 - Layanan Terhubung / Partner Logos -->
                <div class="col-lg-5 col-md-12 mt-3 mt-lg-0">
                    <h6 class="fw-bold mb-3 mt-2">LAYANAN TERHUBUNG</h6>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <img src="{{ asset('images/logo-kemenperin-putih.png') }}" alt="Kementerian Perindustrian" style="max-height: 42px; width: auto;" class="img-fluid me-2 mb-2" onerror="this.onerror=null; this.outerHTML='<span class=\'fw-bold small me-2 mb-2\'>Kemenperin</span>';">
                        <img src="{{ asset('images/logo-lapor-putih.png') }}" alt="LAPOR!" style="max-height: 34px; width: auto;" class="img-fluid me-2 mb-2" onerror="this.onerror=null; this.outerHTML='<span class=\'fw-bold small me-2 mb-2\'>LAPOR!</span>';">
                        <img src="{{ asset('images/logo-sippn-putih.png') }}" alt="SIPPN" style="max-height: 34px; width: auto;" class="img-fluid me-2 mb-2" onerror="this.onerror=null; this.outerHTML='<span class=\'fw-bold small me-2 mb-2\'>SIPPN</span>';">
                        <img src="{{ asset('images/logo-berakhlak-putih.png') }}" alt="BerAKHLAK" style="max-height: 38px; width: auto;" class="img-fluid me-2 mb-2" onerror="this.onerror=null; this.outerHTML='<span class=\'fw-bold small me-2 mb-2\'>BerAKHLAK</span>';">
                        <img src="{{ asset('images/logo-bmb-putih.png') }}" alt="Bangga Melayani Bangsa" style="max-height: 38px; width: auto;" class="img-fluid mb-2" onerror="this.onerror=null; this.outerHTML='<span class=\'fw-bold small mb-2\'>Bangga Melayani Bangsa</span>';">
                    </div>
                </div>
            </div>
            
            <hr class="border-white opacity-25">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-2 small me-md-5 pe-md-4">
                <div class="opacity-75 mb-3 mb-md-0">
                    &copy; 2025 - Balai Besar Standardisasi dan Pelayanan Jasa Industri Kulit, Karet, dan Plastik. Hak cipta dilindungi.
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-medium">Media Sosial</span>
                    <a href="#" class="btn btn-sm btn-light p-0 text-primary d-inline-flex align-items-center justify-content-center bg-white" style="width:28px;height:28px;"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn btn-sm btn-light p-0 text-dark d-inline-flex align-items-center justify-content-center bg-white" style="width:28px;height:28px;"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="btn btn-sm btn-light p-0 text-primary d-inline-flex align-items-center justify-content-center bg-white" style="width:28px;height:28px;"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-sm btn-light p-0 text-danger d-inline-flex align-items-center justify-content-center bg-white" style="width:28px;height:28px;"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Floating WhatsApp -->
    <a href="https://wa.me/62274512929" target="_blank" class="btn btn-success rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="position: fixed; bottom: 25px; right: 25px; width: 56px; height: 56px; z-index: 1050; border: 3px solid white;" title="Bantuan WhatsApp">
        <i class="bi bi-whatsapp fs-3 text-white"></i>
    </a>

    <!-- Accessibility Widget Include -->
    @include('partials.accessibility-widget')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const isHomePage = window.location.pathname === '/' || window.location.pathname === '';
        
        if (isHomePage) {
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            const kontakSection = document.getElementById('kontak');
            
            function updateActiveState() {
                const scrollPos = window.scrollY + (window.innerHeight / 2);
                const isAtKontak = kontakSection && scrollPos >= kontakSection.offsetTop;
                
                navLinks.forEach(link => {
                    const href = link.getAttribute('href');
                    
                    if (href && href.includes('#kontak')) {
                        if (isAtKontak) {
                            link.classList.add('active', 'text-primary');
                            link.classList.remove('text-muted');
                        } else {
                            link.classList.remove('active', 'text-primary');
                            link.classList.add('text-muted');
                        }
                    } 
                    else if (href === '{{ route("home") }}') {
                        if (!isAtKontak) {
                            link.classList.add('active', 'text-primary');
                            link.classList.remove('text-muted');
                        } else {
                            link.classList.remove('active', 'text-primary');
                            link.classList.add('text-muted');
                        }
                    }
                });
            }
            
            // Listeners
            window.addEventListener('scroll', updateActiveState);
            window.addEventListener('hashchange', updateActiveState);
            
            // Initial check in case loaded with hash
            setTimeout(updateActiveState, 100);
        }
    });
    </script>

    @stack('scripts')
</body>
</html>

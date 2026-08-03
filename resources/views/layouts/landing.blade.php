<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WBS BBSPJIKKP - Speak Up, Protect Integrity</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Icon Only.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo Icon Only.png') }}">
    
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
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top" style="z-index: 1040;">
        <div class="container py-2">
            <a class="navbar-brand p-0 d-flex align-items-center me-3 position-relative" style="z-index: 1050;" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-bbspjikkp.png') }}" alt="Logo BBSPJIKKP" style="height: 48px; width: auto;" class="img-fluid">
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
    <footer class="footer mt-5 text-white">
        <div class="container">
            <div class="footer-main">
                <div class="row gy-4 align-items-start">
                    <div class="col-lg-5 col-md-7">
                        <div class="footer-brand d-flex align-items-center gap-3 mb-3">
                            <div class="footer-logo">
                                <img src="{{ asset('images/logo-bbspjikkp.png') }}" alt="Logo BBSPJIKKP">
                            </div>
                            <div>
                                <div class="fw-bold fs-5 lh-sm">BBSPJIKKP Bersih</div>
                                <div class="footer-brand-subtitle">Whistleblowing System Resmi</div>
                            </div>
                        </div>
                        <p class="footer-description mb-4">
                            Balai Besar Standardisasi dan Pelayanan Jasa Industri Kulit, Karet, dan Plastik.
                        </p>

                        <div class="footer-contact-list">
                            <div class="footer-contact-item">
                                <span class="footer-contact-icon"><i class="bi bi-geo-alt-fill"></i></span>
                                <span>Jl. Sokonandi No.9, Yogyakarta, Indonesia 55166</span>
                            </div>
                            <div class="footer-contact-item">
                                <span class="footer-contact-icon"><i class="bi bi-telephone-fill"></i></span>
                                <span>+62 274 512 929</span>
                            </div>
                            <div class="footer-contact-item">
                                <span class="footer-contact-icon"><i class="bi bi-envelope-fill"></i></span>
                                <span>bbkkp_jogja@kemenperin.go.id</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-5">
                        <h6 class="footer-title">Navigasi</h6>
                        <ul class="list-unstyled footer-links mb-0">
                            <li><a href="{{ route('home') }}" class="footer-link">Beranda</a></li>
                            <li><a href="{{ route('laporan.create') }}" class="footer-link">Buat Laporan</a></li>
                            <li><a href="{{ route('track.index') }}" class="footer-link">Lacak Laporan</a></li>
                            <li><a href="{{ route('home') }}#kontak" class="footer-link">Kontak</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-4 col-md-12">
                        <div class="footer-service-box">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="footer-service-icon"><i class="bi bi-clock"></i></span>
                                <h6 class="footer-title mb-0">Jam Pelayanan</h6>
                            </div>
                            <ul class="list-unstyled footer-hours mb-4">
                                <li><span>Senin - Kamis</span><strong>08:00 - 15:30</strong></li>
                                <li><span>Jumat</span><strong>08:00 - 16:00</strong></li>
                                <li><span>Sabtu, Ahad</span><strong>Tutup</strong></li>
                            </ul>

                            <h6 class="footer-title mb-3">Layanan Terhubung</h6>
                            <div class="footer-partners">
                                <img src="{{ url('/images/logo-kemenperin-putih.png') }}" alt="Kementerian Perindustrian">
                                <img src="{{ url('/images/logo-lapor-putih.png') }}" alt="LAPOR!">
                                <img src="{{ url('/images/logo-sippn-putih.png') }}" alt="SIPPN">
                                <img src="{{ url('/images/logo-berakhlak-putih.png') }}" alt="BerAKHLAK">
                                <img src="{{ url('/images/logo-bmb-putih.png') }}" alt="Bangga Melayani Bangsa">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3">
                <div class="footer-copyright">
                    &copy; 2025 - Balai Besar Standardisasi dan Pelayanan Jasa Industri Kulit, Karet, dan Plastik. Hak cipta dilindungi.
                </div>
                <div class="footer-social d-flex align-items-center gap-2">
                    <span>Media Sosial</span>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
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

    // Client-side DOM Injection for Header Logo & Hero Gedung Image
    (function() {
        function applyImageDOM() {
            // 1. DOM manipulation for Navbar Brand Header Logo
            const navbarBrand = document.querySelector('.navbar-brand');
            if (navbarBrand) {
                let logoImg = navbarBrand.querySelector('img');
                if (!logoImg) {
                    logoImg = document.createElement('img');
                    logoImg.className = 'img-fluid';
                    logoImg.alt = 'Logo BBSPJIKKP';
                    navbarBrand.prepend(logoImg);
                }
                logoImg.setAttribute('src', '/images/logo-bbspjikkp.png');
                logoImg.style.height = '48px';
                logoImg.style.width = 'auto';
                logoImg.style.display = 'block';
                logoImg.style.visibility = 'visible';
                logoImg.style.opacity = '1';
            }

            // 2. DOM manipulation for Hero Section Gedung Image
            const heroImg = document.querySelector('.hero-image-wrapper img');
            if (heroImg) {
                heroImg.setAttribute('src', '/images/gedung-bbspjikkp.jpg');
                heroImg.style.height = '420px';
                heroImg.style.width = '100%';
                heroImg.style.objectFit = 'cover';
                heroImg.style.display = 'block';
                heroImg.style.visibility = 'visible';
                heroImg.style.opacity = '1';
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyImageDOM);
        } else {
            applyImageDOM();
        }
    })();
    </script>

    @stack('scripts')
</body>
</html>

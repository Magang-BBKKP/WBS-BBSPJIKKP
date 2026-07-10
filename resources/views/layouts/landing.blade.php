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
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top">
        <div class="container py-2">
            <a class="navbar-brand fw-bold text-primary" href="#">
                WBS BBSPJIKKP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active fw-medium" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium text-muted" href="{{ route('laporan.create') }}">Lapor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium text-muted" href="{{ route('track.index') }}">Lacak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium text-muted" href="#kontak">Kontak</a>
                    </li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-lg-block">
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
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-4 rounded-pill fw-medium">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="mt-5 pt-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-4 mt-5">
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-primary fw-bold mb-2 mb-md-0">
                WBS BBSPJIKKP
                <div class="text-muted small fw-normal mt-1">&copy; 2026 Government Whistleblowing System. All Rights Reserved.</div>
            </div>
            <div class="d-flex gap-4 small fw-medium text-muted">
                <a href="#" class="text-decoration-none text-muted">Privacy Policy</a>
                <a href="#" class="text-decoration-none text-muted">Accessibility</a>
                <a href="#" class="text-decoration-none text-muted">Contact Support</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>

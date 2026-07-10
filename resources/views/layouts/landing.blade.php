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
                        <a class="nav-link fw-medium text-muted" href="#track">Lacak</a>
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
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm px-4 rounded-pill fw-medium">Dashboard</a>
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

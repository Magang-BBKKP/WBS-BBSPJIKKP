<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - WBS BBSPJIKKP</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --bs-primary: #0a4282;
            --bs-primary-soft: #eaf1ff;
            --bs-info: #00d2ff;
            --bs-dark-blue: #0b192c;
            --bs-light-blue: #f2f7ff;
            --bs-gray-text: #6c757d;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafbfe;
            color: #2b3543;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }

        .bg-hover-light {
            color: #495057 !important;
            transition: all 0.2s ease;
        }

        .bg-hover-light:hover {
            background-color: var(--bs-light-blue);
            color: var(--bs-primary) !important;
        }

        .nav-pills .nav-link {
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .nav-pills .nav-link.active {
            background-color: var(--bs-primary) !important;
            color: #fff !important;
        }

        /* Sidebar Styling */
        .sidebar {
            position: sticky;
            top: 90px;
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn {
            transition: all 0.3s ease;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top">
        <div class="container py-2">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center gap-2" href="{{ route('home') }}">
                <i class="bi bi-shield-check-fill fs-4"></i> WBS BBSPJIKKP
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="d-flex align-items-center gap-3 ms-auto ms-lg-0 order-lg-last">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none dropdown-toggle text-dark d-flex align-items-center gap-2 p-0" type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="rounded-circle object-fit-cover shadow-sm" style="width: 32px; height: 32px;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                </div>
                            @endif
                            <span class="d-none d-md-inline fw-medium" style="font-size: 0.95rem;">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3 mt-2" aria-labelledby="userMenuDropdown">
                            <li>
                                <div class="px-3 py-2 border-bottom">
                                    <p class="mb-0 small text-muted">Masuk sebagai</p>
                                    <p class="mb-0 fw-bold small text-truncate" style="max-width: 180px;">{{ auth()->user()->email }}</p>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-muted" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-gear"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-muted" href="{{ route('home') }}">
                                    <i class="bi bi-house"></i> Landing Page
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
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="container-fluid mt-5 pt-4">
        <div class="container py-4">
            <div class="row">
                <!-- Sidebar Sub-nav -->
                <aside class="col-lg-3 col-md-4 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white sidebar">
                        <!-- User quick profile in sidebar -->
                        <div class="d-flex align-items-center gap-3 mb-4 p-2 pb-3 border-bottom">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="rounded-circle object-fit-cover shadow-sm" style="width: 48px; height: 48px;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                </div>
                            @endif
                            <div class="overflow-hidden">
                                <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.95rem;">{{ auth()->user()->name }}</h6>
                                <span class="badge bg-primary-soft text-primary small fw-semibold text-capitalize" style="font-size: 0.75rem;">
                                    {{ auth()->user()->roles->first()->name ?? 'User' }}
                                </span>
                            </div>
                        </div>

                        <!-- Sidebar menu links -->
                        <div class="nav flex-column nav-pills gap-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('dashboard') ? 'active' : 'bg-hover-light' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-grid-fill"></i> Dashboard
                            </a>
                            
                            <!-- Placeholder menus for WBS Modules (Step 1 requirement) -->
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 bg-hover-light disabled text-muted" href="#" tabindex="-1" aria-disabled="true" style="opacity: 0.65;">
                                <i class="bi bi-file-earmark-text"></i> Laporan Masuk
                            </a>
                            
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 bg-hover-light disabled text-muted" href="#" tabindex="-1" aria-disabled="true" style="opacity: 0.65;">
                                <i class="bi bi-shield-check"></i> Investigasi
                            </a>

                            <!-- User Management -->
                            @can('view-user')
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('users.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i> User Management
                            </a>
                            @endcan

                            <hr class="my-2 text-muted">

                            <!-- Profile Settings -->
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('profile.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-gear"></i> Pengaturan Profil
                            </a>
                        </div>
                    </div>
                </aside>

                <!-- Page Content -->
                <main class="col-lg-9 col-md-8">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

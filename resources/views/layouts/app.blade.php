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
            --sidebar-active-bg: linear-gradient(135deg, #0a4282, #185a9d);
            --card-shadow: 0 10px 30px -5px rgba(10, 66, 130, 0.05), 0 4px 12px -2px rgba(10, 66, 130, 0.03);
            --card-shadow-hover: 0 20px 40px -5px rgba(10, 66, 130, 0.1), 0 8px 20px -2px rgba(10, 66, 130, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f6f8fb;
            color: #2b3543;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 2px 15px rgba(10, 66, 130, 0.03);
        }

        .card {
            border: none !important;
            box-shadow: var(--card-shadow) !important;
            border-radius: 16px !important;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            box-shadow: var(--card-shadow-hover) !important;
        }

        /* Sidebar Styling */
        .sidebar {
            position: sticky;
            top: 90px;
            border: 1px solid rgba(10, 66, 130, 0.05) !important;
        }

        .bg-hover-light {
            color: #495057 !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border-left: 3px solid transparent;
        }

        .bg-hover-light:hover {
            background-color: var(--bs-primary-soft);
            color: var(--bs-primary) !important;
            transform: translateX(4px);
            border-left: 3px solid var(--bs-info);
        }

        .nav-pills .nav-link {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
            padding: 0.75rem 1rem;
            border-radius: 10px !important;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .nav-pills .nav-link i {
            font-size: 1.1rem;
            transition: transform 0.25s ease;
        }

        .nav-pills .nav-link:hover i {
            transform: scale(1.1);
        }

        .nav-pills .nav-link.active {
            background: var(--sidebar-active-bg) !important;
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(10, 66, 130, 0.25);
            border-left: 3px solid var(--bs-info);
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover {
            background-color: #083468;
            border-color: #083468;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(10, 66, 130, 0.15);
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
                            <span class="d-none d-md-inline fw-medium" style="font-size: 0.95rem;">{{ ucwords(auth()->user()->name) }}</span>
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
                        <!-- Sidebar menu links -->
                        <div class="nav flex-column nav-pills gap-1" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <!-- Pengaturan Profil -->
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('profile.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-gear"></i> Pengaturan Profil
                            </a>

                            <hr class="my-2 text-muted opacity-25">

                            @can('view-dashboard')
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('dashboard') ? 'active' : 'bg-hover-light' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-grid-fill"></i> Dashboard
                            </a>
                            @endcan

                            {{-- Laporan Saya (Pelapor / user biasa) --}}
                            @if(!auth()->user()->hasAnyRole(['super-admin','tim-wbs','investigator','kepala-bbspjikkp']))
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('laporan.saya') ? 'active' : 'bg-hover-light' }}" href="{{ route('laporan.saya') }}">
                                <i class="bi bi-file-earmark-person"></i> Laporan Saya
                            </a>
                            @endif

                            {{-- Verifikasi (Tim WBS) --}}
                            @can('view-verifikasi')
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('verifikasi.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('verifikasi.index') }}">
                                <i class="bi bi-shield-check"></i> Verifikasi Laporan
                            </a>
                            @endcan

                            {{-- Pembentukan Tim Investigasi (Kepala & Super Admin) --}}
                            @can('approve-investigasi')
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('kepala.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('kepala.index') }}">
                                <i class="bi bi-person-check"></i> Pembentukan Tim Investigasi
                            </a>
                            @endcan

                            {{-- Investigasi --}}
                            @hasanyrole('investigator|super-admin')
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('investigations.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('investigations.index') }}">
                                <i class="bi bi-search"></i> Investigasi
                            </a>
                            @endhasanyrole

                            {{-- Tindak Lanjut (Kepala & Super Admin) --}}
                            @can('view-tindak-lanjut')
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('tindak-lanjut.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('tindak-lanjut.index') }}">
                                <i class="bi bi-clipboard2-check"></i> Tindak Lanjut
                            </a>
                            @endcan

                            {{-- Monitoring --}}
                            @can('view-monitoring')
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('monitoring.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('monitoring.index') }}">
                                <i class="bi bi-bar-chart-line"></i> Monitoring
                            </a>
                            @endcan

                            <hr class="my-1 text-muted">

                            {{-- User Management --}}
                            @can('view-user')
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('users.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i> User Management
                            </a>
                            @endcan

                            {{-- Master Data --}}
                            @can('view-master-data')
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('master-data.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('master-data.index') }}">
                                <i class="bi bi-database"></i> Master Data
                            </a>
                            @endcan

                            {{-- Audit Log --}}
                            @can('view-audit-log')
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('audit-log.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('audit-log.index') }}">
                                <i class="bi bi-journal-text"></i> Audit Log
                            </a>
                            @endcan

                            {{-- Pengaturan Sistem --}}
                            @can('view-settings')
                            <a class="nav-link rounded-3 fw-medium d-flex align-items-center gap-2 {{ request()->routeIs('settings.*') ? 'active' : 'bg-hover-light' }}" href="{{ route('settings.index') }}">
                                <i class="bi bi-gear"></i> Pengaturan Sistem
                            </a>
                            @endcan


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

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WBS-BBSPJIKKP') }} - Secure Terminal</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .auth-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            color: #1e293b;
            box-shadow: 0 10px 25px -5px rgba(10, 66, 130, 0.05), 0 8px 10px -6px rgba(10, 66, 130, 0.05);
        }
        .auth-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #0f172a;
        }
        .auth-subtitle {
            font-size: 0.9rem;
            color: #475569;
            margin-bottom: 1.5rem;
        }
        .form-control {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 0.75rem 1rem 0.75rem 2.5rem; /* Padding for left icon */
            border-radius: 8px;
            box-shadow: none;
            color: #1e293b;
        }
        .form-control:focus {
            border-color: #0a4282;
            box-shadow: 0 0 0 3px rgba(10, 66, 130, 0.1);
        }
        .input-group-text {
            background: transparent;
            border: none;
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: #64748b;
        }
        .input-group {
            position: relative;
        }
        .btn-toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: none;
            border: none;
            color: #64748b;
        }
        .btn-primary {
            background-color: #0a4282; /* Deep blue matching project theme */
            border-color: #0a4282;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #083569;
            border-color: #083569;
            transform: translateY(-1px);
        }
        .footer-links {
            font-size: 0.85rem;
            color: #64748b;
        }
        .footer-links a {
            color: #0a4282;
            text-decoration: none;
            font-weight: 500;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
        .header-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .header-logo h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0a4282;
        }
        .header-logo p {
            color: #64748b;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="main-container flex-column py-5">
        
        <div class="header-logo">
            <h1><i class="bi bi-shield-check me-2"></i> WBS BBSPJIKKP</h1>
            <p class="mb-0 text-center">Sistem Whistleblowing (WBS) BBSPJIKKP<br>Berintegritas • Siap Melayani • Hebat Tanpa Korupsi</p>
        </div>

        @yield('content')

        <div class="mt-4 text-center footer-links w-100" style="max-width: 500px;">
            <div class="d-flex justify-content-center gap-3 align-items-center flex-wrap">
                <div><i class="bi bi-shield-check"></i> ISO 27001 Certified</div>
                <span>&bull;</span>
                <div><i class="bi bi-shield-lock"></i> AES 256-bit</div>
                <span>&bull;</span>
                <div>&copy; 2026 BBSPJIKKP</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

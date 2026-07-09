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
            background: linear-gradient(135deg, #0b1a30 0%, #061121 100%);
            color: #e2e8f0;
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
            background-color: #e2e8f0; /* Light gray from reference */
            border-radius: 12px;
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            color: #1e293b;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
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
        .form-control, .form-control:focus {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 0.75rem 1rem 0.75rem 2.5rem; /* Padding for left icon */
            border-radius: 8px;
            box-shadow: none;
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
            background-color: #0f172a; /* Dark blue button */
            border-color: #0f172a;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #1e293b;
            border-color: #1e293b;
        }
        .footer-links {
            font-size: 0.85rem;
            color: #94a3b8;
        }
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
        }
        .footer-links a:hover {
            color: #e2e8f0;
        }
        .header-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header-logo h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #ffffff;
        }
        .header-logo p {
            color: #94a3b8;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="main-container flex-column">
        
        <div class="header-logo">
            <h1><i class="bi bi-shield-check me-2"></i> WBS Government</h1>
            <p class="mb-0 text-center">Secure Integrity Portal. Your identity is protected by<br>state-grade encryption protocols.</p>
        </div>

        @yield('content')

        <div class="mt-4 text-center footer-links w-100" style="max-width: 500px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div><i class="bi bi-shield-check"></i> ISO 27001<br>Certified</div>
                <div><i class="bi bi-shield-lock"></i> 256-bit<br>AES</div>
                <div>&copy; 2024</div>
                <div><a href="#">Privacy<br>Policy</a></div>
                <div>&bull; <a href="#">Accessibility</a></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

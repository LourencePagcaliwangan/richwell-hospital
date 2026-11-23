<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Richwell Hospital Reservation System')</title>
    <style>
        :root {
            --navy: #0f294a;
            --navy-dark: #091a30;
            --teal: #0e7490;
            --teal-dark: #155e75;
            --blue-btn: #1656b8;
            --blue-btn-hover: #104291;
            --bg-overlay: rgba(9, 26, 48, 0.72);
            --card-bg: rgba(255, 255, 255, 0.96);
            --text: #1e293b;
            --muted: #64748b;
            --border: #cbd5e1;
            --success-bg: #dcfce7;
            --success-text: #166534;
            --error-bg: #fee2e2;
            --error-text: #991b1b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                linear-gradient(var(--bg-overlay), var(--bg-overlay)),
                url('/assets/richwell%20hospital.png') center center / cover fixed no-repeat,
                linear-gradient(135deg, var(--navy-dark), var(--teal-dark));
            padding: 24px 16px;
        }

        .page-shell {
            max-width: 1120px;
            margin: 0 auto;
        }

        .top-banner {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
            margin-bottom: 18px;
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            object-fit: contain;
            border-radius: 8px;
            background: #f8fafc;
            padding: 4px;
            border: 1px solid #e2e8f0;
        }

        .brand-title {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--navy);
        }

        .brand-subtitle {
            margin: 2px 0 0;
            font-size: 0.82rem;
            color: var(--muted);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .user-pill {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--navy);
            background: #e2e8f0;
            padding: 6px 12px;
            border-radius: 999px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 7px;
            border: 1px solid transparent;
            font-size: 0.86rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .btn-primary {
            background: var(--blue-btn);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--blue-btn-hover);
        }

        .btn-teal {
            background: var(--teal);
            color: #fff;
        }

        .btn-teal:hover {
            background: var(--teal-dark);
        }

        .btn-outline {
            background: #fff;
            color: var(--navy);
            border-color: var(--border);
        }

        .btn-outline:hover {
            background: #f1f5f9;
        }

        .btn-dark {
            background: #1e293b;
            color: #fff;
        }

        .btn-dark:hover {
            background: #0f172a;
        }

        .btn-danger {
            background: #dc2626;
            color: #fff;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.78rem;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success-text);
            border: 1px solid #86efac;
        }

        .alert-error {
            background: var(--error-bg);
            color: var(--error-text);
            border: 1px solid #fca5a5;
        }

        .alert ul {
            margin: 6px 0 0 18px;
            padding: 0;
        }

        .card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.24);
        }

        .page-footer {
            margin-top: 18px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 10px 16px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 0.78rem;
            color: var(--muted);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="page-shell">
        <header class="top-banner">
            <div class="brand-group">
                <img src="/assets/logo.png" alt="Richwell Hospital Logo" class="brand-logo" onerror="this.style.display='none'">
                <div>
                    <h1 class="brand-title">@yield('header_title', 'Richwell Hospital Reservation System')</h1>
                    <p class="brand-subtitle">@yield('header_subtitle', 'Secure Outpatient Appointment Booking & Live Tracking')</p>
                </div>
            </div>

            <nav class="nav-actions">
                @yield('nav_links')
            </nav>
        </header>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Please check the form inputs:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

        <footer class="page-footer">
            <span>Richwell Hospital Outpatient Portal • Appointments depend on doctor hours and real-time slot availability</span>
            <span>For urgent emergencies, proceed directly to Emergency Triage</span>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
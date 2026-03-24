<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GrosirKita - Solusi Belanja Grosir</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --bg-light: #f8fafc;
            --card-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(0, 0, 0, 0.06);
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            background-image: 
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.05) 0, transparent 50%), 
                radial-gradient(at 100% 0%, rgba(5, 150, 105, 0.05) 0, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .glass {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.02), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        }

        nav {
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.8);
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
        }

        .logo {
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: -0.025em;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo::before {
            content: "G";
            background: var(--primary);
            color: white;
            padding: 0 0.5rem;
            border-radius: 0.5rem;
            font-size: 1.25rem;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .btn {
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
        }

        .btn-outline {
            background: white;
            border: 1px solid var(--glass-border);
            color: var(--text-main);
        }

        .btn-outline:hover {
            background: var(--bg-light);
            border-color: var(--text-muted);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 1rem;
        }

        .modal-content {
            max-width: 500px;
            width: 100%;
            padding: 2rem;
            background: white;
            border-radius: 1.5rem;
            text-align: center;
            position: relative;
            animation: modalIn 0.3s ease-out;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1.25rem;
            font-size: 1.25rem;
            cursor: pointer;
            color: var(--text-muted);
            transition: color 0.2s;
        }

        .close-modal:hover {
            color: var(--text-main);
        }

        .prize-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            background: #f1f5f9;
        }

        /* Toggle Switch Styles */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e2e8f0;
            transition: .3s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        input:checked + .slider {
            background-color: var(--primary);
        }

        input:checked + .slider:before {
            transform: translateX(20px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        footer {
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
            border-top: 1px solid var(--glass-border);
            background: white;
        }

        /* Success Alert */
        .alert-success {
            padding: 1rem 1.5rem;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">GrosirKita</div>
        <div class="nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            @auth
                <a href="{{ route('admin.vouchers.index') }}" class="{{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">Admin Dashboard</a>
            @else
                <a href="{{ route('admin.login') }}" class="{{ request()->routeIs('admin.login') ? 'active' : '' }}">Admin Login</a>
            @endauth
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        &copy; {{ date('Y') }} GrosirKita. Solusi Belanja Grosir Terpercaya.
    </footer>

    @stack('scripts')
</body>
</html>

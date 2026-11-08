<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Farmasi Solusi Kita') }} - @yield('title', 'Apotek Online Terpercaya')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            /* Same palette as the login page and customer portal */
            --admin-primary: #1e40af;
            --admin-primary-dark: #1e3a8a;
            --admin-success: #059669;
            --admin-warning: #d97706;
            --admin-danger: #dc2626;
            --admin-info: #0891b2;
            --admin-secondary: #64748b;
            --admin-light: #f8fafc;
            --admin-dark: #1e293b;
            --admin-border: #e2e8f0;
            --admin-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.08);
            --admin-shadow-lg: 0 10px 15px -3px rgba(15, 23, 42, 0.12);
            --admin-gradient-primary: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            --admin-gradient-success: linear-gradient(135deg, #059669 0%, #10b981 100%);
            --admin-gradient-warning: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
            --admin-gradient-danger: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
        }

        /* Navbar Styles */
        .navbar {
            background: white !important;
            box-shadow: var(--admin-shadow);
            border: none;
            border-radius: 0 0 16px 16px;
            margin-bottom: 2rem;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: var(--admin-gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-brand i {
            background: var(--admin-gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            color: var(--admin-dark) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 0.25rem;
        }

        .nav-link:hover {
            background: var(--admin-gradient-primary);
            color: white !important;
            transform: translateY(-2px);
        }

        /* Search Bar */
        .search-bar {
            background: white;
            border-radius: 25px;
            box-shadow: var(--admin-shadow);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .search-bar:focus-within {
            border-color: var(--admin-primary);
            box-shadow: var(--admin-shadow-lg);
            transform: translateY(-2px);
        }

        .search-bar .form-control {
            border: none;
            border-radius: 25px 0 0 25px;
            padding: 0.75rem 1.25rem;
        }

        .search-bar .btn {
            border-radius: 0 25px 25px 0;
            background: var(--admin-gradient-primary);
            border: none;
            padding: 0.75rem 1.25rem;
        }

        .search-bar .btn:hover {
            transform: scale(1.05);
        }

        /* Button Styles */
        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--admin-gradient-primary);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--admin-shadow-lg);
        }

        .btn-success {
            background: var(--admin-gradient-success);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: var(--admin-shadow-lg);
        }

        .btn-warning {
            background: var(--admin-gradient-warning);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-3px);
            box-shadow: var(--admin-shadow-lg);
        }

        .btn-outline-primary {
            background: transparent;
            border: 2px solid var(--admin-primary);
            color: var(--admin-primary);
        }

        .btn-outline-primary:hover {
            background: var(--admin-gradient-primary);
            border-color: transparent;
            color: white;
            transform: translateY(-3px);
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--admin-shadow);
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--admin-shadow-lg);
        }

        .card-header {
            background: var(--admin-gradient-primary);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        /* Badge Styles */
        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge.bg-primary {
            background: var(--admin-gradient-primary) !important;
        }

        /* Footer */
        .footer {
            background: var(--admin-dark);
            color: white;
            margin-top: 4rem;
            border-radius: 16px 16px 0 0;
            box-shadow: var(--admin-shadow-lg);
        }

        .footer h5 {
            background: var(--admin-gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        /* Main Content */
        main {
            min-height: calc(100vh - 200px);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }

        /* Utility Classes */
        .min-vh-75 {
            min-height: 75vh;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .search-bar {
                margin: 1rem 0;
            }
            
            .btn {
                padding: 0.5rem 1rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('products.index') }}">
                <i class="fas fa-pills me-2"></i>
                Farmasi Solusi Kita
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search Bar -->
                <div class="mx-auto" style="width: 400px;">
                    <form action="{{ route('products.search') }}" method="GET" class="d-flex">
                        <div class="input-group search-bar">
                            <input type="text" 
                                   class="form-control" 
                                   placeholder="Cari obat, vitamin, atau produk kesehatan..." 
                                   name="q" 
                                   value="{{ request('q') }}">
                            <button class="btn" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Navigation -->
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}" id="cart-link">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="badge bg-primary rounded-pill" id="cart-count">0</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-gauge me-2"></i>Admin Panel</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-house-medical me-2"></i>Dashboard Saya</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-receipt me-2"></i>Pesanan Saya</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @foreach(['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'] as $key => $type)
            @if(session($key))
                <div class="container">
                    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                        {{ session($key) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
        @endforeach
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-pills me-2"></i>Farmasi Solusi Kita</h5>
                    <p class="mb-0">Apotek online terpercaya untuk semua kebutuhan kesehatan Anda.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; {{ date('Y') }} Farmasi Solusi Kita. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Cart functionality (only logged-in customers have a cart) -->
    <script>
        @auth
        document.addEventListener('DOMContentLoaded', function() {
            loadCartCount();
        });
        @endauth

        function loadCartCount() {
            if (!document.getElementById('cart-count')) return;
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.cart_count;
                    }
                })
                .catch(error => {
                    console.error('Error loading cart count:', error);
                });
        }
    </script>
    
    @stack('scripts')
</body>
</html>

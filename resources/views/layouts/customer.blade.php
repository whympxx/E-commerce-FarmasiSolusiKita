<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Akun Saya') - Farmasi Solusi Kita</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Gaya portal pelanggan (mengikuti panel admin) -->
    <style>
        :root {
            --admin-primary: #1e40af;
            --admin-secondary: #64748b;
            --admin-success: #059669;
            --admin-warning: #d97706;
            --admin-danger: #dc2626;
            --admin-sidebar: #1e293b;
            --admin-sidebar-hover: #334155;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: var(--admin-sidebar);
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar.collapsed { width: var(--sidebar-collapsed-width); }

        .sidebar .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid #334155;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 70px;
        }

        .sidebar .sidebar-header h4 {
            color: white;
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-header h4 { opacity: 0; width: 0; }

        .sidebar-toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-toggle-btn:hover { background: rgba(255, 255, 255, 0.1); transform: scale(1.1); }

        .sidebar-nav {
            padding: 0;
            margin: 0;
            list-style: none;
            overflow-y: auto;
            flex: 1;
        }

        .sidebar-nav .nav-section {
            color: #64748b;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 1rem 1rem .4rem;
            white-space: nowrap;
        }

        .sidebar.collapsed .sidebar-nav .nav-section { visibility: hidden; height: .75rem; padding: 0; }

        .sidebar-nav .nav-item { border-bottom: 1px solid #334155; }

        .sidebar-nav .nav-link {
            color: #cbd5e1;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            background: none;
            border: none;
            width: 100%;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: var(--admin-sidebar-hover);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-nav .nav-link.active::before {
            content: '';
            position: absolute;
            left: -5px; top: 0; bottom: 0;
            width: 4px;
            background: #60a5fa;
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .sidebar-nav .nav-link .badge { margin-left: auto; }

        .sidebar.collapsed .sidebar-nav .nav-link { justify-content: center; padding: 0.75rem 0.5rem; }
        .sidebar.collapsed .sidebar-nav .nav-link i { margin-right: 0; font-size: 1.2rem; }
        .sidebar.collapsed .sidebar-nav .nav-link span,
        .sidebar.collapsed .sidebar-nav .nav-link .badge { display: none; }

        .sidebar-user {
            padding: 1rem;
            border-top: 1px solid #334155;
            display: flex;
            align-items: center;
            gap: .75rem;
            color: #e2e8f0;
            white-space: nowrap;
            overflow: hidden;
        }

        .avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: #fff;
            display: grid; place-items: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .sidebar.collapsed .sidebar-user { justify-content: center; padding: 1rem .5rem; }
        .sidebar.collapsed .sidebar-user .user-meta { display: none; }

        /* Konten utama */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.expanded { margin-left: var(--sidebar-collapsed-width); }

        .top-navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .top-navbar .left-section,
        .top-navbar .right-section { display: flex; align-items: center; gap: 1rem; }

        .top-icon-btn {
            position: relative;
            width: 40px; height: 40px;
            border-radius: 10px;
            display: grid; place-items: center;
            color: var(--admin-sidebar);
            background: #f1f5f9;
            text-decoration: none;
            transition: all .2s ease;
        }
        .top-icon-btn:hover { background: #e2e8f0; color: var(--admin-primary); }
        .top-icon-btn .count {
            position: absolute; top: -6px; right: -6px;
            min-width: 20px; height: 20px; padding: 0 5px;
            border-radius: 10px;
            background: var(--admin-danger); color: #fff;
            font-size: .7rem; font-weight: 700;
            display: grid; place-items: center;
        }

        .content-area { padding: 1.5rem; }

        /* Kartu & komponen (sama dengan admin) */
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
            color: var(--admin-sidebar);
        }

        .btn-primary { background: var(--admin-primary); border-color: var(--admin-primary); }
        .btn-primary:hover { background: #1d4ed8; border-color: #1d4ed8; }
        .btn-outline-primary { color: var(--admin-primary); border-color: var(--admin-primary); }
        .btn-outline-primary:hover { background: var(--admin-primary); border-color: var(--admin-primary); }
        .text-primary { color: var(--admin-primary) !important; }

        .pharma3d-canvas { display: block; width: 100% !important; height: 100% !important; }

        /* Responsif */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: var(--sidebar-width); }
            .sidebar.show { transform: translateX(0); }
            .sidebar.collapsed { width: var(--sidebar-width); }
            .main-content, .main-content.expanded { margin-left: 0; }
            .content-area { padding: 1rem; }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            .sidebar-overlay.show { opacity: 1; visibility: visible; }
        }

        .fade-in { animation: fadeIn 0.4s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

        .sidebar-nav::-webkit-scrollbar { width: 5px; }
        .sidebar-nav::-webkit-scrollbar-track { background: #334155; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #64748b; border-radius: 3px; }
    </style>

    @stack('styles')
</head>
<body>
    @php
        $customer = Auth::user();
        $cartCount = (int) \App\Models\Cart::where('user_id', $customer->id)->sum('quantity');
        $activeOrderCount = $customer->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count();
        $initial = mb_strtoupper(mb_substr($customer->name, 0, 1));
    @endphp

    <!-- Overlay sidebar (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-pills me-2"></i>Akun Saya</h4>
            <button class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Ciutkan menu">
                <i class="fas fa-bars" id="sidebarToggleIcon"></i>
            </button>
        </div>

        <ul class="sidebar-nav">
            <li class="nav-section">Menu</li>
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-house-medical"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i><span>Pesanan Saya</span>
                    @if($activeOrderCount)
                        <span class="badge rounded-pill bg-warning text-dark">{{ $activeOrderCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('cart.index') }}" class="nav-link">
                    <i class="fas fa-shopping-cart"></i><span>Keranjang</span>
                    <span class="badge rounded-pill bg-primary {{ $cartCount ? '' : 'd-none' }}" data-cart-count>{{ $cartCount }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link">
                    <i class="fas fa-store"></i><span>Belanja Produk</span>
                </a>
            </li>

            <li class="nav-section">Akun</li>
            <li class="nav-item">
                <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                    <i class="fas fa-user"></i><span>Profil Saya</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-user-gear"></i><span>Pengaturan Akun</span>
                </a>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link">
                        <i class="fas fa-right-from-bracket"></i><span>Keluar</span>
                    </button>
                </form>
            </li>
        </ul>

        <div class="sidebar-user">
            <div class="avatar">{{ $initial }}</div>
            <div class="user-meta">
                <div class="fw-semibold small text-truncate" style="max-width: 160px;">{{ $customer->name }}</div>
                <div class="small text-secondary text-truncate" style="max-width: 160px;">{{ $customer->email }}</div>
            </div>
        </div>
    </nav>

    <!-- Konten utama -->
    <div class="main-content" id="mainContent">
        <div class="top-navbar">
            <div class="left-section">
                <button class="btn btn-link sidebar-toggle-btn text-dark d-md-none" onclick="toggleSidebar()" title="Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            </div>

            <div class="right-section">
                <a href="{{ route('products.index') }}" class="top-icon-btn" title="Belanja">
                    <i class="fas fa-store"></i>
                </a>
                <a href="{{ route('cart.index') }}" class="top-icon-btn" title="Keranjang">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="count {{ $cartCount ? '' : 'd-none' }}" data-cart-count>{{ $cartCount }}</span>
                </a>
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none text-dark d-flex align-items-center gap-2 p-0" data-bs-toggle="dropdown">
                        <span class="avatar" style="width: 36px; height: 36px;">{{ $initial }}</span>
                        <span class="d-none d-sm-inline fw-semibold">{{ $customer->name }}</span>
                        <i class="fas fa-chevron-down small text-muted"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Profil Saya</a></li>
                        <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-receipt me-2"></i>Pesanan Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-right-from-bracket me-2"></i>Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Status sidebar disimpan terpisah dari panel admin
        const SIDEBAR_KEY = 'customerSidebarCollapsed';

        function readCollapsed() {
            try { return localStorage.getItem(SIDEBAR_KEY) === 'true'; } catch (e) { return false; }
        }

        function setIcon(collapsed) {
            const icon = document.getElementById('sidebarToggleIcon');
            icon.classList.toggle('fa-bars', !collapsed);
            icon.classList.toggle('fa-chevron-right', collapsed);
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                document.getElementById('sidebarOverlay').classList.toggle('show');
                return;
            }
            const collapsed = sidebar.classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('expanded', collapsed);
            setIcon(collapsed);
            try { localStorage.setItem(SIDEBAR_KEY, collapsed); } catch (e) {}
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (window.innerWidth > 768 && readCollapsed()) {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('expanded');
                setIcon(true);
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSidebar();
        });

        // Tutup notifikasi otomatis setelah 5 detik
        setTimeout(function () {
            document.querySelectorAll('.alert.alert-dismissible').forEach(function (el) {
                bootstrap.Alert.getOrCreateInstance(el).close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Masuk' }} - Farmasi Solusi Kita</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Warna mengikuti panel admin -->
        <style>
            :root {
                --admin-primary: #1e40af;
                --admin-primary-hover: #1d4ed8;
                --admin-sidebar: #1e293b;
                --admin-sidebar-hover: #334155;
                --admin-success: #059669;
            }

            body {
                font-family: 'Figtree', sans-serif;
                background: #f8fafc;
                min-height: 100vh;
            }

            .auth-wrapper { min-height: 100vh; }

            /* Panel kiri: gelap seperti sidebar admin + animasi 3D */
            .auth-brand-panel {
                position: relative;
                overflow: hidden;
                background:
                    radial-gradient(circle at 75% 35%, rgba(37, 99, 235, .35), transparent 55%),
                    radial-gradient(circle at 15% 85%, rgba(5, 150, 105, .25), transparent 50%),
                    linear-gradient(160deg, #0f172a 0%, var(--admin-sidebar) 60%, #172554 100%);
                color: #fff;
            }

            .auth-brand-panel .scene {
                position: absolute;
                inset: 0;
            }

            .pharma3d-canvas { display: block; width: 100% !important; height: 100% !important; }

            .auth-brand-content {
                position: relative;
                z-index: 2;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 3rem;
                pointer-events: none;
            }
            .auth-brand-content a { pointer-events: auto; }

            .brand-logo {
                display: inline-flex;
                align-items: center;
                gap: .75rem;
                color: #fff;
                text-decoration: none;
                font-weight: 700;
                font-size: 1.25rem;
            }
            .brand-logo .logo-icon {
                width: 44px; height: 44px;
                border-radius: 12px;
                background: var(--admin-primary);
                display: grid; place-items: center;
                box-shadow: 0 8px 24px rgba(30, 64, 175, .5);
            }

            .auth-headline { max-width: 370px; }
            .auth-headline h1 { font-weight: 800; font-size: 2.4rem; line-height: 1.15; }
            .auth-headline h1 span { color: #60a5fa; }
            .auth-headline p { color: #cbd5e1; }
            .auth-headline { text-shadow: 0 2px 12px rgba(15, 23, 42, .6); }

            .auth-feature {
                display: flex; align-items: center; gap: .75rem;
                color: #e2e8f0; margin-bottom: .75rem;
            }
            .auth-feature i {
                width: 34px; height: 34px; border-radius: 10px;
                background: rgba(255, 255, 255, .08);
                border: 1px solid rgba(255, 255, 255, .12);
                display: grid; place-items: center; color: #60a5fa;
            }

            /* Panel kanan: form */
            .auth-form-panel {
                display: flex; align-items: center; justify-content: center;
                padding: 2rem 1rem;
            }
            .auth-card {
                width: 100%;
                max-width: 430px;
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(15, 23, 42, .08);
                padding: 2.25rem;
                animation: authIn .5s ease-out;
            }
            @keyframes authIn {
                from { opacity: 0; transform: translateY(16px); }
                to { opacity: 1; transform: none; }
            }
            .auth-card h2 { font-weight: 800; color: var(--admin-sidebar); }

            .form-label { color: var(--admin-sidebar); }
            .form-control, .input-group-text { border-color: #e2e8f0; padding-top: .65rem; padding-bottom: .65rem; }
            .input-group-text { background: #f8fafc; color: #64748b; }
            .form-control:focus { border-color: var(--admin-primary); box-shadow: 0 0 0 .2rem rgba(30, 64, 175, .15); }
            .input-group:focus-within .input-group-text { border-color: var(--admin-primary); color: var(--admin-primary); }

            .btn-primary {
                background: var(--admin-primary);
                border-color: var(--admin-primary);
                font-weight: 600;
            }
            .btn-primary:hover, .btn-primary:focus {
                background: var(--admin-primary-hover);
                border-color: var(--admin-primary-hover);
            }
            .btn-auth { padding: .75rem; border-radius: 10px; transition: transform .15s ease, box-shadow .15s ease; }
            .btn-auth:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(30, 64, 175, .3); }

            a { color: var(--admin-primary); }

            .mobile-brand { display: none; }

            /* Kelas bantu agar halaman auth bawaan (lupa password, dll.) tetap rapi */
            .flex { display: flex; }
            .items-center { align-items: center; }
            .justify-end { justify-content: flex-end; }
            .justify-between { justify-content: space-between; }
            .w-full { width: 100%; }
            .block { display: block; }
            .text-sm { font-size: .875rem; }
            .text-gray-600 { color: #64748b; }
            .mb-4 { margin-bottom: 1rem; }

            @media (max-width: 991.98px) {
                .mobile-brand {
                    display: flex; justify-content: center; margin-bottom: 1.5rem;
                }
                .mobile-brand .brand-logo { color: var(--admin-sidebar); }
                .auth-card { padding: 1.75rem 1.25rem; }
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row auth-wrapper">
                <!-- Panel merek + 3D -->
                <div class="col-lg-6 d-none d-lg-block p-0">
                    <div class="auth-brand-panel h-100">
                        <div class="scene" id="authScene"></div>
                        <div class="auth-brand-content">
                            <a href="{{ route('home') }}" class="brand-logo">
                                <span class="logo-icon"><i class="fas fa-pills"></i></span>
                                Farmasi Solusi Kita
                            </a>

                            <div class="auth-headline">
                                <h1>Kesehatan keluarga,<br><span>satu klik</span> saja.</h1>
                                <p class="mb-4">Belanja obat, vitamin, dan alat kesehatan dengan aman. Pantau pesanan Anda dari mana saja.</p>
                                <div class="auth-feature"><i class="fas fa-shield-heart"></i> Produk asli &amp; terjamin kualitasnya</div>
                                <div class="auth-feature"><i class="fas fa-truck-fast"></i> Pengiriman cepat ke rumah</div>
                                <div class="auth-feature"><i class="fas fa-user-doctor"></i> Didukung apoteker profesional</div>
                            </div>

                            <small class="text-white-50">&copy; {{ date('Y') }} Farmasi Solusi Kita</small>
                        </div>
                    </div>
                </div>

                <!-- Panel form -->
                <div class="col-lg-6 auth-form-panel">
                    <div class="w-100" style="max-width: 430px;">
                        <div class="mobile-brand">
                            <a href="{{ route('home') }}" class="brand-logo">
                                <span class="logo-icon text-white"><i class="fas fa-pills"></i></span>
                                Farmasi Solusi Kita
                            </a>
                        </div>
                        <div class="auth-card">
                            {{ $slot }}
                        </div>
                        <p class="text-center text-muted small mt-4 mb-0">
                            <a href="{{ route('products.index') }}" class="text-decoration-none text-muted">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke toko
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Animasi 3D (hanya dimuat di layar lebar, panel kiri tersembunyi di mobile) -->
        <script>
            if (window.matchMedia('(min-width: 992px)').matches) {
                const three = document.createElement('script');
                three.src = 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js';
                three.onload = function () {
                    const s = document.createElement('script');
                    s.src = '{{ asset('js/pharma-3d.js') }}';
                    s.onload = function () {
                        Pharma3D.mount(document.getElementById('authScene'), { objects: 9, heroOffsetX: 3, area: { xMin: 0.8, xMax: 6, yMin: -6, yMax: 6 } });
                    };
                    document.body.appendChild(s);
                };
                document.body.appendChild(three);
            }

            // Tombol lihat/sembunyikan password
            document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const input = document.getElementById(btn.dataset.togglePassword);
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    btn.querySelector('i').className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
                });
            });
        </script>
    </body>
</html>

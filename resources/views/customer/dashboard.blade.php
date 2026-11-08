@extends('layouts.customer')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@php
    $hour = now()->hour;
    $greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 18 ? 'Selamat sore' : 'Selamat malam'));
    $firstName = explode(' ', trim($user->name))[0];
@endphp

@push('styles')
<style>
    /* ---------- Hero 3D ---------- */
    .hero-3d {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        min-height: 320px;
        color: #fff;
        background:
            radial-gradient(circle at 78% 40%, rgba(37, 99, 235, .45), transparent 55%),
            radial-gradient(circle at 10% 90%, rgba(5, 150, 105, .3), transparent 45%),
            linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #172554 100%);
        box-shadow: 0 20px 40px rgba(15, 23, 42, .25);
    }
    .hero-3d .scene { position: absolute; inset: 0; }
    .hero-3d .hero-content {
        position: relative;
        z-index: 2;
        padding: 2.5rem;
        max-width: 560px;
        pointer-events: none;
        text-shadow: 0 2px 12px rgba(15, 23, 42, .5);
    }
    .hero-3d .hero-content a, .hero-3d .hero-content button { pointer-events: auto; }
    .hero-3d h1 { font-weight: 800; font-size: 2.1rem; }
    .hero-3d h1 .wave { display: inline-block; animation: wave 2.4s ease-in-out infinite; transform-origin: 70% 70%; }
    @keyframes wave { 0%, 60%, 100% { transform: rotate(0); } 10%, 30% { transform: rotate(14deg); } 20% { transform: rotate(-8deg); } }
    .hero-3d p { color: #cbd5e1; }
    .hero-3d .hint {
        position: absolute; right: 1.25rem; bottom: 1rem; z-index: 2;
        font-size: .75rem; color: #94a3b8; pointer-events: none;
    }
    .hero-3d .btn-light { font-weight: 600; color: #1e40af; }
    .hero-3d .btn-glass {
        background: rgba(255, 255, 255, .1);
        border: 1px solid rgba(255, 255, 255, .25);
        color: #fff; font-weight: 600;
        backdrop-filter: blur(6px);
    }
    .hero-3d .btn-glass:hover { background: rgba(255, 255, 255, .2); color: #fff; }

    /* Cadangan jika WebGL tidak tersedia: kapsul CSS 3D */
    .pharma3d-fallback::after {
        content: '';
        position: absolute; right: 12%; top: 50%;
        width: 70px; height: 190px; margin-top: -95px;
        border-radius: 35px;
        background: linear-gradient(to bottom, #3b82f6 50%, #f8fafc 50%);
        box-shadow: inset -12px 0 20px rgba(0, 0, 0, .2), 0 20px 40px rgba(0, 0, 0, .3);
        animation: spinCapsule 6s linear infinite;
    }
    @keyframes spinCapsule { from { transform: rotateZ(35deg) rotateY(0); } to { transform: rotateZ(35deg) rotateY(360deg); } }

    /* ---------- Kartu 3D (miring mengikuti mouse) ---------- */
    .tilt { transform-style: preserve-3d; transition: transform .15s ease-out, box-shadow .2s ease; will-change: transform; }
    .tilt:hover { box-shadow: 0 18px 35px rgba(15, 23, 42, .12) !important; }
    .tilt .pop { transform: translateZ(30px); }

    .stat-card {
        border-left: 4px solid var(--admin-primary);
        border-radius: 12px !important;
        perspective: 800px;
    }
    .stat-card.success { border-left-color: var(--admin-success); }
    .stat-card.warning { border-left-color: var(--admin-warning); }
    .stat-card.purple { border-left-color: #7c3aed; }
    .stat-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: grid; place-items: center; font-size: 1.35rem;
    }
    .stat-value { font-size: 1.6rem; font-weight: 800; color: var(--admin-sidebar); line-height: 1.1; }

    /* ---------- Pelacak pesanan ---------- */
    .tracker { display: flex; justify-content: space-between; position: relative; margin: 1.5rem 0 1rem; }
    .tracker::before, .tracker .progress-line {
        content: ''; position: absolute; top: 22px; left: 12%; right: 12%; height: 4px; background: #e2e8f0; border-radius: 2px;
    }
    .tracker .progress-line { background: linear-gradient(90deg, #1e40af, #3b82f6); right: auto; transition: width 1.2s ease; }
    .tracker .step { position: relative; z-index: 1; text-align: center; flex: 1; }
    .tracker .dot {
        width: 48px; height: 48px; margin: 0 auto .5rem;
        border-radius: 50%;
        display: grid; place-items: center;
        background: #fff; border: 3px solid #e2e8f0; color: #94a3b8;
        transition: all .3s ease;
    }
    .tracker .step.done .dot { background: #1e40af; border-color: #1e40af; color: #fff; }
    .tracker .step.current .dot { animation: pulse 1.8s infinite; }
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(30, 64, 175, .5); } 70% { box-shadow: 0 0 0 14px rgba(30, 64, 175, 0); } 100% { box-shadow: 0 0 0 0 rgba(30, 64, 175, 0); } }
    .tracker .label { font-size: .8rem; font-weight: 600; color: #64748b; }
    .tracker .step.done .label { color: var(--admin-sidebar); }

    .item-thumb {
        width: 48px; height: 48px; border-radius: 10px; object-fit: cover;
        background: #f1f5f9; border: 1px solid #e2e8f0;
    }

    /* ---------- Kartu tips (bisa dibalik 3D) ---------- */
    .flip-card { perspective: 1000px; min-height: 240px; cursor: pointer; }
    .flip-inner {
        position: relative; width: 100%; height: 100%; min-height: 240px;
        transform-style: preserve-3d; transition: transform .8s cubic-bezier(.2, .8, .2, 1);
    }
    .flip-card:hover .flip-inner, .flip-card.flipped .flip-inner { transform: rotateY(180deg); }
    .flip-face {
        position: absolute; inset: 0; border-radius: 12px; padding: 1.5rem;
        backface-visibility: hidden; -webkit-backface-visibility: hidden;
        display: flex; flex-direction: column; justify-content: center;
    }
    .flip-front { background: linear-gradient(135deg, #059669, #047857); color: #fff; }
    .flip-back { background: linear-gradient(135deg, #1e40af, #1e293b); color: #fff; transform: rotateY(180deg); }
    .flip-face .tip-icon {
        width: 56px; height: 56px; border-radius: 16px; font-size: 1.5rem;
        background: rgba(255, 255, 255, .15); display: grid; place-items: center; margin-bottom: 1rem;
    }

    /* ---------- Aksi cepat ---------- */
    .quick-action {
        display: flex; align-items: center; gap: .9rem;
        padding: .85rem 1rem; border-radius: 10px;
        color: var(--admin-sidebar); text-decoration: none;
        transition: all .2s ease;
    }
    .quick-action:hover { background: #f1f5f9; color: var(--admin-primary); transform: translateX(4px); }
    .quick-action .qa-icon { width: 40px; height: 40px; border-radius: 10px; display: grid; place-items: center; }

    /* ---------- Produk rekomendasi ---------- */
    .product-tile { border-radius: 14px !important; overflow: hidden; perspective: 900px; }
    .product-tile .img-wrap { height: 170px; background: #f8fafc; overflow: hidden; }
    .product-tile .img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
    .product-tile:hover .img-wrap img { transform: scale(1.06); }
    .product-tile .price { color: var(--admin-primary); font-weight: 700; }

    /* Muncul bertahap */
    .reveal { opacity: 0; transform: translateY(18px); transition: opacity .6s ease, transform .6s ease; }
    .reveal.in { opacity: 1; transform: none; }
    .tilt.reveal.in { transition: transform .15s ease-out, box-shadow .2s ease, opacity .6s ease; }

    @media (max-width: 768px) {
        .hero-3d { min-height: 400px; }
        .hero-3d .hero-content { padding: 1.5rem; }
        .hero-3d h1 { font-size: 1.6rem; }
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal { opacity: 1; transform: none; transition: none; }
        .tilt { transition: none; }
    }
</style>
@endpush

@section('content')
    <!-- Hero dengan animasi 3D -->
    <section class="hero-3d mb-4">
        <div class="scene" id="customerScene"></div>
        <div class="hero-content">
            <span class="badge rounded-pill mb-3" style="background: rgba(96, 165, 250, .2); color: #bfdbfe;">
                <i class="fas fa-calendar-day me-1"></i>{{ now()->locale('id')->translatedFormat('l, d F Y') }}
            </span>
            <h1 class="mb-2">{{ $greeting }}, {{ $firstName }} <span class="wave">👋</span></h1>
            <p class="mb-4">Selamat datang di akun Farmasi Solusi Kita. Pantau pesanan, lihat rekomendasi produk, dan jaga kesehatan keluarga Anda.</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-light px-4">
                    <i class="fas fa-store me-2"></i>Belanja Sekarang
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-glass px-4">
                    <i class="fas fa-receipt me-2"></i>Pesanan Saya
                </a>
            </div>
        </div>
        <div class="hint d-none d-md-block"><i class="fas fa-computer-mouse me-1"></i>Gerakkan & klik obat 3D</div>
    </section>

    <!-- Statistik -->
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['label' => 'Total Pesanan', 'value' => $stats['total'], 'icon' => 'fa-receipt', 'class' => '', 'bg' => '#dbeafe', 'color' => '#1e40af'],
                ['label' => 'Sedang Berjalan', 'value' => $stats['active'], 'icon' => 'fa-truck-fast', 'class' => 'warning', 'bg' => '#fef3c7', 'color' => '#d97706'],
                ['label' => 'Pesanan Selesai', 'value' => $stats['delivered'], 'icon' => 'fa-circle-check', 'class' => 'success', 'bg' => '#d1fae5', 'color' => '#059669'],
                ['label' => 'Total Belanja', 'value' => $stats['spent'], 'icon' => 'fa-wallet', 'class' => 'purple', 'bg' => '#ede9fe', 'color' => '#7c3aed', 'money' => true],
            ];
        @endphp
        @foreach($cards as $i => $card)
            <div class="col-6 col-xl-3">
                <div class="card stat-card {{ $card['class'] }} tilt reveal h-100" style="transition-delay: {{ $i * 80 }}ms;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon pop" style="background: {{ $card['bg'] }}; color: {{ $card['color'] }};">
                            <i class="fas {{ $card['icon'] }}"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stat-value" data-count="{{ $card['value'] }}" @if(!empty($card['money'])) data-money="1" @endif>
                                {{ !empty($card['money']) ? 'Rp ' . number_format($card['value'], 0, ',', '.') : $card['value'] }}
                            </div>
                            <small class="text-muted">{{ $card['label'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">
        <!-- Pelacak pesanan aktif -->
        <div class="col-lg-8">
            <div class="card h-100 reveal">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span><i class="fas fa-location-dot me-2 text-primary"></i>Lacak Pesanan Aktif</span>
                    @if($activeOrder)
                        <a href="{{ route('orders.show', $activeOrder) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                    @endif
                </div>
                <div class="card-body">
                    @if($activeOrder)
                        @php
                            $steps = [
                                ['key' => 'pending', 'label' => 'Menunggu', 'icon' => 'fa-hourglass-half'],
                                ['key' => 'processing', 'label' => 'Diproses', 'icon' => 'fa-box-open'],
                                ['key' => 'shipped', 'label' => 'Dikirim', 'icon' => 'fa-truck-fast'],
                                ['key' => 'delivered', 'label' => 'Selesai', 'icon' => 'fa-house-circle-check'],
                            ];
                            $currentIndex = array_search($activeOrder->status, array_column($steps, 'key'));
                            $progress = $currentIndex === false ? 0 : ($currentIndex / (count($steps) - 1)) * 76;
                        @endphp
                        <div class="d-flex flex-wrap justify-content-between gap-2">
                            <div>
                                <div class="fw-bold">{{ $activeOrder->order_number }}</div>
                                <small class="text-muted">Dipesan {{ $activeOrder->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary">{{ $activeOrder->formatted_total_amount }}</div>
                                <small class="text-muted">{{ $activeOrder->payment_status_label }}</small>
                            </div>
                        </div>

                        <div class="tracker">
                            <div class="progress-line" data-width="{{ $progress }}%" style="width: 0;"></div>
                            @foreach($steps as $i => $step)
                                <div class="step {{ $currentIndex !== false && $i <= $currentIndex ? 'done' : '' }} {{ $i === $currentIndex ? 'current' : '' }}">
                                    <div class="dot"><i class="fas {{ $step['icon'] }}"></i></div>
                                    <div class="label">{{ $step['label'] }}</div>
                                </div>
                            @endforeach
                        </div>

                        @if($activeOrder->tracking_number)
                            <div class="alert alert-info py-2 small mb-3">
                                <i class="fas fa-barcode me-2"></i>No. resi: <strong>{{ $activeOrder->tracking_number }}</strong>
                            </div>
                        @endif

                        <div class="d-flex flex-wrap gap-3">
                            @foreach($activeOrder->orderItems->take(4) as $item)
                                <div class="d-flex align-items-center gap-2">
                                    @if($item->product && $item->product->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="item-thumb">
                                    @else
                                        <div class="item-thumb d-grid" style="place-items: center;"><i class="fas fa-pills text-muted"></i></div>
                                    @endif
                                    <div class="small">
                                        <div class="fw-semibold">{{ $item->product_name }}</div>
                                        <div class="text-muted">x{{ $item->quantity }}</div>
                                    </div>
                                </div>
                            @endforeach
                            @if($activeOrder->orderItems->count() > 4)
                                <div class="small text-muted align-self-center">+{{ $activeOrder->orderItems->count() - 4 }} produk lain</div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3" style="font-size: 3rem; color: #cbd5e1;"><i class="fas fa-box-open"></i></div>
                            <h6 class="fw-bold">Tidak ada pesanan yang sedang berjalan</h6>
                            <p class="text-muted small mb-3">Pesanan baru Anda akan bisa dilacak di sini.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm px-4">Mulai Belanja</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tips kesehatan (kartu balik 3D) -->
        <div class="col-lg-4">
            <div class="flip-card h-100 reveal" onclick="this.classList.toggle('flipped')" title="Klik untuk membalik">
                <div class="flip-inner">
                    <div class="flip-face flip-front">
                        <div class="tip-icon"><i class="fas {{ $tip['icon'] }}"></i></div>
                        <small class="text-uppercase fw-bold opacity-75" style="letter-spacing: .08em;">Tips Kesehatan Hari Ini</small>
                        <h5 class="fw-bold mt-1">{{ $tip['title'] }}</h5>
                        <p class="mb-0 opacity-75 small">{{ $tip['text'] }}</p>
                    </div>
                    <div class="flip-face flip-back">
                        <div class="tip-icon"><i class="fas fa-user-doctor"></i></div>
                        <h5 class="fw-bold">Butuh obat atau vitamin?</h5>
                        <p class="opacity-75 small">Temukan produk kesehatan untuk Anda dan keluarga, dikirim langsung ke rumah.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-light btn-sm fw-semibold align-self-start" onclick="event.stopPropagation()">
                            Jelajahi Produk <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Riwayat pesanan terbaru -->
        <div class="col-lg-8">
            <div class="card h-100 reveal">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span><i class="fas fa-clock-rotate-left me-2 text-primary"></i>Riwayat Pesanan Terbaru</span>
                    <a href="{{ route('orders.index') }}" class="small text-decoration-none">Lihat semua <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                @if($recentOrders->isEmpty())
                    <div class="card-body text-center py-5">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Anda belum memiliki pesanan.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">No. Pesanan</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Item</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center pe-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr style="cursor: pointer;" onclick="window.location='{{ route('orders.show', $order) }}'">
                                        <td class="ps-3 fw-semibold">{{ $order->order_number }}</td>
                                        <td class="text-muted">{{ $order->created_at->locale('id')->translatedFormat('d M Y') }}</td>
                                        <td class="text-center">{{ $order->order_items_count }}</td>
                                        <td class="text-end">{{ $order->formatted_total_amount }}</td>
                                        <td class="text-center pe-3"><span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Aksi cepat -->
        <div class="col-lg-4">
            <div class="card h-100 reveal">
                <div class="card-header py-3"><i class="fas fa-bolt me-2 text-primary"></i>Akses Cepat</div>
                <div class="card-body p-2">
                    <a href="{{ route('products.index') }}" class="quick-action">
                        <span class="qa-icon" style="background: #dbeafe; color: #1e40af;"><i class="fas fa-store"></i></span>
                        <span><span class="fw-semibold d-block">Belanja Produk</span><small class="text-muted">Obat, vitamin & alat kesehatan</small></span>
                    </a>
                    <a href="{{ route('cart.index') }}" class="quick-action">
                        <span class="qa-icon" style="background: #fef3c7; color: #d97706;"><i class="fas fa-shopping-cart"></i></span>
                        <span><span class="fw-semibold d-block">Keranjang Saya</span><small class="text-muted">Lanjutkan ke pembayaran</small></span>
                    </a>
                    <a href="{{ route('orders.index') }}" class="quick-action">
                        <span class="qa-icon" style="background: #d1fae5; color: #059669;"><i class="fas fa-receipt"></i></span>
                        <span><span class="fw-semibold d-block">Semua Pesanan</span><small class="text-muted">Status & riwayat belanja</small></span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="quick-action">
                        <span class="qa-icon" style="background: #ede9fe; color: #7c3aed;"><i class="fas fa-user-gear"></i></span>
                        <span><span class="fw-semibold d-block">Pengaturan Akun</span><small class="text-muted">Ubah profil & password</small></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekomendasi produk -->
    @if($recommended->isNotEmpty())
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><i class="fas fa-star me-2 text-warning"></i>Rekomendasi untuk Anda</h5>
            <a href="{{ route('products.index') }}" class="small text-decoration-none">Lihat semua <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-3">
            @foreach($recommended as $i => $product)
                <div class="col-6 col-lg-3">
                    <div class="card product-tile tilt reveal h-100" style="transition-delay: {{ $i * 80 }}ms;">
                        <a href="{{ route('products.show', $product->slug) }}" class="img-wrap d-block">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            @else
                                <div class="h-100 d-grid" style="place-items: center;"><i class="fas fa-pills fa-3x text-muted"></i></div>
                            @endif
                        </a>
                        <div class="card-body d-flex flex-column">
                            <small class="text-muted">{{ $product->brand }}</small>
                            <a href="{{ route('products.show', $product->slug) }}" class="fw-semibold text-dark text-decoration-none mb-2">{{ $product->name }}</a>
                            <div class="mt-auto d-flex justify-content-between align-items-center gap-2">
                                <span class="price">{{ $product->formatted_effective_price }}</span>
                                <button type="button" class="btn btn-sm btn-primary pop" data-add-to-cart="{{ $product->id }}" title="Tambah ke keranjang">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="{{ asset('js/pharma-3d.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // 1. Scene 3D di hero
        const sceneEl = document.getElementById('customerScene');
        const wide = window.innerWidth > 768;
        Pharma3D.mount(sceneEl, wide
            ? { objects: 14, heroOffsetX: 4.2, area: { xMin: 0.5, xMax: 9, yMin: -4, yMax: 4 } }
            : { objects: 7, hero: false, particles: 120, area: { xMin: -4, xMax: 4, yMin: -6.2, yMax: -4 } });

        // 2. Kartu miring 3D mengikuti posisi mouse
        if (!reduceMotion) {
            document.querySelectorAll('.tilt').forEach(function (card) {
                card.addEventListener('mousemove', function (e) {
                    const r = card.getBoundingClientRect();
                    const x = (e.clientX - r.left) / r.width - 0.5;
                    const y = (e.clientY - r.top) / r.height - 0.5;
                    card.style.transform = 'perspective(800px) rotateX(' + (-y * 10) + 'deg) rotateY(' + (x * 12) + 'deg) translateZ(6px)';
                });
                card.addEventListener('mouseleave', function () {
                    card.style.transform = '';
                });
            });
        }

        // 3. Elemen muncul bertahap saat discroll + animasi angka statistik
        function countUp(el) {
            const target = parseFloat(el.dataset.count) || 0;
            const money = el.dataset.money === '1';
            const fmt = function (v) {
                return money ? 'Rp ' + Math.round(v).toLocaleString('id-ID') : Math.round(v).toString();
            };
            if (reduceMotion || target === 0) { el.textContent = fmt(target); return; }
            const start = performance.now();
            const duration = 1200;
            (function tick(now) {
                const p = Math.min((now - start) / duration, 1);
                el.textContent = fmt(target * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(tick);
            })(start);
        }

        const io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('in');
                setTimeout(function () { entry.target.style.transitionDelay = ''; }, 900);
                entry.target.querySelectorAll('[data-count]').forEach(countUp);
                entry.target.querySelectorAll('.progress-line').forEach(function (line) {
                    line.style.width = line.dataset.width;
                });
                io.unobserve(entry.target);
            });
        }, { threshold: 0.15 });
        document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });

        // 4. Tambah ke keranjang langsung dari rekomendasi
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        document.querySelectorAll('[data-add-to-cart]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const original = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                fetch('{{ route('cart.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ product_id: btn.dataset.addToCart, quantity: 1 }),
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data.success) {
                            btn.innerHTML = '<i class="fas fa-check"></i>';
                            btn.classList.replace('btn-primary', 'btn-success');
                            updateCartCount(data.cart_count);
                        } else {
                            alert(data.message || 'Gagal menambahkan ke keranjang.');
                            btn.innerHTML = original;
                        }
                    })
                    .catch(function () {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                        btn.innerHTML = original;
                    })
                    .finally(function () {
                        setTimeout(function () {
                            btn.disabled = false;
                            btn.innerHTML = original;
                            btn.classList.replace('btn-success', 'btn-primary');
                        }, 1500);
                    });
            });
        });

        function updateCartCount(count) {
            document.querySelectorAll('[data-cart-count]').forEach(function (el) {
                el.textContent = count;
                el.classList.toggle('d-none', !count);
            });
        }
    });
</script>
@endpush

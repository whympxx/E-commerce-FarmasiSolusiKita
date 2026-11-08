@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card bg-gradient-primary text-white rounded-4 shadow-lg p-4 animate__animated animate__fadeInDown">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2 fw-bold">
                            <i class="fas fa-sun me-2 animate__animated animate__pulse animate__infinite"></i>
                            Selamat Datang, {{ Auth::user()->name }}!
                        </h2>
                        <p class="mb-0 opacity-75">Kelola toko farmasi Anda dengan mudah dan efisien</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="current-time">
                            <div class="time-display h4 mb-1">{{ now()->format('H:i') }}</div>
                            <div class="date-display">{{ now()->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Products -->
        <div class="col-xl-3 col-lg-6 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Total Produk</div>
                            <div class="stat-value h2 fw-bold text-primary mb-1">{{ $stats['total_products'] }}</div>
                            <div class="stat-subtitle text-success small">
                                <i class="fas fa-check-circle me-1"></i>{{ $stats['active_products'] }} aktif
                            </div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-pills fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: {{ ($stats['active_products'] / max($stats['total_products'], 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="col-xl-3 col-lg-6 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Total Kategori</div>
                            <div class="stat-value h2 fw-bold text-success mb-1">{{ $stats['total_categories'] }}</div>
                            <div class="stat-subtitle text-muted small">
                                <i class="fas fa-tags me-1"></i>kategori aktif
                            </div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-tags fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-xl-3 col-lg-6 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Stok Rendah</div>
                            <div class="stat-value h2 fw-bold text-warning mb-1">{{ $stats['low_stock'] }}</div>
                            <div class="stat-subtitle text-warning small">
                                <i class="fas fa-exclamation-triangle me-1"></i>perlu restok
                            </div>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning animate__animated animate__pulse animate__infinite"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: {{ ($stats['low_stock'] / max($stats['total_products'], 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="col-xl-3 col-lg-6 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Stok Habis</div>
                            <div class="stat-value h2 fw-bold text-danger mb-1">{{ $stats['out_of_stock'] }}</div>
                            <div class="stat-subtitle text-danger small">
                                <i class="fas fa-times-circle me-1"></i>produk kosong
                            </div>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-danger" style="width: {{ ($stats['out_of_stock'] / max($stats['total_products'], 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6 animate__animated animate__slideInLeft" style="animation-delay: 0.1s;">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stat-icon-large bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-shopping-bag fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold text-primary mb-1">{{ $stats['total_orders'] }}</h4>
                    <p class="text-muted mb-2">Total Pesanan</p>
                    <div class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
                        {{ $stats['pending_orders'] }} pending
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 animate__animated animate__slideInLeft" style="animation-delay: 0.2s;">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stat-icon-large bg-success bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-users fa-2x text-success"></i>
                    </div>
                    <h4 class="fw-bold text-success mb-1">{{ $stats['total_users'] }}</h4>
                    <p class="text-muted mb-2">Total Pengguna</p>
                    <div class="badge bg-success bg-opacity-10 text-success rounded-pill">
                        terdaftar
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 animate__animated animate__slideInLeft" style="animation-delay: 0.3s;">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stat-icon-large bg-info bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-shopping-cart fa-2x text-info"></i>
                    </div>
                    <h4 class="fw-bold text-info mb-1">{{ $stats['active_carts'] }}</h4>
                    <p class="text-muted mb-2">Keranjang Aktif</p>
                    <div class="badge bg-info bg-opacity-10 text-info rounded-pill">
                        sedang berbelanja
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 animate__animated animate__slideInLeft" style="animation-delay: 0.4s;">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="stat-icon-large bg-warning bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-chart-line fa-2x text-warning"></i>
                    </div>
                    <h4 class="fw-bold text-warning mb-1">-</h4>
                    <p class="text-muted mb-2">Penjualan Bulan Ini</p>
                    <div class="badge bg-warning bg-opacity-10 text-warning rounded-pill">
                        segera hadir
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Recent Products -->
        <div class="col-lg-6 animate__animated animate__slideInLeft" style="animation-delay: 0.2s;">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 d-flex justify-content-between align-items-center p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-clock me-2"></i>Produk Terbaru
                    </h6>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm rounded-pill">
                        <i class="fas fa-eye me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3 fw-semibold">Produk</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Kategori</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Harga</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Stok</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentProducts as $product)
                                        <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="product-avatar bg-light rounded-circle me-3 d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0" style="width: 40px; height: 40px;">
                                                        @if($product && $product->image_url)
                                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <i class="fas fa-pills text-muted"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $product->name }}</div>
                                                        <small class="text-muted">{{ $product->sku }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">
                                                    {{ $product->category->name }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 fw-semibold">{{ $product->formatted_price }}</td>
                                            <td class="px-4 py-3">
                                                @if($product->manage_stock)
                                                    <span class="badge badge-stock-{{ $product->stock_quantity == 0 ? 'out' : ($product->stock_quantity <= 10 ? 'low' : 'normal') }} rounded-pill">
                                                        {{ $product->stock_quantity }} {{ $product->unit }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary rounded-pill">Unlimited</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge badge-status-{{ $product->status }} rounded-pill">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-pills fa-3x text-muted"></i>
                            </div>
                            <p class="text-muted mb-0">Belum ada produk terbaru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="col-lg-6 animate__animated animate__slideInRight" style="animation-delay: 0.3s;">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header bg-gradient-warning text-white border-0 rounded-top-4 d-flex justify-content-between align-items-center p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Produk Stok Rendah
                    </h6>
                    <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="btn btn-light btn-sm rounded-pill">
                        <i class="fas fa-exclamation-triangle me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($lowStockProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3 fw-semibold">Produk</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Kategori</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Stok</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockProducts as $product)
                                        <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="product-avatar bg-light rounded-circle me-3 d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0" style="width: 40px; height: 40px;">
                                                        @if($product && $product->image_url)
                                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <i class="fas fa-pills text-muted"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $product->name }}</div>
                                                        <small class="text-muted">{{ $product->sku }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">
                                                    {{ $product->category->name }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge badge-stock-{{ $product->stock_quantity == 0 ? 'out' : 'low' }} rounded-pill">
                                                    {{ $product->stock_quantity }} {{ $product->unit }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-check-circle fa-3x text-success"></i>
                            </div>
                            <p class="text-muted mb-0">Semua produk memiliki stok yang cukup</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-gradient-info text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary w-100 rounded-pill py-3 fw-semibold shadow-sm">
                                <i class="fas fa-plus me-2"></i>Tambah Produk
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-success w-100 rounded-pill py-3 fw-semibold shadow-sm">
                                <i class="fas fa-plus me-2"></i>Tambah Kategori
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="btn btn-warning w-100 rounded-pill py-3 fw-semibold shadow-sm">
                                <i class="fas fa-exclamation-triangle me-2"></i>Cek Stok Rendah
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-info w-100 rounded-pill py-3 fw-semibold shadow-sm">
                                <i class="fas fa-shopping-bag me-2"></i>Kelola Pesanan
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary w-100 rounded-pill py-3 fw-semibold shadow-sm">
                                <i class="fas fa-users me-2"></i>Kelola Pelanggan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
/* Additional Custom Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.05);
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.stat-icon-large {
    transition: all 0.3s ease;
}

.card:hover .stat-icon-large {
    transform: scale(1.1);
}

.product-avatar {
    transition: all 0.3s ease;
}

.table tbody tr:hover .product-avatar {
    transform: scale(1.1);
    background: var(--bs-primary) !important;
}

.table tbody tr:hover .product-avatar i {
    color: white !important;
}

.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.welcome-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.current-time {
    background: rgba(255,255,255,0.2);
    padding: 1rem;
    border-radius: 1rem;
    backdrop-filter: blur(10px);
}

.time-display {
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.date-display {
    font-size: 0.9rem;
    opacity: 0.9;
}

.empty-state {
    padding: 3rem 1rem;
}

.empty-icon {
    opacity: 0.5;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .welcome-card .row {
        text-align: center;
    }
    
    .current-time {
        margin-top: 1rem;
    }
    
    .stat-card .card-body {
        padding: 1.5rem;
    }
    
    .card-header {
        padding: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
<script>
// Update time every second
function updateTime() {
    const now = new Date();
    const timeDisplay = document.querySelector('.time-display');
    const dateDisplay = document.querySelector('.date-display');
    
    if (timeDisplay && dateDisplay) {
        timeDisplay.textContent = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        dateDisplay.textContent = now.toLocaleDateString('id-ID', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        });
    }
}

// Update time immediately and then every second
updateTime();
setInterval(updateTime, 1000);

// Add hover effects to cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.stat-card, .card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush

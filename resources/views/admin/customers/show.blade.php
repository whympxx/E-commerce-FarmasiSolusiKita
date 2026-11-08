@extends('layouts.admin')

@section('title', 'Detail Pelanggan - ' . $customer->name)
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="customer-detail-container">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
        </a>
    </div>

    <!-- Customer Profile Card -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm animate__animated animate__fadeInLeft">
                <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-user me-2"></i>Informasi Pelanggan
                        </h6>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.customers.edit', $customer) }}" 
                               class="btn btn-light btn-sm rounded-pill">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <form action="{{ route('admin.customers.destroy', $customer) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light btn-sm rounded-pill">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="customer-avatar-large bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-3x text-muted"></i>
                            </div>
                            <h4 class="text-center fw-bold mb-1">{{ $customer->name }}</h4>
                            <p class="text-center text-muted mb-3">{{ $customer->email }}</p>
                            
                            <div class="d-flex justify-content-center gap-2 mb-3">
                                @if($customer->email_verified_at)
                                    <span class="badge bg-success rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                    </span>
                                @else
                                    <span class="badge bg-warning rounded-pill">
                                        <i class="fas fa-exclamation-circle me-1"></i>Belum Verifikasi
                                    </span>
                                @endif
                                @if($stats['total_orders'] > 0)
                                    <span class="badge bg-info rounded-pill">
                                        <i class="fas fa-shopping-bag me-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">
                                        <i class="fas fa-user-clock me-1"></i>Tidak Aktif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="customer-info">
                                <div class="info-item mb-3">
                                    <label class="text-muted small fw-semibold">ID Pelanggan</label>
                                    <div class="fw-semibold">#{{ $customer->id }}</div>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="text-muted small fw-semibold">Tanggal Registrasi</label>
                                    <div class="fw-semibold">{{ $customer->created_at->format('d F Y') }}</div>
                                    <small class="text-muted">{{ $customer->created_at->format('H:i') }}</small>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="text-muted small fw-semibold">Status Email</label>
                                    <div class="fw-semibold">
                                        @if($customer->email_verified_at)
                                            <span class="text-success">
                                                <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                            </span>
                                            <br><small class="text-muted">{{ $customer->email_verified_at->format('d/m/Y H:i') }}</small>
                                        @else
                                            <span class="text-warning">
                                                <i class="fas fa-exclamation-circle me-1"></i>Belum Verifikasi
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label class="text-muted small fw-semibold">Terakhir Login</label>
                                    <div class="fw-semibold text-muted">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="col-lg-4">
            <div class="row g-3">
                <div class="col-12 animate__animated animate__fadeInRight" style="animation-delay: 0.1s;">
                    <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="stat-icon-large bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-shopping-bag fa-2x text-primary"></i>
                            </div>
                            <h4 class="fw-bold text-primary mb-1">{{ $stats['total_orders'] }}</h4>
                            <p class="text-muted mb-0">Total Pesanan</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 animate__animated animate__fadeInRight" style="animation-delay: 0.2s;">
                    <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="stat-icon-large bg-success bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                            </div>
                            <h4 class="fw-bold text-success mb-1">{{ $customer->formatted_total_spent }}</h4>
                            <p class="text-muted mb-0">Total Belanja</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 animate__animated animate__fadeInRight" style="animation-delay: 0.3s;">
                    <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="stat-icon-large bg-info bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-chart-line fa-2x text-info"></i>
                            </div>
                            <h4 class="fw-bold text-info mb-1">{{ $customer->average_order_value }}</h4>
                            <p class="text-muted mb-0">Rata-rata Pesanan</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 animate__animated animate__fadeInRight" style="animation-delay: 0.4s;">
                    <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="stat-icon-large bg-warning bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-shopping-cart fa-2x text-warning"></i>
                            </div>
                            <h4 class="fw-bold text-warning mb-1">{{ $stats['active_cart_items'] }}</h4>
                            <p class="text-muted mb-0">Item di Keranjang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card border-0 rounded-4 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
        <div class="card-header bg-gradient-info text-white border-0 rounded-top-4 d-flex justify-content-between align-items-center p-4">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-shopping-bag me-2"></i>Riwayat Pesanan
            </h6>
            @if($stats['total_orders'] > 0)
                <a href="{{ route('admin.orders.index', ['customer' => $customer->id]) }}" class="btn btn-light btn-sm rounded-pill">
                    <i class="fas fa-eye me-1"></i>Lihat Semua
                </a>
            @endif
        </div>
        <div class="card-body p-0">
            @if($customer->orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-4 py-3 fw-semibold">Order ID</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Tanggal</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Status</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Total</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->orders as $order)
                                <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                                    <td class="px-4 py-3">
                                        <div class="fw-semibold">{{ $order->order_number }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-muted small">
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $order->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge badge-status-{{ $order->status }} rounded-pill">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="fw-semibold text-success">
                                            {{ $order->formatted_total_amount }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="btn btn-outline-primary btn-sm rounded-pill">
                                            <i class="fas fa-eye me-1"></i>Detail
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
                        <i class="fas fa-shopping-bag fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum ada pesanan</h5>
                    <p class="text-muted mb-0">Pelanggan ini belum melakukan pesanan apapun</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Active Cart Items -->
    @if($stats['active_cart_items'] > 0)
        <div class="card border-0 rounded-4 shadow-sm mt-4 animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
            <div class="card-header bg-gradient-warning text-white border-0 rounded-top-4 p-4">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-shopping-cart me-2"></i>Item di Keranjang
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-4 py-3 fw-semibold">Produk</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Harga</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Jumlah</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->carts as $cart)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="product-avatar bg-light rounded-circle me-3 d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0" style="width: 40px; height: 40px;">
                                                @if($cart->product && $cart->product->image_url)
                                                    <img src="{{ $cart->product->image_url }}" alt="{{ $cart->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <i class="fas fa-pills text-muted"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $cart->product->name }}</div>
                                                <small class="text-muted">{{ $cart->product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ $cart->product->formatted_price }}</td>
                                    <td class="px-4 py-3">{{ $cart->quantity }}</td>
                                    <td class="px-4 py-3 fw-semibold">{{ $cart->formatted_total_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-customers.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@push('scripts')
<script src="{{ asset('js/admin-customers.js') }}"></script>
@endpush 
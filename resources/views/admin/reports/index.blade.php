@extends('layouts.admin')

@section('title', 'Laporan - Admin Panel')
@section('page-title', 'Laporan')

@php
    // Prevent browser caching for this page
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
@endphp

@section('content')
@if(!isset($reportsContentLoaded))
    @php $reportsContentLoaded = true; @endphp
    <div class="reports-container">
@else
    <div class="reports-container" style="display: none;">
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Laporan sedang dimuat ulang...
        </div>
    </div>
    <script>
        setTimeout(function() {
            window.location.reload();
        }, 1000);
    </script>
@endif
    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-filter me-2"></i>Filter Laporan
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.reports.index') }}" method="GET" id="reportFilterForm">
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <label for="report_type" class="form-label fw-semibold">Jenis Laporan</label>
                                <select class="form-select rounded-pill" id="report_type" name="report_type">
                                    <option value="sales" {{ request('report_type') == 'sales' ? 'selected' : '' }}>Laporan Penjualan</option>
                                    <option value="products" {{ request('report_type') == 'products' ? 'selected' : '' }}>Laporan Produk</option>
                                    <option value="customers" {{ request('report_type') == 'customers' ? 'selected' : '' }}>Laporan Pelanggan</option>
                                    <option value="inventory" {{ request('report_type') == 'inventory' ? 'selected' : '' }}>Laporan Inventori</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label for="date_from" class="form-label fw-semibold">Tanggal Mulai</label>
                                <input type="date" class="form-control rounded-pill" id="date_from" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label for="date_to" class="form-label fw-semibold">Tanggal Akhir</label>
                                <input type="date" class="form-control rounded-pill" id="date_to" name="date_to" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-semibold">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary rounded-pill">
                                        <i class="fas fa-search me-2"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Total Pendapatan</div>
                            <div class="stat-value h3 fw-bold text-success mb-1">Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</div>
                            <div class="stat-subtitle text-success small">
                                <i class="fas fa-arrow-up me-1"></i>{{ $summary['revenue_growth'] ?? 0 }}% dari periode sebelumnya
                            </div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Total Pesanan</div>
                            <div class="stat-value h3 fw-bold text-primary mb-1">{{ $summary['total_orders'] ?? 0 }}</div>
                            <div class="stat-subtitle text-primary small">
                                <i class="fas fa-shopping-bag me-1"></i>{{ $summary['completed_orders'] ?? 0 }} selesai
                            </div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-shopping-bag fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Produk Terjual</div>
                            <div class="stat-value h3 fw-bold text-info mb-1">{{ $summary['total_products_sold'] ?? 0 }}</div>
                            <div class="stat-subtitle text-info small">
                                <i class="fas fa-pills me-1"></i>{{ $summary['unique_products'] ?? 0 }} jenis produk
                            </div>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-pills fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Pelanggan Baru</div>
                            <div class="stat-value h3 fw-bold text-warning mb-1">{{ $summary['new_customers'] ?? 0 }}</div>
                            <div class="stat-subtitle text-warning small">
                                <i class="fas fa-users me-1"></i>{{ $summary['total_customers'] ?? 0 }} total pelanggan
                            </div>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-users fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header bg-gradient-info text-white border-0 rounded-top-4 d-flex justify-content-between align-items-center p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line me-2"></i>Grafik Penjualan
                    </h6>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-light btn-sm rounded-start-pill" data-period="7">7 Hari</button>
                        <button type="button" class="btn btn-light btn-sm" data-period="30">30 Hari</button>
                        <button type="button" class="btn btn-light btn-sm rounded-end-pill" data-period="90">90 Hari</button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header bg-gradient-success text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-chart-pie me-2"></i>Kategori Terlaris
                    </h6>
                </div>
                <div class="card-body p-4">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="row g-4">
        <!-- Top Products -->
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header bg-gradient-warning text-white border-0 rounded-top-4 d-flex justify-content-between align-items-center p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-star me-2"></i>Produk Terlaris
                    </h6>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm rounded-pill">
                        <i class="fas fa-eye me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(isset($data['topProducts']) && $data['topProducts']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3 fw-semibold">Produk</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Terjual</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Pendapatan</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['topProducts'] as $product)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="product-avatar bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-pills text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $product->name }}</div>
                                                        <small class="text-muted">{{ $product->category->name ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 fw-semibold">{{ $product->total_sold ?? 0 }}</td>
                                            <td class="px-4 py-3 fw-semibold text-success">Rp {{ number_format($product->total_revenue ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-warning me-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star-o"></i>
                                                        @endfor
                                                    </div>
                                                    <small class="text-muted">(N/A)</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-chart-bar fa-3x text-muted"></i>
                            </div>
                            <p class="text-muted mb-0">Belum ada data produk terlaris</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 d-flex justify-content-between align-items-center p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-clock me-2"></i>Pesanan Terbaru
                    </h6>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm rounded-pill">
                        <i class="fas fa-eye me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(isset($data['recentOrders']) && $data['recentOrders']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3 fw-semibold">Order ID</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Pelanggan</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Total</th>
                                        <th class="border-0 px-4 py-3 fw-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['recentOrders'] as $order)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="fw-semibold">#{{ $order->id }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="fw-semibold">{{ $order->user->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td class="px-4 py-3 fw-semibold text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3">
                                                <span class="badge badge-status-{{ $order->status }} rounded-pill">
                                                    {{ ucfirst($order->status) }}
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
                                <i class="fas fa-shopping-bag fa-3x text-muted"></i>
                            </div>
                            <p class="text-muted mb-0">Belum ada pesanan terbaru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Export Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-gradient-secondary text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-download me-2"></i>Export Laporan
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.reports.export', ['type' => 'sales', 'format' => 'pdf']) }}" class="btn btn-danger w-100 rounded-pill py-3 fw-semibold shadow-sm">
                                <i class="fas fa-file-pdf me-2"></i>Export PDF
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.reports.export', ['type' => 'sales', 'format' => 'excel']) }}" class="btn btn-success w-100 rounded-pill py-3 fw-semibold shadow-sm">
                                <i class="fas fa-file-excel me-2"></i>Export Excel
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.reports.export', ['type' => 'inventory', 'format' => 'pdf']) }}" class="btn btn-warning w-100 rounded-pill py-3 fw-semibold shadow-sm">
                                <i class="fas fa-boxes me-2"></i>Laporan Stok
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.reports.export', ['type' => 'customers', 'format' => 'excel']) }}" class="btn btn-info w-100 rounded-pill py-3 fw-semibold shadow-sm">
                                <i class="fas fa-users me-2"></i>Data Pelanggan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@if(isset($reportsContentLoaded))
</div>
@endif
@endsection

@push('styles')
@if(!isset($reportsStylesLoaded))
    @php $reportsStylesLoaded = true; @endphp
    <link rel="stylesheet" href="{{ asset('css/admin-reports.css') }}">
@endif
@endpush

@push('scripts')
@if(!isset($reportsScriptsLoaded))
    @php $reportsScriptsLoaded = true; @endphp
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin-reports.js') }}"></script>
@endif
@endpush 
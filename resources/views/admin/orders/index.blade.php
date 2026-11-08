@extends('layouts.admin')

@section('title', 'Orders Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-orders.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    /* Modern Design System */
    :root {
        --primary-gradient: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        --secondary-gradient: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        --success-gradient: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
        --warning-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
        --danger-gradient: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
        --glass-bg: rgba(255, 255, 255, 0.25);
        --glass-border: rgba(255, 255, 255, 0.18);
        --shadow-light: 0 8px 32px rgba(0, 0, 0, 0.1);
        --shadow-medium: 0 15px 35px rgba(0, 0, 0, 0.15);
        --border-radius: 20px;
        --border-radius-sm: 12px;
    }

    /* Responsive Container */
    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* Fix for admin sidebar overlap */
    .main-content {
        margin-left: var(--sidebar-width);
        min-height: 100vh;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .main-content.expanded {
        margin-left: var(--sidebar-collapsed-width);
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }

        .main-content.expanded {
            margin-left: 0;
        }
    }

    /* Modern Header with Better Responsiveness */
    .modern-gradient {
        background: var(--primary-gradient);
        border-radius: var(--border-radius);
        position: relative;
        overflow: hidden;
    }
    
    .glass-effect {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
    }
    
    .floating-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: var(--shadow-light);
        border-radius: var(--border-radius-sm);
        height: 100%;
    }
    
    .floating-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-medium);
    }
    
    /* Enhanced Status Badges */
    .status-badge {
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        min-width: 100px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .status-pending { background: #fffbe6; color: #b8860b; border: 1px solid #ffe58f; }
    .status-processing { background: #e6f7ff; color: #096dd9; border: 1px solid #91d5ff; }
    .status-shipped { background: #f0fff0; color: #389e0d; border: 1px solid #b7eb8f; }
    .status-delivered { background: #e6fffb; color: #13c2c2; border: 1px solid #87e8de; }
    .status-cancelled { background: #fff1f0; color: #cf1322; border: 1px solid #ffa39e; }
    .payment-pending { background: #fffbe6; color: #b8860b; border: 1px solid #ffe58f; }
    .payment-paid { background: #f6ffed; color: #237804; border: 1px solid #b7eb8f; }
    .payment-failed { background: #fff1f0; color: #cf1322; border: 1px solid #ffa39e; }
    .payment-refunded { background: #f9f0ff; color: #722ed1; border: 1px solid #d3adf7; }
    
    /* Enhanced Metric Cards */
    .metric-card {
        background: var(--primary-gradient);
        color: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .metric-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Improved Search Box */
    .search-box {
        background: white;
        border-radius: var(--border-radius-sm);
        padding: 1.5rem;
        box-shadow: var(--shadow-light);
        border: none;
        margin-bottom: 1.5rem;
    }
    
    /* Enhanced Table Design */
    .table-modern {
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-light);
        margin-bottom: 2rem;
    }
    
    .table-modern thead th {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 1.5rem 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        white-space: nowrap;
    }
    
    /* Table Modern - Tambah padding dan hover effect lebih halus */
    .table-modern tbody tr {
        transition: background 0.2s, transform 0.2s;
        border-bottom: 1px solid #f0f0f0;
    }
    .table-modern tbody tr:hover {
        background: #f5f7fa;
        transform: scale(1.005);
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .table-modern tbody td {
        padding: 1.7rem 1.2rem;
        vertical-align: middle;
        border: none;
    }
    
    /* Responsive Table */
    @media (max-width: 768px) {
        .table-modern {
            font-size: 0.9rem;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 1rem 0.5rem;
        }
        
        .table-modern thead th {
            font-size: 0.75rem;
        }
    }
    
    /* Enhanced Buttons */
    .btn-modern {
        border-radius: var(--border-radius-sm);
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
    }
    
    .btn-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-modern:hover::before {
        left: 100%;
    }
    
    .btn-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    /* Responsive Button Groups */
    .btn-group-responsive {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .btn-group-responsive .btn {
        flex: 1;
        min-width: 120px;
    }
    
    @media (max-width: 576px) {
        .btn-group-responsive {
            flex-direction: column;
        }
        
        .btn-group-responsive .btn {
            width: 100%;
        }
    }
    
    /* Enhanced Form Controls */
    .form-control-modern {
        border-radius: var(--border-radius-sm);
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .form-control-modern:focus {
        border-color: #1e40af;
        box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
        background: white;
    }
    
    /* Responsive Form Layout */
    .form-row-responsive {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    @media (max-width: 768px) {
        .form-row-responsive {
            grid-template-columns: 1fr;
        }
    }
    
    /* Enhanced Pagination */
    .pagination-modern .page-link {
        border-radius: 10px;
        margin: 0 2px;
        border: none;
        color: #1e40af;
        font-weight: 600;
    }
    
    .pagination-modern .page-item.active .page-link {
        background: var(--primary-gradient);
        color: white;
    }
    
    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loading-spinner {
        text-align: center;
        color: white;
    }
    
    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0 10px;
        }
        
        .modern-gradient {
            padding: 1.5rem !important;
        }
        
        .metric-card {
            padding: 1.5rem;
            min-height: 100px;
        }
        
        .search-box {
            padding: 1rem;
        }
        
        .floating-card {
            margin-bottom: 1rem;
        }
        
        .table-responsive {
            border-radius: var(--border-radius-sm);
        }
        
        /* Hide less important columns on mobile */
        .table-modern th:nth-child(3),
        .table-modern td:nth-child(3),
        .table-modern th:nth-child(4),
        .table-modern td:nth-child(4) {
            display: none;
        }

        /* Ensure content doesn't overlap with sidebar on mobile */
        .content-area {
            padding: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .modern-gradient h1 {
            font-size: 1.5rem;
        }
        
        .modern-gradient p {
            font-size: 0.9rem;
        }
        
        .metric-card h2 {
            font-size: 1.5rem;
        }
        
        .btn-modern {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
    }
    
    /* Enhanced Order Cards for Mobile */
    .order-card-mobile {
        display: none;
        background: white;
        border-radius: var(--border-radius-sm);
        padding: 1.7rem 1.2rem;
        margin-bottom: 1.2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        border-left: 4px solid #1e40af;
    }
    .order-card-mobile .badge {
        font-size: 0.95rem;
    }
    .order-card-mobile .fw-bold {
        font-size: 1.1rem;
    }
    .order-card-mobile .action-buttons {
        margin-top: 0.7rem;
    }
    
    .order-card-mobile:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
    }
    
    @media (max-width: 768px) {
        .order-card-mobile {
            display: block;
        }
        
        .table-modern {
            display: none;
        }
    }

    /* Ensure content doesn't overlap with admin sidebar */
    .content-area {
        padding: 1.5rem;
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
        position: relative;
        z-index: 1;
    }

    /* Adjust container for admin layout */
    .container-fluid {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Override admin layout spacing for this page */
    .main-content .content-area {
        margin-left: 0 !important;
        width: 100% !important;
    }

    /* Ensure sidebar doesn't interfere with content */
    .sidebar {
        z-index: 1000;
    }

    .main-content {
        position: relative;
        z-index: 1;
    }

    /* Ensure proper spacing on all screen sizes */
    @media (min-width: 769px) {
        .content-area {
            padding: 2rem;
        }
        
        /* Desktop: ensure full width content */
        .main-content .content-area {
            margin-left: 0 !important;
            width: 100% !important;
        }
    }

    @media (max-width: 768px) {
        .content-area {
            padding: 1rem;
        }
        
        .container-fluid {
            padding: 0 10px;
        }
        
            /* Mobile: ensure no sidebar interference */
    .main-content {
        margin-left: 0 !important;
    }
    
    .main-content .content-area {
        margin-left: 0 !important;
        width: 100% !important;
    }

    /* Ensure content is always visible */
    .content-area {
        min-height: calc(100vh - 70px); /* Account for top navbar */
    }

    /* Fix for any potential overflow issues */
    body {
        overflow-x: hidden;
    }

    .main-content {
        overflow-x: hidden;
    }
    }
    
    /* Toast Notifications */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
    }
    
    .toast-custom {
        background: white;
        border-radius: var(--border-radius-sm);
        box-shadow: var(--shadow-medium);
        border: none;
        margin-bottom: 10px;
    }
    
    /* Enhanced Status Selects */
    .status-select-modern {
        border-radius: var(--border-radius-sm);
        border: 2px solid #e9ecef;
        padding: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        min-width: 120px;
    }
    
    .status-select-modern:focus {
        border-color: #1e40af;
        box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
    }
    
    /* Action Buttons Enhancement */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .action-buttons .btn {
        padding: 0.5rem;
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
    }
    
    .action-buttons .btn:hover {
        transform: translateY(-2px);
    }
    
    @media (max-width: 576px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
    }

    /* Tombol aksi konsisten dan tooltip */
    .action-buttons .btn {
        padding: 0.5rem 0.7rem;
        border-radius: var(--border-radius-sm);
        transition: all 0.2s;
        font-size: 1rem;
        position: relative;
    }
    .action-buttons .btn[title]:hover::after {
        content: attr(title);
        position: absolute;
        left: 50%;
        top: -30px;
        transform: translateX(-50%);
        background: #222;
        color: #fff;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        white-space: nowrap;
        z-index: 10;
        opacity: 0.95;
    }

    /* --- RAPIH & RESPONSIVE ORDER LAYOUT --- */
    :root {
        --order-max-width: 1140px;
    }
    .container-fluid {
        max-width: var(--order-max-width);
        margin: 0 auto;
        padding-left: 12px;
        padding-right: 12px;
        box-sizing: border-box;
    }
    .row {
        margin-left: -8px;
        margin-right: -8px;
    }
    [class^="col-"], [class*=" col-"] {
        padding-left: 8px;
        padding-right: 8px;
        box-sizing: border-box;
    }
    .modern-gradient,
    .search-box,
    .table-modern,
    .order-card-mobile {
        margin-bottom: 1.25rem;
    }
    .modern-gradient {
        padding: 2rem 1.5rem;
    }
    .metric-card {
        padding: 1.5rem 1rem;
        min-height: 100px;
    }
    .search-box {
        padding: 1.25rem 1rem;
    }
    .table-modern {
        margin-bottom: 1.5rem;
    }
    .card-header, .card-footer {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
    .order-card-mobile {
        margin-bottom: 1rem;
        padding: 1.25rem 1rem;
    }
    .btn-group-responsive {
        gap: 0.5rem;
        justify-content: flex-end;
    }
    @media (max-width: 991px) {
        .modern-gradient, .search-box, .table-modern, .order-card-mobile {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .metric-card {
            padding: 1rem 0.5rem;
        }
    }
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 4px;
            padding-right: 4px;
        }
        .modern-gradient, .search-box, .table-modern, .order-card-mobile {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .row {
            margin-left: -4px;
            margin-right: -4px;
        }
        [class^="col-"], [class*=" col-"] {
            padding-left: 4px;
            padding-right: 4px;
        }
        .btn-group-responsive {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }
        .search-box {
            margin-bottom: 1rem;
        }
        .table-modern {
            font-size: 0.95rem;
        }
        .card-header, .card-footer {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    }
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }
        .modern-gradient, .search-box, .table-modern, .order-card-mobile {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }
        .btn-modern {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
    /* Table scroll for mobile */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    /* Card mobile view spacing */
    .order-card-mobile .row {
        margin-left: 0;
        margin-right: 0;
    }
    .order-card-mobile [class^="col-"], .order-card-mobile [class*=" col-"] {
        padding-left: 0;
        padding-right: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Modern Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-gradient text-white rounded-4 p-4 position-relative overflow-hidden">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-md-7 col-sm-12">
                        <h1 class="display-4 fw-bold mb-2">
                            <i class="fas fa-shopping-bag me-3"></i>
                            Orders Management
                        </h1>
                        <p class="lead mb-0 opacity-75">Manage customer orders and track delivery status with real-time updates</p>
                    </div>
                    <div class="col-lg-4 col-md-5 col-sm-12 text-center text-md-end mt-3 mt-md-0">
                        <div class="metric-card">
                            <h2 class="display-6 fw-bold mb-1">{{ $orders->total() }}</h2>
                            <p class="mb-0 opacity-75">Total Orders</p>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10 d-none d-lg-block">
                    <i class="fas fa-chart-line fa-8x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="floating-card card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <h4 class="fw-bold text-warning">{{ $orders->where('status', 'pending')->count() }}</h4>
                    <p class="text-muted mb-0">Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="floating-card card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-cog fa-2x text-info"></i>
                    </div>
                    <h4 class="fw-bold text-info">{{ $orders->where('status', 'processing')->count() }}</h4>
                    <p class="text-muted mb-0">Processing</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="floating-card card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-shipping-fast fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold text-primary">{{ $orders->where('status', 'shipped')->count() }}</h4>
                    <p class="text-muted mb-0">Shipped</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="floating-card card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <h4 class="fw-bold text-success">{{ $orders->where('status', 'delivered')->count() }}</h4>
                    <p class="text-muted mb-0">Delivered</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-box">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="btn-group-responsive">
                            <a href="{{ route('admin.orders.export') }}" class="btn btn-modern btn-success">
                                <i class="fas fa-download me-2"></i> Export Orders
                            </a>
                            <button class="btn btn-modern btn-outline-primary" onclick="refreshOrders()">
                                <i class="fas fa-sync-alt me-2"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 text-center text-md-end mt-3 mt-md-0">
                        <div class="btn-group-responsive">
                            <button type="button" class="btn btn-modern btn-outline-secondary" onclick="toggleFilters()">
                                <i class="fas fa-filter me-2"></i> Filters
                            </button>
                            <button type="button" class="btn btn-modern btn-outline-secondary" onclick="toggleView()">
                                <i class="fas fa-th-large me-2"></i> Grid View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-4" id="filterSection">
        <div class="col-12">
            <div class="search-box">
                <form method="GET" action="{{ route('admin.orders.index') }}" id="filterForm">
                    <div class="form-row-responsive">
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-modern" id="search" name="search" 
                                   placeholder="Search orders..." value="{{ request('search') }}">
                            <label for="search">
                                <i class="fas fa-search me-2"></i>Search Orders
                            </label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select form-control-modern" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <label for="status">Order Status</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select form-control-modern" id="payment_status" name="payment_status">
                                <option value="">All Payment Status</option>
                                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                            <label for="payment_status">Payment Status</label>
                        </div>
                        <div class="form-floating">
                            <input type="date" class="form-control form-control-modern" id="date_from" name="date_from" 
                                   value="{{ request('date_from') }}">
                            <label for="date_from">Date From</label>
                        </div>
                        <div class="form-floating">
                            <input type="date" class="form-control form-control-modern" id="date_to" name="date_to" 
                                   value="{{ request('date_to') }}">
                            <label for="date_to">Date To</label>
                        </div>
                        <div class="d-flex gap-2 align-items-end">
                            <button type="submit" class="btn btn-modern btn-primary flex-fill">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-modern btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="table-modern">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 col-sm-12">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-list me-2"></i>
                                Orders List ({{ $orders->total() }} total)
                            </h5>
                        </div>
                        <div class="col-md-6 col-sm-12 text-center text-md-end mt-3 mt-md-0">
                            <div class="btn-group-responsive">
                                <a href="{{ route('admin.orders.export', request()->query()) }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-file-csv me-1"></i> CSV
                                </a>
                                <button type="button" class="btn btn-light btn-sm" onclick="showToast('info', 'Export Feature', 'PDF export feature coming soon!');">
                                    <i class="fas fa-file-pdf me-1"></i> PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($orders->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-modern mb-0" id="ordersTable">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-2"></i>Order ID</th>
                                    <th><i class="fas fa-user me-2"></i>Customer</th>
                                    <th><i class="fas fa-calendar me-2"></i>Date</th>
                                    <th><i class="fas fa-box me-2"></i>Items</th>
                                    <th><i class="fas fa-money-bill me-2"></i>Total</th>
                                    <th><i class="fas fa-truck me-2"></i>Status</th>
                                    <th><i class="fas fa-credit-card me-2"></i>Payment</th>
                                    <th class="text-center"><i class="fas fa-cogs me-2"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $index => $order)
                                <tr class="order-row animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.1 }}s">
                                    <td class="order-number">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-shopping-bag"></i>
                                            </div>
                                            <div>
                                                <strong class="text-primary">#{{ $order->id }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                            <div>
                                                <strong class="customer-name">{{ $order->customer_name }}</strong>
                                                <br>
                                                <small class="text-muted customer-email">
                                                    <i class="fas fa-envelope me-1"></i>{{ $order->customer_email }}
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="bg-light rounded p-2">
                                                <div class="fw-bold text-primary">{{ $order->created_at->format('d') }}</div>
                                                <small class="text-muted">{{ $order->created_at->format('M Y') }}</small>
                                            </div>
                                            <small class="text-muted d-block mt-1">{{ $order->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info rounded-pill fs-6">
                                            <i class="fas fa-box me-1"></i>{{ $order->items->count() }} items
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success fs-5">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td>
                                        <select class="form-select status-select-modern order-status-select" 
                                                data-order-id="{{ $order->id }}" 
                                                data-current-status="{{ $order->status }}">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select status-select-modern payment-status-select" 
                                                data-order-id="{{ $order->id }}" 
                                                data-current-status="{{ $order->payment_status }}">
                                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="btn btn-outline-info btn-sm" title="View Order Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.orders.invoice', $order->id) }}" 
                                               class="btn btn-outline-success btn-sm" title="Generate Invoice" target="_blank">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                            @if($order->status === 'shipped' || $order->status === 'processing')
                                            <a href="{{ route('admin.orders.shipping-label', $order->id) }}" 
                                               class="btn btn-outline-warning btn-sm" title="Print Shipping Label" target="_blank">
                                                <i class="fas fa-shipping-fast"></i>
                                            </a>
                                            @endif
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                        data-bs-toggle="dropdown" title="More Actions">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('admin.orders.edit', $order->id) }}"><i class="fas fa-edit me-2"></i>Edit Order</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank"><i class="fas fa-print me-2"></i>Print Receipt</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline delete-order-form" onsubmit="return confirm('Are you sure you want to delete order #{{ $order->id }}?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="event.stopPropagation();"><i class="fas fa-trash me-2"></i>Delete Order</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="d-md-none">
                        @foreach($orders as $index => $order)
                        <div class="order-card-mobile animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.1 }}s">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <div>
                                            <strong class="text-primary">#{{ $order->id }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                                        </div>
                                    </div>
                                    <h6 class="mb-1">{{ $order->customer_name }}</h6>
                                    <small class="text-muted d-block mb-2">
                                        <i class="fas fa-envelope me-1"></i>{{ $order->customer_email }}
                                    </small>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-info rounded-pill">
                                            <i class="fas fa-box me-1"></i>{{ $order->items->count() }} items
                                        </span>
                                        <div class="fw-bold text-success">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-2">
                                        <select class="form-select form-select-sm status-select-modern order-status-select" 
                                                data-order-id="{{ $order->id }}" 
                                                data-current-status="{{ $order->status }}">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <select class="form-select form-select-sm status-select-modern payment-status-select" 
                                                data-order-id="{{ $order->id }}" 
                                                data-current-status="{{ $order->payment_status }}">
                                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                    </div>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                           class="btn btn-outline-info btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.invoice', $order->id) }}" 
                                           class="btn btn-outline-success btn-sm" title="Invoice" target="_blank">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-outline-secondary btn-sm" title="Edit Order">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline delete-order-form" onsubmit="return confirm('Are you sure you want to delete order #{{ $order->id }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete Order">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Enhanced Pagination -->
                    <div class="card-footer bg-light border-0 p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6 col-sm-12 text-center text-md-start mb-3 mb-md-0">
                                <p class="mb-0 text-muted">
                                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
                                </p>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <nav aria-label="Orders pagination" class="d-flex justify-content-center justify-content-md-end">
                                    {{ $orders->appends(request()->query())->links() }}
                                </nav>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">No orders found</h4>
                        <p class="text-muted mb-4">No orders match your current filters. Try adjusting your search criteria.</p>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-modern btn-primary">
                            <i class="fas fa-refresh me-2"></i>Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay d-none">
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-white">Updating order status...</p>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-orders.js') }}"></script>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle order status change with enhanced UX
    $('.order-status-select').on('change', function() {
        const orderId = $(this).data('order-id');
        const newStatus = $(this).val();
        const currentStatus = $(this).data('current-status');
        const selectElement = $(this);
        const row = selectElement.closest('tr, .order-card-mobile');
        
        if (newStatus === currentStatus) {
            return;
        }
        
        // Show loading state
        row.addClass('table-warning');
        $('#loadingOverlay').removeClass('d-none');
        
        $.ajax({
            url: `/admin/orders/${orderId}/status`,
            type: 'PATCH',
            data: {
                status: newStatus,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    selectElement.data('current-status', newStatus);
                    row.removeClass('table-warning').addClass('table-success');
                    
                    // Show success animation
                    row.addClass('animate__animated animate__pulse');
                    setTimeout(() => {
                        row.removeClass('animate__animated animate__pulse table-success');
                    }, 2000);
                    
                    // Show toast notification
                    showToast('success', 'Order status updated successfully!', 'Status changed from ' + currentStatus + ' to ' + newStatus);
                } else {
                    selectElement.val(currentStatus);
                    row.removeClass('table-warning').addClass('table-danger');
                    showToast('error', 'Failed to update order status', response.message || 'An error occurred');
                }
            },
            error: function(xhr) {
                selectElement.val(currentStatus);
                row.removeClass('table-warning').addClass('table-danger');
                showToast('error', 'Error updating order status', 'Please try again later');
            },
            complete: function() {
                $('#loadingOverlay').addClass('d-none');
                setTimeout(() => {
                    row.removeClass('table-danger');
                }, 3000);
            }
        });
    });

    // Handle payment status change
    $('.payment-status-select').on('change', function() {
        const orderId = $(this).data('order-id');
        const newStatus = $(this).val();
        const currentStatus = $(this).data('current-status');
        const selectElement = $(this);
        const row = selectElement.closest('tr, .order-card-mobile');
        
        if (newStatus === currentStatus) {
            return;
        }
        
        // Show loading state
        row.addClass('table-warning');
        $('#loadingOverlay').removeClass('d-none');
        
        $.ajax({
            url: `/admin/orders/${orderId}/payment-status`,
            type: 'PATCH',
            data: {
                payment_status: newStatus,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    selectElement.data('current-status', newStatus);
                    row.removeClass('table-warning').addClass('table-success');
                    
                    // Show success animation
                    row.addClass('animate__animated animate__pulse');
                    setTimeout(() => {
                        row.removeClass('animate__animated animate__pulse table-success');
                    }, 2000);
                    
                    showToast('success', 'Payment status updated successfully!', 'Payment status changed from ' + currentStatus + ' to ' + newStatus);
                } else {
                    selectElement.val(currentStatus);
                    row.removeClass('table-warning').addClass('table-danger');
                    showToast('error', 'Failed to update payment status', response.message || 'An error occurred');
                }
            },
            error: function(xhr) {
                selectElement.val(currentStatus);
                row.removeClass('table-warning').addClass('table-danger');
                showToast('error', 'Error updating payment status', 'Please try again later');
            },
            complete: function() {
                $('#loadingOverlay').addClass('d-none');
                setTimeout(() => {
                    row.removeClass('table-danger');
                }, 3000);
            }
        });
    });

    // Enhanced search with debouncing
    let searchTimeout;
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            $('#filterForm').submit();
        }, 500);
    });

    // Auto-submit form on select changes
    $('#status, #payment_status').on('change', function() {
        $('#filterForm').submit();
    });

    // Responsive behavior
    function handleResize() {
        if ($(window).width() <= 768) {
            $('.table-modern').addClass('d-none');
            $('.order-card-mobile').removeClass('d-none');
        } else {
            $('.table-modern').removeClass('d-none');
            $('.order-card-mobile').addClass('d-none');
        }
    }

    // Initial call
    handleResize();

    // Listen for window resize
    $(window).on('resize', handleResize);

    // Ensure proper spacing with admin sidebar
    function adjustForSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const contentArea = document.querySelector('.content-area');
        
        if (sidebar && mainContent && contentArea) {
            if (window.innerWidth > 768) {
                // Desktop: maintain sidebar functionality while ensuring content doesn't overlap
                if (sidebar.classList.contains('collapsed')) {
                    mainContent.style.marginLeft = '70px';
                } else {
                    mainContent.style.marginLeft = '250px';
                }
                contentArea.style.marginLeft = '0';
                contentArea.style.width = '100%';
            } else {
                // Mobile: ensure no overlap
                mainContent.style.marginLeft = '0';
                contentArea.style.marginLeft = '0';
                contentArea.style.width = '100%';
            }
        }
    }

    // Call on load and resize
    adjustForSidebar();
    $(window).on('resize', adjustForSidebar);

    // Listen for sidebar toggle events
    $(document).on('click', '.sidebar-toggle-btn', function() {
        setTimeout(adjustForSidebar, 300); // Wait for transition
    });

    // Ensure content is properly positioned on page load
    $(document).ready(function() {
        setTimeout(adjustForSidebar, 100);
    });

    // Override admin sidebar behavior for this page
    $(document).ready(function() {
        // Ensure sidebar toggle works properly
        const originalToggleSidebar = window.toggleSidebar;
        if (originalToggleSidebar) {
            window.toggleSidebar = function() {
                originalToggleSidebar();
                setTimeout(adjustForSidebar, 300);
            };
        }
    });
});

// Enhanced Toast Notification System
function showToast(type, title, message) {
    const toastId = 'toast-' + Date.now();
    const icon = type === 'success' ? 'fas fa-check-circle text-success' : 'fas fa-exclamation-circle text-danger';
    
    const toastHtml = `
        <div class="toast toast-custom" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="${icon} me-2"></i>
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    $('#toastContainer').append(toastHtml);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 5000
    });
    
    toast.show();
    
    // Auto remove after hiding
    toastElement.addEventListener('hidden.bs.toast', function() {
        $(this).remove();
    });
}

function toggleFilters() {
    const filterSection = $('#filterSection');
    filterSection.slideToggle(300);
}

function toggleView() {
    // Implement grid/list view toggle
    showToast('info', 'Feature Coming Soon', 'Grid view feature will be available in the next update!');
}

function refreshOrders() {
    location.reload();
}

function exportToCSV() {
    window.location.href = '{{ route('admin.orders.export') }}' + window.location.search;
}

function exportToPDF() {
    showToast('info', 'Export Feature', 'PDF export feature coming soon!');
}

// Add smooth scrolling for better UX
$('html').css('scroll-behavior', 'smooth');

// Enhanced mobile experience
if ($(window).width() <= 768) {
    // Add touch-friendly interactions
    $('.order-card-mobile').on('touchstart', function() {
        $(this).addClass('active');
    }).on('touchend', function() {
        $(this).removeClass('active');
    });
}
</script>
@endpush

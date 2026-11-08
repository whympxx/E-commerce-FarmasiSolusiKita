@extends('layouts.admin')

@section('title', 'Create Order')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .order-create-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .modern-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .modern-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .floating-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 2rem;
        overflow: hidden;
    }
    
    .floating-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .card-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.9rem;
    }
    
    .card-body-modern {
        padding: 2rem;
    }
    
    .btn-modern {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
    
    .alert-modern {
        border-radius: 15px;
        border: none;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #2c5aa0;
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
        color: white;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    .feature-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    @media (max-width: 768px) {
        .order-create-container {
            padding: 1rem 0;
        }
        
        .modern-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .card-body-modern {
            padding: 1.5rem;
        }
        
        .btn-modern {
            padding: 0.75rem 1.25rem;
            font-size: 0.8rem;
        }
    }

    /* --- SIDEBAR/CONTENT LAYOUT FIXES --- */
    .main-content .container-fluid,
    .order-create-container .container-fluid {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    .main-content .order-create-container,
    .order-create-container {
        min-height: calc(100vh - 70px);
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
        position: relative;
        z-index: 1;
    }
    @media (min-width: 769px) {
        .main-content .order-create-container,
        .order-create-container {
            padding: 2rem 0;
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
    @media (max-width: 768px) {
        .main-content .order-create-container,
        .order-create-container {
            padding: 1rem 0;
            margin-left: 0 !important;
            width: 100% !important;
        }
        .main-content {
            margin-left: 0 !important;
        }
    }
    body, .main-content { overflow-x: hidden; }
</style>
@endpush

@section('content')
<div class="order-create-container">
    <div class="container-fluid">
        <!-- Modern Header -->
        <div class="modern-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="fas fa-plus-circle me-3"></i>
                        Create New Order
                    </h1>
                    <p class="lead mb-0 opacity-75">
                        Create a new order manually or through the checkout process
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-modern btn-light">
                        <i class="fas fa-arrow-left me-2"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>

        <!-- Coming Soon Alert -->
        <div class="alert alert-modern">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle me-3 fa-2x"></i>
                <div>
                    <h5 class="mb-2">Manual Order Creation Coming Soon!</h5>
                    <p class="mb-0">
                        We're working on a comprehensive manual order creation feature. For now, orders are created automatically through the checkout process.
                    </p>
                </div>
            </div>
        </div>

        <!-- Features Preview -->
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon bg-primary">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h5 class="mb-3">Customer Selection</h5>
                    <p class="text-muted mb-0">
                        Choose from existing customers or create new customer profiles with complete information.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon bg-success">
                        <i class="fas fa-box"></i>
                    </div>
                    <h5 class="mb-3">Product Management</h5>
                    <p class="text-muted mb-0">
                        Add products to orders with quantity selection, price calculation, and inventory tracking.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon bg-warning">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h5 class="mb-3">Shipping & Billing</h5>
                    <p class="text-muted mb-0">
                        Configure shipping addresses, billing information, and delivery methods for each order.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon bg-info">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h5 class="mb-3">Payment Processing</h5>
                    <p class="text-muted mb-0">
                        Handle various payment methods including credit cards, bank transfers, and cash on delivery.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon bg-danger">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <h5 class="mb-3">Invoice Generation</h5>
                    <p class="text-muted mb-0">
                        Automatically generate professional invoices and receipts for all orders.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon bg-secondary">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 class="mb-3">Order Tracking</h5>
                    <p class="text-muted mb-0">
                        Track order status, shipping updates, and delivery confirmations in real-time.
                    </p>
                </div>
            </div>
        </div>

        <!-- Current Order Management -->
        <div class="floating-card">
            <div class="card-header-modern">
                <i class="fas fa-cogs me-2"></i>
                Current Order Management
            </div>
            <div class="card-body-modern">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h5 class="mb-3">Manage Existing Orders</h5>
                        <p class="text-muted mb-4">
                            While manual order creation is being developed, you can still manage all existing orders, 
                            update their status, generate invoices, and track deliveries through our comprehensive order management system.
                        </p>
                        <div class="d-flex gap-3">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-modern btn-primary">
                                <i class="fas fa-list me-2"></i> View All Orders
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-modern btn-outline-primary">
                                <i class="fas fa-box me-2"></i> Manage Products
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px;">
                            <i class="fas fa-tools fa-3x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
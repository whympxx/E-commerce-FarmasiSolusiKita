@extends('layouts.admin')

@section('title', 'Order Details - #' . $order->id)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .order-detail-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .modern-header {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
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
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
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
    
    .table-modern {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .table-modern thead th {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.8rem;
    }
    
    .table-modern tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .table-modern tbody tr:hover {
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.05) 0%, rgba(37, 99, 235, 0.05) 100%);
    }
    
    .table-modern tbody td {
        padding: 1rem;
        vertical-align: middle;
        border: none;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending { background: linear-gradient(45deg, #fef3c7, #fde68a); color: #8b4513; }
    .status-processing { background: linear-gradient(45deg, #dbeafe, #bfdbfe); color: #2c5aa0; }
    .status-shipped { background: linear-gradient(45deg, #fef3c7, #fde68a); color: #8b4513; }
    .status-delivered { background: linear-gradient(45deg, #d1fae5, #a7f3d0); color: #2d5016; }
    .status-cancelled { background: linear-gradient(45deg, #fee2e2, #fecaca); color: #8b0000; }
    
    .payment-pending { background: linear-gradient(45deg, #fef3c7, #fde68a); color: #8b4513; }
    .payment-paid { background: linear-gradient(45deg, #d1fae5, #a7f3d0); color: #2d5016; }
    .payment-failed { background: linear-gradient(45deg, #fee2e2, #fecaca); color: #8b0000; }
    .payment-refunded { background: linear-gradient(45deg, #dbeafe, #bfdbfe); color: #2c5aa0; }
    
    .customer-avatar {
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
    
    .product-image {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .product-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 1.5rem;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
    }
    
    .info-value {
        color: #6c757d;
        text-align: right;
    }
    
    .total-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: #28a745;
    }
    
    .address-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid #1e40af;
    }
    
    .address-card h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .address-card p {
        color: #6c757d;
        margin-bottom: 0;
        line-height: 1.5;
    }
    
    @media (max-width: 768px) {
        .order-detail-container {
            padding: 1rem 0;
        }
        
        .modern-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .card-body-modern {
            padding: 1.5rem;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .btn-modern {
            padding: 0.75rem 1.25rem;
            font-size: 0.8rem;
        }
    }

    /* --- SIDEBAR/CONTENT LAYOUT FIXES --- */
    .main-content .container-fluid,
    .order-detail-container .container-fluid {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    .main-content .order-detail-container,
    .order-detail-container {
        min-height: calc(100vh - 70px);
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
        position: relative;
        z-index: 1;
    }
    @media (min-width: 769px) {
        .main-content .order-detail-container,
        .order-detail-container {
            padding: 2rem 0;
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
    @media (max-width: 768px) {
        .main-content .order-detail-container,
        .order-detail-container {
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
<div class="order-detail-container">
    <div class="container-fluid">
        <!-- Modern Header -->
        <div class="modern-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="fas fa-shopping-bag me-3"></i>
                        Order #{{ $order->id }}
                    </h1>
                    <p class="lead mb-0 opacity-75">
                        Order placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-modern btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                        <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-modern btn-success" target="_blank">
                            <i class="fas fa-file-invoice me-2"></i> Invoice
                        </a>
                        @if($order->status === 'shipped' || $order->status === 'processing')
                        <a href="{{ route('admin.orders.shipping-label', $order->id) }}" class="btn btn-modern btn-warning" target="_blank">
                            <i class="fas fa-shipping-fast me-2"></i> Label
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Information -->
            <div class="col-lg-8">
                <!-- Order Details Card -->
                <div class="floating-card">
                    <div class="card-header-modern">
                        <i class="fas fa-info-circle me-2"></i>
                        Order Information
                    </div>
                    <div class="card-body-modern">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <span class="info-label">Order ID:</span>
                                    <span class="info-value">#{{ $order->id }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Order Date:</span>
                                    <span class="info-value">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Total Amount:</span>
                                    <span class="total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Payment Method:</span>
                                    <span class="info-value">{{ $order->payment_method_label }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <span class="info-label">Order Status:</span>
                                    <span class="info-value">
                                        <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Payment Status:</span>
                                    <span class="info-value">
                                        <form method="POST" action="{{ route('admin.orders.update-payment-status', $order->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="payment_status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                            </select>
                                        </form>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Last Updated:</span>
                                    <span class="info-value">{{ $order->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="floating-card">
                    <div class="card-header-modern">
                        <i class="fas fa-box me-2"></i>
                        Order Items ({{ $order->items->count() }} items)
                    </div>
                    <div class="card-body-modern p-0">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product_name }}" 
                                                         class="product-image me-3">
                                                @else
                                                    <div class="product-placeholder me-3">
                                                        <i class="fas fa-pills"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                    @if($item->product && $item->product->prescription_required)
                                                        <small class="text-warning">
                                                            <i class="fas fa-exclamation-triangle me-1"></i> Requires Prescription
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-end">
                                            <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="3" class="text-end">Total Amount:</th>
                                        <th class="text-end total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                @if($order->notes)
                <div class="floating-card">
                    <div class="card-header-modern">
                        <i class="fas fa-sticky-note me-2"></i>
                        Order Notes
                    </div>
                    <div class="card-body-modern">
                        <div class="bg-light rounded p-3">
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Customer Information -->
            <div class="col-lg-4">
                <!-- Customer Info -->
                <div class="floating-card">
                    <div class="card-header-modern">
                        <i class="fas fa-user me-2"></i>
                        Customer Information
                    </div>
                    <div class="card-body-modern text-center">
                        @if($order->user)
                            <div class="customer-avatar bg-primary">
                                <i class="fas fa-user"></i>
                            </div>
                            <h5 class="mb-1">{{ $order->user->name }}</h5>
                            <small class="text-muted">Registered Customer</small>
                        @else
                            <div class="customer-avatar bg-secondary">
                                <i class="fas fa-user-times"></i>
                            </div>
                            <h5 class="mb-1">{{ $order->customer_name }}</h5>
                            <small class="text-muted">Guest Customer</small>
                        @endif
                        
                        <hr class="my-3">
                        
                        <div class="text-start">
                            <div class="info-row">
                                <span class="info-label">Email:</span>
                                <span class="info-value">{{ $order->customer_email }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Phone:</span>
                                <span class="info-value">{{ $order->customer_phone }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="floating-card">
                    <div class="card-header-modern">
                        <i class="fas fa-shipping-fast me-2"></i>
                        Shipping Information
                    </div>
                    <div class="card-body-modern">
                        <div class="address-card">
                            <h6><i class="fas fa-map-marker-alt me-2"></i>Shipping Address</h6>
                            <p>
                                {{ $order->shipping_street }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                                {{ $order->shipping_postal_code }}<br>
                                {{ $order->shipping_country }}
                            </p>
                        </div>
                        
                        @if($order->shipping_method)
                        <div class="info-row">
                            <span class="info-label">Shipping Method:</span>
                            <span class="info-value">{{ ucfirst($order->shipping_method) }}</span>
                        </div>
                        @endif
                        @if($order->tracking_number)
                        <div class="info-row">
                            <span class="info-label">Nomor Resi:</span>
                            <span class="info-value">{{ $order->tracking_number }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Billing Information -->
                <div class="floating-card">
                    <div class="card-header-modern">
                        <i class="fas fa-credit-card me-2"></i>
                        Billing Information
                    </div>
                    <div class="card-body-modern">
                        <div class="address-card">
                            <h6><i class="fas fa-map-marker-alt me-2"></i>Billing Address</h6>
                            <p>
                                {{ $order->billing_street }}<br>
                                {{ $order->billing_city }}, {{ $order->billing_state }}<br>
                                {{ $order->billing_postal_code }}<br>
                                {{ $order->billing_country }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Actions -->
                <div class="floating-card">
                    <div class="card-header-modern">
                        <i class="fas fa-cogs me-2"></i>
                        Order Actions
                    </div>
                    <div class="card-body-modern">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-modern btn-primary">
                                <i class="fas fa-edit me-2"></i> Edit Order
                            </a>
                            <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-modern btn-success" target="_blank">
                                <i class="fas fa-file-invoice me-2"></i> View Invoice
                            </a>
                            @if($order->status === 'shipped' || $order->status === 'processing')
                            <a href="{{ route('admin.orders.shipping-label', $order->id) }}" class="btn btn-modern btn-warning" target="_blank">
                                <i class="fas fa-shipping-fast me-2"></i> Print Shipping Label
                            </a>
                            @endif
                            @if($order->customer_email)
                            <a href="mailto:{{ $order->customer_email }}?subject=Order {{ $order->id }}" class="btn btn-modern btn-info">
                                <i class="fas fa-envelope me-2"></i> Send Email
                            </a>
                            @endif
                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline delete-order-form mt-2" onsubmit="return confirm('Are you sure you want to delete order #{{ $order->id }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-modern btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i> Delete Order
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Show success message if status was updated
    @if(session('success'))
        // You can implement your preferred toast library here
        alert('{{ session('success') }}');
    @endif
    
    // Show error message if there was an issue
    @if(session('error'))
        alert('{{ session('error') }}');
    @endif
});
</script>
@endpush

@extends('layouts.admin')

@section('title', 'Invoice - Order #' . $order->id)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .invoice-container {
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
    
    .invoice-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
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
    
    .invoice-header {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    
    .invoice-body {
        padding: 2rem;
    }
    
    .company-info {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #1e40af;
    }
    
    .invoice-details {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #28a745;
    }
    
    .customer-info {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #ffc107;
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
        font-size: 0.85rem;
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
    
    .total-section {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-paid { background: linear-gradient(45deg, #d1fae5, #a7f3d0); color: #2d5016; }
    .status-pending { background: linear-gradient(45deg, #fef3c7, #fde68a); color: #8b4513; }
    .status-failed { background: linear-gradient(45deg, #fee2e2, #fecaca); color: #8b0000; }
    
    .status-delivered { background: linear-gradient(45deg, #d1fae5, #a7f3d0); color: #2d5016; }
    .status-pending-order { background: linear-gradient(45deg, #fef3c7, #fde68a); color: #8b4513; }
    .status-processing { background: linear-gradient(45deg, #dbeafe, #bfdbfe); color: #2c5aa0; }
    
    .footer-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        border-top: 4px solid #1e40af;
    }
    
    @media print {
        .invoice-container {
            background: white !important;
            padding: 0 !important;
        }
        
        .modern-header,
        .btn-modern {
            display: none !important;
        }
        
        .invoice-card {
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        
        .invoice-header,
        .invoice-body {
            padding: 1rem !important;
        }
        
        .company-info,
        .invoice-details,
        .customer-info,
        .total-section,
        .footer-section {
            background: white !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0 !important;
        }
        
        .table-modern thead th {
            background: #f8f9fa !important;
            color: #495057 !important;
        }
        
        .table-modern tbody tr:hover {
            background: white !important;
        }
    }
    
    @media (max-width: 768px) {
        .invoice-container {
            padding: 1rem 0;
        }
        
        .modern-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .invoice-body {
            padding: 1rem;
        }
        
        .btn-modern {
            padding: 0.75rem 1.25rem;
            font-size: 0.8rem;
        }
    }

    /* --- SIDEBAR/CONTENT LAYOUT FIXES --- */
    .main-content .container-fluid,
    .invoice-container .container-fluid {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    .main-content .invoice-container,
    .invoice-container {
        min-height: calc(100vh - 70px);
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
        position: relative;
        z-index: 1;
    }
    @media (min-width: 769px) {
        .main-content .invoice-container,
        .invoice-container {
            padding: 2rem 0;
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
    @media (max-width: 768px) {
        .main-content .invoice-container,
        .invoice-container {
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
<div class="invoice-container">
    <div class="container-fluid">
        <!-- Modern Header -->
        <div class="modern-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="fas fa-file-invoice me-3"></i>
                        Invoice - Order #{{ $order->id }}
                    </h1>
                    <p class="lead mb-0 opacity-75">
                        Professional invoice for order placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="d-flex gap-2 justify-content-end">
                        <button onclick="window.print()" class="btn btn-modern btn-light">
                            <i class="fas fa-print me-2"></i> Print
                        </button>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-modern btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-card">
            <!-- Invoice Header -->
            <div class="invoice-header">
                <div class="row align-items-center">
                    <div class="col-md-6 text-start">
                        <h2 class="mb-2">
                            <i class="fas fa-pills me-2"></i>
                            Farmasi Solusi Kita
                        </h2>
                        <p class="mb-1">Jl. Contoh No. 123, Jakarta, Indonesia</p>
                        <p class="mb-1">Phone: +62 21 1234 5678</p>
                        <p class="mb-0">Email: info@farmasisolusi.com</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h1 class="display-6 fw-bold mb-2">INVOICE</h1>
                        <p class="mb-1"><strong>Invoice #:</strong> {{ $order->id }}</p>
                        <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
                        <p class="mb-0"><strong>Due Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Body -->
            <div class="invoice-body">
                <!-- Customer Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="customer-info">
                            <h5 class="mb-3">
                                <i class="fas fa-user me-2"></i>
                                Bill To:
                            </h5>
                            <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                            <p class="mb-1">{{ $order->customer_email }}</p>
                            <p class="mb-1">{{ $order->customer_phone }}</p>
                            <p class="mb-0">{{ $order->billing_street }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="customer-info">
                            <h5 class="mb-3">
                                <i class="fas fa-shipping-fast me-2"></i>
                                Ship To:
                            </h5>
                            <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                            <p class="mb-1">{{ $order->shipping_street }}</p>
                            <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                            <p class="mb-0">{{ $order->shipping_postal_code }}, {{ $order->shipping_country }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="table-responsive mb-4">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
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
                                                     class="rounded me-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-pills text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->product && $item->product->prescription_required)
                                                    <br><small class="text-warning">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> Requires Prescription
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="total-section">
                            <h5 class="mb-3">
                                <i class="fas fa-calculator me-2"></i>
                                Order Summary
                            </h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Subtotal:</span>
                                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Tax:</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Shipping:</span>
                                <span>Rp 0</span>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold fs-5">Total:</span>
                                <span class="fw-bold fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="company-info">
                            <h6 class="mb-2">
                                <i class="fas fa-credit-card me-2"></i>
                                Payment Information:
                            </h6>
                            <p class="mb-1"><strong>Payment Method:</strong> {{ $order->payment_method ? $order->payment_method_label : 'Not specified' }}</p>
                            <p class="mb-0">
                                <strong>Payment Status:</strong> 
                                <span class="status-badge status-{{ $order->payment_status }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="invoice-details">
                            <h6 class="mb-2">
                                <i class="fas fa-truck me-2"></i>
                                Order Information:
                            </h6>
                            <p class="mb-1"><strong>Order ID:</strong> #{{ $order->id }}</p>
                            <p class="mb-0">
                                <strong>Order Status:</strong> 
                                <span class="status-badge status-{{ $order->status === 'delivered' ? 'delivered' : ($order->status === 'pending' ? 'pending-order' : 'processing') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($order->notes)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="company-info">
                                <h6 class="mb-2">
                                    <i class="fas fa-sticky-note me-2"></i>
                                    Notes:
                                </h6>
                                <p class="mb-0">{{ $order->notes }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Footer -->
                <div class="footer-section">
                    <h5 class="mb-3">
                        <i class="fas fa-heart me-2 text-danger"></i>
                        Thank you for your business!
                    </h5>
                    <p class="mb-2">For questions or support, please contact us:</p>
                    <p class="mb-1">Email: info@farmasisolusi.com</p>
                    <p class="mb-0">Phone: +62 21 1234 5678</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
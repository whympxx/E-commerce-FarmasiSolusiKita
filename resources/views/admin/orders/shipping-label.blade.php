@extends('layouts.admin')

@section('title', 'Shipping Label - Order #' . $order->id)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .shipping-label-container {
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
    
    .shipping-label-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
        border: 3px solid #667eea;
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
    
    .label-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        text-align: center;
        border-bottom: 3px solid #667eea;
    }
    
    .label-body {
        padding: 2rem;
    }
    
    .address-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #667eea;
        position: relative;
    }
    
    .address-section.from {
        border-left-color: #28a745;
    }
    
    .address-section.to {
        border-left-color: #ffc107;
    }
    
    .address-section h6 {
        color: #495057;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
        font-size: 0.8rem;
    }
    
    .address-content {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        border: 1px solid #dee2e6;
    }
    
    .order-info-section {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .package-contents-section {
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    }
    
    .table-modern tbody td {
        padding: 1rem;
        vertical-align: middle;
        border: none;
    }
    
    .special-instructions-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #dc3545;
    }
    
    .tracking-weight-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #6f42c1;
    }
    
    .barcode-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        border: 2px dashed #667eea;
        margin-bottom: 2rem;
    }
    
    .barcode-placeholder {
        background: white;
        border: 2px solid #000;
        padding: 1rem;
        display: inline-block;
        min-width: 300px;
    }
    
    .barcode-lines {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        height: 60px;
        margin-bottom: 0.5rem;
    }
    
    .barcode-line {
        background: #000;
        height: 100%;
        margin: 0 1px;
        border-radius: 1px;
    }
    
    .barcode-text {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        font-size: 1.2rem;
        letter-spacing: 2px;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .info-value {
        font-weight: 700;
        font-size: 1rem;
    }
    
    @media print {
        .shipping-label-container {
            background: white !important;
            padding: 0 !important;
        }
        
        .modern-header,
        .btn-modern {
            display: none !important;
        }
        
        .shipping-label-card {
            box-shadow: none !important;
            border-radius: 0 !important;
            border: 2px solid #000 !important;
        }
        
        .label-header,
        .label-body {
            padding: 1rem !important;
        }
        
        .address-section,
        .order-info-section,
        .package-contents-section,
        .special-instructions-section,
        .tracking-weight-section,
        .barcode-section {
            background: white !important;
            border: 1px solid #000 !important;
            border-radius: 0 !important;
        }
        
        .table-modern thead th {
            background: #f8f9fa !important;
            color: #000 !important;
        }
        
        .table-modern tbody tr:hover {
            background: white !important;
        }
        
        .barcode-section {
            border: 2px solid #000 !important;
        }
    }
    
    @media (max-width: 768px) {
        .shipping-label-container {
            padding: 1rem 0;
        }
        
        .modern-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .label-body {
            padding: 1rem;
        }
        
        .btn-modern {
            padding: 0.75rem 1.25rem;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<div class="shipping-label-container">
    <div class="container-fluid">
        <!-- Modern Header -->
        <div class="modern-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="fas fa-shipping-fast me-3"></i>
                        Shipping Label - Order #{{ $order->id }}
                    </h1>
                    <p class="lead mb-0 opacity-75">
                        Professional shipping label for order placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}
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

        <div class="shipping-label-card">
            <!-- Label Header -->
            <div class="label-header">
                <div class="row align-items-center">
                    <div class="col-md-6 text-start">
                        <h2 class="mb-2">
                            <i class="fas fa-pills me-2"></i>
                            Farmasi Solusi Kita
                        </h2>
                        <p class="mb-0">Professional Pharmaceutical Shipping</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h1 class="display-6 fw-bold mb-2">SHIPPING LABEL</h1>
                        <p class="mb-0">Order #{{ $order->id }}</p>
                    </div>
                </div>
            </div>

            <!-- Label Body -->
            <div class="label-body">
                <!-- Addresses -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="address-section from">
                            <h6>
                                <i class="fas fa-map-marker-alt me-2"></i>
                                FROM:
                            </h6>
                            <div class="address-content">
                                <strong>Farmasi Solusi Kita</strong><br>
                                Jl. Contoh No. 123<br>
                                Jakarta, Indonesia 12345<br>
                                Phone: +62 21 1234 5678<br>
                                Email: info@farmasisolusi.com
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="address-section to">
                            <h6>
                                <i class="fas fa-user me-2"></i>
                                TO:
                            </h6>
                            <div class="address-content">
                                <strong>{{ $order->customer_name }}</strong><br>
                                {{ $order->shipping_street }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                                {{ $order->shipping_postal_code }}, {{ $order->shipping_country }}<br>
                                Phone: {{ $order->customer_phone }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="order-info-section">
                    <h5 class="mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Order Information
                    </h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-item">
                                <span class="info-label">Order #:</span>
                                <span class="info-value">{{ $order->id }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <span class="info-label">Date:</span>
                                <span class="info-value">{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <span class="info-label">Items:</span>
                                <span class="info-value">{{ $order->items->count() }} items</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <span class="info-label">Weight:</span>
                                <span class="info-value">{{ $order->items->sum('quantity') * 0.1 }} kg</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Package Contents -->
                <div class="package-contents-section">
                    <h5 class="mb-3">
                        <i class="fas fa-box me-2"></i>
                        Package Contents
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Type</th>
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
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 40px; height: 40px;">
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
                                        <td class="text-center">
                                            @if($item->product && $item->product->prescription_required)
                                                <span class="badge bg-warning">Prescription</span>
                                            @else
                                                <span class="badge bg-success">OTC</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Special Instructions -->
                <div class="special-instructions-section">
                    <h5 class="mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Special Instructions
                    </h5>
                    <div class="bg-white rounded p-3">
                        @if($order->notes)
                            <p class="mb-0">{{ $order->notes }}</p>
                        @else
                            <p class="mb-0">
                                <strong>Handle with care.</strong> Contains pharmaceutical products. 
                                Keep in a cool, dry place. Do not expose to direct sunlight.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Tracking & Weight -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="tracking-weight-section">
                            <h5 class="mb-3">
                                <i class="fas fa-truck me-2"></i>
                                Tracking Information
                            </h5>
                            <div class="bg-white rounded p-3">
                                @if($order->tracking_number)
                                    <p class="mb-1"><strong>Tracking #:</strong> {{ $order->tracking_number }}</p>
                                @else
                                    <p class="mb-1"><strong>Tracking #:</strong> <em>Will be assigned upon shipment</em></p>
                                @endif
                                <p class="mb-0"><strong>Carrier:</strong> Standard Shipping</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tracking-weight-section">
                            <h5 class="mb-3">
                                <i class="fas fa-weight-hanging me-2"></i>
                                Package Details
                            </h5>
                            <div class="bg-white rounded p-3">
                                <p class="mb-1"><strong>Weight:</strong> {{ $order->items->sum('quantity') * 0.1 }} kg</p>
                                <p class="mb-1"><strong>Dimensions:</strong> 20 x 15 x 10 cm</p>
                                <p class="mb-0"><strong>Service:</strong> Standard Delivery</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barcode -->
                <div class="barcode-section">
                    <h5 class="mb-3">
                        <i class="fas fa-barcode me-2"></i>
                        Shipping Barcode
                    </h5>
                    <div class="barcode-placeholder">
                        <div class="barcode-lines">
                            @for($i = 0; $i < 20; $i++)
                                <div class="barcode-line" style="width: {{ rand(1, 4) }}px;"></div>
                            @endfor
                        </div>
                        <div class="barcode-text">{{ $order->id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
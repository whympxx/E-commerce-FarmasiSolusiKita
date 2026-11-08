@extends('layouts.admin')

@section('title', 'Edit Order - #' . $order->id)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .order-edit-container {
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
    
    .form-control-modern {
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        padding: 0.875rem 1.25rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        font-weight: 500;
    }
    
    .form-control-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    .form-label-modern {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
    }
    
    .form-group-modern {
        margin-bottom: 1.5rem;
    }
    
    .alert-modern {
        border-radius: 15px;
        border: none;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        color: #8b0000;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%);
        color: #2d5016;
    }
    
    .checkbox-modern {
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .checkbox-modern:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .checkbox-modern:focus {
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    
    .form-check-label-modern {
        font-weight: 500;
        color: #495057;
        margin-left: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .order-edit-container {
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
    .order-edit-container .container-fluid {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    .main-content .order-edit-container,
    .order-edit-container {
        min-height: calc(100vh - 70px);
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
        position: relative;
        z-index: 1;
    }
    @media (min-width: 769px) {
        .main-content .order-edit-container,
        .order-edit-container {
            padding: 2rem 0;
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
    @media (max-width: 768px) {
        .main-content .order-edit-container,
        .order-edit-container {
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
<div class="order-edit-container">
    <div class="container-fluid">
        <!-- Modern Header -->
        <div class="modern-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="fas fa-edit me-3"></i>
                        Edit Order #{{ $order->id }}
                    </h1>
                    <p class="lead mb-0 opacity-75">
                        Update order information and customer details
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-modern btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-modern btn-outline-light">
                            <i class="fas fa-list me-2"></i> All Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-modern alert-danger">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                    <div>
                        <h6 class="mb-1">Please fix the following errors:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Order Information -->
                <div class="col-lg-8">
                    <div class="floating-card">
                        <div class="card-header-modern">
                            <i class="fas fa-info-circle me-2"></i>
                            Order Information
                        </div>
                        <div class="card-body-modern">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="status" class="form-label-modern">Order Status</label>
                                        <select name="status" id="status" class="form-select form-control-modern" required>
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="payment_status" class="form-label-modern">Payment Status</label>
                                        <select name="payment_status" id="payment_status" class="form-select form-control-modern" required>
                                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="payment_method" class="form-label-modern">Payment Method</label>
                                        <select name="payment_method" id="payment_method" class="form-select form-control-modern" required>
                                            @foreach(\App\Models\Order::PAYMENT_METHODS as $value => $label)
                                                <option value="{{ $value }}" {{ old('payment_method', $order->payment_method) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="shipping_method" class="form-label-modern">Shipping Method</label>
                                        <select name="shipping_method" id="shipping_method" class="form-select form-control-modern">
                                            <option value="">Select shipping method</option>
                                            <option value="standard" {{ $order->shipping_method === 'standard' ? 'selected' : '' }}>Standard Shipping</option>
                                            <option value="express" {{ $order->shipping_method === 'express' ? 'selected' : '' }}>Express Shipping</option>
                                            <option value="overnight" {{ $order->shipping_method === 'overnight' ? 'selected' : '' }}>Overnight Shipping</option>
                                            <option value="pickup" {{ $order->shipping_method === 'pickup' ? 'selected' : '' }}>Store Pickup</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group-modern">
                                <label for="tracking_number" class="form-label-modern">Nomor Resi</label>
                                <input type="text" name="tracking_number" id="tracking_number" class="form-control form-control-modern"
                                       value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Isi setelah pesanan dikirim">
                            </div>
                            <div class="form-group-modern">
                                <label for="notes" class="form-label-modern">Order Notes</label>
                                <textarea name="notes" id="notes" class="form-control form-control-modern" rows="3" 
                                          placeholder="Internal notes about this order...">{{ old('notes', $order->notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="floating-card">
                        <div class="card-header-modern">
                            <i class="fas fa-user me-2"></i>
                            Customer Information
                        </div>
                        <div class="card-body-modern">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="customer_name" class="form-label-modern">Customer Name</label>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control form-control-modern" 
                                               value="{{ old('customer_name', $order->customer_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="customer_email" class="form-label-modern">Customer Email</label>
                                        <input type="email" name="customer_email" id="customer_email" class="form-control form-control-modern" 
                                               value="{{ old('customer_email', $order->customer_email) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group-modern">
                                <label for="customer_phone" class="form-label-modern">Customer Phone</label>
                                <input type="text" name="customer_phone" id="customer_phone" class="form-control form-control-modern" 
                                       value="{{ old('customer_phone', $order->customer_phone) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="floating-card">
                        <div class="card-header-modern">
                            <i class="fas fa-shipping-fast me-2"></i>
                            Shipping Address
                        </div>
                        <div class="card-body-modern">
                            <div class="form-group-modern">
                                <label for="shipping_address" class="form-label-modern">Address</label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control form-control-modern" rows="2" 
                                          required>{{ old('shipping_address', $order->shipping_street) }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="shipping_city" class="form-label-modern">City</label>
                                        <input type="text" name="shipping_city" id="shipping_city" class="form-control form-control-modern" 
                                               value="{{ old('shipping_city', $order->shipping_city) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="shipping_state" class="form-label-modern">State/Province</label>
                                        <input type="text" name="shipping_state" id="shipping_state" class="form-control form-control-modern" 
                                               value="{{ old('shipping_state', $order->shipping_state) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="shipping_postal_code" class="form-label-modern">Postal Code</label>
                                        <input type="text" name="shipping_postal_code" id="shipping_postal_code" class="form-control form-control-modern" 
                                               value="{{ old('shipping_postal_code', $order->shipping_postal_code) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="shipping_country" class="form-label-modern">Country</label>
                                        <input type="text" name="shipping_country" id="shipping_country" class="form-control form-control-modern" 
                                               value="{{ old('shipping_country', $order->shipping_country) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div class="floating-card">
                        <div class="card-header-modern d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-credit-card me-2"></i>
                                Billing Address
                            </div>
                            <div class="form-check">
                                <input class="form-check-input checkbox-modern" type="checkbox" id="same_as_shipping" onchange="copyShippingToBilling()">
                                <label class="form-check-label form-check-label-modern" for="same_as_shipping">
                                    Same as shipping address
                                </label>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <div class="form-group-modern">
                                <label for="billing_address" class="form-label-modern">Address</label>
                                <textarea name="billing_address" id="billing_address" class="form-control form-control-modern" rows="2" 
                                          required>{{ old('billing_address', $order->billing_street) }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="billing_city" class="form-label-modern">City</label>
                                        <input type="text" name="billing_city" id="billing_city" class="form-control form-control-modern" 
                                               value="{{ old('billing_city', $order->billing_city) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="billing_state" class="form-label-modern">State/Province</label>
                                        <input type="text" name="billing_state" id="billing_state" class="form-control form-control-modern" 
                                               value="{{ old('billing_state', $order->billing_state) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="billing_postal_code" class="form-label-modern">Postal Code</label>
                                        <input type="text" name="billing_postal_code" id="billing_postal_code" class="form-control form-control-modern" 
                                               value="{{ old('billing_postal_code', $order->billing_postal_code) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="billing_country" class="form-label-modern">Country</label>
                                        <input type="text" name="billing_country" id="billing_country" class="form-control form-control-modern" 
                                               value="{{ old('billing_country', $order->billing_country) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="floating-card">
                        <div class="card-header-modern">
                            <i class="fas fa-calculator me-2"></i>
                            Order Summary
                        </div>
                        <div class="card-body-modern">
                            <div class="text-center mb-4">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-shopping-bag text-white fa-2x"></i>
                                </div>
                                <h5 class="mt-3 mb-1">Order #{{ $order->id }}</h5>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold">Total Items:</span>
                                <span class="badge bg-primary rounded-pill">{{ $order->items->count() }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold">Total Amount:</span>
                                <span class="text-success fw-bold fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-modern btn-primary">
                                    <i class="fas fa-save me-2"></i> Update Order
                                </button>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-modern btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i> Cancel
                                </a>
                                {{-- Form hapus ada di luar form update (form bersarang membuat tombol simpan ikut mengirim DELETE) --}}
                                <button type="submit" form="delete-order-form" class="btn btn-modern btn-danger w-100 mt-2">
                                    <i class="fas fa-trash me-2"></i> Delete Order
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="floating-card">
                        <div class="card-header-modern">
                            <i class="fas fa-box me-2"></i>
                            Order Items
                        </div>
                        <div class="card-body-modern">
                            @foreach($order->items as $item)
                            <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="rounded me-3" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-pills text-white"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $item->product_name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                </div>
                                <div class="text-end">
                                    <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="delete-order-form" action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-none" onsubmit="return confirm('Are you sure you want to delete order #{{ $order->id }}?');">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyShippingToBilling() {
    if (document.getElementById('same_as_shipping').checked) {
        document.getElementById('billing_address').value = document.getElementById('shipping_address').value;
        document.getElementById('billing_city').value = document.getElementById('shipping_city').value;
        document.getElementById('billing_state').value = document.getElementById('shipping_state').value;
        document.getElementById('billing_postal_code').value = document.getElementById('shipping_postal_code').value;
        document.getElementById('billing_country').value = document.getElementById('shipping_country').value;
    }
}

$(document).ready(function() {
    // Show success message if order was updated
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    
    // Show error message if there was an issue
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
});
</script>
@endpush

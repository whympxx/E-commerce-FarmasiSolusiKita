@extends('layouts.catalog')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">Keranjang Belanja</li>
        </ol>
    </nav>

    <!-- Cart Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja</h2>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i>Lanjut Belanja
            </a>
        </div>
    </div>

    @if($cartItems->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Item dalam Keranjang ({{ $cartTotal['items_count'] }})</h5>
                            <button class="btn btn-outline-danger btn-sm" onclick="clearCart()">
                                <i class="fas fa-trash me-1"></i>Kosongkan Keranjang
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @foreach($cartItems as $item)
                            <div class="cart-item border-bottom p-4" data-cart-id="{{ $item->id }}">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-2 col-sm-3 mb-3 mb-md-0">
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded overflow-hidden" style="height: 80px;">
                                            @if($item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                                            @else
                                                <i class="fas fa-pills fa-2x text-muted"></i>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="col-md-4 col-sm-5 mb-3 mb-md-0">
                                        <h6 class="mb-1">
                                            <a href="{{ route('products.show', $item->product->slug) }}" 
                                               class="text-decoration-none text-dark">
                                                {{ $item->product->name }}
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-1">{{ $item->product->brand }}</p>
                                        <p class="text-muted small mb-1">{{ $item->product->category->name }}</p>
                                        
                                        @if($item->product->prescription_required)
                                            <span class="badge badge-prescription small">
                                                <i class="fas fa-prescription me-1"></i>Resep Dokter
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Price -->
                                    <div class="col-md-2 col-sm-4 mb-3 mb-md-0 text-center">
                                        @if($item->product->is_on_sale)
                                            <div class="price-sale fw-bold">{{ $item->product->formatted_sale_price }}</div>
                                            <div class="price-original small">{{ $item->product->formatted_price }}</div>
                                        @else
                                            <div class="fw-bold text-primary">{{ $item->product->formatted_price }}</div>
                                        @endif
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="col-md-2 col-sm-6 mb-3 mb-md-0">
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   value="{{ $item->quantity }}" min="1" 
                                                   max="{{ $item->product->manage_stock ? $item->product->stock_quantity : 999 }}"
                                                   onchange="updateQuantity({{ $item->id }}, this.value)">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">+</button>
                                        </div>
                                        @if($item->product->manage_stock)
                                            <small class="text-muted d-block text-center mt-1">
                                                Maks: {{ $item->product->stock_quantity }}
                                            </small>
                                        @endif
                                    </div>

                                    <!-- Item Total -->
                                    <div class="col-md-2 col-sm-6 mb-3 mb-md-0 text-center">
                                        <div class="fw-bold item-total">{{ $item->formatted_total_price }}</div>
                                        <button class="btn btn-link text-danger p-0 small" 
                                                onclick="removeItem({{ $item->id }})">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal ({{ $cartTotal['items_count'] }} item)</span>
                            <span class="subtotal">Rp {{ number_format($cartTotal['subtotal'], 0, ',', '.') }}</span>
                        </div>
                        
                        @if($cartTotal['discount'] > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Diskon</span>
                                <span class="discount">-Rp {{ number_format($cartTotal['discount'], 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkos Kirim</span>
                            <span class="shipping">{{ $cartTotal['shipping'] > 0 ? 'Rp ' . number_format($cartTotal['shipping'], 0, ',', '.') : 'Gratis' }}</span>
                        </div>

                        @if($cartTotal['tax_rate'] > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Pajak ({{ rtrim(rtrim(number_format($cartTotal['tax_rate'], 2, ',', ''), '0'), ',') }}%)</span>
                                <span class="tax">Rp {{ number_format($cartTotal['tax'], 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong class="text-primary total-amount">Rp {{ number_format($cartTotal['grand_total'], 0, ',', '.') }}</strong>
                        </div>

                        <!-- Checkout Button -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg" onclick="proceedToCheckout()">
                                <i class="fas fa-credit-card me-2"></i>Lanjut ke Pembayaran
                            </button>
                            <button class="btn btn-outline-success" onclick="saveForLater()">
                                <i class="fas fa-bookmark me-2"></i>Simpan untuk Nanti
                            </button>
                        </div>

                        <!-- Promo Code -->
                        <div class="mt-4">
                            <h6>Kode Promo</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Masukkan kode promo" id="promoCode">
                                <button class="btn btn-outline-secondary" onclick="applyPromo()">Gunakan</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prescription Notice -->
                @php
                    $hasPresciption = $cartItems->filter(function($item) {
                        return $item->product->prescription_required;
                    })->count() > 0;
                @endphp

                @if($hasPresciption)
                    <div class="alert alert-warning mt-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Keranjang Anda berisi obat yang memerlukan resep dokter. 
                        Silakan siapkan resep untuk proses checkout.
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
            <h4>Keranjang Belanja Kosong</h4>
            <p class="text-muted mb-4">Belum ada produk dalam keranjang belanja Anda.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-pills me-2"></i>Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// CSRF Token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Update quantity
function updateQuantity(cartId, newQuantity) {
    if (newQuantity < 1) return;
    
    fetch(`/cart/${cartId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            quantity: parseInt(newQuantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update item total
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            const itemTotal = cartItem.querySelector('.item-total');
            itemTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.item_total);
            
            // Update cart totals
            updateCartTotals(data.cart_total);
            updateCartCount(data.cart_count);
            
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Terjadi kesalahan saat memperbarui keranjang.', 'error');
    });
}

// Remove item from cart
function removeItem(cartId) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
        fetch(`/cart/${cartId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove item from DOM
                const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
                cartItem.remove();
                
                // Update cart totals
                updateCartTotals(data.cart_total);
                updateCartCount(data.cart_count);
                
                showMessage(data.message, 'success');
                
                // Reload page if cart is empty
                if (data.cart_count === 0) {
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Terjadi kesalahan saat menghapus item.', 'error');
        });
    }
}

// Clear entire cart
function clearCart() {
    if (confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')) {
        fetch('/cart', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Terjadi kesalahan saat mengosongkan keranjang.', 'error');
        });
    }
}

// Update cart totals in the summary
function updateCartTotals(totals) {
    const subtotalElement = document.querySelector('.subtotal');
    const discountElement = document.querySelector('.discount');
    const totalElement = document.querySelector('.total-amount');
    
    if (subtotalElement) {
        subtotalElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totals.subtotal);
    }
    
    if (discountElement) {
        discountElement.textContent = '-Rp ' + new Intl.NumberFormat('id-ID').format(totals.discount);
    }
    
    const taxElement = document.querySelector('.tax');
    if (taxElement) {
        taxElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totals.tax);
    }

    if (totalElement) {
        totalElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totals.grand_total);
    }
}

// Update cart count in navigation
function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

// Show message to user
function showMessage(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insert at top of container
    const container = document.querySelector('.container');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 3000);
}

// Proceed to checkout
function proceedToCheckout() {
    // Tamu akan diarahkan ke login dulu, lalu kembali ke checkout (keranjang ikut dipindah)
    window.location.href = '{{ route('checkout.index') }}';
}

// Save for later
function saveForLater() {
    showMessage('Keranjang tersimpan untuk nanti!', 'success');
}

// Apply promo code
function applyPromo() {
    const promoCode = document.getElementById('promoCode').value;
    if (promoCode.trim() === '') {
        showMessage('Masukkan kode promo terlebih dahulu.', 'error');
        return;
    }
    
    // This will be implemented when we create the promo code functionality
    showMessage('Fitur kode promo akan segera tersedia!', 'info');
}
</script>
@endpush

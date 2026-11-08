@extends('layouts.catalog')

@section('title', $product->name)

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center position-relative">
                    <!-- Sale Badge -->
                    @if($product->is_on_sale)
                        <span class="badge badge-sale position-absolute" style="top: 15px; right: 15px;">
                            -{{ $product->discount_percentage }}%
                        </span>
                    @endif

                    <!-- Main Product Image -->
                    <div class="bg-light d-flex align-items-center justify-content-center mb-3 overflow-hidden" style="height: 400px; border-radius: 8px;">
                        @if($product->image_url)
                            <img id="mainProductImage" src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                        @else
                            <i class="fas fa-pills fa-5x text-muted"></i>
                        @endif
                    </div>

                    <!-- Thumbnail Images -->
                    @if(count($product->images_array) > 1)
                        <div class="row g-2">
                            @foreach($product->images_array as $image)
                                <div class="col-3">
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
                                         class="bg-light"
                                         style="width: 100%; height: 80px; object-fit: contain; border-radius: 4px; cursor: pointer;"
                                         onclick="document.getElementById('mainProductImage').src = this.src">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="col-lg-6">
            <div class="mb-3">
                <!-- Product Badges -->
                @if($product->prescription_required)
                    <span class="badge badge-prescription me-2">
                        <i class="fas fa-prescription me-1"></i>Resep Dokter Diperlukan
                    </span>
                @endif
                @if($product->is_featured)
                    <span class="badge bg-success">
                        <i class="fas fa-star me-1"></i>Produk Unggulan
                    </span>
                @endif
            </div>

            <h1 class="h3 mb-2">{{ $product->name }}</h1>
            <p class="text-muted mb-3">{{ $product->brand }} | SKU: {{ $product->sku }}</p>

            <!-- Price -->
            <div class="mb-4">
                @if($product->is_on_sale)
                    <h4 class="price-sale mb-1">{{ $product->formatted_sale_price }}</h4>
                    <p class="price-original mb-0">{{ $product->formatted_price }}</p>
                    <small class="text-success">Hemat {{ $product->formatted_sale_price ? 'Rp ' . number_format($product->price - $product->sale_price, 0, ',', '.') : '0' }}</small>
                @else
                    <h4 class="text-primary">{{ $product->formatted_price }}</h4>
                @endif
            </div>

            <!-- Short Description -->
            <div class="mb-4">
                <h6>Deskripsi Singkat</h6>
                <p>{{ $product->short_description }}</p>
            </div>

            <!-- Stock Status -->
            <div class="mb-4">
                @if($product->in_stock)
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>
                            <strong>Stok Tersedia</strong>
                            @if($product->manage_stock)
                                <br><small>{{ $product->stock_quantity }} {{ $product->unit }} tersisa</small>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="fas fa-times-circle me-2"></i>
                        <strong>Stok Habis</strong>
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="mb-4">
                <h6>Detail Produk</h6>
                <div class="row">
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li><strong>Kategori:</strong> {{ $product->category->name }}</li>
                            <li><strong>Brand:</strong> {{ $product->brand }}</li>
                            <li><strong>Manufacturer:</strong> {{ $product->manufacturer }}</li>
                            @if($product->weight)
                                <li><strong>Berat:</strong> {{ $product->weight }} kg</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li><strong>Unit:</strong> {{ $product->unit }}</li>
                            @if($product->expiry_date)
                                <li><strong>Tanggal Kadaluarsa:</strong> {{ $product->expiry_date->format('d/m/Y') }}</li>
                            @endif
                            @if($product->batch_number)
                                <li><strong>No. Batch:</strong> {{ $product->batch_number }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Add to Cart Section -->
            @if($product->in_stock)
                <div class="border rounded p-3 mb-4">
                    <h6 class="mb-3">Tambah ke Keranjang</h6>
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label class="form-label">Jumlah</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">-</button>
                                <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="{{ $product->manage_stock ? $product->stock_quantity : 999 }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>
                        <div class="col-8">
                            <button class="btn btn-primary btn-lg w-100" onclick="addToCart({{ $product->id }})">
                                <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Warning for Prescription Required -->
            @if($product->prescription_required)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Produk ini memerlukan resep dokter. Silakan upload resep saat checkout.
                </div>
            @endif
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">
                        Deskripsi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="dosage-tab" data-bs-toggle="tab" data-bs-target="#dosage" type="button">
                        Dosis & Cara Pakai
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="side-effects-tab" data-bs-toggle="tab" data-bs-target="#side-effects" type="button">
                        Efek Samping
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contraindications-tab" data-bs-toggle="tab" data-bs-target="#contraindications" type="button">
                        Kontraindikasi
                    </button>
                </li>
            </ul>

            <div class="tab-content border-start border-end border-bottom p-4" id="productTabContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <h6>Deskripsi Produk</h6>
                    <p>{{ $product->description }}</p>
                </div>
                
                <div class="tab-pane fade" id="dosage" role="tabpanel">
                    <h6>Dosis & Cara Penggunaan</h6>
                    <p>{{ $product->dosage_instructions ?: 'Informasi dosis tidak tersedia. Konsultasikan dengan apoteker atau dokter.' }}</p>
                </div>
                
                <div class="tab-pane fade" id="side-effects" role="tabpanel">
                    <h6>Efek Samping</h6>
                    <p>{{ $product->side_effects ?: 'Informasi efek samping tidak tersedia. Konsultasikan dengan apoteker atau dokter.' }}</p>
                </div>
                
                <div class="tab-pane fade" id="contraindications" role="tabpanel">
                    <h6>Kontraindikasi</h6>
                    <p>{{ $product->contraindications ?: 'Informasi kontraindikasi tidak tersedia. Konsultasikan dengan apoteker atau dokter.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-4">Produk Terkait</h4>
                <div class="row">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card product-card border-0 shadow-sm position-relative">
                                @if($relatedProduct->is_on_sale)
                                    <span class="badge badge-sale">-{{ $relatedProduct->discount_percentage }}%</span>
                                @endif

                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center overflow-hidden" style="height: 180px;">
                                    @if($relatedProduct->image_url)
                                        <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-pills fa-2x text-muted"></i>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="text-decoration-none text-dark">
                                            {{ Str::limit($relatedProduct->name, 40) }}
                                        </a>
                                    </h6>
                                    
                                    <p class="text-muted small mb-2">{{ $relatedProduct->brand }}</p>

                                    <div class="mb-3">
                                        @if($relatedProduct->is_on_sale)
                                            <span class="price-sale">{{ $relatedProduct->formatted_sale_price }}</span>
                                            <span class="price-original ms-1">{{ $relatedProduct->formatted_price }}</span>
                                        @else
                                            <span class="text-primary fw-bold">{{ $relatedProduct->formatted_price }}</span>
                                        @endif
                                    </div>

                                    <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-eye me-1"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.getAttribute('max'));
    
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const minValue = parseInt(quantityInput.getAttribute('min'));
    
    if (currentValue > minValue) {
        quantityInput.value = currentValue - 1;
    }
}

function addToCart(productId) {
    const quantity = document.getElementById('quantity').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: parseInt(quantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = data.cart_count;
            }
            
            // Show success message
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Terjadi kesalahan saat menambahkan ke keranjang.', 'error');
    });
}

// Show message function
function showMessage(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" style="top: 80px; right: 20px; z-index: 1050; min-width: 300px;" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 3000);
}
</script>
@endpush

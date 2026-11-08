@extends('layouts.catalog')

@section('title', 'Produk Kesehatan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/products.js') }}"></script>
@endpush

@section('content')
<!-- Page Header -->
<div class="products-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1><i class="fas fa-pills me-3"></i>Produk Kesehatan</h1>
                <p>Temukan berbagai produk kesehatan berkualitas untuk kebutuhan Anda</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex align-items-center justify-content-lg-end">
                    <div class="me-3">
                        <i class="fas fa-shipping-fast fa-2x text-white-50"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-white">Pengiriman Cepat</h6>
                        <small class="text-white-50">Gratis ongkir</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="category-sidebar p-4">
                <h5 class="mb-3"><i class="fas fa-list me-2"></i>Kategori</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('products.index') }}" 
                           class="{{ !request('category') ? 'active' : '' }}">
                            <span><i class="fas fa-pills me-2"></i>Semua Produk</span>
                            <span class="badge">{{ $products->total() }}</span>
                        </a>
                    </li>
                    @foreach($categories as $category)
                        <li class="mb-2">
                            <a href="{{ route('products.category', $category->slug) }}" 
                               class="{{ request('category') == $category->slug ? 'active' : '' }}">
                                <span><i class="fas fa-circle me-2" style="font-size: 0.5em;"></i>{{ $category->name }}</span>
                                <span class="badge">{{ $category->active_products_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- Filter Form -->
                <div class="filter-section">
                    <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Filter</h6>
                    <form method="GET" action="{{ route('products.index') }}">
                        <!-- Preserve search term -->
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        
                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Rentang Harga</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Prescription Filter -->
                        <div class="mb-3">
                            <label class="form-label">Jenis Obat</label>
                            <select name="prescription" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <option value="0" {{ request('prescription') === '0' ? 'selected' : '' }}>Obat Bebas</option>
                                <option value="1" {{ request('prescription') === '1' ? 'selected' : '' }}>Resep Dokter</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-search me-1"></i>Terapkan Filter
                        </button>
                    </form>

                    <!-- Clear Filters -->
                    @if(request()->hasAny(['min_price', 'max_price', 'prescription', 'search']))
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                            <i class="fas fa-times me-1"></i>Hapus Filter
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="products-main">
                <!-- Search Results Header -->
                @if(request('search'))
                    <div class="search-results-header">
                        <i class="fas fa-search me-2"></i>
                        Hasil pencarian untuk "<strong>{{ request('search') }}</strong>" - {{ $products->total() }} produk ditemukan
                    </div>
                @endif

                <!-- Sorting and View Options -->
                <div class="sorting-section d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Produk Kesehatan</h4>
                        <p class="mb-0">{{ $products->total() }} produk tersedia</p>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <label class="me-2">Urutkan:</label>
                        <form method="GET" action="{{ url()->current() }}" class="d-inline">
                            <!-- Preserve all current parameters -->
                            @foreach(request()->except(['sort', 'page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            
                            <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                <option value="">Default</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Rendah</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tinggi</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card product-card">
                                    <!-- Sale Badge -->
                                    @if($product->is_on_sale)
                                        <span class="badge-sale">
                                            -{{ $product->discount_percentage }}%
                                        </span>
                                    @endif

                                    <!-- Product Image -->
                                    <div class="card-img-top">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-pills"></i>
                                        @endif
                                    </div>

                                    <div class="card-body">
                                        <div class="mb-2">
                                            @if($product->prescription_required)
                                                <span class="badge-prescription">
                                                    <i class="fas fa-prescription me-1"></i>Resep Dokter
                                                </span>
                                            @endif
                                            @if($product->is_featured)
                                                <span class="badge-featured">
                                                    <i class="fas fa-star me-1"></i>Unggulan
                                                </span>
                                            @endif
                                        </div>

                                        <h6 class="card-title">
                                            <a href="{{ route('products.show', $product->slug) }}">
                                                {{ $product->name }}
                                            </a>
                                        </h6>
                                        
                                        <p class="product-brand">{{ $product->brand }}</p>
                                        <p class="product-description">{{ Str::limit($product->short_description, 60) }}</p>

                                        <!-- Price -->
                                        <div class="price-container">
                                            @if($product->is_on_sale)
                                                <span class="price-sale">{{ $product->formatted_sale_price }}</span>
                                                <span class="price-original">{{ $product->formatted_price }}</span>
                                            @else
                                                <span class="price-normal">{{ $product->formatted_price }}</span>
                                            @endif
                                        </div>

                                        <!-- Stock Status -->
                                        @if($product->in_stock)
                                            <div class="stock-status stock-available">
                                                <i class="fas fa-check-circle me-1"></i>Stok Tersedia
                                            </div>
                                        @else
                                            <div class="stock-status stock-unavailable">
                                                <i class="fas fa-times-circle me-1"></i>Stok Habis
                                            </div>
                                        @endif

                                        <!-- Actions -->
                                        <div class="product-actions">
                                            <a href="{{ route('products.show', $product->slug) }}" 
                                               class="btn btn-view-detail">
                                                <i class="fas fa-eye me-1"></i>Lihat Detail
                                            </a>
                                            @if($product->in_stock)
                                                <button class="btn btn-add-cart" 
                                                        onclick="addToCart({{ $product->id }})">
                                                    <i class="fas fa-cart-plus me-1"></i>Tambah ke Keranjang
                                                </button>
                                            @else
                                                <button class="btn btn-add-cart" disabled>
                                                    <i class="fas fa-times me-1"></i>Stok Habis
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-container">
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- No Products Found -->
                    <div class="no-products">
                        <i class="fas fa-search"></i>
                        <h5>Tidak ada produk ditemukan</h5>
                        <p>Coba ubah kata kunci pencarian atau filter yang Anda gunakan.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali ke Semua Produk
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addToCart(productId) {
    const quantity = 1; // Default quantity
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menambahkan...';
    button.disabled = true;
    
    fetch('/cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
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
            
            // Add success animation to button
            button.innerHTML = '<i class="fas fa-check me-1"></i>Berhasil!';
            button.style.background = 'var(--secondary-color)';
            
            // Reset button after 2 seconds
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
                button.disabled = false;
            }, 2000);
        } else {
            showMessage(data.message, 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Terjadi kesalahan saat menambahkan ke keranjang.', 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Show message function with improved styling
function showMessage(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 80px; right: 20px; z-index: 1050; min-width: 300px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);" 
             role="alert">
            <i class="fas ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto-hide after 4 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 4000);
}

// Add smooth scroll to top functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add scroll to top button
    const scrollToTopBtn = document.createElement('button');
    scrollToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    scrollToTopBtn.className = 'btn btn-primary position-fixed';
    scrollToTopBtn.style.cssText = 'bottom: 20px; right: 20px; z-index: 1000; border-radius: 50%; width: 50px; height: 50px; display: none; box-shadow: 0 4px 20px rgba(0,0,0,0.15);';
    
    document.body.appendChild(scrollToTopBtn);
    
    // Show/hide scroll to top button
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.style.display = 'block';
        } else {
            scrollToTopBtn.style.display = 'none';
        }
    });
    
    // Scroll to top functionality
    scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>
@endpush

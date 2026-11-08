@extends('layouts.catalog')

@section('title', 'Hasil Pencarian: ' . $searchTerm)

@section('content')
<div class="container">
    <!-- Search Results Header -->
    <div class="bg-white rounded p-4 shadow-sm mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2">Hasil Pencarian</h2>
                <p class="text-muted mb-0">
                    Pencarian untuk "<strong>{{ $searchTerm }}</strong>" - {{ $products->total() }} produk ditemukan
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="category-sidebar p-4">
                <h5 class="mb-3"><i class="fas fa-list me-2"></i>Kategori</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('products.index') }}" class="text-decoration-none text-dark">
                            <i class="fas fa-pills me-2"></i>Semua Produk
                        </a>
                    </li>
                    @foreach($categories as $category)
                        <li class="mb-2">
                            <a href="{{ route('products.category', $category->slug) }}" 
                               class="text-decoration-none text-dark">
                                <i class="fas fa-circle me-2" style="font-size: 0.5em;"></i>
                                {{ $category->name }}
                                <span class="badge bg-light text-dark ms-1">{{ $category->active_products_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- Search in Category -->
                <hr>
                <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Perbaiki Pencarian</h6>
                <form method="GET" action="{{ route('products.search') }}">
                    <input type="hidden" name="q" value="{{ $searchTerm }}">
                    
                    <!-- Category Filter -->
                    <div class="mb-3">
                        <label class="form-label">Cari dalam kategori</label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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

                <!-- Clear Search -->
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                    <i class="fas fa-times me-1"></i>Hapus Pencarian
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Search Info -->
            @if(request()->hasAny(['category', 'min_price', 'max_price', 'prescription']))
                <div class="alert alert-info">
                    <i class="fas fa-filter me-2"></i>
                    Filter aktif diterapkan pada hasil pencarian.
                    <a href="{{ route('products.search', ['q' => $searchTerm]) }}" class="alert-link">Hapus semua filter</a>
                </div>
            @endif

            <!-- Sorting Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">Hasil Pencarian</h4>
                    <p class="text-muted mb-0">{{ $products->total() }} produk ditemukan</p>
                </div>
                
                <div class="d-flex align-items-center">
                    <label class="me-2">Urutkan:</label>
                    <form method="GET" action="{{ route('products.search') }}" class="d-inline">
                        <!-- Preserve all current parameters -->
                        @foreach(request()->except(['sort', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                            <option value="">Relevansi</option>
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
                            <div class="card product-card border-0 shadow-sm position-relative">
                                <!-- Sale Badge -->
                                @if($product->is_on_sale)
                                    <span class="badge badge-sale">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @endif

                                <!-- Product Image -->
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center overflow-hidden" style="height: 200px;">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-pills fa-3x text-muted"></i>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">{{ $product->category->name }}</small>
                                        @if($product->prescription_required)
                                            <span class="badge badge-prescription small ms-1">
                                                <i class="fas fa-prescription me-1"></i>Resep
                                            </span>
                                        @endif
                                        @if($product->is_featured)
                                            <span class="badge bg-success small ms-1">
                                                <i class="fas fa-star me-1"></i>Unggulan
                                            </span>
                                        @endif
                                    </div>

                                    <h6 class="card-title">
                                        <a href="{{ route('products.show', $product->slug) }}" 
                                           class="text-decoration-none text-dark">
                                            {!! str_ireplace($searchTerm, '<mark>' . $searchTerm . '</mark>', $product->name) !!}
                                        </a>
                                    </h6>
                                    
                                    <p class="text-muted small mb-2">{{ $product->brand }}</p>
                                    <p class="card-text small text-muted">
                                        {!! str_ireplace($searchTerm, '<mark>' . $searchTerm . '</mark>', Str::limit($product->short_description, 60)) !!}
                                    </p>

                                    <!-- Price -->
                                    <div class="mb-3">
                                        @if($product->is_on_sale)
                                            <span class="price-sale h6">{{ $product->formatted_sale_price }}</span>
                                            <span class="price-original ms-2">{{ $product->formatted_price }}</span>
                                        @else
                                            <span class="h6 text-primary">{{ $product->formatted_price }}</span>
                                        @endif
                                    </div>

                                    <!-- Stock Status -->
                                    @if($product->in_stock)
                                        <p class="text-success small mb-2">
                                            <i class="fas fa-check-circle me-1"></i>Stok Tersedia
                                        </p>
                                    @else
                                        <p class="text-danger small mb-2">
                                            <i class="fas fa-times-circle me-1"></i>Stok Habis
                                        </p>
                                    @endif

                                    <!-- Actions -->
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('products.show', $product->slug) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Lihat Detail
                                        </a>
                                        @if($product->in_stock)
                                            <button class="btn btn-primary btn-sm" 
                                                    onclick="addToCart({{ $product->id }})">
                                                <i class="fas fa-cart-plus me-1"></i>Tambah ke Keranjang
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <!-- No Products Found -->
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>Tidak ada produk ditemukan</h5>
                    <p class="text-muted">
                        Tidak ditemukan produk yang cocok dengan pencarian "<strong>{{ $searchTerm }}</strong>".
                        <br>Coba kata kunci yang berbeda atau lihat semua produk.
                    </p>
                    
                    <!-- Search Suggestions -->
                    <div class="mt-4">
                        <h6>Saran Pencarian:</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('products.search', ['q' => 'paracetamol']) }}" class="btn btn-outline-secondary btn-sm">Paracetamol</a>
                            <a href="{{ route('products.search', ['q' => 'vitamin']) }}" class="btn btn-outline-secondary btn-sm">Vitamin</a>
                            <a href="{{ route('products.search', ['q' => 'obat batuk']) }}" class="btn btn-outline-secondary btn-sm">Obat Batuk</a>
                            <a href="{{ route('products.search', ['q' => 'antiseptik']) }}" class="btn btn-outline-secondary btn-sm">Antiseptik</a>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-1"></i>Lihat Semua Produk
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addToCart(productId) {
    const quantity = 1; // Default quantity
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
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

@extends('layouts.catalog')

@section('title', 'Farmasi Solusi Kita - Apotek Online Terpercaya')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section text-white">
    <div class="scene" id="homeScene"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="hero-title mb-4">
                    Kesehatan keluarga, <span>satu klik</span> saja.
                </h1>
                <p class="hero-subtitle mb-5">
                    Apotek online terpercaya dengan ribuan produk kesehatan dan obat-obatan berkualitas. 
                    Dapatkan layanan kesehatan terbaik dengan pengiriman cepat ke seluruh Indonesia.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('products.index') }}"
                       class="btn btn-light btn-lg me-3 mb-3 px-4 py-3 fw-bold">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Belanja Sekarang
                    </a>
                    <a href="#categories"
                       class="btn btn-glass btn-lg mb-3 px-4 py-3 fw-bold">
                        <i class="fas fa-th-large me-2"></i>
                        Jelajahi Kategori
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Mengapa Memilih Kami?</h2>
            <p class="section-subtitle">
                Kami berkomitmen memberikan layanan kesehatan terbaik dengan standar kualitas tinggi
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon quality">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">Produk Berkualitas</h3>
                    <p class="text-muted mb-0">
                        Semua produk terjamin kualitas dan keamanannya dengan sertifikasi BPOM dan standar farmasi internasional
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon delivery">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">Pengiriman Cepat</h3>
                    <p class="text-muted mb-0">
                        Pengiriman cepat ke seluruh Indonesia dengan berbagai pilihan kurir terpercaya dan tracking real-time
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon consultation">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">Konsultasi Gratis</h3>
                    <p class="text-muted mb-0">
                        Konsultasi dengan apoteker berpengalaman untuk mendapatkan rekomendasi produk yang tepat
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="categories-section py-5">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Kategori Produk</h2>
            <p class="section-subtitle">
                Temukan produk kesehatan yang Anda butuhkan dalam berbagai kategori yang lengkap
            </p>
        </div>
        
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('products.category', $category->slug) }}" 
                   class="text-decoration-none">
                    <div class="category-card h-100">
                        <div class="category-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <h3 class="h6 fw-bold text-dark mb-2">{{ $category->name }}</h3>
                        <p class="text-muted small mb-0">{{ $category->description }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" 
               class="btn btn-primary btn-lg px-5 py-3 fw-bold">
                <i class="fas fa-th-large me-2"></i>
                Lihat Semua Kategori
            </a>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section class="products-section py-5">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Produk Unggulan</h2>
            <p class="section-subtitle">
                Produk pilihan terbaik dengan kualitas terjamin dan harga terbaik
            </p>
        </div>
        
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card">
                    <div class="product-image">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-100 h-100">
                        @else
                            <div class="placeholder-icon">
                                <i class="fas fa-pills"></i>
                            </div>
                        @endif
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <p class="product-description">{{ Str::limit($product->description, 80) }}</p>
                        <div class="product-price">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        <a href="{{ route('products.show', $product->slug) }}" 
                           class="btn btn-primary product-button">
                            <i class="fas fa-eye me-2"></i>
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" 
               class="btn btn-primary btn-lg px-5 py-3 fw-bold">
                <i class="fas fa-shopping-bag me-2"></i>
                Lihat Semua Produk
            </a>
        </div>
    </div>
</section>
@endif

<!-- Latest Products Section -->
@if($latestProducts->count() > 0)
<section class="categories-section py-5">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Produk Terbaru</h2>
            <p class="section-subtitle">
                Produk terbaru yang baru saja masuk ke toko kami dengan kualitas terjamin
            </p>
        </div>
        
        <div class="row g-4">
            @foreach($latestProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card">
                    <div class="product-image">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-100 h-100">
                        @else
                            <div class="placeholder-icon">
                                <i class="fas fa-pills"></i>
                            </div>
                        @endif
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <p class="product-description">{{ Str::limit($product->description, 80) }}</p>
                        <div class="product-price">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        <a href="{{ route('products.show', $product->slug) }}" 
                           class="btn btn-primary product-button">
                            <i class="fas fa-eye me-2"></i>
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Statistics Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon quality">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="h4 fw-bold text-primary mb-2">10,000+</h3>
                    <p class="text-muted mb-0">Pelanggan Puas</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon delivery">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3 class="h4 fw-bold text-primary mb-2">5,000+</h3>
                    <p class="text-muted mb-0">Produk Tersedia</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon consultation">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="h4 fw-bold text-primary mb-2">50,000+</h3>
                    <p class="text-muted mb-0">Pengiriman Berhasil</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon quality">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="h4 fw-bold text-primary mb-2">4.9/5</h3>
                    <p class="text-muted mb-0">Rating Pelanggan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section text-white py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center newsletter-content">
                <h2 class="h1 fw-bold mb-4">Berlangganan Newsletter</h2>
                <p class="h5 mb-5 opacity-75">
                    Dapatkan informasi terbaru tentang produk kesehatan, promo menarik, dan tips kesehatan langsung ke email Anda
                </p>
                
                <form class="newsletter-form d-flex gap-3">
                    <input type="email" 
                           class="form-control newsletter-input" 
                           placeholder="Masukkan email Anda" 
                           required>
                    <button type="submit" class="btn newsletter-button">
                        <i class="fas fa-paper-plane me-2"></i>
                        Berlangganan
                    </button>
                </form>
                
                <p class="small mt-3 opacity-75">
                    Kami tidak akan mengirim spam. Anda dapat berhenti berlangganan kapan saja.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="categories-section py-5">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Apa Kata Pelanggan Kami?</h2>
            <p class="section-subtitle">
                Testimoni dari pelanggan setia yang telah mempercayai layanan kami
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Sarah Johnson</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-0">
                        "Pelayanan sangat cepat dan produk berkualitas. Pengiriman tepat waktu dan packaging yang aman. Sangat puas!"
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Ahmad Rahman</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-0">
                        "Konsultasi dengan apoteker sangat membantu. Produk yang direkomendasikan sesuai dengan kebutuhan saya."
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Maria Garcia</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-0">
                        "Harga sangat kompetitif dan kualitas produk terjamin. Sudah berbelanja di sini selama 2 tahun."
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="{{ asset('js/pharma-3d.js') }}"></script>
<script>
    // 3D capsule scene in the hero, same setup as the customer dashboard
    document.addEventListener('DOMContentLoaded', function () {
        const wide = window.innerWidth > 992;
        Pharma3D.mount(document.getElementById('homeScene'), wide
            ? { objects: 14, heroOffsetX: 4.2, area: { xMin: 0.5, xMax: 9, yMin: -4, yMax: 4 } }
            : { objects: 7, hero: false, particles: 120, area: { xMin: -4, xMax: 4, yMin: -6.2, yMax: -4 } });
    });
</script>
<script src="{{ asset('js/home.js') }}"></script>
@endpush

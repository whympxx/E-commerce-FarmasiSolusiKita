@extends('layouts.admin')

@section('title', 'Tambah Pelanggan Baru')
@section('page-title', 'Tambah Pelanggan Baru')

@section('content')
<div class="customer-create-container">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm animate__animated animate__fadeInUp">
                <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-user-plus me-2"></i>Form Tambah Pelanggan
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.customers.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="fas fa-user me-1 text-primary"></i>Nama Lengkap
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Masukkan nama lengkap"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="fas fa-envelope me-1 text-primary"></i>Email
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="contoh@email.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label fw-semibold">
                                        <i class="fas fa-lock me-1 text-primary"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Minimal 8 karakter"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label fw-semibold">
                                        <i class="fas fa-lock me-1 text-primary"></i>Konfirmasi Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Ulangi password"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Verification -->
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="email_verified" 
                                           name="email_verified" 
                                           value="1" 
                                           {{ old('email_verified') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="email_verified">
                                        <i class="fas fa-check-circle me-1 text-success"></i>
                                        Email sudah terverifikasi
                                    </label>
                                    <div class="form-text">
                                        Centang jika email pelanggan sudah terverifikasi
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{ route('admin.customers.index') }}" 
                                       class="btn btn-outline-secondary rounded-pill px-4">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                                        <i class="fas fa-save me-2"></i>Simpan Pelanggan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card border-0 rounded-4 shadow-sm mt-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="card-header bg-gradient-info text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>Informasi
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <h6 class="fw-semibold text-primary mb-2">
                                    <i class="fas fa-shield-alt me-1"></i>Keamanan
                                </h6>
                                <p class="text-muted small mb-0">
                                    Password akan dienkripsi secara otomatis untuk keamanan data pelanggan.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <h6 class="fw-semibold text-success mb-2">
                                    <i class="fas fa-envelope-open me-1"></i>Verifikasi Email
                                </h6>
                                <p class="text-muted small mb-0">
                                    Jika email sudah terverifikasi, pelanggan dapat langsung mengakses fitur lengkap.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <h6 class="fw-semibold text-warning mb-2">
                                    <i class="fas fa-user-check me-1"></i>Status Pelanggan
                                </h6>
                                <p class="text-muted small mb-0">
                                    Pelanggan baru akan otomatis memiliki status "Tidak Aktif" hingga melakukan pesanan pertama.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <h6 class="fw-semibold text-info mb-2">
                                    <i class="fas fa-cog me-1"></i>Pengaturan
                                </h6>
                                <p class="text-muted small mb-0">
                                    Data pelanggan dapat diedit atau dihapus melalui menu kelola pelanggan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-customers.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@push('scripts')
<script src="{{ asset('js/admin-customers.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('password_confirmation');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        
        if (password.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            return false;
        }
    });
});
</script>
@endpush 
@extends('layouts.admin')

@section('title', 'Edit Pelanggan - ' . $customer->name)
@section('page-title', 'Edit Pelanggan')

@section('content')
<div class="customer-edit-container">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm animate__animated animate__fadeInUp">
                <div class="card-header bg-gradient-warning text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-user-edit me-2"></i>Form Edit Pelanggan
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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
                                           value="{{ old('name', $customer->name) }}" 
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
                                           value="{{ old('email', $customer->email) }}" 
                                           placeholder="contoh@email.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password (Optional) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label fw-semibold">
                                        <i class="fas fa-lock me-1 text-primary"></i>Password Baru
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Kosongkan jika tidak ingin mengubah">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">
                                        Kosongkan jika tidak ingin mengubah password
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
                                               placeholder="Ulangi password baru">
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
                                           {{ old('email_verified', $customer->email_verified_at ? '1' : '') ? 'checked' : '' }}>
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
                                    <a href="{{ route('admin.customers.show', $customer) }}" 
                                       class="btn btn-outline-secondary rounded-pill px-4">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-warning rounded-pill px-4">
                                        <i class="fas fa-save me-2"></i>Update Pelanggan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customer Information Card -->
            <div class="card border-0 rounded-4 shadow-sm mt-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="card-header bg-gradient-info text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>Informasi Pelanggan
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <h6 class="fw-semibold text-primary mb-2">
                                    <i class="fas fa-id-card me-1"></i>ID Pelanggan
                                </h6>
                                <p class="text-muted mb-0">#{{ $customer->id }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <h6 class="fw-semibold text-success mb-2">
                                    <i class="fas fa-calendar-plus me-1"></i>Tanggal Registrasi
                                </h6>
                                <p class="text-muted mb-0">{{ $customer->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <h6 class="fw-semibold text-warning mb-2">
                                    <i class="fas fa-clock me-1"></i>Terakhir Update
                                </h6>
                                <p class="text-muted mb-0">{{ $customer->updated_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <h6 class="fw-semibold text-info mb-2">
                                    <i class="fas fa-shopping-bag me-1"></i>Total Pesanan
                                </h6>
                                <p class="text-muted mb-0">{{ $customer->orders()->count() }} pesanan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="card border-0 rounded-4 shadow-sm mt-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="card-header bg-gradient-danger text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Peringatan
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-warning border-0 mb-0">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
                            <div>
                                <h6 class="fw-semibold mb-2">Perhatian!</h6>
                                <p class="mb-2">Perubahan data pelanggan akan mempengaruhi:</p>
                                <ul class="mb-0">
                                    <li>Riwayat pesanan pelanggan</li>
                                    <li>Data login dan keamanan</li>
                                    <li>Status verifikasi email</li>
                                </ul>
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
        
        // Only validate if password is being changed
        if (password.value && password.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            return false;
        }
    });

    // Show confirmation dialog
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.addEventListener('click', function(e) {
        if (!confirm('Apakah Anda yakin ingin mengupdate data pelanggan ini?')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush 
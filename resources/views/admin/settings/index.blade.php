@extends('layouts.admin')

@section('title', 'Pengaturan - Admin Panel')
@section('page-title', 'Pengaturan Sistem')

@section('content')
<div class="settings-container">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- System Overview Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Total Pengguna</div>
                            <div class="stat-value h3 fw-bold text-primary mb-1">{{ number_format($systemStats['total_users']) }}</div>
                            <div class="stat-subtitle text-primary small">
                                <i class="fas fa-users me-1"></i>Terdaftar
                            </div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Total Produk</div>
                            <div class="stat-value h3 fw-bold text-success mb-1">{{ number_format($systemStats['total_products']) }}</div>
                            <div class="stat-subtitle text-success small">
                                <i class="fas fa-pills me-1"></i>Aktif
                            </div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-pills fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Total Pesanan</div>
                            <div class="stat-value h3 fw-bold text-info mb-1">{{ number_format($systemStats['total_orders']) }}</div>
                            <div class="stat-subtitle text-info small">
                                <i class="fas fa-shopping-bag me-1"></i>Semua waktu
                            </div>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-shopping-bag fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Total Pendapatan</div>
                            <div class="stat-value h3 fw-bold text-warning mb-1">Rp {{ number_format($systemStats['total_revenue']) }}</div>
                            <div class="stat-subtitle text-warning small">
                                <i class="fas fa-money-bill-wave me-1"></i>Lifetime
                            </div>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-cogs me-2"></i>Pengaturan Sistem
                    </h6>
                </div>
                <div class="card-body p-0">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs nav-fill" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-0" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                <i class="fas fa-globe me-2"></i>Umum
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-0" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                                <i class="fas fa-address-book me-2"></i>Kontak
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-0" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
                                <i class="fas fa-credit-card me-2"></i>Pembayaran
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-0" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                                <i class="fas fa-server me-2"></i>Sistem
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-0" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab">
                                <i class="fas fa-share-alt me-2"></i>Sosial Media
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="settingsTabContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-4" id="generalForm">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <label for="site_name" class="form-label fw-semibold">Nama Website <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-pill" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required>
                                        @error('site_name')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="currency" class="form-label fw-semibold">Mata Uang <span class="text-danger">*</span></label>
                                        <select class="form-select rounded-pill" id="currency" name="currency" required>
                                            <option value="IDR" {{ $settings['currency'] == 'IDR' ? 'selected' : '' }}>IDR - Rupiah Indonesia</option>
                                            <option value="USD" {{ $settings['currency'] == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                            <option value="EUR" {{ $settings['currency'] == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                        </select>
                                        @error('currency')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="site_description" class="form-label fw-semibold">Deskripsi Website</label>
                                        <textarea class="form-control rounded-3" id="site_description" name="site_description" rows="3">{{ old('site_description', $settings['site_description']) }}</textarea>
                                        @error('site_description')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary rounded-pill">
                                            <i class="fas fa-save me-2"></i>Simpan Pengaturan Umum
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Contact Settings -->
                        <div class="tab-pane fade" id="contact" role="tabpanel">
                            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-4" id="contactForm">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <label for="contact_email" class="form-label fw-semibold">Email Kontak <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control rounded-pill" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required>
                                        @error('contact_email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="contact_phone" class="form-label fw-semibold">Nomor Telepon</label>
                                        <input type="text" class="form-control rounded-pill" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}">
                                        @error('contact_phone')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="address" class="form-label fw-semibold">Alamat</label>
                                        <textarea class="form-control rounded-3" id="address" name="address" rows="3">{{ old('address', $settings['address']) }}</textarea>
                                        @error('address')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary rounded-pill">
                                            <i class="fas fa-save me-2"></i>Simpan Pengaturan Kontak
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Payment Settings -->
                        <div class="tab-pane fade" id="payment" role="tabpanel">
                            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-4" id="paymentForm">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <label for="tax_rate" class="form-label fw-semibold">Persentase Pajak (%)</label>
                                        <input type="number" class="form-control rounded-pill" id="tax_rate" name="tax_rate" value="{{ old('tax_rate', $settings['tax_rate']) }}" min="0" max="100" step="0.1">
                                        @error('tax_rate')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="shipping_cost" class="form-label fw-semibold">Biaya Pengiriman Default (IDR)</label>
                                        <input type="number" class="form-control rounded-pill" id="shipping_cost" name="shipping_cost" value="{{ old('shipping_cost', $settings['shipping_cost']) }}" min="0">
                                        @error('shipping_cost')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary rounded-pill">
                                            <i class="fas fa-save me-2"></i>Simpan Pengaturan Pembayaran
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- System Settings -->
                        <div class="tab-pane fade" id="system" role="tabpanel">
                            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-4" id="systemForm">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="maintenance_mode" value="0">
                                            <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ $settings['maintenance_mode'] ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="maintenance_mode">
                                                Mode Maintenance
                                            </label>
                                        </div>
                                        <small class="text-muted">Aktifkan untuk menutup website sementara</small>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="email_notifications" value="0">
                                            <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" value="1" {{ $settings['email_notifications'] ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="email_notifications">
                                                Notifikasi Email
                                            </label>
                                        </div>
                                        <small class="text-muted">Kirim notifikasi email otomatis</small>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="auto_backup" value="0">
                                            <input class="form-check-input" type="checkbox" id="auto_backup" name="auto_backup" value="1" {{ $settings['auto_backup'] ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="auto_backup">
                                                Backup Otomatis
                                            </label>
                                        </div>
                                        <small class="text-muted">Backup database secara otomatis</small>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="session_timeout" class="form-label fw-semibold">Timeout Session (menit)</label>
                                        <input type="number" class="form-control rounded-pill" id="session_timeout" name="session_timeout" value="{{ old('session_timeout', $settings['session_timeout']) }}" min="5" max="480">
                                        @error('session_timeout')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary rounded-pill">
                                            <i class="fas fa-save me-2"></i>Simpan Pengaturan Sistem
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Social Media Settings -->
                        <div class="tab-pane fade" id="social" role="tabpanel">
                            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-4" id="socialForm">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <label for="social_facebook" class="form-label fw-semibold">URL Facebook</label>
                                        <input type="url" class="form-control rounded-pill" id="social_facebook" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="social_instagram" class="form-label fw-semibold">URL Instagram</label>
                                        <input type="url" class="form-control rounded-pill" id="social_instagram" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="social_twitter" class="form-label fw-semibold">URL Twitter</label>
                                        <input type="url" class="form-control rounded-pill" id="social_twitter" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary rounded-pill">
                                            <i class="fas fa-save me-2"></i>Simpan Pengaturan Sosial Media
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card-footer bg-light border-0 p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary rounded-pill" id="backupBtn">
                                    <i class="fas fa-download me-2"></i>Backup Database
                                </button>
                                <button type="button" class="btn btn-outline-warning rounded-pill" id="clearCacheBtn">
                                    <i class="fas fa-broom me-2"></i>Bersihkan Cache
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary rounded-pill" onclick="resetForms()">
                                    <i class="fas fa-undo me-2"></i>Reset Semua
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="row g-4 mt-4">
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header bg-gradient-info text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-server me-2"></i>Informasi Sistem
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="info-item">
                                <div class="info-label text-muted fw-semibold">PHP Version</div>
                                <div class="info-value fw-bold">{{ $systemStats['php_version'] }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item">
                                <div class="info-label text-muted fw-semibold">Laravel Version</div>
                                <div class="info-value fw-bold">{{ $systemStats['laravel_version'] }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item">
                                <div class="info-label text-muted fw-semibold">Memory Limit</div>
                                <div class="info-value fw-bold">{{ $systemStats['memory_usage']['limit'] }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item">
                                <div class="info-label text-muted fw-semibold">Memory Usage</div>
                                <div class="info-value fw-bold">{{ number_format($systemStats['memory_usage']['percentage'], 1) }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header bg-gradient-success text-white border-0 rounded-top-4 p-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-hdd me-2"></i>Penggunaan Disk
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="disk-usage-info">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold">Storage Usage</span>
                            <span class="fw-bold text-success">{{ number_format($systemStats['disk_usage']['percentage'], 1) }}%</span>
                        </div>
                        <div class="progress rounded-pill mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $systemStats['disk_usage']['percentage'] }}%"></div>
                        </div>
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <div class="disk-stat">
                                    <div class="disk-value fw-bold text-primary">{{ number_format($systemStats['disk_usage']['used'] / 1024 / 1024 / 1024, 1) }}</div>
                                    <div class="disk-label text-muted small">GB Used</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="disk-stat">
                                    <div class="disk-value fw-bold text-success">{{ number_format($systemStats['disk_usage']['free'] / 1024 / 1024 / 1024, 1) }}</div>
                                    <div class="disk-label text-muted small">GB Free</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="disk-stat">
                                    <div class="disk-value fw-bold text-info">{{ number_format($systemStats['disk_usage']['total'] / 1024 / 1024 / 1024, 1) }}</div>
                                    <div class="disk-label text-muted small">GB Total</div>
                                </div>
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
<link rel="stylesheet" href="{{ asset('css/admin-settings.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/admin-settings.js') }}"></script>
@endpush 
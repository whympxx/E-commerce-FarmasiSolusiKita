@extends('layouts.admin')

@section('title', 'Kelola Pelanggan')
@section('page-title', 'Kelola Pelanggan')

@section('content')
<div class="customers-container">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Total Pelanggan</div>
                            <div class="stat-value h2 fw-bold text-primary mb-1">{{ $stats['total_customers'] }}</div>
                            <div class="stat-subtitle text-success small">
                                <i class="fas fa-users me-1"></i>terdaftar
                            </div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Terverifikasi</div>
                            <div class="stat-value h2 fw-bold text-success mb-1">{{ $stats['verified_customers'] }}</div>
                            <div class="stat-subtitle text-success small">
                                <i class="fas fa-check-circle me-1"></i>email terverifikasi
                            </div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: {{ $stats['total_customers'] > 0 ? ($stats['verified_customers'] / $stats['total_customers']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Pelanggan Aktif</div>
                            <div class="stat-value h2 fw-bold text-info mb-1">{{ $stats['active_customers'] }}</div>
                            <div class="stat-subtitle text-info small">
                                <i class="fas fa-shopping-bag me-1"></i>pernah berbelanja
                            </div>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-shopping-bag fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: {{ $stats['total_customers'] > 0 ? ($stats['active_customers'] / $stats['total_customers']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
            <div class="stat-card h-100 border-0 rounded-4 shadow-sm position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label text-muted fw-semibold mb-1">Bulan Ini</div>
                            <div class="stat-value h2 fw-bold text-warning mb-1">{{ $stats['new_customers_this_month'] }}</div>
                            <div class="stat-subtitle text-warning small">
                                <i class="fas fa-user-plus me-1"></i>pelanggan baru
                            </div>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-user-plus fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card border-0 rounded-4 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
        <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 d-flex justify-content-between align-items-center p-4">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-users me-2"></i>Daftar Pelanggan
            </h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.customers.export') }}" class="btn btn-light btn-sm rounded-pill">
                    <i class="fas fa-download me-1"></i>Export
                </a>
                <a href="{{ route('admin.customers.create') }}" class="btn btn-light btn-sm rounded-pill">
                    <i class="fas fa-plus me-1"></i>Tambah Pelanggan
                </a>
            </div>
        </div>
        
        <div class="card-body p-4">
            <!-- Search and Filter -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.customers.index') }}" class="d-flex">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0" 
                                   placeholder="Cari nama atau email..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Belum Verifikasi</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        <select name="sort_by" class="form-select" onchange="this.form.submit()">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Urutkan</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                            <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="orders_count" {{ request('sort_by') == 'orders_count' ? 'selected' : '' }}>Total Pesanan</option>
                            <option value="orders_sum_total_amount" {{ request('sort_by') == 'orders_sum_total_amount' ? 'selected' : '' }}>Total Belanja</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Customers Table -->
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-4 py-3 fw-semibold">Pelanggan</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Status</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Pesanan</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Total Belanja</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Registrasi</th>
                                <th class="border-0 px-4 py-3 fw-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="customer-avatar bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $customer->name }}</div>
                                                <small class="text-muted">{{ $customer->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex flex-column gap-1">
                                            @if($customer->email_verified_at)
                                                <span class="badge bg-success rounded-pill">
                                                    <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                                </span>
                                            @else
                                                <span class="badge bg-warning rounded-pill">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Belum Verifikasi
                                                </span>
                                            @endif
                                            @if($customer->orders_count > 0)
                                                <span class="badge bg-info rounded-pill">
                                                    <i class="fas fa-shopping-bag me-1"></i>Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">
                                                    <i class="fas fa-user-clock me-1"></i>Tidak Aktif
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-semibold me-2">{{ $customer->orders_count }}</span>
                                            <small class="text-muted">pesanan</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="fw-semibold text-success">
                                            {{ $customer->orders_sum_total_amount ? 'Rp ' . number_format($customer->orders_sum_total_amount, 0, ',', '.') : 'Rp 0' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-muted small">
                                            {{ $customer->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $customer->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.customers.show', $customer) }}" 
                                               class="btn btn-outline-primary btn-sm rounded-pill">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.customers.edit', $customer) }}" 
                                               class="btn btn-outline-warning btn-sm rounded-pill">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.customers.destroy', $customer) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }} 
                        dari {{ $customers->total() }} pelanggan
                    </div>
                    <div>
                        {{ $customers->links() }}
                    </div>
                </div>
            @else
                <div class="empty-state text-center py-5">
                    <div class="empty-icon mb-3">
                        <i class="fas fa-users fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum ada pelanggan</h5>
                    <p class="text-muted mb-3">Pelanggan yang mendaftar akan muncul di sini</p>
                    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary rounded-pill">
                        <i class="fas fa-plus me-2"></i>Tambah Pelanggan Pertama
                    </a>
                </div>
            @endif
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
@endpush 
@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.customer')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile-show.css') }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-4">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center profile-photo" style="width: 90px; height: 90px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="mb-1">{{ $user->name }}</h2>
                            <p class="mb-0 text-muted">{{ $user->email }}</p>
                            <span class="badge bg-primary mt-2">Bergabung sejak {{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <div class="form-control bg-light">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <div class="form-control bg-light">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status Email</label>
                            <div class="form-control bg-light">{{ $user->email_verified_at ? 'Terverifikasi' : 'Belum diverifikasi' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jumlah Pesanan</label>
                            <div class="form-control bg-light">{{ $user->orders()->count() }}</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary px-4 py-2">
                            <i class="fas fa-receipt me-2"></i>Pesanan Saya
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-edit me-2"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

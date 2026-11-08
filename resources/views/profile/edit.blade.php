@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.customer')

@section('title', 'Pengaturan Akun')
@section('page-title', 'Pengaturan Akun')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="{{ asset('css/profile-animations.css') }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center g-4">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold fs-4 mb-0">Edit Profil</h2>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

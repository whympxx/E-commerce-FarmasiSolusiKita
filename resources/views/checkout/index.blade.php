@extends('layouts.catalog')

@section('title', 'Checkout')

@section('content')
@php
    $prev = $lastOrder;
@endphp
<div class="container py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Checkout</h2>
        <a href="{{ route('cart.index') }}" class="text-decoration-none"><i class="fas fa-arrow-left me-1"></i>Kembali ke Keranjang</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-user me-2"></i>Data Penerima</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                                   value="{{ old('customer_name', $prev->customer_name ?? $user->name) }}" required maxlength="255">
                            @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" id="customer_email" class="form-control @error('customer_email') is-invalid @enderror"
                                       value="{{ old('customer_email', $prev->customer_email ?? $user->email) }}" required maxlength="255">
                                @error('customer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                <input type="tel" name="customer_phone" id="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror"
                                       value="{{ old('customer_phone', $prev->customer_phone ?? '') }}" required maxlength="20" placeholder="08xxxxxxxxxx">
                                @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-location-dot me-2"></i>Alamat Pengiriman</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="shipping_address" id="shipping_address" rows="2" class="form-control @error('shipping_address') is-invalid @enderror"
                                      required maxlength="500" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan">{{ old('shipping_address', $prev->shipping_street ?? '') }}</textarea>
                            @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="shipping_city" class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_city" id="shipping_city" class="form-control @error('shipping_city') is-invalid @enderror"
                                       value="{{ old('shipping_city', $prev->shipping_city ?? '') }}" required maxlength="100">
                                @error('shipping_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shipping_state" class="form-label">Provinsi</label>
                                <input type="text" name="shipping_state" id="shipping_state" class="form-control @error('shipping_state') is-invalid @enderror"
                                       value="{{ old('shipping_state', $prev->shipping_state ?? '') }}" maxlength="100">
                                @error('shipping_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="shipping_postal_code" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_postal_code" id="shipping_postal_code" class="form-control @error('shipping_postal_code') is-invalid @enderror"
                                       value="{{ old('shipping_postal_code', $prev->shipping_postal_code ?? '') }}" required maxlength="10">
                                @error('shipping_postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div>
                            <label for="notes" class="form-label">Catatan (opsional)</label>
                            <textarea name="notes" id="notes" rows="2" class="form-control" maxlength="1000" placeholder="Contoh: titip di pos satpam">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Metode Pembayaran</h5></div>
                    <div class="card-body">
                        @foreach($paymentMethods as $value => $label)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="pm_{{ $value }}" value="{{ $value }}"
                                       {{ old('payment_method', 'bank_transfer') === $value ? 'checked' : '' }} required>
                                <label class="form-check-label" for="pm_{{ $value }}">{{ $label }}</label>
                            </div>
                        @endforeach
                        @error('payment_method')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm position-sticky" style="top: 1rem;">
                    <div class="card-header bg-white"><h5 class="mb-0">Ringkasan Pesanan</h5></div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="d-flex justify-content-between mb-2 small">
                                <div class="me-2">
                                    {{ $item->product->name }} <span class="text-muted">× {{ $item->quantity }}</span>
                                    @if($item->product->prescription_required)
                                        <br><span class="text-warning"><i class="fas fa-file-prescription me-1"></i>Perlu resep</span>
                                    @endif
                                </div>
                                <div class="text-nowrap">Rp {{ number_format($item->product->effective_price * $item->quantity, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Subtotal ({{ $cartTotal['items_count'] }} item)</span>
                            <span>Rp {{ number_format($cartTotal['subtotal'], 0, ',', '.') }}</span>
                        </div>
                        @if($cartTotal['discount'] > 0)
                            <div class="d-flex justify-content-between mb-1 text-success">
                                <span>Diskon</span><span>-Rp {{ number_format($cartTotal['discount'], 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between mb-1">
                            <span>Ongkos Kirim</span>
                            <span>{{ $cartTotal['shipping'] > 0 ? 'Rp ' . number_format($cartTotal['shipping'], 0, ',', '.') : 'Gratis' }}</span>
                        </div>
                        @if($cartTotal['tax_rate'] > 0)
                            <div class="d-flex justify-content-between mb-1">
                                <span>Pajak ({{ rtrim(rtrim(number_format($cartTotal['tax_rate'], 2, ',', ''), '0'), ',') }}%)</span>
                                <span>Rp {{ number_format($cartTotal['tax'], 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between fs-5 mb-3">
                            <strong>Total</strong>
                            <strong class="text-primary">Rp {{ number_format($cartTotal['grand_total'], 0, ',', '.') }}</strong>
                        </div>

                        @if($cartItems->contains(fn ($item) => $item->product->prescription_required))
                            <div class="alert alert-warning small">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Pesanan berisi obat keras. Resep dokter akan diminta saat verifikasi pesanan.
                            </div>
                        @endif

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="place-order-btn">
                                <i class="fas fa-lock me-2"></i>Buat Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Cegah pesanan dobel karena tombol diklik berkali-kali
    document.getElementById('checkout-form').addEventListener('submit', function () {
        const btn = document.getElementById('place-order-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    });
</script>
@endpush

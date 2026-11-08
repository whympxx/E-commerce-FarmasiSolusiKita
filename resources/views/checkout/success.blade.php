@extends('layouts.catalog')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="container py-2">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center py-5">
            <div class="mb-3">
                <i class="fas fa-circle-check fa-4x text-success"></i>
            </div>
            <h2 class="fw-bold">Terima kasih, pesanan Anda berhasil dibuat!</h2>
            <p class="text-muted mb-1">Nomor pesanan: <strong>{{ $order->order_number }}</strong></p>
            <p class="text-muted mb-4">Total pembayaran: <strong class="text-primary">{{ $order->formatted_total_amount }}</strong></p>

            @if($order->payment_method === 'bank_transfer')
                <div class="alert alert-info d-inline-block text-start">
                    <i class="fas fa-building-columns me-2"></i>
                    Silakan transfer sesuai total pembayaran. Pesanan akan diproses setelah pembayaran dikonfirmasi admin.
                </div>
            @elseif($order->payment_method === 'ewallet')
                <div class="alert alert-info d-inline-block text-start">
                    <i class="fas fa-wallet me-2"></i>
                    Silakan selesaikan pembayaran melalui e-wallet. Pesanan akan diproses setelah pembayaran dikonfirmasi admin.
                </div>
            @else
                <div class="alert alert-info d-inline-block text-start">
                    <i class="fas fa-truck me-2"></i>
                    Siapkan uang sesuai total pembayaran saat pesanan tiba.
                </div>
            @endif

            <div class="d-flex justify-content-center gap-2 mt-3">
                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">Lihat Detail Pesanan</a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Lanjut Belanja</a>
            </div>
        </div>
    </div>

    @include('orders._detail')
</div>
@endsection

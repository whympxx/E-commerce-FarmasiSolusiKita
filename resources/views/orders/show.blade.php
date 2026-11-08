@extends('layouts.customer')

@section('title', 'Pesanan ' . $order->order_number)
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="fade-in">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <a href="{{ route('orders.index') }}" class="text-decoration-none small"><i class="fas fa-arrow-left me-1"></i>Pesanan Saya</a>
            <h2 class="fw-bold mb-0 mt-1">Pesanan {{ $order->order_number }}</h2>
        </div>
        @if($order->status === 'pending' && $order->payment_status !== 'paid')
            <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Batalkan pesanan ini?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-times me-2"></i>Batalkan Pesanan
                </button>
            </form>
        @endif
    </div>

    @include('orders._detail')
</div>
@endsection

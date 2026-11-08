@extends('layouts.customer')

@section('title', 'Pesanan Saya')
@section('page-title', 'Pesanan Saya')

@section('content')
<div class="fade-in">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold mb-0">Halo, {{ $user->name }}</h2>
            <p class="text-muted mb-0">Pantau semua pesanan Anda di sini.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="fas fa-shopping-bag me-2"></i>Belanja Lagi
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-3 fw-bold text-primary">{{ $stats['total'] }}</div>
                <small class="text-muted">Total Pesanan</small>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-3 fw-bold text-warning">{{ $stats['active'] }}</div>
                <small class="text-muted">Sedang Berjalan</small>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-3 fw-bold text-success">{{ $stats['delivered'] }}</div>
                <small class="text-muted">Selesai</small>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><h5 class="mb-0">Riwayat Pesanan</h5></div>
        @if($orders->isEmpty())
            <div class="card-body text-center py-5">
                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-3">Anda belum memiliki pesanan.</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Mulai Belanja</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th class="text-center">Item</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Pembayaran</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->locale('id')->translatedFormat('d M Y') }}</td>
                                <td class="text-center">{{ $order->order_items_count }}</td>
                                <td class="text-end">{{ $order->formatted_total_amount }}</td>
                                <td class="text-center"><span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                                <td class="text-center"><small>{{ $order->payment_status_label }}</small></td>
                                <td class="text-end">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

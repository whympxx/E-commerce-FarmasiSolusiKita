{{-- Detail pesanan untuk pelanggan (dipakai di halaman sukses checkout dan detail pesanan) --}}
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Item Pesanan</h5>
                <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->product_name }}</div>
                                        <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                        @if($item->product && $item->product->prescription_required)
                                            <div><small class="text-warning"><i class="fas fa-file-prescription me-1"></i>Perlu resep dokter</small></div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ $item->formatted_unit_price }}</td>
                                    <td class="text-end">{{ $item->formatted_total_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between mb-1">
                    <span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-1 text-success">
                        <span>Diskon</span><span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="d-flex justify-content-between mb-1">
                    <span>Ongkos Kirim</span>
                    <span>{{ $order->shipping_amount > 0 ? 'Rp ' . number_format($order->shipping_amount, 0, ',', '.') : 'Gratis' }}</span>
                </div>
                @if($order->tax_amount > 0)
                    <div class="d-flex justify-content-between mb-1">
                        <span>Pajak</span><span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <hr class="my-2">
                <div class="d-flex justify-content-between fs-5">
                    <strong>Total</strong><strong class="text-primary">{{ $order->formatted_total_amount }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="mb-0">Informasi Pesanan</h5></div>
            <div class="card-body small">
                <p class="mb-2"><span class="text-muted">No. Pesanan</span><br><strong>{{ $order->order_number }}</strong></p>
                <p class="mb-2"><span class="text-muted">Tanggal</span><br>{{ $order->created_at->format('d M Y, H:i') }}</p>
                <p class="mb-2"><span class="text-muted">Pembayaran</span><br>{{ $order->payment_method_label }} &middot; <strong>{{ $order->payment_status_label }}</strong></p>
                @if($order->tracking_number)
                    <p class="mb-2"><span class="text-muted">Nomor Resi</span><br><strong>{{ $order->tracking_number }}</strong></p>
                @endif
                @if($order->notes)
                    <p class="mb-0"><span class="text-muted">Catatan</span><br>{{ $order->notes }}</p>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Alamat Pengiriman</h5></div>
            <div class="card-body small">
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->customer_phone }}<br>
                {{ $order->shipping_street }}<br>
                {{ collect([$order->shipping_city, $order->shipping_state, $order->shipping_postal_code])->filter()->implode(', ') }}
            </div>
        </div>
    </div>
</div>

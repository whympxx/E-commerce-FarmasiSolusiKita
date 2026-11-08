<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $table['title'] }} - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 24px; font-size: 13px; }
        @media print { .no-print { display: none !important; } body { padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print mb-3 d-flex gap-2">
        <button class="btn btn-primary btn-sm" onclick="window.print()">Cetak / Simpan PDF</button>
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.reports.index', ['report_type' => $type]) }}">Kembali</a>
    </div>

    <h4 class="mb-0">{{ config('app.name') }}</h4>
    <h5 class="text-muted">{{ $table['title'] }}</h5>
    <p class="mb-3">Periode: {{ $dateRange['from']->format('d M Y') }} &ndash; {{ $dateRange['to']->format('d M Y') }} &middot; Dicetak {{ now()->format('d M Y H:i') }}</p>

    <table class="table table-sm table-bordered mb-4" style="max-width: 600px;">
        <tr><th>Total Pendapatan</th><td>Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</td></tr>
        <tr><th>Total Pesanan</th><td>{{ $summary['total_orders'] }} ({{ $summary['completed_orders'] }} selesai)</td></tr>
        <tr><th>Produk Terjual</th><td>{{ $summary['total_products_sold'] }}</td></tr>
        <tr><th>Pelanggan Baru</th><td>{{ $summary['new_customers'] }} dari {{ $summary['total_customers'] }}</td></tr>
    </table>

    <table class="table table-sm table-striped table-bordered">
        <thead class="table-light">
            <tr>
                @foreach($table['headers'] as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($table['rows'] as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ is_float($cell) ? 'Rp ' . number_format($cell, 0, ',', '.') : $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ count($table['headers']) }}" class="text-center text-muted">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <script>window.addEventListener('load', () => window.print());</script>
</body>
</html>

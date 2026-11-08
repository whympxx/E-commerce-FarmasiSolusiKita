# Halaman Laporan Admin - Farmasi Solusi Kita

## Deskripsi
Halaman laporan admin yang menyediakan analisis komprehensif tentang performa bisnis farmasi, termasuk penjualan, produk, pelanggan, dan inventori.

## Fitur Utama

### 1. Filter Laporan
- **Jenis Laporan**: Penjualan, Produk, Pelanggan, Inventori
- **Rentang Tanggal**: Filter berdasarkan periode waktu tertentu
- **Auto-submit**: Form otomatis tersubmit saat jenis laporan berubah

### 2. Dashboard Statistik
- **Total Pendapatan**: Menampilkan total pendapatan dengan persentase pertumbuhan
- **Total Pesanan**: Jumlah pesanan dengan breakdown pesanan selesai
- **Produk Terjual**: Total produk terjual dan jenis produk unik
- **Pelanggan Baru**: Jumlah pelanggan baru dengan total pelanggan

### 3. Grafik Interaktif
- **Grafik Penjualan**: Line chart dengan periode 7, 30, atau 90 hari
- **Grafik Kategori**: Doughnut chart untuk kategori produk terlaris
- **Responsive**: Grafik menyesuaikan dengan ukuran layar

### 4. Laporan Detail
- **Produk Terlaris**: Top 10 produk berdasarkan penjualan
- **Pesanan Terbaru**: 10 pesanan terbaru dengan status
- **Data Real-time**: Terhubung langsung dengan database

### 5. Export Laporan
- **Format PDF**: Export laporan dalam format PDF
- **Format Excel**: Export laporan dalam format Excel
- **Laporan Stok**: Laporan khusus inventori
- **Data Pelanggan**: Export data pelanggan

## File yang Dibuat

### 1. View
- `resources/views/admin/reports/index.blade.php` - Halaman utama laporan

### 2. Controller
- `app/Http/Controllers/Admin/ReportController.php` - Logic bisnis laporan

### 3. Styling
- `public/css/admin-reports.css` - CSS untuk styling halaman laporan
- `public/js/admin-reports.js` - JavaScript untuk interaktivitas

### 4. Routes
- Ditambahkan ke `routes/web.php` dalam grup admin

## Struktur Database

### Tabel yang Digunakan
1. **orders** - Data pesanan
2. **order_items** - Item dalam pesanan
3. **products** - Data produk
4. **categories** - Kategori produk
5. **users** - Data pengguna/pelanggan

### Query Utama
- **Revenue Calculation**: Menghitung total pendapatan dengan filter status
- **Product Performance**: Analisis produk berdasarkan penjualan
- **Customer Analysis**: Analisis pelanggan berdasarkan order
- **Inventory Status**: Status stok produk

## Fitur Teknis

### 1. Responsive Design
- Bootstrap 5 untuk layout responsive
- Mobile-first approach
- Touch-friendly interface

### 2. Animasi dan Transisi
- Smooth hover effects
- Loading animations
- Chart transitions

### 3. Interaktivitas
- Real-time chart updates
- Dynamic data filtering
- Keyboard shortcuts (Ctrl+R untuk refresh, Ctrl+E untuk export)

### 4. Performance
- Lazy loading untuk data besar
- Optimized database queries
- Caching untuk data statistik

## Cara Penggunaan

### 1. Akses Halaman
1. Login sebagai admin
2. Klik menu "Laporan" di sidebar
3. Halaman laporan akan terbuka

### 2. Filter Data
1. Pilih jenis laporan dari dropdown
2. Set tanggal mulai dan akhir
3. Klik tombol "Filter"

### 3. Export Laporan
1. Pilih jenis laporan yang ingin diexport
2. Klik tombol export (PDF/Excel)
3. File akan didownload otomatis

### 4. Interaksi Grafik
1. Klik periode waktu (7/30/90 hari) untuk mengubah grafik
2. Hover pada grafik untuk melihat detail
3. Klik legend untuk show/hide data series

## Konfigurasi

### 1. Chart.js
```html
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
```

### 2. Bootstrap
```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
```

### 3. Font Awesome
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
```

## Customization

### 1. Warna Tema
Edit variabel CSS di `admin-reports.css`:
```css
:root {
    --primary-color: #667eea;
    --success-color: #43e97b;
    --warning-color: #fa709a;
    --danger-color: #f5576c;
}
```

### 2. Periode Default
Ubah periode default di `ReportController.php`:
```php
// Default to last 30 days
return [
    'from' => Carbon::now()->subDays(30)->startOfDay(),
    'to' => Carbon::now()->endOfDay()
];
```

### 3. Jumlah Data
Ubah limit data di controller:
```php
->limit(10) // Ubah angka sesuai kebutuhan
```

## Troubleshooting

### 1. Grafik Tidak Muncul
- Pastikan Chart.js sudah dimuat
- Cek console browser untuk error JavaScript
- Pastikan data tersedia di database

### 2. Data Tidak Tampil
- Cek koneksi database
- Pastikan tabel dan relasi sudah benar
- Cek log Laravel untuk error

### 3. Export Tidak Berfungsi
- Pastikan route export sudah terdaftar
- Cek permission file system
- Pastikan library PDF/Excel sudah terinstall

## Dependencies

### PHP
- Laravel 10+
- Carbon (untuk date handling)
- DB Query Builder

### Frontend
- Bootstrap 5.3.0
- Chart.js 4.x
- Font Awesome 6.4.0

### Database
- MySQL/PostgreSQL
- Relasi antar tabel yang sudah didefinisikan

## Security

### 1. Authentication
- Halaman hanya bisa diakses admin
- Middleware `auth` dan `admin`

### 2. Data Validation
- Validasi input tanggal
- Sanitasi data sebelum query

### 3. SQL Injection Protection
- Menggunakan Query Builder Laravel
- Parameter binding untuk query dinamis

## Performance Tips

### 1. Database Optimization
- Index pada kolom yang sering diquery
- Pagination untuk data besar
- Caching untuk data statistik

### 2. Frontend Optimization
- Lazy loading untuk chart
- Debounce untuk filter input
- Minify CSS dan JavaScript

### 3. Caching Strategy
- Cache hasil query statistik
- Cache chart data
- Cache export files

## Future Enhancements

### 1. Real-time Updates
- WebSocket untuk data real-time
- Auto-refresh setiap interval tertentu

### 2. Advanced Analytics
- Machine learning untuk prediksi
- Trend analysis
- Customer segmentation

### 3. Custom Reports
- Builder untuk custom report
- Scheduled reports
- Email notifications

## Support

Untuk bantuan teknis atau pertanyaan, silakan hubungi tim development atau buat issue di repository project. 
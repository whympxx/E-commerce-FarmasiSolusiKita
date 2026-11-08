# Halaman Pengaturan Admin Panel

## Deskripsi
Halaman pengaturan admin panel adalah fitur yang memungkinkan administrator untuk mengelola konfigurasi sistem e-commerce Farmasi Solusi Kita. Halaman ini dirancang dengan antarmuka yang profesional dan responsif menggunakan Bootstrap 5.

## Fitur Utama

### 1. Dashboard Statistik
- **Total Pengguna**: Menampilkan jumlah pengguna terdaftar
- **Total Produk**: Menampilkan jumlah produk aktif
- **Total Pesanan**: Menampilkan jumlah pesanan semua waktu
- **Total Pendapatan**: Menampilkan pendapatan lifetime

### 2. Pengaturan Berdasarkan Kategori

#### A. Pengaturan Umum
- **Nama Website**: Nama toko/website
- **Mata Uang**: Pilihan mata uang (IDR, USD, EUR)
- **Deskripsi Website**: Deskripsi singkat website

#### B. Pengaturan Kontak
- **Email Kontak**: Email utama untuk kontak
- **Nomor Telepon**: Nomor telepon kontak
- **Alamat**: Alamat lengkap toko

#### C. Pengaturan Pembayaran
- **Persentase Pajak**: Pajak dalam persentase (0-100%)
- **Biaya Pengiriman Default**: Biaya pengiriman standar dalam IDR

#### D. Pengaturan Sistem
- **Mode Maintenance**: Toggle untuk mode maintenance
- **Notifikasi Email**: Toggle untuk notifikasi email otomatis
- **Backup Otomatis**: Toggle untuk backup database otomatis
- **Timeout Session**: Waktu timeout session dalam menit (5-480)

#### E. Pengaturan Sosial Media
- **URL Facebook**: Link Facebook toko
- **URL Instagram**: Link Instagram toko
- **URL Twitter**: Link Twitter toko

### 3. Informasi Sistem
- **Informasi Sistem**: PHP version, Laravel version, memory usage
- **Penggunaan Disk**: Visualisasi penggunaan storage dengan progress bar

### 4. Fitur Tambahan
- **Backup Database**: Membuat backup database secara manual
- **Bersihkan Cache**: Membersihkan cache sistem
- **Reset Form**: Mengembalikan semua pengaturan ke nilai default

## Teknologi yang Digunakan

### Backend
- **Laravel 10**: Framework PHP
- **MySQL**: Database
- **Eloquent ORM**: Database abstraction layer

### Frontend
- **Bootstrap 5**: Framework CSS
- **Font Awesome 6**: Icon library
- **Vanilla JavaScript**: Interaktivitas client-side

### File Struktur
```
app/Http/Controllers/Admin/SettingController.php
database/migrations/2025_01_15_000000_create_settings_table.php
database/seeders/SettingsSeeder.php
resources/views/admin/settings/index.blade.php
public/css/admin-settings.css
public/js/admin-settings.js
routes/web.php (admin routes)
```

## Database Schema

### Tabel Settings
```sql
CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(255) UNIQUE NOT NULL,
    value TEXT NULL,
    type VARCHAR(255) DEFAULT 'string',
    `group` VARCHAR(255) DEFAULT 'general',
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Tipe Data Settings
- **string**: Teks biasa
- **boolean**: Toggle on/off
- **integer**: Angka bulat
- **json**: Data JSON

### Grup Settings
- **general**: Pengaturan umum
- **contact**: Informasi kontak
- **payment**: Pengaturan pembayaran
- **system**: Konfigurasi sistem
- **social**: Media sosial

## API Endpoints

### GET /admin/settings
Menampilkan halaman pengaturan

### POST /admin/settings/update
Memperbarui pengaturan
- **Content-Type**: application/json atau multipart/form-data
- **Response**: JSON dengan status success/error

### POST /admin/settings/backup
Membuat backup database
- **Response**: JSON dengan status dan nama file backup

### POST /admin/settings/clear-cache
Membersihkan cache sistem
- **Response**: JSON dengan status operasi

## Fitur JavaScript

### 1. Auto-save
- Menyimpan perubahan secara otomatis setelah 2 detik
- Indikator visual saat menyimpan
- Backup data ke session storage

### 2. Validasi Real-time
- Validasi email format
- Validasi angka (min/max)
- Validasi field required
- Feedback visual untuk error

### 3. Tab Management
- Penyimpanan data per tab
- Restore data saat pindah tab
- Indikator tab aktif

### 4. Notifikasi
- Toast notification untuk feedback
- Auto-dismiss setelah 5 detik
- Tipe: success, error, warning

## Responsive Design

### Desktop (≥992px)
- Sidebar tetap
- Layout 2 kolom untuk form
- Hover effects lengkap

### Tablet (768px - 991px)
- Sidebar collapsible
- Layout 1 kolom untuk form
- Icon tersembunyi di tab

### Mobile (<768px)
- Sidebar overlay
- Form full-width
- Button stack vertical

## Accessibility Features

### Keyboard Navigation
- Tab navigation
- Enter untuk submit
- Escape untuk close modal

### Screen Reader Support
- ARIA labels
- Semantic HTML
- Focus indicators

### High Contrast Mode
- Support untuk preferensi kontras tinggi
- Border tebal untuk elemen penting

## Security Features

### CSRF Protection
- Token CSRF di setiap form
- Validasi server-side

### Input Validation
- Sanitasi input
- Validasi tipe data
- Escape output

### Authorization
- Middleware admin
- Role-based access control

## Performance Optimization

### Caching
- Cache settings di database
- Auto-clear cache saat update
- Session storage untuk form data

### Lazy Loading
- JavaScript loaded on demand
- CSS minified
- Image optimization

## Troubleshooting

### Common Issues

1. **Settings tidak tersimpan**
   - Periksa permission database
   - Pastikan CSRF token valid
   - Cek log error Laravel

2. **Backup gagal**
   - Periksa permission folder storage
   - Pastikan disk space cukup
   - Cek konfigurasi database

3. **Cache tidak ter-clear**
   - Restart web server
   - Clear cache manual via artisan
   - Periksa konfigurasi cache

### Debug Mode
```php
// Di .env
APP_DEBUG=true
LOG_LEVEL=debug
```

## Maintenance

### Backup Regular
- Backup otomatis setiap hari
- Backup manual via admin panel
- Simpan di folder storage/backups

### Cache Management
- Clear cache secara berkala
- Monitor memory usage
- Optimize query database

### Update Settings
- Backup sebelum update
- Test di environment staging
- Rollback plan tersedia

## Future Enhancements

### Planned Features
- Import/Export settings
- Version control settings
- Audit log perubahan
- Multi-language support
- Advanced backup options

### Performance Improvements
- Redis caching
- Database optimization
- CDN integration
- Progressive Web App

## Support

Untuk bantuan teknis atau pertanyaan, silakan hubungi:
- Email: admin@farmasisolusi.com
- Dokumentasi: [Link ke dokumentasi lengkap]
- Issue Tracker: [Link ke repository]

---

**Versi**: 1.0.0  
**Terakhir Update**: 15 Januari 2025  
**Dibuat oleh**: Tim Development Farmasi Solusi Kita 
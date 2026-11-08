# Panduan Penggunaan Customer Management

## Cara Mengakses

1. Login ke admin panel dengan akun admin
2. Klik menu "Pelanggan" di sidebar kiri
3. Atau klik tombol "Kelola Pelanggan" di dashboard

## Halaman Daftar Pelanggan

### Statistik Dashboard
- **Total Pelanggan**: Jumlah keseluruhan pelanggan terdaftar
- **Terverifikasi**: Jumlah pelanggan dengan email terverifikasi
- **Pelanggan Aktif**: Jumlah pelanggan yang pernah berbelanja
- **Bulan Ini**: Jumlah pelanggan baru di bulan berjalan

### Fitur Pencarian dan Filter
1. **Pencarian**: Ketik nama atau email pelanggan di kotak pencarian
2. **Filter Status**: Pilih filter berdasarkan status pelanggan
   - Semua Status
   - Terverifikasi
   - Belum Verifikasi
   - Aktif
   - Tidak Aktif
3. **Pengurutan**: Pilih cara pengurutan data
   - Urutkan (default: tanggal registrasi)
   - Nama
   - Email
   - Total Pesanan
   - Total Belanja

### Tabel Pelanggan
Kolom yang ditampilkan:
- **Pelanggan**: Nama dan email pelanggan
- **Status**: Status verifikasi dan aktivitas
- **Pesanan**: Jumlah pesanan yang dilakukan
- **Total Belanja**: Total uang yang dibelanjakan
- **Registrasi**: Tanggal dan waktu registrasi
- **Aksi**: Tombol untuk melihat, edit, atau hapus

### Tombol Aksi
- **👁️ Lihat**: Melihat detail pelanggan
- **✏️ Edit**: Mengubah data pelanggan
- **🗑️ Hapus**: Menghapus pelanggan (dengan konfirmasi)

## Halaman Detail Pelanggan

### Informasi Pelanggan
- Foto profil (default icon)
- Nama lengkap
- Email
- Status verifikasi dan aktivitas
- ID pelanggan
- Tanggal registrasi
- Status email verifikasi
- Terakhir login

### Statistik Personal
- **Total Pesanan**: Jumlah pesanan yang dilakukan
- **Total Belanja**: Total uang yang dibelanjakan
- **Rata-rata Pesanan**: Rata-rata nilai per pesanan
- **Item di Keranjang**: Jumlah item yang ada di keranjang

### Riwayat Pesanan
Tabel yang menampilkan:
- Order ID
- Tanggal pesanan
- Status pesanan
- Total pembayaran
- Tombol detail pesanan

### Item di Keranjang
Tabel yang menampilkan:
- Nama produk
- Harga per unit
- Jumlah
- Total harga

## Menambah Pelanggan Baru

### Langkah-langkah:
1. Klik tombol "Tambah Pelanggan" di halaman daftar
2. Isi form dengan data lengkap:
   - **Nama Lengkap**: Wajib diisi
   - **Email**: Wajib diisi, format email valid
   - **Password**: Minimal 8 karakter
   - **Konfirmasi Password**: Harus sama dengan password
   - **Email Terverifikasi**: Centang jika email sudah terverifikasi
3. Klik "Simpan Pelanggan"

### Validasi Form:
- Nama tidak boleh kosong
- Email harus valid dan unik
- Password minimal 8 karakter
- Konfirmasi password harus sama

## Mengedit Data Pelanggan

### Langkah-langkah:
1. Klik tombol "Edit" di halaman detail atau daftar pelanggan
2. Ubah data yang diperlukan:
   - **Nama Lengkap**: Bisa diubah
   - **Email**: Bisa diubah (harus unik)
   - **Password Baru**: Opsional (kosongkan jika tidak ingin mengubah)
   - **Konfirmasi Password**: Wajib jika mengubah password
   - **Email Terverifikasi**: Bisa diubah statusnya
3. Klik "Update Pelanggan"

### Catatan:
- Password lama tidak ditampilkan untuk keamanan
- Jika tidak mengubah password, biarkan kosong
- Perubahan akan mempengaruhi data login pelanggan

## Menghapus Pelanggan

### Langkah-langkah:
1. Klik tombol "Hapus" di halaman detail atau daftar pelanggan
2. Konfirmasi penghapusan di dialog yang muncul
3. Klik "OK" untuk melanjutkan

### Pembatasan:
- Pelanggan dengan pesanan tidak bisa dihapus
- Hanya admin yang bisa menghapus pelanggan
- Tindakan ini tidak dapat dibatalkan

## Export Data Pelanggan

### Langkah-langkah:
1. Klik tombol "Export" di halaman daftar pelanggan
2. File CSV akan otomatis terdownload
3. File berisi data lengkap pelanggan

### Data yang Diexport:
- ID Pelanggan
- Nama
- Email
- Status Verifikasi
- Total Pesanan
- Total Belanja
- Tanggal Registrasi
- Pesanan Terakhir

## Fitur Keamanan

### Validasi Input:
- Semua input divalidasi di sisi server
- Email harus format valid dan unik
- Password minimal 8 karakter
- Konfirmasi password wajib

### Authorization:
- Hanya admin yang bisa akses
- Validasi session untuk setiap request
- CSRF protection untuk semua form

### Data Protection:
- Password di-hash menggunakan bcrypt
- Data sensitif tidak ditampilkan
- Log aktivitas admin

## Troubleshooting

### Masalah Umum:

#### 1. Tidak bisa menghapus pelanggan
**Penyebab**: Pelanggan memiliki pesanan
**Solusi**: Hapus pesanan terlebih dahulu atau arsipkan pelanggan

#### 2. Email sudah terdaftar
**Penyebab**: Email sudah digunakan pelanggan lain
**Solusi**: Gunakan email yang berbeda

#### 3. Password tidak cocok
**Penyebab**: Konfirmasi password tidak sama
**Solusi**: Pastikan kedua field password sama

#### 4. Halaman tidak responsive
**Penyebab**: Browser lama atau cache
**Solusi**: Refresh halaman atau clear cache

#### 5. Export tidak berfungsi
**Penyebab**: Browser memblokir download
**Solusi**: Izinkan popup untuk domain ini

### Error Messages:

#### "Pelanggan tidak ditemukan"
- Pastikan ID pelanggan valid
- Refresh halaman dan coba lagi

#### "Tidak dapat menghapus pelanggan yang memiliki pesanan"
- Pelanggan memiliki riwayat pesanan
- Hapus pesanan terlebih dahulu

#### "Email sudah terdaftar"
- Email sudah digunakan
- Gunakan email yang berbeda

#### "Password minimal 8 karakter"
- Password terlalu pendek
- Gunakan password yang lebih panjang

## Tips Penggunaan

### 1. Pencarian Efektif:
- Gunakan nama lengkap untuk hasil yang lebih akurat
- Email biasanya memberikan hasil yang tepat
- Gunakan filter untuk menyempitkan hasil

### 2. Manajemen Data:
- Verifikasi email pelanggan untuk keamanan
- Update password secara berkala
- Monitor pelanggan yang tidak aktif

### 3. Export Data:
- Export secara berkala untuk backup
- Gunakan data untuk analisis
- Simpan file di lokasi yang aman

### 4. Keamanan:
- Jangan bagikan password admin
- Logout setelah selesai menggunakan
- Monitor aktivitas mencurigakan

## Keyboard Shortcuts

### Navigasi:
- `Tab`: Navigasi antar elemen
- `Enter`: Submit form
- `Escape`: Tutup dialog/modal
- `Ctrl + F`: Pencarian di browser

### Tabel:
- `Arrow Keys`: Navigasi antar baris
- `Space`: Pilih checkbox
- `Enter`: Buka detail

## Browser Support

### Supported Browsers:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### Minimum Requirements:
- JavaScript enabled
- Cookies enabled
- CSS3 support
- HTML5 support

## Performance Tips

### 1. Untuk Data Besar:
- Gunakan filter untuk mengurangi data
- Export data dalam batch kecil
- Refresh halaman secara berkala

### 2. Untuk Kecepatan:
- Gunakan koneksi internet stabil
- Tutup tab browser yang tidak perlu
- Clear cache browser secara berkala

### 3. Untuk Mobile:
- Gunakan browser terbaru
- Pastikan layar cukup besar
- Gunakan mode landscape untuk tabel

## Support

### Jika mengalami masalah:
1. Cek troubleshooting di atas
2. Refresh halaman dan coba lagi
3. Clear cache browser
4. Hubungi administrator sistem

### Informasi Kontak:
- Email: admin@farmasi.com
- Phone: +62-xxx-xxx-xxxx
- Hours: Senin-Jumat 09:00-17:00 WIB

---

**Versi**: 1.0.0  
**Terakhir Update**: December 2024  
**Dibuat oleh**: Development Team 
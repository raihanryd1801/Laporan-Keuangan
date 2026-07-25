Sistem Laporan Keuangan Retail - SKYKOM FINANCE RETAIL
Aplikasi berbasis Laravel untuk mencatat dan melaporkan transaksi keuangan retail. Cocok untuk usaha yang memiliki banyak area/wilayah pemasangan dan teknisi lapangan.

## 📸 Tampilan Aplikasi

| Halaman | Screenshot |
|---------|------------|
| Login | ![Login](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/login.png) |
| Keuangan Utama | ![Keuangan Utama](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/keuanganut.png) |
| Statistik | ![Statistik](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/statistic.png) |
| Laporan Pemasukan | ![Pemasukan](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/pemasukan.png) |
| Laporan Pengeluaran | ![Pengeluaran](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/pengeluaran.png) |
| Laporan Per Area | ![Per Area](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/laparea.png) |
| Kasbon Teknisi | ![Kasbon](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/kasbontek.png) |
| Master Area | ![Master Area](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/masarea.png) |
| Master Kategori | ![Master Kategori](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/masket.png) |
| Data Teknisi | ![Data Teknisi](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/datek.png) |
| History Log | ![History Log](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/historylog.png) |
| Firewall & Sesi | ![Firewall](https://raw.githubusercontent.com/raihanryd1801/Laporan-Keuangan/main/public/images/screenshots/firewall.png) |



✨ Fitur Utama
Input Transaksi – Catat pemasukan (cash/transfer), pengeluaran operasional, dan kasbon teknisi. Bisa pilih area, kategori, dan metode pembayaran.

Laporan Keuangan Utama – Tampilkan saldo awal, pemasukan berjalan, pengeluaran, dan saldo akhir dalam satu tabel rekap.

Laporan Spesifik – Pemasangan baru, pemasukan per metode (cash/transfer), pengeluaran operasional, kasbon teknisi, dan laporan per area.

Filter Tanggal – Semua laporan bisa difilter dengan rentang tanggal.

Statistik Grafik – Tampilkan grafik bar pemasukan vs pengeluaran per bulan/hari, serta diagram donat distribusi per kategori.

Master Data – Kelola area retail, kategori transaksi, dan daftar teknisi/pegawai.

Ekspor Excel & PDF – Dari halaman Keuangan Utama, cetak laporan ke Excel atau PDF dengan kop surat dan tanda tangan.

Aktivitas Log – Setiap tambah, edit, atau hapus transaksi tercatat dengan detail.

Firewall Sederhana – Whitelist IP yang diizinkan akses, dan lihat sesi aktif untuk ditendang (kill session).

🛠️ Instalasi
Clone atau salin project ke server lokal.

bash
git clone https://github.com/raihanryd1801/skykom-finance.git
cd skykom-finance
Install dependency PHP.

bash
composer install
Buat file .env dari contoh.

bash
cp .env.example .env
Generate key aplikasi.

bash
php artisan key:generate
Sesuaikan konfigurasi database di .env

env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skykom_finance
DB_USERNAME=root
DB_PASSWORD=
Jalankan migrasi dan seeder (untuk data awal).

bash
php artisan migrate --seed
Jalankan server development.

bash
php artisan serve
Akses di http://localhost:8000.

Login default (dari seeder):

Email: admin@example.com

Password: password

📂 Struktur Folder Penting
text
app/
├── Http/
│   └── Controllers/
│       └── LaporanController.php   # controller utama
resources/
└── views/
    └── laporan/                    # semua file blade
        ├── keuangan.blade.php
        ├── statistic.blade.php
        ├── pemasukan.blade.php
        ├── pengeluaran.blade.php
        ├── area.blade.php
        ├── kasbon.blade.php
        ├── master_area.blade.php
        ├── master_kategori.blade.php
        ├── teknisi.blade.php
        ├── activity_log.blade.php
        ├── firewall.blade.php
        └── ...
public/
└── screenshots/                    # gambar untuk README
🧩 Cara Penggunaan Singkat
Login dengan akun yang sudah terdaftar.

Dari dashboard, pilih Menu Input untuk mencatat transaksi baru.

Isi tanggal, jenis (debet/kredit), kategori, area (opsional), metode, dan nominal.

Untuk kasbon, pilih nama teknisi.

Lihat laporan di menu Keuangan Utama untuk rekap keseluruhan.

Gunakan filter tanggal di setiap halaman laporan.

Kelola data referensi di menu Master Data (area, kategori, teknisi).

Pantau aktivitas melalui Log Aktivitas.

Untuk keamanan, atur IP yang diizinkan di Firewall & Sesi.

📦 Dependensi
Laravel 10.x

MySQL / MariaDB

DomPDF (untuk ekspor PDF)

Maatwebsite Excel (untuk ekspor Excel)

Chart.js (untuk grafik)

⚙️ Catatan Teknis
Pastikan ekstensi PHP gd, zip, dan mbstring terinstal.

Jika ekspor PDF error, jalankan:

bash
composer require barryvdh/laravel-dompdf
Untuk production, atur permission folder storage dan bootstrap/cache.

📝 Lisensi
Hak cipta milik PT. Fans Media Jember. Untuk penggunaan internal.

Selamat menggunakan! Jika ada kendala, cek log di storage/logs/laravel.log atau hubungi admin sistem.
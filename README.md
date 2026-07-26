## Sistem Laporan Keuangan Retail - SKYKOM FINANCE RETAIL

Aplikasi berbasis Laravel untuk mencatat dan melaporkan transaksi keuangan retail. Cocok untuk usaha yang memiliki banyak area/wilayah pemasangan dan teknisi lapangan.

## Tampilan Aplikasi

| Halaman | Dokumentasi |
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



# Skykom Finance

Aplikasi manajemen keuangan retail berbasis Laravel untuk mencatat pemasukan, pengeluaran, kasbon teknisi, serta menghasilkan laporan keuangan lengkap dengan grafik statistik dan fitur ekspor.

---

## Fitur

- **Input Transaksi**
  - Mencatat pemasukan (Cash/Transfer)
  - Mencatat pengeluaran operasional
  - Mencatat kasbon teknisi
  - Mendukung area, kategori, dan metode pembayaran

- **Laporan Keuangan**
  - Rekap saldo awal
  - Total pemasukan
  - Total pengeluaran
  - Saldo akhir

- **Laporan Detail**
  - Pemasangan Baru
  - Pemasukan Cash
  - Pemasukan Transfer
  - Pengeluaran Operasional
  - Kasbon Teknisi
  - Laporan per Area

- **Filter Tanggal**
  - Seluruh laporan dapat difilter berdasarkan rentang tanggal.

- **Statistik**
  - Grafik pemasukan dan pengeluaran
  - Diagram distribusi transaksi berdasarkan kategori

- **Master Data**
  - Area Retail
  - Kategori Transaksi
  - Data Teknisi

- **Ekspor Laporan**
  - Microsoft Excel
  - PDF lengkap dengan kop surat dan tanda tangan

- **Activity Log**
  - Mencatat aktivitas tambah, ubah, dan hapus data.

- **Firewall & Session**
  - Whitelist IP
  - Monitoring sesi aktif
  - Kill Session

---

# Instalasi

## 1. Clone Repository

```bash
git clone https://github.com/raihanryd1801/Laporan-Keuangan.git
cd skykom-finance
```

## 2. Install Dependency

```bash
composer install
```

## 3. Copy File Environment

```bash
cp .env.example .env
```

## 4. Generate Application Key

```bash
php artisan key:generate
```

## 5. Konfigurasi Database

Sesuaikan file `.env`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skykom_finance
DB_USERNAME=root
DB_PASSWORD=
```

## 6. Jalankan Migration dan Seeder

```bash
php artisan migrate --seed
```

## 7. Jalankan Aplikasi

```bash
php artisan serve
```

Aplikasi dapat diakses melalui:
```bash
php /var/www/html/finance/artisan serve --host=0.0.0.0 --port=8005
```

```
http://localhost:8000
```

---

# Login Menggunakan PHP Thinker
```bash
php artisan tinker --execute="\App\Models\User::create(['name' => 'Admin Fans Media', 'email' => 'admin@test.co.id', 'password' => bcrypt('password123'), 'jabatan' => 'Admin']);"
```
```
Email    : admin@test.co.id
Password : password123
```

---

# Struktur Folder

```text
app/
├── Http/
│   └── Controllers/
│       └── LaporanController.php

resources/
└── views/
    └── laporan/
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
└── screenshots/
```

---

# Cara Penggunaan

1. Login menggunakan akun yang tersedia.
2. Tambahkan transaksi melalui menu **Input Transaksi**.
3. Pilih jenis transaksi, kategori, area, metode pembayaran, dan nominal.
4. Untuk kasbon, pilih teknisi yang bersangkutan.
5. Lihat rekap pada menu **Keuangan Utama**.
6. Gunakan filter tanggal untuk melihat laporan tertentu.
7. Kelola data Area, Kategori, dan Teknisi melalui menu **Master Data**.
8. Pantau seluruh aktivitas melalui **Activity Log**.
9. Atur keamanan akses melalui menu **Firewall & Session**.

---

# Dependensi

- Laravel 10.x
- PHP 8.x
- MySQL / MariaDB
- Laravel DomPDF
- Laravel Excel (Maatwebsite)
- Chart.js

---

# Catatan

Pastikan ekstensi PHP berikut telah aktif:

- gd
- mbstring
- zip

Jika fitur ekspor PDF belum tersedia, jalankan:

```bash
composer require barryvdh/laravel-dompdf
```

Untuk deployment production, pastikan permission folder berikut telah sesuai:

```
storage/
bootstrap/cache/
```

---

# Lisensi

Hak cipta © PT. Fans Media Jember.

Aplikasi ini ditujukan untuk penggunaan internal perusahaan.

---

## Bantuan

Apabila mengalami kendala, periksa log aplikasi pada:

```
storage/logs/laravel.log
```

atau hubungi administrator sistem.
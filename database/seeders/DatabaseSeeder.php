<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\MetodePembayaran;
use App\Models\Transaksi;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Data Kategori (Data Master)
        $katPasang = Kategori::create(['nama_kategori' => 'Pemasangan Baru', 'tipe' => 'income']);
        $katPemasukan = Kategori::create(['nama_kategori' => 'Pemasukan Lain', 'tipe' => 'income']);
        $katPengeluaran = Kategori::create(['nama_kategori' => 'Pengeluaran Operasional', 'tipe' => 'expense']);
        $katKasbon = Kategori::create(['nama_kategori' => 'Kasbon', 'tipe' => 'expense']);

        // 2. Buat Data Metode Pembayaran
        $cash = MetodePembayaran::create(['nama_metode' => 'Cash', 'saldo' => 0]);
        $transfer = MetodePembayaran::create(['nama_metode' => 'Transfer Bank', 'saldo' => 0]);

        // 3. Buat Beberapa Transaksi Dummy (Biar laporannya gak kosong)
        Transaksi::create([
            'tanggal' => Carbon::now()->subDays(2)->format('Y-m-d'), // 2 hari lalu
            'keterangan' => 'Pemasangan Baru a.n Budi - Retail',
            'kategori_id' => $katPasang->id,
            'metode_pembayaran_id' => $cash->id,
            'debet' => 1500000,
            'kredit' => 0
        ]);

        Transaksi::create([
            'tanggal' => Carbon::now()->subDays(1)->format('Y-m-d'), // 1 hari lalu
            'keterangan' => 'Beli Kabel LAN & Konektor RJ45',
            'kategori_id' => $katPengeluaran->id,
            'metode_pembayaran_id' => $cash->id,
            'debet' => 0,
            'kredit' => 350000
        ]);

        Transaksi::create([
            'tanggal' => Carbon::now()->format('Y-m-d'), // Hari ini
            'keterangan' => 'Iuran Bulanan a.n Siti',
            'kategori_id' => $katPemasukan->id,
            'metode_pembayaran_id' => $transfer->id,
            'debet' => 200000,
            'kredit' => 0
        ]);
    }
}
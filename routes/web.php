<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;

// Route Login
Route::get('/login', [LaporanController::class, 'showLogin'])->name('login');
Route::post('/login', [LaporanController::class, 'processLogin']);
Route::post('/logout', [LaporanController::class, 'logout'])->name('logout');

// Route yang membutuhkan Login (Middleware Auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/laporan/keuangan', [LaporanController::class, 'laporanKeuangan']);
    Route::get('/laporan/pemasangan-baru', [LaporanController::class, 'pemasanganBaru']);
    Route::get('/laporan/pemasukan', [LaporanController::class, 'pemasukan']);
    Route::get('/laporan/pengeluaran', [LaporanController::class, 'pengeluaran']);
    Route::get('/laporan/kasbon', [LaporanController::class, 'kasbon']);

    // Route Menu Input & Transaksi (Tambahkan /simpan di sini)
    Route::get('/laporan/menu-input', [LaporanController::class, 'menuInput']);
    Route::get('/laporan/transaksi/tambah', [LaporanController::class, 'create']);
    Route::post('/laporan/transaksi/store', [LaporanController::class, 'store']);
    Route::post('/laporan/transaksi/simpan', [LaporanController::class, 'store']); // <-- Tambahkan baris ini
    Route::get('/laporan/transaksi/edit/{id}', [LaporanController::class, 'editTransaksi']);
    Route::match(['post', 'put'], '/laporan/transaksi/update/{id}', [LaporanController::class, 'updateTransaksi']);
    Route::get('/laporan/transaksi/hapus/{id}', [LaporanController::class, 'destroyTransaksi']);
    Route::get('/laporan/transaksi/create', [LaporanController::class, 'create']);

    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel']);
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf']);

    Route::get('/laporan/teknisi', [LaporanController::class, 'indexTeknisi']);
    Route::post('/laporan/teknisi/store', [LaporanController::class, 'storeTeknisi']);
    Route::get('/laporan/teknisi/hapus/{id}', [LaporanController::class, 'destroyTeknisi']); // Pastikan menggunakan Route::get
    Route::post('/laporan/teknisi/simpan', [LaporanController::class, 'storeTeknisi']);
    Route::get('/laporan/teknisi/edit/{id}', [LaporanController::class, 'editArea']);
    Route::match(['get', 'post'], '/laporan/teknisi/update/{id}', [LaporanController::class, 'updateArea']);
    Route::get('/laporan/teknisi/hapus/{id}', [LaporanController::class, 'destroyArea']);

    Route::get('/laporan/area', [LaporanController::class, 'laporanArea']);
    Route::get('/laporan/master-area', [LaporanController::class, 'indexArea']);
    Route::post('/laporan/master-area/store', [LaporanController::class, 'storeArea']);
    Route::post('/laporan/master-area/simpan', [LaporanController::class, 'storeArea']);
    Route::get('/laporan/master-area/edit/{id}', [LaporanController::class, 'editArea']);
    Route::match(['get', 'post'], '/laporan/master-area/update/{id}', [LaporanController::class, 'updateArea']);
    Route::get('/laporan/master-area/hapus/{id}', [LaporanController::class, 'destroyArea']);

    Route::get('/laporan/master-kategori', [LaporanController::class, 'indexKategori']);
    Route::post('/laporan/master-kategori/store', [LaporanController::class, 'storeKategori']);
    Route::post('/laporan/master-kategori/simpan', [LaporanController::class, 'storeKategori']); // <-- Tambahkan baris ini
    Route::get('/laporan/activity-log', [LaporanController::class, 'indexLog']);
    Route::get('/laporan/master-kategori/edit/{id}', [LaporanController::class, 'editArea']);
    Route::match(['get', 'post'], '/laporan/master-kategori/update/{id}', [LaporanController::class, 'updateArea']);
    Route::get('/laporan/master-kategori/hapus/{id}', [LaporanController::class, 'destroyArea']);
});

// Redirect default ke laporan keuangan
Route::get('/', function () {
    return redirect('/laporan/keuangan');
});
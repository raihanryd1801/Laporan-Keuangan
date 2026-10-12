<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Index baca untuk filter tanggal yang dipakai hampir semua laporan.
     * Tidak mengubah data, hanya mempercepat WHERE tanggal / tanggal+metode.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->index('tanggal', 'transaksis_tanggal_index');
            $table->index(['tanggal', 'metode_pembayaran_id'], 'transaksis_tanggal_metode_index');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropIndex('transaksis_tanggal_metode_index');
            $table->dropIndex('transaksis_tanggal_index');
        });
    }
};

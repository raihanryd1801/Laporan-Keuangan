<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
        
            // Foreign Key ke tabel Kategoris
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('restrict');
        
            // Foreign Key ke tabel Metode Pembayarans
            $table->foreignId('metode_pembayaran_id')->constrained('metode_pembayarans')->onDelete('restrict');
            Schema::table('transaksis', function (Blueprint $table) {
        $table->foreignId('area_id')->nullable()->after('metode_pembayaran_id')->constrained('areas')->onDelete('set null');
        });
            // Relasi ke User (bisa pencatat atau teknisi yang kasbon)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Nominal Transaksi
            $table->decimal('debet', 15, 2)->default(0);
            $table->decimal('kredit', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
        Schema::table('transaksis', function (Blueprint $table) {
        $table->dropForeign(['area_id']);
        $table->dropColumn('area_id');
        });
    }
    
};

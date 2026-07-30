@extends('layouts.app')
@section('title', 'Menu Input Transaksi')

@section('content')
    <div class="header">
        <h2>Pilih Menu Input Transaksi</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">

        <!-- KARTU 1: PEMASUKAN -->
        <div
            style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 5px solid #2ecc71; text-align: center;">
            <h3 style="color: #2c3e50; margin-bottom: 10px;">Pemasukan / Retail</h3>
            <p style="color: #7f8c8d; font-size: 13px; margin-bottom: 20px;">Catat pembayaran atau pemasangan baru lengkap
                dengan pilihan area.</p>
            <a href="{{ url('/laporan/transaksi/create?jenis=debet') }}"
                style="display: inline-block; background: #2ecc71; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Input
                Pemasukan</a>
        </div>

        <!-- KARTU 2: PENGELUARAN -->
        <div
            style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 5px solid #e74c3c; text-align: center;">
            <h3 style="color: #2c3e50; margin-bottom: 10px;">Pengeluaran Operasional</h3>
            <p style="color: #7f8c8d; font-size: 13px; margin-bottom: 20px;">Catat biaya operasional, pembelian alat, atau
                pengeluaran lain.</p>
            <a href="{{ url('/laporan/transaksi/create?jenis=kredit') }}"
                style="display: inline-block; background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Input
                Pengeluaran</a>
        </div>

        <!-- KARTU 3: KASBON -->
        <div
            style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 5px solid #f39c12; text-align: center;">
            <h3 style="color: #2c3e50; margin-bottom: 10px;">Kasbon Teknisi</h3>
            <p style="color: #7f8c8d; font-size: 13px; margin-bottom: 20px;">Catat pinjaman atau kasbon harian untuk teknisi
                lapangan.</p>
            <a href="{{ url('/laporan/transaksi/create?jenis=kredit') }}"
                style="display: inline-block; background: #f39c12; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Input
                Kasbon</a>
        </div>

        <!-- KARTU 4: SETOR TUNAI / MUTASI BANK (BARU) -->
        <div
            style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 5px solid #9b59b6; text-align: center;">
            <h3 style="color: #2c3e50; margin-bottom: 10px;">Setor Tunai (Ke Bank)</h3>
            <p style="color: #7f8c8d; font-size: 13px; margin-bottom: 20px;">Catat penyetoran uang fisik (cash retail) ke
                rekening bank PT.</p>

            <!-- Jika form-nya digabung di file create.blade.php yang sama, abang bisa pakai parameter ?jenis=mutasi -->
            <a href="{{ url('/laporan/transaksi/create?jenis=mutasi') }}"
                style="display: inline-block; background: #9b59b6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Input
                Setor Bank</a>
        </div>

    </div>
@endsection
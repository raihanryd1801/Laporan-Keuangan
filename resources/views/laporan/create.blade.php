@extends('layouts.app')
@section('title', 'Form Input Transaksi')

@section('content')
    <div class="header">
        <h2>Form Input Transaksi Retail</h2>
        <a href="{{ url('/laporan/menu-input') }}" style="text-decoration: none; color: #7f8c8d;">&larr; Kembali ke Menu
            Input</a>
    </div>

    <form action="{{ url('/laporan/transaksi/simpan') }}" method="POST" style="max-width: 600px; margin: 0 auto;">
        @csrf

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Tanggal Transaksi</label>
            <input type="date" name="tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Jenis Transaksi:</label>
            <select name="jenis_transaksi" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"
                required>
                <option value="debet" {{ (isset($defaultJenis) && $defaultJenis == 'debet') ? 'selected' : '' }}>Pemasukan
                    (Debet)</option>
                <option value="kredit" {{ (isset($defaultJenis) && $defaultJenis == 'kredit') ? 'selected' : '' }}>Pengeluaran
                    / Kasbon (Kredit)</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Kategori</label>
            <select name="kategori_id" required
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <!-- PILIHAN AREA (BARU) -->
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Pilih Area / Wilayah (Khusus
                Retail)</label>
            <select name="area_id" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">-- Pilih Area (Opsional / Umum) --</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}">{{ $area->nama_area }}</option>
                @endforeach
            </select>
            <small style="color: #7f8c8d;">*Pilih area jika ini transaksi pemasangan/pemasukan retail wilayah
                tertentu.</small>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Metode Pembayaran</label>
            <select name="metode_pembayaran_id" required
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                @foreach($metodes as $metode)
                    <option value="{{ $metode->id }}">{{ $metode->nama_metode }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Teknisi (Khusus KASBON)</label>
            <select name="user_id" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">-- Bukan Kasbon (Abaikan) --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Keterangan / Deskripsi</label>
            <textarea name="keterangan" rows="3" required placeholder="Contoh: Pembayaran internet bulanan a.n Budi"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nominal (Rp)</label>
            <input type="number" name="nominal" min="0" required placeholder="Contoh: 150000"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <button type="submit"
            style="width: 100%; padding: 12px; background-color: #2ecc71; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer;">
            Simpan Transaksi
        </button>
    </form>
@endsection
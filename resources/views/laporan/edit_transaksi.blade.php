@extends('layouts.app')
@section('title', 'Edit Transaksi Keuangan')

@section('content')
    <div class="header">
        <h2>Edit Data Transaksi</h2>
    </div>

    <div
        style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); max-width: 700px;">
        <form action="{{ url('/laporan/transaksi/update/' . $transaksi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Tanggal -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Tanggal Transaksi</label>
                <input type="date" name="tanggal" value="{{ $transaksi->tanggal }}" required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <!-- Jenis Transaksi (Debet / Kredit) -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Jenis Transaksi</label>
                <select name="jenis_transaksi" required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="debet" {{ $transaksi->debet > 0 ? 'selected' : '' }}>Pemasukan (Debet)</option>
                    <option value="kredit" {{ $transaksi->kredit > 0 ? 'selected' : '' }}>Pengeluaran / Kasbon (Kredit)
                    </option>
                </select>
            </div>

            <!-- Kategori -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Kategori Transaksi</label>
                <select name="kategori_id" required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ $transaksi->kategori_id == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Metode Pembayaran -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Metode Pembayaran</label>
                <select name="metode_pembayaran_id" required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    @foreach($metodes as $metode)
                        <option value="{{ $metode->id }}" {{ $transaksi->metode_pembayaran_id == $metode->id ? 'selected' : '' }}>
                            {{ $metode->nama_metode }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Area / Lokasi -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Area / Lokasi (Opsional)</label>
                <select name="area_id" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Umum / Pusat --</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ $transaksi->area_id == $area->id ? 'selected' : '' }}>
                            {{ $area->nama_area }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Teknisi / Pegawai -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Teknisi / Pegawai (Opsional /
                    Kasbon)</label>
                <select name="user_id" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Tidak Ada --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $transaksi->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Keterangan -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Keterangan</label>
                <input type="text" name="keterangan" value="{{ $transaksi->keterangan }}" required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <!-- Nominal -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nominal (Rp)</label>
                <input type="number" name="nominal"
                    value="{{ $transaksi->debet > 0 ? $transaksi->debet : $transaksi->kredit }}" required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <!-- Tombol Aksi -->
            <div style="display: flex; gap: 10px;">
                <button type="submit"
                    style="background: #f39c12; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer;">Simpan
                    Perubahan</button>
                <a href="{{ url('/laporan/keuangan') }}"
                    style="background: #95a5a6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block;">Batal</a>
            </div>
        </form>
    </div>
@endsection
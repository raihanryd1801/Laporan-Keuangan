@extends('layouts.app')
@section('title', 'Kelola Master Kategori')

@section('content')
    <div class="header">
        <h2>Daftar Kategori Transaksi</h2>
    </div>

    <!-- Form Tambah Kategori -->
    <form action="{{ url('/laporan/master-kategori/simpan') }}" method="POST"
        style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 20px; display: flex; gap: 10px;">
        @csrf
        <input type="text" name="nama_kategori" required placeholder="Nama Kategori Baru (Cth: Pembelian Alat)"
            style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit"
            style="background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer;">Simpan
            Kategori</button>
    </form>

    <!-- Tabel Daftar Kategori -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Status</th>
                    <th>Aksi</th> <!-- Kolom Aksi ditambahkan di sini -->
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $index => $kat)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $kat->nama_kategori }}</strong></td>
                        <td><span style="color: #27ae60; font-size: 13px;">Aktif</span></td>
                        <td>
                            <!-- Tombol Edit & Hapus ditarik ke dalam baris data -->
                            <a href="{{ url('/laporan/master-kategori/edit/' . $kat->id) }}"
                                style="background: #f39c12; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px;">Edit</a>
                            <a href="{{ url('/laporan/master-kategori/hapus/' . $kat->id) }}"
                                onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                style="background: #e74c3c; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; margin-left: 5px;">Hapus</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">Belum ada data kategori. Silakan tambahkan
                            melalui form di atas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
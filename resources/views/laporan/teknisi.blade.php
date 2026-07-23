@extends('layouts.app')
@section('title', 'Kelola Data Teknisi')

@section('content')
    <div class="header">
        <h2>Daftar Nama Teknisi / Pegawai</h2>
    </div>

    <!-- Form Tambah Teknisi Cepat -->
    <form action="{{ url('/laporan/teknisi/simpan') }}" method="POST"
        style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 20px; display: flex; gap: 10px;">
        @csrf
        <input type="text" name="name" required placeholder="Masukkan Nama Teknisi Baru (Cth: P. Mul)"
            style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit"
            style="background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer;">Tambah
            Teknisi</button>
    </form>

    <!-- Tabel Daftar Teknisi -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Teknisi</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->jabatan ?? 'Teknisi' }}</td>
                        <td>
                            <!-- Dibungkus <td> dan menggunakan variabel $user->id -->
                            <a href="{{ url('/laporan/teknisi/edit/' . $user->id) }}"
                                style="background: #f39c12; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px;">Edit</a>
                            <a href="{{ url('/laporan/teknisi/hapus/' . $user->id) }}"
                                onclick="return confirm('Yakin ingin menghapus teknisi ini?')"
                                style="background: #e74c3c; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; margin-left: 5px;">Hapus</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">Belum ada data teknisi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
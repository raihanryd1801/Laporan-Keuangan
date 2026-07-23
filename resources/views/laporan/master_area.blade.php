@extends('layouts.app')

@section('title', 'Kelola Master Area')

@section('content')

    <div class="header">
        <h2>Daftar Area / Wilayah Retail</h2>
    </div>

    {{-- Form Tambah Area --}}
    <div style="background:#f8f9fa; padding:20px; border-radius:8px; margin-bottom:25px;">

        <form action="{{ url('/laporan/master-area/simpan') }}" method="POST" style="display:flex; gap:10px;">

            @csrf

            <input type="text" name="nama_area" class="form-control" placeholder="Contoh: Sukorambi" required
                style="flex:1; padding:10px; border:1px solid #ccc; border-radius:5px;">

            <button type="submit"
                style="background:#27ae60; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer; font-weight:bold;">
                + Simpan Area
            </button>

        </form>

    </div>

    {{-- Tabel Area --}}
    <div class="table-responsive">

        <table>

            <thead>

                <tr>
                    <th width="70">No</th>
                    <th>Nama Area</th>
                    <th width="180">Aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse($areas as $index => $area)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            <strong>{{ $area->nama_area }}</strong>
                        </td>

                        <td>

                            <a href="{{ url('/laporan/master-area/edit/' . $area->id) }}" style="
                                                        background:#f39c12;
                                                        color:white;
                                                        padding:6px 12px;
                                                        border-radius:4px;
                                                        text-decoration:none;
                                                        font-size:13px;
                                                    ">
                                Edit
                            </a>

                            <a href="{{ url('/laporan/master-area/hapus/' . $area->id) }}"
                                onclick="return confirm('Yakin ingin menghapus area ini?')" style="
                                                        background:#e74c3c;
                                                        color:white;
                                                        padding:6px 12px;
                                                        border-radius:4px;
                                                        text-decoration:none;
                                                        font-size:13px;
                                                        margin-left:5px;
                                                    ">
                                Hapus
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" style="text-align:center; padding:25px; color:#777;">

                            Belum ada data area.<br>
                            Silakan tambahkan area menggunakan form di atas.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

@endsection
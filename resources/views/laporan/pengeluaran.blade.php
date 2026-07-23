@extends('layouts.app')
@section('title', 'Laporan Pengeluaran')

@section('content')
    <div class="header">
        <h2>Laporan Pengeluaran Operasional</h2>

        <!-- Form Filter Tanggal -->
        <form method="GET" action="{{ url('/laporan/pengeluaran') }}" class="filter-form">
            <input type="date" name="tanggal_mulai" value="{{ $mulai }}">
            <input type="date" name="tanggal_selesai" value="{{ $sampai }}">
            <button type="submit">Filter</button>
        </form>
    </div>

    <!-- Tabel Data Pengeluaran -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Lokasi / Area</th>
                    <th>Keterangan</th>
                    <th>Metode</th>
                    <th class="text-right">Nominal (Kredit)</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ optional($row->kategori)->nama_kategori }}</td>
                        <td>{{ optional($row->area)->nama_area ?? 'Pusat' }}</td>

                        {{-- Keterangan --}}
                        <td>{{ $row->keterangan }}</td>

                        {{-- Metode --}}
                        <td>{{ optional($row->metodePembayaran)->nama_metode }}</td>

                        {{-- Nominal --}}
                        <td class="text-right text-danger">
                            Rp {{ number_format($row->kredit, 0, ',', '.') }}
                        </td>

                        {{-- Aksi --}}
                        <td style="text-align:center;">
                            <a href="{{ url('/laporan/transaksi/edit/' . $row->id) }}"
                                style="background:#f39c12;color:#fff;padding:4px 8px;text-decoration:none;border-radius:3px;font-size:11px;">
                                Edit
                            </a>

                            <a href="{{ url('/laporan/transaksi/hapus/' . $row->id) }}"
                                onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                                style="background:#e74c3c;color:#fff;padding:4px 8px;text-decoration:none;border-radius:3px;font-size:11px;margin-left:3px;">
                                Hapus
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:20px;">
                            Belum ada data pengeluaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td colspan="6" class="text-right">TOTAL PENGELUARAN:</td>
                    <td class="text-right text-danger">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
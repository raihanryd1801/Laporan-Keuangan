@extends('layouts.app')
@section('title', 'Laporan Pemasukan Retail')

@section('content')
    <div class="header">
        <h2>Laporan Pemasukan Retail</h2>
    </div>

    <div class="header" style="flex-direction: column; align-items: flex-start; gap: 15px; margin-bottom: 25px;">
        <h2>Filter Pemasukan</h2>

        <form method="GET" action=""
            style="display: flex; gap: 10px; flex-wrap: wrap; background: #f8f9fa; padding: 15px; border-radius: 6px; width: 100%;">
            <div>
                <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 3px;">Dari Tanggal:</label>
                <input type="date" name="tanggal_mulai" value="{{ $mulai }}"
                    style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 3px;">Sampai
                    Tanggal:</label>
                <input type="date" name="tanggal_selesai" value="{{ $sampai }}"
                    style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="submit"
                    style="background: #27ae60; color: white; border: none; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer;">Terapkan
                    Filter</button>
            </div>
        </form>
    </div>

    <!-- Tabel Pemasukan Cash -->
    <h3 style="margin-top: 10px; margin-bottom: 10px; color: #2ecc71; font-size: 16px;">▶ Pemasukan via Cash</h3>
    <div class="table-responsive" style="margin-bottom: 30px;">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th style="width: 100px;">Tanggal</th>
                    <th>Kategori</th>
                    <th>Area</th>
                    <th>Teknisi</th>
                    <th>Keterangan</th>
                    <th class="text-right" style="width: 130px;">Nominal</th>
                    <th style="text-align:center; width:160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemasukanCash as $index => $row)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ optional($row->kategori)->nama_kategori }}</td>
                        <td>{{ optional($row->area)->nama_area ?? 'Pusat' }}</td>
                        <td>{{ optional($row->user)->name ?? '-' }}</td>
                        <td>{{ $row->keterangan }}</td>
                        <td class="text-right text-success">Rp {{ number_format($row->debet, 0, ',', '.') }}</td>
                        <td style="text-align: center;">
                            <a href="{{ url('/laporan/transaksi/edit/' . $row->id) }}"
                                style="background: #f39c12; color: white; padding: 4px 8px; text-decoration: none; border-radius: 3px; font-size: 11px;">Edit</a>
                            <a href="{{ url('/laporan/transaksi/hapus/' . $row->id) }}"
                                onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                                style="background: #e74c3c; color: white; padding: 4px 8px; text-decoration: none; border-radius: 3px; font-size: 11px; margin-left: 3px;">Hapus</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px; color: #7f8c8d;">Belum ada data pemasukan
                            cash.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td colspan="6" class="text-right">TOTAL CASH:</td>
                    <td class="text-right text-success">Rp {{ number_format($totalCash, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Tabel Pemasukan Transfer Bank -->
    <h3 style="margin-top: 10px; margin-bottom: 10px; color: #3498db; font-size: 16px;">▶ Pemasukan via Transfer Bank</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th style="width: 100px;">Tanggal</th>
                    <th>Kategori</th>
                    <th>Area</th>
                    <th>Teknisi</th>
                    <th>Keterangan</th>
                    <th class="text-right" style="width: 130px;">Nominal</th>
                    <th style="text-align: center; width: 160px;">Aksi</th> <!-- Disamakan jadi 160px -->
                </tr>
            </thead>
            <tbody>
                @forelse($pemasukanTransfer as $index => $row)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ optional($row->kategori)->nama_kategori }}</td>
                        <td>{{ optional($row->area)->nama_area ?? 'Pusat' }}</td>
                        <td>{{ optional($row->user)->name ?? '-' }}</td>
                        <td>{{ $row->keterangan }}</td>
                        <td class="text-right text-success">Rp {{ number_format($row->debet, 0, ',', '.') }}</td>
                        <td style="text-align: center;">
                            <a href="{{ url('/laporan/transaksi/edit/' . $row->id) }}"
                                style="background: #f39c12; color: white; padding: 4px 8px; text-decoration: none; border-radius: 3px; font-size: 11px;">Edit</a>
                            <a href="{{ url('/laporan/transaksi/hapus/' . $row->id) }}"
                                onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                                style="background: #e74c3c; color: white; padding: 4px 8px; text-decoration: none; border-radius: 3px; font-size: 11px; margin-left: 3px;">Hapus</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px; color: #7f8c8d;">Belum ada data pemasukan
                            transfer.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td colspan="6" class="text-right">TOTAL TRANSFER:</td>
                    <td class="text-right text-success">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
@extends('layouts.app')
@section('title', 'Laporan Pemasangan Baru')

@section('content')
    <div class="header">
        <h2>Laporan Pemasangan Baru</h2>

        <!-- Form Filter Tanggal -->
        <form method="GET" action="{{ url('/laporan/pemasangan-baru') }}" class="filter-form">
            <input type="date" name="tanggal_mulai" value="{{ $mulai }}">
            <input type="date" name="tanggal_selesai" value="{{ $sampai }}">
            <button type="submit">Filter</button>
        </form>
    </div>

    <!-- Tabel Data Pemasangan Baru -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Lokasi / Area</th>
                    <th>Keterangan</th>
                    <th>Metode</th>
                    <th class="text-right">Nominal</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $sumPasang = 0; @endphp
                @forelse($transaksi as $index => $row)
                    @php $sumPasang += $row->debet > 0 ? $row->debet : $row->kredit; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ optional($row->area)->nama_area ?? 'Pusat' }}</td>
                        <td>{{ optional($row->user)->name ?? '-' }}</td>
                        <td>{{ $row->keterangan }}</td>
                        <td class="text-right">Rp {{ number_format($row->debet > 0 ? $row->debet : $row->kredit, 0, ',', '.') }}
                        </td>
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
                        <td colspan="7" style="text-align: center; padding: 20px;">Belum ada data pemasangan baru.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td colspan="5" class="text-right">TOTAL PEMASANGAN BARU:</td>
                    <td class="text-right text-success">Rp {{ number_format($totalPemasangan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
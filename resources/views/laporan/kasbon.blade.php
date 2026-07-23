@extends('layouts.app')
@section('title', 'Laporan Kasbon Teknisi')

@section('content')
    <div class="header">
        <h2>Laporan Kasbon Teknisi</h2>
    </div>
    <div class="header" style="flex-direction: column; align-items: flex-start; gap: 15px;">
        <h2>Filter Kasbon</h2>

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

    <div class="table-responsive">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="width:50px; text-align:center;">No</th>
                    <th style="width:110px; text-align:center;">Tanggal</th>
                    <th style="width:150px;">Area</th>
                    <th style="width:180px;">Nama Teknisi</th>
                    <th style="width:120px; text-align:center;">Metode</th>
                    <th>Keterangan</th>
                    <th style="width:150px; text-align:right;">Nominal</th>
                    <th style="width:170px; text-align:center;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($kasbon as $index => $row)
                    <tr>
                        <td style="text-align:center;">
                            {{ $index + 1 }}
                        </td>

                        <td style="text-align:center;">
                            {{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ optional($row->area)->nama_area ?? 'Pusat' }}
                        </td>

                        <td>
                            <strong>{{ optional($row->user)->name ?? '-' }}</strong>
                        </td>

                        <td style="text-align:center;">
                            {{ optional($row->metodePembayaran)->nama_metode }}
                        </td>

                        <td>
                            {{ $row->keterangan }}
                        </td>

                        <td style="text-align:right; white-space:nowrap; color:#e74c3c; font-weight:bold;">
                            Rp {{ number_format($row->kredit, 0, ',', '.') }}
                        </td>

                        <td style="text-align:center; white-space:nowrap;">

                            <a href="{{ url('/laporan/transaksi/edit/' . $row->id) }}" style="
                                        display:inline-block;
                                        background:#f39c12;
                                        color:#fff;
                                        text-decoration:none;
                                        padding:5px 10px;
                                        border-radius:4px;
                                        font-size:11px;
                                        margin-right:4px;">
                                Edit
                            </a>

                            <a href="{{ url('/laporan/transaksi/hapus/' . $row->id) }}"
                                onclick="return confirm('Yakin ingin menghapus transaksi ini?')" style="
                                        display:inline-block;
                                        background:#e74c3c;
                                        color:#fff;
                                        text-decoration:none;
                                        padding:5px 10px;
                                        border-radius:4px;
                                        font-size:11px;">
                                Hapus
                            </a>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="8" style="
                                    text-align:center;
                                    padding:20px;
                                    color:#7f8c8d;">
                            Belum ada data kasbon teknisi.
                        </td>
                    </tr>

                @endforelse

            </tbody>

            <tfoot>
                <tr style="background:#f8f9fa; font-weight:bold;">
                    <td colspan="6" style="text-align:right;">
                        TOTAL KASBON KELUAR :
                    </td>

                    <td style="text-align:right; color:#e74c3c;">
                        Rp {{ number_format($totalKasbon, 0, ',', '.') }}
                    </td>

                    <td></td>
                </tr>
            </tfoot>

        </table>
    </div>
@endsection
@extends('layouts.app')
@section('title', 'Laporan Kasbon Teknisi')

@section('content')
    <!-- BUNGKUS UTAMA (FLEX-SHRINK: 0) AGAR TABEL TIDAK DIGENCET -->
    <div style="flex-shrink: 0; width: 100%; padding-bottom: 40px;">

        <div class="header" style="margin-bottom: 25px;">
            <h2>Laporan Kasbon Teknisi</h2>
        </div>

        <div class="header" style="flex-direction: column; align-items: flex-start; gap: 15px; margin-bottom: 25px;">
            <h2>Filter Kasbon</h2>

            <form method="GET" action=""
                style="display: flex; gap: 10px; flex-wrap: wrap; background: #f8f9fa; padding: 15px; border-radius: 6px; width: 100%;">

                <div>
                    <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 3px;">Dari
                        Tanggal:</label>
                    <input type="date" name="tanggal_mulai" value="{{ $mulai }}"
                        style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 3px;">Sampai
                        Tanggal:</label>
                    <input type="date" name="tanggal_selesai" value="{{ $sampai }}"
                        style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <!-- DROPDOWN FILTER TEKNISI -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 3px;">Pilih
                        Teknisi:</label>
                    <select name="teknisi_id"
                        style="padding: 6px; border: 1px solid #ccc; border-radius: 4px; min-width: 150px;">
                        <option value="">-- Semua Teknisi --</option>
                        @foreach($teknisis as $tek)
                            <option value="{{ $tek->id }}" {{ $teknisiId == $tek->id ? 'selected' : '' }}>
                                {{ $tek->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; align-items: flex-end;">
                    <button type="submit"
                        style="background: #e67e22; color: white; border: none; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer;">Terapkan
                        Filter</button>
                </div>
            </form>
        </div>

        <!-- Tabel Kasbon -->
        <h3 style="margin-top: 10px; margin-bottom: 10px; color: #d35400; font-size: 16px;">▶ Rincian Kasbon</h3>
        <div
            style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); padding: 15px; overflow-x: auto; margin-bottom: 30px;">
            <table style="width: 100%; min-width: 1000px; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f1f2f6; border-top: 2px solid #333; border-bottom: 2px solid #333;">
                        <th style="width: 50px; text-align: center; padding: 10px;">No</th>
                        <th style="width: 100px; padding: 10px;">Tanggal</th>
                        <th style="padding: 10px;">Teknisi</th>
                        <th style="padding: 10px;">Metode</th>
                        <th style="padding: 10px;">Area</th>
                        <th style="padding: 10px;">Keterangan</th>
                        <th class="text-right" style="width: 130px; padding: 10px;">Nominal Kasbon</th>
                        <th style="text-align: center; width: 140px; padding: 10px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasbon as $index => $row)
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="text-align: center; padding: 10px;">{{ $index + 1 }}</td>
                            <td style="padding: 10px;">{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                            <td style="padding: 10px; font-weight: 600; color: #2c3e50;">{{ optional($row->user)->name ?? '-' }}
                            </td>
                            <td style="padding: 10px;">{{ optional($row->metodePembayaran)->nama_metode ?? '-' }}</td>
                            <td style="padding: 10px;">{{ optional($row->area)->nama_area ?? 'Pusat' }}</td>
                            <td style="padding: 10px;">{{ $row->keterangan }}</td>
                            <td class="text-right" style="padding: 10px; color: #e74c3c; font-weight: 600;">
                                Rp {{ number_format($row->kredit > 0 ? $row->kredit : $row->debet, 0, ',', '.') }}
                            </td>
                            <td style="text-align: center; padding: 10px;">
                                <a href="{{ url('/laporan/transaksi/edit/' . $row->id) }}"
                                    style="background: #f39c12; color: white; padding: 4px 8px; text-decoration: none; border-radius: 3px; font-size: 11px;">Edit</a>
                                <a href="{{ url('/laporan/transaksi/hapus/' . $row->id) }}"
                                    onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                                    style="background: #e74c3c; color: white; padding: 4px 8px; text-decoration: none; border-radius: 3px; font-size: 11px; margin-left: 3px;">Hapus</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px; color: #7f8c8d;">Belum ada data kasbon.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr
                        style="background-color: #fce4ec; font-weight: bold; border-top: 2px solid #333; border-bottom: 2px solid #333;">
                        <td colspan="6" class="text-right" style="padding: 10px;">TOTAL KASBON:</td>
                        <td class="text-right" style="padding: 10px; color: #c0392b; font-size: 14px;">Rp
                            {{ number_format($totalKasbon, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
@endsection
@extends('layouts.app')
@section('title', 'Laporan Berdasarkan Area')

@section('content')
    <div class="header" style="flex-direction: column; align-items: flex-start; gap: 15px;">
        <h2>Laporan Retail Per Area</h2>

        <!-- Form Filter Tanggal & Area -->
        <form method="GET" action="{{ url('/laporan/area') }}"
            style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%; background: #f8f9fa; padding: 15px; border-radius: 6px;">
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
            <div>
                <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 3px;">Pilih Area:</label>
                <select name="area_id"
                    style="padding: 7px; border: 1px solid #000000; border-radius: 4px; min-width: 180px;">
                    <option value="">-- Semua Area --</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ $areaId == $area->id ? 'selected' : '' }}>{{ $area->nama_area }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="submit"
                    style="background: #2980b9; color: white; border: none; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer;">Filter
                    Laporan</button>
            </div>
        </form>
    </div>

    <!-- Kotak Summary Total Gabungan (Cash + Transfer) -->
    <div class="summary-cards" style="margin-bottom: 20px;">
        <div class="card debet" style="background-color: #1b426128;">
            <h3>Total Pemasukan Area Terpilih</h3>
            <div class="amount">Rp {{ number_format($totalNominal, 0, ',', '.') }}</div>
            <small style="opacity: 0.8; font-size: 12px;">(Gabungan Cash & Transfer)</small>
        </div>
    </div>

    <!-- Tabel Data Transaksi Area -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Area / Wilayah</th>
                    <th>Keterangan</th>
                    <th>Metode Pembayaran</th>
                    <th class="text-right">Nominal (Debet)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                        <td>
                            <span
                                style="background: #e8f8f5; color: #1abc9c; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                {{ $row->area->nama_area ?? 'Umum / Pusat' }}
                            </span>
                        </td>
                        <td>{{ $row->keterangan }}</td>
                        <td>
                            <span
                                style="background: #95a5a6; color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px;">
                                {{ $row->metodePembayaran->nama_metode ?? '-' }}
                            </span>
                        </td>
                        <td class="text-right text-success">{{ number_format($row->debet, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            Tidak ditemukan data pemasukan untuk filter area dan tanggal tersebut.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td colspan="5" class="text-right">TOTAL KESELURUHAN:</td>
                    <td class="text-right text-success">Rp {{ number_format($totalNominal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
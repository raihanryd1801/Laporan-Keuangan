@extends('layouts.app')
@section('title', 'Laporan Keuangan Utama & Rekapitulasi')

@section('content')
    <div class="header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <h2>Laporan Keuangan Utama & Rekapitulasi</h2>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <!-- Form Filter Tanggal -->
            <form method="GET" action="{{ url('/laporan/keuangan') }}" class="filter-form">
                <input type="date" name="tanggal_mulai" value="{{ $mulai }}">
                <input type="date" name="tanggal_selesai" value="{{ $sampai }}">
                <button type="submit">Filter</button>
            </form>

            <a href="{{ url('/laporan/export/excel?tanggal_mulai=' . $mulai . '&tanggal_selesai=' . $sampai) }}"
                style="background: #27ae60; color: white; padding: 8px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 13px;">📥 Excel</a>
            <a href="{{ url('/laporan/export/pdf?tanggal_mulai=' . $mulai . '&tanggal_selesai=' . $sampai) }}" target="_blank"
                style="background: #e74c3c; color: white; padding: 8px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 13px;">🖨️ Cetak PDF</a>
        </div>
    </div>

    <!-- KARTU SUMMARY -->
    <div class="summary-cards">
        <div class="card debet">
            <h3>Pemasukan Berjalan</h3>
            <div class="amount">Rp {{ number_format($totalDebet, 0, ',', '.') }}</div>
        </div>
        <div class="card kredit">
            <h3>Pengeluaran Berjalan</h3>
            <div class="amount">Rp {{ number_format($totalKredit, 0, ',', '.') }}</div>
        </div>
        <div class="card saldo">
            <h3>Saldo Akhir Net</h3>
            <div class="amount">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- TABEL UTAMA LAPORAN KEUANGAN -->
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); padding: 15px; overflow-x: auto; margin-bottom: 30px;">
        <div style="text-align: center; font-weight: bold; margin-bottom: 15px; font-size: 15px;">
            Periode {{ \Carbon\Carbon::parse($mulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($sampai)->format('d F Y') }}
        </div>

        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr style="background-color: #f1f2f6; border-top: 2px solid #333; border-bottom: 2px solid #333;">
                    <th style="width: 50px; text-align: center; padding: 8px;">No</th>
                    <th style="padding: 8px;">Keterangan</th>
                    <th style="text-align: right; padding: 8px; width: 140px;">Debet</th>
                    <th style="text-align: right; padding: 8px; width: 140px;">Kredit</th>
                    <th style="text-align: right; padding: 8px; width: 160px;">Saldo</th>
                </tr>
            </thead>
            <tbody>
                <!-- I. SALDO -->
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td style="text-align: center;">I</td>
                    <td colspan="4">Saldo</td>
                </tr>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>Saldo Bulan Sebelumnya (Saldo Awal)</td>
                    <td></td>
                    <td></td>
                    <td class="text-right">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">2</td>
                    <td>Pemasukan Berjalan (Bulan Ini)</td>
                    <td class="text-right text-success">Rp {{ number_format($totalDebet, 0, ',', '.') }}</td>
                    <td></td>
                    <td class="text-right">Rp {{ number_format($saldoAwal + $totalDebet, 0, ',', '.') }}</td>
                </tr>

                <!-- II. PEMASUKAN -->
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td style="text-align: center;">II</td>
                    <td colspan="4">Pemasukan Rinci</td>
                </tr>
                @php 
                    $noPemasukan = 1; 
                    $daftarAreaNames = $areas->pluck('nama_area')->toArray();
                @endphp

                <!-- A. Berdasarkan Area Retail -->
                @foreach($areas as $area)
                    <tr>
                        <td style="text-align: center;">{{ $noPemasukan++ }}</td>
                        <td>Pembayaran Retail {{ $area->nama_area }}</td>
                        <td class="text-right text-success">Rp {{ number_format($pemasukanPerArea[$area->nama_area] ?? 0, 0, ',', '.') }}</td>
                        <td></td>
                        <td class="text-right">-</td>
                    </tr>
                @endforeach

                <!-- B. Pemasukan Lainnya di Luar Area (Misal: Tukar Cash, dll) -->
                @foreach($pemasukanPerArea as $namaKey => $nominalKey)
                    @if(!in_array($namaKey, $daftarAreaNames) && $nominalKey > 0)
                        <tr>
                            <td style="text-align: center;">{{ $noPemasukan++ }}</td>
                            <td>{{ $namaKey }}</td>
                            <td class="text-right text-success">Rp {{ number_format($nominalKey, 0, ',', '.') }}</td>
                            <td></td>
                            <td class="text-right">-</td>
                        </tr>
                    @endif
                @endforeach

                <!-- C. Kasbon Masuk -->
                <tr>
                    <td style="text-align: center;">{{ $noPemasukan++ }}</td>
                    <td>Pembayaran Kasbon Teknisi</td>
                    <td class="text-right text-success">Rp {{ number_format($kasbonMasuk, 0, ',', '.') }}</td>
                    <td></td>
                    <td class="text-right">-</td>
                </tr>

                <!-- III. PENGELUARAN -->
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td style="text-align: center;">III</td>
                    <td colspan="4">Pengeluaran Rinci</td>
                </tr>
                @php $noPengeluaran = 1; @endphp
                @foreach($pengeluaranPerKategori as $namaKat => $nominalKat)
                    <tr>
                        <td style="text-align: center;">{{ $noPengeluaran++ }}</td>
                        <td>{{ $namaKat }}</td>
                        <td></td>
                        <td class="text-right text-danger">Rp {{ number_format($nominalKat, 0, ',', '.') }}</td>
                        <td class="text-right text-danger">-Rp {{ number_format($nominalKat, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td style="text-align: center;">{{ $noPengeluaran++ }}</td>
                    <td>Kasbon Teknisi</td>
                    <td></td>
                    <td class="text-right text-danger">Rp {{ number_format($kasbonKeluar, 0, ',', '.') }}</td>
                    <td class="text-right text-danger">-Rp {{ number_format($kasbonKeluar, 0, ',', '.') }}</td>
                </tr>

                <!-- TOTAL KEUANGAN RETAIL -->
                <tr style="background-color: #ffeaa7; font-weight: bold; font-size: 14px; border-top: 2px solid #333; border-bottom: 2px solid #333;">
                    <td colspan="2" style="text-align: center; padding: 10px;">TOTAL KEUANGAN RETAIL</td>
                    <td class="text-right">Rp {{ number_format($totalDebet, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalKredit, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #d63031;">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- TABEL SUMMARY POSISI KAS AKHIR -->
        <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 30px;">
            <thead>
                <tr style="background-color: #74b9ff; color: #fff;">
                    <th colspan="3" style="padding: 8px; text-align: center;">Laporan Keuangan Retail - Posisi Saldo Akhir</th>
                </tr>
                <tr style="background-color: #f1f2f6; border-bottom: 2px solid #333;">
                    <th style="width: 50px; text-align: center; padding: 6px;">No</th>
                    <th style="padding: 6px;">Keterangan Posisi Kas</th>
                    <th style="text-align: right; padding: 6px; width: 200px;">Saldo (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>Uang Cash di Operasional</td>
                    <!-- Menampilkan Saldo Awal Cash (Sisa operasional lama) -->
                    <td class="text-right">Rp {{ number_format($saldoAwalCash, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">2</td>
                    <td>Uang Cash dari Retail yang belum disetor ke Bank</td>
                    <!-- Menampilkan Uang Cash Belum Disetor (Hasil hitungan mutasi berjalan) -->
                    <td class="text-right">Rp {{ number_format($uangCashBelumDisetor, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">3</td>
                    <td>Uang Retail di Rekening</td>
                    <!-- Menampilkan Saldo Bank -->
                    <td class="text-right">Rp {{ number_format($uangRetailDiRekening, 0, ',', '.') }}</td>
                </tr>
                
                @php
                    // Pastikan total di bawah sama persis dengan $saldoAkhir global
                    $totalPosisiKas = $saldoAwalCash + $uangCashBelumDisetor + $uangRetailDiRekening;
                @endphp
                <tr style="background-color: #ffeaa7; font-weight: bold;">
                    <td colspan="2" style="text-align: center; padding: 8px;">TOTAL KEUANGAN RETAIL</td>
                    <td class="text-right" style="color: #d63031;">Rp {{ number_format($totalPosisiKas, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
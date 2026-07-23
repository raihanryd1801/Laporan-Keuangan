<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Utama & Rekapitulasi</title>
    <style>
        /* Mengatur ukuran halaman A4 dengan margin rapat agar muat 1 halaman */
        @page {
            size: A4;
            margin: 8mm 10mm 15mm 10mm;
        }

        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #333;
            padding: 0;
            margin: 0;
        }

        /* Kop Surat - Logo di kiri, Teks Informasi di Tengah */
        .kop-container {
            border-bottom: 2px double #333;
            padding-bottom: 6px;
            margin-bottom: 10px;
            position: relative;
        }

        .kop-logo {
            position: absolute;
            left: 0;
            top: 1px;
            width: 45px;
        }

        .kop-text {
            text-align: center;
            width: 100%;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .kop-text p {
            margin: 2px 0 0 0;
            font-size: 10px;
            font-weight: bold;
            color: #444;
        }

        /* Footer Alamat di Bawah Halaman */
        .footer-alamat {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 4px;
            background: #fff;
        }

        /* Tabel Lebih Rapat agar Muat 1 Halaman Bersama TTD */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 3px 5px;
            text-align: left;
        }

        th {
            background: #f1f2f6;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .text-success {
            color: #27ae60;
        }

        .center-total {
            text-align: center !important;
            font-weight: bold;
        }

        /* Mencegah Konten Terpotong / Memaksa Bagian Bawah Satu Halaman */
        .keep-together {
            page-break-inside: avoid;
        }

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <!-- KOP SURAT (LOGO DI KIRI, NAMA PT DI TENGAH) -->
    <div class="kop-container">
        <img src="{{ asset('images/fans.png') }}" alt="Logo" class="kop-logo" onerror="this.style.display='none'">
        <div class="kop-text">
            <h2>PT. FANS MEDIA JEMBER</h2>
            <p>Laporan Keuangan Retail</p>
            <div style="font-size: 10px; font-weight: normal; margin-top: 2px;">
                Periode: {{ \Carbon\Carbon::parse($mulai)->format('d F Y') }} s/d
                {{ \Carbon\Carbon::parse($sampai)->format('d F Y') }}
            </div>
        </div>
    </div>

    <!-- TABEL UTAMA KEUANGAN -->
    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th>Keterangan</th>
                <th class="text-right" style="width: 100px;">Debet</th>
                <th class="text-right" style="width: 100px;">Kredit</th>
                <th class="text-right" style="width: 110px;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <!-- I. SALDO -->
            <tr style="background: #f8f9fa;" class="bold">
                <td class="text-center">I</td>
                <td colspan="4">Saldo</td>
            </tr>
            <tr>
                <td class="text-center">1</td>
                <td>Saldo Bulan Sebelumnya (Saldo Awal)</td>
                <td></td>
                <td></td>
                <td class="text-right">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>Pemasukan Berjalan (Bulan Ini)</td>
                <td class="text-right text-success">Rp {{ number_format($totalDebet, 0, ',', '.') }}</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($saldoAwal + $totalDebet, 0, ',', '.') }}</td>
            </tr>

            <!-- II. PEMASUKAN -->
            <tr style="background: #f8f9fa;" class="bold">
                <td class="text-center">II</td>
                <td colspan="4">Pemasukan Rinci</td>
            </tr>
            @php 
                $noPemasukan = 1; 
                $daftarAreaNames = $areas->pluck('nama_area')->toArray();
            @endphp

            <!-- A. Berdasarkan Area Retail -->
            @foreach($areas as $area)
                <tr>
                    <td class="text-center">{{ $noPemasukan++ }}</td>
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
                        <td class="text-center">{{ $noPemasukan++ }}</td>
                        <td>{{ $namaKey }}</td>
                        <td class="text-right text-success">Rp {{ number_format($nominalKey, 0, ',', '.') }}</td>
                        <td></td>
                        <td class="text-right">-</td>
                    </tr>
                @endif
            @endforeach

            <!-- C. Kasbon Masuk -->
            <tr>
                <td class="text-center">{{ $noPemasukan++ }}</td>
                <td>Pembayaran Kasbon Teknisi</td>
                <td class="text-right text-success">Rp {{ number_format($kasbonMasuk, 0, ',', '.') }}</td>
                <td></td>
                <td class="text-right">-</td>
            </tr>

            <!-- III. PENGELUARAN -->
            <tr style="background: #f8f9fa;" class="bold">
                <td class="text-center">III</td>
                <td colspan="4">Pengeluaran Rinci</td>
            </tr>
            @php $noPengeluaran = 1; @endphp
            @foreach($pengeluaranPerKategori as $namaKat => $nominalKat)
                <tr>
                    <td class="text-center">{{ $noPengeluaran++ }}</td>
                    <td>{{ $namaKat }}</td>
                    <td></td>
                    <td class="text-right">Rp {{ number_format($nominalKat, 0, ',', '.') }}</td>
                    <td class="text-right">-Rp {{ number_format($nominalKat, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td class="text-center">{{ $noPengeluaran++ }}</td>
                <td>Kasbon Teknisi</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($kasbonKeluar, 0, ',', '.') }}</td>
                <td class="text-right">-Rp {{ number_format($kasbonKeluar, 0, ',', '.') }}</td>
            </tr>

            <!-- TOTAL KEUANGAN RETAIL -->
            <tr class="bold" style="background-color: #ffeaa7; font-size: 11px;">
                <td colspan="2" class="center-total">TOTAL KEUANGAN RETAIL</td>
                <td class="text-right">Rp {{ number_format($totalDebet, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalKredit, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- BUNGKUS KONTROL SUPAYA POSISI KAS AKHIR & TTD BERADA DI 1 HALAMAN YANG UTUH -->
    <div class="keep-together">
        <!-- TABLE SUMMARY POSISI KAS AKHIR -->
        <table>
            <thead>
                <tr style="background-color: #f1f2f6;">
                    <th colspan="3" class="text-center" style="font-size: 10px; padding: 4px; font-weight: bold;">
                        Laporan Keuangan Retail - Posisi Saldo Akhir</th>
                </tr>
                <tr>
                    <th style="width: 30px;" class="text-center">No</th>
                    <th>Keterangan</th>
                    <th class="text-right" style="width: 130px;">Saldo (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>Uang Cash di Operasional</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>Uang Cash dari Retail yang belum disetor ke Bank</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>Uang Retail di Rekening</td>
                    <td class="text-right">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                </tr>
                <!-- TOTAL KEUANGAN RETAIL -->
                <tr class="bold" style="background-color: #ffeaa7;">
                    <td colspan="2" class="center-total">TOTAL KEUANGAN RETAIL</td>
                    <td class="text-right">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- CATATAN & TANDA TANGAN -->
        <div style="margin-top: 6px; font-style: italic; font-size: 9px;">Catatan : Bukti Terlampir</div>

        <table style="width: 100%; border: none; margin-top: 10px;">
            <tr>
                <td style="border: none; width: 50%; text-align: center; vertical-align: top;">
                    Direktur
                    <br><br><br><br>
                    <strong>Fans Ach Farrosil Miqdad</strong>
                </td>
                <td style="border: none; width: 50%; text-align: center; vertical-align: top;">
                    Jember, {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}<br>
                    Admin Retail
                    <br><br><br><br>
                    <strong>Hertina Rahmaningtyas</strong>
                </td>
            </tr>
        </table>

        <table style="width: 100%; border: none; margin-top: 5px;">
            <tr>
                <td style="border: none; text-align: center; vertical-align: top;">
                    Mengetahui,<br>
                    Komisaris
                    <br><br><br><br>
                    <strong>Erfan Effendi S.Pd., M.Pd</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER ALAMAT DI BAWAH HALAMAN -->
    <div class="footer-alamat">
        Alamat: Perum Griya Mangli Indah Df 01, Wonosari, Mangli, Kec. Kaliwates, Kabupaten Jember, Jawa Timur 68136 |
        Provinsi: Jawa Timur | Telepon: 0851-7505-9195
    </div>

</body>

</html>
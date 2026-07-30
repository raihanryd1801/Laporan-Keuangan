@extends('layouts.app')
@section('title', 'Laporan Pemasukan Retail')

@section('content')
    <!-- BUNGKUS UTAMA (FLEX-SHRINK: 0) AGAR TABEL TIDAK DIGENCET OLEH LAYOUT UTAMA -->
    <div style="flex-shrink: 0; width: 100%; padding-bottom: 40px;">

        <div class="header" style="margin-bottom: 25px;">
            <h2>Laporan Pemasukan Retail</h2>
        </div>

        <!-- ========================================== -->
        <!-- WIDGET CARDS SUMMARY (SEMUA CARD DALAM SATU GRID YANG RAPI) -->
        <!-- ========================================== -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            
            <!-- Card 1: Pemasukan Cash -->
            <div style="background: #fff; border-radius: 8px; border-left: 5px solid #2ecc71; padding: 20px 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: center; min-height: 110px;">
                <p style="margin: 0; font-size: 12px; color: #7f8c8d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">
                    Total Pemasukan Cash
                </p>
                <h3 style="margin: 8px 0 4px 0; font-size: 24px; color: #2ecc71; font-weight: 700;">
                    Rp {{ number_format($totalCash, 0, ',', '.') }}
                </h3>
                <small style="color: #95a5a6; font-size: 11px; line-height: 1.4;">Omzet Murni Tunai</small>
            </div>

            <!-- Card 2: Pemasukan Transfer -->
            <div style="background: #fff; border-radius: 8px; border-left: 5px solid #3498db; padding: 20px 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: center; min-height: 110px;">
                <p style="margin: 0; font-size: 12px; color: #7f8c8d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">
                    Total Pemasukan Transfer
                </p>
                <h3 style="margin: 8px 0 4px 0; font-size: 24px; color: #3498db; font-weight: 700;">
                    Rp {{ number_format($totalTransfer, 0, ',', '.') }}
                </h3>
                <small style="color: #95a5a6; font-size: 11px; line-height: 1.4;">Omzet Langsung ke Bank</small>
            </div>

            <!-- Card 3: Setor Tunai / Mutasi -->
            <div style="background: #fff; border-radius: 8px; border-left: 5px solid #9b59b6; padding: 20px 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: center; min-height: 110px;">
                <p style="margin: 0; font-size: 12px; color: #7f8c8d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">
                    Total Setor Ke Bank (Mutasi)
                </p>
                <h3 style="margin: 8px 0 4px 0; font-size: 24px; color: #9b59b6; font-weight: 700;">
                    Rp {{ number_format($totalMutasi, 0, ',', '.') }}
                </h3>
                <small style="color: #95a5a6; font-size: 11px; line-height: 1.4;">Pindahan Fisik ke Rekening PT</small>
            </div>

            <!-- Card 4: Cash Berjalan -->
            <div style="background: #fff; border-radius: 8px; border-left: 5px solid #16a085; padding: 20px 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: center; min-height: 110px;">
                <p style="margin: 0; font-size: 12px; color: #7f8c8d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">
                    Total Cash Berjalan
                </p>
                <h3 style="margin: 8px 0 4px 0; font-size: 24px; color: #16a085; font-weight: 700;">
                    Rp {{ number_format($totalCashBerjalan, 0, ',', '.') }}
                </h3>
                <small style="color: #95a5a6; font-size: 11px; line-height: 1.4;">Cash - Saldo Awal</small>
            </div>

            <!-- Card 5: Transfer Berjalan -->
            <div style="background: #fff; border-radius: 8px; border-left: 5px solid #e67e22; padding: 20px 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: center; min-height: 110px;">
                <p style="margin: 0; font-size: 12px; color: #7f8c8d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">
                    Total Transfer Berjalan
                </p>
                <h3 style="margin: 8px 0 4px 0; font-size: 24px; color: #e67e22; font-weight: 700;">
                    Rp {{ number_format($totalTransferBerjalan, 0, ',', '.') }}
                </h3>
                <small style="color: #95a5a6; font-size: 11px; line-height: 1.4;">Transfer - Saldo Awal</small>
            </div>

        </div>
        <!-- ========================================== -->

        <!-- ========================================== -->
        <!-- FORM FILTER TANGGAL -->
        <div class="header" style="flex-direction: column; align-items: flex-start; gap: 15px; margin-bottom: 25px;">
            <h2>Filter Pemasukan & Mutasi</h2>

            <form method="GET" action=""
                style="display: flex; gap: 10px; flex-wrap: wrap; background: #f8f9fa; padding: 15px; border-radius: 6px; width: 100%;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 3px;">Dari Tanggal:</label>
                    <input type="date" name="tanggal_mulai" value="{{ $mulai }}"
                        style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 3px;">Sampai Tanggal:</label>
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
        <!-- ========================================== -->


        <!-- TABEL 1: RIWAYAT SETOR TUNAI / MUTASI BANK (IKUT TERFILTER TANGGAL) -->
        <h3 style="margin-top: 10px; margin-bottom: 10px; color: #8e44ad; font-size: 16px;">▶ Riwayat Setor Tunai / Mutasi Bank</h3>
        <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); padding: 15px; overflow-x: auto; margin-bottom: 30px;">
            <table style="width: 100%; min-width: 1000px; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f1f2f6; border-top: 2px solid #333; border-bottom: 2px solid #333;">
                        <th style="width: 50px; text-align: center; padding: 10px;">No</th>
                        <th style="width: 100px; padding: 10px;">Tanggal</th>
                        <th style="padding: 10px;">Tujuan Bank</th>
                        <th style="padding: 10px;">Keterangan</th>
                        <th class="text-right" style="width: 130px; padding: 10px;">Nominal Setor</th>
                        <th style="text-align: center; width: 140px; padding: 10px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatMutasi as $index => $mutasi)
                    <tr style="border-bottom: 1px solid #f1f1f1;">
                        <td style="text-align: center; padding: 10px;">{{ $index + 1 }}</td>
                        <td style="padding: 10px;">{{ \Carbon\Carbon::parse($mutasi->tanggal)->format('d/m/Y') }}</td>
                        <td style="padding: 10px;">{{ optional($mutasi->metodePembayaran)->nama_metode ?? '-' }}</td>
                        <td style="padding: 10px;">{{ str_replace('[MUTASI MASUK] ', '', $mutasi->keterangan) }}</td>
                        <td class="text-right" style="padding: 10px; color: #8e44ad; font-weight: bold;">Rp {{ number_format($mutasi->debet, 0, ',', '.') }}</td>
                        <td style="text-align: center; padding: 10px;">
                            <a href="{{ url('/laporan/transaksi/edit/' . $mutasi->id) }}"
                                style="background: #f39c12; color: white; padding: 4px 8px; text-decoration: none; border-radius: 3px; font-size: 11px;">Edit</a>
                            
                            <a href="{{ url('/laporan/transaksi/hapus/' . $mutasi->id) }}"
                                onclick="return confirm('Yakin ingin menghapus riwayat setor ini?')"
                                style="background: #e74c3c; color: white; padding: 4px 8px; text-decoration: none; border-radius: 3px; font-size: 11px; margin-left: 3px;">Hapus</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #7f8c8d;">Belum ada data riwayat setor tunai pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TABEL 2: Pemasukan via Cash -->
        <h3 style="margin-top: 10px; margin-bottom: 10px; color: #2ecc71; font-size: 16px;">▶ Pemasukan via Cash</h3>
        <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); padding: 15px; overflow-x: auto; margin-bottom: 30px;">
            <table style="width: 100%; min-width: 1000px; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f1f2f6; border-top: 2px solid #333; border-bottom: 2px solid #333;">
                        <th style="width: 50px; text-align: center; padding: 10px;">No</th>
                        <th style="width: 100px; padding: 10px;">Tanggal</th>
                        <th style="padding: 10px;">Kategori</th>
                        <th style="padding: 10px;">Area</th>
                        <th style="padding: 10px;">Teknisi</th>
                        <th style="padding: 10px;">Keterangan</th>
                        <th class="text-right" style="width: 130px; padding: 10px;">Nominal</th>
                        <th style="text-align: center; width: 140px; padding: 10px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemasukanCash as $index => $row)
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="text-align: center; padding: 10px;">{{ $index + 1 }}</td>
                            <td style="padding: 10px;">{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                            <td style="padding: 10px;">{{ optional($row->kategori)->nama_kategori }}</td>
                            <td style="padding: 10px;">{{ optional($row->area)->nama_area ?? 'Pusat' }}</td>
                            <td style="padding: 10px;">{{ optional($row->user)->name ?? '-' }}</td>
                            <td style="padding: 10px;">{{ $row->keterangan }}</td>
                            <td class="text-right text-success" style="padding: 10px;">Rp
                                {{ number_format($row->debet, 0, ',', '.') }}</td>
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
                            <td colspan="8" style="text-align: center; padding: 20px; color: #7f8c8d;">Belum ada data pemasukan cash.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TABEL 3: Pemasukan via Transfer Bank -->
        <h3 style="margin-top: 10px; margin-bottom: 10px; color: #3498db; font-size: 16px;">▶ Pemasukan via Transfer Bank</h3>
        <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); padding: 15px; overflow-x: auto; margin-bottom: 30px;">
            <table style="width: 100%; min-width: 1000px; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f1f2f6; border-top: 2px solid #333; border-bottom: 2px solid #333;">
                        <th style="width: 50px; text-align: center; padding: 10px;">No</th>
                        <th style="width: 100px; padding: 10px;">Tanggal</th>
                        <th style="padding: 10px;">Kategori</th>
                        <th style="padding: 10px;">Area</th>
                        <th style="padding: 10px;">Teknisi</th>
                        <th style="padding: 10px;">Keterangan</th>
                        <th class="text-right" style="width: 130px; padding: 10px;">Nominal</th>
                        <th style="text-align: center; width: 140px; padding: 10px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemasukanTransfer as $index => $row)
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="text-align: center; padding: 10px;">{{ $index + 1 }}</td>
                            <td style="padding: 10px;">{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                            <td style="padding: 10px;">{{ optional($row->kategori)->nama_kategori }}</td>
                            <td style="padding: 10px;">{{ optional($row->area)->nama_area ?? 'Pusat' }}</td>
                            <td style="padding: 10px;">{{ optional($row->user)->name ?? '-' }}</td>
                            <td style="padding: 10px;">{{ $row->keterangan }}</td>
                            <td class="text-right text-success" style="padding: 10px;">Rp
                                {{ number_format($row->debet, 0, ',', '.') }}</td>
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
                            <td colspan="8" style="text-align: center; padding: 20px; color: #7f8c8d;">Belum ada data pemasukan transfer.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div> <!-- END PENUTUP BUNGKUS SAKTI -->
@endsection
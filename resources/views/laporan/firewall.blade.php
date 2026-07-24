@extends('layouts.app')
@section('title', 'Manajemen Firewall & Sesi')

@section('content')
    <div style="width: 100%; padding-bottom: 40px;">

        <div class="header" style="margin-bottom: 25px;">
            <h2>🛡️ Firewall & Manajemen Sesi Aktif</h2>
            <p class="text-muted" style="font-size: 13px;">Kelola IP Address yang diizinkan mengakses sistem dan pantau sesi
                pengguna yang sedang aktif.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success"
                style="padding: 12px; background: #d4edda; color: #155724; border-radius: 6px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">

            <!-- BAGIAN 1: WHITESLIST IP ADDRESS -->
            <div
                style="flex: 1; min-width: 350px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h4 style="margin-bottom: 15px; font-size: 16px; color: #2c3e50;">➕ Tambah IP Whitelist</h4>

                <form action="{{ url('/laporan/firewall/store-ip') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 4px;">IP
                            Address:</label>
                        <input type="text" name="ip_address" class="form-control" placeholder="Contoh: 192.168.99.50"
                            required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 4px;">Keterangan /
                            Pemilik:</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Laptop Admin NOC"
                            required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>

                    <button type="submit"
                        style="background: #2ecc71; color: white; border: none; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%;">Simpan
                        IP Whitelist</button>
                </form>

                <hr style="margin: 25px 0; border: 0; border-top: 1px solid #eee;">

                <h4 style="margin-bottom: 15px; font-size: 16px; color: #2c3e50;">📋 Daftar IP yang Diizinkan</h4>
                <div style="max-height: 250px; overflow-y: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background: #f1f2f6; text-align: left;">
                                <th style="padding: 8px;">IP Address</th>
                                <th style="padding: 8px;">Keterangan</th>
                                <th style="padding: 8px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allowedIps as $ip)
                                <tr style="border-bottom: 1px solid #f1f1f1;">
                                    <td style="padding: 8px; font-weight: 600; color: #e67e22;">{{ $ip->ip_address }}</td>
                                    <td style="padding: 8px;">{{ $ip->keterangan }}</td>
                                    <td style="padding: 8px; text-align: center;">
                                        <form action="{{ url('/laporan/firewall/delete-ip/' . $ip->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin mencabut izin IP {{ $ip->ip_address }} ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="background: #e74c3c; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; cursor: pointer;">Cabut</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 15px; color: #7f8c8d;">Belum ada IP yang
                                        didaftarkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- BAGIAN 2: MONITORING & KILL SESI AKTIF -->
            <div
                style="flex: 2; min-width: 450px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h4 style="margin-bottom: 15px; font-size: 16px; color: #2c3e50;">💻 Sesi Pengguna & Guest yang Aktif</h4>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background: #f1f2f6; border-top: 2px solid #333; border-bottom: 2px solid #333;">
                                <th style="padding: 10px;">IP Address</th>
                                <th style="padding: 10px;">User / Akun</th>
                                <th style="padding: 10px;">Aktivitas Terakhir</th>
                                <th style="text-align: center; padding: 10px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeSessions as $session)
                                <tr style="border-bottom: 1px solid #f1f1f1;">
                                    <td style="padding: 10px; font-weight: bold; color: #2980b9;">{{ $session->ip_address }}
                                    </td>
                                    <td style="padding: 10px;">{{ $session->name ?? 'Guest / Tamu (Belum Login)' }}</td>
                                    <td style="padding: 10px;">
                                        {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                    </td>
                                    <td style="text-align: center; padding: 10px;">
                                        <form action="{{ url('/laporan/firewall/kill/' . $session->session_id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menendang sesi / IP ini dari sistem?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="background: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; cursor: pointer;">Tendang
                                                / Kill</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 20px; color: #7f8c8d;">Tidak ada sesi
                                        aktif saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
@endsection
@extends('layouts.app')
@section('title', 'Manajemen Firewall & Sesi')

@section('content')
    <div style="width: 100%; padding-bottom: 40px;">

        <div class="header" style="margin-bottom: 25px;">
            <h2>Firewall & Manajemen Sesi Aktif</h2>
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

            <!-- BAGIAN 1: WHITELIST IP ADDRESS -->
            <div
                style="flex: 1; min-width: 350px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h4 style="margin-bottom: 15px; font-size: 16px; color: #2c3e50;">Tambah IP Whitelist</h4>

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

                <h4 style="margin-bottom: 15px; font-size: 16px; color: #2c3e50;">Daftar IP yang Diizinkan</h4>
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
                <h4 style="margin-bottom: 15px; font-size: 16px; color: #2c3e50;">Sesi Pengguna & Guest yang Aktif</h4>

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
                                        <form action="{{ url('/laporan/firewall/kill/' . $session->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Cabut Sesi</button>
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
                <!-- BAGIAN 1: FAIL2BAN WHITELIST (IGNOREIP) -->
                <div
                    style="margin-top: 30px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h4 style="margin-bottom: 15px; font-size: 16px; color: #2c3e50;">Fail2ban Whitelist Manager (Ignore
                        IP)</h4>

                    <form action="{{ url('/laporan/firewall/fail2ban/store') }}" method="POST"
                        style="display: flex; gap: 10px; margin-bottom: 20px;">
                        @csrf
                        <input type="text" name="ip_address" class="form-control"
                            placeholder="Masukkan IP untuk di-whitelist (Contoh: 36.85.x.x)" required
                            style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <button type="submit"
                            style="background: #3498db; color: white; border: none; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer;">Tambah
                            ke Whitelist</button>
                    </form>

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                            <thead>
                                <tr style="background: #f1f2f6; text-align: left;">
                                    <th style="padding: 8px;">IP Address Whitelist</th>
                                    <th style="padding: 8px; text-align: center; width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fail2banIps as $fIp)
                                    <tr style="border-bottom: 1px solid #f1f1f1;">
                                        <td style="padding: 8px; font-weight: 600; color: #2980b9;">{{ $fIp }}</td>
                                        <td style="padding: 8px; text-align: center;">
                                            <form action="{{ url('/laporan/firewall/fail2ban/delete') }}" method="POST"
                                                onsubmit="return confirm('Cabut IP {{ $fIp }} dari whitelist?');">
                                                @csrf
                                                <input type="hidden" name="ip" value="{{ $fIp }}">
                                                <button type="submit"
                                                    style="background: #e74c3c; color: white; border: none; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; cursor: pointer;">Cabut</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" style="text-align: center; padding: 15px; color: #7f8c8d;">Belum ada IP
                                            tambahan di whitelist fail2ban.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- BAGIAN: PENGATURAN FAIL2BAN (MAXRETRY & BANTIME) -->
                <div
                    style="margin-top: 30px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h4 style="margin-bottom: 15px; font-size: 16px; color: #2c3e50;">Konfigurasi Parameter Fail2ban</h4>

                    <form action="{{ url('/laporan/firewall/fail2ban/config') }}" method="POST"
                        style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: end;">
                        @csrf
                        <div>
                            <label style="font-size: 12px; font-weight: bold; color: #555;">Max Retry (Batas Gagal
                                Login):</label>
                            <input type="number" name="maxretry" value="{{ $fail2banConfig->maxretry ?? 3 }}"
                                class="form-control" required
                                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                        <div>
                            <label style="font-size: 12px; font-weight: bold; color: #555;">Ban Time (Durasi Blokir dalam
                                Detik):</label>
                            <input type="number" name="bantime" value="{{ $fail2banConfig->bantime ?? 3600 }}"
                                class="form-control" required
                                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            <small style="color: #7f8c8d; font-size: 11px;">(Contoh: 3600 = 1 Jam, 86400 = 24 Jam, atau
                                604800 = 1 Minggu)</small>
                        </div>
                        <div>
                            <button type="submit"
                                style="background: #27ae60; color: white; border: none; padding: 9px 20px; border-radius: 4px; font-weight: bold; cursor: pointer;">Simpan
                                Pengaturan</button>
                        </div>
                    </form>
                </div>

                <!-- BAGIAN: DAFTAR IP YANG SEDANG TERBLOKIR (BANNED IP + UNBAN) -->
                <div
                    style="margin-top: 30px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h4 style="margin-bottom: 15px; font-size: 16px; color: #2c3e50;">Daftar IP yang Sedang Diblokir
                        (Banned IPs)</h4>

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                            <thead>
                                <tr style="background: #f1f2f6; text-align: left;">
                                    <th style="padding: 8px;">IP Terblokir Saat Ini</th>
                                    <th style="padding: 8px; text-align: center; width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Ambil daftar IP yang sedang diblokir secara real-time dari fail2ban-client
                                    $bannedOutput = shell_exec('sudo fail2ban-client status laravel-auth');
                                    preg_match('/Banned IP list:\s*(.*)/', $bannedOutput, $matches);
                                    $bannedIps = isset($matches[1]) ? array_filter(explode(' ', trim($matches[1]))) : [];
                                @endphp

                                @forelse($bannedIps as $bIp)
                                    <tr style="border-bottom: 1px solid #f1f1f1;">
                                        <td style="padding: 8px; font-weight: 600; color: #c0392b;">{{ $bIp }}</td>
                                        <td style="padding: 8px; text-align: center;">
                                            <form action="{{ url('/laporan/firewall/fail2ban/unban') }}" method="POST"
                                                onsubmit="return confirm('Buka blokir (Unban) untuk IP {{ $bIp }}?');">
                                                @csrf
                                                <input type="hidden" name="ip" value="{{ $bIp }}">
                                                <button type="submit"
                                                    style="background: #f39c12; color: white; border: none; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; cursor: pointer;">Unban
                                                    IP</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" style="text-align: center; padding: 15px; color: #7f8c8d;">Tidak ada IP
                                            yang sedang diblokir saat ini (Aman).</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
@extends('layouts.app')
@section('title', 'Manajemen Firewall & Sesi')

@section('content')
    <div class="container-fluid" style="padding: 20px 0;">

        {{-- Header --}}
        <div class="header-section" style="margin-bottom: 30px;">
            <h2 style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">Firewall & Manajemen Sesi Aktif</h2>
            <p style="color: #7f8c8d; font-size: 14px; margin: 0;">Kelola IP Address yang diizinkan mengakses sistem dan
                pantau sesi pengguna yang sedang aktif.</p>
        </div>

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="alert alert-success"
                style="padding: 15px; background: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #28a745;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Main Grid --}}
        <div class="row" style="display: grid; grid-template-columns: 1.2fr 2fr; gap: 25px;">

            {{-- Kolom Kiri: Whitelist IP --}}
            <div class="card"
                style="background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 22px; transition: box-shadow 0.3s;">
                <h4
                    style="font-size: 17px; font-weight: 600; color: #2c3e50; margin-top: 0; margin-bottom: 20px; border-bottom: 2px solid #ecf0f1; padding-bottom: 12px;">
                    <span
                        style="background: #2ecc71; width: 8px; height: 8px; display: inline-block; border-radius: 50%; margin-right: 10px;"></span>
                    Tambah IP Whitelist
                </h4>

                <form action="{{ url('/laporan/firewall/store-ip') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-size: 13px; font-weight: 600; color: #34495e; margin-bottom: 5px;">IP
                            Address</label>
                        <input type="text" name="ip_address" class="form-control" placeholder="Contoh: 192.168.99.50"
                            required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #dce1e8; border-radius: 6px; font-size: 14px; transition: border-color 0.2s;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label
                            style="display: block; font-size: 13px; font-weight: 600; color: #34495e; margin-bottom: 5px;">Keterangan
                            / Pemilik</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Laptop Admin NOC"
                            required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #dce1e8; border-radius: 6px; font-size: 14px;">
                    </div>
                    <button type="submit" class="btn btn-success"
                        style="background: #2ecc71; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; color: #fff; width: 100%; cursor: pointer; transition: background 0.2s; font-size: 15px;">
                        Simpan IP Whitelist
                    </button>
                </form>

                <hr style="margin: 28px 0; border: 0; border-top: 1px solid #ecf0f1;">

                <h4 style="font-size: 17px; font-weight: 600; color: #2c3e50; margin-top: 0; margin-bottom: 15px;">
                    <span
                        style="background: #3498db; width: 8px; height: 8px; display: inline-block; border-radius: 50%; margin-right: 10px;"></span>
                    Daftar IP yang Diizinkan
                </h4>
                <div style="max-height: 300px; overflow-y: auto; border-radius: 6px; border: 1px solid #ecf0f1;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #2c3e50;">IP
                                    Address</th>
                                <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #2c3e50;">
                                    Keterangan</th>
                                <th style="padding: 10px 12px; text-align: center; font-weight: 600; color: #2c3e50;">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allowedIps as $ip)
                                <tr style="border-bottom: 1px solid #f1f2f6; transition: background 0.1s;">
                                    <td style="padding: 10px 12px; font-weight: 600; color: #e67e22;">{{ $ip->ip_address }}</td>
                                    <td style="padding: 10px 12px; color: #34495e;">{{ $ip->keterangan }}</td>
                                    <td style="padding: 10px 12px; text-align: center;">
                                        <form action="{{ url('/laporan/firewall/delete-ip/' . $ip->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin mencabut izin IP {{ $ip->ip_address }} ini?');"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                style="background: #e74c3c; border: none; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; color: #fff; cursor: pointer; transition: background 0.2s;">Cabut</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        style="text-align: center; padding: 20px; color: #95a5a6; font-style: italic;">Belum ada
                                        IP yang didaftarkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kolom Kanan: Sesi Aktif + Fail2ban --}}
            <div class="card"
                style="background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 22px; transition: box-shadow 0.3s;">

                {{-- Sesi Aktif --}}
                <h4
                    style="font-size: 17px; font-weight: 600; color: #2c3e50; margin-top: 0; margin-bottom: 20px; border-bottom: 2px solid #ecf0f1; padding-bottom: 12px;">
                    <span
                        style="background: #9b59b6; width: 8px; height: 8px; display: inline-block; border-radius: 50%; margin-right: 10px;"></span>
                    Sesi Pengguna & Guest yang Aktif
                </h4>

                <div style="overflow-x: auto; border-radius: 6px; border: 1px solid #ecf0f1; margin-bottom: 30px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #2c3e50;">IP
                                    Address</th>
                                <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #2c3e50;">User /
                                    Akun</th>
                                <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #2c3e50;">
                                    Aktivitas Terakhir</th>
                                <th style="padding: 10px 12px; text-align: center; font-weight: 600; color: #2c3e50;">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeSessions as $session)
                                <tr style="border-bottom: 1px solid #f1f2f6; transition: background 0.1s;">
                                    <td style="padding: 10px 12px; font-weight: 600; color: #2980b9;">{{ $session->ip_address }}
                                    </td>
                                    <td style="padding: 10px 12px; color: #34495e;">{{ $session->name ?? 'Guest / Tamu' }}</td>
                                    <td style="padding: 10px 12px; color: #7f8c8d;">
                                        {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}</td>
                                    <td style="padding: 10px 12px; text-align: center;">
                                        <form action="{{ url('/laporan/firewall/kill/' . $session->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                style="background: #e74c3c; border: none; padding: 4px 14px; border-radius: 4px; font-size: 12px; font-weight: 600; color: #fff; cursor: pointer; transition: background 0.2s;">Cabut
                                                Sesi</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        style="text-align: center; padding: 20px; color: #95a5a6; font-style: italic;">Tidak ada
                                        sesi aktif saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Fail2ban Whitelist --}}
                <div style="margin-bottom: 25px;">
                    <h4
                        style="font-size: 17px; font-weight: 600; color: #2c3e50; margin-top: 0; margin-bottom: 15px; border-bottom: 2px solid #ecf0f1; padding-bottom: 12px;">
                        <span
                            style="background: #f39c12; width: 8px; height: 8px; display: inline-block; border-radius: 50%; margin-right: 10px;"></span>
                        Fail2ban Whitelist Manager (Ignore IP)
                    </h4>

                    <form action="{{ url('/laporan/firewall/fail2ban/store') }}" method="POST"
                        style="display: flex; gap: 12px; margin-bottom: 18px; flex-wrap: wrap;">
                        @csrf
                        <input type="text" name="ip_address" class="form-control"
                            placeholder="Masukkan IP untuk di-whitelist (Contoh: 36.85.x.x)" required
                            style="flex: 1; min-width: 200px; padding: 10px 12px; border: 1px solid #dce1e8; border-radius: 6px; font-size: 14px;">
                        <button type="submit" class="btn btn-primary"
                            style="background: #3498db; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; color: #fff; cursor: pointer; transition: background 0.2s; font-size: 14px;">Tambah
                            ke Whitelist</button>
                    </form>

                    <div style="overflow-x: auto; border-radius: 6px; border: 1px solid #ecf0f1;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                            <thead>
                                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                    <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #2c3e50;">IP
                                        Address Whitelist</th>
                                    <th
                                        style="padding: 10px 12px; text-align: center; width: 120px; font-weight: 600; color: #2c3e50;">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fail2banIps as $fIp)
                                    <tr style="border-bottom: 1px solid #f1f2f6;">
                                        <td style="padding: 10px 12px; font-weight: 600; color: #2980b9;">{{ $fIp }}</td>
                                        <td style="padding: 10px 12px; text-align: center;">
                                            <form action="{{ url('/laporan/firewall/fail2ban/delete') }}" method="POST"
                                                onsubmit="return confirm('Cabut IP {{ $fIp }} dari whitelist?');"
                                                style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="ip" value="{{ $fIp }}">
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    style="background: #e74c3c; border: none; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; color: #fff; cursor: pointer; transition: background 0.2s;">Cabut</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2"
                                            style="text-align: center; padding: 20px; color: #95a5a6; font-style: italic;">Belum
                                            ada IP tambahan di whitelist fail2ban.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Konfigurasi Fail2ban --}}
                <div style="margin-bottom: 25px;">
                    <h4
                        style="font-size: 17px; font-weight: 600; color: #2c3e50; margin-top: 0; margin-bottom: 15px; border-bottom: 2px solid #ecf0f1; padding-bottom: 12px;">
                        <span
                            style="background: #1abc9c; width: 8px; height: 8px; display: inline-block; border-radius: 50%; margin-right: 10px;"></span>
                        Konfigurasi Parameter Fail2ban
                    </h4>

                    <form action="{{ url('/laporan/firewall/fail2ban/config') }}" method="POST"
                        style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 18px; align-items: end;">
                        @csrf
                        <div>
                            <label
                                style="display: block; font-size: 13px; font-weight: 600; color: #34495e; margin-bottom: 5px;">Max
                                Retry</label>
                            <input type="number" name="maxretry" value="{{ $fail2banConfig->maxretry ?? 3 }}"
                                class="form-control" required
                                style="width: 100%; padding: 10px 12px; border: 1px solid #dce1e8; border-radius: 6px; font-size: 14px;">
                            <small style="color: #7f8c8d; font-size: 12px;">Batas gagal login</small>
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 13px; font-weight: 600; color: #34495e; margin-bottom: 5px;">Ban
                                Time (detik)</label>
                            <input type="number" name="bantime" value="{{ $fail2banConfig->bantime ?? 3600 }}"
                                class="form-control" required
                                style="width: 100%; padding: 10px 12px; border: 1px solid #dce1e8; border-radius: 6px; font-size: 14px;">
                            <small style="color: #7f8c8d; font-size: 12px;">3600 = 1 Jam, 86400 = 24 Jam</small>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success"
                                style="background: #27ae60; border: none; padding: 10px 28px; border-radius: 6px; font-weight: 600; color: #fff; cursor: pointer; transition: background 0.2s; font-size: 14px;">Simpan
                                Pengaturan</button>
                        </div>
                    </form>
                </div>

                {{-- Daftar Banned IP --}}
                <div>
                    <h4
                        style="font-size: 17px; font-weight: 600; color: #2c3e50; margin-top: 0; margin-bottom: 15px; border-bottom: 2px solid #ecf0f1; padding-bottom: 12px;">
                        <span
                            style="background: #e74c3c; width: 8px; height: 8px; display: inline-block; border-radius: 50%; margin-right: 10px;"></span>
                        Daftar IP yang Sedang Diblokir (Banned IPs)
                    </h4>

                    @php
                        $bannedOutput = shell_exec('sudo fail2ban-client status laravel-auth');
                        preg_match('/Banned IP list:\s*(.*)/', $bannedOutput, $matches);
                        $bannedIps = isset($matches[1]) ? array_filter(explode(' ', trim($matches[1]))) : [];
                    @endphp

                    <div style="overflow-x: auto; border-radius: 6px; border: 1px solid #ecf0f1;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                            <thead>
                                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                    <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #2c3e50;">IP
                                        Terblokir</th>
                                    <th
                                        style="padding: 10px 12px; text-align: center; width: 120px; font-weight: 600; color: #2c3e50;">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bannedIps as $bIp)
                                    <tr style="border-bottom: 1px solid #f1f2f6;">
                                        <td style="padding: 10px 12px; font-weight: 600; color: #c0392b;">{{ $bIp }}</td>
                                        <td style="padding: 10px 12px; text-align: center;">
                                            <form action="{{ url('/laporan/firewall/fail2ban/unban') }}" method="POST"
                                                onsubmit="return confirm('Buka blokir (Unban) untuk IP {{ $bIp }}?');"
                                                style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="ip" value="{{ $bIp }}">
                                                <button type="submit" class="btn btn-warning"
                                                    style="background: #f39c12; border: none; padding: 4px 14px; border-radius: 4px; font-size: 12px; font-weight: 600; color: #fff; cursor: pointer; transition: background 0.2s;">Unban
                                                    IP</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2"
                                            style="text-align: center; padding: 20px; color: #95a5a6; font-style: italic;">Tidak
                                            ada IP yang sedang diblokir saat ini (Aman).</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>{{-- end kolom kanan --}}

        </div>{{-- end row --}}

    </div>

    {{-- Optional: tambahan style untuk hover efek --}}
    <style>
        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
        }

        .btn:hover {
            opacity: 0.85;
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        table tbody tr:hover {
            background: #fafbfc;
        }

        input:focus {
            border-color: #3498db !important;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
    </style>
@endsection
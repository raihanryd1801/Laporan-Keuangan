@extends('layouts.app')
@section('title', 'Log Aktivitas Sistem')

@section('content')

    <div class="header"
        style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px; flex-wrap: wrap; gap: 15px;">
        <h2>History Log Aktivitas Sistem</h2>

        <div
            style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; background: #f8f9fa; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
            <!-- Form Pencarian & Filter Tanggal -->
            <form method="GET" action="{{ url('/laporan/activity-log') }}"
                style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; margin: 0;">

                <div style="display: flex; align-items: center; gap: 5px;">
                    <label style="font-size: 11px; font-weight: bold; color: #2c3e50;">Dari:</label>
                    <input type="date" name="tanggal_mulai" value="{{ $mulai }}"
                        style="padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-size: 13px;">
                </div>

                <div style="display: flex; align-items: center; gap: 5px;">
                    <label style="font-size: 11px; font-weight: bold; color: #2c3e50;">Sampai:</label>
                    <input type="date" name="tanggal_selesai" value="{{ $sampai }}"
                        style="padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-size: 13px;">
                </div>

                <div style="display: flex; align-items: center; gap: 5px; border-left: 2px solid #ddd; padding-left: 10px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user atau teks..."
                        style="padding: 6px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 13px; outline: none; width: 180px;">

                    <button type="submit"
                        style="background: #3498db; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: bold; cursor: pointer;">
                        🔍 Filter
                    </button>

                    @if(request('search') || request('tanggal_mulai'))
                        <a href="{{ url('/laporan/activity-log') }}"
                            style="background: #e74c3c; color: #fff; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: bold; display: flex; align-items: center;">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <a href="{{ url('/laporan/keuangan') }}"
                style="background:#95a5a6; color:#fff; padding:6px 12px; border-radius:4px; text-decoration:none; font-size:13px; font-weight:600; margin-left: 10px;">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="table-responsive"
        style="background:#fff; padding:15px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,.05);">

        <table>
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">No</th>
                    <th style="width:160px;">Waktu</th>
                    <th style="width:170px;">User / Admin</th>
                    <th style="width:150px;">Aktivitas</th>
                    <th>Deskripsi Perubahan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($logs as $index => $log)

                    @php
                        $badgeColor = '#3498db';

                        if (stripos($log->aktivitas, 'Tambah') !== false) {
                            $badgeColor = '#2ecc71';
                        }
                        if (stripos($log->aktivitas, 'Edit') !== false) {
                            $badgeColor = '#f39c12';
                        }
                        if (stripos($log->aktivitas, 'Hapus') !== false) {
                            $badgeColor = '#e74c3c';
                        }
                    @endphp

                    <tr>
                        <td style="text-align:center;">
                            {{ $logs->firstItem() + $index }}
                        </td>

                        <td>
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>

                        <td>
                            <strong>{{ optional($log->user)->name ?? 'System / Guest' }}</strong>
                        </td>

                        <td>
                            <span
                                style="background:{{ $badgeColor }}; color:#fff; padding:4px 10px; border-radius:5px; font-size:11px; font-weight:bold;">
                                {{ $log->aktivitas }}
                            </span>
                        </td>

                        <td>{{ $log->deskripsi }}</td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" style="text-align:center; padding:25px; color:#7f8c8d;">
                            Belum ada riwayat aktivitas yang ditemukan.
                        </td>
                    </tr>

                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        <div style="margin-top:18px;">

            <div class="pagination-container" style="display:flex; justify-content:flex-start; align-items:center;">
                <!-- appends(request()->query()) mengamankan URL pagination agar parameter filter tidak hilang saat pindah halaman -->
                {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>

            <div style="margin-top:10px; font-size:13px; color:#6c757d;">
                Menampilkan
                <strong>{{ $logs->firstItem() ?? 0 }}</strong>
                -
                <strong>{{ $logs->lastItem() ?? 0 }}</strong>
                dari
                <strong>{{ $logs->total() }}</strong>
                data
            </div>

        </div>

    </div>

    <style>
        /* CSS PAGINATION RAPI */
        .pagination-container nav>ul.pagination {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            padding-left: 0 !important;
            list-style: none !important;
            margin: 0 !important;
            gap: 4px;
        }

        .pagination-container .page-item {
            display: inline-block !important;
            float: none !important;
            margin: 0 !important;
        }

        .pagination-container .page-link {
            padding: 6px 12px !important;
            font-size: 13px !important;
            min-width: 36px;
            height: 34px;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 6px !important;
            border: 1px solid #dee2e6;
            color: #333;
            background-color: #fff;
            text-decoration: none;
            box-shadow: none !important;
        }

        .pagination-container .page-link svg {
            width: 14px;
            height: 14px;
        }

        .pagination-container .page-link:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        .pagination-container .page-item.active .page-link {
            background-color: #3498db !important;
            border-color: #3498db !important;
            color: #fff !important;
        }

        .pagination-container .page-item.disabled .page-link {
            color: #94a3b8 !important;
            background-color: #f8f9fa !important;
            border-color: #e2e8f0 !important;
            pointer-events: none;
        }
    </style>

@endsection
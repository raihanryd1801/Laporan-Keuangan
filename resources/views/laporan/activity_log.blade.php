@extends('layouts.app')
@section('title', 'Log Aktivitas Sistem')

@section('content')

    <div class="header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h2>History Log Aktivitas Sistem</h2>

        <a href="{{ url('/laporan/keuangan') }}" style="
                            background:#95a5a6;
                            color:#fff;
                            padding:8px 14px;
                            border-radius:6px;
                            text-decoration:none;
                            font-size:13px;
                            font-weight:600;">
            ← Kembali
        </a>
    </div>

    <div class="table-responsive"
        style="background:#fff;padding:15px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,.05);">

        <table>
            <thead>
                <tr>
                    <th style="width:60px;text-align:center;">No</th>
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
                            <span style="
                                                            background:{{ $badgeColor }};
                                                            color:#fff;
                                                            padding:4px 10px;
                                                            border-radius:5px;
                                                            font-size:11px;
                                                            font-weight:bold;">
                                {{ $log->aktivitas }}
                            </span>
                        </td>

                        <td>{{ $log->deskripsi }}</td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" style="text-align:center;padding:25px;color:#7f8c8d;">
                            Belum ada riwayat aktivitas.
                        </td>
                    </tr>

                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        <div style="margin-top:18px;">

            <div class="pagination-container" style="display:flex; justify-content:flex-start; align-items:center;">
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>

            <div style="margin-top:10px;font-size:13px;color:#6c757d;">
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
        /* Menyembunyikan tombol teks Previous & Next */
        .pagination-container .page-item:first-child,
        .pagination-container .page-item:last-child {
            display: none !important;
        }

        /* Memaksa list pagination tersusun sejajar ke samping (horizontal) */
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
@extends('layouts.app')
@section('title', 'Log Aktivitas Sistem')

@section('content')
    <div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>History Log Aktivitas Sistem</h2>
        <a href="{{ url('/laporan/keuangan') }}"
            style="background: #95a5a6; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: bold;">←
            Kembali ke Laporan</a>
    </div>

    <div class="table-responsive"
        style="background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th style="width: 150px;">Waktu</th>
                    <th style="width: 140px;">User / Admin</th>
                    <th style="width: 150px;">Aktivitas</th>
                    <th>Deskripsi Perubahan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                            <tr>
                                <td style="text-align: center;">{{ $logs->firstItem() + $index }}</td>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td><strong>{{ optional($log->user)->name ?? 'System / Guest' }}</strong></td>
                                <td>
                                    @php
                                        $badgeColor = '#3498db'; // Default Biru
                                        if (stripos($log->aktivitas, 'Tambah') !== false)
                                            $badgeColor = '#2ecc71'; // Hijau
                                        if (stripos($log->aktivitas, 'Edit') !== false)
                                            $badgeColor = '#f39c12'; // Oranye
                                        if (stripos($log->aktivitas, 'Hapus') !== false)
                                            $badgeColor = '#e74c3c'; // Merah
                                    @endphp
                     <span
                                        style="background: {{ $badgeColor }}; color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                        {{ $log->aktivitas }}
                                    </span>
                                </td>
                                <td>{{ $log->deskripsi }}</td>
                            </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 25px; color: #7f8c8d;">Belum ada riwayat aktivitas
                            yang tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Styling & Layout Pagination yang Rapih -->
        <div
            style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #555;">
            <div>
                Menampilkan <strong>{{ $logs->firstItem() ?? 0 }}</strong> sampai
                <strong>{{ $logs->lastItem() ?? 0 }}</strong> dari total <strong>{{ $logs->total() }}</strong> data
            </div>
            <div>
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    <!-- CSS Tambahan Khusus Pagination bawaan Laravel agar selaras dengan tema web -->
    <style>
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            gap: 5px;
            margin: 0;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination li a,
        .pagination li span {
            position: relative;
            display: block;
            padding: 6px 12px;
            text-decoration: none;
            color: #3498db;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .pagination li.active span {
            z-index: 3;
            color: #fff;
            background-color: #3498db;
            border-color: #3498db;
        }

        .pagination li.disabled span {
            color: #7f8c8d;
            background-color: #f8f9fa;
            border-color: #ddd;
        }

        .pagination li a:hover {
            background-color: #e9ecef;
            border-color: #ddd;
        }
    </style>
@endsection
<!DOCTYPE html>
<html lang="id">

<head>
    {{-- Night mode: terapkan tema sebelum paint agar tidak kedip. Murni tampilan, tanpa ubah data. --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('skykom-theme');
                if (!t && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) t = 'dark';
                document.documentElement.dataset.theme = (t === 'dark') ? 'dark' : 'light';
            } catch (e) { document.documentElement.dataset.theme = 'light'; }
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="turbo-cache-control" content="no-cache">
    <title>@yield('title', 'Aplikasi Keuangan Retail')</title>
    {{-- Turbo Hotwired: SPA-like navigation tanpa reload <head>, tanpa ubah data/backend --}}
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8/dist/turbo.es2017-umd.js" defer></script>
    {{-- Toggle night mode: event-delegation di document (head tidak di-render ulang Turbo) --}}
    <script>
        function syncThemeToggle() {
            var dark = document.documentElement.dataset.theme === 'dark';
            document.querySelectorAll('[data-theme-toggle]').forEach(function (b) {
                b.textContent = dark ? '☀️ Mode Siang' : '🌙 Mode Malam';
                b.setAttribute('aria-pressed', dark ? 'true' : 'false');
            });
        }
        document.addEventListener('click', function (e) {
            var b = (e.target && e.target.closest) ? e.target.closest('[data-theme-toggle]') : null;
            if (!b) return;
            var next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
            document.documentElement.dataset.theme = next;
            try { localStorage.setItem('skykom-theme', next); } catch (err) {}
            syncThemeToggle();
            window.dispatchEvent(new Event('theme:changed'));
        });
        document.addEventListener('DOMContentLoaded', syncThemeToggle);
        document.addEventListener('turbo:load', syncThemeToggle);
    </script>
    <style>
        /* ===== Variabel tema: light = warna lama persis, dark = night mode ===== */
        :root {
            color-scheme: light;
            --bg: #f4f6f9;
            --surface: #fff;
            --surface-2: #f8f9fa;
            --text: #333;
            --heading: #2c3e50;
            --muted: #7f8c8d;
            --border: #f1f1f1;
            --hover: #fdfdfd;
            --input-border: #ccc;
            --inline-surface: #222834;
        }
        [data-theme="dark"] {
            color-scheme: dark;
            --bg: #161a21;
            --surface: #20262f;
            --surface-2: #2a313b;
            --text: #dfe3e8;
            --heading: #ecf0f1;
            --muted: #9aa4b2;
            --border: #2e3540;
            --hover: #262c36;
            --input-border: #3a4250;
        }
        /* Progress bar Turbo saat pindah menu */
        .turbo-progress-bar {
            height: 3px;
            background-color: #e67e22;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 260px;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
            height: 100vh;
            flex-shrink: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 10px;
            overflow-y: auto;
            flex: 1;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu .menu-category {
            font-size: 11px;
            text-transform: uppercase;
            color: #95a5a6;
            padding: 10px 10px 5px 10px;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #bdc3c7;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #34495e;
            color: #ffffff;
            font-weight: 600;
            transform: translateX(4px);
        }

        .sidebar-menu a.btn-input {
            background-color: #e67e22;
            color: white;
            margin-top: 10px;
            font-weight: bold;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar-menu a.btn-input:hover {
            background-color: #d35400;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            background-color: var(--bg);
            padding: 30px;
            height: 100vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            background: var(--surface);
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .header h2 {
            color: var(--heading);
            font-size: 22px;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            background: var(--surface);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            border-left: 5px solid #3498db;
        }

        .card.debet {
            border-left-color: #2ecc71;
        }

        .card.kredit {
            border-left-color: #e74c3c;
        }

        .card.saldo {
            border-left-color: #f39c12;
        }

        .card h3 {
            font-size: 13px;
            color: var(--muted);
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .card .amount {
            font-size: 20px;
            font-weight: bold;
            color: var(--heading);
        }

        .table-responsive {
            background: var(--surface);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            overflow-x: auto;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th,
        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        th {
            background-color: var(--surface-2);
            color: var(--heading);
            font-weight: 600;
        }

        tr:hover {
            background-color: var(--hover);
        }

        .text-right {
            text-align: right;
        }

        .text-success {
            color: #27ae60;
            font-weight: bold;
        }

        .text-danger {
            color: #e74c3c;
            font-weight: bold;
        }

        .filter-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-form input[type="date"],
        .filter-form select {
            padding: 8px 12px;
            border: 1px solid var(--input-border);
            border-radius: 4px;
            font-size: 13px;
            background-color: var(--surface);
            color: var(--text);
        }

        .filter-form button {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 13px;
        }

        .filter-form button:hover {
            background: #2980b9;
        }

        .alert-success {
            background: #2ecc71;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* ===== Night mode: samakan elemen konten ber-inline-style terang =====
           (terbatas di .main-content; sidebar, login & PDF tidak tersentuh.
            Warna semantik surface berwarna — hijau/merah/kuning pastel — sengaja dipertahankan.) */
        [data-theme="dark"] .main-content [style*="background: #fff"],
        [data-theme="dark"] .main-content [style*="background:#fff"],
        [data-theme="dark"] .main-content [style*="background-color: #fff"],
        [data-theme="dark"] .main-content [style*="background: #f8f9fa"],
        [data-theme="dark"] .main-content [style*="background:#f8f9fa"],
        [data-theme="dark"] .main-content [style*="background-color: #f8f9fa"],
        [data-theme="dark"] .main-content [style*="background-color: #f1f2f6"],
        [data-theme="dark"] .main-content [style*="background: #f1f2f6"],
        [data-theme="dark"] .main-content [style*="background: #e9ecef"],
        [data-theme="dark"] .main-content [style*="background:#e9ecef"] {
            background-color: var(--inline-surface) !important;
        }
        [data-theme="dark"] .main-content [style*="color: #2c3e50"],
        [data-theme="dark"] .main-content [style*="color:#2c3e50"],
        [data-theme="dark"] .main-content [style*="color: #34495e"],
        [data-theme="dark"] .main-content [style*="color:#34495e"],
        [data-theme="dark"] .main-content [style*="color: #333"],
        [data-theme="dark"] .main-content [style*="color:#333"],
        [data-theme="dark"] .main-content [style*="color: #495057"],
        [data-theme="dark"] .main-content [style*="color:#495057"] {
            color: #e8ecf1 !important;
        }
        [data-theme="dark"] .main-content input,
        [data-theme="dark"] .main-content select,
        [data-theme="dark"] .main-content textarea {
            background-color: #232b36 !important;
            border-color: var(--input-border) !important;
            color: var(--text) !important;
        }
        [data-theme="dark"] .main-content input::placeholder,
        [data-theme="dark"] .main-content textarea::placeholder {
            color: var(--muted);
        }
        /* Kartu ringkasan statistik (pastel) + tombol Reset: samakan ke night mode */
        [data-theme="dark"] .main-content [style*="#d4edda"] { background-color: #1d3a2a !important; }
        [data-theme="dark"] .main-content [style*="#f8d7da"] { background-color: #452227 !important; }
        [data-theme="dark"] .main-content [style*="#cce5ff"] { background-color: #1c3a5e !important; }
        [data-theme="dark"] .main-content [style*="#ffe5cc"] { background-color: #4a3418 !important; }
        [data-theme="dark"] .main-content [style*="#ecf0f1"] { background-color: var(--inline-surface) !important; }
        [data-theme="dark"] .main-content [style*="color: #155724"],
        [data-theme="dark"] .main-content [style*="color:#155724"] { color: #a9dfbf !important; }
        [data-theme="dark"] .main-content [style*="color: #721c24"],
        [data-theme="dark"] .main-content [style*="color:#721c24"] { color: #f1aeb5 !important; }
        [data-theme="dark"] .main-content [style*="color: #004085"],
        [data-theme="dark"] .main-content [style*="color:#004085"] { color: #9ec5fe !important; }
        [data-theme="dark"] .main-content [style*="color: #856404"],
        [data-theme="dark"] .main-content [style*="color:#856404"] { color: #ffda6a !important; }
        /* ===== Night mode: sidebar dipergelap agar menyatu dengan konten ===== */
        [data-theme="dark"] .sidebar {
            background-color: #10141a;
        }
        [data-theme="dark"] .sidebar-header {
            background: linear-gradient(135deg, #0b0e13 0%, #141a22 100%) !important;
            border-bottom-color: #1c232e !important;
        }
        [data-theme="dark"] .sidebar-menu a {
            color: #9aa4b2;
        }
        [data-theme="dark"] .sidebar-menu a:hover,
        [data-theme="dark"] .sidebar-menu a.active {
            background-color: #1c232e;
            color: #ffffff;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR NAVIGATION -->
    <div class="sidebar">

        <!-- HEADER BRAND & PROFIL USER (dengan tema gelap seirama) -->
        <div class="sidebar-header" style="
            padding: 20px 18px 16px 18px;
            border-bottom: 1px solid #1a252f;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #1a252f 0%, #2c3e50 100%);
        ">
            <h3 style="
                margin: 0 0 14px 0;
                font-size: 16px;
                color: #ecf0f1;
                font-weight: 700;
                letter-spacing: 0.3px;
                text-align: center;
            ">
                SKYKOM FINANCE RETAIL
            </h3>

            @auth
                <a href="{{ url('/laporan/profile') }}" style="
                                        display: block;
                                        text-decoration: none;
                                        background: rgba(255,255,255,0.08);
                                        padding: 12px 14px;
                                        border-radius: 8px;
                                        border: 1px solid #34495e;
                                        text-align: center;
                                        transition: 0.2s;
                                    " onmouseover="this.style.background='rgba(255,255,255,0.15)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                    <div style="
                                            font-weight: 700;
                                            color: #ecf0f1;
                                            font-size: 18px;
                                            line-height: 1.4;
                                            word-break: break-word;
                                        ">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="
                                            color: #bdc3c7;
                                            font-size: 18px;
                                            line-height: 1.4;
                                            word-break: break-word;
                                            margin-top: 2px;
                                        ">
                        {{ auth()->user()->email }}
                    </div>
                    <div style="
                                            color: #f1c40f;
                                            font-size: 14px;
                                            margin-top: 5px;
                                            font-weight: 600;
                                            letter-spacing: 0.3px;
                                        ">
                        ✎ User Edit
                    </div>
                </a>
            @endauth
        </div>

        <!-- TOGGLE NIGHT MODE (pilihan tersimpan per browser) -->
        <div style="padding: 0 18px 4px 18px;">
            <button type="button" data-theme-toggle aria-pressed="false" style="
                width:100%;
                background:transparent;
                border:1px solid #34495e;
                color:#bdc3c7;
                padding:9px 12px;
                border-radius:6px;
                font-size:13px;
                font-weight:600;
                cursor:pointer;
            ">🌙 Mode Malam</button>
        </div>

        <!-- MENU SIDEBAR -->
        <ul class="sidebar-menu">
            <li><a href="{{ url('/laporan/menu-input') }}" class="btn-input">+ Menu Input Transaksi</a></li>

            <div class="menu-category" style="margin-top: 15px;">Utama</div>
            <li><a href="{{ url('/laporan/keuangan') }}"
                    class="{{ request()->is('laporan/keuangan') ? 'active' : '' }}">Keuangan Utama</a></li>
            <li><a href="{{ url('/laporan/statistik') }}"
                    class="{{ request()->is('laporan/statistik') ? 'active' : '' }}">Statistik Keuangan</a></li>

            <div class="menu-category">Laporan Spesifik</div>
            <li><a href="{{ url('/laporan/pemasangan-baru') }}"
                    class="{{ request()->is('laporan/pemasangan-baru') ? 'active' : '' }}">Pemasangan Baru</a></li>
            <li><a href="{{ url('/laporan/pemasukan') }}"
                    class="{{ request()->is('laporan/pemasukan') ? 'active' : '' }}">Pemasukan Cash / Transfer</a></li>
            <li><a href="{{ url('/laporan/pengeluaran') }}"
                    class="{{ request()->is('laporan/pengeluaran') ? 'active' : '' }}">Pengeluaran Operasional</a></li>
            <li><a href="{{ url('/laporan/kasbon') }}"
                    class="{{ request()->is('laporan/kasbon') ? 'active' : '' }}">Kasbon Teknisi</a></li>
            <li><a href="{{ url('/laporan/area') }}" class="{{ request()->is('laporan/area') ? 'active' : '' }}">Laporan
                    Per Area</a></li>

            <div class="menu-category">Master Data</div>
            <li><a href="{{ url('/laporan/master-area') }}"
                    class="{{ request()->is('laporan/master-area*') ? 'active' : '' }}">Master Area</a></li>
            <li><a href="{{ url('/laporan/master-kategori') }}"
                    class="{{ request()->is('laporan/master-kategori*') ? 'active' : '' }}">Master Kategori</a></li>
            <li><a href="{{ url('/laporan/teknisi') }}"
                    class="{{ request()->is('laporan/teknisi*') ? 'active' : '' }}">Data Teknisi</a></li>

            <li style="list-style:none; margin-top:15px;">
                <a href="{{ url('/laporan/activity-log') }}" style="
                    width:100%;
                    display:block;
                    background:#27ae60;
                    color:#fff;
                    text-decoration:none;
                    padding:12px 15px;
                    border-radius:6px;
                    font-size:14px;
                    font-weight:600;
                    text-align:center;
                    transition:.3s;
                ">Log Audit</a>
            </li>

            <li style="list-style:none; margin-top:10px;">
                <a href="{{ url('/laporan/firewall') }}" style="
                    width:100%;
                    display:block;
                    background:#27A6AE;
                    color:#fff;
                    text-decoration:none;
                    padding:12px 15px;
                    border-radius:6px;
                    font-size:14px;
                    font-weight:600;
                    text-align:center;
                    transition:.3s;
                ">Firewall & Sesi</a>
            </li>

            <li style="list-style:none; margin-top:10px;">
                {{-- Logout wajib full-reload agar sesi benar-benar bersih --}}
                <form action="{{ route('logout') }}" method="POST" data-turbo="false" style="display:inline; width:100%;">
                    @csrf
                    <button type="submit" style="
                        width:100%;
                        display:block;
                        background:#e74c3c;
                        color:#fff;
                        border:none;
                        padding:12px 15px;
                        border-radius:6px;
                        font-size:14px;
                        font-weight:600;
                        cursor:pointer;
                        text-align:center;
                        transition:.3s;
                    ">Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

</body>

</html>
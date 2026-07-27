<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Keuangan Retail')</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f6f9;
            color: #333;
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
            background-color: #f4f6f9;
            padding: 30px;
            height: 100vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            background: #fff;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .header h2 {
            color: #2c3e50;
            font-size: 22px;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            background: #fff;
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
            color: #7f8c8d;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .card .amount {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
        }

        .table-responsive {
            background: #fff;
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
            border-bottom: 1px solid #f1f1f1;
            font-size: 14px;
        }

        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
        }

        tr:hover {
            background-color: #fdfdfd;
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
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 13px;
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
                <form action="{{ route('logout') }}" method="POST" style="display:inline; width:100%;">
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
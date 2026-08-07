<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Berakhir | NOC Panel PT. Dankom Mitra Abadi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'JetBrains Mono', monospace;
            margin: 0;
        }

        .error-container {
            text-align: center;
            border: 1px solid #334155;
            padding: 3rem;
            border-radius: 8px;
            background-color: #1e293b;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            max-width: 500px;
            width: 90%;
        }

        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #ef4444;
            line-height: 1;
            text-shadow: 0 0 10px rgba(239, 68, 68, 0.4);
        }

        .error-title {
            font-size: 1.5rem;
            margin-top: 1rem;
            color: #e2e8f0;
            letter-spacing: 2px;
        }

        .error-desc {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-bottom: 2rem;
            margin-top: 1rem;
            line-height: 1.6;
        }

        .btn-custom {
            background-color: transparent;
            color: #3b82f6;
            border: 2px solid #3b82f6;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .btn-custom:hover {
            background-color: #3b82f6;
            color: #ffffff;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
        }
    </style>
</head>

<body>

    <div class="error-container">
        <div class="error-code">419</div>
        <div class="error-title">PAGE EXPIRED</div>
        <p class="error-desc">
            Sesi keamanan Anda telah berakhir karena tidak ada aktivitas (CSRF Token Mismatch).<br>
            Silakan muat ulang halaman untuk mendapatkan sesi baru.
        </p>
        <!-- Mengarahkan kembali ke halaman sebelumnya atau ke halaman arsip -->
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/login' }}" class="btn-custom">
            ⟳ Muat Ulang Halaman
        </a>
    </div>

</body>

</html>
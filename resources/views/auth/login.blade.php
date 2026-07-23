<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT. Fans Media Jember</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-card img {
            width: 70px;
            height: auto;
            margin-bottom: 15px;
        }

        .login-card h2 {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .login-card p {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #dcdde1;
            border-radius: 6px;
            font-size: 13px;
            outline: none;
            transition: border 0.2s;
        }

        .form-group input:focus {
            border-color: #3498db;
        }

        .btn-login {
            width: 100%;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 11px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }

        .btn-login:hover {
            background: #1a252f;
        }

        .alert-error {
            background: #ff7675;
            color: white;
            padding: 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <!-- Logo Perusahaan -->
        <img src="{{ asset('images/fans.png') }}" alt="Logo PT. Fans Media Jember" onerror="this.style.display='none'">

        <h2>PT. Fans Media Jember</h2>
        <p>Silakan login untuk masuk ke sistem keuangan</p>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="form-group">
                <label>Email / Username</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="Masukkan email...">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukkan password...">
            </div>

            <button type="submit" class="btn-login">Masuk Sistem</button>
        </form>
    </div>

</body>

</html>
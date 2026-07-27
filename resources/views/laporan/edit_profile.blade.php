@extends('layouts.app')

@section('content')
    <div style="
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 20px;
        ">
        <div style="
                width: 100%;
                max-width: 700px;
                background: #fff;
                padding: 35px 40px;
                border-radius: 12px;
                box-shadow: 0 6px 20px rgba(0,0,0,0.06);
            ">
            <h2 style="
                    font-size: 24px;
                    font-weight: 600;
                    color: #2c3e50;
                    margin-top: 0;
                    margin-bottom: 20px;
                    text-align: center;
                    border-bottom: 2px solid #ecf0f1;
                    padding-bottom: 15px;
                ">
                Pengaturan Profil Akun
            </h2>

            @if(session('success'))
                <div
                    style="background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div
                    style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/laporan/profile') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 18px;">
                    <label
                        style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; color: #34495e;">Nama
                        Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        style="width: 100%; padding: 10px 14px; border: 1px solid #dce1e8; border-radius: 6px; font-size: 15px; transition: border-color 0.2s;">
                </div>

                <div style="margin-bottom: 18px;">
                    <label
                        style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; color: #34495e;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        style="width: 100%; padding: 10px 14px; border: 1px solid #dce1e8; border-radius: 6px; font-size: 15px; transition: border-color 0.2s;">
                </div>

                <hr style="border: 0; border-top: 1px solid #ecf0f1; margin: 25px 0;">

                <p style="font-size: 13px; color: #7f8c8d; margin-bottom: 18px; text-align: center; font-style: italic;">
                    Kosongkan kolom password jika tidak ingin mengubah kata sandi.
                </p>

                <div style="margin-bottom: 18px;">
                    <label
                        style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; color: #34495e;">Password
                        Baru</label>
                    <input type="password" name="password"
                        style="width: 100%; padding: 10px 14px; border: 1px solid #dce1e8; border-radius: 6px; font-size: 15px; transition: border-color 0.2s;">
                </div>

                <div style="margin-bottom: 25px;">
                    <label
                        style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; color: #34495e;">Konfirmasi
                        Password Baru</label>
                    <input type="password" name="password_confirmation"
                        style="width: 100%; padding: 10px 14px; border: 1px solid #dce1e8; border-radius: 6px; font-size: 15px; transition: border-color 0.2s;">
                </div>

                <button type="submit" style="
                        background: #27ae60;
                        color: white;
                        border: none;
                        padding: 12px 20px;
                        border-radius: 6px;
                        font-weight: 600;
                        font-size: 16px;
                        cursor: pointer;
                        width: 100%;
                        transition: background 0.2s;
                    " onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#27ae60'">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <div style="padding: 20px; max-width: 600px; margin: 0 auto;">
        <h2>Pengaturan Profil Akun</h2>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 15px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <form action="{{ url('/laporan/profile') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Nama
                        Lengkap:</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Email:</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

                <p style="font-size: 12px; color: #7f8c8d; margin-bottom: 15px;">Kosongkan bagian password di bawah ini jika
                    tidak ingin mengubah kata sandi.</p>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Password
                        Baru:</label>
                    <input type="password" name="password" class="form-control"
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Konfirmasi
                        Password Baru:</label>
                    <input type="password" name="password_confirmation" class="form-control"
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <button type="submit"
                    style="background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%;">Simpan
                    Perubahan Profil</button>
            </form>
        </div>
    </div>
@endsection
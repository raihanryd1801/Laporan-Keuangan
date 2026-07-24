@extends('layouts.app')
@section('title', 'Form Input Transaksi')

@section('content')
    <div class="header">
        <h2>Form Input Transaksi Retail</h2>
        <a href="{{ url('/laporan/menu-input') }}" style="text-decoration: none; color: #7f8c8d;">&larr; Kembali ke Menu
            Input</a>
    </div>

    <form action="{{ url('/laporan/transaksi/simpan') }}" method="POST" style="max-width: 600px; margin: 0 auto;">
        @csrf

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Tanggal Transaksi</label>
            <input type="date" name="tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Jenis Transaksi:</label>
            <select name="jenis_transaksi" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"
                required>
                <option value="debet" {{ (isset($defaultJenis) && $defaultJenis == 'debet') ? 'selected' : '' }}>Pemasukan
                    (Debet)</option>
                <option value="kredit" {{ (isset($defaultJenis) && $defaultJenis == 'kredit') ? 'selected' : '' }}>Pengeluaran
                    / Kasbon (Kredit)</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Kategori</label>
            <select name="kategori_id" required
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <!-- PILIHAN AREA (BARU) -->
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Pilih Area / Wilayah (Khusus
                Retail)</label>
            <select name="area_id" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">-- Pilih Area (Opsional / Umum) --</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}">{{ $area->nama_area }}</option>
                @endforeach
            </select>
            <small style="color: #7f8c8d;">*Pilih area jika ini transaksi pemasangan/pemasukan retail wilayah
                tertentu.</small>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Metode Pembayaran</label>
            <select name="metode_pembayaran_id" required
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                @foreach($metodes as $metode)
                    <option value="{{ $metode->id }}">{{ $metode->nama_metode }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Teknisi (Khusus KASBON)</label>
            <select name="user_id" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">-- Bukan Kasbon (Abaikan) --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Keterangan / Deskripsi</label>
            <textarea name="keterangan" rows="3" required placeholder="Contoh: Pembayaran internet bulanan a.n Budi"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
        </div>

        <!-- BAGIAN INPUT NOMINAL DENGAN KALKULATOR OTOMATIS -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #2c3e50;">
                Nominal Transaksi (Bisa pakai rumus) <span style="color:red;">*</span>
            </label>
            <p style="font-size: 11.5px; color: #7f8c8d; margin-bottom: 8px; line-height: 1.4;">
                Anda bisa langsung mengetik angka total (contoh: <b>2600000</b>) atau menjumlahkan rinciannya dengan rumus
                matematika (contoh: <b>4*100000 + 20*110000</b>). Sistem akan otomatis menghitungnya.
            </p>

            <div style="display: flex; align-items: center;">
                <span
                    style="padding: 10px 15px; background: #e9ecef; border: 1px solid #ced4da; border-right: none; border-radius: 4px 0 0 4px; font-weight: bold; color: #495057;">
                    Rp
                </span>

                <!-- Input yang dilihat dan diketik user -->
                <input type="text" id="input_rumus_nominal" class="form-control"
                    placeholder="Contoh rumus: 4*100000 + 20*110000" value="{{ old('nominal') }}"
                    style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 0 4px 4px 0; font-size: 14px;"
                    autocomplete="off" required>

                <!-- Input tersembunyi yang akan dikirim ke Controller Laravel -->
                <input type="hidden" name="nominal" id="nominal_asli" value="{{ old('nominal') }}" required>
            </div>

            <!-- Teks hasil kalkulasi otomatis -->
            <div id="tampil_hasil"
                style="margin-top: 8px; font-size: 15px; font-weight: 800; color: #27ae60; display: none;">
                = Rp 0
            </div>
        </div>

        <button type="submit"
            style="width: 100%; padding: 12px; background-color: #2ecc71; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer;">
            Simpan Transaksi
        </button>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let inputRumus = document.getElementById('input_rumus_nominal');
            let hiddenNominal = document.getElementById('nominal_asli');
            let tampilHasil = document.getElementById('tampil_hasil');

            function kalkulasiOtomatis() {
                let inputVal = inputRumus.value;

                // 1. Cegah huruf masuk (hanya izinkan angka dan simbol matematika: + - * / ( ) )
                let sanitized = inputVal.replace(/[^0-9+\-*/(). ]/g, '');

                // Jika user mencoba mengetik huruf, langsung bersihkan
                if (sanitized !== inputVal) {
                    inputRumus.value = sanitized;
                }

                // Jika kosong, sembunyikan hasil
                if (sanitized.trim() === '') {
                    tampilHasil.style.display = 'none';
                    hiddenNominal.value = '';
                    return;
                }

                // 2. Coba evaluasi rumusnya
                try {
                    // Gunakan Function constructor untuk membaca operasi hitungan murni
                    let hitung = new Function('return ' + sanitized)();

                    // Cek jika hasil hitungan sukses berbentuk angka
                    if (hitung !== undefined && !isNaN(hitung) && isFinite(hitung)) {
                        hiddenNominal.value = hitung; // Masukkan angka bulat ke database (misal: 2600000)
                        tampilHasil.innerHTML = '= Rp ' + hitung.toLocaleString('id-ID'); // Munculkan: = Rp 2.600.000
                        tampilHasil.style.display = 'block';
                    } else {
                        tampilHasil.style.display = 'none';
                        hiddenNominal.value = '';
                    }
                } catch (error) {
                    // Abaikan error di background saat user sedang proses mengetik setengah jalan (misal ngetik "4*")
                    tampilHasil.style.display = 'none';
                    hiddenNominal.value = '';
                }
            }

            // Jalankan setiap kali ada ketikan baru
            inputRumus.addEventListener('input', kalkulasiOtomatis);

            // Jika ada error validasi Laravel dan halaman reload membawa old() data, trigger otomatis
            if (inputRumus.value !== '') {
                kalkulasiOtomatis();
            }
        });
    </script>
@endsection
@extends('layouts.app')
@section('title', 'Edit Area')
@section('content')
    <div class="header"><h2>Edit Nama Area</h2></div>
    <form action="{{ url('/laporan/master-area/update/'.$area->id) }}" method="POST" style="max-width: 500px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px;">
        @csrf
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Area</label>
            <input type="text" name="nama_area" value="{{ $area->nama_area }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <button type="submit" style="background: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer;">Simpan Perubahan</button>
        <a href="{{ url('/laporan/master-area') }}" style="margin-left: 10px; text-decoration: none; color: #7f8c8d;">Kembali</a>
    </form>
@endsection
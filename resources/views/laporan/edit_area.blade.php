@extends('layouts.app')
@section('title', 'Edit Area')
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
                max-width: 600px;
                background: #fff;
                padding: 35px 40px;
                border-radius: 12px;
                box-shadow: 0 6px 20px rgba(0,0,0,0.06);
            ">
            <h2 style="
                    font-size: 22px;
                    font-weight: 600;
                    color: #2c3e50;
                    margin-top: 0;
                    margin-bottom: 25px;
                    text-align: center;
                    border-bottom: 2px solid #ecf0f1;
                    padding-bottom: 15px;
                ">
                Edit Nama Area
            </h2>

            <form action="{{ url('/laporan/master-area/update/' . $area->id) }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; color: #34495e;">Nama
                        Area</label>
                    <input type="text" name="nama_area" value="{{ $area->nama_area }}" required
                        style="width: 100%; padding: 10px 14px; border: 1px solid #dce1e8; border-radius: 6px; font-size: 15px; transition: border-color 0.2s;">
                </div>

                <div style="display: flex; gap: 12px; margin-top: 5px;">
                    <button type="submit" style="
                            background: #3498db;
                            color: white;
                            border: none;
                            padding: 12px 24px;
                            border-radius: 6px;
                            font-weight: 600;
                            font-size: 15px;
                            cursor: pointer;
                            flex: 1;
                            transition: background 0.2s;
                        " onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                        Simpan Perubahan
                    </button>
                    <a href="{{ url('/laporan/master-area') }}" style="
                            text-decoration: none;
                            background: #ecf0f1;
                            color: #2c3e50;
                            padding: 12px 24px;
                            border-radius: 6px;
                            font-weight: 600;
                            font-size: 15px;
                            text-align: center;
                            flex: 0.5;
                            transition: background 0.2s;
                        " onmouseover="this.style.background='#dde1e6'" onmouseout="this.style.background='#ecf0f1'">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
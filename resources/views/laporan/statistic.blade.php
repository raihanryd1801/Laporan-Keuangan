@extends('layouts.app')
@section('title', 'Statistik Keuangan')

@section('content')
    <div class="container-fluid" style="padding-bottom: 40px;">

        <!-- ROW 1: HEADER + FILTER (sejajar) -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="mb-1 fw-bold">Statistik Keuangan</h2>
                <p class="text-muted mb-0">Pantau grafik pergerakan kas dan proporsi kategori transaksi.</p>
            </div>
            <div class="col-md-6">
                <form action="{{ url('/laporan/statistik') }}" method="GET"
                    class="d-flex flex-nowrap align-items-center justify-content-md-end gap-2 p-2 bg-white rounded shadow-sm border"
                    style="border-color: #e9ecef; border-radius: 10px; overflow-x: auto;">

                    <span class="fw-bold text-secondary me-1" style="font-size: 13px; white-space: nowrap;">Filter:</span>

                    <div class="d-flex align-items-center gap-1">
                        <label for="bulan" class="form-label mb-0"
                            style="font-size: 12px; font-weight: 600; color: #34495e; white-space: nowrap;">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select form-select-sm"
                            style="min-width: 100px; border-radius: 6px; cursor: pointer; border: 1px solid #dce1e8; padding: 4px 8px; background: #f8f9fa; font-size: 13px;"
                            onchange="this.form.submit()">
                            <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>Semua</option>
                            <option value="01" {{ $bulan == '01' ? 'selected' : '' }}>Januari</option>
                            <option value="02" {{ $bulan == '02' ? 'selected' : '' }}>Februari</option>
                            <option value="03" {{ $bulan == '03' ? 'selected' : '' }}>Maret</option>
                            <option value="04" {{ $bulan == '04' ? 'selected' : '' }}>April</option>
                            <option value="05" {{ $bulan == '05' ? 'selected' : '' }}>Mei</option>
                            <option value="06" {{ $bulan == '06' ? 'selected' : '' }}>Juni</option>
                            <option value="07" {{ $bulan == '07' ? 'selected' : '' }}>Juli</option>
                            <option value="08" {{ $bulan == '08' ? 'selected' : '' }}>Agustus</option>
                            <option value="09" {{ $bulan == '09' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ $bulan == '10' ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ $bulan == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ $bulan == '12' ? 'selected' : '' }}>Desember</option>
                        </select>
                    </div>

                    <div class="d-flex align-items-center gap-1">
                        <label for="tahun" class="form-label mb-0"
                            style="font-size: 12px; font-weight: 600; color: #34495e; white-space: nowrap;">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select form-select-sm"
                            style="min-width: 80px; border-radius: 6px; cursor: pointer; border: 1px solid #dce1e8; padding: 4px 8px; background: #f8f9fa; font-size: 13px;"
                            onchange="this.form.submit()">
                            @for($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit" style="
                                background: #3498db;
                                color: white;
                                border: none;
                                padding: 5px 14px;
                                border-radius: 6px;
                                font-weight: 600;
                                font-size: 13px;
                                cursor: pointer;
                                transition: all 0.3s ease;
                                white-space: nowrap;
                            " onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                        Terapkan
                    </button>

                    <a href="{{ url('/laporan/statistik') }}" style="
                                background: #ecf0f1;
                                color: #2c3e50;
                                text-decoration: none;
                                padding: 5px 12px;
                                border-radius: 6px;
                                font-weight: 600;
                                font-size: 12px;
                                transition: all 0.3s ease;
                                white-space: nowrap;
                            " onmouseover="this.style.background='#dde1e6'" onmouseout="this.style.background='#ecf0f1'">
                        Reset
                    </a>
                </form>
            </div>
        </div>

        <!-- ROW 2: KARTU RINGKASAN (3 kolom sama tinggi) -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card p-3 shadow-sm border-0 h-100"
                    style="border-radius: 12px; background: #d4edda; color: #155724;">
                    <h6 class="text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem; font-weight: 600;">Total
                        Pemasukan</h6>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card p-3 shadow-sm border-0 h-100"
                    style="border-radius: 12px; background: #f8d7da; color: #721c24;">
                    <h6 class="text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem; font-weight: 600;">Total
                        Pengeluaran</h6>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 shadow-sm border-0 h-100"
                    style="border-radius: 12px; background: {{ $labaRugi >= 0 ? '#cce5ff' : '#ffe5cc' }}; color: {{ $labaRugi >= 0 ? '#004085' : '#856404' }};">
                    <h6 class="text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem; font-weight: 600;">Laba / Rugi
                        Bersih</h6>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($labaRugi, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- ROW 3: BAR CHART -->
        <div class="card shadow-sm p-4 mb-4" style="border-radius: 12px; border: 1px solid #e9ecef;">
            <h5 class="mb-4 fw-bold text-secondary">
                {{ $bulan == 'all' ? 'Grafik Pemasukan vs Pengeluaran per Bulan' : 'Grafik Pergerakan Harian' }}
            </h5>
            <div style="height: 350px; width: 100%;">
                <canvas id="keuanganChart"></canvas>
            </div>
        </div>

        <!-- ROW 4: PIE CHARTS -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm p-4 h-100" style="border-radius: 12px; border: 1px solid #e9ecef;">
                    <h5 class="mb-4 fw-bold text-success text-center">Distribusi Pemasukan per Kategori</h5>
                    <div style="height: 280px; width: 100%; position: relative;">
                        @if(count($piePemasukan) > 0)
                            <canvas id="piePemasukan"></canvas>
                        @else
                            <div class="d-flex h-100 justify-content-center align-items-center text-muted">Belum ada data
                                pemasukan</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm p-4 h-100" style="border-radius: 12px; border: 1px solid #e9ecef;">
                    <h5 class="mb-4 fw-bold text-danger text-center">Distribusi Pengeluaran per Kategori</h5>
                    <div style="height: 280px; width: 100%; position: relative;">
                        @if(count($piePengeluaran) > 0)
                            <canvas id="piePengeluaran"></canvas>
                        @else
                            <div class="d-flex h-100 justify-content-center align-items-center text-muted">Belum ada data
                                pengeluaran</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- CDN Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // BAR CHART
            const ctxBar = document.getElementById('keuanganChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: {!! json_encode($pemasukanData) !!},
                            backgroundColor: '#2ecc71',
                            borderRadius: 4
                        },
                        {
                            label: 'Pengeluaran',
                            data: {!! json_encode($pengeluaranData) !!},
                            backgroundColor: '#e74c3c',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                            }
                        }
                    }
                }
            });

            const colorPalette = ['#3498db', '#f1c40f', '#9b59b6', '#e67e22', '#1abc9c', '#34495e', '#ff9ff3', '#feca57', '#48dbfb', '#ff6b6b'];

            // PIE PEMASUKAN
            @if(count($piePemasukan) > 0)
                const ctxPieIn = document.getElementById('piePemasukan').getContext('2d');
                new Chart(ctxPieIn, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode(array_keys($piePemasukan)) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($piePemasukan)) !!},
                            backgroundColor: colorPalette,
                            borderWidth: 2,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: { position: 'right', labels: { boxWidth: 12 } },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return ' ' + context.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed);
                                    }
                                }
                            }
                        }
                    }
                });
            @endif

                // PIE PENGELUARAN
                @if(count($piePengeluaran) > 0)
                    const ctxPieOut = document.getElementById('piePengeluaran').getContext('2d');
                    new Chart(ctxPieOut, {
                        type: 'doughnut',
                        data: {
                            labels: {!! json_encode(array_keys($piePengeluaran)) !!},
                            datasets: [{
                                data: {!! json_encode(array_values($piePengeluaran)) !!},
                                backgroundColor: colorPalette,
                                borderWidth: 2,
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '60%',
                            plugins: {
                                legend: { position: 'right', labels: { boxWidth: 12 } },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            return ' ' + context.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed);
                                        }
                                    }
                                }
                            }
                        }
                    });
                @endif

                });
    </script>
@endsection
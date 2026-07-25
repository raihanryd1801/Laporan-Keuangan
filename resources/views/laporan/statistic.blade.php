@extends('layouts.app')
@section('title', 'Statistik Keuangan')

@section('content')
    <div class="container-fluid" style="padding-bottom: 40px;">

        <!-- HEADER & FILTER (Dibuat Responsive & Rapi) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="mb-1 fw-bold"> Statistik Keuangan</h2>
                <p class="text-muted mb-0">Pantau grafik pergerakan kas dan proporsi kategori transaksi.</p>
            </div>

            <!-- Filter Form -->
            <form action="{{ url('/laporan/statistik') }}" method="GET"
                class="d-flex flex-wrap gap-2 align-items-center p-2 bg-white rounded shadow-sm border">
                <span class="fw-bold text-secondary px-2" style="font-size: 14px;">Filter:</span>

                <select name="bulan" class="form-select form-select-sm"
                    style="min-width: 140px; border-radius: 6px; cursor: pointer;" onchange="this.form.submit()">
                    <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>Semua Bulan</option>
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

                <select name="tahun" class="form-select form-select-sm"
                    style="min-width: 110px; border-radius: 6px; cursor: pointer;" onchange="this.form.submit()">
                    @for($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>Tahun {{ $i }}</option>
                    @endfor
                </select>
            </form>
        </div>

        <!-- 3 Kartu Ringkasan -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card p-3 shadow-sm border-0 bg-success text-white" style="border-radius: 12px;">
                    <h6 class="text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Total Pemasukan</h6>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card p-3 shadow-sm border-0 bg-danger text-white" style="border-radius: 12px;">
                    <h6 class="text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Total Pengeluaran</h6>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 shadow-sm border-0 {{ $labaRugi >= 0 ? 'bg-primary' : 'bg-warning' }} text-white"
                    style="border-radius: 12px;">
                    <h6 class="text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Laba / Rugi Bersih</h6>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($labaRugi, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- BAR CHART (Bulan/Hari) -->
        <div class="card shadow-sm border-0 p-4 mb-4" style="border-radius: 12px;">
            <h5 class="mb-4 fw-bold text-secondary">
                {{ $bulan == 'all' ? 'Grafik Pemasukan vs Pengeluaran per Bulan' : 'Grafik Pergerakan Harian' }}
            </h5>
            <div style="height: 350px; width: 100%;">
                <canvas id="keuanganChart"></canvas>
            </div>
        </div>

        <!-- ROW UNTUK DOUGHNUT/PIE CHART (Per Kategori) -->
        <div class="row">
            <!-- Pie Pemasukan -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 p-4 h-100" style="border-radius: 12px;">
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

            <!-- Pie Pengeluaran -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 p-4 h-100" style="border-radius: 12px;">
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

            // --- 1. SETTING BAR CHART ---
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

            // --- PALET WARNA UNTUK PIE CHART ---
            const colorPalette = ['#3498db', '#f1c40f', '#9b59b6', '#e67e22', '#1abc9c', '#34495e', '#ff9ff3', '#feca57', '#48dbfb', '#ff6b6b'];

            // --- 2. SETTING DOUGHNUT CHART (PEMASUKAN) ---
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

                // --- 3. SETTING DOUGHNUT CHART (PENGELUARAN) ---
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
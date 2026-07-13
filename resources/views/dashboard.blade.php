@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Dashboard Statistik</h1>
        <span class="text-muted small">Tahun Ajaran Aktif: {{ date('Y') }}/{{ date('Y')+1 }}</span>
    </div>

    <!-- ROW 1: Summary Cards -->
    <div class="row">
        <!-- Card Total Siswa -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Siswa Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSiswa }} Anak</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Total Guru -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tenaga Pendidik</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalGuru }} Guru</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Total Kelas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rombongan Belajar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKelas }} Kelas</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Total Alumni -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Alumni</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAlumni }} Lulusan</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 2: Charts -->
    <div class="row">
        <!-- Bar Chart: Persebaran Siswa Per Kelas -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow h-100" style="border-radius: 10px;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white" style="border-radius: 10px 10px 0 0;">
                    <h6 class="m-0 font-weight-bold text-primary">Kepadatan Siswa per Kelas</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="height: 300px;">
                        <canvas id="barKelas"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <!-- Donut Chart: Status SPP Bulan Ini -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow h-100" style="border-radius: 10px;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white" style="border-radius: 10px 10px 0 0;">
                    <h6 class="m-0 font-weight-bold text-success">SPP Bulan Ini ({{ \Carbon\Carbon::now()->translatedFormat('F Y') }})</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="height: 240px;">
                        <canvas id="pieSpp"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Sudah Bayar ({{ $siswaSudahBayarSpp }})
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Belum ({{ $siswaBelumBayarSpp }})
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donut Chart: Gender -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4" style="border-radius: 10px;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white" style="border-radius: 10px 10px 0 0;">
                    <h6 class="m-0 font-weight-bold text-primary">Rasio Jenis Kelamin</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="height: 240px;">
                        <canvas id="pieGender"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Laki-laki ({{ $siswaLaki }})
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Perempuan ({{ $siswaPerempuan }})
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Pastikan script Chart.js sudah diload di layout utama Anda (layouts.app), jika belum, uncomment baris di bawah ini -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
    // Set default font family untuk Chart.js agar serasi dengan SB Admin
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // 1. Inisialisasi Donut Chart (Gender)
    var ctxPie = document.getElementById("pieGender");
    var pieGender = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ["Laki-laki", "Perempuan"],
            datasets: [{
                data: [{{ $siswaLaki }}, {{ $siswaPerempuan }}],
                backgroundColor: ['#4e73df', '#e74a3b'],
                hoverBackgroundColor: ['#2e59d9', '#be2617'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 70,
        },
    });

    // 2. Inisialisasi Bar Chart (Siswa per Kelas)
    var ctxBar = document.getElementById("barKelas");
    var barKelas = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labelKelas) !!},
            datasets: [{
                label: "Jumlah Siswa",
                backgroundColor: "#1cc88a",
                hoverBackgroundColor: "#17a673",
                borderColor: "#1cc88a",
                data: {!! json_encode($dataSiswaPerKelas) !!},
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: { left: 10, right: 25, top: 25, bottom: 0 }
            },
            scales: {
                xAxes: [{
                    gridLines: { display: false, drawBorder: false },
                    ticks: { maxTicksLimit: 10 }
                }],
                yAxes: [{
                    ticks: { min: 0, maxTicksLimit: 5, padding: 10 },
                    gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                }],
            },
            legend: { display: false },
            tooltips: {
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
        }


    });

    // 3. Inisialisasi Donut Chart (Status SPP)
    var ctxSpp = document.getElementById("pieSpp");
    var pieSpp = new Chart(ctxSpp, {
        type: 'doughnut',
        data: {
            labels: ["Sudah Bayar", "Belum Bayar"],
            datasets: [{
                data: [{{ $siswaSudahBayarSpp }}, {{ $siswaBelumBayarSpp }}],
                backgroundColor: ['#1cc88a', '#f6c23e'], // Hijau dan Kuning
                hoverBackgroundColor: ['#17a673', '#dda20a'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 70, // Membuat lubang donat
        },
    });
</script>
@endpush

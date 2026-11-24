@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center flex-wrap">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold mr-3">Dashboard Statistik</h1>
            @hasanyrole('Super Admin|Admin')
            <button type="button" class="btn btn-sm btn-outline-success font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalUpdateAplikasi">
                <i class="fas fa-bullhorn mr-1"></i> Info Pembaruan
            </button>
            @endhasanyrole
        </div>
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


        <!-- Donut Chart: Status SPP -->
        <div class="col-xl-4 col-lg-5 mb-4" id="card-spp">
            <div class="card shadow h-100" style="border-radius: 10px;">
                <div class="card-header py-3 bg-white" style="border-radius: 10px 10px 0 0;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-wallet mr-1"></i> SPP: {{ $periodeBulanIni }}
                        </h6>
                    </div>
                    {{-- Filter Cepat Bulan/Tahun SPP --}}
                    <form method="GET" action="{{ route('dashboard') }}#card-spp" class="form-row align-items-center">
                        <div class="col-6">
                            <select name="bulan" class="form-control form-control-sm" onchange="this.form.submit()">
                                @foreach($daftarBulanIndo as $num => $namaBln)
                                    <option value="{{ $num }}" {{ $num == $bulanDipilih ? 'selected' : '' }}>
                                        {{ $namaBln }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <select name="tahun" class="form-control form-control-sm" onchange="this.form.submit()">
                                @foreach($daftarTahunPilihan as $thn)
                                    <option value="{{ $thn }}" {{ $thn == $tahunDipilih ? 'selected' : '' }}>
                                        {{ $thn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-2 pb-2" style="height: 220px;">
                        <canvas id="pieSpp"></canvas>
                    </div>
                    <div class="mt-3 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Sudah Lunas ({{ $siswaSudahBayarSpp }})
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Belum Lunas ({{ $siswaBelumBayarSpp }})
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

    {{-- Modal Pop-up Update Pembaharuan Aplikasi (Khusus Admin) --}}
    @hasanyrole('Super Admin|Admin')
    <div class="modal fade" id="modalUpdateAplikasi" tabindex="-1" role="dialog" aria-labelledby="modalUpdateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 16px; overflow: hidden;">
                {{-- Header Modal --}}
                <div class="modal-header bg-white px-4 py-3 border-bottom d-flex align-items-center justify-content-between" style="border-color: #f1f5f9 !important;">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center justify-content-center mr-3 shadow-sm"
                            style="width: 44px; height: 44px; min-width: 44px; border-radius: 12px; background: #ecfdf5; color: #059669;">
                            <i class="fas fa-bullhorn fa-lg"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center flex-wrap">
                                <h5 class="modal-title font-weight-bold text-gray-900 mb-0" id="modalUpdateTitle">
                                    Update Sistem Informasi RA Al Futuh
                                </h5>
                                <span class="badge badge-pill ml-2 px-2 py-1" style="background: #e6f4ea; color: #137333; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.3px;">
                                    TERBARU
                                </span>
                            </div>
                            <small class="text-muted">Ringkasan fitur baru dan penyempurnaan sistem</small>
                        </div>
                    </div>
                    <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none; opacity: 0.6;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="modal-body px-4 py-3" style="background-color: #f8fafc; max-height: 68vh; overflow-y: auto;">
                    <div class="row">
                        {{-- 1. E-Rapor --}}
                        <div class="col-12 mb-2">
                            <div class="card border-0 shadow-sm" style="border-radius: 10px; background: #ffffff; border-left: 3px solid #10b981 !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-light text-success font-weight-bold mr-2 px-2 py-1" style="background: #ecfdf5; font-size: 0.8rem;">
                                                01
                                            </span>
                                            <h6 class="font-weight-bold text-gray-900 mb-0">Fitur E-Rapor</h6>
                                        </div>
                                        <span class="badge badge-pill px-2 py-1" style="background: #e0f2fe; color: #0284c7; font-size: 0.72rem; font-weight: 600;">
                                            Fitur Baru
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-0 pl-1" style="line-height: 1.5;">
                                        Fitur terbaru dari website, digunakan untuk input laporan perkembangan murid dengan mudah dan efisien.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Bayar SPP --}}
                        <div class="col-12 mb-2">
                            <div class="card border-0 shadow-sm" style="border-radius: 10px; background: #ffffff; border-left: 3px solid #3b82f6 !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-light text-primary font-weight-bold mr-2 px-2 py-1" style="background: #eff6ff; font-size: 0.8rem;">
                                                02
                                            </span>
                                            <h6 class="font-weight-bold text-gray-900 mb-0">Fitur Bayar SPP</h6>
                                        </div>
                                        <span class="badge badge-pill px-2 py-1" style="background: #eff6ff; color: #2563eb; font-size: 0.72rem; font-weight: 600;">
                                            Peningkatan
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-0 pl-1" style="line-height: 1.5;">
                                        Saat ini memungkinkan untuk melakukan input pembayaran hingga 5 tahun ke depan dengan proteksi anti duplikasi.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- 3. SPMB (PPDB) --}}
                        <div class="col-12 mb-2">
                            <div class="card border-0 shadow-sm" style="border-radius: 10px; background: #ffffff; border-left: 3px solid #6366f1 !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-light font-weight-bold mr-2 px-2 py-1" style="background: #eef2ff; color: #4f46e5; font-size: 0.8rem;">
                                                03
                                            </span>
                                            <h6 class="font-weight-bold text-gray-900 mb-0">Fitur SPMB (PPDB)</h6>
                                        </div>
                                        <span class="badge badge-pill px-2 py-1" style="background: #eef2ff; color: #4f46e5; font-size: 0.72rem; font-weight: 600;">
                                            Data & Berkas
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-0 pl-1" style="line-height: 1.5;">
                                        Fitur lihat detail data siswa lengkap beserta peninjauan dokumen berkas pendaftaran siswa.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- 4. Komite --}}
                        <div class="col-12 mb-2">
                            <div class="card border-0 shadow-sm" style="border-radius: 10px; background: #ffffff; border-left: 3px solid #0d9488 !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-light font-weight-bold mr-2 px-2 py-1" style="background: #f0fdfa; color: #0d9488; font-size: 0.8rem;">
                                                04
                                            </span>
                                            <h6 class="font-weight-bold text-gray-900 mb-0">Fitur Komite</h6>
                                        </div>
                                        <span class="badge badge-pill px-2 py-1" style="background: #f0fdfa; color: #0f766e; font-size: 0.72rem; font-weight: 600;">
                                            Upload Nota
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-0 pl-1" style="line-height: 1.5;">
                                        Memungkinkan komite untuk mengunggah bukti atau nota transaksi secara langsung ke sistem.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- 5. Data Siswa --}}
                        <div class="col-12 mb-2">
                            <div class="card border-0 shadow-sm" style="border-radius: 10px; background: #ffffff; border-left: 3px solid #64748b !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-light font-weight-bold mr-2 px-2 py-1" style="background: #f8fafc; color: #475569; font-size: 0.8rem;">
                                                05
                                            </span>
                                            <h6 class="font-weight-bold text-gray-900 mb-0">Fitur Data Siswa</h6>
                                        </div>
                                        <span class="badge badge-pill px-2 py-1" style="background: #f1f5f9; color: #475569; font-size: 0.72rem; font-weight: 600;">
                                            Kolom Baru
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-0 pl-1" style="line-height: 1.5;">
                                        Penambahan kolom alamat lengkap pada data induk siswa untuk kelengkapan administrasi.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- 6. Sinkronisasi Data Siswa (Perhatian) --}}
                        <div class="col-12 mb-2">
                            <div class="card border-0 shadow-sm" style="border-radius: 10px; background: #fffbeb; border: 1px solid #fef3c7; border-left: 3px solid #f59e0b !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-light font-weight-bold mr-2 px-2 py-1" style="background: #fef3c7; color: #b45309; font-size: 0.8rem;">
                                                06
                                            </span>
                                            <h6 class="font-weight-bold text-gray-900 mb-0">Sinkronisasi Data Siswa</h6>
                                        </div>
                                        <span class="badge badge-pill px-2 py-1" style="background: #fef3c7; color: #b45309; font-size: 0.72rem; font-weight: 600;">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Penting
                                        </span>
                                    </div>
                                    <p class="small mb-0 pl-1" style="line-height: 1.5; color: #92400e;">
                                        Data siswa harus segera diperbarui dan dirapikan karena tersinkron langsung dengan sistem E-Rapor.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- 7. Tampilan Baru --}}
                        <div class="col-12 mb-1">
                            <div class="card border-0 shadow-sm" style="border-radius: 10px; background: #ffffff; border-left: 3px solid #06b6d4 !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-light font-weight-bold mr-2 px-2 py-1" style="background: #ecfeff; color: #0891b2; font-size: 0.8rem;">
                                                07
                                            </span>
                                            <h6 class="font-weight-bold text-gray-900 mb-0">Tampilan Antarmuka Baru</h6>
                                        </div>
                                        <span class="badge badge-pill px-2 py-1" style="background: #ecfeff; color: #0e7490; font-size: 0.72rem; font-weight: 600;">
                                            UI Refresh
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-0 pl-1" style="line-height: 1.5;">
                                        Penyegaran tampilan visual yang lebih bersih dan modern pada halaman awal serta halaman login.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pesan Penutup --}}
                    <div class="text-center py-2 px-3 mt-3 rounded" style="background: #ffffff; border: 1px dashed #cbd5e1;">
                        <span class="text-muted small" style="font-size: 0.85rem; font-style: italic;">
                            <i class="fas fa-heart text-danger mr-1"></i>
                            Dibuat dengan Cinta biidznillahi taala .. Semoga bermanfaat. aamiin
                        </span>
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="modal-footer bg-white px-4 py-3 d-flex justify-content-between align-items-center border-top" style="border-color: #f1f5f9 !important;">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="chkJanganTampilkan">
                        <label class="custom-control-label small text-muted font-weight-bold" for="chkJanganTampilkan" style="cursor: pointer;">
                            Jangan tampilkan lagi
                        </label>
                    </div>
                    <button type="button" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm" data-dismiss="modal" id="btnTutupUpdate" style="border-radius: 8px;">
                        <i class="fas fa-check mr-1"></i> Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endhasanyrole
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

@hasanyrole('Super Admin|Admin')
<script>
    $(document).ready(function() {
        // Tampilkan modal secara otomatis jika admin belum memilih "Jangan tampilkan lagi"
        if (!localStorage.getItem('alfutuh_update_seen_nov2026')) {
            $('#modalUpdateAplikasi').modal('show');
        }

        // Simpan preferensi jika checkbox dicentang
        $('#btnTutupUpdate').on('click', function() {
            if ($('#chkJanganTampilkan').is(':checked')) {
                localStorage.setItem('alfutuh_update_seen_nov2026', 'true');
            }
        });

        $('#chkJanganTampilkan').on('change', function() {
            if ($(this).is(':checked')) {
                localStorage.setItem('alfutuh_update_seen_nov2026', 'true');
            } else {
                localStorage.removeItem('alfutuh_update_seen_nov2026');
            }
        });
    });
</script>
@endhasanyrole
@endpush

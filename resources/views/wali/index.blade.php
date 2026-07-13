@extends('layouts.app')

@section('title', 'Portal Orang Tua')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Selamat Datang, Bapak/Ibu {{ $orangTua->nama_wali }}</h1>

        <form action="{{ route('wali.dashboard') ?? '#' }}" method="GET" class="d-inline-flex form-inline mt-2 mt-sm-0">
            <select name="bulan" class="form-control form-control-sm mr-2">
                @for($i = 1; $i <= 12; $i++)
                    @php $val = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                    <option value="{{ $val }}" {{ (isset($bulan) && $bulan == $val) ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month((int)$i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
            <select name="tahun" class="form-control form-control-sm mr-2">
                @for($t = date('Y'); $t >= 2023; $t--)
                    <option value="{{ $t }}" {{ (isset($tahun) && $tahun == $t) ? 'selected' : '' }}>{{ $t }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-filter"></i> Filter Absen</button>
        </form>
    </div>
    @if($siswaList->isEmpty())
        <div class="alert alert-warning">
            Belum ada data anak yang dikaitkan dengan akun Anda. Silakan hubungi Tata Usaha.
        </div>
    @else
        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
            @foreach($siswaList as $index => $siswa)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ (request()->has('galeri_'.$siswa->id) || ($index == 0 && empty(request()->except(['bulan', 'tahun'])))) ? 'active' : '' }} font-weight-bold"
                       id="pills-siswa-{{ $siswa->id }}-tab"
                       data-toggle="pill"
                       href="#pills-siswa-{{ $siswa->id }}"
                       role="tab">
                        <i class="fas fa-child"></i> {{ $siswa->nama_siswa }}
                        @if($siswa->status_siswa == 'Lulus') <span class="badge badge-success">Alumni</span> @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="pills-tabContent">
            @foreach($siswaList as $index => $siswa)
                <div class="tab-pane fade {{ (request()->has('galeri_'.$siswa->id) || ($index == 0 && empty(request()->except(['bulan', 'tahun'])))) ? 'show active' : '' }}" id="pills-siswa-{{ $siswa->id }}" role="tabpanel">

                    @php
                        $jmlHadir = isset($siswa->absensi) ? $siswa->absensi->where('status', 'Hadir')->count() : 0;
                        $jmlSakit = isset($siswa->absensi) ? $siswa->absensi->where('status', 'Sakit')->count() : 0;
                        $jmlIzin  = isset($siswa->absensi) ? $siswa->absensi->where('status', 'Izin')->count() : 0;
                        $jmlAlfa  = isset($siswa->absensi) ? $siswa->absensi->where('status', 'Alfa')->count() : 0;

                        // PERBAIKAN CARBON: Tambahkan (int) pada $bulan
                        $namaBulan = isset($bulan) ? \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') : date('F');
                        $tahunAktif = $tahun ?? date('Y');
                    @endphp

                    <div class="card shadow mb-4 border-bottom-primary">
                        <div class="card-header py-3 bg-white">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clipboard-list mr-2"></i>Laporan Kehadiran (Bulan {{ $namaBulan }} {{ $tahunAktif }})</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4 text-center">
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="p-3 bg-light rounded shadow-sm border-left-success">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jmlHadir }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="p-3 bg-light rounded shadow-sm border-left-info">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sakit</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jmlSakit }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="p-3 bg-light rounded shadow-sm border-left-warning">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Izin</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jmlIzin }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="p-3 bg-light rounded shadow-sm border-left-danger">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Alfa</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jmlAlfa }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                <table class="table table-sm table-bordered table-striped mb-0 text-sm">
                                    <thead class="bg-light text-center" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($siswa->absensi) && $siswa->absensi->isNotEmpty())
                                            @foreach($siswa->absensi as $absen)
                                                <tr>
                                                    <td class="text-center font-weight-bold">
                                                        {{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d M Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if($absen->status == 'Hadir')
                                                            <span class="badge badge-success"><i class="fas fa-check"></i> Hadir</span>
                                                        @elseif($absen->status == 'Sakit')
                                                            <span class="badge badge-info"><i class="fas fa-procedures"></i> Sakit</span>
                                                        @elseif($absen->status == 'Izin')
                                                            <span class="badge badge-warning"><i class="fas fa-envelope"></i> Izin</span>
                                                        @else
                                                            <span class="badge badge-danger"><i class="fas fa-times"></i> Alfa</span>
                                                        @endif
                                                    </td>
                                                    <td><span class="text-muted">{{ $absen->keterangan ?? '-' }}</span></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">Belum ada rekaman absensi bulan ini.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-8 col-lg-7">

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-left-success shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Capaian Iqro/Al-Qur'an Terbaru</div>
                                            @if($siswa->perkembanganAlquran->isNotEmpty())
                                                @php $quranLatest = $siswa->perkembanganAlquran->first(); @endphp
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $quranLatest->jilid }} - {{ $quranLatest->halaman }}</div>
                                                <div class="mt-2 text-sm text-muted">Status: <span class="badge badge-info">{{ $quranLatest->status_kelancaran }}</span></div>
                                                <div class="text-xs mt-1">Update: {{ \Carbon\Carbon::parse($quranLatest->tanggal)->format('d M Y') }}</div>
                                            @else
                                                <div class="text-muted small">Belum ada catatan perkembangan.</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card border-left-warning shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Capaian (AISMA) Terbaru</div>
                                            @if($siswa->perkembanganAisma->isNotEmpty())
                                                @php $aismaLatest = $siswa->perkembanganAisma->first(); @endphp
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $aismaLatest->level_jilid }}</div>
                                                <div class="mt-2 text-sm text-muted">Modul: {{ $aismaLatest->halaman_modul }}</div>
                                                <div class="text-xs mt-1">Update: {{ \Carbon\Carbon::parse($aismaLatest->tanggal)->format('d M Y') }}</div>
                                            @else
                                                <div class="text-muted small">Belum ada catatan perkembangan.</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-camera-retro"></i> Galeri Aktivitas & Kenangan</h6>
                                </div>
                                <div class="card-body">

                                    @php
                                        // Panggil data paginasi langsung dari view dengan batasan 4 foto per halaman
                                        // Nama halamannya dipisah per-siswa (galeri_1, galeri_2) agar tidak bentrok
                                        $galeriKenangan = $siswa->aktivitasKenangan()
                                                                ->with('tahunAjaran')
                                                                ->latest()
                                                                ->paginate(4, ['*'], 'galeri_'.$siswa->id);
                                    @endphp

                                    <div class="row">
                                        @forelse($galeriKenangan as $kenangan)
                                            <div class="col-sm-6 mb-4">
                                                <div class="card h-100 bg-light">
                                                    <img src="{{ asset('storage/aktivitas_anak/' . $kenangan->foto_aktivitas) }}" class="card-img-top" style="height: 180px; object-fit: cover; border-radius: 0.35rem 0.35rem 0 0;" alt="Aktivitas">
                                                    <div class="card-body p-3">
                                                        <h6 class="font-weight-bold mb-1">{{ $kenangan->judul_aktivitas }}</h6>
                                                        <p class="text-xs text-muted mb-2">{{ $kenangan->tahunAjaran->nama ?? '' }} - Smt {{ $kenangan->semester }}</p>
                                                        <p class="small mb-0">"{{ $kenangan->narasi_perkembangan }}"</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center text-muted">Belum ada foto aktivitas yang diunggah guru.</div>
                                        @endforelse
                                    </div>

                                    @if($galeriKenangan->hasPages())
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $galeriKenangan->appends(request()->query())->links('pagination::bootstrap-4') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            </div>

                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4 border-left-danger">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-wallet"></i> Informasi Pembayaran</h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="font-weight-bold">SPP yang Telah Lunas:</h6>
                                    @php
                                        // Filter transaksi khusus SPP (kategori 1 sesuai SQL)
                                        $sppTerbayar = $siswa->transaksi->where('kategori_transaksi_id', 1);
                                    @endphp

                                    @if($sppTerbayar->isNotEmpty())
                                        <div class="d-flex flex-wrap mb-4">
                                            @foreach($sppTerbayar as $trxSpp)
                                                <span class="badge badge-success p-2 m-1">
                                                    <i class="fas fa-check-circle"></i> {{ $trxSpp->untuk_bulan }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <p class="text-xs text-muted mb-4">*Bulan yang tidak tercantum di atas berarti belum lunas/menunggak.</p>
                                    @else
                                        <div class="alert alert-secondary text-sm py-2">Belum ada data pembayaran SPP tercatat.</div>
                                    @endif

                                    <hr>

                                    <h6 class="font-weight-bold mt-3">Pembayaran Komponen Lain:</h6>
                                    @php
                                        // Filter transaksi selain SPP
                                        $pembayaranLain = $siswa->transaksi->where('kategori_transaksi_id', '!=', 1);
                                    @endphp

                                    @if($pembayaranLain->isNotEmpty())
                                        <ul class="list-group list-group-flush text-sm">
                                            @foreach($pembayaranLain as $trxLain)
                                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $trxLain->kategoriTransaksi->nama_kategori ?? 'Lainnya' }}</strong><br>
                                                        <span class="text-xs text-muted">{{ \Carbon\Carbon::parse($trxLain->tanggal)->format('d/m/Y') }}</span>
                                                    </div>
                                                    <span class="text-success font-weight-bold">Rp {{ number_format($trxLain->nominal, 0, ',', '.') }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-muted text-sm mt-2">Tidak ada transaksi pembayaran lainnya.</div>
                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

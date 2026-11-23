@extends('layouts.app')

@php
    $nilaiGrouped = $nilaiGrouped ?? $erapor->nilai->groupBy(['kategori_nama', function ($item) {
        return $item->sub_kategori ?? 'Umum';
    }]);
    $tahunAjaranList = $tahunAjaranList ?? \App\Models\TahunAjaran::orderBy('nama', 'desc')->get();
@endphp

@section('title', 'Input e-Rapor: ' . $erapor->siswa->nama_siswa)

@push('css')
<style>
    /* Styling Tabs e-Rapor Nav-Pills agar teks dan ikon selalu terbaca jelas & kontras */
    #eraporTab {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    #eraporTab .nav-item {
        margin: 0;
    }
    #eraporTab .nav-link {
        color: #495057 !important;
        background-color: #f8f9fc;
        border: 1px solid #d1d3e2;
        border-radius: 6px;
        padding: 9px 18px;
        font-weight: 600;
        font-size: 0.92rem;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
    }
    #eraporTab .nav-link:hover {
        background-color: #eaecf4;
        color: #2e384d !important;
        border-color: #b7b9cc;
    }
    /* Saat Tab AKTIF: Teks dan Ikon 100% PUTIH dengan background hijau Al Futuh yang kontras */
    #eraporTab .nav-link.active,
    #eraporTab .show > .nav-link {
        color: #ffffff !important;
        background: linear-gradient(135deg, #1b5e20, #2e7d32) !important;
        border-color: #1b5e20 !important;
        box-shadow: 0 4px 10px rgba(27, 94, 32, 0.35);
    }
    #eraporTab .nav-link.active i,
    #eraporTab .show > .nav-link i,
    #eraporTab .nav-link.active span,
    #eraporTab .show > .nav-link span {
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid mb-5">

    <!-- Top Action Bar -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-file-signature text-success mr-2"></i>Pengisian e-Rapor & Perkembangan
            </h1>
            <p class="text-muted small mb-0">
                Siswa: <strong>{{ $erapor->siswa->nama_siswa }}</strong> &bull; Kelas: {{ $erapor->siswa->kelas->nama_kelas ?? '-' }} &bull; Smt: {{ $erapor->semester }} ({{ $erapor->tahunAjaran->nama ?? '-' }})
            </p>
        </div>
        <div class="mt-3 mt-sm-0">
            <a href="{{ route('erapor.index', ['kelas_id' => $erapor->siswa->kelas_id, 'tahun_ajaran_id' => $erapor->tahun_ajaran_id, 'semester' => $erapor->semester]) }}" class="btn btn-secondary btn-sm shadow-sm mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>

            <!-- Dropdown Cetak -->
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-info dropdown-toggle shadow-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-print mr-1"></i> Cetak Siap Cetak
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow">
                    <a class="dropdown-item" href="{{ route('erapor.cetakIndikator', $erapor->id) }}" target="_blank">
                        <i class="fas fa-file-pdf text-danger mr-2"></i> 1. e-Rapor Indikator (Format Lampiran)
                    </a>
                    <a class="dropdown-item small text-muted pl-4" href="{{ route('erapor.cetakIndikator', $erapor->id) }}?preview=1" target="_blank">
                        <i class="fas fa-eye mr-2"></i> Preview Cetak Indikator
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('erapor.cetakPerkembangan', $erapor->id) }}" target="_blank">
                        <i class="fas fa-images text-success mr-2"></i> 2. Laporan Perkembangan & Foto Bukti
                    </a>
                    <a class="dropdown-item small text-muted pl-4" href="{{ route('erapor.cetakPerkembangan', $erapor->id) }}?preview=1" target="_blank">
                        <i class="fas fa-eye mr-2"></i> Preview Cetak Foto Bukti
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item font-weight-bold" href="{{ route('erapor.cetakLengkap', $erapor->id) }}" target="_blank">
                        <i class="fas fa-book text-primary mr-2"></i> 3. Bundel Lengkap (Semua Halaman)
                    </a>
                    <a class="dropdown-item small text-muted pl-4" href="{{ route('erapor.cetakLengkap', $erapor->id) }}?preview=1" target="_blank">
                        <i class="fas fa-eye mr-2"></i> Preview Bundel Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger shadow-sm">
            <h6 class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-2"></i>Terjadi kesalahan validasi:</h6>
            <ul class="mb-0 pl-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Input e-Rapor -->
    <form action="{{ route('erapor.update', $erapor->id) }}" method="POST" enctype="multipart/form-data" id="formErapor">
        @csrf
        @method('PUT')

        <div class="card shadow">
            <!-- Navigation Tabs -->
            <div class="card-header bg-white py-3">
                <ul class="nav nav-pills card-header-pills" id="eraporTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-identitas-link" data-toggle="pill" href="#tab-identitas" role="tab">
                            <i class="fas fa-id-card mr-1 text-primary"></i> 1. Identitas & Fisik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-indikator-link" data-toggle="pill" href="#tab-indikator" role="tab">
                            <i class="fas fa-check-square mr-1 text-success"></i> 2. Indikator Perkembangan (BB/MB/BSH)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-narasi-link" data-toggle="pill" href="#tab-narasi" role="tab">
                            <i class="fas fa-camera-retro mr-1 text-info"></i> 3. Narasi & Foto Bukti Perkembangan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-absensi-link" data-toggle="pill" href="#tab-absensi" role="tab">
                            <i class="fas fa-calendar-check mr-1 text-warning"></i> 4. Kehadiran & Catatan
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="eraporTabContent">

                    <!-- TAB 1: IDENTITAS & FISIK -->
                    <div class="tab-pane fade show active" id="tab-identitas" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card bg-light mb-4 border-left-success">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-success mb-3"><i class="fas fa-user-graduate mr-1"></i> Biodata Rapor Peserta Didik</h6>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">Nama Lengkap</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="{{ $erapor->siswa->nama_siswa }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">NIS / NISN</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="{{ $erapor->siswa->nis ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">Tempat, Tanggal Lahir</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="{{ $erapor->siswa->tempat_lahir ?? 'Gunungkidul' }}, {{ $erapor->siswa->tanggal_lahir ? \Carbon\Carbon::parse($erapor->siswa->tanggal_lahir)->translatedFormat('d F Y') : '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">Agama</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="agama" class="form-control" value="{{ old('agama', $erapor->agama ?? 'Islam') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">Anak ke-</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="anak_ke" class="form-control" placeholder="Contoh: 3 (tiga)" value="{{ old('anak_ke', $erapor->anak_ke) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">Alamat Peserta Didik</label>
                                            <div class="col-sm-8">
                                                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $erapor->alamat) }}</textarea>
                                            </div>
                                        </div>
                                        <hr>
                                        <h6 class="font-weight-bold text-gray-800 mb-3"><i class="fas fa-users mr-1"></i> Data Orang Tua</h6>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Nama Ayah</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $erapor->nama_ayah) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Agama Ayah</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="agama_ayah" class="form-control" value="{{ old('agama_ayah', $erapor->agama_ayah ?? 'Islam') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Nama Ibu</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $erapor->nama_ibu) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Agama Ibu</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="agama_ibu" class="form-control" value="{{ old('agama_ibu', $erapor->agama_ibu ?? 'Islam') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <!-- Titimangsa & Pengesahan -->
                                <div class="card bg-light mb-4 border-left-info">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-info mb-3"><i class="fas fa-signature mr-1"></i> Pengesahan Rapor</h6>

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Fase Kurikulum</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="fase" class="form-control" value="{{ old('fase', $erapor->fase ?? 'Pondasi') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Tempat Penerbitan</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="tempat_cetak" class="form-control" value="{{ old('tempat_cetak', $erapor->tempat_cetak ?? 'Semin') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Tanggal Rapor</label>
                                            <div class="col-sm-7">
                                                <input type="date" name="tanggal_rapor" class="form-control" value="{{ old('tanggal_rapor', $erapor->tanggal_rapor ? \Carbon\Carbon::parse($erapor->tanggal_rapor)->format('Y-m-d') : date('Y-m-d')) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Kepala RA</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="nama_kepala_sekolah" class="form-control" value="{{ old('nama_kepala_sekolah', $erapor->nama_kepala_sekolah ?? 'Pradita Ovi Agustina, S.Pd') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">NIP Kepala RA</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="nip_kepala_sekolah" class="form-control" value="{{ old('nip_kepala_sekolah', $erapor->nip_kepala_sekolah ?? '-') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Guru / Wali Kelas</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="nama_guru_kelas" class="form-control" value="{{ old('nama_guru_kelas', $erapor->nama_guru_kelas ?? auth()->user()->name) }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">NIP Guru Kelas</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="nip_guru_kelas" class="form-control" value="{{ old('nip_guru_kelas', $erapor->nip_guru_kelas ?? '-') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pemeriksaan Kesehatan Fisik & Pertumbuhan -->
                                <div class="card bg-light mb-4 border-left-warning">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-heartbeat mr-1"></i> Pemeriksaan Kesehatan Fisik & Pertumbuhan</h6>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="small font-weight-bold">Berat Badan</label>
                                                <input type="text" name="berat_badan" class="form-control form-control-sm" placeholder="Contoh: 16 kg" value="{{ old('berat_badan', $erapor->berat_badan) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small font-weight-bold">Tinggi Badan</label>
                                                <input type="text" name="tinggi_badan" class="form-control form-control-sm" placeholder="Contoh: 105 cm" value="{{ old('tinggi_badan', $erapor->tinggi_badan) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small font-weight-bold">Lingkar Kepala</label>
                                                <input type="text" name="lingkar_kepala" class="form-control form-control-sm" placeholder="Contoh: 49 cm" value="{{ old('lingkar_kepala', $erapor->lingkar_kepala) }}">
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered bg-white mb-0">
                                                <thead class="thead-light small text-center">
                                                    <tr>
                                                        <th>Pemeriksaan</th>
                                                        <th width="30%">Penilaian (Baik / Cukup / Kurang)</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="small">
                                                    @php
                                                        $aspekFisik = [
                                                            'status_gizi' => '1. Status Gizi',
                                                            'kondisi_rambut' => '2. Kepala / Rambut',
                                                            'kondisi_mata' => '3. Mata / Penglihatan',
                                                            'kondisi_telinga' => '4. Pendengaran / Telinga',
                                                            'kondisi_hidung' => '5. Hidung',
                                                            'kondisi_mulut' => '6. Mulut',
                                                            'kondisi_kulit' => '7. Kulit',
                                                            'kebersihan_badan' => '8. Kebersihan Badan',
                                                            'kebersihan_pakaian' => '9. Kebersihan Pakaian',
                                                        ];
                                                    @endphp
                                                    @foreach($aspekFisik as $fieldFisik => $labelFisik)
                                                        <tr>
                                                            <td>{{ $labelFisik }}</td>
                                                            <td>
                                                                <select name="{{ $fieldFisik }}" class="form-control form-control-sm">
                                                                    <option value="Baik" {{ old($fieldFisik, $erapor->$fieldFisik) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                                    <option value="Cukup" {{ old($fieldFisik, $erapor->$fieldFisik) == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                                                    <option value="Kurang" {{ old($fieldFisik, $erapor->$fieldFisik) == 'Kurang' ? 'selected' : '' }}>Kurang</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: INDIKATOR PERKEMBANGAN (CHECKLIST) -->
                    <div class="tab-pane fade" id="tab-indikator" role="tabpanel">

                        <!-- Quick Action Toolbar -->
                        <div class="alert alert-info d-flex flex-wrap align-items-center justify-content-between p-3 mb-4 shadow-sm">
                            <div class="mb-2 mb-md-0">
                                <h6 class="font-weight-bold text-info mb-1">
                                    <i class="fas fa-magic mr-1"></i> Manajemen Indikator Perkembangan
                                    <span class="badge badge-success ml-2">Kelas {{ $erapor->siswa->kelas->kelompok ?? $erapor->siswa->kelas->nama_kelas ?? 'A' }}</span>
                                </h6>
                                <p class="mb-0 small text-muted">
                                    Skala: <strong>BB</strong> (Belum Berkembang), <strong>MB</strong> (Mulai Berkembang), <strong>BSH</strong> (Berkembang Sesuai Harapan), <strong>-</strong> (Belum Diberikan).
                                </p>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary shadow-sm mr-1 mb-1" data-toggle="modal" data-target="#modalTambahIndikator">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Indikator Baru
                                </button>
                                <button type="button" class="btn btn-sm btn-warning text-dark shadow-sm mr-1 mb-1" onclick="resetIndikatorStandar()">
                                    <i class="fas fa-sync-alt mr-1"></i> Reset ke Standar Kelas
                                </button>
                                <button type="button" class="btn btn-sm btn-success shadow-sm mr-1 mb-1" onclick="setSemuaNilai('BSH')">
                                    <i class="fas fa-check-double mr-1"></i> Set Semua BSH
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary mb-1" onclick="setSemuaNilai('-')">
                                    <i class="fas fa-undo mr-1"></i> Reset (-)
                                </button>
                            </div>
                        </div>

                        <!-- Render Grouped Indicators -->
                        @foreach($nilaiGrouped as $kategoriNama => $subKategoris)
                            <div class="card mb-4 border-left-success shadow-sm">
                                <div class="card-header bg-success text-white py-2 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold">{{ $kategoriNama }}</h6>
                                    <button type="button" class="btn btn-xs btn-light text-dark font-weight-bold" onclick="setNilaiKategori(this, 'BSH')">
                                        Set Kategori Ini BSH
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    @foreach($subKategoris as $subNama => $items)
                                        @if($subNama !== 'Umum')
                                            <div class="bg-light px-3 py-2 border-bottom font-weight-bold text-primary small">
                                                <i class="fas fa-bookmark mr-1"></i> {{ $subNama }}
                                            </div>
                                        @endif
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover mb-0 tabel-indikator">
                                                <thead class="thead-light small text-center">
                                                    <tr>
                                                        <th width="10%">Kode</th>
                                                        <th>Indikator Perkembangan</th>
                                                        <th width="28%">Penilaian (Semester {{ $erapor->semester }})</th>
                                                        <th width="7%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="small">
                                                    @foreach($items as $item)
                                                        <tr>
                                                            <td class="text-center font-weight-bold text-secondary">{{ $item->kode }}</td>
                                                            <td>{{ $item->indikator }}</td>
                                                            <td class="text-center">
                                                                <div class="btn-group btn-group-toggle btn-group-sm" data-toggle="buttons">
                                                                    <label class="btn btn-outline-danger btn-sm py-1 px-2 {{ $item->nilai == 'BB' ? 'active' : '' }}">
                                                                        <input type="radio" name="nilai[{{ $item->id }}]" value="BB" {{ $item->nilai == 'BB' ? 'checked' : '' }}> BB
                                                                    </label>
                                                                    <label class="btn btn-outline-warning btn-sm py-1 px-2 text-dark {{ $item->nilai == 'MB' ? 'active' : '' }}">
                                                                        <input type="radio" name="nilai[{{ $item->id }}]" value="MB" {{ $item->nilai == 'MB' ? 'checked' : '' }}> MB
                                                                    </label>
                                                                    <label class="btn btn-outline-success btn-sm py-1 px-2 {{ $item->nilai == 'BSH' ? 'active' : '' }}">
                                                                        <input type="radio" name="nilai[{{ $item->id }}]" value="BSH" {{ $item->nilai == 'BSH' ? 'checked' : '' }}> BSH
                                                                    </label>
                                                                    <label class="btn btn-outline-secondary btn-sm py-1 px-2 {{ $item->nilai == '-' || empty($item->nilai) ? 'active' : '' }}">
                                                                        <input type="radio" name="nilai[{{ $item->id }}]" value="-" {{ $item->nilai == '-' || empty($item->nilai) ? 'checked' : '' }}> -
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" title="Hapus indikator ini" onclick="hapusButirIndikator({{ $item->id }}, '{{ addslashes($item->indikator) }}')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- TAB 3: NARASI & FOTO BUKTI PERKEMBANGAN -->
                    <div class="tab-pane fade" id="tab-narasi" role="tabpanel">

                        <div class="alert alert-primary shadow-sm mb-4">
                            <h6 class="font-weight-bold text-primary mb-1">
                                <i class="fas fa-info-circle mr-1"></i> Format Laporan Perkembangan Anak (Sesuai Lampiran 4)
                            </h6>
                            <p class="mb-0 small">
                                Tulis narasi deskriptif perkembangan anak untuk masing-masing elemen capaian pembelajaran, dan lampirkan foto dokumentasi kegiatan nyata anak. Foto akan otomatis dikompresi agar rapi dan ringan saat dicetak.
                            </p>
                        </div>

                        <!-- 1. NILAI AGAMA ISLAM DAN BUDI PEKERTI -->
                        <div class="card mb-4 border-left-success shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 font-weight-bold text-success">
                                    <i class="fas fa-mosque mr-2"></i> 1. Nilai Agama Islam dan Budi Pekerti
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label class="font-weight-bold small">Narasi Deskriptif Perkembangan</label>
                                        <textarea name="narasi_agama" class="form-control mb-3" rows="7" placeholder="Contoh: الْحَمْدُ لِلَّٰهِ رَبِّ ٱلْعَالَمِينَ pada semester ini, Ananda menunjukkan perkembangan yang baik pada nilai karakter Cinta Allah dan Rasul-Nya... Ananda hafal surat pendek (At-Takatsur, Al-Qori'ah), hadits sederhana, dan doa harian. Di semester selanjutnya dapat ditingkatkan hafalan QS Al-Adiyat...">{{ old('narasi_agama', $erapor->narasi_agama) }}</textarea>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold small">Foto Bukti Kegiatan 1</label>
                                        <input type="file" name="foto_agama_1" class="form-control-file mb-2" accept="image/*">
                                        @if($erapor->foto_agama_1)
                                            <div class="mb-3 border p-1 rounded bg-light">
                                                <img src="{{ asset('storage/erapor_foto/' . $erapor->foto_agama_1) }}" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;" alt="Foto 1">
                                                <small class="d-block text-muted">Foto saat ini</small>
                                            </div>
                                        @endif

                                        <label class="font-weight-bold small mt-2">Foto Bukti Kegiatan 2 (Opsional)</label>
                                        <input type="file" name="foto_agama_2" class="form-control-file mb-2" accept="image/*">
                                        @if($erapor->foto_agama_2)
                                            <div class="mb-3 border p-1 rounded bg-light">
                                                <img src="{{ asset('storage/erapor_foto/' . $erapor->foto_agama_2) }}" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;" alt="Foto 2">
                                                <small class="d-block text-muted">Foto saat ini</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. JATI DIRI -->
                        <div class="card mb-4 border-left-info shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 font-weight-bold text-info">
                                    <i class="fas fa-heart mr-2"></i> 2. Jati Diri
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label class="font-weight-bold small">Narasi Deskriptif Perkembangan</label>
                                        <textarea name="narasi_jati_diri" class="form-control mb-3" rows="7" placeholder="Contoh: Ananda menunjukkan perkembangan yang baik dalam nilai karakter Cinta Diri Sendiri dan Sesama. Ananda terbiasa bertanggung jawab menyelesaikan kegiatan sampai tuntas dan merapikan mainan ke tempat semula... Ananda juga suka menolong teman yang sedang kesulitan...">{{ old('narasi_jati_diri', $erapor->narasi_jati_diri) }}</textarea>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold small">Foto Bukti Kegiatan 1</label>
                                        <input type="file" name="foto_jati_diri_1" class="form-control-file mb-2" accept="image/*">
                                        @if($erapor->foto_jati_diri_1)
                                            <div class="mb-3 border p-1 rounded bg-light">
                                                <img src="{{ asset('storage/erapor_foto/' . $erapor->foto_jati_diri_1) }}" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;" alt="Foto 1">
                                                <small class="d-block text-muted">Foto saat ini</small>
                                            </div>
                                        @endif

                                        <label class="font-weight-bold small mt-2">Foto Bukti Kegiatan 2 (Opsional)</label>
                                        <input type="file" name="foto_jati_diri_2" class="form-control-file mb-2" accept="image/*">
                                        @if($erapor->foto_jati_diri_2)
                                            <div class="mb-3 border p-1 rounded bg-light">
                                                <img src="{{ asset('storage/erapor_foto/' . $erapor->foto_jati_diri_2) }}" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;" alt="Foto 2">
                                                <small class="d-block text-muted">Foto saat ini</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. DASAR-DASAR LITERASI, STEAM -->
                        <div class="card mb-4 border-left-primary shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-palette mr-2"></i> 3. Dasar-dasar Literasi, Matematika, Sains, Teknologi, Rekayasa, Seni
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label class="font-weight-bold small">Narasi Deskriptif Perkembangan</label>
                                        <textarea name="narasi_literasi_steam" class="form-control mb-3" rows="7" placeholder="Contoh: Alhamdulillah Ananda sudah menunjukkan perkembangan yang sangat baik dalam elemen Dasar-dasar Literasi, Matematika, Sains, Teknologi, Rekayasa, dan Seni. Ananda mampu mengenali bentuk, menyusun huruf A-Z, dan membuat karya dengan media loose parts secara kreatif (misal: membuat figura taman bunga)...">{{ old('narasi_literasi_steam', $erapor->narasi_literasi_steam) }}</textarea>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold small">Foto Bukti Kegiatan 1</label>
                                        <input type="file" name="foto_literasi_steam_1" class="form-control-file mb-2" accept="image/*">
                                        @if($erapor->foto_literasi_steam_1)
                                            <div class="mb-3 border p-1 rounded bg-light">
                                                <img src="{{ asset('storage/erapor_foto/' . $erapor->foto_literasi_steam_1) }}" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;" alt="Foto 1">
                                                <small class="d-block text-muted">Foto saat ini</small>
                                            </div>
                                        @endif

                                        <label class="font-weight-bold small mt-2">Foto Bukti Kegiatan 2 (Opsional)</label>
                                        <input type="file" name="foto_literasi_steam_2" class="form-control-file mb-2" accept="image/*">
                                        @if($erapor->foto_literasi_steam_2)
                                            <div class="mb-3 border p-1 rounded bg-light">
                                                <img src="{{ asset('storage/erapor_foto/' . $erapor->foto_literasi_steam_2) }}" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;" alt="Foto 2">
                                                <small class="d-block text-muted">Foto saat ini</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. KOKURIKULER / PROJEK P5-PPRA -->
                        <div class="card mb-4 border-left-warning shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 font-weight-bold text-dark">
                                    <i class="fas fa-project-diagram mr-2"></i> 4. Kokurikuler / Projek Penguatan Profil Pelajar Pancasila & Rahmatan Lil Alamin (P5-PPRA)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="font-weight-bold small">Tema Projek</label>
                                    <input type="text" name="tema_projek" class="form-control" placeholder="Contoh: Pembelajaran Berbasis Projek tema 'Aku Cinta Indonesia' & 'Gigiku Sehat'" value="{{ old('tema_projek', $erapor->tema_projek) }}">
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label class="font-weight-bold small">Narasi Tahapan Projek & Keterlibatan Anak</label>
                                        <textarea name="narasi_projek" class="form-control mb-3" rows="7" placeholder="Contoh: Dalam kegiatan kokurikuler melalui Pembelajaran Berbasis Projek dengan tema 'Aku Cinta Indonesia', Ananda menunjukkan perkembangan sangat baik. Projek dilaksanakan dalam tiga tahapan (permulaan, pengembangan, kesimpulan)... Ananda antusias berkolaborasi membatik dan mencintai budaya nusantara...">{{ old('narasi_projek', $erapor->narasi_projek) }}</textarea>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold small">Foto Bukti Kegiatan Projek 1</label>
                                        <input type="file" name="foto_projek_1" class="form-control-file mb-2" accept="image/*">
                                        @if($erapor->foto_projek_1)
                                            <div class="mb-3 border p-1 rounded bg-light">
                                                <img src="{{ asset('storage/erapor_foto/' . $erapor->foto_projek_1) }}" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;" alt="Foto Projek 1">
                                                <small class="d-block text-muted">Foto saat ini</small>
                                            </div>
                                        @endif

                                        <label class="font-weight-bold small mt-2">Foto Bukti Kegiatan Projek 2 (Opsional)</label>
                                        <input type="file" name="foto_projek_2" class="form-control-file mb-2" accept="image/*">
                                        @if($erapor->foto_projek_2)
                                            <div class="mb-3 border p-1 rounded bg-light">
                                                <img src="{{ asset('storage/erapor_foto/' . $erapor->foto_projek_2) }}" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;" alt="Foto Projek 2">
                                                <small class="d-block text-muted">Foto saat ini</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- TAB 4: KEHADIRAN & CATATAN -->
                    <div class="tab-pane fade" id="tab-absensi" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card bg-light mb-4 border-left-danger">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-danger mb-3"><i class="fas fa-calendar-times mr-1"></i> Rekapitulasi Ketidakhadiran</h6>
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label font-weight-bold">Sakit (Hari)</label>
                                            <div class="col-sm-6">
                                                <input type="number" name="kehadiran_sakit" class="form-control" min="0" value="{{ old('kehadiran_sakit', $erapor->kehadiran_sakit ?? 0) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label font-weight-bold">Izin (Hari)</label>
                                            <div class="col-sm-6">
                                                <input type="number" name="kehadiran_izin" class="form-control" min="0" value="{{ old('kehadiran_izin', $erapor->kehadiran_izin ?? 0) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label font-weight-bold">Tanpa Keterangan (Hari)</label>
                                            <div class="col-sm-6">
                                                <input type="number" name="kehadiran_alfa" class="form-control" min="0" value="{{ old('kehadiran_alfa', $erapor->kehadiran_alfa ?? 0) }}">
                                            </div>
                                        </div>
                                        <small class="text-muted font-italic">*Angka ini otomatis terisi dari catatan absensi dan dapat disesuaikan jika diperlukan.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card bg-light mb-4 border-left-primary">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-comment-alt mr-1"></i> Catatan & Status e-Rapor</h6>

                                        <div class="form-group">
                                            <label class="font-weight-bold small">Catatan Guru / Wali Kelas</label>
                                            <textarea name="catatan_guru" class="form-control" rows="4" placeholder="Tulis catatan dan motivasi untuk anak dan orang tua...">{{ old('catatan_guru', $erapor->catatan_guru) }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold small">Umpan Balik Orang Tua / Wali (Opsional)</label>
                                            <textarea name="umpan_balik_ortu" class="form-control" rows="2" placeholder="Tanggapan orang tua...">{{ old('umpan_balik_ortu', $erapor->umpan_balik_ortu) }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold small">Status Publikasi e-Rapor</label>
                                            <select name="status" class="form-control font-weight-bold">
                                                <option value="Draft" {{ old('status', $erapor->status) == 'Draft' ? 'selected' : '' }}>
                                                    Draft (Sedang Dikerjakan)
                                                </option>
                                                <option value="Final" {{ old('status', $erapor->status) == 'Final' ? 'selected' : '' }}>
                                                    Final (Siap Cetak & Diterbitkan)
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Card Footer: Save Button -->
            <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('erapor.index', ['kelas_id' => $erapor->siswa->kelas_id, 'tahun_ajaran_id' => $erapor->tahun_ajaran_id, 'semester' => $erapor->semester]) }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>

                <button type="submit" class="btn btn-success px-4 font-weight-bold shadow">
                    <i class="fas fa-save mr-1"></i> Simpan Semua Perubahan
                </button>
            </div>
        </div>

    </form>
</div>

<!-- Form Hapus Indikator -->
<form id="formHapusIndikator" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Form Reset Indikator ke Standar Kelas -->
<form id="formResetIndikator" action="{{ route('erapor.resetIndikator', $erapor->id) }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Modal Tambah Indikator Baru -->
<div class="modal fade" id="modalTambahIndikator" tabindex="-1" role="dialog" aria-labelledby="modalTambahIndikatorLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('erapor.tambahIndikator', $erapor->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="modalTambahIndikatorLabel">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Indikator Baru
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold small">Pilih Kategori Utama</label>
                        <select name="kategori_kode" class="form-control" id="selectKategoriKode" required>
                            <option value="A">A. NILAI AGAMA DAN MORAL (NAM)</option>
                            <option value="B">B. FISIK MOTORIK</option>
                            <option value="C">C. KOGNITIF</option>
                            <option value="D">D. BAHASA</option>
                            <option value="E">E. SOSIAL EMOSIONAL</option>
                            <option value="F">F. SENI</option>
                            <option value="G">G. MUATAN LOKAL / CIRI KHAS</option>
                        </select>
                    </div>

                    <div class="form-group" id="groupSubKategori">
                        <label class="font-weight-bold small">Sub Kategori (Khusus Agama / Opsional)</label>
                        <select name="sub_kategori" class="form-control" id="selectSubKategori">
                            <option value="">-- Tanpa Sub Kategori --</option>
                            <option value="DAWAAMUL QUR’AN (DQ)" selected>DAWAAMUL QUR’AN (DQ)</option>
                            <option value="MUTIARA HADITS (MH)">MUTIARA HADITS (MH)</option>
                            <option value="DOA - DOA (DD)">DOA - DOA (DD)</option>
                            <option value="DZIKIR (DZ)">DZIKIR (DZ)</option>
                            <option value="AKIDAH (AQ)">AKIDAH (AQ)</option>
                            <option value="AKHLAQ (AK)">AKHLAQ (AK)</option>
                            <option value="FIQIH (FQ)">FIQIH (FQ)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold small">Kode Indikator</label>
                        <input type="text" name="kode" class="form-control" placeholder="Contoh: DQ 17 / MH 12 / DD 13 / 2.1.3" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold small">Nama / Deskripsi Indikator</label>
                        <textarea name="indikator" class="form-control" rows="3" placeholder="Tuliskan nama surah, hadits, doa, atau capaian anak..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold small">Nilai Awal</label>
                        <select name="nilai" class="form-control">
                            <option value="-">- (Belum Dinilai)</option>
                            <option value="BSH" selected>BSH (Berkembang Sesuai Harapan)</option>
                            <option value="MB">MB (Mulai Berkembang)</option>
                            <option value="BB">BB (Belum Berkembang)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Simpan Indikator
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menghapus 1 butir indikator
    function hapusButirIndikator(id, nama) {
        if (confirm('Apakah Anda yakin ingin menghapus indikator:\n"' + nama + '"?')) {
            var form = document.getElementById('formHapusIndikator');
            form.action = "{{ url('erapor/indikator') }}/" + id;
            form.submit();
        }
    }

    // Fungsi untuk mereset seluruh indikator ke standar kelas anak (A atau B)
    function resetIndikatorStandar() {
        if (confirm('PERINGATAN:\nApakah Anda yakin ingin memuat ulang daftar indikator ke standar Kurikulum Kelas {{ $erapor->siswa->kelas->kelompok ?? $erapor->siswa->kelas->nama_kelas ?? "A" }}?\nIndikator akan diperbarui dan nilai akan di-reset ke nilai default.')) {
            document.getElementById('formResetIndikator').submit();
        }
    }

    // Fungsi untuk mengubah semua radio indikator menjadi nilai tertentu (misal: BSH atau -)
    function setSemuaNilai(targetNilai) {
        if (!confirm('Apakah Anda yakin ingin mengatur semua indikator menjadi ' + targetNilai + '?')) {
            return;
        }
        document.querySelectorAll('input[type="radio"][value="' + targetNilai + '"]').forEach(function(radio) {
            radio.checked = true;
            // update label active state
            var parentLabel = radio.closest('label');
            if (parentLabel) {
                var group = parentLabel.closest('.btn-group');
                if (group) {
                    group.querySelectorAll('label').forEach(function(l) { l.classList.remove('active'); });
                    parentLabel.classList.add('active');
                }
            }
        });
    }

    // Fungsi untuk mengubah semua radio dalam 1 kategori kartu menjadi BSH
    function setNilaiKategori(btn, targetNilai) {
        var card = btn.closest('.card');
        if (!card) return;
        card.querySelectorAll('input[type="radio"][value="' + targetNilai + '"]').forEach(function(radio) {
            radio.checked = true;
            var parentLabel = radio.closest('label');
            if (parentLabel) {
                var group = parentLabel.closest('.btn-group');
                if (group) {
                    group.querySelectorAll('label').forEach(function(l) { l.classList.remove('active'); });
                    parentLabel.classList.add('active');
                }
            }
        });
    }
</script>
@endsection

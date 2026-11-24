@extends('layouts.app')
@section('title', 'Detail Pendaftar PPDB - ' . $pendaftaran->no_pendaftaran)

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-circle mr-2 text-info"></i> Detail Pendaftar PPDB
        </h1>
        <a href="{{ route('ppdb.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">

        {{-- Kolom Kiri: Foto & Info Singkat --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4">
                    @if($pendaftaran->foto_url)
                        <img src="{{ $pendaftaran->foto_url }}" alt="Foto Calon Siswa"
                            class="rounded-circle shadow mb-3"
                            style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #d1fae5;">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3 shadow"
                            style="width: 120px; height: 120px; border: 4px solid #d1fae5;">
                            <i class="fas fa-user fa-3x text-muted"></i>
                        </div>
                    @endif

                    <h5 class="font-weight-bold text-dark mb-1">{{ $pendaftaran->nama_lengkap }}</h5>
                    <div class="text-muted small mb-1">Nama Panggilan: <strong>{{ $pendaftaran->nama_panggilan ?? '-' }}</strong></div>
                    <div class="mb-3">
                        @if($pendaftaran->status == 'Pending')
                            <span class="badge badge-warning text-white px-3 py-2">
                                <i class="fas fa-hourglass-half mr-1"></i> Menunggu Proses
                            </span>
                        @elseif($pendaftaran->status == 'Diterima')
                            <span class="badge badge-success px-3 py-2">
                                <i class="fas fa-check-circle mr-1"></i> Diterima sebagai Siswa
                            </span>
                        @else
                            <span class="badge badge-danger px-3 py-2">
                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                            </span>
                        @endif
                    </div>
                    <div class="text-muted small">
                        <i class="fas fa-hashtag mr-1 text-info"></i>
                        <strong>{{ $pendaftaran->no_pendaftaran }}</strong>
                    </div>
                    <div class="text-muted small mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Didaftarkan: {{ \Carbon\Carbon::parse($pendaftaran->created_at)->translatedFormat('d F Y, H:i') }}
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                @if($pendaftaran->status == 'Pending')
                    <div class="card-footer bg-white border-top pt-3 pb-3 d-flex justify-content-around">
                        <form action="{{ route('ppdb.terima', $pendaftaran->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success font-weight-bold px-4"
                                onclick="return confirm('Terima pendaftar ini? Data siswa & akun orang tua akan dibuat otomatis.')">
                                <i class="fas fa-check-circle mr-1"></i> Terima
                            </button>
                        </form>
                        <form action="{{ route('ppdb.tolak', $pendaftaran->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger font-weight-bold px-4"
                                onclick="return confirm('Yakin menolak pendaftar ini?')">
                                <i class="fas fa-times-circle mr-1"></i> Tolak
                            </button>
                        </form>
                    </div>
                @else
                    <div class="card-footer bg-white text-center text-muted small border-top py-3">
                        <i class="fas fa-info-circle mr-1"></i> Pendaftaran ini sudah diproses.
                    </div>
                @endif
            </div>

            {{-- Dokumen --}}
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white py-3 font-weight-bold">
                    <i class="fas fa-file-alt mr-2 text-info"></i> Berkas / Dokumen
                </div>
                <div class="card-body">
                    <a href="{{ $pendaftaran->kk_url ?? '#' }}" target="_blank"
                        class="btn btn-block btn-outline-primary mb-2 {{ $pendaftaran->kk_url ? '' : 'disabled' }}">
                        <i class="fas fa-id-card mr-2"></i> Kartu Keluarga (KK)
                        @if(!$pendaftaran->kk_url) <small class="text-danger ml-1">(tidak ada berkas)</small> @endif
                    </a>
                    <a href="{{ $pendaftaran->akta_url ?? '#' }}" target="_blank"
                        class="btn btn-block btn-outline-info mb-2 {{ $pendaftaran->akta_url ? '' : 'disabled' }}">
                        <i class="fas fa-scroll mr-2"></i> Akta Kelahiran
                        @if(!$pendaftaran->akta_url) <small class="text-danger ml-1">(tidak ada berkas)</small> @endif
                    </a>
                    <a href="{{ $pendaftaran->foto_url ?? '#' }}" target="_blank"
                        class="btn btn-block btn-outline-success {{ $pendaftaran->foto_url ? '' : 'disabled' }}">
                        <i class="fas fa-image mr-2"></i> Foto Calon Siswa
                        @if(!$pendaftaran->foto_url) <small class="text-danger ml-1">(tidak ada berkas)</small> @endif
                    </a>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Data --}}
        <div class="col-lg-8">

            {{-- Data Calon Siswa --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex align-items-center">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-3"
                        style="width: 36px; height: 36px; min-width: 36px;">
                        <i class="fas fa-child"></i>
                    </div>
                    <h6 class="m-0 font-weight-bold text-dark">Data Calon Siswa</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" width="45%">Nama Lengkap</td>
                                    <td width="5%">:</td>
                                    <td><strong>{{ $pendaftaran->nama_lengkap }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama Panggilan</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->nama_panggilan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">NIK / Akta</td>
                                    <td>:</td>
                                    <td><code>{{ $pendaftaran->nik_atau_akta }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>
                                        @if($pendaftaran->jenis_kelamin == 'Laki-laki')
                                            <span class="badge badge-primary"><i class="fas fa-mars mr-1"></i> Laki-laki</span>
                                        @else
                                            <span class="badge badge-danger"><i class="fas fa-venus mr-1"></i> Perempuan</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" width="45%">Tempat Lahir</td>
                                    <td width="5%">:</td>
                                    <td>{{ $pendaftaran->tempat_lahir }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal Lahir</td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Usia</td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->age }} tahun</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Agama</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->agama }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" width="22%">Alamat Lengkap</td>
                                    <td width="2%">:</td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                        {{ $pendaftaran->alamat_lengkap }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Orang Tua --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex align-items-center">
                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center mr-3"
                        style="width: 36px; height: 36px; min-width: 36px;">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6 class="m-0 font-weight-bold text-dark">Data Orang Tua / Wali</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" width="45%">Nama Ayah</td>
                                    <td width="5%">:</td>
                                    <td><strong>{{ $pendaftaran->nama_ayah }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama Ibu</td>
                                    <td>:</td>
                                    <td><strong>{{ $pendaftaran->nama_ibu }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pekerjaan</td>
                                    <td>:</td>
                                    <td>{{ $pendaftaran->pekerjaan_orang_tua ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" width="40%">No. HP / WA</td>
                                    <td width="5%">:</td>
                                    <td>
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pendaftaran->no_hp_ortu) }}" target="_blank" class="text-success font-weight-bold">
                                            <i class="fab fa-whatsapp mr-1"></i>{{ $pendaftaran->no_hp_ortu }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email</td>
                                    <td>:</td>
                                    <td>
                                        <a href="mailto:{{ $pendaftaran->email_ortu }}" class="text-primary">
                                            <i class="fas fa-envelope mr-1"></i>{{ $pendaftaran->email_ortu }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline Pendaftaran --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex align-items-center">
                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center mr-3"
                        style="width: 36px; height: 36px; min-width: 36px;">
                        <i class="fas fa-history"></i>
                    </div>
                    <h6 class="m-0 font-weight-bold text-dark">Info Pendaftaran</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" width="45%">No. Pendaftaran</td>
                                    <td width="5%">:</td>
                                    <td><strong class="text-info">{{ $pendaftaran->no_pendaftaran }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal Daftar</td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($pendaftaran->created_at)->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jam Daftar</td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($pendaftaran->created_at)->format('H:i') }} WIB</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" width="40%">Status Saat Ini</td>
                                    <td width="5%">:</td>
                                    <td>
                                        @if($pendaftaran->status == 'Pending')
                                            <span class="badge badge-warning text-white">Menunggu Verifikasi</span>
                                        @elseif($pendaftaran->status == 'Diterima')
                                            <span class="badge badge-success">Diterima ✓</span>
                                        @else
                                            <span class="badge badge-danger">Ditolak ✗</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Terakhir Diperbarui</td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($pendaftaran->updated_at)->diffForHumans() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($pendaftaran->status == 'Diterima')
                        <div class="alert alert-success mt-3 mb-0 shadow-sm">
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>Pendaftar ini sudah diterima</strong> dan data siswa telah otomatis tersimpan di Master Data Siswa.
                            Orang tua dapat login menggunakan email <strong>{{ $pendaftaran->email_ortu }}</strong>.
                        </div>
                    @elseif($pendaftaran->status == 'Ditolak')
                        <div class="alert alert-danger mt-3 mb-0 shadow-sm">
                            <i class="fas fa-times-circle mr-2"></i>
                            <strong>Pendaftaran ini telah ditolak.</strong>
                            Data tidak diproses ke sistem siswa.
                        </div>
                    @else
                        <div class="alert alert-warning mt-3 mb-0 shadow-sm">
                            <i class="fas fa-hourglass-half mr-2"></i>
                            <strong>Pendaftaran ini masih menunggu verifikasi admin.</strong>
                            Silakan klik tombol <strong>Terima</strong> atau <strong>Tolak</strong> untuk memproses.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

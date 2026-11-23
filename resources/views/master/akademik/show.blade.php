@extends('layouts.app')

@section('title', 'Riwayat Akademik')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Perkembangan: {{ $siswa->nama_siswa }}</h1>
        <div>
            <a href="{{ route('erapor.edit', $siswa->id) }}" class="btn btn-success btn-sm mr-2">
                <i class="fas fa-award"></i> e-Rapor Siap Cetak
            </a>
            <a href="{{ route('master.akademik.cetak', $siswa->id) }}" target="_blank" class="btn btn-primary btn-sm mr-2">
                <i class="fas fa-print"></i> Cetak PDF
            </a>
            <a href="{{ route('master.akademik.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold" id="alquran-tab" data-toggle="tab" href="#alquran" role="tab">Iqro & Al-Qur'an</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold text-warning" id="aisma-tab" data-toggle="tab" href="#aisma" role="tab">AISMA</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold text-primary" id="kenangan-tab" data-toggle="tab" href="#kenangan" role="tab">Foto Kenangan</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active" id="alquran" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Guru</th>
                                    <th>Jenis</th>
                                    <th>Jilid/Halaman</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswa->perkembanganAlquran as $quran)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($quran->tanggal)->format('d M Y') }}</td>
                                        <td>{{ $quran->guru->name ?? '-' }}</td>
                                        <td>{{ $quran->jenis }}</td>
                                        <td>{{ $quran->jilid }} - {{ $quran->halaman }}</td>
                                        <td><span class="badge badge-info">{{ $quran->status_kelancaran }}</span></td>
                                        <td>
                                            <form action="{{ route('master.akademik.destroy-alquran', $quran->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">Belum ada riwayat mengaji.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="aisma" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-warning text-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Guru</th>
                                    <th>Level</th>
                                    <th>Modul</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswa->perkembanganAisma as $aisma)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($aisma->tanggal)->format('d M Y') }}</td>
                                        <td>{{ $aisma->guru->name ?? '-' }}</td>
                                        <td>{{ $aisma->level_jilid }}</td>
                                        <td>{{ $aisma->halaman_modul }}</td>
                                        <td>
                                            <form action="{{ route('master.akademik.destroy-aisma', $aisma->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">Belum ada riwayat AISMA.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="kenangan" role="tabpanel">
                    <div class="row">
                        @forelse($siswa->aktivitasKenangan as $kenangan)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-left-primary shadow-sm">
                                    <img src="{{ asset('storage/aktivitas_anak/' . $kenangan->foto_aktivitas) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Aktivitas">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-primary">{{ $kenangan->judul_aktivitas }}</h6>
                                        <p class="small text-muted mb-1">{{ $kenangan->tahunAjaran->nama_tahun_ajaran ?? '-' }} - Semester {{ $kenangan->semester }}</p>
                                        <p class="small">{{ $kenangan->narasi_perkembangan }}</p>
                                    </div>
                                    <div class="card-footer bg-white border-0 text-right">
                                        <form action="{{ route('master.akademik.destroy-kenangan', $kenangan->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus foto kenangan ini?')"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-3">Belum ada foto kenangan.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

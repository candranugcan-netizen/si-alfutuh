@extends('layouts.app')

@section('title', 'e-Rapor Siap Cetak & Laporan Perkembangan')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">e-Rapor Siap Cetak & Laporan Perkembangan</h1>
            <p class="text-muted small mb-0">Format resmi RA Islam Al Futuh: Buku Laporan Indikator & Laporan Narasi Foto Perkembangan</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Card Filter -->
    <div class="card shadow mb-4 border-left-success">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-filter mr-1"></i> Filter Kelas & Periode Rapor</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('erapor.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <label class="mr-2 font-weight-bold">Kelas:</label>
                    <select name="kelas_id" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} ({{ $kelas->kelompok ?? 'Kelompok' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mr-3 mb-2">
                    <label class="mr-2 font-weight-bold">Tahun Ajaran:</label>
                    <select name="tahun_ajaran_id" class="form-control" required>
                        @foreach($tahunAjaranList as $ta)
                            <option value="{{ $ta->id }}" {{ $tahunAjaranId == $ta->id ? 'selected' : '' }}>
                                {{ $ta->nama }} {{ $ta->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mr-3 mb-2">
                    <label class="mr-2 font-weight-bold">Semester:</label>
                    <select name="semester" class="form-control" required>
                        <option value="I" {{ $semester == 'I' ? 'selected' : '' }}>Semester I (Ganjil)</option>
                        <option value="II" {{ $semester == 'II' ? 'selected' : '' }}>Semester II (Genap)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success mb-2 px-3">
                    <i class="fas fa-search mr-1"></i> Tampilkan
                </button>
            </form>
        </div>
    </div>

    <!-- Card Daftar Siswa -->
    @if($kelasId)
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users mr-1"></i> Daftar Siswa untuk Penilaian e-Rapor
                    </h6>
                    <small class="text-muted">Semester {{ $semester }} &bull; Total: {{ count($siswaList) }} Siswa</small>
                </div>
            </div>
            <div class="card-body">
                @if(count($siswaList) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th width="4%">No</th>
                                    <th width="8%">Foto</th>
                                    <th width="15%">NIS / NISN</th>
                                    <th>Nama Lengkap Peserta Didik</th>
                                    <th width="12%">Status e-Rapor</th>
                                    <th width="15%">Terakhir Diperbarui</th>
                                    <th width="28%">Aksi & Cetak Rapor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswaList as $index => $siswa)
                                    @php
                                        $rapor = $siswa->erapor->first();
                                    @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                                        <td class="text-center">
                                            @if($siswa->foto_anak)
                                                <img src="{{ asset('storage/dokumen_siswa/' . $siswa->foto_anak) }}" class="rounded-circle" style="width: 42px; height: 42px; object-fit: cover;" alt="Foto">
                                            @else
                                                <div class="rounded-circle bg-light text-primary d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $siswa->nis ?? '-' }}</td>
                                        <td>
                                            <div class="font-weight-bold text-gray-800">{{ $siswa->nama_siswa }}</div>
                                            <small class="text-muted">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if(!$rapor)
                                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-clock mr-1"></i> Belum Dibuat</span>
                                            @elseif($rapor->status == 'Final')
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Siap Cetak</span>
                                            @else
                                                <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-pencil-alt mr-1"></i> Draft</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted text-center font-weight-bold">
                                            {{ $rapor ? $rapor->updated_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                                        </td>
                                        <td>
                                            <div class="btn-group mb-1">
                                                <a href="{{ route('erapor.edit', ['siswaId' => $siswa->id, 'tahun_ajaran_id' => $tahunAjaranId, 'semester' => $semester]) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit mr-1"></i> Kelola / Isi Nilai
                                                </a>

                                                @if($rapor)
                                                    <button type="button" class="btn btn-sm btn-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right shadow">
                                                        <h6 class="dropdown-header text-primary font-weight-bold">
                                                            <i class="fas fa-print mr-1"></i> Pilihan Cetak Siap Cetak
                                                        </h6>

                                                        <!-- 1. Cetak Indikator Sesuai Lampiran 3 -->
                                                        <a class="dropdown-item" href="{{ route('erapor.cetakIndikator', $rapor->id) }}" target="_blank">
                                                            <i class="fas fa-file-pdf text-danger mr-2"></i> 1. e-Rapor Indikator (Format Lampiran)
                                                        </a>
                                                        <a class="dropdown-item small text-muted pl-4" href="{{ route('erapor.cetakIndikator', $rapor->id) }}?preview=1" target="_blank">
                                                            <i class="fas fa-eye mr-2"></i> &bull; Preview Browser Indikator
                                                        </a>

                                                        <div class="dropdown-divider"></div>

                                                        <!-- 2. Cetak Laporan Perkembangan & Foto Bukti Sesuai Lampiran 4 -->
                                                        <a class="dropdown-item" href="{{ route('erapor.cetakPerkembangan', $rapor->id) }}" target="_blank">
                                                            <i class="fas fa-images text-success mr-2"></i> 2. Laporan Perkembangan & Foto Bukti
                                                        </a>
                                                        <a class="dropdown-item small text-muted pl-4" href="{{ route('erapor.cetakPerkembangan', $rapor->id) }}?preview=1" target="_blank">
                                                            <i class="fas fa-eye mr-2"></i> &bull; Preview Browser Foto Bukti
                                                        </a>

                                                        <div class="dropdown-divider"></div>

                                                        <!-- 3. Cetak Lengkap / Bundel Semua Halaman -->
                                                        <a class="dropdown-item font-weight-bold" href="{{ route('erapor.cetakLengkap', $rapor->id) }}" target="_blank">
                                                            <i class="fas fa-book text-primary mr-2"></i> 3. Bundel Lengkap (Semua Halaman)
                                                        </a>
                                                        <a class="dropdown-item small text-muted pl-4" href="{{ route('erapor.cetakLengkap', $rapor->id) }}?preview=1" target="_blank">
                                                            <i class="fas fa-eye mr-2"></i> &bull; Preview Browser Bundel Lengkap
                                                        </a>

                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('erapor.destroy', $rapor->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mereset/menghapus data e-Rapor ini? Data nilai dan foto perkembangan rapor ini akan dihapus.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash-alt mr-2"></i> Hapus Data e-Rapor
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info py-4 text-center">
                        <i class="fas fa-info-circle fa-2x mb-3 text-info"></i>
                        <p class="mb-0">Tidak ada siswa aktif yang terdaftar di kelas ini.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
@endsection

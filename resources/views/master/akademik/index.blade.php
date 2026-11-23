@extends('layouts.app') {{-- Sesuaikan dengan layout SB Admin 2 Anda --}}

@section('title', 'Input Akademik & Kenangan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Input Perkembangan Akademik & Aktivitas Siswa</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Kelas</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('master.akademik.index') }}" method="GET" class="form-inline">
                <label class="mr-2">Pilih Kelas:</label>
                <select name="kelas_id" class="form-control mr-2" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan Siswa</button>
            </form>
        </div>
    </div>

    @if($kelasId)
        <div class="card shadow mb-4 border-left-info">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-info">Daftar Siswa untuk Penilaian</h6>
                <div>
                    <a href="{{ route('erapor.index', ['kelas_id' => $kelasId]) }}" class="btn btn-sm btn-success shadow-sm mr-2">
                        <i class="fas fa-award mr-1"></i> e-Rapor Siap Cetak
                    </a>
                    <a href="{{ route('master.akademik.cetak-rekap', $kelasId) }}" target="_blank" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-print fa-sm text-white-50"></i> Cetak Rekap Kelas
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(count($siswaList) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Aksi Input Perkembangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswaList as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="font-weight-bold">{{ $siswa->nama_siswa }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-success mb-1" data-toggle="modal" data-target="#modalAlquran{{ $siswa->id }}">
                                                <i class="fas fa-book-open"></i> Iqro/Al-Qur'an
                                            </button>
                                            <button class="btn btn-sm btn-warning mb-1" data-toggle="modal" data-target="#modalAisma{{ $siswa->id }}">
                                                <i class="fas fa-calculator"></i> AISMA
                                            </button>
                                            <button class="btn btn-sm btn-primary mb-1" data-toggle="modal" data-target="#modalKenangan{{ $siswa->id }}">
                                                <i class="fas fa-camera"></i> Foto Kenangan
                                            </button>
                                                <a href="{{ route('master.akademik.show', $siswa->id) }}" class="btn btn-sm btn-info mb-1">
                                                    <i class="fas fa-history"></i> Riwayat
                                                </a>
                                                <a href="{{ route('erapor.edit', $siswa->id) }}" class="btn btn-sm btn-dark mb-1">
                                                    <i class="fas fa-award"></i> e-Rapor
                                                </a>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalAlquran{{ $siswa->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('master.akademik.store-alquran') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title">Input Ngaji: {{ $siswa->nama_siswa }}</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                                                        <div class="form-group"><label>Jenis</label><select name="jenis" class="form-control"><option value="Iqro">Iqro</option><option value="Al-Quran">Al-Qur'an</option></select></div>
                                                        <div class="form-group"><label>Jilid / Juz (Contoh: Jilid 3)</label><input type="text" name="jilid" class="form-control" required></div>
                                                        <div class="form-group"><label>Halaman / Surah (Contoh: Hal 15)</label><input type="text" name="halaman" class="form-control" required></div>
                                                        <div class="form-group"><label>Kelancaran</label><select name="status_kelancaran" class="form-control"><option value="Belum Lancar">Belum Lancar</option><option value="Cukup Lancar" selected>Cukup Lancar</option><option value="Lancar">Lancar</option></select></div>
                                                        <div class="form-group"><label>Catatan Guru (Opsional)</label><textarea name="catatan" class="form-control" rows="2"></textarea></div>
                                                    </div>
                                                    <div class="modal-footer"><button type="submit" class="btn btn-success">Simpan Data</button></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modalAisma{{ $siswa->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('master.akademik.store-aisma') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                    <div class="modal-header bg-warning text-dark">
                                                        <h5 class="modal-title">Input AISMA: {{ $siswa->nama_siswa }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                                                        <div class="form-group"><label>Level Jilid (Contoh: Level 1)</label><input type="text" name="level_jilid" class="form-control" required></div>
                                                        <div class="form-group"><label>Halaman Modul (Contoh: Modul A Hal 10)</label><input type="text" name="halaman_modul" class="form-control" required></div>
                                                        <div class="form-group"><label>Catatan Guru (Opsional)</label><textarea name="catatan" class="form-control" rows="2"></textarea></div>
                                                    </div>
                                                    <div class="modal-footer"><button type="submit" class="btn btn-warning">Simpan Data</button></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modalKenangan{{ $siswa->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('master.akademik.store-kenangan') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Unggah Foto: {{ $siswa->nama_siswa }}</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Tahun Ajaran</label>
                                                            <select name="tahun_ajaran_id" class="form-control" required>
                                                                @foreach($tahunAjaranList as $ta)
                                                                    <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Semester</label>
                                                            <select name="semester" class="form-control" required>
                                                                <option value="Ganjil">Ganjil</option>
                                                                <option value="Genap">Genap</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group"><label>Judul Aktivitas</label><input type="text" name="judul_aktivitas" placeholder="Misal: Lomba Mewarnai Hari Ibu" class="form-control" required></div>
                                                        <div class="form-group"><label>Narasi Perkembangan</label><textarea name="narasi_perkembangan" class="form-control" rows="3" placeholder="Ceritakan bagaimana keaktifan anak dalam kegiatan ini..." required></textarea></div>
                                                        <div class="form-group">
                                                            <label>Foto Aktivitas (Maks 5MB)</label>
                                                            <input type="file" name="foto_aktivitas" class="form-control-file" accept="image/*" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Unggah & Simpan</button></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">Belum ada siswa aktif di kelas ini.</div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

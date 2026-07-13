@extends('layouts.app')
@section('title', 'Daftar Siswa')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Siswa</h1>
    <div>
        <a href="{{ route('siswa.export') }}" class="btn btn-sm btn-info shadow-sm">
            <i class="fas fa-download"></i> Export Excel
        </a>
        @can('create', App\Models\Siswa::class)
            <button type="button" class="btn btn-sm btn-warning shadow-sm" data-toggle="modal" data-target="#importSiswaModal">
                <i class="fas fa-upload"></i> Import Excel
            </button>
            <a href="{{ route('siswa.create') }}" class="btn btn-sm btn-success shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Siswa
            </a>
        @endcan
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">Daftar Siswa Terdaftar</h6>
    </div>
    <div class="card-body">

        <form action="{{ route('siswa.index') }}" method="GET" class="mb-4">
            <div class="row text-sm">
                <div class="col-md-3 mb-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Lulus" {{ request('status') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="Pindah" {{ request('status') == 'Pindah' ? 'selected' : '' }}>Pindah</option>
                        <option value="Drop Out" {{ request('status') == 'Drop Out' ? 'selected' : '' }}>Drop Out</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="kelas_id" class="form-control form-control-sm">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} ({{ $k->kelompok }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari Nama / NIS..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-sm btn-success w-100"><i class="fas fa-search"></i> Filter</button>
                </div>
            </div>
            @if(request()->hasAny(['status', 'kelas_id', 'search']))
                <div class="col-md-3 form-group">
                    <a href="{{ route('siswa.index') }}" class="small btn btn-sm btn-secondary btn-block mt-1">Reset Filter</a>
                </div>
            @endif
        </form>
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>L/P</th>
                        <th>Kelas</th>
                        <th>Nama Wali (Orang Tua)</th>
                        <th>Nominal SPP</th>
                        @can('create', App\Models\Siswa::class)
                            <th>Aksi</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $key => $s)
                    <tr>
                        <td>{{ $siswas->firstItem() + $key }}</td>
                        <td>{{ $s->nis ?? '-' }}</td>
                        <td>{{ $s->nama_siswa }}</td>
                        <td><span class="badge badge-info">{{ $s->jenis_kelamin }}</span></td>

                        <td>
                            @if($s->status_siswa == 'Lulus')
                                <span class="badge badge-success mb-1"><i class="fas fa-user-graduate"></i> Alumni (Lulus)</span>
                                <br><small class="text-muted">Eks: {{ $s->kelas->nama_kelas ?? '-' }} ({{ $s->kelas->kelompok ?? '-' }})</small>
                            @else
                                {{ $s->kelas->nama_kelas ?? '-' }} ({{ $s->kelas->kelompok ?? '-' }})
                            @endif
                        </td>
                        <td>{{ $s->orangTua->nama_wali ?? '-' }}</td>
                        <td>Rp {{ number_format($s->nominal_spp, 0, ',', '.') }}</td>
                        @can('create', App\Models\Siswa::class)
                        <td>
                            @if($s->foto_anak)
                                <a href="{{ asset('storage/dokumen_siswa/' . $s->foto_anak) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-image"></i> Lihat Foto
                                </a>
                            @else
                                <span class="text-muted small">Tidak ada foto</span>
                            @endif

                            @if($s->akta_kelahiran)
                                <a href="{{ asset('storage/dokumen_siswa/' . $s->akta_kelahiran) }}" target="_blank" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-file-alt"></i> Akta
                                </a>
                            @endif

                            @if($s->kartu_keluarga)
                                <a href="{{ asset('storage/dokumen_siswa/' . $s->kartu_keluarga) }}" target="_blank" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-file-invoice"></i> KK
                                </a>
                            @endif
                            <a href="{{ route('siswa.edit', $s->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            @can('delete', $s)
                            <form action="{{ route('siswa.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus siswa ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">
                Menampilkan {{ $siswas->firstItem() ?? 0 }} sampai {{ $siswas->lastItem() ?? 0 }} dari {{ $siswas->total() }} data transaksi.
            </small>
            <div>
                {{ $siswas->links() }}
            </div>
        </div>
        </div>
</div>

<div class="modal fade" id="importSiswaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Siswa & Wali</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih File Excel (.xlsx)</label>
                        <input type="file" class="form-control-file" name="file_excel" required>
                        <small class="text-muted d-block mt-2">
                            Format Heading Baris 1 Excel wajib sama persis: <br>
                            <code>id_kelas | nama_kelas | nis | nama_siswa | jenis_kelamin | tempat_lahir | tanggal_lahir | nominal_spp | nama_wali | email_ortu | no_hp_ortu</code>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Proses Import</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Daftar Guru')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Guru & Tenaga Kependidikan</h1>
    <div>
    <a href="{{ route('guru.export') }}" class="btn btn-sm btn-info shadow-sm"><i class="fas fa-download"></i> Export Excel</a>
    @can('create', App\Models\Guru::class)
        <button type="button" class="btn btn-sm btn-warning shadow-sm" data-toggle="modal" data-target="#importModal"><i class="fas fa-upload"></i> Import Excel</button>
        <a href="{{ route('guru.create') }}" class="btn btn-sm btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Guru
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
        <h6 class="m-0 font-weight-bold text-success">Daftar Guru Terregistrasi</h6>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>L/P</th>
                        <th>Email Login</th>
                        <th>No HP</th>
                        @can('create', App\Models\Guru::class)
                            <th>Aksi</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($gurus as $key => $g)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $g->nik ?? '-' }}</td>
                        <td>{{ $g->nama_guru }}</td>
                        <td><span class="badge badge-info">{{ $g->jenis_kelamin }}</span></td>
                        <td>{{ $g->user->email }}</td>
                        <td>{{ $g->no_hp ?? '-' }}</td>
                        @can('create', App\Models\Guru::class)
                        <td>
                            <a href="{{ route('guru.edit', $g->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('guru.destroy', $g->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus guru ini? Akun loginnya juga akan terhapus otomatis.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Import Data Guru</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih File Excel (.xlsx)</label>
                        <input type="file" class="form-control-file" name="file_excel" required>
                        <small class="text-muted d-block mt-2">Format Heading Excel: <strong>nik, nama_guru, jenis_kelamin, email_login, no_hp, alamat</strong></small>
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

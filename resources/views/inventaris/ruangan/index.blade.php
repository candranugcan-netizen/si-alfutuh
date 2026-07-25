@extends('layouts.app')
@section('title', 'Master Ruangan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Master Lokasi & Ruangan</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">Nama Ruangan sudah ada atau form tidak lengkap.</div>
@endif

<div class="row">
    <!-- Form Tambah Ruangan -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Tambah Ruangan Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('inventaris.ruangan.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Ruangan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_ruangan" class="form-control" required placeholder="Contoh: Kelas A, Kantor">
                    </div>
                    <div class="form-group">
                        <label>Keterangan / Lokasi</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Simpan Ruangan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Ruangan -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Daftar Ruangan Inventaris</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                        <thead class="bg-light text-center">
                            <tr>
                                <th width="10%">No</th>
                                <th width="35%">Nama Ruangan</th>
                                <th width="40%">Keterangan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ruangan as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $item->nama_ruangan }}</strong></td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('inventaris.ruangan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin menghapus ruangan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data ruangan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

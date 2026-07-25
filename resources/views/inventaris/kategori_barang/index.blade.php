@extends('layouts.app')
@section('title', 'Master Kategori Barang')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Master Kategori Barang</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">Nama Kategori sudah ada atau form tidak lengkap.</div>
@endif

<div class="row">
    <!-- Form Tambah Kategori -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Kategori Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('inventaris.kategori_barang.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control" required placeholder="Contoh: Elektronik, Mebel">
                    </div>
                    <div class="form-group">
                        <label>Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Simpan Kategori</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori Barang</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                        <thead class="bg-light text-center">
                            <tr>
                                <th width="10%">No</th>
                                <th width="35%">Nama Kategori</th>
                                <th width="40%">Keterangan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategori as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $item->nama_kategori }}</strong></td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('inventaris.kategori_barang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin menghapus kategori ini? Menghapus kategori bisa mempengaruhi data barang terkait.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data kategori.</td>
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

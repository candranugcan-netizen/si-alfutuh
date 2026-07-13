@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Berita Baru</h1>

    <div class="card shadow mb-4">
        <div class="card-body">

@if ($errors->any())
                <div class="alert alert-danger shadow-sm">
                    <strong>Gagal Menyimpan!</strong> Silakan periksa masalah berikut:
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label>Judul Berita</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Gambar Utama (Opsional)</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                </div>
                <div class="form-group mb-3">
                    <label>Isi Konten / Pengumuman</label>
                    <textarea name="konten" class="form-control" rows="5" required></textarea>
                </div>
                <div class="form-group mb-4">
                    <label>Status Publikasi</label>
                    <select name="status" class="form-control">
                        <option value="Publish">Publish (Tampilkan)</option>
                        <option value="Draft">Draft (Simpan Sementara)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Berita</button>
                <a href="{{ route('berita.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

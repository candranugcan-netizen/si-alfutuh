@extends('layouts.app')
@section('title', 'Edit Data Inventaris')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Data / Mutasi Barang</h1>
    <a href="{{ route('inventaris.pencatatan.index') }}" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Kode Barang: {{ $item->kode_barang }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('inventaris.pencatatan.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang', $item->nama_barang) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Merek / Tipe</label>
                    <input type="text" name="merk" class="form-control" value="{{ old('merk', $item->merk) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Kategori Barang</label>
                    <select name="kategori_barang_id" class="form-control" required>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}" {{ $item->kategori_barang_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold text-danger"><i class="fas fa-map-marker-alt"></i> Lokasi Ruangan (Mutasi)</label>
                    <select name="ruangan_id" class="form-control border-danger">
                        <option value="">-- Belum Ditempatkan --</option>
                        @foreach($ruangan as $r)
                            <option value="{{ $r->id }}" {{ $item->ruangan_id == $r->id ? 'selected' : '' }}>{{ $r->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="font-weight-bold">Jumlah Total Unit</label>
                    <input type="number" name="jumlah_total" class="form-control" value="{{ old('jumlah_total', $item->jumlah_total) }}" min="1" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="font-weight-bold">Kondisi Barang</label>
                    <select name="kondisi" class="form-control" required>
                        <option value="Baik" {{ $item->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ $item->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ $item->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="font-weight-bold">Sumber Dana</label>
                    <input type="text" name="sumber_dana" class="form-control" value="{{ old('sumber_dana', $item->sumber_dana) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Tanggal Pengadaan</label>
                    <input type="date" name="tanggal_pengadaan" class="form-control" value="{{ old('tanggal_pengadaan', $item->tanggal_pengadaan) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Keterangan / Catatan Tambahan</label>
                    <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $item->keterangan) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection

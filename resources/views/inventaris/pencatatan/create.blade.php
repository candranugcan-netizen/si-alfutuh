@extends('layouts.app')
@section('title', 'Tambah Barang Inventaris')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Barang Baru</h1>
    <a href="{{ route('inventaris.pencatatan.index') }}" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Input Data Aset</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('inventaris.pencatatan.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control" required placeholder="Contoh: Meja Siswa, Balok APE">
                </div>
                <div class="col-md-6 form-group">
                    <label>Merek (Opsional)</label>
                    <input type="text" name="merk" class="form-control" placeholder="Contoh: Olympic, Epson">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Kategori Barang <span class="text-danger">*</span></label>
                    <select name="kategori_barang_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Pastikan Anda sudah mengisi data di master Kategori.</small>
                </div>
                <div class="col-md-6 form-group">
                    <label>Lokasi / Ruangan</label>
                    <select name="ruangan_id" class="form-control">
                        <option value="">-- Belum Ditempatkan --</option>
                        @foreach($ruangan as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Jumlah Total <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_total" class="form-control" required min="1" value="1">
                </div>
                <div class="col-md-4 form-group">
                    <label>Kondisi <span class="text-danger">*</span></label>
                    <select name="kondisi" class="form-control" required>
                        <option value="Baik" selected>Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Tanggal Pengadaan</label>
                    <input type="date" name="tanggal_pengadaan" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Sumber Dana (Opsional)</label>
                    <input type="text" name="sumber_dana" class="form-control" placeholder="Contoh: Dana BOS, Kas Sekolah">
                </div>
                <div class="col-md-6 form-group">
                    <label>Keterangan Tambahan</label>
                    <input type="text" name="keterangan" class="form-control" placeholder="Catatan khusus terkait barang...">
                </div>
            </div>

            <hr>
            <div class="text-right">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Inventaris</button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Catat Peminjaman Barang')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Form Peminjaman Barang</h1>
    <a href="{{ route('inventaris.peminjaman.index') }}" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Input Detail Peminjaman</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('inventaris.peminjaman.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Pilih Barang</label>
                    <select name="inventaris_id" class="form-control" required>
                        <option value="">-- Pilih Barang (Stok Tersedia) --</option>
                        @foreach($inventaris as $item)
                            <option value="{{ $item->id }}" {{ old('inventaris_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_barang }} (Sisa Stok: {{ $item->jumlah_tersedia }} unit)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Jumlah Dipinjam (Unit)</label>
                    <input type="number" name="jumlah_pinjam" class="form-control" value="{{ old('jumlah_pinjam', 1) }}" min="1" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control" placeholder="Contoh: Ustadzah Ambar / Ustadz Erwin" value="{{ old('nama_peminjam') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Peran / Jabatan Peminjam</label>
                    <input type="text" name="peran_peminjam" class="form-control" placeholder="Contoh: Guru Kelas A / Wali Murid" value="{{ old('peran_peminjam') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Batas Waktu Pengembalian (Opsional)</label>
                    <input type="date" name="batas_waktu_kembali" class="form-control" value="{{ old('batas_waktu_kembali') }}">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="font-weight-bold">Keterangan / Keperluan</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Dipinjam untuk kegiatan pengajian">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Transaksi Peminjaman</button>
        </form>
    </div>
</div>
@endsection

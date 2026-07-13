@extends('layouts.app')
@section('title', 'Tambah Kelas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-success">Tambah Kelas Baru</h6></div>
            <div class="card-body">
                <form action="{{ route('kelas.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Kelas</label>
                        <input type="text" class="form-control" name="nama_kelas" value="{{ old('nama_kelas') }}" placeholder="Contoh: Kelas Mawar" required>
                    </div>
                    <div class="form-group">
                        <label>Kelompok</label>
                        <select class="form-control" name="kelompok" required>
                            <option value="">-- Pilih Kelompok --</option>
                            <option value="A">Kelompok A</option>
                            <option value="B">Kelompok B</option>
                            <option value="KB">Kelompok Bermain (KB)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kapasitas (Jumlah Siswa)</label>
                        <input type="number" class="form-control" name="kapasitas" value="{{ old('kapasitas', 20) }}" min="1" required>
                    </div>
                    <hr>
                    <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

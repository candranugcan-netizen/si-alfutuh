@extends('layouts.app')
@section('title', 'Edit Kelas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-success">Ubah Data Kelas</h6></div>
            <div class="card-body">
                <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Nama Kelas</label>
                        <input type="text" class="form-control" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Kelompok</label>
                        <select class="form-control" name="kelompok" required>
                            <option value="A" {{ $kelas->kelompok == 'A' ? 'selected' : '' }}>Kelompok A</option>
                            <option value="B" {{ $kelas->kelompok == 'B' ? 'selected' : '' }}>Kelompok B</option>
                            <option value="KB" {{ $kelas->kelompok == 'KB' ? 'selected' : '' }}>Kelompok Bermain (KB)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kapasitas (Jumlah Siswa)</label>
                        <input type="number" class="form-control" name="kapasitas" value="{{ old('kapasitas', $kelas->kapasitas) }}" min="1" required>
                    </div>
                    <hr>
                    <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Perbarui Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

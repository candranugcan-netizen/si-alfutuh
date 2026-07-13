@extends('layouts.app')
@section('title', 'Edit Tahun Ajaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Ubah Data Tahun Ajaran</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('tahun-ajaran.update', $tahunAjaran->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Tahun Ajaran</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $tahunAjaran->nama) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select class="form-control" id="semester" name="semester" required>
                            <option value="Ganjil" {{ old('semester', $tahunAjaran->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester', $tahunAjaran->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ $tahunAjaran->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Set Sebagai Semester Aktif saat ini</label>
                        </div>
                    </div>
                    <hr>
                    <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Perbarui Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

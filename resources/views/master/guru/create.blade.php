@extends('layouts.app')
@section('title', 'Tambah Guru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-success">Tambah Guru & Akun Baru</h6></div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('guru.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_guru" value="{{ old('nama_guru') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Akun Login <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="contoh: budi@gmail.com" required>
                                <small class="text-muted">Password otomatis diset: <strong>password123</strong></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIK / No. Identitas</label>
                                <input type="text" class="form-control" name="nik" value="{{ old('nik') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-control" name="jenis_kelamin" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>No. HP (WhatsApp)</label>
                        <input type="text" class="form-control" name="no_hp" value="{{ old('no_hp') }}">
                    </div>
                    <div class="form-group">
                        <label>Alamat Tinggal</label>
                        <textarea class="form-control" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                    </div>
                    <hr>
                    <a href="{{ route('guru.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan & Daftarkan Akun</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

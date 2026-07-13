@extends('layouts.app')
@section('title', 'Tambah Siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Form Tambah Siswa & Akun Orang Tua</h6>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                    <h5 class="text-success font-weight-bold">A. Data Profil Siswa</h5>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Nama Lengkap Siswa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_siswa" value="{{ old('nama_siswa') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Pilih Kelas <span class="text-danger">*</span></label>
                            <select class="form-control" name="kelas_id" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }} ({{ $k->kelompok }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>NIS (Nomor Induk Siswa)</label>
                            <input type="text" class="form-control" name="nis" value="{{ old('nis') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-control" name="jenis_kelamin" required>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                        </div>
                    </div>

                    <hr class="sidebar-divider">
                    <h5 class="text-info font-weight-bold">B. Data Akun Orang Tua / Wali</h5>

                    <div class="form-group">
                        <label>Nama Lengkap Orang Tua / Wali <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_wali" value="{{ old('nama_wali') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Email Login Orang Tua <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email_ortu" value="{{ old('email_ortu') }}" placeholder="contoh: ortu@gmail.com" required>
                            <small class="text-muted">Password otomatis diatur default: <strong>[Nomor NIS Siswa]</strong> (atau password123 jika NIS kosong).</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>No. HP / WhatsApp Wali</label>
                            <input type="text" class="form-control" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}">
                        </div>
                    </div>

                    <hr class="sidebar-divider">
                    <h5 class="text-info font-weight-bold">C. Pilihan Nominal SPP</h5>

                    <div class="col-md-6 form-group">
                            <label>Nominal SPP <span class="text-danger">*</span></label>
                            <select class="form-control" name="nominal_spp" required>
                                <option value="90000" {{ old('nominal_spp') == '90000' ? 'selected' : '' }}>Rp 90.000</option>
                                <option value="95000" {{ old('nominal_spp') == '95000' ? 'selected' : '' }}>Rp 95.000</option>
                                <option value="115000" {{ old('nominal_spp') == '115000' ? 'selected' : '' }}>Rp 115.000</option>
                            </select>
                        </div>

                        <hr class="sidebar-divider">
                    <h5 class="text-info font-weight-bold">D. Dokumen Pendukung</h5>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Foto Anak <span class="text-muted">(Gambar)</span></label>
                            <input type="file" class="form-control-file" name="foto_anak" accept="image/*">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Akta Kelahiran <span class="text-muted">(Gambar/PDF)</span></label>
                            <input type="file" class="form-control-file" name="akta_kelahiran" accept="image/*,application/pdf">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Kartu Keluarga <span class="text-muted">(Gambar/PDF)</span></label>
                            <input type="file" class="form-control-file" name="kartu_keluarga" accept="image/*,application/pdf">
                        </div>
                    </div>
                    <hr class="sidebar-divider">

                    <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan Seluruh Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

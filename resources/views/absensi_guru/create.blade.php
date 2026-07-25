@extends('layouts.app')
@section('title', 'Input Absensi Massal')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Catat Kehadiran Guru</h1>
    <a href="{{ route('absensi_guru.index') }}" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Rekap
    </a>
</div>

<div class="card shadow mb-4 border-left-primary">
    <div class="card-body">
        <form action="{{ route('absensi_guru.create') }}" method="GET" class="form-inline">
            <label class="mr-2 font-weight-bold">Pilih Tanggal Absen:</label>
            <input type="date" name="tanggal" class="form-control mr-2" value="{{ $tanggal }}">
            <button type="submit" class="btn btn-primary">Ubah Tanggal</button>
        </form>
        <small class="text-muted mt-2 d-block">* Secara default, tabel di bawah menampilkan absensi untuk hari ini.</small>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-light">
        <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-users"></i> Daftar Guru Belum Absen ({{ date('d-m-Y', strtotime($tanggal)) }})</h6>
    </div>
    <div class="card-body">
        @if(count($gurus) > 0)
        <form action="{{ route('absensi_guru.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Nama Guru</th>
                            <th width="20%">Status Kehadiran</th>
                            <th width="15%">Jam Masuk</th>
                            <th width="15%">Jam Pulang</th>
                            <th width="20%">Keterangan (Opsional)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gurus as $index => $guru)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">
                                <strong>{{ $guru->name }}</strong>
                                <input type="hidden" name="absensi[{{ $index }}][user_id]" value="{{ $guru->id }}">
                            </td>
                            <td>
                                <select name="absensi[{{ $index }}][status]" class="form-control" required>
                                    <option value="Hadir" selected>Hadir</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Alpa">Alpa</option>
                                </select>
                            </td>
                            <td>
                                <input type="time" name="absensi[{{ $index }}][waktu_masuk]" class="form-control" value="07:00">
                            </td>
                            <td>
                                <input type="time" name="absensi[{{ $index }}][waktu_pulang]" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="absensi[{{ $index }}][keterangan]" class="form-control" placeholder="Alasan izin/sakit...">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr>
            <div class="text-right">
                <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Simpan Absensi Massal</button>
            </div>
        </form>
        @else
            <div class="alert alert-success text-center">
                <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                Semua guru telah diabsen pada tanggal <strong>{{ date('d-m-Y', strtotime($tanggal)) }}</strong>.
            </div>
        @endif
    </div>
</div>
@endsection

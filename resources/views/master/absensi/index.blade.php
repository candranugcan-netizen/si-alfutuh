@extends('layouts.app') @section('title', 'Input Absensi Siswa')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary text-white">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-clipboard-check"></i> Form Absensi Harian</h6>
    </div>

    <div class="card-body">
        <form action="{{ route('master.absensi.index') }}" method="GET" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="font-weight-bold">Pilih Kelas</label>
                    <select name="kelas_id" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $selectedKelas == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="font-weight-bold">Tanggal Absensi</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Tampilkan Siswa</button>
                </div>
            </div>
        </form>

        <hr>

        @if($selectedKelas && count($siswas) > 0)
            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif

            <!-- TAMBAHKAN KOTAK TOMBOL DOWNLOAD DI SINI -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Kehadiran Siswa</h6>
                <div>
                    <a href="{{ route('master.absensi.export.excel', ['kelas_id' => $selectedKelas, 'tanggal' => $tanggal]) }}" class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
                    </a>
                    <a href="{{ route('master.absensi.export.pdf', ['kelas_id' => $selectedKelas, 'tanggal' => $tanggal]) }}" class="btn btn-sm btn-danger shadow-sm">
                        <i class="fas fa-file-pdf fa-sm text-white-50"></i> Export PDF
                    </a>
                </div>
            </div>
            <!-- SELESAI PENAMBAHAN TOMBOL -->

            <form action="{{ route('master.absensi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Status Kehadiran</th>
                                <th>Keterangan (Opsional)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswas as $index => $s)
                                @php
                                    // Cek apakah hari ini siswa sudah diabsen (berguna untuk fitur edit)
                                    $statusSekarang = $s->absensi->first()->status ?? 'Hadir'; // Default 'Hadir' agar guru cepat klik simpan
                                    $keteranganSekarang = $s->absensi->first()->keterangan ?? '';
                                @endphp
                                <tr>
                                    <td class="text-center align-middle">{{ $index + 1 }}</td>
                                    <td class="align-middle fw-bold">{{ $s->nama_siswa }}</td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-outline-success {{ $statusSekarang == 'Hadir' ? 'active' : '' }}">
                                                <input type="radio" name="kehadiran[{{ $s->id }}]" value="Hadir" {{ $statusSekarang == 'Hadir' ? 'checked' : '' }} required> Hadir
                                            </label>
                                            <label class="btn btn-outline-info {{ $statusSekarang == 'Sakit' ? 'active' : '' }}">
                                                <input type="radio" name="kehadiran[{{ $s->id }}]" value="Sakit" {{ $statusSekarang == 'Sakit' ? 'checked' : '' }}> Sakit
                                            </label>
                                            <label class="btn btn-outline-warning {{ $statusSekarang == 'Izin' ? 'active' : '' }}">
                                                <input type="radio" name="kehadiran[{{ $s->id }}]" value="Izin" {{ $statusSekarang == 'Izin' ? 'checked' : '' }}> Izin
                                            </label>
                                            <label class="btn btn-outline-danger {{ $statusSekarang == 'Alfa' ? 'active' : '' }}">
                                                <input type="radio" name="kehadiran[{{ $s->id }}]" value="Alfa" {{ $statusSekarang == 'Alfa' ? 'checked' : '' }}> Alfa
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="keterangan[{{ $s->id }}]" class="form-control form-control-sm" placeholder="Contoh: Sakit demam..." value="{{ $keteranganSekarang }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Simpan Absensi Kelas Ini</button>
                </div>
            </form>
        @elseif($selectedKelas && count($siswas) == 0)
            <div class="alert alert-warning text-center">Belum ada data siswa di kelas ini.</div>
        @endif
    </div>
</div>
<!-- KARTU KHUSUS UNDUH REKAP BULANAN -->
<div class="card shadow mb-4 border-left-success">
    <div class="card-header py-3 bg-white">
        <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-file-download"></i> Unduh Rekap Absensi Bulanan</h6>
    </div>
    <div class="card-body">
        <!-- Form Mengarah ke PDF secara default, tapi ada tombol Excel juga lewat JavaScript -->
        <form action="{{ route('master.absensi.export_bulanan.pdf') }}" method="GET" id="formRekapBulanan">
            <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <label class="font-weight-bold">Pilih Kelas</label>
                    <select name="kelas_id_rekap" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="font-weight-bold">Bulan</label>
                    <select name="bulan" class="form-control" required>
                        @for($i = 1; $i <= 12; $i++)
                            @php $val = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                            <option value="{{ $val }}" {{ date('m') == $val ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month((int)$i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="font-weight-bold">Tahun</label>
                    <select name="tahun" class="form-control" required>
                        @for($t = date('Y'); $t >= 2023; $t--)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 mb-3 d-flex">
                    <button type="submit" class="btn btn-danger w-50 mr-1" onclick="document.getElementById('formRekapBulanan').action='{{ route('master.absensi.export_bulanan.pdf') }}'">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button type="submit" class="btn btn-success w-50 ml-1" onclick="document.getElementById('formRekapBulanan').action='{{ route('master.absensi.export_bulanan.excel') }}'">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

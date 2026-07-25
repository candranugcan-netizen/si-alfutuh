@extends('layouts.app')
@section('title', 'Data Absensi Guru')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Rekap Absensi Guru</h1>
    <a href="{{ route('absensi_guru.create') }}" class="btn btn-sm btn-success shadow-sm">
        <i class="fas fa-clipboard-check fa-sm text-white-50"></i> Input Absen Hari Ini
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- FORM EXPORT LAPORAN BULANAN --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-light">
        <h6 class="m-0 font-weight-bold text-secondary"><i class="fas fa-print"></i> Cetak Laporan Kehadiran Bulanan</h6>
    </div>
    <div class="card-body">
        <form method="GET">
            <div class="row align-items-end">
                <div class="col-md-3 form-group mb-0">
                    <label class="small font-weight-bold">Bulan</label>
                    <select name="bulan" class="form-control form-control-sm">
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 form-group mb-0">
                    <label class="small font-weight-bold">Tahun</label>
                    <input type="number" name="tahun" class="form-control form-control-sm" value="{{ date('Y') }}">
                </div>
                <div class="col-md-6 form-group mb-0">
                    {{-- Menggunakan formaction agar 1 form bisa memiliki 2 tujuan --}}
                    <button type="submit" formaction="{{ route('absensi_guru.exportExcel') }}" class="btn btn-sm btn-success shadow-sm mr-2">
                        <i class="fas fa-file-excel"></i> Unduh Excel
                    </button>
                    <button type="submit" formaction="{{ route('absensi_guru.exportPdf') }}" formtarget="_blank" class="btn btn-sm btn-danger shadow-sm">
                        <i class="fas fa-file-pdf"></i> Buka PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Data Kehadiran Tanggal: {{ date('d-m-Y', strtotime($tanggal)) }}</h6>
        <form action="{{ route('absensi_guru.index') }}" method="GET" class="form-inline">
            <input type="date" name="tanggal" class="form-control form-control-sm mr-2" value="{{ $tanggal }}">
            <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead class="text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Guru</th>
                        <th>Status</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Pulang</th>
                        <th>Keterangan</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $index => $absen)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $absen->user->name }}</td>
                        <td class="text-center">
                            @if($absen->status == 'Hadir') <span class="badge badge-success">Hadir</span>
                            @elseif($absen->status == 'Izin') <span class="badge badge-warning">Izin</span>
                            @elseif($absen->status == 'Sakit') <span class="badge badge-info">Sakit</span>
                            @else <span class="badge badge-danger">Alpa</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $absen->waktu_masuk ?? '-' }}</td>
                        <td class="text-center">{{ $absen->waktu_pulang ?? '-' }}</td>
                        <td>{{ $absen->keterangan ?? '-' }}</td>
                        <td class="text-center">
                            <form action="{{ route('absensi_guru.destroy', $absen->id) }}" method="POST" onsubmit="return confirm('Hapus absen ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data absensi untuk tanggal ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

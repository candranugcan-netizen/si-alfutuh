@extends('layouts.app') {{-- Sesuaikan dengan nama layout master SB Admin 2 Anda --}}

@section('title', 'Data Alumni')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Alumni RA Al Futuh</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('master.alumni.index') }}" method="GET" class="form-inline">
                <label class="mr-2 font-weight-bold text-gray-700">Tahun Kelulusan:</label>
                <select name="tahun_lulus" class="form-control mr-2">
                    <option value="">-- Semua Angkatan --</option>
                    @foreach($daftarTahunLulus as $tahun)
                        <option value="{{ $tahun }}" {{ $tahunLulusFilter == $tahun ? 'selected' : '' }}>
                            Tahun Kelulusan {{ $tahun }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm px-3 mr-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if($tahunLulusFilter)
                    <a href="{{ route('master.alumni.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa Lulus</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>NIS</th>
                            <th>Nama Alumni</th>
                            <th>Jenis Kelamin</th>
                            <th>Kelas Terakhir</th>
                            <th>Riwayat Perjalanan Kelas</th>
                            <th>Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alumniList as $key => $alumni)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $alumni->nis ?? '-' }}</td>
                                <td class="font-weight-bold text-gray-800">{{ $alumni->nama_siswa }}</td>
                                <td>{{ $alumni->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>
                                    <span class="badge badge-secondary p-2">
                                        {{ $alumni->kelas->nama_kelas ?? 'Kelas Terhapus' }}
                                    </span>
                                </td>
                                <td>
                                    {{-- Menampilkan rekam jejak kelas yang pernah dilalui anak --}}
                                    @foreach($alumni->riwayatKelas as $riwayat)
                                        <small class="d-block mb-1">
                                            <i class="fas fa-chevron-right text-success mr-1"></i>
                                            <strong>{{ $riwayat->kelas->nama_kelas ?? '-' }}</strong>
                                            ({{ $riwayat->tahunAjaran->nama ?? '-' }})
                                            <span class="badge badge-light border">{{ $riwayat->keterangan }}</span>
                                        </small>
                                    @endforeach
                                </td>

                                <td>
                                    @if($alumni->foto_anak)
                                        <a href="{{ asset('storage/dokumen_siswa/' . $alumni->foto_anak) }}" target="_blank" class="btn btn-sm btn-info mb-1">
                                            <i class="fas fa-image"></i> Lihat Foto
                                        </a>
                                    @else
                                        <span class="text-muted small d-block mb-1">Tidak ada dokumen</span>
                                    @endif

                                    @if($alumni->akta_kelahiran)
                                        <a href="{{ asset('storage/dokumen_siswa/' . $alumni->akta_kelahiran) }}" target="_blank" class="btn btn-sm btn-secondary mb-1">
                                            <i class="fas fa-file-alt"></i> Akta
                                        </a>
                                    @endif

                                    @if($alumni->kartu_keluarga)
                                        <a href="{{ asset('storage/dokumen_siswa/' . $alumni->kartu_keluarga) }}" target="_blank" class="btn btn-sm btn-secondary mb-1">
                                            <i class="fas fa-file-invoice"></i> KK
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                                    Belum ada data alumni yang terekam pada sistem atau filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

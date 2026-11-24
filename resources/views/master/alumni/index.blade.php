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
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        @if($alumni->foto_url)
                                            <a href="javascript:void(0)" class="btn-lihat-dokumen mr-2"
                                                data-toggle="modal" data-target="#modalDokumenSiswa"
                                                data-nama="{{ $alumni->nama_siswa }}"
                                                data-info="NIS: {{ $alumni->nis ?? '-' }} &bull; Alumni Kelas: {{ $alumni->kelas->nama_kelas ?? '-' }}"
                                                data-foto="{{ $alumni->foto_url }}"
                                                data-foto-file="{{ $alumni->foto_anak }}"
                                                data-kk="{{ $alumni->kk_url }}"
                                                data-kk-file="{{ $alumni->kartu_keluarga }}"
                                                data-akta="{{ $alumni->akta_url }}"
                                                data-akta-file="{{ $alumni->akta_kelahiran }}"
                                                data-active="foto"
                                                title="Klik untuk melihat foto alumni">
                                                <img src="{{ $alumni->foto_url }}" alt="Foto" class="rounded-circle shadow-sm"
                                                    style="width: 36px; height: 36px; object-fit: cover; border: 2px solid #4e73df; cursor: pointer;">
                                            </a>
                                        @endif
                                        <span class="font-weight-bold text-gray-800">{{ $alumni->nama_siswa }}</span>
                                    </div>
                                </td>
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

                                <td class="align-middle text-center">
                                    @php
                                        $countAlumniDocs = ($alumni->foto_anak ? 1 : 0) + ($alumni->akta_kelahiran ? 1 : 0) + ($alumni->kartu_keluarga ? 1 : 0);
                                    @endphp
                                    <button type="button"
                                        class="btn btn-sm {{ $countAlumniDocs > 0 ? 'btn-outline-primary' : 'btn-outline-secondary' }} btn-lihat-dokumen"
                                        data-toggle="modal" data-target="#modalDokumenSiswa"
                                        data-nama="{{ $alumni->nama_siswa }}"
                                        data-info="NIS: {{ $alumni->nis ?? '-' }} &bull; Alumni Kelas: {{ $alumni->kelas->nama_kelas ?? '-' }}"
                                        data-foto="{{ $alumni->foto_url }}"
                                        data-foto-file="{{ $alumni->foto_anak }}"
                                        data-kk="{{ $alumni->kk_url }}"
                                        data-kk-file="{{ $alumni->kartu_keluarga }}"
                                        data-akta="{{ $alumni->akta_url }}"
                                        data-akta-file="{{ $alumni->akta_kelahiran }}"
                                        title="Lihat & Download Dokumen Alumni">
                                        <i class="fas fa-folder-open mr-1"></i> Lihat Dokumen
                                        @if($countAlumniDocs > 0)
                                            <span class="badge badge-primary ml-1">{{ $countAlumniDocs }}</span>
                                        @endif
                                    </button>
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

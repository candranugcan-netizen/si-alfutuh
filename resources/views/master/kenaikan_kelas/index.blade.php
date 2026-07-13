@extends('layouts.app') {{-- Sesuaikan dengan layout SB Admin 2 Anda --}}

@section('title', 'Kenaikan & Mutasi Kelas')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Proses Kenaikan & Mutasi Kelas</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Kelas Saat Ini</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('master.kenaikan-kelas.index') }}" method="GET" class="form-inline">
                <label class="mr-2">Pilih Kelas:</label>
                <select name="kelas_id" class="form-control mr-2" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan Siswa</button>
            </form>
        </div>
    </div>

    @if($kelasId)
        <div class="card shadow mb-4 border-left-warning">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">Eksekusi Mutasi Massal</h6>
            </div>
            <div class="card-body">
                @if(count($siswaList) > 0)
                    <form action="{{ route('master.kenaikan-kelas.process') }}" method="POST" id="formMutasi">
                        @csrf

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="50">
                                            <input type="checkbox" id="checkAll">
                                        </th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($siswaList as $siswa)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" class="checkItem">
                                            </td>
                                            <td>{{ $siswa->nis ?? '-' }}</td>
                                            <td>{{ $siswa->nama_siswa }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Tahun Ajaran Saat Ini (Untuk Riwayat) <span class="text-danger">*</span></label>
                                <select name="tahun_ajaran_id" class="form-control" required>
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    @foreach($tahunAjaranList as $ta)
                                        <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Keterangan Mutasi <span class="text-danger">*</span></label>
                                <select name="keterangan" id="keterangan" class="form-control" required>
                                    <option value="">-- Pilih Keterangan --</option>
                                    <option value="Naik Kelas">Naik Kelas</option>
                                    <option value="Tinggal Kelas">Tinggal Kelas</option>
                                    <option value="Lulus">Lulus (Menjadi Alumni)</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Kelas Tujuan (Abaikan jika Lulus)</label>
                                <select name="kelas_tujuan_id" id="kelasTujuan" class="form-control">
                                    <option value="">-- Pilih Kelas Tujuan --</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning mt-3" onclick="return confirm('Apakah Anda yakin ingin mengeksekusi mutasi kelas massal ini?')">
                            <i class="fas fa-random"></i> Proses Mutasi Terpilih
                        </button>
                    </form>
                @else
                    <div class="alert alert-info">Tidak ada siswa berstatus Aktif di kelas ini.</div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Fitur Check All
    document.getElementById('checkAll').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.checkItem');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });

    // Otomatis menonaktifkan dropdown Kelas Tujuan jika memilih opsi "Lulus"
    document.getElementById('keterangan').addEventListener('change', function() {
        var kelasTujuan = document.getElementById('kelasTujuan');
        if (this.value === 'Lulus') {
            kelasTujuan.value = '';
            kelasTujuan.disabled = true;
        } else {
            kelasTujuan.disabled = false;
        }
    });
</script>
@endpush

@extends('layouts.app')
@section('title', 'Kelola Data PPDB')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-plus mr-2"></i> Kelola Data PPDB
        </h1>
        <span class="text-muted small">
            <i class="fas fa-database mr-1"></i> Total {{ $stats['total'] }} pendaftar terdaftar
        </span>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pendaftar</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Proses</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-hourglass-half fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Diterima</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['diterima'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Ditolak</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['ditolak'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-times-circle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow mb-4">

        {{-- Card Header: Judul --}}
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-1"></i> Daftar Pendaftar PPDB
            </h6>
        </div>

        {{-- Filter Bar --}}
        <div class="card-body border-bottom py-3">
            <form method="GET" action="{{ route('ppdb.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-5 col-sm-12 mb-2 mb-md-0">
                        <label class="small font-weight-bold text-gray-700 mb-1">Cari Pendaftar</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" name="search" class="form-control"
                                placeholder="Nama / No. pendaftaran / Email..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                        <label class="small font-weight-bold text-gray-700 mb-1">Status</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">— Semua Status —</option>
                            <option value="Pending"  {{ request('status') == 'Pending'  ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>✅ Diterima</option>
                            <option value="Ditolak"  {{ request('status') == 'Ditolak'  ? 'selected' : '' }}>❌ Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-2 mb-md-0 d-flex align-items-end" style="gap: 6px;">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-filter mr-1"></i> Terapkan Filter
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('ppdb.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times mr-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="min-width: 700px;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center align-middle" style="width: 50px;">No</th>
                            <th class="align-middle" style="width: 150px;">No. Pendaftaran</th>
                            <th class="align-middle">Calon Siswa</th>
                            <th class="align-middle">Orang Tua</th>
                            <th class="text-center align-middle" style="width: 100px;">Status</th>
                            <th class="text-center align-middle" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftar as $index => $item)
                        <tr>
                            {{-- No --}}
                            <td class="text-center align-middle text-muted">
                                {{ $pendaftar->firstItem() + $index }}
                            </td>

                            {{-- No. Pendaftaran --}}
                            <td class="align-middle">
                                <span class="badge badge-info font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                    {{ $item->no_pendaftaran }}
                                </span>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                </div>
                            </td>

                            {{-- Calon Siswa --}}
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    @php
                                        $fotoUrl = null;
                                        if ($item->file_foto) {
                                            $p1 = storage_path('app/public/dokumen_siswa/' . $item->file_foto);
                                            $p2 = storage_path('app/public/uploads/ppdb/' . $item->file_foto);
                                            if (file_exists($p1)) $fotoUrl = asset('storage/dokumen_siswa/' . $item->file_foto);
                                            elseif (file_exists($p2)) $fotoUrl = asset('storage/uploads/ppdb/' . $item->file_foto);
                                        }
                                    @endphp
                                    @if($fotoUrl)
                                        <img src="{{ $fotoUrl }}" alt="Foto"
                                            class="rounded-circle mr-3"
                                            style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #dee2e6; flex-shrink: 0;">
                                    @else
                                        <div class="rounded-circle mr-3 bg-light d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px; border: 2px solid #dee2e6; flex-shrink: 0;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                            {{ $item->nama_lengkap }}
                                        </div>
                                        <div class="small text-muted">
                                            {{ $item->jenis_kelamin == 'Laki-laki' ? '♂' : '♀' }}
                                            {{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d/m/Y') }}
                                            &bull; {{ $item->agama }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Orang Tua --}}
                            <td class="align-middle">
                                <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">
                                    {{ $item->nama_ayah }}
                                </div>
                                <div class="small text-muted">
                                    <i class="fas fa-phone-alt mr-1"></i>{{ $item->no_hp_ortu }}
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="text-center align-middle">
                                @if($item->status == 'Pending')
                                    <span class="badge badge-warning text-white" style="font-size: 0.75rem;">
                                        <i class="fas fa-hourglass-half mr-1"></i> Pending
                                    </span>
                                @elseif($item->status == 'Diterima')
                                    <span class="badge badge-success" style="font-size: 0.75rem;">
                                        <i class="fas fa-check mr-1"></i> Diterima
                                    </span>
                                @else
                                    <span class="badge badge-danger" style="font-size: 0.75rem;">
                                        <i class="fas fa-times mr-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center align-middle">
                                <a href="{{ route('ppdb.show', $item->id) }}"
                                    class="btn btn-sm btn-outline-info mb-1" title="Lihat Detail">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                @if($item->status == 'Pending')
                                    <div class="d-flex justify-content-center mt-1" style="gap: 4px;">
                                        <form action="{{ route('ppdb.terima', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Terima siswa ini? Data otomatis masuk ke master Siswa & User.')"
                                                title="Terima">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('ppdb.tolak', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin ingin menolak pendaftar ini?')"
                                                title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="text-muted small mt-1">
                                        <i class="fas fa-check-double mr-1"></i> Selesai
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block text-gray-300"></i>
                                @if(request()->hasAny(['search', 'status']))
                                    <strong>Tidak ada hasil</strong> untuk filter yang diterapkan.
                                    <div class="mt-2">
                                        <a href="{{ route('ppdb.index') }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-times mr-1"></i> Hapus Filter
                                        </a>
                                    </div>
                                @else
                                    Belum ada data pendaftar PPDB.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($pendaftar->hasPages())
            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Menampilkan {{ $pendaftar->firstItem() }}–{{ $pendaftar->lastItem() }}
                    dari {{ $pendaftar->total() }} pendaftar
                </div>
                {{ $pendaftar->links() }}
            </div>
        @else
            @if($pendaftar->count() > 0)
            <div class="card-footer bg-white">
                <div class="small text-muted">
                    Menampilkan {{ $pendaftar->count() }} pendaftar
                </div>
            </div>
            @endif
        @endif
    </div>
</div>
@endsection

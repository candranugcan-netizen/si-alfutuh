@extends('layouts.app')
@section('title', 'Data Inventaris Sekolah')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Buku Induk Inventaris</h1>
    <div>
        <a href="{{ route('inventaris.exportExcel') }}" class="btn btn-sm btn-success shadow-sm mr-2">
            <i class="fas fa-file-excel fa-sm text-white-50"></i> Excel
        </a>
        <a href="{{ route('inventaris.exportPdf') }}" target="_blank" class="btn btn-sm btn-danger shadow-sm mr-2">
            <i class="fas fa-file-pdf fa-sm text-white-50"></i> PDF
        </a>
        <a href="{{ route('inventaris.cetakQr') }}" target="_blank" class="btn btn-sm btn-info shadow-sm mr-2">
            <i class="fas fa-qrcode fa-sm text-white-50"></i> Cetak Label QR
        </a>
        <a href="{{ route('inventaris.pencatatan.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang Baru
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- CARD FILTER DATA -->
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-light">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Filter & Pencarian Aset</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('inventaris.pencatatan.index') }}">
            <div class="form-row align-items-center">
                <div class="col-md-3 mb-2">
                    <label class="sr-only">Cari</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama, kode, atau merk..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="sr-only">Kategori</label>
                    <select name="kategori_id" class="form-control form-control-sm">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="sr-only">Ruangan</label>
                    <select name="ruangan_id" class="form-control form-control-sm">
                        <option value="">-- Semua Ruangan --</option>
                        @foreach($ruangan as $r)
                            <option value="{{ $r->id }}" {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="sr-only">Kondisi</label>
                    <select name="kondisi" class="form-control form-control-sm">
                        <option value="">-- Semua Kondisi --</option>
                        <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div class="col-md-1 mb-2">
                    <button type="submit" class="btn btn-sm btn-primary btn-block" title="Terapkan Filter">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            @if(request()->anyFilled(['search', 'kategori_id', 'ruangan_id', 'kondisi']))
                <div class="mt-2">
                    <a href="{{ route('inventaris.pencatatan.index') }}" class="btn btn-sm btn-secondary btn-block">Reset Filter</a>
                </div>
            @endif
        </form>
    </div>
</div>

<!-- TABEL DATA -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Seluruh Aset Sekolah</h6>
        <small class="text-muted">Menampilkan {{ $inventaris->firstItem() ?? 0 }} - {{ $inventaris->lastItem() ?? 0 }} dari {{ $inventaris->total() }} barang</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead class="bg-light text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode Barang</th>
                        <th width="20%">Nama Barang / Merek</th>
                        <th width="15%">Kategori & Ruangan</th>
                        <th width="8%">Total</th>
                        <th width="8%">Tersedia</th>
                        <th width="10%">Kondisi</th>
                        <th width="19%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventaris as $index => $item)
                    <tr>
                        <!-- Penomoran otomatis yang mendukung paginasi -->
                        <td class="text-center">{{ $inventaris->firstItem() + $index }}</td>
                        <td class="text-center"><code>{{ $item->kode_barang }}</code></td>
                        <td>
                            <strong>{{ $item->nama_barang }}</strong><br>
                            <small class="text-muted">Merek: {{ $item->merk ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}</span><br>
                            <small class="text-dark font-weight-bold"><i class="fas fa-map-marker-alt text-danger"></i> {{ $item->ruangan->nama_ruangan ?? 'Belum Ditempatkan' }}</small>
                        </td>
                        <td class="text-center">{{ $item->jumlah_total }}</td>
                        <td class="text-center font-weight-bold text-success">{{ $item->jumlah_tersedia }}</td>
                        <td class="text-center">
                            @if($item->kondisi == 'Baik') <span class="badge badge-success">Baik</span>
                            @elseif($item->kondisi == 'Rusak Ringan') <span class="badge badge-warning">Rusak Ringan</span>
                            @else <span class="badge badge-danger">Rusak Berat</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('inventaris.pencatatan.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail & Riwayat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('inventaris.pencatatan.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit Barang / Mutasi">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('inventaris.pencatatan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Barang tidak ditemukan / belum ada data inventaris.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- LINK PAGINASI -->
        <div class="d-flex justify-content-end mt-3">
            {{ $inventaris->links() }}
        </div>
    </div>
</div>
@endsection

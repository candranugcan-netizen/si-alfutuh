@extends('layouts.app')
@section('title', 'Data Inventaris Sekolah')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Buku Induk Inventaris</h1>
    <div>
        <a href="{{ route('inventaris.exportExcel') }}" class="btn btn-sm btn-success shadow-sm mr-2">
            <i class="fas fa-file-excel fa-sm text-white-50"></i> Excel
        </a>

        <!-- Tombol Export PDF -->
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
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Seluruh Aset Sekolah</h6>
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
                        <th width="10%">Total</th>
                        <th width="10%">Tersedia</th>
                        <th width="10%">Kondisi</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventaris as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center"><code>{{ $item->kode_barang }}</code></td>
                        <td>
                            <strong>{{ $item->nama_barang }}</strong><br>
                            <small class="text-muted">Merek: {{ $item->merk ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}</span><br>
                            <small>{{ $item->ruangan->nama_ruangan ?? 'Belum Ditempatkan' }}</small>
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
                            <form action="{{ route('inventaris.pencatatan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data inventaris.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

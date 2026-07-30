@extends('layouts.app')
@section('title', 'Sirkulasi Peminjaman Barang')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Sirkulasi Peminjaman Barang</h1>
    <div>
        <a href="{{ route('inventaris.peminjaman.exportPdf') }}" target="_blank" class="btn btn-sm btn-danger shadow-sm mr-2">
            <i class="fas fa-file-pdf fa-sm text-white-50"></i> Export Laporan PDF
        </a>
        <a href="{{ route('inventaris.peminjaman.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Catat Peminjaman Baru
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
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- CARD FILTER DATA -->
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-light">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Filter Sirkulasi Peminjaman</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('inventaris.peminjaman.index') }}">
            <div class="form-row align-items-center">
                <div class="col-md-4 mb-2">
                    <label class="sr-only">Cari</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari peminjam, kode, atau nama barang..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="sr-only">Status</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="sr-only">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control form-control-sm" value="{{ request('tanggal_pinjam') }}" title="Filter Tanggal Pinjam">
                </div>
                <div class="col-md-1 mb-2">
                    <button type="submit" class="btn btn-sm btn-primary btn-block" title="Terapkan Filter">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            @if(request()->anyFilled(['search', 'status', 'tanggal_pinjam']))
                <div class="mt-2">
                    <a href="{{ route('inventaris.peminjaman.index') }}" class="btn btn-sm btn-secondary btn-block">Reset Filter</a>
                </div>
            @endif
        </form>
    </div>
</div>

<!-- TABEL DATA PEMINJAMAN -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Peminjaman</h6>
        <small class="text-muted">Menampilkan {{ $peminjaman->firstItem() ?? 0 }} - {{ $peminjaman->lastItem() ?? 0 }} dari {{ $peminjaman->total() }} riwayat</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead class="bg-light text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Barang</th>
                        <th width="20%">Peminjam</th>
                        <th width="8%">Jumlah</th>
                        <th width="12%">Tgl Pinjam</th>
                        <th width="12%">Batas Kembali</th>
                        <th width="10%">Status</th>
                        <th width="13%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $index => $row)
                    <tr>
                        <td class="text-center">{{ $peminjaman->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $row->inventaris->nama_barang ?? 'Barang Dihapus' }}</strong><br>
                            <small class="text-muted"><code>{{ $row->inventaris->kode_barang ?? '-' }}</code></small>
                        </td>
                        <td>
                            <strong>{{ $row->nama_peminjam }}</strong><br>
                            <small class="text-muted">{{ $row->peran_peminjam ?? '-' }}</small>
                        </td>
                        <td class="text-center font-weight-bold">{{ $row->jumlah_pinjam }} unit</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            {{ $row->batas_waktu_kembali ? \Carbon\Carbon::parse($row->batas_waktu_kembali)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-center">
                            @if($row->status == 'Dipinjam')
                                <span class="badge badge-warning">Dipinjam</span>
                            @else
                                <span class="badge badge-success">Dikembalikan</span><br>
                                <small class="text-muted">{{ $row->tanggal_dikembalikan ? \Carbon\Carbon::parse($row->tanggal_dikembalikan)->format('d/m/Y') : '' }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('inventaris.peminjaman.cetakSurat', $row->id) }}" target="_blank" class="btn btn-sm btn-info" title="Cetak Surat Bukti Peminjaman">
                                <i class="fas fa-print"></i>
                            </a>

                            @if($row->status == 'Dipinjam')
                                <form action="{{ route('inventaris.peminjaman.kembalikan', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Proses pengembalian barang ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Kembalikan Barang">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('inventaris.peminjaman.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan peminjaman ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Catatan"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Data peminjaman tidak ditemukan / belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- LINK PAGINASI -->
        <div class="d-flex justify-content-end mt-3">
            {{ $peminjaman->links() }}
        </div>
    </div>
</div>
@endsection

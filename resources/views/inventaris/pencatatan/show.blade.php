@extends('layouts.app')
@section('title', 'Detail Inventaris & Riwayat')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Aset & Rekam Jejak</h1>
    <a href="{{ route('inventaris.pencatatan.index') }}" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">Informasi Barang</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><th>Kode Barang</th><td>: <code>{{ $item->kode_barang }}</code></td></tr>
                    <tr><th>Nama Barang</th><td>: <strong>{{ $item->nama_barang }}</strong></td></tr>
                    <tr><th>Merek</th><td>: {{ $item->merk ?? '-' }}</td></tr>
                    <tr><th>Kategori</th><td>: <span class="badge badge-info">{{ $item->kategori->nama_kategori ?? '-' }}</span></td></tr>
                    <tr><th>Lokasi Ruangan</th><td>: <span class="badge badge-success">{{ $item->ruangan->nama_ruangan ?? 'Belum Ditempatkan' }}</span></td></tr>
                    <tr><th>Total / Tersedia</th><td>: {{ $item->jumlah_total }} / <strong>{{ $item->jumlah_tersedia }} unit</strong></td></tr>
                    <tr><th>Kondisi</th><td>: {{ $item->kondisi }}</td></tr>
                    <tr><th>Sumber Dana</th><td>: {{ $item->sumber_dana ?? '-' }}</td></tr>
                    <tr><th>Tanggal Pengadaan</th><td>: {{ $item->tanggal_pengadaan ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-info text-white">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-history"></i> Log Perubahan & Mutasi</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($item->riwayatPerubahan as $log)
                        <li class="list-group-item pl-0 pr-0">
                            <small class="text-muted d-block">{{ $log->created_at->format('d M Y, H:i') }} | Oleh: <strong>{{ $log->petugas }}</strong></small>
                            <strong class="text-dark">{{ $log->jenis_perubahan }}</strong>
                            <p class="mb-0 text-secondary small">{{ $log->keterangan }}</p>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">Belum ada riwayat perubahan.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

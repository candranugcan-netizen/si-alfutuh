@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Kas Keuangan Komite</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Pencatatan Pemasukan & Pengeluaran Kas Komite</li>
    </ol>

    {{-- Ringkasan Saldo --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pemasukan</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Saldo Akumulasi</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Input Transaksi --}}
    <div class="card mb-4 shadow">
        <div class="card-header font-weight-bold">Tambah Transaksi Baru</div>
        <div class="card-body">
            <form action="{{ route('keuangan-komite.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Jenis Transaksi</label>
                        <select name="jenis_transaksi" class="form-control" required>
                            <option value="pemasukan">Pemasukan (+)</option>
                            <option value="pengeluaran">Pengeluaran (-)</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Kategori</label>
                        <input type="text" name="kategori" class="form-control" placeholder="Contoh: Infaq, Event, Konsumsi" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Nominal (Rp)</label>
                        <input type="number" name="nominal" class="form-control" min="0" placeholder="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Catatan rinci transaksi">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Bukti Nota (Opsional)</label>
                        <input type="file" name="bukti_nota" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="card mb-4 shadow">
        <div class="card-header font-weight-bold">Riwayat Transaksi</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Penginput</th>
                            <th>Kategori</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                            <th>Nota</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $item)
                        <tr>
                            <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $item->komite->nama_komite ?? 'Admin' }}</td>
                            <td>{{ $item->kategori }}</td>
                            <td>
                                <span class="badge {{ $item->jenis_transaksi == 'pemasukan' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                    {{ ucfirst($item->jenis_transaksi) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>
                                @if($item->bukti_nota)
                                    <a href="{{ asset('storage/bukti_komite/' . $item->bukti_nota) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('keuangan-komite.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data transaksi kas komite.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $transaksi->links() }}
        </div>
    </div>
</div>
@endsection

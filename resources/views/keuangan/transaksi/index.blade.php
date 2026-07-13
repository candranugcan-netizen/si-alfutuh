@extends('layouts.app')
@section('title', 'Buku Kas Utama')

@section('content')
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Pemasukan {{ request('pos_dana') ? '(Pos Terpilih)' : '' }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-arrow-down fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Pengeluaran {{ request('pos_dana') ? '(Pos Terpilih)' : '' }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-arrow-up fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Saldo Akhir {{ request('pos_dana') ? '(Pos Terpilih)' : '' }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-wallet fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Buku Kas Utama</h1>
    <div>
    <a href="{{ route('keuangan.transaksi.exportExcel', request()->query()) }}" class="btn btn-sm btn-success shadow-sm">
        <i class="fas fa-file-excel"></i> Excel
    </a>

    <a href="{{ route('keuangan.transaksi.exportPdf', request()->query()) }}" target="_blank" class="btn btn-sm btn-danger shadow-sm">
        <i class="fas fa-file-pdf"></i> PDF
    </a>

    <a href="{{ route('keuangan.transaksi.create') }}" class="btn btn-sm btn-success shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Catat Transaksi Baru
    </a>
    </div>
</div>

{{-- ALERT PENANDA FILTER AKTIF --}}
@if(request('pos_dana'))
    <div class="alert alert-warning border-left-warning shadow-sm mb-4">
        <i class="fas fa-exclamation-triangle"></i> Saat ini Anda sedang memfilter data berdasarkan <strong>Pos Kantong Dana</strong>. Nominal Pemasukan, Pengeluaran, dan Saldo di atas disesuaikan secara otomatis hanya untuk pos dana tersebut.
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-light">
        <h6 class="m-0 font-weight-bold text-secondary"><i class="fas fa-filter"></i> Filter & Pencarian Data</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('keuangan.transaksi.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label class="small font-weight-bold">Dari Tanggal</label>
                    <input type="date" class="form-control form-control-sm" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label class="small font-weight-bold">Sampai Tanggal</label>
                    <input type="date" class="form-control form-control-sm" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label class="small font-weight-bold">Jenis Kas</label>
                    <select name="jenis" class="form-control form-control-sm">
                        <option value="">-- Semua Jenis --</option>
                        <option value="Pemasukan" {{ request('jenis') == 'Pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="Pengeluaran" {{ request('jenis') == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label class="small font-weight-bold">Kategori Transaksi</label>
                    <select name="kategori_transaksi_id" class="form-control form-control-sm">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_transaksi_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }} ({{ $k->jenis }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-3 form-group">
                    <label class="small font-weight-bold text-warning"><i class="fas fa-wallet"></i> Filter Pos Kantong Dana</label>
                    <select name="pos_dana" class="form-control form-control-sm border-warning">
                        <option value="">-- Semua Saldo Sekolah --</option>
                        @foreach($kategoriPemasukan as $kp)
                            <option value="{{ $kp->id }}" {{ request('pos_dana') == $kp->id ? 'selected' : '' }}>
                                Saldo: {{ $kp->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 form-group">
                    <label class="small font-weight-bold">Kata Kunci Pencarian</label>
                    <input type="text" class="form-control form-control-sm" name="keyword" value="{{ request('keyword') }}" placeholder="Cari berdasarkan No. Transaksi, Nama Siswa, atau keterangan...">
                </div>

                <div class="col-md-3 form-group align-self-end">
                    <button type="submit" class="btn btn-sm btn-primary btn-block mb-1"><i class="fas fa-search"></i> Terapkan Filter</button>
                    @if(request()->anyFilled(['start_date', 'end_date', 'jenis', 'kategori_transaksi_id', 'pos_dana', 'keyword']))
                        <a href="{{ route('keuangan.transaksi.index') }}" class="btn btn-sm btn-secondary btn-block">Reset Filter</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10%">Tanggal</th>
                        <th>No Transaksi</th>
                        <th>Kategori</th>
                        <th>Keterangan / Alokasi Dana</th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                        <th width="5%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                    <tr>
                        <td>{{ date('d-m-Y', strtotime($t->tanggal)) }}</td>
                        <td><code>{{ $t->no_transaksi }}</code></td>
                        <td>{{ $t->kategori->nama_kategori }}</td>
                        <td>
                            @if($t->siswa)
                                <span class="badge badge-info">Siswa</span> <strong>{{ $t->siswa->nama_siswa }}</strong> ({{ $t->siswa->kelas->nama_kelas }}) <br>
                                <small class="text-muted">Bulan Pembayaran: <strong>{{ $t->untuk_bulan }}</strong></small>
                            @else
                                {{ $t->keterangan ?? '-' }}
                            @endif

                            {{-- REVISI TABEL: Menampilkan status pemotongan kantong dana khusus Pengeluaran --}}
                            @if($t->jenis == 'Pengeluaran')
                                <div class="mt-1">
                                    @if($t->sumberDana)
                                        <span class="badge badge-warning text-dark"><i class="fas fa-wallet"></i> Memotong Dana: {{ $t->sumberDana->nama_kategori }}</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-globe"></i> Memotong: Kas Umum / Bebas</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $t->jenis == 'Pemasukan' ? 'badge-success' : 'badge-danger' }}">
                                {{ $t->jenis }}
                            </span>
                        </td>
                        <td class="font-weight-bold {{ $t->jenis == 'Pemasukan' ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($t->nominal, 0, ',', '.') }}
                        </td>
                        <td>
                            <form action="{{ route('keuangan.transaksi.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus catatan transaksi ini? Saldo akan terkoreksi otomatis.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Data transaksi tidak ditemukan atau masih kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Menampilkan {{ $transaksi->firstItem() ?? 0 }} sampai {{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} data transaksi.
                </small>
                <div>
                    {{ $transaksi->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

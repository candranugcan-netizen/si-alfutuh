<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sirkulasi Peminjaman Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 0; text-transform: uppercase; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 6px; }
        .table th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h3>Laporan Sirkulasi Peminjaman Barang</h3>
        <p>RA AL FUTUH - Tanggal Cetak: {{ date('d/m/Y') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="15%">Kode Barang</th>
                <th width="20%">Nama Barang</th>
                <th width="20%">Peminjam</th>
                <th width="8%">Jumlah</th>
                <th width="11%">Tgl Pinjam</th>
                <th width="11%">Batas Kembali</th>
                <th width="11%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center"><code>{{ $row->inventaris->kode_barang ?? '-' }}</code></td>
                <td>{{ $row->inventaris->nama_barang ?? 'Barang Dihapus' }}</td>
                <td>{{ $row->nama_peminjam }} <br><small>({{ $row->peran_peminjam ?? '-' }})</small></td>
                <td class="text-center">{{ $row->jumlah_pinjam }} unit</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal_pinjam)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $row->batas_waktu_kembali ? \Carbon\Carbon::parse($row->batas_waktu_kembali)->format('d/m/Y') : '-' }}</td>
                <td class="text-center">
                    <strong>{{ $row->status }}</strong>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada data peminjaman barang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
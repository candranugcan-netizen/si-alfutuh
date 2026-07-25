<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku Induk Inventaris</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 14pt; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #f2f2f2; border: 1px solid #000; padding: 8px; text-align: center; }
        td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Buku Induk Inventaris</h2>
        <p><strong>RA Al Futuh</strong></p>
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Barang</th>
                <th width="25%">Nama Barang / Merek</th>
                <th width="15%">Kategori</th>
                <th width="15%">Ruangan / Lokasi</th>
                <th width="10%">Total</th>
                <th width="15%">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventaris as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center"><code>{{ $item->kode_barang }}</code></td>
                <td>
                    <strong>{{ $item->nama_barang }}</strong>
                    @if($item->merk) <br><span style="font-size: 8pt; color: #555;">Merek: {{ $item->merk }}</span> @endif
                </td>
                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                <td class="text-center">{{ $item->jumlah_total }}</td>
                <td class="text-center">{{ $item->kondisi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada data inventaris.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

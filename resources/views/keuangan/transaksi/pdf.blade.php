<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Buku Kas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px double #333; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; text-transform: uppercase; color: #2e7d32; }
        .header p { margin: 5px 0 0 0; font-size: 10pt; color: #666; }
        .info-filter { margin-bottom: 15px; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: left; }
        th, td { border: 1px solid #ddd; padding: 8px; font-size: 9.5pt; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .pemasukan { color: #2e7d32; font-weight: bold; }
        .pengeluaran { color: #c62828; font-weight: bold; }
        .summary-box { width: 40%; float: right; margin-top: 10px; border: 1px solid #333; padding: 10px; background: #fafafa; }
        .summary-title { font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>RA AL FUTUH</h2>
        <p>Laporan Buku Kas Utama Keuangan Sekolah</p>
        <p style="font-style: italic;">Waktu Cetak: {{ date('d-m-Y H:i') }}</p>
    </div>

    <div class="info-filter">
        <strong>Periode/Filter Aktif:</strong> <br>
        Tanggal: {{ $request->start_date ? date('d/m/Y', strtotime($request->start_date)) : 'Semua' }} s/d {{ $request->end_date ? date('d/m/Y', strtotime($request->end_date)) : 'Semua' }} |
        Jenis: {{ $request->jenis ?? 'Semua Arus Uang' }} |
        Keyword: {{ $request->keyword ?? '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="12%">Tanggal</th>
                <th width="18%">No Transaksi</th>
                <th width="15%">Kategori</th>
                <th>Keterangan / Siswa</th>
                <th width="18%" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
            <tr>
                <td class="text-center">{{ date('d/m/Y', strtotime($t->tanggal)) }}</td>
                <td><code>{{ $t->no_transaksi }}</code></td>
                <td>{{ $t->kategori->nama_kategori }}</td>
                <td>
                    @if($t->siswa)
                        Siswa: {{ $t->siswa->nama_siswa }} (Bulan: {{ $t->untuk_bulan }})
                    @else
                        {{ $t->keterangan ?? '-' }}
                    @endif
                </td>
                <td class="text-right {{ $t->jenis == 'Pemasukan' ? 'pemasukan' : 'pengeluaran' }}">
                    {{ $t->jenis == 'Pemasukan' ? '(+) ' : '(-) ' }}Rp {{ number_format($t->nominal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-title">Ringkasan Kas Terfilter:</div>
        <table style="border: none; margin: 0; width: 100%;">
            <tr style="border: none;">
                <td style="border: none; padding: 2px 0;">Total Pemasukan</td>
                <td style="border: none; padding: 2px 0;" class="text-right pemasukan">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 2px 0;">Total Pengeluaran</td>
                <td style="border: none; padding: 2px 0;" class="text-right pengeluaran">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr style="border: none; border-top: 1px solid #ddd;">
                <td style="border: none; padding: 5px 0; font-weight: bold;">Saldo Bersih</td>
                <td style="border: none; padding: 5px 0; font-weight: bold;" class="text-right">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

</body>
</html>

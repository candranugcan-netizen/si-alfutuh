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
        .summary-box { width: 40%; float: right; border: 1px solid #ddd; padding: 15px; border-radius: 5px; background-color: #f9f9f9; }
        .summary-title { font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        .text-muted { color: #666; font-size: 8.5pt; }

        /* CSS Khusus Badge Sumber Dana di PDF */
        .badge { display: inline-block; padding: 3px 6px; font-size: 8pt; border-radius: 3px; background-color: #fcf8e3; border: 1px solid #faebcc; color: #8a6d3b; margin-top: 5px; }
        .badge-umum { background-color: #f5f5f5; border: 1px solid #e3e3e3; color: #333; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Keuangan Kas Sekolah</h2>
        <p>RA Al Futuh</p>
    </div>

    <div class="info-filter">
        <strong>Periode:</strong> {{ $request->start_date && $request->end_date ? date('d/m/Y', strtotime($request->start_date)) . ' s/d ' . date('d/m/Y', strtotime($request->end_date)) : 'Semua Waktu' }}<br>
        <strong>Jenis Kas:</strong> {{ $request->jenis ?? 'Semua Jenis Kas' }}<br>

        {{-- Notifikasi jika difilter berdasarkan Pos Dana Khusus --}}
        @if($request->pos_dana)
            <strong>Fokus Laporan:</strong> <span style="color: #c62828; font-weight: bold;">[DIFILTER] Hanya menampilkan perputaran uang dari POS KANTONG DANA TERPILIH.</span>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="12%">Tanggal</th>
                <th width="15%">No. Transaksi</th>
                <th width="18%">Kategori</th>
                <th width="35%">Keterangan / Alokasi</th>
                <th width="20%" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
            <tr>
                <td>{{ date('d-m-Y', strtotime($t->tanggal)) }}</td>
                <td>{{ $t->no_transaksi }}</td>
                <td>
                    {{ $t->kategori->nama_kategori }}<br>
                    <span style="font-size: 8pt; color: #666;">({{ $t->jenis }})</span>
                </td>
                <td>
                    @if($t->siswa)
                        <strong>{{ $t->siswa->nama_siswa }}</strong> ({{ $t->siswa->kelas->nama_kelas }})<br>
                        <span class="text-muted">Bulan: {{ $t->untuk_bulan }}</span>
                    @else
                        {{ $t->keterangan ?? '-' }}
                    @endif

                    {{-- FITUR BARU: Cetak Info Sumber Dana di tabel PDF --}}
                    @if($t->jenis == 'Pengeluaran')
                        <div>
                            @if($t->sumberDana)
                                <span class="badge">Memotong: {{ $t->sumberDana->nama_kategori }}</span>
                            @else
                                <span class="badge badge-umum">Memotong: Kas Umum / Bebas</span>
                            @endif
                        </div>
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
                <td style="border: none; padding: 5px 0; font-weight: bold; color: {{ $saldoAkhir >= 0 ? '#2e7d32' : '#c62828' }};" class="text-right">
                    Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

</body>
</html>

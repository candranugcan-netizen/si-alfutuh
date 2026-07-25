<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label QR Code Inventaris</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .action-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            margin: 0 5px;
            text-decoration: none;
        }
        .btn-print { background-color: #4e73df; color: white; }
        .btn-back { background-color: #858796; color: white; }

        /* Area Kertas Cetak */
        .print-area {
            background-color: white;
            padding: 20px;
            max-width: 210mm; /* Ukuran A4 */
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 Kolom */
            gap: 15px;
        }

        /* Desain Stiker QR */
        .qr-card {
            border: 2px dashed #ccc;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            page-break-inside: avoid;
        }
        .school-name {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .item-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .qr-code { margin: 10px 0; }
        .item-code {
            font-size: 12px;
            font-family: monospace;
            font-weight: bold;
        }

        /* Mode Print - Sembunyikan Tombol dan Shadow */
        @media print {
            body { background-color: white; padding: 0; }
            .action-bar { display: none; }
            .print-area { box-shadow: none; max-width: 100%; padding: 0; }
            .qr-card { border: 1px solid #000; }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="{{ route('inventaris.pencatatan.index') }}" class="btn btn-back">Kembali ke Daftar</a>
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Label Sekarang</button>
    </div>

    <div class="print-area">
        @forelse($inventaris as $item)
        <div class="qr-card">
            <div class="school-name">RA Al Futuh - Inventaris</div>
            <div class="item-name">{{ $item->nama_barang }}</div>
            <div class="qr-code">
                {{-- Syntax untuk memanggil library QR Code di Blade --}}
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(90)->generate($item->kode_barang) !!}
            </div>
            <div class="item-code">{{ $item->kode_barang }}</div>
        </div>
        @empty
        <div style="grid-column: span 3; text-align: center; padding: 20px;">
            Belum ada data barang untuk dicetak.
        </div>
        @endforelse
    </div>

</body>
</html>

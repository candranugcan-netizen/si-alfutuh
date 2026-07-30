<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Peminjaman Barang</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
        }

        /* CSS Kop Surat Gambar */
        .header-kop {
            width: 100%;
            margin-bottom: 15px;
            text-align: center;
        }
        .header-kop img {
            width: 100%;
            height: auto;
            max-height: 250px; /* Sesuaikan jika perlu */
            object-fit: contain;
        }

        .title {
            text-align: center;
            text-transform: uppercase;
            text-decoration: underline;
            font-size: 13pt;
            margin-bottom: 20px;
        }
        .table-detail {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .table-detail td {
            padding: 6px 4px;
            vertical-align: top;
        }
        .ttd-container {
            width: 100%;
            margin-top: 40px;
        }
        .ttd-box {
            width: 45%;
            float: left;
            text-align: center;
        }
        .ttd-box-right {
            width: 45%;
            float: right;
            text-align: center;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT GAMBAR PNG -->
    <div class="header-kop">
        @if(file_exists(public_path('img/kop_surat.png')))
            <img src="{{ public_path('img/kop_surat.png') }}" alt="Kop Surat">
        @else
            <!-- Fallback jika file gambar belum ada di folder public/images/ -->
            <div style="border-bottom: 2px solid #000; padding-bottom: 10px;">
                <h2 style="margin: 0; text-transform: uppercase;">RA AL FUTUH</h2>
                <p style="margin: 2px 0; font-size: 10pt;">Kop surat PNG belum ditemukan di: public/images/kop_surat.png</p>
            </div>
        @endif
    </div>

    <h3 class="title">Surat Bukti Peminjaman Barang</h3>

    <p>Yang bertanda tangan di bawah ini menyatakan bahwa barang milik RA Al Futuh berikut telah dipinjam dengan rincian:</p>

    <table class="table-detail">
        <tr>
            <td width="30%"><strong>Nama Peminjam</strong></td>
            <td width="5%">:</td>
            <td>{{ $peminjaman->nama_peminjam }} ({{ $peminjaman->peran_peminjam ?? 'Umum' }})</td>
        </tr>
        <tr>
            <td><strong>Nama Barang</strong></td>
            <td>:</td>
            <td><strong>{{ $peminjaman->inventaris->nama_barang ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td><strong>Kode Barang</strong></td>
            <td>:</td>
            <td><code>{{ $peminjaman->inventaris->kode_barang ?? '-' }}</code></td>
        </tr>
        <tr>
            <td><strong>Jumlah Pinjam</strong></td>
            <td>:</td>
            <td>{{ $peminjaman->jumlah_pinjam }} unit</td>
        </tr>
        <tr>
            <td><strong>Tanggal Pinjam</strong></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Batas Waktu Kembali</strong></td>
            <td>:</td>
            <td>{{ $peminjaman->batas_waktu_kembali ? \Carbon\Carbon::parse($peminjaman->batas_waktu_kembali)->translatedFormat('d F Y') : 'Tidak Ditentukan' }}</td>
        </tr>
        <tr>
            <td><strong>Status Peminjaman</strong></td>
            <td>:</td>
            <td><strong>{{ strtoupper($peminjaman->status) }}</strong></td>
        </tr>
        <tr>
            <td><strong>Keterangan / Keperluan</strong></td>
            <td>:</td>
            <td>{{ $peminjaman->keterangan ?? '-' }}</td>
        </tr>
    </table>

    <p style="font-size: 9.5pt; font-style: italic;">
        * Peminjam bertanggung jawab penuh atas keutuhan dan keselamatan barang yang dipinjam selama masa peminjaman.
    </p>

    <div class="ttd-container">
        <div class="ttd-box">
            <p>Peminjam,</p>
            <br><br><br>
            <p><strong>( {{ $peminjaman->nama_peminjam }} )</strong></p>
        </div>

        <div class="ttd-box-right">
            <p>Petugas Inventaris,</p>
            <br><br><br>
            <p><strong>( .................................... )</strong></p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>

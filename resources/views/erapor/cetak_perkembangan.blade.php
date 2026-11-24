<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perkembangan & Foto Bukti - {{ $erapor->siswa->nama_siswa }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.2cm 1.5cm 1.2cm 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10.5px;
            color: #222;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }
        .page-break {
            page-break-after: always;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-weight-bold { font-weight: bold; }

        /* Judul Dokumen */
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 2px;
            color: #111;
        }
        .doc-subtitle {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 12px;
            color: #111;
        }

        /* Tabel Identitas Header */
        table.info-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10.5px;
        }
        table.info-header td {
            border: 1px solid #4a6fa5;
            padding: 5px 8px;
        }
        .info-label {
            background-color: #dbe4ee;
            font-weight: bold;
            color: #1f3a60;
        }
        .info-val {
            font-weight: bold;
            color: #111;
        }

        /* Kotak Elemen Capaian Perkembangan */
        .element-box {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #333;
            margin-bottom: 14px;
        }
        .element-title-cell {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            padding: 6px;
            font-size: 11px;
            border-bottom: 1px solid #333;
        }
        .element-body-table {
            width: 100%;
            border-collapse: collapse;
        }
        .element-text-col {
            padding: 8px 10px;
            vertical-align: top;
            text-align: justify;
            font-size: 10.5px;
            line-height: 1.5;
        }
        .element-photo-col {
            width: 150px;
            padding: 8px;
            vertical-align: top;
            text-align: center;
            border-left: 1px dashed #ccc;
        }
        .element-photo-col img {
            width: 135px;
            height: auto;
            max-height: 150px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #bbb;
            margin-bottom: 5px;
        }
        .arabic-inline {
            font-size: 13px;
            color: #0b3d20;
            font-weight: bold;
            display: inline-block;
            margin-right: 4px;
        }

        /* Tabel Ringkasan Fisik & Absensi */
        table.summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        table.summary-table th, table.summary-table td {
            border: 1px solid #333;
            padding: 4px 6px;
            font-size: 10px;
        }
        table.summary-table th {
            background-color: #e8f5e9;
            text-align: center;
        }

        /* Tanda Tangan */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .ttd-table td {
            vertical-align: top;
            text-align: center;
            padding: 4px;
            font-size: 10.5px;
        }
        .ttd-space {
            height: 55px;
        }

        /* Tombol Cetak Browser */
        .no-print {
            background: #e8f5e9;
            border: 1px solid #a5d6a7;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; }
        }
    </style>
</head>
<body>

    @if(request()->has('preview'))
        <div class="no-print">
            <div>
                <strong>Mode Preview Cetak Browser:</strong> Laporan Perkembangan dengan Lampiran Foto Bukti.
            </div>
            <div>
                <button onclick="window.print()" style="padding: 6px 14px; background: #2e7d32; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    🖨️ Cetak Dokumen Ini
                </button>
                <a href="{{ route('erapor.cetakPerkembangan', $erapor->id) }}" style="margin-left: 10px; padding: 6px 14px; background: #c62828; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;">
                    📄 Unduh File PDF
                </a>
            </div>
        </div>
    @endif

    <div class="doc-title">LAPORAN PERKEMBANGAN ANAK</div>
    <div class="doc-subtitle">RA ISLAM AL FUTUH TAHUN AJARAN {{ $erapor->tahunAjaran->nama ?? '2024/2025' }}</div>

    <table class="info-header">
        <tr>
            <td width="15%" class="info-label">Nama RA</td>
            <td width="35%" class="info-val">: RA ISLAM AL FUTUH</td>
            <td width="15%" class="info-label">Kelas</td>
            <td width="35%" class="info-val">: {{ $erapor->siswa->kelas->nama_kelas ?? 'A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Nama Anak</td>
            <td class="info-val">: {{ strtoupper($erapor->siswa->nama_siswa) }}</td>
            <td class="info-label">Fase</td>
            <td class="info-val">: {{ $erapor->fase ?? 'Pondasi' }}</td>
        </tr>
        <tr>
            <td class="info-label">Semester</td>
            <td class="info-val">: {{ $erapor->semester == 'I' ? 'I ( Satu )' : 'II ( Dua )' }}</td>
            <td class="info-label">Tahun Ajaran</td>
            <td class="info-val">: {{ $erapor->tahunAjaran->nama ?? '2024/2025' }}</td>
        </tr>
    </table>

    <!-- 1. NILAI AGAMA ISLAM DAN BUDI PEKERTI -->
    <div class="element-box">
        <div class="element-title-cell">Nilai Agama Islam dan Budi Pekerti</div>
        <table class="element-body-table">
            <tr>
                <td class="element-text-col">
                    <span class="arabic-inline">ٱلْحَمْدُ لِلَّٰهِ رَبِّ ٱلْعَالَمِينَ</span>
                    {!! nl2br(e($erapor->narasi_agama_display)) !!}
                </td>
                <td class="element-photo-col">
                    @if(!empty($fotoMap['foto_agama_1']))
                        <img src="{{ $fotoMap['foto_agama_1'] }}" alt="Bukti Agama 1">
                    @endif
                    @if(!empty($fotoMap['foto_agama_2']))
                        <img src="{{ $fotoMap['foto_agama_2'] }}" alt="Bukti Agama 2">
                    @endif
                    @if(empty($fotoMap['foto_agama_1']) && empty($fotoMap['foto_agama_2']))
                        <div style="color: #999; font-size: 9px; padding: 25px 0;">[Foto Dokumentasi Kegiatan Ibadah / Mengaji]</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- 2. JATI DIRI -->
    <div class="element-box">
        <div class="element-title-cell">Jati Diri</div>
        <table class="element-body-table">
            <tr>
                <td class="element-text-col">
                    {!! nl2br(e($erapor->narasi_jati_diri_display)) !!}
                </td>
                <td class="element-photo-col">
                    @if(!empty($fotoMap['foto_jati_diri_1']))
                        <img src="{{ $fotoMap['foto_jati_diri_1'] }}" alt="Bukti Jati Diri 1">
                    @endif
                    @if(!empty($fotoMap['foto_jati_diri_2']))
                        <img src="{{ $fotoMap['foto_jati_diri_2'] }}" alt="Bukti Jati Diri 2">
                    @endif
                    @if(empty($fotoMap['foto_jati_diri_1']) && empty($fotoMap['foto_jati_diri_2']))
                        <div style="color: #999; font-size: 9px; padding: 25px 0;">[Foto Dokumentasi Kemandirian / Aktivitas]</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- 3. DASAR-DASAR LITERASI, STEAM -->
    <div class="element-box">
        <div class="element-title-cell">Dasar-dasar Literasi, Matematika, Sains, Teknologi, Rekayasa, Seni</div>
        <table class="element-body-table">
            <tr>
                <td class="element-text-col">
                    {!! nl2br(e($erapor->narasi_literasi_steam_display)) !!}
                </td>
                <td class="element-photo-col">
                    @if(!empty($fotoMap['foto_literasi_steam_1']))
                        <img src="{{ $fotoMap['foto_literasi_steam_1'] }}" alt="Bukti Literasi STEAM 1">
                    @endif
                    @if(!empty($fotoMap['foto_literasi_steam_2']))
                        <img src="{{ $fotoMap['foto_literasi_steam_2'] }}" alt="Bukti Literasi STEAM 2">
                    @endif
                    @if(empty($fotoMap['foto_literasi_steam_1']) && empty($fotoMap['foto_literasi_steam_2']))
                        <div style="color: #999; font-size: 9px; padding: 25px 0;">[Foto Dokumentasi Hasil Karya & Literasi]</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- 4. KOKURIKULER / PROJEK P5-PPRA -->
    <div class="element-box">
        <div class="element-title-cell">Kokurikuler (Projek Penguatan Profil Pelajar Pancasila & Rahmatan Lil Alamin)</div>
        <table class="element-body-table">
            <tr>
                <td class="element-text-col">
                    @if($erapor->tema_projek)
                        <div style="font-weight: bold; margin-bottom: 5px; color: #1b4332;">
                            Tema: {{ $erapor->tema_projek }}
                        </div>
                    @endif
                    {!! nl2br(e($erapor->narasi_projek_display)) !!}
                </td>
                <td class="element-photo-col">
                    @if(!empty($fotoMap['foto_projek_1']))
                        <img src="{{ $fotoMap['foto_projek_1'] }}" alt="Bukti Projek 1">
                    @endif
                    @if(!empty($fotoMap['foto_projek_2']))
                        <img src="{{ $fotoMap['foto_projek_2'] }}" alt="Bukti Projek 2">
                    @endif
                    @if(empty($fotoMap['foto_projek_1']) && empty($fotoMap['foto_projek_2']))
                        <div style="color: #999; font-size: 9px; padding: 25px 0;">[Foto Dokumentasi Kegiatan Projek Kokurikuler]</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- RINGKASAN PERTUMBUHAN & KETIDAKHADIRAN -->
    <table class="summary-table">
        <tr>
            <th colspan="3" width="50%">PERTUMBUHAN FISIK</th>
            <th colspan="3" width="50%">KETIDAKHADIRAN</th>
        </tr>
        <tr>
            <td width="20%">Berat Badan</td>
            <td width="3%">:</td>
            <td width="27%" class="font-weight-bold">{{ $erapor->berat_badan ?? '-' }}</td>
            <td width="20%">Sakit</td>
            <td width="3%">:</td>
            <td width="27%" class="font-weight-bold">{{ $erapor->kehadiran_sakit ?? 0 }} hari</td>
        </tr>
        <tr>
            <td>Tinggi Badan</td>
            <td>:</td>
            <td class="font-weight-bold">{{ $erapor->tinggi_badan ?? '-' }}</td>
            <td>Izin</td>
            <td>:</td>
            <td class="font-weight-bold">{{ $erapor->kehadiran_izin ?? 0 }} hari</td>
        </tr>
        <tr>
            <td>Lingkar Kepala</td>
            <td>:</td>
            <td class="font-weight-bold">{{ $erapor->lingkar_kepala ?? '-' }}</td>
            <td>Tanpa Keterangan</td>
            <td>:</td>
            <td class="font-weight-bold">{{ $erapor->kehadiran_alfa ?? 0 }} hari</td>
        </tr>
    </table>

    @if($erapor->catatan_guru)
        <div style="border: 1px solid #333; padding: 6px 10px; margin-bottom: 12px; font-size: 10px; background-color: #fdfdfd;">
            <strong>Catatan Guru:</strong> {{ $erapor->catatan_guru }}
        </div>
    @endif

    <!-- TANDA TANGAN 3 PIHAK -->
    <table class="ttd-table">
        <tr>
            <td width="33%">
                Mengetahui,<br>
                Orang Tua / Wali<br>
                <div class="ttd-space"></div>
                <strong>..........................................</strong>
            </td>
            <td width="34%">
                Mengetahui,<br>
                Kepala RA Al Futuh<br>
                <div class="ttd-space"></div>
                <strong>{{ $erapor->nama_kepala_sekolah ?? 'Pradita Ovi Agustina, S.Pd' }}</strong><br>
                NIP. {{ $erapor->nip_kepala_sekolah ?? '-' }}
            </td>
            <td width="33%">
                {{ $erapor->tempat_cetak ?? 'Semin' }}, {{ $erapor->tanggal_rapor ? \Carbon\Carbon::parse($erapor->tanggal_rapor)->translatedFormat('d F Y') : '16 Juni 2025' }}<br>
                Guru Kelas<br>
                <div class="ttd-space"></div>
                <strong>{{ $erapor->nama_guru_kelas ?? 'Ati Aturahmi' }}</strong><br>
                NIP. {{ $erapor->nip_guru_kelas ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>

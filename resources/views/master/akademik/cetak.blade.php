<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perkembangan - {{ $siswa->nama_siswa }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 0; padding: 0; font-size: 12px; }
        .identitas { width: 100%; margin-bottom: 20px; }
        .identitas td { padding: 3px; font-weight: bold; }
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; background-color: #f2f2f2; padding: 5px; border-left: 4px solid #4e73df; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        table.data-table th { background-color: #e9ecef; }
        .text-center { text-align: center; }
        .footer { text-align: right; margin-top: 40px; }
        .signature { margin-top: 60px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="header">
        <h2>RA Al Futuh</h2>
        <p>Laporan Perkembangan Akademik dan Aktivitas Siswa</p>
    </div>

    <table class="identitas">
        <tr>
            <td width="15%">Nama Siswa</td>
            <td width="2%">:</td>
            <td width="40%">{{ $siswa->nama_siswa }}</td>
            <td width="15%">Kelas</td>
            <td width="2%">:</td>
            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIS</td>
            <td>:</td>
            <td>{{ $siswa->nis ?? '-' }}</td>
            <td>Status</td>
            <td>:</td>
            <td>{{ $siswa->status_siswa }}</td>
        </tr>
    </table>

    <div class="section-title">A. Perkembangan Bacaan Iqro / Al-Qur'an</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th width="35%">Capaian (Jilid - Hal)</th>
                <th width="20%">Kelancaran</th>
                <th width="25%">Catatan Guru</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa->perkembanganAlquran as $index => $quran)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($quran->tanggal)->format('d M Y') }}</td>
                <td>{{ $quran->jenis }} - {{ $quran->jilid }} ({{ $quran->halaman }})</td>
                <td>{{ $quran->status_kelancaran }}</td>
                <td>{{ $quran->catatan ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">Belum ada catatan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">B. Perkembangan AISMA</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th width="30%">Level Jilid</th>
                <th width="25%">Modul</th>
                <th width="25%">Catatan Guru</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa->perkembanganAisma as $index => $aisma)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($aisma->tanggal)->format('d M Y') }}</td>
                <td>{{ $aisma->level_jilid }}</td>
                <td>{{ $aisma->halaman_modul }}</td>
                <td>{{ $aisma->catatan ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">Belum ada catatan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">C. Rekam Aktivitas & Perkembangan Karakter</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="30%">Judul Kegiatan (Waktu)</th>
                <th width="65%">Narasi Perkembangan Anak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa->aktivitasKenangan as $index => $kenangan)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $kenangan->judul_aktivitas }}</strong><br>
                    <small>Smt {{ $kenangan->semester }} ({{ $kenangan->tahunAjaran->nama ?? '' }})</small>
                </td>
                <td>{{ $kenangan->narasi_perkembangan }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">Belum ada aktivitas yang direkam.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Gunungkidul, {{ date('d F Y') }}</p>
        <br><br><br>
        <p class="signature">Wali Kelas</p>
    </div>

</body>
</html>

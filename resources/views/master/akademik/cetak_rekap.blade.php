<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Perkembangan Kelas {{ $kelas->nama_kelas }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; font-size: 12px; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #555; padding: 6px; text-align: left; }
        table.data-table th { background-color: #d1ecf1; text-align: center; }
        .text-center { text-align: center; }
        .badge { padding: 3px 6px; background: #e2e3e5; border-radius: 4px; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Rekapitulasi Perkembangan Akademik Siswa</h2>
        <p>RA Al Futuh - Kelas: <strong>{{ $kelas->nama_kelas }}</strong> | Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%" rowspan="2">No</th>
                <th width="17%" rowspan="2">Nama Siswa</th>
                <th colspan="3">Capaian Iqro / Al-Qur'an Terakhir</th>
                <th colspan="3">Capaian AISMA Terakhir</th>
            </tr>
            <tr>
                <th width="12%">Tgl Update</th>
                <th width="15%">Jilid & Halaman</th>
                <th width="13%">Status</th>
                <th width="12%">Tgl Update</th>
                <th width="14%">Level Jilid</th>
                <th width="14%">Halaman Modul</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswaList as $index => $siswa)
                @php
                    $quranLatest = $siswa->perkembanganAlquran->first();
                    $aismaLatest = $siswa->perkembanganAisma->first();
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $siswa->nama_siswa }}</strong></td>

                    <td class="text-center">{{ $quranLatest ? \Carbon\Carbon::parse($quranLatest->tanggal)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $quranLatest ? $quranLatest->jenis . ' ' . $quranLatest->jilid . ' (' . $quranLatest->halaman . ')' : 'Belum ada data' }}</td>
                    <td class="text-center">
                        @if($quranLatest)
                            <span class="badge">{{ $quranLatest->status_kelancaran }}</span>
                        @else
                            -
                        @endif
                    </td>

                    <td class="text-center">{{ $aismaLatest ? \Carbon\Carbon::parse($aismaLatest->tanggal)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $aismaLatest ? $aismaLatest->level_jilid : 'Belum ada data' }}</td>
                    <td>{{ $aismaLatest ? $aismaLatest->halaman_modul : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data siswa di kelas ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

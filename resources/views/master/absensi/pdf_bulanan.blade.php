<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi Bulanan - {{ $kelas->nama_kelas }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
    </style>
</head>
<body>

    <h2 class="text-center">Rekapitulasi Absensi Bulanan</h2>

    @php
        $namaBulan = \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F');
    @endphp

    <table>
        <tr>
            <td style="border: none; width: 15%;" class="fw-bold">Kelas</td>
            <td style="border: none; width: 85%;">: {{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td style="border: none;" class="fw-bold">Bulan/Tahun</td>
            <td style="border: none;">: {{ $namaBulan }} {{ $tahun }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Siswa</th>
                <th width="15%">Hadir (Hari)</th>
                <th width="15%">Sakit (Hari)</th>
                <th width="15%">Izin (Hari)</th>
                <th width="15%">Alfa (Hari)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $index => $s)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $s->nama_siswa }}</td>
                    <td class="text-center">{{ $s->total_hadir }}</td>
                    <td class="text-center">{{ $s->total_sakit }}</td>
                    <td class="text-center">{{ $s->total_izin }}</td>
                    <td class="text-center">{{ $s->total_alfa }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi Kelas {{ $kelas->nama_kelas }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .status-Hadir { color: green; }
        .status-Sakit { color: blue; }
        .status-Izin { color: orange; }
        .status-Alfa { color: red; }
    </style>
</head>
<body>

    <h2 class="text-center">Laporan Absensi Siswa</h2>

    <table>
        <tr>
            <td style="border: none; width: 15%; fw-bold">Kelas</td>
            <td style="border: none; width: 85%;">: {{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td style="border: none; fw-bold">Tanggal</td>
            <td style="border: none;">: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Nama Siswa</th>
                <th width="20%">Status Kehadiran</th>
                <th width="30%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $index => $s)
                @php
                    $absen = $s->absensi->first();
                    $status = $absen->status ?? 'Belum Diabsen';
                    $keterangan = $absen->keterangan ?? '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $s->nama_siswa }}</td>
                    <td class="text-center fw-bold status-{{ $status }}">{{ $status }}</td>
                    <td>{{ $keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>

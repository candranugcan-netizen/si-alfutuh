<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kehadiran Guru</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px double #333; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; text-transform: uppercase; color: #2e7d32; }
        .header p { margin: 5px 0 0 0; font-size: 10pt; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; padding: 8px; border: 1px solid #ddd;}
        td { border: 1px solid #ddd; padding: 8px; font-size: 9.5pt; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Rekapitulasi Kehadiran Guru</h2>
        <p>RA Al Futuh</p>
        <p><strong>Periode: Bulan {{ $bulan }} Tahun {{ $tahun }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Guru</th>
                <th width="15%">Hadir</th>
                <th width="15%">Izin</th>
                <th width="15%">Sakit</th>
                <th width="15%">Alpa</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($rekap as $data)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $data['nama'] }}</td>
                <td class="text-center">{{ $data['hadir'] }}</td>
                <td class="text-center">{{ $data['izin'] }}</td>
                <td class="text-center">{{ $data['sakit'] }}</td>
                <td class="text-center">{{ $data['alpa'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada data absensi pada bulan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

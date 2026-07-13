<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiBulananExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $kelas_id, $bulan, $tahun;
    protected $no = 1;

    public function __construct($kelas_id, $bulan, $tahun)
    {
        $this->kelas_id = $kelas_id;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $bulan = $this->bulan;
        $tahun = $this->tahun;

        // KOREKSI: Tambahkan filter agar siswa yang Lulus tidak masuk laporan Excel
        return Siswa::where('kelas_id', $this->kelas_id)
            ->where('status_siswa', '!=', 'Lulus') // <--- Tambah baris ini
            ->get()
            ->map(function($siswa) use ($bulan, $tahun) {
                $absensi = $siswa->absensi()->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();

                $siswa->total_hadir = $absensi->where('status', 'Hadir')->count();
                $siswa->total_sakit = $absensi->where('status', 'Sakit')->count();
                $siswa->total_izin  = $absensi->where('status', 'Izin')->count();
                $siswa->total_alfa  = $absensi->where('status', 'Alfa')->count();

                return $siswa;
            });
    }

    public function headings(): array
    {
        $namaBulan = \Carbon\Carbon::create()->month((int)$this->bulan)->translatedFormat('F');

        return [
            ['Rekapitulasi Absensi Bulanan'],
            ['Bulan: ' . $namaBulan . ' ' . $this->tahun],
            [], // Baris kosong
            ['No', 'Nama Siswa', 'Hadir', 'Sakit', 'Izin', 'Alfa', 'Total Hari Terabsen']
        ];
    }

    public function map($siswa): array
    {
        return [
            $this->no++,
            $siswa->nama_siswa,
            $siswa->total_hadir,
            $siswa->total_sakit,
            $siswa->total_izin,
            $siswa->total_alfa,
            ($siswa->total_hadir + $siswa->total_sakit + $siswa->total_izin + $siswa->total_alfa)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            2    => ['font' => ['italic' => true]],
            4    => ['font' => ['bold' => true]],
        ];
    }
}

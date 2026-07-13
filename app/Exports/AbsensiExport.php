<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $kelas_id, $tanggal;
    protected $no = 1;

    public function __construct($kelas_id, $tanggal)
    {
        $this->kelas_id = $kelas_id;
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        return Siswa::where('kelas_id', $this->kelas_id)
            ->with(['absensi' => function($query) {
                $query->where('tanggal', $this->tanggal);
            }])->get();
    }

    public function headings(): array
    {
        return [
            ['Laporan Absensi Siswa'],
            ['Tanggal: ' . \Carbon\Carbon::parse($this->tanggal)->translatedFormat('d F Y')],
            [], // Baris kosong
            ['No', 'Nama Siswa', 'Status Kehadiran', 'Keterangan']
        ];
    }

    public function map($siswa): array
    {
        $absen = $siswa->absensi->first();
        return [
            $this->no++,
            $siswa->nama_siswa,
            $absen->status ?? 'Belum Diabsen',
            $absen->keterangan ?? '-'
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

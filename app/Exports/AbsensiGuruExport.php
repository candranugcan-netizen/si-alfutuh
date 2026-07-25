<?php

namespace App\Exports;

use App\Models\AbsensiGuru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiGuruExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        // Ambil semua absen di bulan dan tahun terpilih
        $absensi = AbsensiGuru::with('user')
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->get();

        // Kelompokkan dan hitung rekap per guru
        $rekap = [];
        foreach ($absensi as $absen) {
            $userId = $absen->user_id;
            if (!isset($rekap[$userId])) {
                $rekap[$userId] = (object)[
                    'nama' => $absen->user->name,
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alpa' => 0,
                ];
            }

            $status = strtolower($absen->status);
            if (isset($rekap[$userId]->$status)) {
                $rekap[$userId]->$status++;
            }
        }

        // Kembalikan sebagai collection agar bisa diproses Excel
        return collect(array_values($rekap));
    }

    public function headings(): array
    {
        return [
            'Nama Guru',
            'Total Hadir',
            'Total Izin',
            'Total Sakit',
            'Total Alpa',
        ];
    }

    public function map($row): array
    {
        return [
            $row->nama,
            $row->hadir,
            $row->izin,
            $row->sakit,
            $row->alpa,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

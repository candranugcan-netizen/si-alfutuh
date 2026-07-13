<?php

namespace App\Exports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GuruExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Guru::with('user')->latest()->get();
    }

    public function headings(): array
    {
        return ['NIK', 'Nama Guru', 'Jenis Kelamin', 'Email Login', 'No HP', 'Alamat'];
    }

    public function map($guru): array
    {
        return [
            $guru->nik,
            $guru->nama_guru,
            $guru->jenis_kelamin,
            $guru->user->email,
            $guru->no_hp,
            $guru->alamat,
        ];
    }
}

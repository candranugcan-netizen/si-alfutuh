<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Siswa::with(['kelas', 'orangTua.user'])->latest()->get();
    }

    public function headings(): array
    {
        // Kita samakan persis dengan key yang akan dibaca oleh sistem Import
        return [
            'id_kelas', 'nama_kelas', 'nis', 'nama_siswa', 'jenis_kelamin',
            'tempat_lahir', 'tanggal_lahir', 'nominal_spp', 'nama_wali', 'email_ortu', 'no_hp_ortu'
        ];
    }

    public function map($siswa): array
    {
        return [
            $siswa->kelas_id,
            $siswa->kelas->nama_kelas,
            $siswa->nis,
            $siswa->nama_siswa,
            $siswa->jenis_kelamin,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir,
            $siswa->nominal_spp, // <- Tambahan nominal spp
            $siswa->orangTua->nama_wali ?? '',
            $siswa->orangTua->user->email ?? '',
            $siswa->orangTua->no_hp ?? '',
        ];
    }
}

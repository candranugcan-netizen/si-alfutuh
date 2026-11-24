<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiswaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $siswa = $this->route('siswa');

        return [
            'kelas_id' => ['required', 'exists:kelas,id'],
            'nama_siswa' => ['required', 'string', 'max:100'],
            'nis' => ['nullable', 'string', 'max:20', 'unique:siswa,nis,' . $siswa->id],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:50'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string', 'max:500'],
            // Input data Orang Tua tambahan:
            'nama_wali' => ['required', 'string', 'max:100'],
            'email_ortu' => ['required', 'email', 'unique:users,email,' . $siswa->orangTua->user_id],
            'no_hp_ortu' => ['nullable', 'string', 'max:15'],
            'nominal_spp' => ['required', 'in:90000,95000,115000'],

            'foto_anak'      => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'akta_kelahiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'kartu_keluarga' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ];
    }
}

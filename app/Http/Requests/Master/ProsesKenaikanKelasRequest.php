<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ProsesKenaikanKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan hanya user yang berhak (seperti Admin) yang bisa memproses
        return true;
    }

    public function rules(): array
    {
        return [
            // Harus berupa array dan minimal 1 siswa dipilih
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['exists:siswa,id'],

            // Tahun ajaran saat siswa tersebut berada di kelas lama (untuk rekam jejak)
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],

            // Keterangan proses mutasi
            'keterangan' => ['required', 'in:Naik Kelas,Tinggal Kelas,Lulus'],

            // Kelas tujuan (Wajib jika Naik/Tinggal kelas, tidak wajib jika Lulus)
            'kelas_tujuan_id' => ['required_unless:keterangan,Lulus', 'nullable', 'exists:kelas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_ids.required' => 'Anda harus memilih minimal satu siswa.',
            'kelas_tujuan_id.required_unless' => 'Kelas tujuan harus dipilih jika siswa tidak lulus.',
        ];
    }
}

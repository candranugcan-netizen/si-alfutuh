<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StoreKelasRequest extends FormRequest // (Untuk file Update, ubah menjadi UpdateKelasRequest)
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kelas' => ['required', 'string', 'max:50'],
            'kelompok' => ['required', 'string', 'max:20'],
            'kapasitas' => ['required', 'integer', 'min:1']
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'kelompok.required' => 'Kelompok wajib dipilih/diisi.',
            'kapasitas.required' => 'Kapasitas kelas wajib diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
        ];
    }
}

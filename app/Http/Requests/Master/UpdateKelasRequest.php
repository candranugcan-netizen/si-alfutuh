<?php

namespace App\Http\Requests\Master;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKelasRequest extends FormRequest
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

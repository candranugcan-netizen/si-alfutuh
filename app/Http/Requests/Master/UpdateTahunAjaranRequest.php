<?php

namespace App\Http\Requests\Master;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTahunAjaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:50'],
            'semester' => ['required', 'in:Ganjil,Genap'],
            'is_active' => ['nullable', 'boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama Tahun Ajaran wajib diisi (Contoh: 2026/2027).',
            'semester.required' => 'Semester wajib dipilih.',
        ];
    }
}

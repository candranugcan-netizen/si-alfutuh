<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StoreAismaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'siswa_id'      => ['required', 'exists:siswa,id'],
            'tanggal'       => ['required', 'date'],
            'level_jilid'   => ['required', 'string', 'max:50'],
            'halaman_modul' => ['required', 'string', 'max:100'],
            'catatan'       => ['nullable', 'string'],
        ];
    }
}

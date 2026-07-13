<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlquranRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'siswa_id'          => ['required', 'exists:siswa,id'],
            'tanggal'           => ['required', 'date'],
            'jenis'             => ['required', 'in:Iqro,Al-Quran'],
            'jilid'             => ['required', 'string', 'max:50'],
            'halaman'           => ['required', 'string', 'max:100'],
            'status_kelancaran' => ['required', 'in:Belum Lancar,Cukup Lancar,Lancar'],
            'catatan'           => ['nullable', 'string'],
        ];
    }
}

<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StoreKenanganRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'siswa_id'            => ['required', 'exists:siswa,id'],
            'tahun_ajaran_id'     => ['required', 'exists:tahun_ajarans,id'],
            'semester'            => ['required', 'in:Ganjil,Genap'],
            'judul_aktivitas'     => ['required', 'string', 'max:150'],
            'narasi_perkembangan' => ['required', 'string'],
            'foto_aktivitas'      => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'], // Max 5MB sebelum dikompres
        ];
    }
}

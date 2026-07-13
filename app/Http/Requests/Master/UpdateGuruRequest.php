<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuruRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $guru = $this->route('guru'); // Mengambil data guru dari rute URL

        return [
            'nama_guru' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email,' . $guru->user_id],
            'nik' => ['nullable', 'string', 'max:20', 'unique:gurus,nik,' . $guru->id],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'no_hp' => ['nullable', 'string', 'max:15'],
            'alamat' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nis' => ['required', 'unique:students,nis'],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'string', 'max:1'],
            'kelas' => ['required', 'string', 'max:255'],
            'tahun_masuk' => ['required', 'string', 'max:255'],
            'ttl' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'kontak_ortu' => ['required', 'string', 'max:255', 'unique:students,kontak_ortu'],
            'status' => ['required', 'string'],
        ];
    }
}

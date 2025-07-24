<?php

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
        $id = $this->route('siswa'); // Assuming 'siswa' is the route parameter name

        return [
            'nis' => ['required', Rule::unique('students', 'nis')->ignore($id)],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'string', 'max:1'],
            'kelas' => ['required', 'string', 'max:255'],
            'tahun_masuk' => ['required', 'string', 'max:255'],
            'ttl' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'kontak_ortu' => ['required', 'string', 'max:255', Rule::unique('students', 'kontak_ortu')->ignore($id)],
            'status' => ['required', 'string'],
        ];
    }
}

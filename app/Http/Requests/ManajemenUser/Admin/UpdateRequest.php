<?php

namespace App\Http\Requests\ManajemenUser\Admin;

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
        $role = $this->route('role');
        $userId = $this->route('id');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId), // Kolom 'id' default
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'guru', 'siswa'])],
        ];

        // Tambahan jika role == guru
        if ($role === 'guru') {
            $rules = array_merge($rules, [
                'nip' => [
                    'nullable',
                    'string',
                    Rule::unique('guru_profiles', 'nip')->ignore($userId, 'user_id'), // ignore berdasarkan user_id
                ],
                'no_telp' => ['nullable', 'string', 'max:20'],
                'alamat' => ['nullable', 'string', 'max:255'],
                'status_guru' => ['required', Rule::in(['pns', 'honorer'])],
                'tanggal_masuk' => ['nullable', 'date'],
                'matpel_id' => ['required', 'exists:mata_pelajaran,id'],
            ]);
        }

        // Tambahan jika role == siswa
        if ($role === 'siswa') {
            $rules = array_merge($rules, [
                'nisn' => [
                    'required',
                    'string',
                    Rule::unique('siswa_profiles', 'nisn')->ignore($userId, 'user_id'), // ignore berdasarkan user_id
                ],
                'kelas_id' => ['nullable', 'exists:kelas,id'],
                'tahun_masuk' => ['nullable', 'digits:4'],
                'alamat' => ['nullable', 'string', 'max:255'],
                'kontak_ortu' => ['nullable', 'string', 'max:20'],
                'status' => ['required', Rule::in(['aktif', 'tidak_aktif'])],
            ]);
        }

        return $rules;
    }
}

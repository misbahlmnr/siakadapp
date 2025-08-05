<?php

namespace App\Http\Requests\ManajemenUser\Admin;

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
        $role = $this->route('role');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:admin,guru,siswa',
        ];

        if ($role === 'guru') {
            $rules = array_merge($rules, [
                'nip' => 'nullable|string|unique:guru_profiles,nip',
                'mapel' => 'nullable|string|max:255',
                'no_telp' => 'nullable|string|max:20',
                'alamat' => 'nullable|string|max:255',
                'status_guru' => 'required|in:pns,honorer',
                'tanggal_masuk' => 'nullable|date',
            ]);
        }

        if ($role === 'siswa') {
            $rules = array_merge($rules, [
                'nisn' => 'required|string|unique:siswa_profiles,nisn',
                'kelas' => 'nullable|string|max:50',
                'tahun_masuk' => 'nullable|digits:4',
                'alamat' => 'nullable|string|max:255',
                'kontak_ortu' => 'nullable|string|max:20',
                'status' => 'required|in:aktif,tidak_aktif',
            ]);
        }

        return $rules;
    }
}

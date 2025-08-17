<?php

namespace App\Http\Requests\ManajemenUser;

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
                'jenis_kelamin' => 'nullable|string|max:50',
                'tempat_lahir' => 'nullable|string|max:255',
                'tanggal_lahir' => 'required|date|before:today|after:1900-01-01',
                'alamat' => 'nullable|string|max:255',
                'no_hp' => 'required|string|max:20',
                'status_kepegawaian' => 'required|in:pns,honorer',
            ]);
        }

        if ($role === 'siswa') {
            $rules = array_merge($rules, [
                'kelas_id' => 'required|exists:kelas,id',
                'nis' => 'required|string|unique:siswa_profiles,nis',
                'nisn' => 'required|string|unique:siswa_profiles,nisn',
                'jenis_kelamin' => 'required|string|max:1',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date|before:today|after:1900-01-01',
                'no_hp' => 'required|string|max:20',
                'angkatan' => 'required|string',
                'status' => 'required|string',
                'nama_ortu' => 'required|string|max:255',
                'kontak_ortu' => 'required|string|max:20',
            ]);
        }

        return $rules;
    }
}

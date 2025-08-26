<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RekomendasiRequest extends FormRequest
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
            'siswa_id' => 'required|exists:siswa_profiles,id',
            'evaluasi_id' => 'required|exists:evaluasi_pembelajaran,id',
            'materi_id' => 'required|exists:materi_pelajaran,id',
            'alasan_rekomendasi' => 'nullable|string|max:500',
            'status' => 'required|in:belum_dibaca,dibaca,selesai',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'siswa_id.required' => 'Siswa harus dipilih',
            'siswa_id.exists' => 'Siswa tidak valid',
            'evaluasi_id.required' => 'Evaluasi harus dipilih',
            'evaluasi_id.exists' => 'Evaluasi tidak valid',
            'materi_id.required' => 'Materi harus dipilih',
            'materi_id.exists' => 'Materi tidak valid',
            'alasan_rekomendasi.max' => 'Alasan rekomendasi maksimal 500 karakter',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ];
    }
}

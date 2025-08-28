<?php

namespace App\Http\Requests\RekomendasiMateriManual;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'siswa_id' => 'required|exists:siswa_profiles,id',
            'evaluasi_id' => 'required|exists:evaluasi_pembelajaran,id',
            'materi_id' => 'required|exists:materi_pelajaran,id',
            'alasan_rekomendasi' => 'required|string|max:255',
            'status' => 'required|string|in:belum_dibaca,dibaca,selesai',
        ];
    }
}

<?php

namespace App\Http\Requests\Absensi;

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
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id',
            'siswa_id' => 'required|exists:siswa_profiles,id',
            'semester_ajaran_id' => 'required|exists:semester_ajaran,id',
            'pertemuan_ke' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,sakit,ijin,alfa',
        ];
    }
}

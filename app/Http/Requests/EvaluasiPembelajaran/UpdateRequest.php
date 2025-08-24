<?php

namespace App\Http\Requests\EvaluasiPembelajaran;

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
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id',
            'guru_id' => 'required|exists:guru_profiles,id',
            'semester_ajaran_id' => 'required|exists:semester_ajaran,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis' => 'required|string|in:ujian,kuis,tugas',
            'waktu_mulai' => 'required|date:format:Y-m-d H:i:s',
            'waktu_selesai' => 'required|date:format:Y-m-d H:i:s|after_or_equal:waktu_mulai',
            'link_soal' => 'nullable|url',
            'file_soal' => 'nullable|file|mimes:pdf,doc,ppt|max:2048',
            'semester' => 'nullable|string|in:Ganjil,Genap',
            'tahun_ajaran' => 'nullable|string|max:50',
        ];
    }
}

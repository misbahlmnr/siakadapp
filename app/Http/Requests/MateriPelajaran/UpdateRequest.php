<?php

namespace App\Http\Requests\MateriPelajaran;

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
            'guru_matpel_id' => 'required|exists:guru_mata_pelajaran,id',
            'semester_ajaran_id' => 'required|exists:semester_ajaran,id',
            'pertemuan_ke' => 'required|numeric',
            'judul_materi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_materi' => 'nullable|file|mimes:pdf,docx,pptx|max:5120',
            'link_materi' => 'nullable|url',
        ];
    }
}

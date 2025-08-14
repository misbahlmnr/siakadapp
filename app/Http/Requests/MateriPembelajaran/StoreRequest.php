<?php

namespace App\Http\Requests\MateriPembelajaran;

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
            'guru_id' => 'required|exists:guru_profiles,id',
            'pertemuan_ke' => 'required|numeric',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_path' => 'nullable|file|mimes:pdf,docx,pptx|max:5120',
            'link_file' => 'nullable|url',
        ];
    }
}

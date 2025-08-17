<?php

namespace App\Http\Requests\MataPelajaran;

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
        $matpelId = $this->route('id');

        return [
            'kode_mapel' => ['required', 'string', 'max:255', Rule::unique('mata_pelajaran', 'kode_mapel')->ignore($matpelId)],
            'nama_mapel' => ['required', 'string', 'max:255', Rule::unique('mata_pelajaran', 'nama_mapel')->ignore($matpelId)],
            'guru_id' => 'nullable|exists:guru_profiles,id',
        ];
    }
}

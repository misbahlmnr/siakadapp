<?php

namespace App\Http\Requests\SemesterAjaran;

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
            'semester' => 'required|in:ganjil,genap',
            'tahun_ajaran' => 'required|string',
            'status_aktif' => 'required|boolean',
        ];
    }
}

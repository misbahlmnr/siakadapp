<?php

namespace App\Http\Requests\AdaptiveRules;

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
            'matpel_id' => 'required|exists:mata_pelajaran,id',
            'materi_id' => 'required|exists:materi_pelajaran,id',
            'operator' => 'required|in:<,<=,=,>=,>',
            'nilai_batas' => 'required|integer|min:0',
        ];
    }
}

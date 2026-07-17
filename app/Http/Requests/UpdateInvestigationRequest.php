<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvestigationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['investigator', 'super-admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'final_result'   => ['required', 'string', 'min:10'],
            'recommendation' => ['required', 'string', 'min:10'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'final_result.required'   => 'Hasil investigasi akhir wajib diisi.',
            'final_result.min'        => 'Hasil investigasi akhir minimal 10 karakter.',
            'recommendation.required' => 'Rekomendasi tindakan wajib diisi.',
            'recommendation.min'      => 'Rekomendasi tindakan minimal 10 karakter.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimelineRequest extends FormRequest
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
            'description' => ['required', 'string', 'max:2000'],
            'date'        => ['required', 'date', 'before_or_equal:now'],
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
            'description.required' => 'Deskripsi perkembangan investigasi wajib diisi.',
            'description.max'      => 'Deskripsi perkembangan maksimal 2000 karakter.',
            'date.required'        => 'Tanggal kejadian/perkembangan wajib diisi.',
            'date.date'            => 'Format tanggal tidak valid.',
            'date.before_or_equal' => 'Tanggal perkembangan tidak boleh melebihi waktu sekarang.',
        ];
    }
}

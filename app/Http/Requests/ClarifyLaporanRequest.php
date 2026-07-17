<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClarifyLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('tim-wbs');
    }

    public function rules(): array
    {
        return [
            'clarification_message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'clarification_message.required' => 'Pesan klarifikasi wajib diisi.',
            'clarification_message.min' => 'Pesan klarifikasi minimal 10 karakter.',
            'clarification_message.max' => 'Pesan klarifikasi maksimal 2000 karakter.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('tim-wbs');
    }

    public function rules(): array
    {
        return [
            'verification_note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'verification_note.max' => 'Catatan verifikasi maksimal 2000 karakter.',
        ];
    }
}

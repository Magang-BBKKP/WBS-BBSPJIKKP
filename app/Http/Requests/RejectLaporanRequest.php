<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('tim-wbs');
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:255'],
            'verification_note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rejection_reason.required' => 'Alasan penolakan wajib dipilih/diisi.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 255 karakter.',
            'verification_note.max' => 'Catatan tambahan maksimal 2000 karakter.',
        ];
    }
}

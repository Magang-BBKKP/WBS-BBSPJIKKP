<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            'document' => ['required', 'file', 'mimes:pdf,jpg,png,doc,docx', 'max:5120'],
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
            'document.required' => 'File dokumen wajib diunggah.',
            'document.file'     => 'Format file tidak valid.',
            'document.mimes'    => 'Dokumen hanya boleh berupa file dengan tipe: PDF, JPG, PNG, DOC, DOCX.',
            'document.max'      => 'Ukuran file dokumen tidak boleh melebihi 5 MB (5120 KB).',
        ];
    }
}

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
            'final_result'        => ['required', 'string', 'min:10'],
            'recommendation'      => ['required', 'string', 'min:10'],
            'dokumen_hasil_akhir' => ['nullable', 'file', 'mimes:pdf,docx', 'max:5120'],
            'dokumen_rekomendasi' => ['nullable', 'file', 'mimes:pdf,docx', 'max:5120'],
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
            'final_result.required'       => 'Hasil investigasi akhir wajib diisi.',
            'final_result.min'            => 'Hasil investigasi akhir minimal 10 karakter.',
            'recommendation.required'     => 'Rekomendasi tindakan wajib diisi.',
            'recommendation.min'          => 'Rekomendasi tindakan minimal 10 karakter.',
            'dokumen_hasil_akhir.mimes'   => 'Format Dokumen Hasil Akhir harus berupa PDF atau DOCX.',
            'dokumen_hasil_akhir.max'     => 'Ukuran Dokumen Hasil Akhir maksimal 5 MB.',
            'dokumen_rekomendasi.mimes'   => 'Format Dokumen Rekomendasi harus berupa PDF atau DOCX.',
            'dokumen_rekomendasi.max'     => 'Ukuran Dokumen Rekomendasi maksimal 5 MB.',
        ];
    }
}

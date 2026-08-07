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
            'evidences'   => ['nullable', 'array', 'max:10'],
            'evidences.*' => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,zip', 'max:10240'],
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
            'evidences.array'      => 'Format bukti pendukung tidak valid.',
            'evidences.max'        => 'Maksimal 10 file bukti per perkembangan.',
            'evidences.*.file'     => 'File bukti tidak valid.',
            'evidences.*.mimes'    => 'Bukti hanya boleh berupa file dengan tipe: PDF, JPG, PNG, DOC, DOCX, XLS, XLSX, ZIP.',
            'evidences.*.max'      => 'Ukuran file bukti tidak boleh melebihi 10 MB per file.',
        ];
    }
}

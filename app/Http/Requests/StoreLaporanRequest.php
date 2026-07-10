<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // form publik, siapa saja boleh submit
    }

    public function rules(): array
    {
        $isAnonim = $this->boolean('is_anonim', true);

        return [
            // Step 1: Kategori
            'kategori_id'      => ['required', 'exists:kategoris,id'],

            // Step 2: Deskripsi
            'judul'            => ['required', 'string', 'max:255'],
            'deskripsi'        => ['required', 'string', 'min:50'],
            'tanggal_kejadian' => ['nullable', 'date', 'before_or_equal:today'],
            'lokasi'           => ['nullable', 'string', 'max:255'],
            'nama_terlapor'    => ['nullable', 'string', 'max:255'],
            'jabatan_terlapor' => ['nullable', 'string', 'max:255'],
            'unit_terlapor'    => ['nullable', 'string', 'max:255'],

            // Pilihan anonim
            'is_anonim'        => ['nullable', 'boolean'],
            'nama_pelapor'     => [$isAnonim ? 'nullable' : 'required', 'string', 'max:255'],
            'email_pelapor'    => [$isAnonim ? 'nullable' : 'required', 'email', 'max:255'],
            'telepon_pelapor'  => ['nullable', 'string', 'max:20'],

            // Step 3: Bukti
            'bukti'            => ['nullable', 'array', 'max:10'],
            'bukti.*'          => [
                'file',
                'max:10240', // 10 MB per file
                'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,mp4,zip',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required'     => 'Silakan pilih kategori pelanggaran.',
            'kategori_id.exists'       => 'Kategori yang dipilih tidak valid.',
            'judul.required'           => 'Judul laporan wajib diisi.',
            'judul.max'                => 'Judul laporan maksimal 255 karakter.',
            'deskripsi.required'       => 'Deskripsi kejadian wajib diisi.',
            'deskripsi.min'            => 'Deskripsi harus minimal 50 karakter.',
            'tanggal_kejadian.before_or_equal' => 'Tanggal kejadian tidak boleh di masa depan.',
            'nama_pelapor.required'    => 'Nama pelapor wajib diisi jika tidak anonim.',
            'email_pelapor.required'   => 'Email pelapor wajib diisi jika tidak anonim.',
            'email_pelapor.email'      => 'Format email tidak valid.',
            'bukti.max'                => 'Maksimal 10 file bukti.',
            'bukti.*.max'              => 'Ukuran file maksimal 10 MB.',
            'bukti.*.mimes'            => 'Format file tidak didukung. Gunakan: JPG, PNG, PDF, DOC, XLS, MP4, atau ZIP.',
        ];
    }

    public function prepareForValidation(): void
    {
        // Normalize checkbox boolean
        $this->merge([
            'is_anonim' => $this->has('is_anonim') ? true : false,
        ]);
    }
}

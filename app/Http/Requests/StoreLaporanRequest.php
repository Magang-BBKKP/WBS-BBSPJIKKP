<?php

namespace App\Http\Requests;

use App\Models\ReportFormField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // form publik, siapa saja boleh submit
    }

    public function rules(): array
    {
        $isAnonim = $this->boolean('is_anonim', true);
        $baseFields = ReportFormField::baseForLaporanForm()->keyBy('name');
        $required = fn (string $name) => optional($baseFields->get($name))->is_required ? 'required' : 'nullable';
        $active = fn (string $name) => $baseFields->has($name);

        return [
            // Step 1: Kategori
            'kategori_id'      => [$active('kategori_id') ? $required('kategori_id') : 'nullable', 'exists:kategoris,id'],

            // Step 2: Deskripsi
            'judul'            => [$active('judul') ? $required('judul') : 'nullable', 'string', 'max:255'],
            'deskripsi'        => [$active('deskripsi') ? $required('deskripsi') : 'nullable', 'string', 'min:50'],
            'tanggal_kejadian' => [$active('tanggal_kejadian') ? $required('tanggal_kejadian') : 'nullable', 'date', 'before_or_equal:today'],
            'lokasi'           => [$active('lokasi') ? $required('lokasi') : 'nullable', 'string', 'max:255'],
            'nama_terlapor'    => [$active('nama_terlapor') ? $required('nama_terlapor') : 'nullable', 'string', 'max:255'],
            'jabatan_terlapor' => [$active('jabatan_terlapor') ? $required('jabatan_terlapor') : 'nullable', 'string', 'max:255'],
            'unit_terlapor'    => [$active('unit_terlapor') ? $required('unit_terlapor') : 'nullable', 'string', 'max:255'],

            // Pilihan anonim
            'is_anonim'        => ['nullable', 'boolean'],
            'nama_pelapor'     => [!$isAnonim && $active('nama_pelapor') ? $required('nama_pelapor') : 'nullable', 'string', 'max:255'],
            'email_pelapor'    => [!$isAnonim && $active('email_pelapor') ? $required('email_pelapor') : 'nullable', 'email', 'max:255'],
            'telepon_pelapor'  => [$active('telepon_pelapor') ? $required('telepon_pelapor') : 'nullable', 'string', 'max:20'],

            // Step 3: Bukti
            'custom_fields'    => ['nullable', 'array'],
            'bukti'            => [$active('bukti') ? $required('bukti') : 'nullable', 'array', 'max:10'],
            'bukti.*'          => [
                'file',
                'max:10240', // 10 MB per file
                'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,mp4,zip',
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $fields = ReportFormField::forLaporanForm();
            $values = (array) $this->input('custom_fields', []);

            foreach ($fields as $field) {
                $value = $values[$field->name] ?? null;
                $isEmpty = is_array($value)
                    ? count(array_filter($value, fn ($item) => trim((string) $item) !== '')) === 0
                    : trim((string) $value) === '';

                if ($field->is_required && $isEmpty) {
                    $validator->errors()->add("custom_fields.{$field->name}", "{$field->label} wajib diisi.");
                    continue;
                }

                if ($isEmpty) {
                    continue;
                }

                if ($field->type === 'number' && !is_numeric($value)) {
                    $validator->errors()->add("custom_fields.{$field->name}", "{$field->label} harus berupa angka.");
                }

                if ($field->type === 'date' && strtotime((string) $value) === false) {
                    $validator->errors()->add("custom_fields.{$field->name}", "{$field->label} harus berupa tanggal yang valid.");
                }

                if (in_array($field->type, ['select', 'radio'], true) && !in_array($value, $field->options ?? [], true)) {
                    $validator->errors()->add("custom_fields.{$field->name}", "Pilihan {$field->label} tidak valid.");
                }

                if ($field->type === 'checkbox') {
                    $selectedValues = is_array($value) ? $value : [$value];
                    foreach ($selectedValues as $selected) {
                        if (!in_array($selected, $field->options ?? [], true)) {
                            $validator->errors()->add("custom_fields.{$field->name}", "Pilihan {$field->label} tidak valid.");
                            break;
                        }
                    }
                }
            }
        });
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
        $hasAnonimField = ReportFormField::baseForLaporanForm()->contains('name', 'is_anonim');

        // Normalize checkbox boolean
        $this->merge([
            'is_anonim' => $hasAnonimField ? $this->boolean('is_anonim') : true,
            'custom_fields' => (array) $this->input('custom_fields', []),
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\ReportFormField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportFormFieldSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed default additional report fields.
     */
    public function run(): void
    {
        $fields = array_merge(ReportFormField::baseDefaults(), [
            [
                'label' => 'Sumber Informasi',
                'name' => 'sumber_informasi',
                'type' => 'select',
                'placeholder' => null,
                'help_text' => 'Pilih asal informasi yang Anda gunakan saat membuat laporan.',
                'options' => ['Melihat langsung', 'Mendengar dari pihak lain', 'Dokumen pendukung'],
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 10,
                'source' => ReportFormField::SOURCE_CUSTOM,
            ],
            [
                'label' => 'Perkiraan Kerugian (Rp)',
                'name' => 'perkiraan_kerugian',
                'type' => 'number',
                'placeholder' => 'Contoh: 5000000',
                'help_text' => 'Isi jika ada estimasi nominal kerugian.',
                'options' => null,
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 20,
                'source' => ReportFormField::SOURCE_CUSTOM,
            ],
            [
                'label' => 'Apakah Bersedia Dihubungi?',
                'name' => 'bersedia_dihubungi',
                'type' => 'radio',
                'placeholder' => null,
                'help_text' => 'Informasi ini memudahkan tim saat membutuhkan klarifikasi tambahan.',
                'options' => ['Ya', 'Tidak'],
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 30,
                'source' => ReportFormField::SOURCE_CUSTOM,
            ],
        ]);

        foreach ($fields as $field) {
            ReportFormField::updateOrCreate(
                ['name' => $field['name']],
                $field
            );
        }
    }
}

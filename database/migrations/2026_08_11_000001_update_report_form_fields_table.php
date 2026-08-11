<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Set is_required = true for reported party fields
        DB::table('report_form_fields')
            ->whereIn('name', ['nama_terlapor', 'jabatan_terlapor', 'unit_terlapor'])
            ->update(['is_required' => true]);

        // 2. Add "Lainnya" to options for "sumber_informasi"
        $sumberInformasi = DB::table('report_form_fields')
            ->where('name', 'sumber_informasi')
            ->first();

        if ($sumberInformasi) {
            $options = json_decode($sumberInformasi->options ?? '[]', true) ?: [];
            if (!in_array('Lainnya', $options)) {
                $options[] = 'Lainnya';
                DB::table('report_form_fields')
                    ->where('name', 'sumber_informasi')
                    ->update(['options' => json_encode($options)]);
            }
        }

        // 3. Delete "bersedia_dihubungi" field
        DB::table('report_form_fields')
            ->where('name', 'bersedia_dihubungi')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert is_required = false
        DB::table('report_form_fields')
            ->whereIn('name', ['nama_terlapor', 'jabatan_terlapor', 'unit_terlapor'])
            ->update(['is_required' => false]);

        // Remove "Lainnya" from options
        $sumberInformasi = DB::table('report_form_fields')
            ->where('name', 'sumber_informasi')
            ->first();

        if ($sumberInformasi) {
            $options = json_decode($sumberInformasi->options ?? '[]', true) ?: [];
            if (($key = array_search('Lainnya', $options)) !== false) {
                unset($options[$key]);
                DB::table('report_form_fields')
                    ->where('name', 'sumber_informasi')
                    ->update(['options' => json_encode(array_values($options))]);
            }
        }

        // Re-insert "bersedia_dihubungi" (We can leave it to seeding or re-create it)
        DB::table('report_form_fields')->updateOrInsert(
            ['name' => 'bersedia_dihubungi'],
            [
                'label' => 'Apakah Bersedia Dihubungi?',
                'type' => 'radio',
                'placeholder' => null,
                'help_text' => 'Informasi ini memudahkan tim saat membutuhkan klarifikasi tambahan.',
                'options' => json_encode(['Ya', 'Tidak']),
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 30,
                'source' => 'custom',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
};

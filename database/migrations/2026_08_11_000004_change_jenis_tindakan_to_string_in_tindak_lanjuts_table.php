<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            $table->string('jenis_tindakan', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            $table->enum('jenis_tindakan', [
                'pembinaan',
                'teguran',
                'hukuman_disiplin',
                'pemutusan_kontrak',
                'pelaporan_aph',
                'perbaikan_sistem',
            ])->change();
        });
    }
};

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
        Schema::table('investigations', function (Blueprint $table) {
            $table->string('dokumen_hasil_akhir')->nullable()->after('final_result');
            $table->string('dokumen_rekomendasi')->nullable()->after('recommendation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investigations', function (Blueprint $table) {
            $table->dropColumn(['dokumen_hasil_akhir', 'dokumen_rekomendasi']);
        });
    }
};

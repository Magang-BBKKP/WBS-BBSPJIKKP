<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporans')->cascadeOnDelete();
            $table->foreignId('investigation_id')->nullable()->constrained('investigations')->nullOnDelete();
            $table->enum('jenis_tindakan', [
                'pembinaan',
                'teguran',
                'hukuman_disiplin',
                'pemutusan_kontrak',
                'pelaporan_aph',
                'perbaikan_sistem',
            ]);
            $table->text('keterangan')->nullable();
            $table->foreignId('ditetapkan_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamp('ditetapkan_pada')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjuts');
    }
};

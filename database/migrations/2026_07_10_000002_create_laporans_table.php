<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi')->unique();
            $table->string('tracking_token')->unique();
            $table->foreignId('kategori_id')->constrained('kategoris')->restrictOnDelete();

            // Detail Laporan
            $table->string('judul');
            $table->text('deskripsi');
            $table->date('tanggal_kejadian')->nullable();
            $table->string('lokasi')->nullable();

            // Data Terlapor
            $table->string('nama_terlapor')->nullable();
            $table->string('jabatan_terlapor')->nullable();
            $table->string('unit_terlapor')->nullable();

            // Data Pelapor (opsional jika tidak anonim)
            $table->boolean('is_anonim')->default(true);
            $table->string('nama_pelapor')->nullable();
            $table->string('email_pelapor')->nullable();
            $table->string('telepon_pelapor')->nullable();

            // Status
            $table->enum('status', [
                'menunggu',
                'verifikasi',
                'valid',
                'ditolak',
                'investigasi',
                'selesai',
            ])->default('menunggu');

            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};

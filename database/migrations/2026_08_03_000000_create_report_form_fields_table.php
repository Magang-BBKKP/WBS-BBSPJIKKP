<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('name')->unique();
            $table->enum('type', ['text', 'textarea', 'number', 'date', 'select', 'radio', 'checkbox']);
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('laporans', function (Blueprint $table) {
            $table->json('custom_fields')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn('custom_fields');
        });

        Schema::dropIfExists('report_form_fields');
    }
};

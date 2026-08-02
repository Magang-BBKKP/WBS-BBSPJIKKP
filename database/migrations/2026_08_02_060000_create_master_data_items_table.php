<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_data_items', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30);
            $table->string('name', 120);
            $table->string('description', 500)->nullable();
            $table->string('color', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['type', 'name']);
            $table->index(['type', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_data_items');
    }
};

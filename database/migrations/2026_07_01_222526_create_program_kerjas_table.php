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
        Schema::create('program_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('tema')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('icon')->nullable();
            $table->string('pic')->nullable();
            $table->string('status')->default('Coming Soon');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_kerja');
    }
};

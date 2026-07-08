<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observasi_lapangan', function (Blueprint $table) {
            $table->id();
            $table->text('ringkasan_permasalahan')->nullable();
            $table->text('rencana_pemecahan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('observasi_lapangan_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('observasi_lapangan_id')->constrained('observasi_lapangan')->cascadeOnDelete();
            $table->string('nama_kelembagaan');
            $table->enum('status', ['ada', 'tidak'])->default('tidak');
            $table->text('permasalahan')->nullable();
            $table->text('rencana_pemecahan_masalah')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observasi_lapangan_item');
        Schema::dropIfExists('observasi_lapangan');
    }
};

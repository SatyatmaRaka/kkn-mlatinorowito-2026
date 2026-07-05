<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan_pelaksanaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->date('tanggal');
            $table->string('tempat');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->foreignId('pic_anggota_id')->nullable()->constrained('anggota')->nullOnDelete();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tanggal');
        });

        Schema::create('kegiatan_peserta_masyarakat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_pelaksanaan_id')->constrained('kegiatan_pelaksanaan')->cascadeOnDelete();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->timestamps();
        });

        Schema::create('kegiatan_tugas_tim', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_pelaksanaan_id')->constrained('kegiatan_pelaksanaan')->cascadeOnDelete();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->string('tugas');
            $table->timestamps();

            $table->unique(['kegiatan_pelaksanaan_id', 'anggota_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_tugas_tim');
        Schema::dropIfExists('kegiatan_peserta_masyarakat');
        Schema::dropIfExists('kegiatan_pelaksanaan');
    }
};

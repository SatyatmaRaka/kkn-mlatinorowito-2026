<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_tamu', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_tamu');
            $table->string('alamat_jabatan')->nullable();
            $table->text('keperluan');
            $table->foreignId('anggota_id')->nullable()->constrained('anggota')->nullOnDelete();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tanggal');
            $table->index('nama_tamu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_tamu');
    }
};

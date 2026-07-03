<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal');
            $table->string('asal_tujuan');
            $table->string('perihal');
            $table->text('keterangan')->nullable();
            $table->string('lampiran')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['jenis', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};

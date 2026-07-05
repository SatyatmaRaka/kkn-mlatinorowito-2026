<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ukm', function (Blueprint $table) {
            $table->id();
            $table->string('nama_usaha');
            $table->string('jenis_usaha');
            $table->string('rata_rata_omzet')->nullable();
            $table->string('jangkauan_pemasaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('nama_usaha');
            $table->index('urutan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ukm');
    }
};

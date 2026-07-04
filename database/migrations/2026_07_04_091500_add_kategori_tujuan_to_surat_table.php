<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->string('kategori_tujuan', 20)->nullable()->after('jenis');
            $table->string('nomor_rt', 10)->nullable()->after('asal_tujuan');
            $table->string('nomor_rw', 10)->nullable()->after('nomor_rt');
        });
    }

    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropColumn(['kategori_tujuan', 'nomor_rt', 'nomor_rw']);
        });
    }
};

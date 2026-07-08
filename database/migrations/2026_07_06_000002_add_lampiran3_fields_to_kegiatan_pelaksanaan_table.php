<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatan_pelaksanaan', function (Blueprint $table) {
            $table->string('tema_kegiatan')->nullable()->after('nama_kegiatan');
            $table->text('latar_belakang')->nullable()->after('tempat');
            $table->text('kondisi_mendukung')->nullable()->after('latar_belakang');
            $table->text('manfaat_tujuan')->nullable()->after('kondisi_mendukung');
            $table->bigInteger('sumber_dana_masyarakat')->default(0)->after('manfaat_tujuan');
            $table->bigInteger('sumber_dana_mahasiswa')->default(0)->after('sumber_dana_masyarakat');
            $table->bigInteger('sumber_dana_donatur')->default(0)->after('sumber_dana_mahasiswa');
            $table->string('sumber_dana_donatur_keterangan')->nullable()->after('sumber_dana_donatur');
        });
    }

    public function down(): void
    {
        Schema::table('kegiatan_pelaksanaan', function (Blueprint $table) {
            $table->dropColumn([
                'tema_kegiatan',
                'latar_belakang',
                'kondisi_mendukung',
                'manfaat_tujuan',
                'sumber_dana_masyarakat',
                'sumber_dana_mahasiswa',
                'sumber_dana_donatur',
                'sumber_dana_donatur_keterangan',
            ]);
        });
    }
};

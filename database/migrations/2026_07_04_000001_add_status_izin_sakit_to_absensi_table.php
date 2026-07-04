<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('status')->default('hadir')->after('tanggal');
            $table->text('keterangan')->nullable()->after('status');
            $table->foreignId('dicatat_oleh')->nullable()->after('keterangan')->constrained('users')->nullOnDelete();
            $table->timestamp('check_in_at')->nullable()->change();
        });

        DB::table('absensi')->update(['status' => 'hadir']);
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['dicatat_oleh']);
            $table->dropColumn(['status', 'keterangan', 'dicatat_oleh']);
            $table->timestamp('check_in_at')->nullable(false)->change();
        });
    }
};

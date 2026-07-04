<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keuangans', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('surat', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('logbooks', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('keuangans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('surat', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};

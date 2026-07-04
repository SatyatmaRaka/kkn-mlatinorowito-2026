<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('surat');
        Schema::dropIfExists('galeri');
        Schema::dropIfExists('kegiatans');
    }

    public function down(): void
    {
        // Tabel dihapus permanen — restore lewat migrasi asli jika diperlukan.
    }
};

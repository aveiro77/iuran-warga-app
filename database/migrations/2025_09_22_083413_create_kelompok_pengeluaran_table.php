<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelompok_pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        // Tambahkan kolom kelompok_pengeluaran_id ke tabel pengeluarans
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->foreignId('kelompok_pengeluaran_id')->nullable()->constrained('kelompok_pengeluaran')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropForeign(['kelompok_pengeluaran_id']);
            $table->dropColumn('kelompok_pengeluaran_id');
        });
        
        Schema::dropIfExists('kelompok_pengeluaran');
    }
};
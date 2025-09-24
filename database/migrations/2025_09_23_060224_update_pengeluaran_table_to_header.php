<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus kolom yang tidak diperlukan di tabel pengeluarans
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropColumn(['keperluan', 'jumlah', 'keterangan']);
            $table->text('keterangan')->nullable()->after('kelompok_pengeluaran_id');
            $table->string('bukti_transaksi')->nullable()->after('penanggung_jawab');
        });
    }

    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->string('keperluan');
            $table->decimal('jumlah', 10, 2);
            $table->text('keterangan')->nullable();
            $table->dropColumn('bukti_transaksi');
        });
    }
};
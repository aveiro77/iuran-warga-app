<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus kolom yang tidak diperlukan
        Schema::table('pengeluarans', function (Blueprint $table) {
            if (Schema::hasColumn('pengeluarans', 'keperluan')) {
                $table->dropColumn('keperluan');
            }
            if (Schema::hasColumn('pengeluarans', 'jumlah')) {
                $table->dropColumn('jumlah');
            }
            
            // Pastikan kolom yang diperlukan ada
            if (!Schema::hasColumn('pengeluarans', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('kelompok_pengeluaran_id');
            }
            
            if (!Schema::hasColumn('pengeluarans', 'bukti_transaksi')) {
                $table->string('bukti_transaksi')->nullable()->after('penanggung_jawab');
            }
        });
    }

    public function down(): void
    {
        // Tidak perlu rollback yang kompleks
    }
};
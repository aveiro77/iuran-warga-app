<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemasukans', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada
            if (!Schema::hasColumn('pemasukans', 'nomor_transaksi')) {
                $table->string('nomor_transaksi')->unique()->after('id');
                $table->index('nomor_transaksi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemasukans', function (Blueprint $table) {
            if (Schema::hasColumn('pemasukans', 'nomor_transaksi')) {
                $table->dropColumn('nomor_transaksi');
            }
        });
    }
};
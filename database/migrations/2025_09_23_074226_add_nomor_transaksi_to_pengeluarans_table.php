<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->string('nomor_transaksi')->unique()->after('id');
            $table->index('nomor_transaksi');
        });
    }

    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropColumn('nomor_transaksi');
        });
    }
};
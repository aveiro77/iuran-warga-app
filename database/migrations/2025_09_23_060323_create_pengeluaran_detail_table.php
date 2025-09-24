<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_id')->constrained()->onDelete('cascade');
            $table->string('item');
            $table->decimal('jumlah', 10, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_detail');
    }
};
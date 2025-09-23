<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'kelompok_pengeluaran_id',
        'keperluan',
        'jumlah',
        'keterangan',
        'penanggung_jawab',
    ];

    public function kelompokPengeluaran(): BelongsTo
    {
        return $this->belongsTo(KelompokPengeluaran::class, 'kelompok_pengeluaran_id');
    }
}
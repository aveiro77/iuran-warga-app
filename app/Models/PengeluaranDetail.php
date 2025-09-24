<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengeluaranDetail extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_detail';

    protected $fillable = [
        'pengeluaran_id',
        'item',
        'jumlah',
        'keterangan',
    ];

    public function pengeluaran(): BelongsTo
    {
        return $this->belongsTo(Pengeluaran::class);
    }
}
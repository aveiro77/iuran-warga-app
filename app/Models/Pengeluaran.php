<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_transaksi',
        'tanggal',
        'kelompok_pengeluaran_id',
        'keterangan',
        'penanggung_jawab',
        'bukti_transaksi',
    ];

    protected $casts = [
        'tanggal' => 'date', // Tambahkan casting untuk tanggal
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->nomor_transaksi)) {
                $model->nomor_transaksi = static::generateNomorTransaksi();
            }
        });
    }

    public static function generateNomorTransaksi(): string
    {
        $prefix = 'TRX-E';
        $date = now()->format('ymd');
        $lastTransaction = static::where('nomor_transaksi', 'like', "{$prefix}{$date}%")->latest()->first();
        
        $sequence = 1;
        if ($lastTransaction) {
            $lastSequence = (int) substr($lastTransaction->nomor_transaksi, -4);
            $sequence = $lastSequence + 1;
        }
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function kelompokPengeluaran(): BelongsTo
    {
        return $this->belongsTo(KelompokPengeluaran::class, 'kelompok_pengeluaran_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PengeluaranDetail::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->details->sum('jumlah');
    }
}
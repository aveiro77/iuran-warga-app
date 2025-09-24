<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemasukan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_transaksi',
        'tanggal',
        'warga_id',
        'jumlah',
        'keterangan',
        'penarik',
    ];

    protected $casts = [
        'tanggal' => 'date', // Tambahkan casting untuk tanggal
        'jumlah' => 'decimal:2',
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
        $prefix = 'TRX-P';
        $date = now()->format('ymd');
        $lastTransaction = static::where('nomor_transaksi', 'like', "{$prefix}{$date}%")->latest()->first();
        
        $sequence = 1;
        if ($lastTransaction) {
            $lastSequence = (int) substr($lastTransaction->nomor_transaksi, -4);
            $sequence = $lastSequence + 1;
        }
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class);
    }
}
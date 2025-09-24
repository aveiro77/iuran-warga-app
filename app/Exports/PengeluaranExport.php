<?php

namespace App\Exports;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengeluaranExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Pengeluaran::with(['kelompokPengeluaran', 'details']);
        
        if ($this->startDate) {
            $query->whereDate('tanggal', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->whereDate('tanggal', '<=', $this->endDate);
        }
        
        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No. Transaksi',
            'Tanggal',
            'Kelompok Pengeluaran',
            'Keterangan',
            'Total',
            'Penanggung Jawab',
            'Jumlah Item',
            'Dibuat Pada',
        ];
    }

    public function map($pengeluaran): array
    {
        return [
            $pengeluaran->nomor_transaksi,
            $pengeluaran->tanggal instanceof \Carbon\Carbon 
                ? $pengeluaran->tanggal->format('d/m/Y')
                : \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y'),
            $pengeluaran->kelompokPengeluaran->nama ?? '-',
            $pengeluaran->keterangan ?? '-',
            $pengeluaran->total,
            $pengeluaran->penanggung_jawab,
            $pengeluaran->details->count(),
            $pengeluaran->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A' => ['width' => 15],
            'B' => ['width' => 12],
            'C' => ['width' => 20],
            'D' => ['width' => 30],
            'E' => ['width' => 15],
            'F' => ['width' => 20],
            'G' => ['width' => 12],
            'H' => ['width' => 18],
        ];
    }
}
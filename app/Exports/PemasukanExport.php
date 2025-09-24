<?php

namespace App\Exports;

use App\Models\Pemasukan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PemasukanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
        $query = Pemasukan::with('warga');
        
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
            'Nama Warga',
            'Alamat',
            'Jumlah',
            'Keterangan',
            'Penarik',
            'Dibuat Pada',
        ];
    }

    public function map($pemasukan): array
    {
        return [
            $pemasukan->nomor_transaksi,
            $pemasukan->tanggal instanceof \Carbon\Carbon 
                ? $pemasukan->tanggal->format('d/m/Y')
                : \Carbon\Carbon::parse($pemasukan->tanggal)->format('d/m/Y'),
            $pemasukan->warga->nama ?? '-',
            $pemasukan->warga->alamat ?? '-',
            $pemasukan->jumlah,
            $pemasukan->keterangan ?? '-',
            $pemasukan->penarik,
            $pemasukan->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A' => ['width' => 15],
            'B' => ['width' => 12],
            'C' => ['width' => 25],
            'D' => ['width' => 30],
            'E' => ['width' => 15],
            'F' => ['width' => 30],
            'G' => ['width' => 20],
            'H' => ['width' => 18],
        ];
    }
}
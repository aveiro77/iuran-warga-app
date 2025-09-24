<?php

namespace App\Exports;

use App\Models\Warga;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WargaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Warga::withCount('pemasukan')->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'Alamat',
            'RT',
            'RW',
            'No Telepon',
            'Status Aktif',
            'Jumlah Transaksi',
            'Tanggal Dibuat',
        ];
    }

    public function map($warga): array
    {
        return [
            $warga->nik ?? '-',
            $warga->nama,
            $warga->alamat,
            $warga->rt,
            $warga->rw,
            $warga->no_telepon ?? '-',
            $warga->status_aktif ? 'Aktif' : 'Tidak Aktif',
            $warga->pemasukan_count,
            $warga->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A' => ['width' => 20],
            'B' => ['width' => 25],
            'C' => ['width' => 30],
            'D' => ['width' => 10],
            'E' => ['width' => 10],
            'F' => ['width' => 15],
            'G' => ['width' => 15],
            'H' => ['width' => 15],
            'I' => ['width' => 20],
        ];
    }
}
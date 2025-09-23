<?php

namespace Database\Seeders;

use App\Models\KelompokPengeluaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelompokPengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $kelompok = [
            [
                'kode' => 'K01',
                'nama' => 'Kebersihan',
                'deskripsi' => 'Pengeluaran untuk kebersihan lingkungan',
                'status_aktif' => true,
            ],
            [
                'kode' => 'K02',
                'nama' => 'Keamanan',
                'deskripsi' => 'Pengeluaran untuk keamanan lingkungan',
                'status_aktif' => true,
            ],
            [
                'kode' => 'K03',
                'nama' => 'Kegiatan Warga',
                'deskripsi' => 'Pengeluaran untuk kegiatan warga',
                'status_aktif' => true,
            ],
            [
                'kode' => 'K04',
                'nama' => 'Pemeliharaan',
                'deskripsi' => 'Pengeluaran untuk pemeliharaan fasilitas',
                'status_aktif' => true,
            ],
            [
                'kode' => 'K05',
                'nama' => 'Lain-lain',
                'deskripsi' => 'Pengeluaran lainnya',
                'status_aktif' => true,
            ],
        ];

        foreach ($kelompok as $data) {
            KelompokPengeluaran::create($data);
        }
    }
}
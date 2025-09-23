<?php

namespace Database\Seeders;

use App\Models\Warga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WargaSeeder extends Seeder
{
    public function run(): void
    {
        $wargas = [
            [
                'nik' => '1234567890123456',
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. Merdeka No. 1',
                'rt' => '01',
                'rw' => '01',
                'no_telepon' => '081234567890',
                'status_aktif' => true,
            ],
            [
                'nik' => '1234567890123457',
                'nama' => 'Siti Rahayu',
                'alamat' => 'Jl. Merdeka No. 2',
                'rt' => '01',
                'rw' => '01',
                'no_telepon' => '081234567891',
                'status_aktif' => true,
            ],
            [
                'nik' => '1234567890123458',
                'nama' => 'Ahmad Fauzi',
                'alamat' => 'Jl. Merdeka No. 3',
                'rt' => '01',
                'rw' => '01',
                'no_telepon' => '081234567892',
                'status_aktif' => true,
            ],
            [
                'nik' => '1234567890123459',
                'nama' => 'Dewi Lestari',
                'alamat' => 'Jl. Merdeka No. 4',
                'rt' => '02',
                'rw' => '01',
                'no_telepon' => '081234567893',
                'status_aktif' => true,
            ],
            [
                'nik' => '1234567890123460',
                'nama' => 'Rudi Hermawan',
                'alamat' => 'Jl. Merdeka No. 5',
                'rt' => '02',
                'rw' => '01',
                'no_telepon' => '081234567894',
                'status_aktif' => true,
            ],
        ];

        foreach ($wargas as $warga) {
            Warga::create($warga);
        }
    }
}
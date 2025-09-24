<?php

namespace Database\Seeders;

use App\Models\KelompokPengeluaran;
use App\Models\Pengeluaran;
use App\Models\Pemasukan;
use App\Models\Warga;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed kelompok pengeluaran
        $this->call(KelompokPengeluaranSeeder::class);

        // Seed warga
        $this->call(WargaSeeder::class);

        // Seed data contoh pemasukan dan pengeluaran
        $this->seedContohData();
    }

    protected function seedContohData(): void
    {
        // Buat data pengeluaran contoh
        $pengeluaran = Pengeluaran::create([
            'tanggal' => now(),
            'kelompok_pengeluaran_id' => 1,
            'keterangan' => 'Pembersihan lingkungan bulanan',
            'penanggung_jawab' => 'Budi Santoso',
            'bukti_transaksi' => null,
        ]);

        $pengeluaran->details()->createMany([
            [
                'item' => 'Sapu dan pengki',
                'jumlah' => 150000,
                'keterangan' => 'Peralatan kebersihan',
            ],
            [
                'item' => 'Biaya tukang kebun',
                'jumlah' => 300000,
                'keterangan' => 'Honor tukang kebun 2 orang',
            ],
        ]);

        // Buat data pemasukan contoh
        $warga = Warga::first();
        if ($warga) {
            Pemasukan::create([
                'tanggal' => now(),
                'warga_id' => $warga->id,
                'jumlah' => 50000,
                'keterangan' => 'Iuran bulan Januari',
                'penarik' => 'Ketua RT',
            ]);
        }
    }
}
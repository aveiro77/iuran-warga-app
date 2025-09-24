<?php

namespace Database\Seeders;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        // Update pemasukan yang belum punya nomor transaksi
        $pemasukans = Pemasukan::whereNull('nomor_transaksi')->get();
        foreach ($pemasukans as $pemasukan) {
            $pemasukan->nomor_transaksi = Pemasukan::generateNomorTransaksi();
            $pemasukan->save();
        }

        // Update pengeluaran yang belum punya nomor transaksi
        $pengeluarans = Pengeluaran::whereNull('nomor_transaksi')->get();
        foreach ($pengeluarans as $pengeluaran) {
            $pengeluaran->nomor_transaksi = Pengeluaran::generateNomorTransaksi();
            $pengeluaran->save();
        }

        $this->command->info('Berhasil update nomor transaksi untuk ' . $pemasukans->count() . ' pemasukan dan ' . $pengeluarans->count() . ' pengeluaran.');
    }
}
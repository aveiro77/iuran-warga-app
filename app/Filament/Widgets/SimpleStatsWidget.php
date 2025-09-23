<?php

namespace App\Filament\Widgets;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Warga;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SimpleStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;
        
        $totalWarga = Warga::where('status_aktif', true)->count();

        return [
            Stat::make('Total Warga', $totalWarga)
                ->description('Warga aktif')
                ->color('primary')
                ->icon('heroicon-o-user-group'),
                
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalPemasukan, 0, ',', '.'))
                ->description('Total iuran warga')
                ->color('success')
                ->icon('heroicon-o-banknotes'),
                
            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'))
                ->description('Total pengeluaran kampung')
                ->color('danger')
                ->icon('heroicon-o-credit-card'),
                
            Stat::make('Saldo', 'Rp ' . number_format($saldo, 0, ',', '.'))
                ->description('Saldo saat ini')
                ->color($saldo >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-scale'),
        ];
    }
}
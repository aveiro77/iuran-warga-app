<?php

namespace App\Filament\Widgets;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Warga;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 1;

    public $startDate;
    public $endDate;

    public function mount(): void
    {
        $this->startDate = request()->input('start_date');
        $this->endDate = request()->input('end_date');
    }

    protected function getStats(): array
    {
        // Query dengan filter tanggal jika ada
        $pemasukanQuery = Pemasukan::query();
        $pengeluaranQuery = Pengeluaran::query()->with('details');
        
        if ($this->startDate) {
            $pemasukanQuery->whereDate('tanggal', '>=', $this->startDate);
            $pengeluaranQuery->whereDate('tanggal', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $pemasukanQuery->whereDate('tanggal', '<=', $this->endDate);
            $pengeluaranQuery->whereDate('tanggal', '<=', $this->endDate);
        }
        
        $totalPemasukan = $pemasukanQuery->sum('jumlah');
        
        // Total pengeluaran dari sum details
        $pengeluarans = $pengeluaranQuery->get();
        $totalPengeluaran = $pengeluarans->sum(function ($pengeluaran) {
            return $pengeluaran->details->sum('jumlah');
        });
        
        $saldo = $totalPemasukan - $totalPengeluaran;
        
        $totalWarga = Warga::where('status_aktif', true)->count();

        // Description dengan info periode
        $periodeDescription = $this->startDate ? 
            'Periode: ' . \Carbon\Carbon::parse($this->startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($this->endDate)->format('d M Y') : 
            'Semua waktu';

        return [
            Stat::make('Total Warga', $totalWarga)
                ->description('Warga aktif')
                ->color('primary')
                ->icon('heroicon-o-user-group'),
                
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalPemasukan, 0, ',', '.'))
                ->description($periodeDescription)
                ->color('success')
                ->icon('heroicon-o-banknotes'),
                
            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'))
                ->description($periodeDescription)
                ->color('danger')
                ->icon('heroicon-o-credit-card'),
                
            Stat::make('Saldo', 'Rp ' . number_format($saldo, 0, ',', '.'))
                ->description($periodeDescription)
                ->color($saldo >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-scale'),
        ];
    }
}
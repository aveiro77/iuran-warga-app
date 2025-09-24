<?php

namespace App\Filament\Pages;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Warga;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?int $navigationSort = -2;

    public $startDate;
    public $endDate;
    public $totalWarga;
    public $totalPemasukan;
    public $totalPengeluaran;
    public $saldo;
    public $recentPemasukans;
    public $recentPengeluarans; // Tambahkan ini

    public function mount()
    {
        // Set default period: 1 Januari 2023 sampai hari ini
        $this->startDate = request()->input('start_date', '2023-01-01');
        $this->endDate = request()->input('end_date', now()->format('Y-m-d'));
        
        $this->updateStats();
        $this->loadRecentPemasukans();
        $this->loadRecentPengeluarans(); // Tambahkan ini
    }

    public function updateStats()
    {
        // Hitung statistik berdasarkan periode
        $pemasukanQuery = Pemasukan::whereBetween('tanggal', [$this->startDate, $this->endDate]);
        $pengeluaranQuery = Pengeluaran::whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->withSum('details', 'jumlah');
        
        $this->totalWarga = Warga::where('status_aktif', true)->count();
        $this->totalPemasukan = $pemasukanQuery->sum('jumlah');
        
        // Total pengeluaran dari sum details
        $this->totalPengeluaran = $pengeluaranQuery->get()->sum(function ($pengeluaran) {
            return $pengeluaran->details_sum_jumlah ?? 0;
        });
        
        $this->saldo = $this->totalPemasukan - $this->totalPengeluaran;
    }

    public function loadRecentPemasukans()
    {
        $this->recentPemasukans = Pemasukan::with('warga')
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    // Tambahkan method untuk pengeluaran
    public function loadRecentPengeluarans()
    {
        $this->recentPengeluarans = Pengeluaran::with(['kelompokPengeluaran', 'details'])
            ->withCount('details')
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function applyFilter()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $this->updateStats();
        $this->loadRecentPemasukans();
        $this->loadRecentPengeluarans(); // Tambahkan ini
        
        // Update URL dengan parameter filter
        $this->dispatch('update-url', [
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);
    }

    public function resetFilter()
    {
        $this->startDate = '2023-01-01';
        $this->endDate = now()->format('Y-m-d');
        $this->updateStats();
        $this->loadRecentPemasukans();
        $this->loadRecentPengeluarans(); // Tambahkan ini
        
        // Reset URL
        $this->dispatch('reset-url');
    }

    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }

    public function getTitle(): string
    {
        return 'Dashboard';
    }
}
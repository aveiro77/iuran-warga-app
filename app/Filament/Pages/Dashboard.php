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

    public function mount()
    {
        // Set default period: 1 Januari 2023 sampai hari ini
        $this->startDate = request()->input('start_date', '2023-01-01');
        $this->endDate = request()->input('end_date', now()->format('Y-m-d'));
        
        $this->updateStats();
    }

    public function updateStats()
    {
        // Hitung statistik berdasarkan periode
        $pemasukanQuery = Pemasukan::whereBetween('tanggal', [$this->startDate, $this->endDate]);
        $pengeluaranQuery = Pengeluaran::whereBetween('tanggal', [$this->startDate, $this->endDate]);
        
        $this->totalWarga = Warga::where('status_aktif', true)->count();
        $this->totalPemasukan = $pemasukanQuery->sum('jumlah');
        $this->totalPengeluaran = $pengeluaranQuery->sum('jumlah');
        $this->saldo = $this->totalPemasukan - $this->totalPengeluaran;
    }

    public function applyFilter()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $this->updateStats();
        
        // Update URL dengan parameter filter
        //$this->dispatchBrowserEvent('update-url', [
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

    // Tambahkan method ini di class Dashboard
    public function getPengeluaranPerKelompok()
    {
        return \App\Models\KelompokPengeluaran::withSum(['pengeluaran' => function ($query) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }], 'jumlah')
        ->where('status_aktif', true)
        ->get()
        ->map(function ($kelompok) {
            return [
                'nama' => $kelompok->nama,
                'total' => $kelompok->pengeluaran_sum_jumlah ?? 0
            ];
        });
    }
}
<?php

namespace App\Filament\Widgets;

use App\Models\KelompokPengeluaran;
use App\Models\Pengeluaran;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PengeluaranChart extends ChartWidget
{
    protected static ?string $heading = 'Pengeluaran per Kelompok';
    protected static ?string $pollingInterval = null;
    protected static ?string $maxHeight = '300px';

    public ?string $filter = 'month'; // month, year, all
    public $startDate;
    public $endDate;

    protected function getFilters(): ?array
    {
        return [
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
            'all' => 'Semua Waktu',
        ];
    }

    public function mount(): void
    {
        $this->startDate = request()->input('start_date');
        $this->endDate = request()->input('end_date');
        $this->filter = 'all';
    }

    protected function getData(): array
    {
        $query = KelompokPengeluaran::query()
            ->where('status_aktif', true)
            ->leftJoin('pengeluarans', 'kelompok_pengeluaran.id', '=', 'pengeluarans.kelompok_pengeluaran_id')
            ->leftJoin('pengeluaran_detail', 'pengeluarans.id', '=', 'pengeluaran_detail.pengeluaran_id');

        // Apply date filter if provided
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('pengeluarans.tanggal', [$this->startDate, $this->endDate]);
        } else {
            // Apply chart filter
            match ($this->filter) {
                'month' => $query->whereYear('pengeluarans.tanggal', now()->year)
                            ->whereMonth('pengeluarans.tanggal', now()->month),
                'year' => $query->whereYear('pengeluarans.tanggal', now()->year),
                'all' => $query, // No filter
            };
        }

        $data = $query->select(
                'kelompok_pengeluaran.nama as kelompok',
                DB::raw('COALESCE(SUM(pengeluaran_detail.jumlah), 0) as total')
            )
            ->groupBy('kelompok_pengeluaran.id', 'kelompok_pengeluaran.nama')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengeluaran (Rp)',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', 
                        '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $data->pluck('kelompok')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => function($value) {
                            return 'Rp ' . number_format($value, 0, ',', '.');
                        }
                    ],
                ],
            ],
        ];
    }

    public function getDescription(): ?string
    {
        if ($this->startDate && $this->endDate) {
            return 'Data periode: ' . \Carbon\Carbon::parse($this->startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($this->endDate)->format('d M Y');
        }

        return match ($this->filter) {
            'month' => 'Data bulan ' . now()->translatedFormat('F Y'),
            'year' => 'Data tahun ' . now()->year,
            default => 'Data semua waktu',
        };
    }
}
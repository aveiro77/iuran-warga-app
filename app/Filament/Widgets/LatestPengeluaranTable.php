<?php

namespace App\Filament\Widgets;

use App\Models\Pengeluaran;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestPengeluaranTable extends BaseWidget
{
    protected static ?string $heading = 'Pengeluaran Terbaru';
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 2;

    public $startDate;
    public $endDate;

    public function mount(): void
    {
        $this->startDate = request()->input('start_date');
        $this->endDate = request()->input('end_date');
    }

    public function table(Table $table): Table
    {
        $query = Pengeluaran::with(['kelompokPengeluaran', 'details']);

        // Apply date filter if provided
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        } else {
            // Default: show last 10 records
            $query->latest()->take(10);
        }

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('nomor_transaksi')
                    ->label('No. Transaksi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelompokPengeluaran.nama')
                    ->label('Kelompok')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->total),
                Tables\Columns\TextColumn::make('penanggung_jawab')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('details_count')
                    ->label('Jml Item')
                    ->counts('details')
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->url(fn ($record) => route('filament.user.resources.pengeluarans.view', $record))
                    ->icon('heroicon-o-eye'),
            ])
            ->emptyStateHeading('Belum ada data pengeluaran')
            ->emptyStateDescription('Data pengeluaran akan muncul di sini setelah ditambahkan.');
    }

    public function getDescription(): ?string
    {
        if ($this->startDate && $this->endDate) {
            return 'Data periode: ' . \Carbon\Carbon::parse($this->startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($this->endDate)->format('d M Y');
        }

        return '10 transaksi terbaru';
    }
}
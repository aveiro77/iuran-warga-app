<?php

namespace App\Filament\Resources;

use App\Exports\PemasukanExport;
use App\Filament\Resources\PemasukanResource\Pages;
use App\Filament\Resources\PemasukanResource\RelationManagers;
use App\Models\Pemasukan;
use App\Models\Warga;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class PemasukanResource extends Resource
{
    protected static ?string $model = Pemasukan::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Keuangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Transaksi')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_transaksi')
                            ->label('Nomor Transaksi')
                            ->default(fn () => \App\Models\Pemasukan::generateNomorTransaksi())
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal')
                            ->required()
                            ->default(now()),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Detail Pemasukan')
                    ->schema([
                        Forms\Components\Select::make('warga_id')
                            ->label('Warga')
                            ->options(Warga::where('status_aktif', true)->pluck('nama', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('jumlah')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('keterangan')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('penarik')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('nomor_transaksi')
                ->searchable()
                ->sortable()
                ->label('No. Transaksi'),
            Tables\Columns\TextColumn::make('tanggal')
                ->date()
                ->sortable(),
                Tables\Columns\TextColumn::make('warga.nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('warga.alamat')
                    ->label('Alamat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penarik')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('warga')
                    ->relationship('warga', 'nama')
                    ->searchable(),
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Sampai Tanggal'),
                    ])
                    ->action(function (array $data) {
                        return Excel::download(
                            new PemasukanExport($data['start_date'] ?? null, $data['end_date'] ?? null), 
                            'data-pemasukan-' . now()->format('Y-m-d') . '.xlsx'
                        );
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPemasukans::route('/'),
            'create' => Pages\CreatePemasukan::route('/create'),
            'edit' => Pages\EditPemasukan::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\Resources;

use App\Exports\PengeluaranExport;
use App\Filament\Resources\PengeluaranResource\Pages;
use App\Filament\Resources\PengeluaranResource\RelationManagers;
use App\Models\KelompokPengeluaran;
use App\Models\Pengeluaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class PengeluaranResource extends Resource
{
    protected static ?string $model = Pengeluaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Keuangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengeluaran')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_transaksi')
                            ->label('Nomor Transaksi')
                            ->default(fn () => \App\Models\Pengeluaran::generateNomorTransaksi())
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal')
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('kelompok_pengeluaran_id')
                            ->label('Kelompok Pengeluaran')
                            ->relationship('kelompokPengeluaran', 'nama')
                            ->options(KelompokPengeluaran::where('status_aktif', true)->pluck('nama', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Umum')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('penanggung_jawab')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('bukti_transaksi')
                            ->label('Bukti Transaksi')
                            ->image()
                            ->directory('bukti-transaksi')
                            ->maxSize(2048)
                            ->helperText('Upload bukti transaksi (max 2MB)')
                            ->downloadable()
                            ->openable()
                            ->preserveFilenames(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Item Pengeluaran')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->label('Nama Item')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('jumlah')
                                    ->label('Jumlah')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp'),
                                Forms\Components\Textarea::make('keterangan')
                                    ->label('Keterangan Item')
                                    ->maxLength(65535),
                            ])
                            ->defaultItems(1)
                            ->minItems(1)
                            ->maxItems(20)
                            ->columnSpanFull()
                            ->grid(2)
                            ->createItemButtonLabel('Tambah Item'),
                    ]),
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
                Tables\Columns\TextColumn::make('kelompokPengeluaran.nama')
                    ->searchable()
                    ->sortable()
                    ->label('Kelompok Pengeluaran'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->total),
                Tables\Columns\TextColumn::make('penanggung_jawab')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('bukti_transaksi')
                    ->label('Bukti')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-receipt.png'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('details_count')
                    ->label('Jumlah Item')
                    ->counts('details')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelompok_pengeluaran')
                    ->relationship('kelompokPengeluaran', 'nama')
                    ->searchable()
                    ->label('Kelompok Pengeluaran'),
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
                Tables\Actions\ViewAction::make(),
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
                            new PengeluaranExport($data['start_date'] ?? null, $data['end_date'] ?? null), 
                            'data-pengeluaran-' . now()->format('Y-m-d') . '.xlsx'
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
            'index' => Pages\ListPengeluarans::route('/'),
            'create' => Pages\CreatePengeluaran::route('/create'),
            'edit' => Pages\EditPengeluaran::route('/{record}/edit'),
            'view' => Pages\ViewPengeluaran::route('/{record}'),
        ];
    }
}
<?php

namespace App\Filament\Resources\KelompokPengeluaranResource\Pages;

use App\Filament\Resources\KelompokPengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKelompokPengeluarans extends ManageRecords
{
    protected static string $resource = KelompokPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

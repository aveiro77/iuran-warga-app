<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Tambahkan style untuk form filter
        \Filament\Forms\Components\DatePicker::configureUsing(function ($component) {
            $component
                ->prefixIcon('heroicon-o-calendar')
                ->displayFormat('d/m/Y')
                ->format('Y-m-d');
        });
    }
}
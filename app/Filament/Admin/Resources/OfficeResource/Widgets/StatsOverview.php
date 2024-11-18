<?php

namespace App\Filament\Admin\Resources\OfficeResource\Widgets;

use App\Models\Office; // Import model Office
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Ambil data dari tabel offices, misalnya data pertama
        $office = Office::first(); // Sesuaikan query jika ingin mengambil data lain

        return [
            Card::make('Office Name', $office?->name ?? 'N/A'),
            Card::make('Latitude', $office?->latitude ?? 'N/A'),
            Card::make('Longitude', $office?->longitude ?? 'N/A'),

        ];
    }
}

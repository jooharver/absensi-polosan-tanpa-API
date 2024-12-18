<?php

namespace App\Filament\Admin\Resources\AbsenResource\Pages;

use App\Filament\Admin\Resources\AbsenResource;
use App\Filament\Admin\Resources\AbsenResource\Widgets\AbsenStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAbsens extends ListRecords
{
    protected static string $resource = AbsenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Absen')
            ->icon('heroicon-o-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AbsenStats::class
        ];
    }
}

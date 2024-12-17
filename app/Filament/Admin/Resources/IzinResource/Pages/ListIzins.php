<?php

namespace App\Filament\Admin\Resources\IzinResource\Pages;

use App\Filament\Admin\Resources\IzinResource;
use App\Filament\Admin\Resources\IzinResource\Widgets\IzinStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIzins extends ListRecords
{
    protected static string $resource = IzinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            IzinStats::class,
        ];
    }
}

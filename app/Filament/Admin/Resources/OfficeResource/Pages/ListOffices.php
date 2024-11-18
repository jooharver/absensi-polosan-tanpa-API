<?php

namespace App\Filament\Admin\Resources\OfficeResource\Pages;

use App\Filament\Admin\Resources\OfficeResource;
use App\Filament\Admin\Resources\OfficeResource\Widgets\StatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOffices extends ListRecords
{
    protected static string $resource = OfficeResource::class;

    protected function getHeaderWidgets(): array
    {
        return[
            StatsOverview::class,
        ];
    }
}

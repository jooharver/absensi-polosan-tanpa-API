<?php

namespace App\Filament\Admin\Resources\ThrResource\Pages;

use App\Filament\Admin\Resources\ThrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListThrs extends ListRecords
{
    protected static string $resource = ThrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

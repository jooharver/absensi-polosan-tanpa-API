<?php

namespace App\Filament\Admin\Resources\SetThrResource\Pages;

use App\Filament\Admin\Resources\SetThrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSetThrs extends ListRecords
{
    protected static string $resource = SetThrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Set THR')
            ->icon('heroicon-o-plus'),
        ];
    }
}

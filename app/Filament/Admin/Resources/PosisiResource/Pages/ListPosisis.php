<?php

namespace App\Filament\Admin\Resources\PosisiResource\Pages;

use App\Filament\Admin\Resources\PosisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPosisis extends ListRecords
{
    protected static string $resource = PosisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Posisi')
            ->icon('heroicon-o-plus'),
        ];
    }
}

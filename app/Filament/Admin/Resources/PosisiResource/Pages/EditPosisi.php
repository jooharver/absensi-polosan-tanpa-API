<?php

namespace App\Filament\Admin\Resources\PosisiResource\Pages;

use App\Filament\Admin\Resources\PosisiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosisi extends EditRecord
{
    protected static string $resource = PosisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

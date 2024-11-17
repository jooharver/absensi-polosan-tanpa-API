<?php

namespace App\Filament\Admin\Resources\ThrResource\Pages;

use App\Filament\Admin\Resources\ThrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditThr extends EditRecord
{
    protected static string $resource = ThrResource::class;

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

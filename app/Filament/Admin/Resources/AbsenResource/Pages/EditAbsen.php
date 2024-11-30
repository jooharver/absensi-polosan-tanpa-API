<?php

namespace App\Filament\Admin\Resources\AbsenResource\Pages;

use App\Filament\Admin\Resources\AbsenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAbsen extends EditRecord
{
    protected static string $resource = AbsenResource::class;

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

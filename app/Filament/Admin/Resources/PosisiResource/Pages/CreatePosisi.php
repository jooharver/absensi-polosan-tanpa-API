<?php

namespace App\Filament\Admin\Resources\PosisiResource\Pages;

use App\Filament\Admin\Resources\PosisiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePosisi extends CreateRecord
{
    protected static string $resource = PosisiResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

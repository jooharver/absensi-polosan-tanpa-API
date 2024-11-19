<?php

namespace App\Filament\Admin\Resources\ThrResource\Pages;

use App\Filament\Admin\Resources\ThrResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateThr extends CreateRecord
{
    protected static string $resource = ThrResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

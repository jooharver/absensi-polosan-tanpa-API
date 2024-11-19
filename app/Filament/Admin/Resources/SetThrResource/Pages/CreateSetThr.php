<?php

namespace App\Filament\Admin\Resources\SetThrResource\Pages;

use App\Filament\Admin\Resources\SetThrResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSetThr extends CreateRecord
{
    protected static string $resource = SetThrResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

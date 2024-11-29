<?php

namespace App\Filament\Admin\Resources\AbsenResource\Pages;

use App\Filament\Admin\Resources\AbsenResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAbsen extends CreateRecord
{
    protected static string $resource = AbsenResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

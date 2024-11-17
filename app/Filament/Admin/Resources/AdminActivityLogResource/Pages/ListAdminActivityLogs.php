<?php

namespace App\Filament\Admin\Resources\AdminActivityLogResource\Pages;

use App\Filament\Admin\Resources\AdminActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminActivityLogs extends ListRecords
{
    protected static string $resource = AdminActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources\AdminActivityLogResource\Pages;

use App\Filament\Admin\Resources\AdminActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminActivityLog extends EditRecord
{
    protected static string $resource = AdminActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

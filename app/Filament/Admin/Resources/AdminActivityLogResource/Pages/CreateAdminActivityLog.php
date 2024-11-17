<?php

namespace App\Filament\Admin\Resources\AdminActivityLogResource\Pages;

use App\Filament\Admin\Resources\AdminActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdminActivityLog extends CreateRecord
{
    protected static string $resource = AdminActivityLogResource::class;
}

<?php

namespace App\Filament\Admin\Resources\RekapAbsensiResource\Pages;

use App\Filament\Admin\Resources\RekapAbsensiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekapAbsensi extends EditRecord
{
    protected static string $resource = RekapAbsensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

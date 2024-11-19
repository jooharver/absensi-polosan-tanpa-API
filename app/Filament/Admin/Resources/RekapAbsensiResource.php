<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\RekapAbsensiView;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;
use App\Filament\Admin\Resources\RekapAbsensiResource\Pages;

class RekapAbsensiResource extends Resource
{
    protected static ?string $model = RekapAbsensiView::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama')
                    ->label('ID Karyawan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_hadir')
                    ->label('Total Hadir')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_sakit')
                    ->label('Total Sakit')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_izin')
                    ->label('Total Izin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_alpha')
                    ->label('Total Alpha')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRekapAbsensis::route('/'),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ThrResource\Pages;
use App\Filament\Admin\Resources\ThrResource\RelationManagers;
use App\Models\Thr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThrResource extends Resource
{
    protected static ?string $model = Thr::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'List THR';
    protected static ?string $label = 'THR';

    protected static ?string $pluralLabel = 'Tunjangan Hari Raya';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'THR';
    protected static ?string $navigationGroupSort = '2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('karyawan.posisi.posisi')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('thr')
                ->label('Besaran THR')
                ->searchable()
                ->sortable()
                ->formatStateUsing(fn ($state) => 'Rp. ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Export Excel')
                    ->url(route('export-thr'))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-down-tray'),
                // Mengubah ekspor PDF menjadi action yang memanggil controller
                Tables\Actions\Action::make('Export PDF')
                    ->label('Export PDF')
                    ->url(route('thr.exportPDF'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListThrs::route('/'),
            'create' => Pages\CreateThr::route('/create'),
            'edit' => Pages\EditThr::route('/{record}/edit'),
        ];
    }
}

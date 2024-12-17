<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SetThrResource\Pages;
use App\Filament\Admin\Resources\SetThrResource\RelationManagers;
use App\Models\SetThr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;



class SetThrResource extends Resource
{
    protected static ?string $model = SetThr::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Set THR';

    protected static ?string $label = 'Set THR';

    protected static ?string $pluralLabel = 'Setting Tunjangan Hari Raya';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'THR';
    protected static ?string $navigationGroupSort = '2';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('posisi_id')
                ->relationship('posisi', 'posisi')
                ->required()
                ->rule(function ($record) {
                    return Rule::unique('set_thrs', 'posisi_id')
                        ->ignore($record->id_set_thr ?? null, 'id_set_thr'); // Ensure correct ID is ignored
                })
                ->label('Posisi'),

                Forms\Components\TextInput::make('besaran_thr')
                ->required()
                ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('posisi.posisi')
                ->searchable(),
                Tables\Columns\TextColumn::make('besaran_thr')
                ->label('Nominal')
                ->formatStateUsing(fn (string $state): string => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListSetThrs::route('/'),
            'create' => Pages\CreateSetThr::route('/create'),
            'edit' => Pages\EditSetThr::route('/{record}/edit'),
        ];
    }
}

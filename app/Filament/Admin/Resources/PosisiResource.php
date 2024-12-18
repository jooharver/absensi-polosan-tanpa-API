<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PosisiResource\Pages;
use App\Filament\Admin\Resources\PosisiResource\RelationManagers;
use App\Models\Posisi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Validation\Rule;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PosisiResource extends Resource
{
    protected static ?string $model = Posisi::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?string $navigationLabel = 'Posisi';

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Administrasi';


    protected static ?string $pluralLabel = 'Kelola Posisi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('posisi')
                    ->required()
                    ->maxLength(50)
                    ->rule(function ($record) {
                        return Rule::unique('posisis', 'posisi')
                            ->ignore($record->id_posisi ?? null, 'id_posisi'); // Ensure correct ID is ignored
                    }),
                Forms\Components\TextInput::make('jam_kerja_per_hari')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('hari_kerja_per_minggu')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jam_masuk')
                    ->required()
                    ,
                Forms\Components\TextInput::make('jam_keluar')
                    ->required()
                    ,
                Forms\Components\TimePicker::make('grace_period')
                    ->label('Grace Period')
                    ->default('00:15:00')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('posisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jam_kerja_per_hari')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hari_kerja_per_minggu')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('jam_masuk')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state !== null ? sprintf('%02d:00 WIB', $state) : null),

                Tables\Columns\TextColumn::make('jam_keluar')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state !== null ? sprintf('%02d:00 WIB', $state) : null),

                Tables\Columns\TextColumn::make('grace_period')
                    ->label('Grace Period')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state !== null ? (int) \Carbon\Carbon::parse($state)->minute . "'" : null),

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
            'index' => Pages\ListPosisis::route('/'),
            'create' => Pages\CreatePosisi::route('/create'),
            'edit' => Pages\EditPosisi::route('/{record}/edit'),
        ];
    }
}

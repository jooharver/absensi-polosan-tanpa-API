<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdminActivityLogResource\Pages;
use App\Filament\Admin\Resources\AdminActivityLogResource\RelationManagers;
use App\Models\AdminActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminActivityLogResource extends Resource
{
    protected static ?string $model = AdminActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Activity Log';

    protected static ?string $pluralLabel = 'Activity Log';
    protected static ?string $navigationGroup = 'Logs';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->numeric(),
                Forms\Components\TextInput::make('action')
                    ->maxLength(255),
                Forms\Components\Textarea::make('from')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('to')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('action_time'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->default('Null')
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('from')
                    ->default('Null')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('to')
                    ->searchable()
                    ->default('Null'),
                Tables\Columns\TextColumn::make('action_time')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListAdminActivityLogs::route('/'),
            'edit' => Pages\EditAdminActivityLog::route('/{record}/edit'),
        ];
    }
}

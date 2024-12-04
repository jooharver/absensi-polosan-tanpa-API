<?php

namespace App\Filament\Admin\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Absen;
use Filament\Forms\Form;
use App\Models\ViewAbsen;
use App\Models\AbsenMasuk;
use Filament\Tables\Table;
use App\Models\AbsenKeluar;
use Filament\Resources\Resource;
use App\Http\Controllers\AbsenController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\AbsenResource\Pages;
use App\Filament\Admin\Resources\AbsenResource\RelationManagers;

class AbsenResource extends Resource
{
    protected static ?string $model = Absen::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    protected static ?string $navigationLabel = 'Absensi';

    protected static ?string $pluralLabel = 'Kelola Absensi';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Administrasi';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TimePicker::make('jam_masuk')
            ,
            Forms\Components\TimePicker::make('jam_keluar')
            ,

                Forms\Components\DatePicker::make('tanggal')
                ->default(now('Asia/Jakarta')->toDateString()) // sets the default date to today's date in Asia/Jakarta timezone
                ->required()
                ->label('Tanggal'),
                Forms\Components\Select::make('karyawan_id')
                ->relationship('karyawan', 'nama')
                ->required(),
                Forms\Components\TextInput::make('sakit')
                ->default('00:00:00'),
                Forms\Components\TextInput::make('izin')
                ->default('00:00:00'),
                Forms\Components\TextInput::make('alpha')
                ->default('00:00:00'),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('tanggal'),
            Tables\Columns\TextColumn::make('karyawan.nama'),
            Tables\Columns\TextColumn::make('jam_masuk'),
            Tables\Columns\TextColumn::make('jam_keluar'),
            Tables\Columns\TextColumn::make('hadir'),
            Tables\Columns\TextColumn::make('sakit'),
            Tables\Columns\TextColumn::make('izin'),
            Tables\Columns\TextColumn::make('alpha'),

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
                ->label('Export Excel')
                ->url(route('export-absensi'))
                ->icon('heroicon-o-arrow-down-tray')
                ->openUrlInNewTab(),
            // Mengubah ekspor PDF menjadi action yang memanggil controller
            Tables\Actions\Action::make('Export PDF')
                ->label('Export PDF')
                ->url(route('absensi.exportPDF'))
                ->icon('heroicon-o-arrow-down-tray')
                ->openUrlInNewTab(),
        ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAbsens::route('/'),
            'create' => Pages\CreateAbsen::route('/create'),
            'edit' => Pages\EditAbsen::route('/{record}/edit'),
        ];
    }
}

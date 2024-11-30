<?php

namespace App\Filament\Admin\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Absen;
use Filament\Forms\Form;
use App\Models\AbsenMasuk;
use Filament\Tables\Table;
use App\Models\AbsenKeluar;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\AbsenResource\Pages;
use App\Filament\Admin\Resources\AbsenResource\RelationManagers;
use App\Models\ViewAbsen;

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

            Forms\Components\Section::make('Absen Masuk')
                ->schema([
                    Forms\Components\TimePicker::make('jam_masuk')
                        ->label('Jam Masuk')
                ])
                ->relationship('absen_masuk'),

            Forms\Components\Section::make('Absen Keluar')
                ->schema([
                    Forms\Components\TimePicker::make('jam_keluar')
                        ->label('Jam Keluar'),
                ])
                ->relationship('absen_keluar'),
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
            Tables\Columns\TextColumn::make('absen_masuk.jam_masuk'),
            Tables\Columns\TextColumn::make('absen_keluar.jam_keluar'),
            Tables\Columns\TextColumn::make('view_hadir')
                ->label('Hadir')
                ->getStateUsing(function ($record) {
                    // Ambil data hadir dari view_absen menggunakan id_absen
                    $viewAbsen = ViewAbsen::where('id_absen', $record->id)->first();
                    $hadir = $viewAbsen ? $viewAbsen->hadir : '00:00:00';
                    // Format waktu menjadi 00:00
                    return substr($hadir, 0, 5);
                }),
            Tables\Columns\TextColumn::make('sakit')
                ->getStateUsing(function ($record) {
                    // Format waktu menjadi 00:00
                    return substr($record->sakit ?? '00:00:00', 0, 5);
                }),
            Tables\Columns\TextColumn::make('izin')
                ->getStateUsing(function ($record) {
                    // Format waktu menjadi 00:00
                    return substr($record->izin ?? '00:00:00', 0, 5);
                }),
                Tables\Columns\TextColumn::make('view_alpha')
                ->label('Alpha')
                ->getStateUsing(function ($record) {
                    // Ambil data hadir dari view_absen menggunakan id_absen
                    $viewAbsen = ViewAbsen::where('id_absen', $record->id)->first();
                    $alpha = $viewAbsen ? $viewAbsen->alpha : '00:00:00';
                    // Format waktu menjadi 00:00
                    return substr($alpha, 0, 5);
                }),
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

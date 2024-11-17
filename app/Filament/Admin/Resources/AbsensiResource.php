<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Resources\AbsensiResource\Pages;
use App\Filament\Resources\AbsensiResource\RelationManagers;
use App\Models\Absensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;
use App\Filament\Resources\AbsensiResource\Widgets\StatsOverview;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    protected static ?string $navigationLabel = 'Absensi';

    protected static ?string $pluralLabel = 'Kelola Absensi';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Administrasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('karyawan_id')
                    ->relationship('karyawan', 'nama'),
                Forms\Components\DatePicker::make('tanggal')
                ->default(now()->timezone('Asia/Jakarta')->toDateString()) // sets the default date to today's date in Indonesia
                ->required()
                ->label('Tanggal'),
                Forms\Components\TextInput::make('jam_masuk'),
                Forms\Components\TextInput::make('jam_keluar'),
                Forms\Components\Select::make('status')
                ->options([
                    'Hadir' => 'Hadir',
                    'Sakit' => 'Sakit',
                    'Izin' => 'Izin',
                    'Alpa' => 'Alpa'
                ]),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_masuk')
                    ->label('Jam Masuk')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('H:i')),
                Tables\Columns\TextColumn::make('jam_keluar')
                    ->label('Jam Keluar')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('H:i')),
                Tables\Columns\TextColumn::make('durasi'),
                Tables\Columns\TextColumn::make('status'),
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
                Tables\Filters\Filter::make('tanggal_hari_ini')
                    ->label('Tanggal Hari Ini')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal', Carbon::today())),

                Tables\Filters\Filter::make('kemarin')
                    ->label('Kemarin')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal', Carbon::yesterday())),

                Tables\Filters\Filter::make('seminggu_terakhir')
                    ->label('Seminggu Terakhir')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal', '>=', Carbon::today()->subWeek())),

                Tables\Filters\Filter::make('sebulan_terakhir')
                    ->label('Sebulan Terakhir')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal', '>=', Carbon::today()->subMonth())),

                Tables\Filters\Filter::make('setahun_terakhir')
                    ->label('Setahun Terakhir')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal', '>=', Carbon::today()->subYear())),

                Tables\Filters\Filter::make('status_hadir')
                    ->label('Hadir')
                    ->query(fn (Builder $query) => $query->where('status', 'Hadir')),

                Tables\Filters\Filter::make('status_sakit')
                    ->label('Sakit')
                    ->query(fn (Builder $query) => $query->where('status', 'Sakit')),

                Tables\Filters\Filter::make('status_izin')
                    ->label('Izin')
                    ->query(fn (Builder $query) => $query->where('status', 'Izin')),

                Tables\Filters\Filter::make('status_alpa')
                    ->label('Alpa')
                    ->query(fn (Builder $query) => $query->where('status', 'Alpa')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Export Excel')
                    ->url(route('export-absensi'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->openUrlInNewTab(),
                // Mengubah ekspor PDF menjadi action yang memanggil controller
                Tables\Actions\Action::make('Export PDF')
                    ->label('Export PDF')
                    ->url(route('absensi.exportPDF'))
                    ->icon('heroicon-o-arrow-down-tray') // Pastikan route ini ada
                    ->openUrlInNewTab(),
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
            'index' => AbsensiResource\Pages\ListAbsensis::route('/'),
            'create' => AbsensiResource\Pages\CreateAbsensi::route('/create'),
            'edit' => AbsensiResource\Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }


}

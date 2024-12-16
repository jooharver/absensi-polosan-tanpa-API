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

    protected static ?string $pluralLabel = 'Absensi';
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
                ->relationship('karyawan', 'nama')->preload()
                ->searchable()
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
            Tables\Columns\TextColumn::make('tanggal')
            ->date('d M Y')
            ->searchable(),
            Tables\Columns\TextColumn::make('karyawan.nama')->limit(13)
            ->searchable(),
            Tables\Columns\TextColumn::make('jam_masuk')
            ->label('Masuk')
            ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::createFromFormat('H:i:s', $state)->format('H:i') : null),
            Tables\Columns\TextColumn::make('jam_keluar')
            ->label('Keluar')
            ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::createFromFormat('H:i:s', $state)->format('H:i') : null),
            Tables\Columns\TextColumn::make('hadir')
            ->default('00:00:00')
            ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::createFromFormat('H:i:s', $state)->format('H:i') : null),
            Tables\Columns\TextColumn::make('sakit')
            ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::createFromFormat('H:i:s', $state)->format('H:i') : null),
            Tables\Columns\TextColumn::make('izin')
            ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::createFromFormat('H:i:s', $state)->format('H:i') : null),
            Tables\Columns\TextColumn::make('alpha')
            ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::createFromFormat('H:i:s', $state)->format('H:i') : null),
            Tables\Columns\TextColumn::make('keterangan')->limit(15),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])->paginated([20])

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
            Tables\Filters\Filter::make('tanggal_hari_ini')
                ->label('Hari Ini')
                ->query(fn (Builder $query) => $query->whereDate('tanggal', Carbon::today())),

            Tables\Filters\Filter::make('kemarin')
                ->label('Kemarin')
                ->query(fn (Builder $query) => $query->whereDate('tanggal', Carbon::yesterday())),

            Tables\Filters\Filter::make('seminggu_terakhir')
            ->label('Seminggu Terakhir')
            ->query(fn (Builder $query) => $query->whereBetween('tanggal', [
                Carbon::today()->subDays(6), // 6 hari sebelumnya
                Carbon::today() // Hari ini
            ])),

            Tables\Filters\Filter::make('sebulan_terakhir')
            ->label('Sebulan Terakhir')
            ->query(fn (Builder $query) => $query->whereBetween('tanggal', [
                Carbon::today()->subDays(29), // 29 hari sebelumnya
                Carbon::today() // Hari ini
            ])),

            Tables\Filters\Filter::make('tahun_ini')
            ->label('Tahun Ini')
            ->query(fn (Builder $query) => $query->whereYear('tanggal', Carbon::now()->year)),

            // Filter untuk bulan Januari
            Tables\Filters\Filter::make('bulan_januari')
                ->label('Januari')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 1)),

            Tables\Filters\Filter::make('bulan_februari')
                ->label('Februari')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 2)),

            Tables\Filters\Filter::make('bulan_maret')
                ->label('Maret')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 3)),

            Tables\Filters\Filter::make('bulan_april')
                ->label('April')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 4)),

            Tables\Filters\Filter::make('bulan_juni')
                ->label('Mei')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 5)),

            Tables\Filters\Filter::make('bulan_juli')
                ->label('Juni')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 6)),

            Tables\Filters\Filter::make('bulan_juli')
                ->label('Juli')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 7)),

            Tables\Filters\Filter::make('bulan_agustus')
                ->label('Agustus')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 8)),

            Tables\Filters\Filter::make('bulan_september')
                ->label('September')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 9)),

            Tables\Filters\Filter::make('bulan_oktober')
                ->label('Oktober')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 10)),

            Tables\Filters\Filter::make('bulan_november')
                ->label('November')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 11)),

            Tables\Filters\Filter::make('bulan_desember')
                ->label('Desember')
                ->query(fn (Builder $query) => $query->whereMonth('tanggal', 12)),

            Tables\Filters\Filter::make('status_hadir')
                ->label('Hadir')
                ->query(fn (Builder $query) => $query->where('hadir', '!=', '00:00:00')),

            Tables\Filters\Filter::make('status_sakit')
                ->label('Sakit')
                ->query(fn (Builder $query) => $query->where('sakit', '!=', '00:00:00')),

            Tables\Filters\Filter::make('status_izin')
                ->label('Izin')
                ->query(fn (Builder $query) => $query->where('izin', '!=', '00:00:00')),

            Tables\Filters\Filter::make('status_alpa')
                ->label('Alpha')
                ->query(fn (Builder $query) => $query->where('alpha', '!=', '00:00:00')),
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

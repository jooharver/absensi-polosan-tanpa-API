<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\IzinResource\Pages;
use App\Filament\Admin\Resources\IzinResource\RelationManagers;
use App\Models\Izin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IzinResource extends Resource
{
    protected static ?string $model = Izin::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Izin';

    protected static ?string $pluralLabel = 'Izin';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Administrasi';

    public static function getNavigationBadge(): ?string
    {
        return (string) Izin::whereDate('start', now()->toDateString())->count(); // Hitung izin hari ini
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('karyawan_id')
                    ->label('Nama')
                    ->relationship('karyawan', 'nama')->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('jenis') // Dropdown untuk jenis
                    ->options([
                        'sakit' => 'Sakit',
                        'izin' => 'Izin',
                    ])
                    ->required()
                    ->label('Jenis'),
                Forms\Components\DatePicker::make('start')
                    ->required()
                    ->default(now())
                    ->label('Tanggal Mulai'),
                Forms\Components\DatePicker::make('end')
                    ->required()
                    ->default(now())
                    ->label('Tanggal Selesai'),
                Forms\Components\Select::make('status') // Dropdown untuk status
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required()
                    ->label('Status'),
                Forms\Components\Textarea::make('keterangan') // Textarea untuk deskripsi
                    ->maxLength(255)
                    ->required()
                    ->label('Keterangan'),
                Forms\Components\FileUpload::make('image_path')
                    ->image()
                    ->label('Unggah Bukti (Opsional)'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_izin')
                ->label('ID'),
                Tables\Columns\TextColumn::make('karyawan.nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d-M-Y' . ', ' .'H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis'),
                Tables\Columns\TextColumn::make('start')
                ->label('Mulai tanggal')
                    ->date('d-M-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end')
                ->label('Sampai tanggal')
                ->date('d-M-Y')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'success' => 'approved',
                    'warning' => 'pending',
                    'danger' => 'rejected'
                ]),
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
            'index' => Pages\ListIzins::route('/'),
            'create' => Pages\CreateIzin::route('/create'),
            'edit' => Pages\EditIzin::route('/{record}/edit'),
        ];
    }
}

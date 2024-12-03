<?php

namespace App\Filament\Admin\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Karyawan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AdminActivityLog;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\KaryawanResource\Pages;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // For logging
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Karyawan';

    protected static ?string $pluralLabel = 'Kelola Karyawan';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Administrasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->maxLength(16),
                Forms\Components\TextInput::make('nama')
                    ->label('Nama lengkap')
                    ->required()
                    ->maxLength(100),
                Forms\Components\DatePicker::make('tanggal_lahir')
                ->required(),
                Forms\Components\Select::make('jenis_kelamin')
                ->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan'
                ]),
                Forms\Components\Textarea::make('alamat')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Select::make('agama')
                    ->options([
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen',
                        'Katolik' => 'Katolik',
                        'Hindu' => 'Hindu',
                        'Buddha' => 'Buddha',
                        'Konghucu' => 'Konghucu',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('no_telepon')
                    ->tel()
                    ->maxLength(15)
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(100),
                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->required()
                    ->default(Carbon::now('Asia/Jakarta')),
                Forms\Components\FileUpload::make('face_vector')
                    ->label('Upload Foto')
                    ->image()
                    ->directory('faces'),
                Forms\Components\Select::make('posisi_id')
                    ->relationship('posisi', 'posisi')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Debug
                Tables\Columns\TextColumn::make('face_vector')
                    ->limit(50), // Optional, for debugging

                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin'),
                Tables\Columns\TextColumn::make('alamat'),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('posisi.posisi')
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
                // Filter usia 18-30
                Tables\Filters\Filter::make('usia_18_30')
                    ->label('Usia 18-30')
                    ->query(function (Builder $query) {
                        $query->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 18 AND 30');
                    }),

                // Filter usia 30-60
                Tables\Filters\Filter::make('usia_30_60')
                    ->label('Usia 30-60')
                    ->query(function (Builder $query) {
                        $query->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 30 AND 60');
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Export Excel')
                    ->label('Export Excel')
                    ->url(route('export-karyawan'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->openUrlInNewTab(),
                // Mengubah ekspor PDF menjadi action yang memanggil controller
                Tables\Actions\Action::make('Export PDF')
                    ->label('Export PDF')
                    ->url(route('karyawan.exportPDF'))
                    ->icon('heroicon-o-arrow-down-tray')
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

    protected static function afterCreate(Karyawan $record): void
    {
        AdminActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'from' => null, // Tidak ada data sebelumnya
            'to' => json_encode($record->getAttributes()),
        ]);

        // Call saveFaceVector after creation
        $imagePath = $record->face_vector; // Replace with the actual attribute name for the image path
        $faceRecognitionController = new \App\Http\Controllers\FaceRecognitionController();
        $faceRecognitionController->saveFaceVector($imagePath, $record->id_karyawan);
    }

    protected static function afterUpdate(Karyawan $record): void
    {
        AdminActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'from' => json_encode($record->getOriginal()),
            'to' => json_encode($record->getAttributes()),
        ]);

        // Call saveFaceVector after update
        $imagePath = $record->face_vector; // Replace with the actual attribute name for the image path
        $faceRecognitionController = new \App\Http\Controllers\FaceRecognitionController();
        $faceRecognitionController->saveFaceVector($imagePath, $record->id_karyawan);
    }

    protected static function afterDelete(Karyawan $record): void
    {
        AdminActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'from' => json_encode($record->getAttributes()),
            'to' => null, // Karena data ini dihapus
        ]);
    }

    public static function getRecordRouteKeyName(): string
    {
        return 'id_karyawan';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}

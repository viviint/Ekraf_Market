<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Manajemen Produk';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),

                Forms\Components\TextInput::make('name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0),

                Forms\Components\TextInput::make('stock')
                    ->label('Stok Awal')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->helperText('Gunakan fitur "Update Stok" di tabel untuk penyesuaian cepat.'),

                Forms\Components\FileUpload::make('image')
                    ->label('Foto Produk')
                    ->image()
                    ->directory('products')
                    ->imageEditor() // Fitur crop bawaan filament
                    ->maxSize(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->size(50)
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                // --- BAGIAN LOGIC WARNA STOK ---
                Tables\Columns\TextColumn::make('stock')
                    ->label('Sisa Stok')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (int)$state <= 5 => 'danger',   // Merah (Kritis)
                        (int)$state <= 20 => 'warning', // Kuning (Menipis)
                        default => 'success',           // Hijau (Aman)
                    })
                    ->icon(fn (string $state): ?string => match (true) {
                        (int)$state <= 5 => 'heroicon-m-exclamation-circle', // Ikon peringatan
                        default => null,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter Kategori
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Kategori'),

                // Filter Stok Kritis (< 10)
                Tables\Filters\Filter::make('critical_stock')
                    ->label('Stok Kritis (< 10)')
                    ->query(fn (Builder $query) => $query->where('stock', '<', 10))
                    ->indicator('Stok Kritis'), // Muncul badge di atas tabel kalau aktif
            ])
            ->actions([
                // Fitur Keren: Update Stok Tanpa Edit Halaman
                Tables\Actions\Action::make('update_stock')
                    ->label('Update Stok')
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->color('warning')
                    ->modalWidth('sm')
                    ->form([
                        Forms\Components\TextInput::make('stock_adjustment')
                            ->label('Penyesuaian Stok')
                            ->numeric()
                            ->required()
                            ->helperText('Masukkan angka positif (+) untuk menambah, atau negatif (-) untuk mengurangi.')
                            ->placeholder('Contoh: 10 atau -5'),
                    ])
                    ->action(function (Product $record, array $data) {
                        $adjustment = (int) $data['stock_adjustment'];
                        $newStock = $record->stock + $adjustment;

                        // Cegah stok minus
                        if ($newStock < 0) {
                             FilamentNotification::make()
                                ->title('Gagal!')
                                ->body('Stok tidak boleh kurang dari 0.')
                                ->danger()
                                ->send();
                             return;
                        }

                        $record->update(['stock' => $newStock]);

                        FilamentNotification::make()
                            ->title('Stok Berhasil Diupdate')
                            ->body("Stok {$record->name} sekarang: {$newStock}")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    // Biar cuma admin yang bisa akses
    public static function canViewAny(): bool
    {
        return auth()->user() && auth()->user()->isAdmin();
    }
}

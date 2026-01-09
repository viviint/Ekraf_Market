<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// INI PENTING BANG: Import class kolom biar gak error
use Filament\Tables\Columns\TextColumn;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    // Saya ganti iconnya jadi Bintang biar cocok sama Review
    protected static ?string $navigationIcon = 'heroicon-o-star';

    // Label di menu sidebar
    protected static ?string $navigationLabel = 'Review Customer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Kita kosongin dulu gak apa-apa, karena admin jarang edit review
                // Biasanya cuma hapus aja kalau ada review toxic
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Nama User (Otomatis ambil dari relasi user -> name)
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                // 2. Nama Produk (Otomatis ambil dari relasi product -> name)
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->sortable()
                    ->searchable(),

                // 3. Rating (Pakai warna-warni biar Admin seneng liatnya)
                TextColumn::make('rating')
                    ->label('Bintang')
                    ->sortable()
                    ->badge() // Biar bentuknya kayak lencana
                    ->color(fn (string $state): string => match ($state) {
                        '5' => 'success', // Hijau (Mantap)
                        '4' => 'info',    // Biru (Oke)
                        '3' => 'warning', // Kuning (Biasa)
                        default => 'danger', // Merah (Jelek)
                    }),

                // 4. Komentar Review
                TextColumn::make('comment')
                    ->label('Ulasan')
                    ->limit(50) // Batasi 50 huruf biar tabel gak kepanjangan
                    ->tooltip(function (TextColumn $column): ?string {
                        return $column->getState(); // Kalau di-hover muncul full text
                    }),

                // 5. Tanggal Review
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Cukup tombol Delete aja, admin jarang edit komentar user
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}

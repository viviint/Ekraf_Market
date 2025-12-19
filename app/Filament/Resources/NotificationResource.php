<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    
    protected static ?string $navigationGroup = 'Sistem';
    
    protected static ?int $navigationSort = 10;
    
    protected static ?string $navigationBadgeTooltip = 'Notifikasi belum dibaca';
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::unread()->count() ?: null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::unread()->count() > 0 ? 'warning' : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->disabled(),
                    
                Forms\Components\Textarea::make('message')
                    ->label('Pesan')
                    ->required()
                    ->disabled(),
                    
                Forms\Components\Toggle::make('is_read')
                    ->label('Sudah Dibaca')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_read')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-bell-alert')
                    ->trueColor('success')
                    ->falseColor('warning'),
                    
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipe')
                    ->colors([
                        'success' => 'new_order',
                        'info' => 'payment_uploaded',
                        'warning' => 'low_stock',
                        'danger' => 'out_of_stock',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'new_order' => 'Pesanan Baru',
                        'payment_uploaded' => 'Pembayaran',
                        'low_stock' => 'Stok Rendah',
                        'out_of_stock' => 'Stok Habis',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('message')
                    ->label('Pesan')
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'new_order' => 'Pesanan Baru',
                        'payment_uploaded' => 'Pembayaran',
                        'low_stock' => 'Stok Rendah',
                        'out_of_stock' => 'Stok Habis',
                    ]),
                    
                Tables\Filters\Filter::make('unread')
                    ->label('Belum Dibaca')
                    ->query(fn ($query) => $query->where('is_read', false))
                    ->default(),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_as_read')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (Notification $record) => $record->markAsRead())
                    ->visible(fn (Notification $record): bool => !$record->is_read),
                    
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_all_read')
                        ->label('Tandai Semua Dibaca')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->markAsRead()),
                        
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s'); // Auto refresh every 30s
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
            'index' => Pages\ListNotifications::route('/'),
        ];
    }
    
    // Hide from mahasiswa
    public static function canViewAny(): bool
    {
        return auth()->user()->isAdmin();
    }
}

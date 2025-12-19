<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\Storage;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Manajemen Pesanan';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Customer')
                    ->required()
                    ->searchable()
                    ->disabled(),
                    
                Forms\Components\TextInput::make('invoice_number')
                    ->label('No Invoice')
                    ->required()
                    ->disabled(),
                    
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Menunggu Pembayaran' => 'Menunggu Pembayaran',
                        'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                        'Dibayar' => 'Dibayar',
                        'Diproses' => 'Diproses',
                        'Dikirim' => 'Dikirim',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ])
                    ->required(),
                    
                Forms\Components\TextInput::make('total_amount')
                    ->label('Total')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled(),
                    
                Forms\Components\TextInput::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->disabled(),
                    
                Forms\Components\TextInput::make('shipping_name')
                    ->label('Nama Penerima')
                    ->disabled(),
                    
                Forms\Components\Textarea::make('shipping_address')
                    ->label('Alamat Pengiriman')
                    ->disabled(),
                    
                Forms\Components\TextInput::make('shipping_phone')
                    ->label('No Telepon')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('No Invoice')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'Menunggu Pembayaran',
                        'info' => 'Menunggu Verifikasi',
                        'success' => ['Dibayar', 'Diproses', 'Dikirim', 'Selesai'],
                        'danger' => 'Dibatalkan',
                    ]),
                    
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Pembayaran')
                    ->badge(),
                    
                Tables\Columns\IconColumn::make('payment_proof')
                    ->label('Bukti Bayar')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Menunggu Pembayaran' => 'Menunggu Pembayaran',
                        'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                        'Dibayar' => 'Dibayar',
                        'Diproses' => 'Diproses',
                        'Dikirim' => 'Dikirim',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ]),
                    
                Tables\Filters\Filter::make('has_payment_proof')
                    ->label('Ada Bukti Bayar')
                    ->query(fn ($query) => $query->whereNotNull('payment_proof')),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($q) => $q->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                
                Tables\Actions\Action::make('view_payment_proof')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->url(fn (Order $record): ?string => 
                        $record->payment_proof ? Storage::url($record->payment_proof) : null
                    )
                    ->openUrlInNewTab()
                    ->visible(fn (Order $record): bool => $record->payment_proof !== null),
                    
                Tables\Actions\Action::make('verify_payment')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Pembayaran')
                    ->modalDescription(fn (Order $record) => "Verifikasi pembayaran untuk order {$record->invoice_number}?")
                    ->action(function (Order $record) {
                        $record->update([
                            'status' => 'Dibayar',
                            'paid_at' => now(),
                        ]);
                        
                        FilamentNotification::make()
                            ->title('Pembayaran Terverifikasi')
                            ->body("Order {$record->invoice_number} telah diverifikasi")
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Order $record): bool => 
                        $record->status === 'Menunggu Verifikasi' && $record->payment_proof !== null
                    ),
                    
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    
    // Hide from mahasiswa
    public static function canViewAny(): bool
    {
        return auth()->user()->isAdmin();
    }
}

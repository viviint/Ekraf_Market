<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use Carbon\Carbon;

class SalesOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            // TOTAL PENJUALAN HARI INI
            Stat::make(
                'Penjualan Hari Ini',
                'Rp ' . number_format(
                    Order::whereDate('created_at', Carbon::today())
                        ->sum('total_amount'),
                    0,
                    ',',
                    '.'
                )
            )
            ->description('Total transaksi hari ini')
            ->color('success'),

            // TOTAL PENJUALAN BULAN INI
            Stat::make(
                'Penjualan Bulan Ini',
                'Rp ' . number_format(
                    Order::whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('total_amount'),
                    0,
                    ',',
                    '.'
                )
            )
            ->description('Total transaksi bulan berjalan')
            ->color('primary'),

            // JUMLAH PESANAN
            Stat::make(
                'Total Pesanan',
                Order::count()
            )
            ->description('Jumlah seluruh pesanan')
            ->color('warning'),
        ];
    }
}

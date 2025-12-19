<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan (7 Hari Terakhir)';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get last 7 days data
        $data = collect();
        $labels = collect();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels->push($date->format('d M'));
            
            $totalSales = Order::whereDate('created_at', $date)
                ->sum('total_amount');
                
            $data->push($totalSales / 1000); // Convert to thousands for better display
        }

        return [
            'datasets' => [
                [
                    'label' => 'Penjualan (Ribu Rupiah)',
                    'data' => $data->toArray(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

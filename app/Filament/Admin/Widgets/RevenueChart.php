<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return 'Receita dos Últimos 30 Dias';
    }

    protected function getData(): array
    {
        $data = collect(range(29, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'date' => $date->format('d/m'),
                'revenue' => Order::where('payment_status', 'paid')
                    ->whereDate('created_at', $date)
                    ->sum('total'),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Receita (R$)',
                    'data' => $data->pluck('revenue')->toArray(),
                    'borderColor' => '#C9A84C',
                    'backgroundColor' => 'rgba(201, 168, 76, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

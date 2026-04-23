<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $ordersToday = Order::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        return [
            Stat::make('Receita Total', 'R$ ' . number_format($totalRevenue, 2, ',', '.'))
                ->description('Pedidos pagos')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Pedidos Hoje', $ordersToday)
                ->description('Novos pedidos hoje')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),
            Stat::make('Pedidos Pendentes', $pendingOrders)
                ->description('Aguardando pagamento')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Produtos Ativos', Product::where('is_active', true)->count())
                ->description('No catálogo')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
        ];
    }
}

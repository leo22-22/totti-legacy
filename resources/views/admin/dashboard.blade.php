@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="bg-success bg-opacity-10 text-success rounded p-2 me-3">
                        <i class="bi bi-currency-dollar fs-5"></i>
                    </span>
                    <div>
                        <div class="text-muted small">Receita Total</div>
                        <div class="fw-bold fs-5">R$ {{ number_format($stats['revenue'], 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="text-muted" style="font-size:.75rem">Pedidos pagos</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                        <i class="bi bi-cart3 fs-5"></i>
                    </span>
                    <div>
                        <div class="text-muted small">Pedidos Hoje</div>
                        <div class="fw-bold fs-5">{{ $stats['orders_today'] }}</div>
                    </div>
                </div>
                <div class="text-muted" style="font-size:.75rem">Novos pedidos</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="bg-warning bg-opacity-10 text-warning rounded p-2 me-3">
                        <i class="bi bi-clock fs-5"></i>
                    </span>
                    <div>
                        <div class="text-muted small">Pedidos Pendentes</div>
                        <div class="fw-bold fs-5">{{ $stats['pending_orders'] }}</div>
                    </div>
                </div>
                <div class="text-muted" style="font-size:.75rem">Aguardando pagamento</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="bg-info bg-opacity-10 text-info rounded p-2 me-3">
                        <i class="bi bi-bag fs-5"></i>
                    </span>
                    <div>
                        <div class="text-muted small">Produtos Ativos</div>
                        <div class="fw-bold fs-5">{{ $stats['active_products'] }}</div>
                    </div>
                </div>
                @if($stats['low_stock'] > 0)
                    <div class="text-danger" style="font-size:.75rem">
                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $stats['low_stock'] }} com estoque baixo
                    </div>
                @else
                    <div class="text-muted" style="font-size:.75rem">No catálogo</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-semibold">Pedidos Recentes</h6>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Status</th>
                        <th>Pagamento</th>
                        <th>Total</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_orders as $order)
                    <tr>
                        <td class="fw-semibold">{{ $order->order_number }}</td>
                        <td>
                            {{ $order->user?->name ?? '—' }}
                            <div class="text-muted" style="font-size:.75rem">{{ $order->user?->email }}</div>
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'pending'    => ['warning', 'Aguardando'],
                                    'processing' => ['info',    'Processando'],
                                    'paid'       => ['success', 'Pago'],
                                    'shipped'    => ['primary', 'Enviado'],
                                    'delivered'  => ['success', 'Entregue'],
                                    'cancelled'  => ['danger',  'Cancelado'],
                                    'refunded'   => ['secondary','Reembolsado'],
                                ];
                                [$color, $label] = $statusMap[$order->status] ?? ['secondary', $order->status];
                            @endphp
                            <span class="badge text-bg-{{ $color }}">{{ $label }}</span>
                        </td>
                        <td>
                            @php
                                $pm = ['stripe' => '💳 Cartão', 'mercadopago' => '🛒 MP', 'pix' => '⚡ PIX'];
                            @endphp
                            <span class="text-muted small">{{ $pm[$order->payment_method] ?? $order->payment_method }}</span>
                        </td>
                        <td class="fw-semibold">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                        <td class="text-muted small">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Nenhum pedido ainda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

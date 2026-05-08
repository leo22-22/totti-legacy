@extends('admin.layouts.app')
@section('title', 'Pedidos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-semibold">Pedidos</h5>
    <small class="text-muted">{{ $orders->total() }} pedido(s)</small>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Buscar por número ou cliente..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos os status</option>
                    <option value="pending"    {{ request('status') === 'pending'    ? 'selected' : '' }}>Aguardando</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processando</option>
                    <option value="paid"       {{ request('status') === 'paid'       ? 'selected' : '' }}>Pago</option>
                    <option value="shipped"    {{ request('status') === 'shipped'    ? 'selected' : '' }}>Enviado</option>
                    <option value="delivered"  {{ request('status') === 'delivered'  ? 'selected' : '' }}>Entregue</option>
                    <option value="cancelled"  {{ request('status') === 'cancelled'  ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_method" class="form-select form-select-sm">
                    <option value="">Pagamento</option>
                    <option value="stripe"      {{ request('payment_method') === 'stripe'      ? 'selected' : '' }}>Cartão</option>
                    <option value="mercadopago" {{ request('payment_method') === 'mercadopago' ? 'selected' : '' }}>MercadoPago</option>
                    <option value="pix"         {{ request('payment_method') === 'pix'         ? 'selected' : '' }}>PIX</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-secondary">Filtrar</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>Pagamento</th>
                    <th>Total</th>
                    <th>Data</th>
                    <th>Rastreio</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="fw-semibold small">{{ $order->order_number }}</td>
                    <td>
                        <div>{{ $order->user?->name ?? '—' }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $order->user?->email }}</div>
                    </td>
                    <td>
                        @php
                            $map = [
                                'pending'    => ['warning',   'Aguardando'],
                                'processing' => ['info',      'Processando'],
                                'paid'       => ['success',   'Pago'],
                                'shipped'    => ['primary',   'Enviado'],
                                'delivered'  => ['success',   'Entregue'],
                                'cancelled'  => ['danger',    'Cancelado'],
                                'refunded'   => ['secondary', 'Reembolsado'],
                            ];
                            [$color, $label] = $map[$order->status] ?? ['secondary', $order->status];
                        @endphp
                        <span class="badge text-bg-{{ $color }}">{{ $label }}</span>
                    </td>
                    <td>
                        @php $pm = ['stripe' => '💳 Cartão', 'mercadopago' => '🛒 MP', 'pix' => '⚡ PIX']; @endphp
                        <span class="text-muted small">{{ $pm[$order->payment_method] ?? $order->payment_method }}</span>
                    </td>
                    <td class="fw-semibold">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                    <td class="text-muted small">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-muted small">{{ $order->tracking_code ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-cart3 d-block fs-2 mb-2"></i>
                        Nenhum pedido encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="card-footer bg-white">{{ $orders->links() }}</div>
    @endif
</div>
@endsection

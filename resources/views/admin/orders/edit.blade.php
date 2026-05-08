@extends('admin.layouts.app')
@section('title', 'Pedido #' . $order->order_number)

@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-semibold">Pedido #{{ $order->order_number }}</h5>
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
        [$statusColor, $statusLabel] = $map[$order->status] ?? ['secondary', $order->status];
    @endphp
    <span class="badge text-bg-{{ $statusColor }}">{{ $statusLabel }}</span>
</div>

{{-- Quick action buttons --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    @if(in_array($order->status, ['pending', 'processing']))
        <form action="{{ route('admin.orders.mark-paid', $order) }}" method="POST"
              onsubmit="return confirm('Marcar pedido como pago manualmente?')">
            @csrf
            <button type="submit" class="btn btn-sm btn-success">
                <i class="bi bi-check-circle me-1"></i> Marcar como Pago
            </button>
        </form>
    @endif

    @if($order->status === 'paid')
        <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#shipModal">
            <i class="bi bi-truck me-1"></i> Marcar como Enviado
        </button>
    @endif

    @if($order->status === 'shipped')
        <form action="{{ route('admin.orders.mark-delivered', $order) }}" method="POST"
              onsubmit="return confirm('Marcar como entregue?')">
            @csrf
            <button type="submit" class="btn btn-sm btn-success">
                <i class="bi bi-house-check me-1"></i> Marcar como Entregue
            </button>
        </form>
    @endif

    @if(!in_array($order->status, ['delivered', 'cancelled', 'refunded']))
        <form action="{{ route('admin.orders.cancel', $order) }}" method="POST"
              onsubmit="return confirm('Cancelar este pedido? Esta ação não pode ser desfeita.')">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-x-circle me-1"></i> Cancelar Pedido
            </button>
        </form>
    @endif
</div>

<div class="row g-3">
    {{-- Status & Tracking form --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold py-3">Atualizar Status</div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['pending'=>'Aguardando','processing'=>'Em Processamento','paid'=>'Pago','shipped'=>'Enviado','delivered'=>'Entregue','cancelled'=>'Cancelado','refunded'=>'Reembolsado'] as $val => $lbl)
                                <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Código de Rastreio</label>
                        <input type="text" name="tracking_code" class="form-control"
                               value="{{ $order->tracking_code }}" placeholder="AA000000000BR">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observações Internas</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $order->notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-lg me-1"></i> Salvar
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold py-3">Financeiro</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted">Subtotal</td><td class="text-end">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td></tr>
                    <tr><td class="text-muted">Desconto</td><td class="text-end text-danger">- R$ {{ number_format($order->discount, 2, ',', '.') }}</td></tr>
                    <tr><td class="text-muted">Frete</td><td class="text-end">R$ {{ number_format($order->shipping, 2, ',', '.') }}</td></tr>
                    <tr class="fw-bold"><td>Total</td><td class="text-end">R$ {{ number_format($order->total, 2, ',', '.') }}</td></tr>
                </table>
                <hr class="my-2">
                <div class="small text-muted">
                    <div>Método: @php $pm = ['stripe'=>'💳 Cartão','mercadopago'=>'🛒 MercadoPago','pix'=>'⚡ PIX']; @endphp
                        {{ $pm[$order->payment_method] ?? $order->payment_method }}</div>
                    <div>Status do pagamento: {{ $order->payment_status }}</div>
                    @if($order->coupon_code)
                        <div>Cupom: <strong>{{ $order->coupon_code }}</strong></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Customer, address, items --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold py-3">Cliente</div>
            <div class="card-body row g-2 small">
                <div class="col-6"><span class="text-muted">Nome</span><div>{{ $order->user?->name ?? '—' }}</div></div>
                <div class="col-6"><span class="text-muted">Email</span><div>{{ $order->user?->email ?? '—' }}</div></div>
                <div class="col-6"><span class="text-muted">Telefone</span><div>{{ $order->billing_address['phone'] ?? '—' }}</div></div>
                <div class="col-6"><span class="text-muted">CPF</span><div>{{ $order->billing_address['cpf'] ?? '—' }}</div></div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold py-3">Endereço de Entrega</div>
            <div class="card-body small">
                @php $addr = $order->shipping_address ?? []; @endphp
                <div>{{ ($addr['street'] ?? '') . ', ' . ($addr['number'] ?? '') }}</div>
                <div>{{ $addr['neighborhood'] ?? '' }}</div>
                <div>{{ ($addr['city'] ?? '') . ' — ' . ($addr['state'] ?? '') . ' — CEP: ' . ($addr['cep'] ?? '') }}</div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold py-3">Itens do Pedido</div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th>Tam.</th>
                            <th>Cor</th>
                            <th>Qtd</th>
                            <th class="text-end">Unit.</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="fw-semibold small">{{ $item->product_name }}</div>
                                <div class="text-muted" style="font-size:.7rem">SKU: {{ $item->product_sku }}</div>
                            </td>
                            <td class="small">{{ $item->size ?? '—' }}</td>
                            <td class="small">{{ $item->color ?? '—' }}</td>
                            <td class="small">{{ $item->quantity }}</td>
                            <td class="small text-end">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                            <td class="small text-end fw-semibold">R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Ship modal --}}
@if($order->status === 'paid')
<div class="modal fade" id="shipModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.orders.mark-shipped', $order) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h6 class="modal-title">Marcar como Enviado</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Código de Rastreio (Correios) <span class="text-danger">*</span></label>
                <input type="text" name="tracking_code" class="form-control"
                       placeholder="AA000000000BR" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-info text-white btn-sm">
                    <i class="bi bi-truck me-1"></i> Confirmar Envio
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

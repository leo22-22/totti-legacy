@extends('layouts.app')
@section('title', 'Pedido {{ $order->order_number }} — Totti Legacy')
@section('content')
<section style="max-width: 800px; margin: 0 auto; padding: 4rem 2rem;">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
        <a href="{{ route('account.orders') }}" style="color: var(--gray); text-decoration: none; font-size: 0.85rem;">← Meus Pedidos</a>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 3rem;">
        <div>
            <h1 class="font-serif" style="font-size: 2rem; font-weight: 400;">{{ $order->order_number }}</h1>
            <p style="color: var(--gray); font-size: 0.85rem; margin-top: 0.3rem;">Realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}</p>
        </div>
        <span style="font-size: 0.8rem; padding: 0.5rem 1.2rem; background: {{ $order->status === 'delivered' ? '#1a472a' : ($order->status === 'cancelled' ? '#4a1515' : '#1a1a2e') }}; color: {{ $order->status === 'delivered' ? '#a3e9b4' : ($order->status === 'cancelled' ? '#fca5a5' : '#a0aec0') }}; border-radius: 20px;">{{ $order->status_label }}</span>
    </div>

    @if($order->tracking_code)
    <div style="background: #f0fff4; border: 1px solid #9ae6b4; padding: 1rem 1.5rem; border-radius: 4px; margin-bottom: 2rem; font-size: 0.9rem;">
        📦 Seu pedido foi enviado! Código de rastreio: <strong>{{ $order->tracking_code }}</strong>
        <a href="https://rastreamento.correios.com.br/app/index.php" target="_blank" rel="noopener" style="margin-left: 1rem; color: #276749; font-size: 0.8rem;">Rastrear nos Correios →</a>
    </div>
    @endif

    {{-- Itens --}}
    <div style="margin-bottom: 2.5rem;">
        <h2 style="font-size: 0.75rem; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 700; margin-bottom: 1.2rem; padding-bottom: 0.8rem; border-bottom: 1px solid rgba(0,0,0,0.1);">Itens do Pedido</h2>
        @foreach($order->items as $item)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0; border-bottom: 1px solid rgba(0,0,0,0.06);">
            <div>
                <div style="font-weight: 600; font-size: 0.9rem;">{{ $item->product_name }}</div>
                <div style="color: var(--gray); font-size: 0.8rem;">Tam: {{ $item->size }} | Cor: {{ $item->color }} | Qtd: {{ $item->quantity }}</div>
            </div>
            <div style="font-weight: 600;">R$ {{ number_format($item->total_price, 2, ',', '.') }}</div>
        </div>
        @endforeach
    </div>

    {{-- Totais --}}
    <div style="background: #f9f9f9; padding: 1.5rem; margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.6rem; font-size: 0.9rem;">
            <span>Subtotal</span><span>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
        </div>
        @if($order->discount > 0)
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.6rem; font-size: 0.9rem; color: #276749;">
            <span>Desconto @if($order->coupon_code)({{ $order->coupon_code }})@endif</span>
            <span>− R$ {{ number_format($order->discount, 2, ',', '.') }}</span>
        </div>
        @endif
        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.9rem;">
            <span>Frete</span><span>{{ $order->shipping > 0 ? 'R$ ' . number_format($order->shipping, 2, ',', '.') : 'Grátis' }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.1rem; padding-top: 0.8rem; border-top: 1px solid rgba(0,0,0,0.1);">
            <span>Total</span><span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
        </div>
    </div>

    {{-- Endereço --}}
    @if($order->shipping_address)
    <div>
        <h2 style="font-size: 0.75rem; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 700; margin-bottom: 1rem; padding-bottom: 0.8rem; border-bottom: 1px solid rgba(0,0,0,0.1);">Endereço de Entrega</h2>
        <p style="font-size: 0.9rem; line-height: 1.8; color: var(--gray);">
            {{ $order->shipping_address['street'] ?? '' }}, {{ $order->shipping_address['number'] ?? '' }}<br>
            {{ $order->shipping_address['neighborhood'] ?? '' }} — {{ $order->shipping_address['city'] ?? '' }}/{{ $order->shipping_address['state'] ?? '' }}<br>
            CEP: {{ $order->shipping_address['cep'] ?? '' }}
        </p>
    </div>
    @endif
</section>
@endsection

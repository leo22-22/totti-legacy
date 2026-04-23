@extends('layouts.app')
@section('title', 'Meus Pedidos — Totti Legacy')
@section('content')
<section style="max-width: 900px; margin: 0 auto; padding: 4rem 2rem;">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 3rem;">
        <a href="{{ route('account.index') }}" style="color: var(--gray); text-decoration: none; font-size: 0.85rem;">← Minha Conta</a>
        <h1 class="font-serif" style="font-size: 2.5rem; font-weight: 400;">Meus Pedidos</h1>
    </div>

    @forelse($orders as $order)
    <div style="border: 1px solid rgba(0,0,0,0.08); padding: 1.5rem; margin-bottom: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
            <div>
                <div style="font-weight: 700; font-size: 0.95rem;">{{ $order->order_number }}</div>
                <div style="color: var(--gray); font-size: 0.82rem; margin-top: 0.2rem;">{{ $order->created_at->format('d/m/Y \à\s H:i') }}</div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 700; font-size: 1.05rem;">R$ {{ number_format($order->total, 2, ',', '.') }}</div>
                <span style="font-size: 0.72rem; padding: 0.3rem 0.8rem; background: {{ $order->status === 'delivered' ? '#1a472a' : ($order->status === 'cancelled' ? '#4a1515' : '#1a1a2e') }}; color: {{ $order->status === 'delivered' ? '#a3e9b4' : ($order->status === 'cancelled' ? '#fca5a5' : '#a0aec0') }}; border-radius: 20px; display: inline-block; margin-top: 0.3rem;">{{ $order->status_label }}</span>
            </div>
        </div>
        @if($order->tracking_code)
        <div style="background: #f9f9f9; padding: 0.6rem 1rem; border-radius: 4px; font-size: 0.82rem; margin-bottom: 1rem;">
            📦 Código de rastreio: <strong>{{ $order->tracking_code }}</strong>
        </div>
        @endif
        <a href="{{ route('account.order', $order->order_number) }}" class="btn btn-outline-dark" style="font-size: 0.75rem; padding: 0.6rem 1.5rem;">Ver detalhes</a>
    </div>
    @empty
    <div style="text-align: center; padding: 4rem; color: var(--gray);">
        <p style="font-size: 1.1rem; margin-bottom: 1.5rem;">Você ainda não fez nenhum pedido.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-dark">Ver Camisas</a>
    </div>
    @endforelse

    <div style="margin-top: 2rem;">{{ $orders->links() }}</div>
</section>
@endsection

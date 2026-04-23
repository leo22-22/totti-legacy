@extends('layouts.app')
@section('title', 'Pedido Confirmado — Totti Legacy')

@section('content')
<section style="max-width: 700px; margin: 0 auto; padding: 6rem 2rem; text-align: center;">

    <!-- Success Icon -->
    <div style="width: 100px; height: 100px; border: 2px solid var(--black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; font-size: 2.5rem; color: var(--black);">
        <i class="fas fa-check"></i>
    </div>

    <p style="font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; color: var(--gray); margin-bottom: 1rem;">Pedido Confirmado</p>
    <h1 class="font-serif" style="font-size: 2.5rem; font-weight: 400; margin-bottom: 0.5rem;">Obrigado!</h1>
    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 3rem;">Seu pedido foi recebido com sucesso.</p>

    <!-- Order Details -->
    <div style="background: var(--light); padding: 2rem; margin-bottom: 2rem; text-align: left;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div>
                <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--gray); margin-bottom: 0.3rem;">Número do Pedido</p>
                <p style="font-weight: 700; font-size: 1rem;">{{ $order->order_number }}</p>
            </div>
            <div>
                <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--gray); margin-bottom: 0.3rem;">Status</p>
                <p style="font-weight: 600;">{{ $order->status_label ?? ucfirst($order->status) }}</p>
            </div>
            <div>
                <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--gray); margin-bottom: 0.3rem;">Total</p>
                <p style="font-weight: 700; font-size: 1.2rem;">R$ {{ number_format($order->total, 2, ',', '.') }}</p>
            </div>
            <div>
                <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--gray); margin-bottom: 0.3rem;">Pagamento</p>
                <p>{{ match($order->payment_method) { 'stripe' => 'Cartão de Crédito', 'pix' => 'PIX', 'mercadopago' => 'MercadoPago', default => $order->payment_method } }}</p>
            </div>
        </div>
    </div>

    <!-- PIX Payment Block -->
    @if($order->payment_method === 'pix' && !empty($paymentData['pix_qr_url']))
    <div style="background: var(--light); padding: 2rem; margin-bottom: 2rem; border: 1px solid rgba(0,0,0,0.1); text-align: center;">
        <h3 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.5rem;">Pague via PIX</h3>
        <p style="font-size: 0.85rem; color: var(--gray); margin-bottom: 1.5rem;">Escaneie o QR Code ou copie o código abaixo. O pedido é confirmado em instantes.</p>

        <img src="{{ $paymentData['pix_qr_url'] }}" alt="QR Code PIX" style="width: 200px; height: 200px; margin: 0 auto 1.5rem; display: block; border: 1px solid rgba(0,0,0,0.08);">

        <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--gray); margin-bottom: 0.5rem;">PIX Copia e Cola</p>
        <div style="position: relative; display: flex; align-items: center; gap: 0.5rem; background: white; border: 1px solid rgba(0,0,0,0.12); padding: 0.75rem 1rem; margin-bottom: 1rem;">
            <input id="pixPayload" type="text" readonly value="{{ $paymentData['pix_payload'] }}"
                style="flex: 1; border: none; font-size: 0.75rem; font-family: monospace; color: var(--gray); background: transparent; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
            <button onclick="copyPix()" id="copyBtn"
                style="flex-shrink: 0; background: var(--black); color: var(--white); border: none; padding: 0.5rem 1rem; font-family: 'Montserrat'; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer;">
                Copiar
            </button>
        </div>

        <p style="font-size: 0.8rem; color: var(--gray);">
            Valor: <strong>R$ {{ number_format($order->total, 2, ',', '.') }}</strong>
            &nbsp;·&nbsp; Pedido: <strong>{{ $order->order_number }}</strong>
        </p>
    </div>
    @elseif($order->payment_method === 'pix')
    <div style="background: var(--light); padding: 2rem; margin-bottom: 2rem; border: 1px solid rgba(0,0,0,0.1); text-align: center;">
        <h3 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.5rem;">Pagamento PIX</h3>
        <p style="font-size: 0.85rem; color: var(--gray);">Entre em contato via WhatsApp para receber os dados de pagamento.</p>
    </div>
    @endif

    <!-- Items -->
    <div style="text-align: left; margin-bottom: 2.5rem;">
        <h3 style="font-size: 0.75rem; letter-spacing: 0.15em; text-transform: uppercase; font-weight: 700; margin-bottom: 1rem;">Itens do Pedido</h3>
        @foreach($order->items as $item)
        <div style="display: flex; justify-content: space-between; padding: 0.8rem 0; border-bottom: 1px solid rgba(0,0,0,0.06); font-size: 0.85rem;">
            <span>{{ $item->product_name }} <span style="color: var(--gray);">× {{ $item->quantity }}</span>
                <small style="color: var(--gray); display: block;">{{ $item->size }} • {{ $item->color }}</small>
            </span>
            <span style="font-weight: 600;">R$ {{ number_format($item->total_price, 2, ',', '.') }}</span>
        </div>
        @endforeach

        <div style="display: flex; justify-content: space-between; padding: 1rem 0 0; font-size: 1rem; font-weight: 700;">
            <span>Total</span>
            <span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
        </div>
    </div>

    <p style="font-size: 0.85rem; color: var(--gray); margin-bottom: 2rem;">
        Você receberá um e-mail de confirmação em breve.<br>
        Dúvidas? Entre em contato pelo <a href="https://wa.me/5511999999999" style="color: var(--black); font-weight: 600;">WhatsApp</a>.
    </p>

    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('account.orders') }}" class="btn btn-dark">Meus Pedidos</a>
        <a href="{{ route('shop.index') }}" class="btn btn-outline-dark">Continuar Comprando</a>
    </div>
</section>

@push('scripts')
<script>
function copyPix() {
    const input = document.getElementById('pixPayload');
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.textContent = 'Copiado!';
        setTimeout(() => btn.textContent = 'Copiar', 2000);
    });
}
</script>
@endpush
@endsection

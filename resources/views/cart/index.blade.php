@extends('layouts.app')
@section('title', 'Carrinho — Totti Legacy')

@section('content')
<section style="max-width: 1200px; margin: 0 auto; padding: 4rem 2rem;">
    <h1 class="font-serif" style="font-size: 2.5rem; font-weight: 400; margin-bottom: 3rem;">
        Seu Carrinho
        @if(count($cart) > 0)
            <span style="font-size: 1rem; color: var(--gray); font-family: 'Montserrat'; font-weight: 400;">({{ array_sum(array_column($cart, 'quantity')) }} item(s))</span>
        @endif
    </h1>

    @if(empty($cart))
        <div style="text-align: center; padding: 5rem 0;">
            <i class="fas fa-shopping-bag" style="font-size: 4rem; color: rgba(0,0,0,0.1); display: block; margin-bottom: 1.5rem;"></i>
            <h2 class="font-serif" style="font-size: 1.8rem; margin-bottom: 1rem; font-weight: 400;">Seu carrinho está vazio</h2>
            <p style="color: var(--gray); margin-bottom: 2rem;">Adicione produtos para continuar comprando.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-gold">Explorar a Loja</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 3rem; align-items: start;">
            <!-- CART ITEMS -->
            <div>
                @foreach($cart as $item)
                <div style="display: flex; gap: 1.5rem; padding: 1.5rem 0; border-bottom: 1px solid rgba(0,0,0,0.08);">
                    <!-- Image -->
                    <div style="width: 120px; height: 150px; flex-shrink: 0; overflow: hidden; background: var(--light);">
                        <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/placeholder.jpg') }}" alt="{{ $item['name'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>

                    <!-- Info -->
                    <div style="flex: 1;">
                        <h3 class="font-serif" style="font-size: 1.3rem; font-weight: 500; margin-bottom: 0.4rem;">
                            <a href="{{ route('shop.show', $item['slug']) }}" style="text-decoration: none; color: var(--black);">{{ $item['name'] }}</a>
                        </h3>
                        <p style="font-size: 0.78rem; color: var(--gray); margin-bottom: 0.2rem;">Tamanho: <strong>{{ $item['size'] }}</strong></p>
                        <p style="font-size: 0.78rem; color: var(--gray); margin-bottom: 1rem;">Cor: <strong>{{ $item['color'] }}</strong></p>

                        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
                            <!-- Qty -->
                            <form method="POST" action="{{ route('cart.update') }}" style="display: flex; align-items: center; border: 1px solid rgba(0,0,0,0.15);">
                                @csrf
                                <input type="hidden" name="key" value="{{ $item['key'] }}">
                                <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" style="width: 36px; height: 36px; background: none; border: none; cursor: pointer; font-size: 1rem;">−</button>
                                <span style="width: 40px; text-align: center; font-weight: 600;">{{ $item['quantity'] }}</span>
                                <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" style="width: 36px; height: 36px; background: none; border: none; cursor: pointer; font-size: 1rem;">+</button>
                            </form>

                            <!-- Remove -->
                            <form method="POST" action="{{ route('cart.remove') }}">
                                @csrf
                                <input type="hidden" name="key" value="{{ $item['key'] }}">
                                <button type="submit" style="background: none; border: none; font-size: 0.75rem; color: var(--gray); cursor: pointer; text-decoration: underline; font-family: 'Montserrat';">Remover</button>
                            </form>
                        </div>
                    </div>

                    <!-- Price -->
                    <div style="text-align: right; flex-shrink: 0;">
                        <p style="font-weight: 700; font-size: 1.1rem;">R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</p>
                        @if($item['quantity'] > 1)
                        <p style="font-size: 0.78rem; color: var(--gray);">R$ {{ number_format($item['price'], 2, ',', '.') }} cada</p>
                        @endif
                    </div>
                </div>
                @endforeach

                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('shop.index') }}" style="font-size: 0.8rem; color: var(--gray); text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Continuar comprando
                    </a>
                </div>
            </div>

            <!-- ORDER SUMMARY -->
            <div style="background: var(--light); padding: 2rem; position: sticky; top: 90px;">
                <h3 style="font-size: 0.8rem; letter-spacing: 0.15em; text-transform: uppercase; font-weight: 700; margin-bottom: 1.5rem;">Resumo do Pedido</h3>

                <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem; font-size: 0.9rem;">
                    <span style="color: var(--gray);">Subtotal</span>
                    <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    <span style="color: var(--gray);">Frete</span>
                    <span style="color: #27ae60;">{{ $subtotal >= 299 ? 'Grátis' : 'R$ 19,90' }}</span>
                </div>

                @if($subtotal < 299)
                <div style="background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.3); padding: 0.8rem; margin-bottom: 1.5rem; font-size: 0.78rem; color: var(--gray);">
                    <i class="fas fa-info-circle" style="color: var(--gold);"></i>
                    Faltam <strong>R$ {{ number_format(299 - $subtotal, 2, ',', '.') }}</strong> para frete grátis!
                </div>
                @endif

                <div style="border-top: 1px solid rgba(0,0,0,0.1); padding-top: 1rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 700;">
                        <span>Total</span>
                        <span>R$ {{ number_format($subtotal + ($subtotal >= 299 ? 0 : 19.90), 2, ',', '.') }}</span>
                    </div>
                    <p style="font-size: 0.75rem; color: var(--gray); margin-top: 0.3rem;">ou 12x de R$ {{ number_format(($subtotal + ($subtotal >= 299 ? 0 : 19.90)) / 12, 2, ',', '.') }} sem juros</p>
                </div>

                <a href="{{ route('checkout.index') }}" class="btn btn-gold btn-full" style="margin-bottom: 0.8rem;">
                    <i class="fas fa-lock"></i> Finalizar Compra
                </a>
                <div style="text-align: center; margin-top: 1rem;">
                    <i class="fab fa-cc-visa" style="font-size: 1.5rem; color: var(--gray); margin: 0 0.3rem;"></i>
                    <i class="fab fa-cc-mastercard" style="font-size: 1.5rem; color: var(--gray); margin: 0 0.3rem;"></i>
                    <i class="fab fa-pix" style="font-size: 1.5rem; color: var(--gray); margin: 0 0.3rem;"></i>
                </div>
            </div>
        </div>
    @endif
</section>
@endsection

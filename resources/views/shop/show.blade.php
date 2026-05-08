@extends('layouts.app')
@section('title', $product->name . ' — Totti Legacy')
@section('meta_description', $product->short_description ?? $product->name)

@section('content')
<section style="max-width: 1400px; margin: 0 auto; padding: 4rem 2rem;">
    <!-- Breadcrumb -->
    <nav style="margin-bottom: 2rem; font-size: 0.75rem; color: var(--gray);">
        <a href="{{ route('home') }}" style="color: var(--gray); text-decoration: none;">Home</a>
        <span style="margin: 0 0.5rem;">›</span>
        <a href="{{ route('shop.index') }}" style="color: var(--gray); text-decoration: none;">Loja</a>
        @if($product->category)
        <span style="margin: 0 0.5rem;">›</span>
        <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" style="color: var(--gray); text-decoration: none;">{{ $product->category->name }}</a>
        @endif
        <span style="margin: 0 0.5rem;">›</span>
        <span style="color: var(--black);">{{ $product->name }}</span>
    </nav>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: start;">

        <!-- GALLERY -->
        <div>
            <div id="mainImage" style="aspect-ratio: 3/4; overflow: hidden; background: var(--light); margin-bottom: 1rem;">
                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" id="mainImg" style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.3s;">
            </div>
            @if($product->images && count($product->images) > 0)
            <div style="display: flex; gap: 0.8rem; overflow-x: auto;">
                <button onclick="changeImage('{{ $product->main_image_url }}')" style="width: 80px; height: 80px; flex-shrink: 0; overflow: hidden; border: 2px solid var(--gold); background: none; cursor: pointer; padding: 0;">
                    <img src="{{ $product->main_image_url }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                </button>
                @foreach($product->images as $img)
                <button onclick="changeImage('{{ asset('storage/' . $img) }}')" style="width: 80px; height: 80px; flex-shrink: 0; overflow: hidden; border: 2px solid transparent; background: none; cursor: pointer; padding: 0; transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='transparent'">
                    <img src="{{ asset('storage/' . $img) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- PRODUCT INFO -->
        <div style="position: sticky; top: 90px;">
            @if($product->category)
                <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" style="font-size: 0.65rem; letter-spacing: 0.2em; text-transform: uppercase; color: var(--gold); text-decoration: none; display: block; margin-bottom: 0.8rem;">{{ $product->category->name }}</a>
            @endif

            <h1 class="font-serif" style="font-size: 2.5rem; font-weight: 500; margin-bottom: 1.5rem; line-height: 1.2;">{{ $product->name }}</h1>

            <!-- Price -->
            <div style="margin-bottom: 2rem;">
                @if($product->is_on_sale)
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span style="font-size: 2rem; font-weight: 700; color: #c0392b;">R$ {{ number_format($product->sale_price, 2, ',', '.') }}</span>
                        <span style="font-size: 1.1rem; color: var(--gray); text-decoration: line-through;">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                        <span style="background: #c0392b; color: white; padding: 0.2rem 0.6rem; font-size: 0.7rem; font-weight: 700;">-{{ $product->discount_percentage }}%</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--gray); margin-top: 0.3rem;">ou 12x de R$ {{ number_format($product->current_price / 12, 2, ',', '.') }} sem juros</p>
                @else
                    <span style="font-size: 2rem; font-weight: 700;">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                    <p style="font-size: 0.8rem; color: var(--gray); margin-top: 0.3rem;">ou 12x de R$ {{ number_format($product->price / 12, 2, ',', '.') }} sem juros</p>
                @endif
                <p style="font-size: 0.78rem; color: #27ae60; margin-top: 0.2rem;"><i class="fas fa-tag"></i> 5% de desconto no Pix</p>
            </div>

            @if($product->sale_ends_at && $product->sale_ends_at->isFuture() && $product->is_on_sale)
            <div class="countdown-bar" id="productCountdown" data-ends="{{ $product->sale_ends_at->timestamp }}" style="margin-bottom: 1.5rem; padding: .5rem 1rem; border-radius: 4px;">
                <span class="countdown-label">Oferta termina em</span>
                <span class="countdown-timer"></span>
            </div>
            @endif

            @if($product->short_description)
            <p style="font-size: 0.9rem; color: var(--gray); line-height: 1.8; margin-bottom: 2rem;">{{ $product->short_description }}</p>
            @endif

            <form method="POST" action="{{ route('cart.add') }}" id="addToCart">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- Sizes -->
                @if($product->sizes && count($product->sizes) > 0)
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem;">
                        <label style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700;">Tamanho <span id="selectedSize" style="color: var(--gold);"></span></label>
                        <button type="button" onclick="openSizeGuide()" style="font-size: 0.7rem; color: var(--gray); background: none; border: none; padding: 0; cursor: pointer; text-decoration: underline; text-underline-offset: 2px;">Guia de tamanhos →</button>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach($product->sizes as $size)
                        <label style="cursor: pointer;">
                            <input type="radio" name="size" value="{{ $size }}" style="display: none;" required onchange="document.getElementById('selectedSize').textContent = '— ' + this.value">
                            <span class="size-opt" data-size="{{ $size }}" style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border: 1px solid rgba(0,0,0,0.2); font-size: 0.8rem; font-weight: 600; transition: all 0.2s; cursor: pointer;">
                                {{ $size }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Colors -->
                @if($product->colors && count($product->colors) > 0)
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; display: block; margin-bottom: 0.8rem;">Cor <span id="selectedColor" style="color: var(--gold);"></span></label>
                    <div style="display: flex; gap: 0.6rem;">
                        @foreach($product->colors as $color)
                        <label style="cursor: pointer;" title="{{ $color['name'] }}">
                            <input type="radio" name="color" value="{{ $color['name'] }}" style="display: none;" onchange="document.getElementById('selectedColor').textContent = '— ' + this.value">
                            <div class="color-opt" style="width: 36px; height: 36px; border-radius: 50%; background: {{ $color['hex'] ?? '#ccc' }}; border: 2px solid transparent; transition: all 0.2s; cursor: pointer;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='transparent'"></div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @else
                    <input type="hidden" name="color" value="Único">
                @endif

                <!-- Quantity -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; display: block; margin-bottom: 0.8rem;">Quantidade</label>
                    <div style="display: flex; align-items: center; border: 1px solid rgba(0,0,0,0.2); width: fit-content;">
                        <button type="button" onclick="changeQty(-1)" style="width: 44px; height: 44px; background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--black);">−</button>
                        <input type="number" name="quantity" id="qty" value="1" min="1" max="10" style="width: 60px; text-align: center; border: none; font-family: 'Montserrat'; font-size: 1rem; font-weight: 600; height: 44px;">
                        <button type="button" onclick="changeQty(1)" style="width: 44px; height: 44px; background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--black);">+</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-gold btn-full" style="margin-bottom: 0.8rem; padding: 1rem;">
                    <i class="fas fa-shopping-bag"></i> Adicionar ao Carrinho
                </button>
                <a href="{{ route('checkout.index') }}" class="btn btn-dark btn-full" style="padding: 1rem; justify-content: center;">
                    <i class="fas fa-bolt"></i> Comprar Agora
                </a>
            </form>

            <!-- Trust -->
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(0,0,0,0.08);">
                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.75rem; color: var(--gray);"><i class="fas fa-truck" style="color: var(--gold);"></i> Frete Grátis acima de R$299</span>
                    <span style="font-size: 0.75rem; color: var(--gray);"><i class="fas fa-exchange-alt" style="color: var(--gold);"></i> Troca gratuita em 30 dias</span>
                    <span style="font-size: 0.75rem; color: var(--gray);"><i class="fas fa-shield-alt" style="color: var(--gold);"></i> Pagamento seguro</span>
                </div>
            </div>

            <!-- Composition -->
            @if($product->composition)
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(0,0,0,0.08);">
                <details>
                    <summary style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; cursor: pointer; margin-bottom: 0.8rem;">Composição</summary>
                    <ul style="font-size: 0.85rem; color: var(--gray); margin-top: 0.8rem;">
                        @foreach($product->composition as $material => $perc)
                        <li style="margin-bottom: 0.4rem;">{{ $material }}: {{ $perc }}</li>
                        @endforeach
                    </ul>
                </details>
            </div>
            @endif
        </div>
    </div>

    <!-- DESCRIPTION -->
    @if($product->description)
    <div style="margin-top: 5rem; padding-top: 3rem; border-top: 1px solid rgba(0,0,0,0.08); max-width: 800px;">
        <h2 class="font-serif" style="font-size: 1.8rem; margin-bottom: 1.5rem;">Descrição</h2>
        <div style="font-size: 0.9rem; line-height: 1.9; color: var(--gray);">{!! $product->description !!}</div>
    </div>
    @endif

    <!-- RELATED -->
    @if($related->count())
    <div style="margin-top: 5rem; padding-top: 3rem; border-top: 1px solid rgba(0,0,0,0.08);">
        <h2 class="font-serif" style="font-size: 2rem; margin-bottom: 2rem;">Você também pode gostar</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem;">
            @foreach($related as $p)
                @include('partials.product-card', ['product' => $p])
            @endforeach
        </div>
    </div>
    @endif
</section>

@push('scripts')
<script>
// Init product countdown if present
document.addEventListener('DOMContentLoaded', function () {
    var bar = document.getElementById('productCountdown');
    if (bar) startCountdown(bar.querySelector('.countdown-timer'), parseInt(bar.dataset.ends));
});

function changeQty(delta) {
    const qty = document.getElementById('qty');
    qty.value = Math.max(1, Math.min(10, parseInt(qty.value) + delta));
}
function changeImage(url) {
    const img = document.getElementById('mainImg');
    img.style.opacity = '0';
    setTimeout(() => { img.src = url; img.style.opacity = '1'; }, 150);
}
document.querySelectorAll('.size-opt').forEach(el => {
    el.addEventListener('click', function() {
        document.querySelectorAll('.size-opt').forEach(s => {
            s.style.background = '';
            s.style.color = '';
            s.style.borderColor = 'rgba(0,0,0,0.2)';
        });
        this.style.background = 'var(--black)';
        this.style.color = 'var(--white)';
        this.style.borderColor = 'var(--black)';
    });
});
document.querySelectorAll('.color-opt').forEach(el => {
    el.addEventListener('click', function() {
        document.querySelectorAll('.color-opt').forEach(c => c.style.borderColor = 'transparent');
        this.style.borderColor = 'var(--gold)';
    });
});
</script>
@endpush
@endsection

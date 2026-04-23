<div class="product-card" style="{{ ($dark ?? false) ? 'background: #1A1A1A;' : '' }}">
    <div class="product-card-img">
        <a href="{{ route('shop.show', $product->slug) }}">
            <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" loading="lazy">
        </a>

        @if($product->is_on_sale)
            <span class="product-badge badge-sale">-{{ $product->discount_percentage }}%</span>
        @elseif($product->is_new)
            <span class="product-badge badge-new">Novo</span>
        @endif

        <div class="product-card-actions">
            <form method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="size" value="{{ $product->sizes[0] ?? 'M' }}">
                <input type="hidden" name="color" value="{{ $product->colors[0]['name'] ?? 'Único' }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-gold btn-full" style="padding: 0.7rem 1rem; font-size: 0.7rem;">
                    <i class="fas fa-shopping-bag"></i> Adicionar ao Carrinho
                </button>
            </form>
        </div>
    </div>

    <div class="product-card-body">
        @if($product->category)
            <p class="product-card-category">{{ $product->category->name }}</p>
        @endif
        <a href="{{ route('shop.show', $product->slug) }}" class="product-card-name" style="{{ ($dark ?? false) ? 'color: #fff;' : '' }}">
            {{ $product->name }}
        </a>
        <div class="product-card-price">
            @if($product->is_on_sale)
                <span class="price-current price-sale">R$ {{ number_format($product->sale_price, 2, ',', '.') }}</span>
                <span class="price-original">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
            @else
                <span class="price-current" style="{{ ($dark ?? false) ? 'color: #fff;' : '' }}">
                    R$ {{ number_format($product->price, 2, ',', '.') }}
                </span>
            @endif
        </div>
    </div>
</div>

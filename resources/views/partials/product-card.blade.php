@php
    $hasCountdown = $product->sale_ends_at && $product->sale_ends_at->isFuture() && $product->is_on_sale;
    $priceHtml = $product->is_on_sale
        ? '<span class="price-current price-sale">R$ ' . number_format($product->sale_price, 2, ',', '.') . '</span> <span class="price-original">R$ ' . number_format($product->price, 2, ',', '.') . '</span>'
        : '<span class="price-current">R$ ' . number_format($product->price, 2, ',', '.') . '</span>';
    $sizes  = collect($product->sizes ?? [])->filter()->values()->toArray();
    $colors = collect($product->colors ?? [])->pluck('name')->filter()->values()->toArray();
@endphp

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
            <button type="button" class="btn btn-gold btn-full"
                    style="padding: 0.7rem 1rem; font-size: 0.7rem;"
                    onclick='openQuickBuy(@json([
                        "id"         => $product->id,
                        "name"       => $product->name,
                        "url"        => route("shop.show", $product->slug),
                        "price_html" => $priceHtml,
                        "img"        => $product->main_image_url,
                        "sizes"      => $sizes,
                        "colors"     => $colors,
                    ]))'>
                <i class="fas fa-shopping-bag"></i> Comprar Rápido
            </button>
        </div>
    </div>

    @if($hasCountdown)
        <div class="countdown-bar product-countdown" data-ends="{{ $product->sale_ends_at->timestamp }}">
            <span class="countdown-label">Oferta termina em</span>
            <span class="countdown-timer"></span>
        </div>
    @endif

    <div class="product-card-body">
        @if($product->category)
            <p class="product-card-category">{{ $product->category->name }}</p>
        @endif
        <a href="{{ route('shop.show', $product->slug) }}" class="product-card-name"
           style="{{ ($dark ?? false) ? 'color: #fff;' : '' }}">
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

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.product-countdown[data-ends]').forEach(function (bar) {
        startCountdown(bar.querySelector('.countdown-timer'), parseInt(bar.dataset.ends));
    });
});
</script>
@endpush
@endonce

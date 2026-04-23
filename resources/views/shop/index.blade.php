@extends('layouts.app')
@section('title', 'Loja — Totti Legacy')

@section('content')
<!-- SHOP HEADER -->
<section style="background: var(--black); padding: 5rem 2rem 3rem; text-align: center;">
    <p style="font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; color: var(--gold); margin-bottom: 0.8rem;">Coleção</p>
    <h1 class="font-serif" style="font-size: 3rem; color: var(--white); font-weight: 300;">Nossa Loja</h1>
</section>

<section style="max-width: 1400px; margin: 0 auto; padding: 3rem 2rem;">
    <div style="display: flex; gap: 3rem; align-items: flex-start;">

        <!-- SIDEBAR FILTERS -->
        <aside style="width: 240px; flex-shrink: 0; position: sticky; top: 90px;">
            <h3 style="font-size: 0.7rem; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 700; margin-bottom: 1.5rem; padding-bottom: 0.8rem; border-bottom: 1px solid rgba(0,0,0,0.1);">Filtros</h3>

            <form method="GET" action="{{ route('shop.index') }}" id="filterForm">
                <!-- Search -->
                <div style="margin-bottom: 2rem;">
                    <label style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; display: block; margin-bottom: 0.8rem;">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome do produto..." style="width: 100%; border: 1px solid rgba(0,0,0,0.15); padding: 0.6rem 0.8rem; font-size: 0.85rem; font-family: 'Montserrat', sans-serif;">
                </div>

                <!-- Categories -->
                @if($categories->count())
                <div style="margin-bottom: 2rem;">
                    <label style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; display: block; margin-bottom: 0.8rem;">Categoria</label>
                    <ul style="list-style: none;">
                        <li style="margin-bottom: 0.6rem;">
                            <a href="{{ route('shop.index', array_diff_key(request()->all(), ['category' => '', 'team' => ''])) }}"
                               style="font-size: 0.82rem; color: {{ !request('category') ? 'var(--black)' : 'var(--gray)' }}; text-decoration: none; font-weight: {{ !request('category') ? '700' : '400' }};">
                                Todos
                            </a>
                        </li>
                        @foreach($categories as $cat)
                        <li style="margin-bottom: 0.2rem;">
                            <a href="{{ route('shop.index', [...array_diff_key(request()->all(), ['team' => '']), 'category' => $cat->slug]) }}"
                               style="font-size: 0.82rem; color: {{ request('category') === $cat->slug ? 'var(--black)' : 'var(--gray)' }}; text-decoration: none; font-weight: {{ request('category') === $cat->slug ? '700' : '400' }}; display: flex; align-items: center; justify-content: space-between; padding: 0.35rem 0;">
                                <span>{{ $cat->name }}</span>
                                @if(request('category') === $cat->slug)
                                    <i class="fas fa-chevron-down" style="font-size: 0.6rem; color: var(--gray);"></i>
                                @endif
                            </a>

                            {{-- Subcategorias (times) da categoria ativa --}}
                            @if(request('category') === $cat->slug && $teams->count())
                            <ul style="list-style: none; padding-left: 0.8rem; margin-top: 0.3rem; border-left: 2px solid rgba(0,0,0,0.1);">
                                <li style="margin-bottom: 0.3rem;">
                                    <a href="{{ route('shop.index', array_diff_key(request()->all(), ['team' => ''])) }}"
                                       style="font-size: 0.78rem; color: {{ !request('team') ? 'var(--black)' : 'var(--gray)' }}; text-decoration: none; font-weight: {{ !request('team') ? '600' : '400' }};">
                                        Todos os times
                                    </a>
                                </li>
                                @foreach($teams as $teamItem)
                                <li style="margin-bottom: 0.3rem;">
                                    <a href="{{ route('shop.index', [...request()->all(), 'team' => $teamItem->team]) }}"
                                       style="font-size: 0.78rem; color: {{ request('team') === $teamItem->team ? 'var(--black)' : 'var(--gray)' }}; text-decoration: none; font-weight: {{ request('team') === $teamItem->team ? '600' : '400' }}; display: flex; justify-content: space-between; gap: 0.5rem;">
                                        <span>{{ $teamItem->team }}</span>
                                        <span style="font-size: 0.7rem; color: var(--gray); background: rgba(0,0,0,0.06); border-radius: 20px; padding: 0 0.4rem; line-height: 1.6;">{{ $teamItem->total_stock }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Gender -->
                <div style="margin-bottom: 2rem;">
                    <label style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; display: block; margin-bottom: 0.8rem;">Gênero</label>
                    @foreach(['masculine' => 'Masculino', 'feminine' => 'Feminino', 'unisex' => 'Unissex'] as $val => $label)
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--gray); cursor: pointer; margin-bottom: 0.5rem;">
                        <input type="radio" name="gender" value="{{ $val }}" {{ request('gender') === $val ? 'checked' : '' }} onchange="this.form.submit()">
                        {{ $label }}
                    </label>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-dark btn-full" style="font-size: 0.7rem; padding: 0.7rem;">Aplicar Filtros</button>
                @if(request()->hasAny(['search', 'category', 'gender']))
                <a href="{{ route('shop.index') }}" style="display: block; text-align: center; color: var(--gray); font-size: 0.75rem; margin-top: 0.8rem; text-decoration: none;">Limpar filtros</a>
                @endif
            </form>
        </aside>

        <!-- PRODUCTS -->
        <div style="flex: 1;">
            <!-- Sort Bar -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(0,0,0,0.08);">
                <p style="font-size: 0.8rem; color: var(--gray);">{{ $products->total() }} produto(s) encontrado(s)</p>
                <select name="sort" form="filterForm" onchange="document.getElementById('filterForm').submit()" style="border: 1px solid rgba(0,0,0,0.15); padding: 0.5rem 1rem; font-family: 'Montserrat', sans-serif; font-size: 0.8rem; cursor: pointer;">
                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Mais Recentes</option>
                    <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Destaques</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Menor Preço</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Maior Preço</option>
                </select>
            </div>

            @if($products->isEmpty())
                <div style="text-align: center; padding: 4rem 0;">
                    <i class="fas fa-search" style="font-size: 3rem; color: var(--gray); margin-bottom: 1rem; display: block;"></i>
                    <p style="color: var(--gray);">Nenhum produto encontrado. Tente outros filtros.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem;">
                    @foreach($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <!-- PAGINATION -->
                @if($products->hasPages())
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 3rem;">
                    {{ $products->onEachSide(1)->links('vendor.pagination.totti') }}
                </div>
                @endif
            @endif
        </div>
    </div>
</section>
@endsection

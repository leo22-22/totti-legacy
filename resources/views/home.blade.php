@extends('layouts.app')

@section('title', 'Totti Legacy — Moda Premium')

@section('content')

<!-- HERO -->
<section class="hero" style="height: 100vh; background: #0D0D0D; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
    @if(file_exists(public_path('images/logo.jpg')))
        <div style="position: absolute; inset: 0; background: url('{{ asset('images/logo.jpg') }}') center/cover no-repeat; opacity: 0.2;"></div>
    @endif
    <div style="position: absolute; inset: 0; background: radial-gradient(ellipse at center, rgba(255,255,255,0.03) 0%, rgba(13,13,13,0.96) 70%);"></div>
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(13,13,13,0.3) 0%, rgba(13,13,13,0.92) 100%);"></div>

    <!-- Linhas decorativas -->
    <div style="position: absolute; top: 0; left: 50%; width: 1px; height: 120px; background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.3));"></div>
    <div style="position: absolute; bottom: 0; left: 50%; width: 1px; height: 120px; background: linear-gradient(to top, transparent, rgba(255,255,255,0.3));"></div>

    <div style="text-align: center; position: relative; z-index: 1; padding: 0 2rem;">
        <p style="font-size: 0.7rem; letter-spacing: 0.5em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 1.5rem;">Nova Coleção 2025</p>
        <h1 class="font-serif" style="font-size: clamp(3rem, 8vw, 7rem); font-weight: 300; color: var(--white); line-height: 1.1; margin-bottom: 1.5rem;">
            TOTTI<br>LEGACY
        </h1>
        <p style="font-size: 0.8rem; color: rgba(255,255,255,0.5); letter-spacing: 0.2em; text-transform: uppercase; max-width: 400px; margin: 0 auto 2.5rem; line-height: 2;">
            O legado de quem nos inspira a ir além
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('shop.index') }}" class="btn btn-gold">
                <i class="fas fa-shopping-bag"></i> Explorar Coleção
            </a>
            <a href="#sobre" class="btn btn-outline">
                Nossa História
            </a>
        </div>
    </div>
</section>

<!-- ANNOUNCEMENT BAR -->
<div style="background: var(--black); border-top: 1px solid rgba(255,255,255,0.08); border-bottom: 1px solid rgba(255,255,255,0.08); padding: 0.7rem; text-align: center; overflow: hidden;">
    <div style="display: flex; gap: 4rem; animation: marquee 20s linear infinite; white-space: nowrap; width: max-content;">
        @foreach(range(1, 4) as $i)
        <span style="font-size: 0.68rem; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 500; color: rgba(255,255,255,0.6);">
            ✦ Frete Grátis acima de R$299 &nbsp; ✦ Parcele em até 12x sem juros &nbsp; ✦ Troca grátis em 30 dias &nbsp; ✦ PIX com 5% de desconto
        </span>
        @endforeach
    </div>
</div>

<!-- CATEGORIES -->
@if($categories->count())
<section style="padding: 6rem 2rem; max-width: 1400px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 4rem;">
        <p style="font-size: 0.65rem; letter-spacing: 0.3em; text-transform: uppercase; color: var(--gray); margin-bottom: 0.8rem;">Explore</p>
        <h2 class="font-serif" style="font-size: 2.8rem; font-weight: 400;">Nossas Categorias</h2>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        @foreach($categories as $category)
        <a href="{{ route('shop.index', ['category' => $category->slug]) }}" style="text-decoration: none; position: relative; overflow: hidden; aspect-ratio: 3/4; display: block; background: var(--light);">
            @if($category->image)
            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
            @else
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--dark), var(--black));"></div>
            @endif
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, transparent 60%);"></div>
            <div style="position: absolute; bottom: 1.5rem; left: 1.5rem;">
                <p style="font-size: 0.6rem; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(255,255,255,0.5); margin-bottom: 0.3rem;">Categoria</p>
                <h3 class="font-serif" style="color: var(--white); font-size: 1.4rem; font-weight: 600;">{{ $category->name }}</h3>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

<!-- FEATURED PRODUCTS -->
@if($featured->count())
<section style="padding: 4rem 2rem 6rem; background: var(--light);">
    <div style="max-width: 1400px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 4rem;">
            <p style="font-size: 0.65rem; letter-spacing: 0.3em; text-transform: uppercase; color: var(--gray); margin-bottom: 0.8rem;">Seleção</p>
            <h2 class="font-serif" style="font-size: 2.8rem; font-weight: 400;">Peças em Destaque</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem;">
            @foreach($featured as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div style="text-align: center; margin-top: 3rem;">
            <a href="{{ route('shop.index') }}" class="btn btn-dark">Ver Todos os Produtos <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>
@endif

<!-- BRAND STORY -->
<section id="sobre" style="background: var(--black); padding: 0;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; min-height: 600px;">
        <!-- Lado esquerdo: logo/imagem -->
        <div style="position: relative; overflow: hidden; min-height: 500px; background: #111;">
            @if(file_exists(public_path('images/logo.jpg')))
                <img src="{{ asset('images/logo.jpg') }}" alt="Totti Legacy"
                    style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9;">
            @else
                <div style="display: flex; align-items: center; justify-content: center; height: 100%; padding: 4rem;">
                    <p class="font-serif" style="font-size: 6rem; font-weight: 700; color: rgba(255,255,255,0.08); text-align: center; line-height: 1;">TOTTI<br>LEGACY</p>
                </div>
            @endif
            <div style="position: absolute; inset: 0; background: linear-gradient(to right, transparent 40%, var(--black));"></div>
        </div>

        <!-- Lado direito: história -->
        <div style="padding: 6rem 4rem; display: flex; flex-direction: column; justify-content: center;">
            <p style="font-size: 0.6rem; letter-spacing: 0.4em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 1.5rem;">O Legado de Totti</p>
            <h2 class="font-serif" style="font-size: 2.4rem; font-weight: 300; color: var(--white); line-height: 1.3; margin-bottom: 2rem;">
                A inspiração e o amor<br><em style="font-style: italic;">que dura.</em>
            </h2>
            <div style="font-size: 0.88rem; color: rgba(255,255,255,0.65); line-height: 2; display: flex; flex-direction: column; gap: 1.2rem;">
                <p>
                    Quando o Totti ficou doente e o tratamento foi ficando cada dia mais caro, uma das saídas foi me desfazer de boa parte da minha coleção pessoal de camisas de futebol. Vendi cada uma por R$200 — e claro, sabia que ali tinham camisas que valiam até R$1.500, R$2.000 em alguns casos.
                </p>
                <p>
                    Não tenho arrependimentos. A onda de amor que recebi eu jamais esquecerei. Graças a essas camisas, pude dar um fim de vida digno a esse serzinho que me trouxe tanto amor e alegria.
                </p>
                <p style="color: rgba(255,255,255,0.85); font-style: italic;">
                    Além do amor que me deu durante a vida, esse cãozinho me inspirou a criar esta loja. E nada mais justo que dar a ele o nome desse negócio.
                </p>
                <p>
                    Por isso <strong style="color: var(--white);">TOTTI LEGACY</strong> — o legado de Totti. Me uno ao meu irmão Cauê <a href="https://www.instagram.com/eaucaraujo/" target="_blank" style="color: var(--white); text-decoration: underline; text-underline-offset: 3px;">@eaucaraujo</a> com o compromisso de trazer o melhor produto para quem me acompanha há mais de 20 anos no <strong style="color: rgba(255,255,255,0.9);">Futirinhas</strong>.
                </p>
            </div>
            <div style="margin-top: 2.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="https://www.instagram.com/tottilegacy/" target="_blank" rel="noopener" class="btn btn-gold">
                    <i class="fab fa-instagram"></i> @tottilegacy
                </a>
                <a href="{{ route('shop.index') }}" class="btn btn-outline">
                    Ver Coleção
                </a>
            </div>
        </div>
    </div>

    <!-- Quote bar -->
    <div style="border-top: 1px solid rgba(255,255,255,0.08); padding: 3rem 2rem; text-align: center;">
        <p class="font-serif" style="font-size: 1.5rem; font-weight: 300; color: rgba(255,255,255,0.8); font-style: italic; max-width: 700px; margin: 0 auto;">
            "Vamos juntos e <strong style="font-style: normal; font-weight: 600; color: var(--white);">OBRIGADO</strong> pelo carinho!"
        </p>
    </div>
</section>

<style>
@media (max-width: 768px) {
    #sobre > div { grid-template-columns: 1fr !important; }
    #sobre > div > div:first-child { min-height: 300px !important; }
    #sobre > div > div:last-child { padding: 3rem 1.5rem !important; }
}
</style>

<!-- NEW ARRIVALS -->
@if($newArrivals->count())
<section style="padding: 4rem 2rem 8rem; background: var(--dark);">
    <div style="max-width: 1400px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 4rem;">
            <p style="font-size: 0.65rem; letter-spacing: 0.3em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 0.8rem;">Chegou Agora</p>
            <h2 class="font-serif" style="font-size: 2.8rem; font-weight: 400; color: var(--white);">Novidades</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem;">
            @foreach($newArrivals as $product)
                @include('partials.product-card', ['product' => $product, 'dark' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- TRUST BADGES -->
<section style="padding: 5rem 2rem; background: var(--white); border-top: 1px solid rgba(0,0,0,0.06);">
    <div style="max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 3rem; text-align: center;">
        @foreach([
            ['icon' => 'fas fa-truck', 'title' => 'Frete Grátis', 'desc' => 'Em compras acima de R$299'],
            ['icon' => 'fas fa-exchange-alt', 'title' => 'Troca Fácil', 'desc' => 'Até 30 dias sem burocracia'],
            ['icon' => 'fas fa-shield-alt', 'title' => 'Compra Segura', 'desc' => 'Pagamento 100% protegido'],
            ['icon' => 'fas fa-star', 'title' => 'Qualidade Premium', 'desc' => 'Tecidos selecionados'],
        ] as $badge)
        <div>
            <div style="width: 60px; height: 60px; border: 1px solid rgba(0,0,0,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--black); font-size: 1.3rem;">
                <i class="{{ $badge['icon'] }}"></i>
            </div>
            <h4 style="font-size: 0.85rem; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.4rem;">{{ $badge['title'] }}</h4>
            <p style="font-size: 0.78rem; color: var(--gray);">{{ $badge['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

<style>
@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
</style>

@endsection

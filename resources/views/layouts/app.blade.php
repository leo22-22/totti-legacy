<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Totti Legacy') — Camisas Premium</title>
    <meta name="description" content="@yield('meta_description', 'Totti Legacy — Moda Premium com estilo e exclusividade.')">

    @php
        use App\Models\Setting;
        $bodyFont    = Setting::get('theme_body_font',    'Montserrat');
        $headingFont = Setting::get('theme_heading_font', 'Cormorant Garamond');
        $accentColor = Setting::get('theme_accent_color', '#FFFFFF');
        $accentHover = Setting::get('theme_accent_hover', '#E8E8E8');
        $darkColor   = Setting::get('theme_dark_color',   '#0D0D0D');
        $imageRatio  = Setting::get('theme_image_ratio',  '3/4');
        $customCss   = Setting::get('theme_custom_css',   '');
        $footerLogo  = Setting::get('theme_footer_logo',  '');

        $barEnabled   = Setting::get('bar_enabled',   '1') === '1';
        $barText      = Setting::get('bar_text',      'Frete grátis acima de R$ 299 · 12x sem juros');
        $barLink      = Setting::get('bar_link',      '');
        $barBg        = Setting::get('bar_bg_color',  '#1A1A1A');
        $barTextColor = Setting::get('bar_text_color','#FFFFFF');

        $popupEnabled = Setting::get('popup_enabled', '0') === '1';
        $popupTitle   = Setting::get('popup_title',   'OFERTA ESPECIAL');
        $popupText    = Setting::get('popup_text',    '');
        $popupCoupon  = Setting::get('popup_coupon',  '');
        $popupDelay   = (int) Setting::get('popup_delay', '5');

        // Font pairs mapping
        $fontPairs = [
            'Montserrat'   => 'Montserrat:wght@300;400;500;600;700',
            'Inter'        => 'Inter:wght@300;400;500;600;700',
            'Poppins'      => 'Poppins:wght@300;400;500;600;700',
            'Raleway'      => 'Raleway:wght@300;400;500;600;700',
            'Nunito Sans'  => 'Nunito+Sans:wght@300;400;500;600;700',
            'Cormorant Garamond' => 'Cormorant+Garamond:wght@300;400;500;600;700',
            'Playfair Display'   => 'Playfair+Display:wght@400;500;600;700',
            'Libre Baskerville'  => 'Libre+Baskerville:wght@400;700',
            'Merriweather'       => 'Merriweather:wght@300;400;700',
            'Lora'               => 'Lora:wght@400;500;600;700',
        ];
        $googleFonts = collect([$bodyFont, $headingFont])->unique()
            ->map(fn($f) => $fontPairs[$f] ?? str_replace(' ', '+', $f))
            ->implode('&family=');
    @endphp

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ $googleFonts }}&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --accent:      {{ $accentColor }};
            --accent-dim:  {{ $accentColor }}1f;
            --accent-hover:{{ $accentHover }};
            --black:       {{ $darkColor }};
            --dark:        #1A1A1A;
            --gray:        #8A8A8A;
            --light:       #F2F2F2;
            --white:       #FFFFFF;
            --gold:        {{ $accentColor }};
            --gold-light:  {{ $accentHover }};
            --font-body:   '{{ $bodyFont }}', sans-serif;
            --font-heading:'{{ $headingFont }}', serif;
            --img-ratio:   {{ $imageRatio }};
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background: var(--white);
            color: var(--black);
        }

        .font-serif { font-family: var(--font-heading); }
        .text-accent { color: var(--accent); }
        .bg-accent   { background-color: var(--accent); }

        /* ANNOUNCEMENT BAR */
        .announcement-bar {
            background: {{ $barBg }};
            color: {{ $barTextColor }};
            font-size: .72rem;
            font-weight: 500;
            letter-spacing: .08em;
            text-align: center;
            padding: .45rem 1rem;
            position: relative;
            z-index: 1001;
        }
        .announcement-bar a {
            color: inherit;
            text-decoration: none;
        }
        .announcement-bar .bar-close {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            opacity: .6;
            font-size: .9rem;
            padding: 0 .3rem;
        }
        .announcement-bar .bar-close:hover { opacity: 1; }

        /* NAV */
        nav.main-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: rgba(13,13,13,.97);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,.1);
            transition: top .3s ease;
        }
        nav.main-nav.bar-visible { top: 32px; }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
        }

        .nav-logo {
            font-family: var(--font-heading);
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: .1em;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .nav-logo span { color: rgba(255,255,255,.6); }
        .nav-logo img { height: 48px; width: auto; mix-blend-mode: screen; filter: brightness(1.1); }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }
        .nav-links a {
            color: rgba(255,255,255,.6);
            text-decoration: none;
            font-size: .75rem;
            letter-spacing: .15em;
            text-transform: uppercase;
            font-weight: 500;
            transition: color .2s;
        }
        .nav-links a:hover, .nav-links a.active { color: var(--white); }

        .nav-actions { display: flex; align-items: center; gap: 1.5rem; }
        .nav-icon-btn {
            color: rgba(255,255,255,.6);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            position: relative;
            transition: color .2s;
            text-decoration: none;
            padding: .3rem;
        }
        .nav-icon-btn:hover { color: var(--white); }

        .cart-badge {
            position: absolute;
            top: -6px; right: -8px;
            background: var(--white);
            color: var(--black);
            border-radius: 50%;
            width: 18px; height: 18px;
            font-size: .65rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hamburger { display: none; }
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hamburger { display: block; }
        }

        /* FLASH MESSAGES */
        .flash-container {
            position: fixed;
            top: 80px; right: 1.5rem;
            z-index: 9999;
            display: flex; flex-direction: column; gap: .5rem;
        }
        .flash {
            padding: 1rem 1.5rem;
            border-radius: 4px;
            font-size: .85rem;
            font-weight: 500;
            animation: slideIn .3s ease;
            max-width: 350px;
        }
        .flash-success { background: #1a472a; color: #a3e9b4; border-left: 3px solid #2ed573; }
        .flash-error   { background: #4a1515; color: #fca5a5; border-left: 3px solid #ef4444; }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }

        /* FOOTER */
        footer.main-footer {
            background: var(--black);
            color: rgba(255,255,255,.6);
            padding: 4rem 0 2rem;
        }
        .footer-grid {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 3rem;
        }
        .footer-brand h3 {
            font-family: var(--font-heading);
            font-size: 1.8rem;
            color: var(--white);
            margin-bottom: 1rem;
        }
        .footer-brand p { font-size: .85rem; line-height: 1.8; }
        .footer-links h4 {
            color: var(--white);
            font-size: .7rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            margin-bottom: 1.2rem;
        }
        .footer-links ul { list-style: none; }
        .footer-links li { margin-bottom: .6rem; }
        .footer-links a {
            color: rgba(255,255,255,.5);
            text-decoration: none;
            font-size: .85rem;
            transition: color .2s;
        }
        .footer-links a:hover { color: var(--white); }
        .footer-social { display: flex; gap: 1rem; margin-top: 1rem; }
        .footer-social a {
            width: 38px; height: 38px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.7);
            text-decoration: none;
            transition: all .2s;
            font-size: .9rem;
        }
        .footer-social a:hover { background: var(--white); color: var(--black); border-color: var(--white); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.08);
            max-width: 1400px;
            margin: 3rem auto 0;
            padding: 1.5rem 2rem 0;
            text-align: center;
            font-size: .78rem;
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .85rem 2rem;
            font-family: var(--font-body);
            font-size: .75rem;
            letter-spacing: .15em;
            text-transform: uppercase;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all .3s;
        }
        .btn-gold { background: var(--accent); color: var(--black); border: 1px solid var(--accent); }
        .btn-gold:hover { background: var(--accent-hover); transform: translateY(-1px); box-shadow: 0 4px 20px rgba(255,255,255,.15); }
        .btn-outline { background: transparent; color: var(--white); border: 1px solid rgba(255,255,255,.5); }
        .btn-outline:hover { background: var(--white); color: var(--black); border-color: var(--white); }
        .btn-dark { background: var(--black); color: var(--white); border: 1px solid var(--black); }
        .btn-dark:hover { background: #2a2a2a; border-color: #2a2a2a; }
        .btn-outline-dark { background: transparent; color: var(--black); border: 1px solid rgba(0,0,0,.4); }
        .btn-outline-dark:hover { background: var(--black); color: var(--white); }
        .btn-full { width: 100%; justify-content: center; }

        /* PRODUCT CARD */
        .product-card { background: var(--white); position: relative; overflow: hidden; }
        .product-card-img {
            position: relative;
            overflow: hidden;
            aspect-ratio: var(--img-ratio);
            background: var(--light);
        }
        .product-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
        .product-card:hover .product-card-img img { transform: scale(1.05); }
        .product-badge {
            position: absolute;
            top: 1rem; left: 1rem;
            z-index: 1;
            padding: .3rem .8rem;
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        .badge-new  { background: var(--black); color: var(--white); }
        .badge-sale { background: var(--white); color: var(--black); border: 1px solid var(--black); }

        .product-card-actions {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: rgba(13,13,13,.92);
            padding: .75rem;
            transform: translateY(100%);
            transition: transform .3s ease;
            display: flex;
            flex-direction: column;
            gap: .4rem;
        }
        .product-card:hover .product-card-actions { transform: translateY(0); }

        .product-card-body { padding: 1.2rem 0; }
        .product-card-category { font-size: .65rem; letter-spacing: .15em; text-transform: uppercase; color: var(--gray); margin-bottom: .4rem; }
        .product-card-name { font-family: var(--font-heading); font-size: 1.1rem; font-weight: 600; color: var(--black); text-decoration: none; display: block; margin-bottom: .5rem; }
        .product-card-price { display: flex; align-items: center; gap: .8rem; }
        .price-current { font-weight: 700; font-size: 1rem; color: var(--black); }
        .price-original { font-size: .85rem; color: var(--gray); text-decoration: line-through; }
        .price-sale { color: #c0392b; }

        /* COUNTDOWN */
        .countdown-bar {
            background: #1a0a0a;
            color: #fca5a5;
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .05em;
            padding: .35rem .75rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .countdown-units { display: flex; gap: .3rem; }
        .countdown-unit {
            background: rgba(255,255,255,.12);
            padding: .15rem .4rem;
            border-radius: 3px;
            font-weight: 700;
            font-size: .75rem;
            min-width: 28px;
            text-align: center;
        }
        .countdown-sep { opacity: .6; }
        .countdown-label { opacity: .75; font-size: .65rem; text-transform: uppercase; letter-spacing: .08em; }
        .countdown-timer { display: flex; align-items: center; gap: .25rem; }

        /* COUPON POPUP */
        .popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.7);
            z-index: 9000;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn .3s ease;
        }
        .popup-box {
            background: var(--black);
            color: var(--white);
            max-width: 420px;
            width: 90%;
            padding: 2.5rem 2rem;
            border: 1px solid rgba(255,255,255,.12);
            position: relative;
            text-align: center;
        }
        .popup-close {
            position: absolute;
            top: 1rem; right: 1rem;
            background: none; border: none;
            color: rgba(255,255,255,.5);
            font-size: 1.2rem;
            cursor: pointer;
        }
        .popup-close:hover { color: var(--white); }
        .popup-title {
            font-family: var(--font-heading);
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .popup-text { color: rgba(255,255,255,.7); font-size: .85rem; line-height: 1.7; margin-bottom: 1.5rem; }
        .popup-coupon-code {
            background: rgba(255,255,255,.08);
            border: 1px dashed rgba(255,255,255,.3);
            padding: .75rem 1.5rem;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: .2em;
            cursor: pointer;
            transition: background .2s;
            margin-bottom: 1rem;
        }
        .popup-coupon-code:hover { background: rgba(255,255,255,.14); }
        .popup-coupon-hint { font-size: .72rem; color: rgba(255,255,255,.4); margin-bottom: 1.5rem; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* PAGE WRAPPER — shifts to account for announcement bar */
        .page-content { padding-top: 72px; min-height: 100vh; }
        body.has-bar .page-content { padding-top: calc(72px + 32px); }
        body.has-bar nav.main-nav { top: 32px; }

        /* SIZE GUIDE MODAL */
        .size-modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.75);
            z-index: 8000;
            display: flex; align-items: center; justify-content: center;
        }
        .size-modal-box {
            background: #fff; color: #111;
            max-width: 560px; width: 94%;
            max-height: 90vh; overflow-y: auto;
            padding: 2rem;
            position: relative;
        }
        .size-modal-box h3 {
            font-family: var(--font-heading);
            font-size: 1.6rem;
            margin-bottom: 1.2rem;
        }
        .size-modal-box table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        .size-modal-box th { background: #111; color: #fff; padding: .5rem .75rem; text-align: center; }
        .size-modal-box td { border: 1px solid #e5e5e5; padding: .45rem .75rem; text-align: center; }
        .size-modal-close { position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #666; }

        /* QUICK BUY MODAL */
        .quickbuy-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.75);
            z-index: 8000;
            display: flex; align-items: center; justify-content: center;
        }
        .quickbuy-box {
            background: #fff; color: #111;
            max-width: 480px; width: 94%;
            padding: 2rem;
            position: relative;
            max-height: 90vh; overflow-y: auto;
        }
        .quickbuy-box h3 { font-family: var(--font-heading); font-size: 1.4rem; margin-bottom: .3rem; }
        .quickbuy-price { font-size: 1.2rem; font-weight: 700; margin-bottom: 1.2rem; }
        .quickbuy-label { font-size: .72rem; letter-spacing: .12em; text-transform: uppercase; font-weight: 600; margin-bottom: .5rem; color: #555; }
        .quickbuy-sizes { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.2rem; }
        .qb-size {
            padding: .4rem .9rem;
            border: 1px solid #ccc;
            font-size: .8rem;
            cursor: pointer;
            transition: all .15s;
            background: none;
        }
        .qb-size:hover, .qb-size.active { background: #111; color: #fff; border-color: #111; }
        .quickbuy-colors { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.5rem; }
        .qb-color {
            padding: .4rem .9rem;
            border: 1px solid #ccc;
            font-size: .8rem;
            cursor: pointer;
            background: none;
            transition: all .15s;
        }
        .qb-color:hover, .qb-color.active { border-color: #111; background: #f5f5f5; }
        .quickbuy-close { position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #666; }

        @media (max-width: 640px) {
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>

    @if($customCss)
    <style id="custom-css">{{ $customCss }}</style>
    @endif

    @yield('styles')
</head>

<body class="{{ $barEnabled ? 'has-bar' : '' }}">

    {{-- ANNOUNCEMENT BAR --}}
    @if($barEnabled)
    <div class="announcement-bar" id="announcementBar"
         style="background:{{ $barBg }};color:{{ $barTextColor }}">
        @if($barLink)
            <a href="{{ $barLink }}">{{ $barText }}</a>
        @else
            <span>{{ $barText }}</span>
        @endif
        <button class="bar-close" onclick="closeBar()" title="Fechar">&times;</button>
    </div>
    @endif

    <!-- NAVIGATION -->
    <nav class="main-nav" id="mainNav">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="nav-logo">
                @if(file_exists(public_path('images/logo.jpg')))
                    <img src="{{ asset('images/logo.jpg') }}" alt="Totti Legacy">
                @else
                    TOTTI <span>LEGACY</span>
                @endif
            </a>

            <ul class="nav-links">
                <li><a href="{{ route('home') }}"        class="{{ request()->routeIs('home')   ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('shop.index') }}"  class="{{ request()->routeIs('shop.*') ? 'active' : '' }}">Camisas</a></li>
                <li><a href="{{ route('home') }}#sobre">Sobre</a></li>
                <li><a href="{{ route('home') }}#contato">Contato</a></li>
            </ul>

            <div class="nav-actions">
                <a href="{{ route('shop.index') }}" class="nav-icon-btn" title="Buscar">
                    <i class="fas fa-search"></i>
                </a>
                <a href="{{ route('cart.index') }}" class="nav-icon-btn" title="Carrinho">
                    <i class="fas fa-shopping-bag"></i>
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                    <a href="{{ route('account.index') }}" class="nav-icon-btn" title="Minha conta">
                        <i class="fas fa-user"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-icon-btn" title="Entrar">
                        <i class="fas fa-user"></i>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- FLASH MESSAGES -->
    @if(session('success') || session('error') || session('coupon_success') || session('coupon_error'))
        <div class="flash-container" id="flashContainer">
            @if(session('success'))
                <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @if(session('coupon_success'))
                <div class="flash flash-success"><i class="fas fa-ticket-alt"></i> {{ session('coupon_success') }}</div>
            @endif
            @if(session('coupon_error'))
                <div class="flash flash-error"><i class="fas fa-times-circle"></i> {{ session('coupon_error') }}</div>
            @endif
        </div>
    @endif

    <!-- PAGE CONTENT -->
    <main class="page-content">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="main-footer">
        <div class="footer-grid">
            <div class="footer-brand">
                @php $fLogo = $footerLogo ?: ''; @endphp
                @if($fLogo && file_exists(storage_path('app/public/' . $fLogo)))
                    <img src="{{ asset('storage/' . $fLogo) }}" alt="Totti Legacy"
                         style="height:70px;width:auto;margin-bottom:1rem;filter:brightness(1.1)">
                @elseif(file_exists(public_path('images/logo.jpg')))
                    <img src="{{ asset('images/logo.jpg') }}" alt="Totti Legacy"
                         style="height:70px;width:auto;mix-blend-mode:screen;filter:brightness(1.1);margin-bottom:1rem">
                @else
                    <h3>TOTTI LEGACY</h3>
                @endif
                <p>Nascida do amor a um cãozinho chamado Totti, a Totti Legacy carrega o legado de quem nos inspira a ir além.</p>
                <div class="footer-social">
                    <a href="https://www.instagram.com/tottilegacy/" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div class="footer-links">
                <h4>Navegação</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('shop.index') }}">Camisas</a></li>
                    <li><a href="{{ route('home') }}#sobre">Sobre</a></li>
                    <li><a href="{{ route('home') }}#contato">Contato</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Ajuda</h4>
                <ul>
                    <li><a href="#" onclick="openSizeGuide();return false">Guia de Tamanhos</a></li>
                    <li><a href="#">Política de Troca</a></li>
                    <li><a href="#">Prazo de Entrega</a></li>
                    <li><a href="#">Contato</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Pagamentos</h4>
                <ul>
                    <li><a href="#">Pix</a></li>
                    <li><a href="#">Cartão de Crédito</a></li>
                    <li><a href="#">Boleto Bancário</a></li>
                    <li><a href="#">Parcelamento em até 12x</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© {{ date('Y') }} Totti Legacy. Todos os direitos reservados.</p>
        </div>
    </footer>

    {{-- COUPON POPUP --}}
    @if($popupEnabled && $popupCoupon)
    <div class="popup-overlay" id="couponPopup" style="display:none">
        <div class="popup-box">
            <button class="popup-close" onclick="closePopup()">&times;</button>
            <div style="font-size:2.5rem;margin-bottom:.75rem">🎁</div>
            <div class="popup-title">{{ $popupTitle }}</div>
            <div class="popup-text">{{ $popupText }}</div>
            <div class="popup-coupon-code" onclick="copyPopupCoupon(this)" id="popupCouponCode">{{ $popupCoupon }}</div>
            <div class="popup-coupon-hint">Clique para copiar · Use no carrinho</div>
            <button class="btn btn-gold btn-full" onclick="closePopup()" style="font-size:.72rem">Aproveitar agora →</button>
        </div>
    </div>
    @endif

    {{-- SIZE GUIDE MODAL --}}
    <div class="size-modal-overlay" id="sizeGuideModal" style="display:none" onclick="if(event.target===this)closeSizeGuide()">
        <div class="size-modal-box">
            <button class="size-modal-close" onclick="closeSizeGuide()">&times;</button>
            <h3>Guia de Tamanhos</h3>
            <p style="font-size:.82rem;color:#666;margin-bottom:1.2rem">Medidas em centímetros. Dica: meça a camisa que você já tem e compare.</p>
            <table>
                <thead>
                    <tr>
                        <th>Tamanho</th>
                        <th>Tórax</th>
                        <th>Ombro</th>
                        <th>Comprimento</th>
                        <th>Manga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><strong>PP</strong></td><td>88–92</td><td>41</td><td>68</td><td>20</td></tr>
                    <tr><td><strong>P</strong></td><td>92–96</td><td>43</td><td>70</td><td>21</td></tr>
                    <tr><td><strong>M</strong></td><td>96–100</td><td>45</td><td>72</td><td>22</td></tr>
                    <tr><td><strong>G</strong></td><td>100–104</td><td>47</td><td>74</td><td>23</td></tr>
                    <tr><td><strong>GG</strong></td><td>104–110</td><td>49</td><td>76</td><td>24</td></tr>
                    <tr><td><strong>XG</strong></td><td>110–118</td><td>51</td><td>78</td><td>25</td></tr>
                    <tr><td><strong>XXG</strong></td><td>118–128</td><td>54</td><td>80</td><td>26</td></tr>
                </tbody>
            </table>
            <p style="font-size:.75rem;color:#999;margin-top:1rem">* Medidas aproximadas. Modelos fitness podem ter corte mais ajustado.</p>
        </div>
    </div>

    {{-- QUICK BUY MODAL --}}
    <div class="quickbuy-overlay" id="quickBuyModal" style="display:none" onclick="if(event.target===this)closeQuickBuy()">
        <div class="quickbuy-box">
            <button class="quickbuy-close" onclick="closeQuickBuy()">&times;</button>
            <div id="qb-img-wrap" style="margin-bottom:1rem"></div>
            <h3 id="qb-name"></h3>
            <div class="quickbuy-price" id="qb-price"></div>
            <form id="quickBuyForm" method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" id="qb-product-id">
                <input type="hidden" name="size"       id="qb-size-val">
                <input type="hidden" name="color"      id="qb-color-val">
                <input type="hidden" name="quantity"   value="1">

                <div id="qb-sizes-wrap">
                    <div class="quickbuy-label">Tamanho</div>
                    <div class="quickbuy-sizes" id="qb-sizes"></div>
                </div>
                <div id="qb-colors-wrap">
                    <div class="quickbuy-label">Cor</div>
                    <div class="quickbuy-colors" id="qb-colors"></div>
                </div>

                <button type="submit" class="btn btn-dark btn-full" style="margin-top:.5rem">
                    <i class="fas fa-shopping-bag"></i> Adicionar ao Carrinho
                </button>
                <a id="qb-link" href="#" class="btn btn-outline-dark btn-full" style="margin-top:.5rem;text-align:center">
                    Ver produto completo
                </a>
            </form>
        </div>
    </div>

    <script>
    // Announcement bar
    function closeBar() {
        const bar = document.getElementById('announcementBar');
        if (bar) {
            bar.style.display = 'none';
            document.body.classList.remove('has-bar');
            sessionStorage.setItem('barClosed', '1');
        }
    }
    if (sessionStorage.getItem('barClosed') === '1') {
        const bar = document.getElementById('announcementBar');
        if (bar) { bar.style.display = 'none'; document.body.classList.remove('has-bar'); }
    }

    // Flash auto-dismiss
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.flash').forEach(f => {
            setTimeout(() => { f.style.opacity = '0'; f.style.transition = 'opacity .5s'; setTimeout(() => f.remove(), 500); }, 4000);
        });

        @if($popupEnabled && $popupCoupon)
        if (!sessionStorage.getItem('popupShown')) {
            setTimeout(function () {
                const popup = document.getElementById('couponPopup');
                if (popup) popup.style.display = 'flex';
            }, {{ $popupDelay * 1000 }});
        }
        @endif
    });

    // Coupon popup
    function closePopup() {
        const p = document.getElementById('couponPopup');
        if (p) p.style.display = 'none';
        sessionStorage.setItem('popupShown', '1');
    }
    function copyPopupCoupon(el) {
        navigator.clipboard.writeText(el.textContent.trim()).then(() => {
            const orig = el.textContent;
            el.textContent = '✓ COPIADO!';
            setTimeout(() => el.textContent = orig, 1500);
        });
    }

    // Size guide
    function openSizeGuide()  { document.getElementById('sizeGuideModal').style.display = 'flex'; }
    function closeSizeGuide() { document.getElementById('sizeGuideModal').style.display = 'none'; }

    // Quick buy
    function openQuickBuy(data) {
        document.getElementById('qb-name').textContent       = data.name;
        document.getElementById('qb-product-id').value       = data.id;
        document.getElementById('qb-link').href              = data.url;
        document.getElementById('qb-price').innerHTML        = data.price_html;
        document.getElementById('qb-img-wrap').innerHTML     = data.img
            ? `<img src="${data.img}" style="width:100%;max-height:220px;object-fit:cover;border-radius:4px">`
            : '';

        // Sizes
        const sizesWrap = document.getElementById('qb-sizes-wrap');
        const sizesCont = document.getElementById('qb-sizes');
        sizesCont.innerHTML = '';
        if (data.sizes && data.sizes.length) {
            sizesWrap.style.display = 'block';
            data.sizes.forEach((s, i) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'qb-size' + (i === 0 ? ' active' : '');
                btn.textContent = s;
                btn.onclick = function () {
                    document.querySelectorAll('#qb-sizes .qb-size').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('qb-size-val').value = s;
                };
                sizesCont.appendChild(btn);
            });
            document.getElementById('qb-size-val').value = data.sizes[0];
        } else {
            sizesWrap.style.display = 'none';
        }

        // Colors
        const colorsWrap = document.getElementById('qb-colors-wrap');
        const colorsCont = document.getElementById('qb-colors');
        colorsCont.innerHTML = '';
        if (data.colors && data.colors.length) {
            colorsWrap.style.display = 'block';
            data.colors.forEach((c, i) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'qb-color' + (i === 0 ? ' active' : '');
                btn.textContent = c;
                btn.onclick = function () {
                    document.querySelectorAll('#qb-colors .qb-color').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('qb-color-val').value = c;
                };
                colorsCont.appendChild(btn);
            });
            document.getElementById('qb-color-val').value = data.colors[0];
        } else {
            colorsWrap.style.display = 'none';
            document.getElementById('qb-color-val').value = 'Único';
        }

        document.getElementById('quickBuyModal').style.display = 'flex';
    }
    function closeQuickBuy() { document.getElementById('quickBuyModal').style.display = 'none'; }

    // Countdown helper (used by product-card and show page)
    function startCountdown(el, endTs) {
        function tick() {
            const diff = Math.max(0, endTs - Math.floor(Date.now() / 1000));
            if (diff === 0) { el.closest('.countdown-bar')?.remove(); return; }
            const d = Math.floor(diff / 86400);
            const h = Math.floor((diff % 86400) / 3600);
            const m = Math.floor((diff % 3600) / 60);
            const s = diff % 60;
            const fmt = n => String(n).padStart(2, '0');
            el.innerHTML = (d > 0 ? `<span class="countdown-unit">${d}d</span><span class="countdown-sep">:</span>` : '') +
                `<span class="countdown-unit">${fmt(h)}</span><span class="countdown-sep">:</span>` +
                `<span class="countdown-unit">${fmt(m)}</span><span class="countdown-sep">:</span>` +
                `<span class="countdown-unit">${fmt(s)}</span>`;
        }
        tick();
        setInterval(tick, 1000);
    }
    </script>

    @stack('scripts')
</body>
</html>

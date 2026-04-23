<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Totti Legacy') — Camisas Premium</title>
    <meta name="description"
        content="@yield('meta_description', 'Totti Legacy — Moda Premium com estilo e exclusividade.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --accent: #FFFFFF;
            --accent-dim: rgba(255, 255, 255, 0.12);
            --black: #0D0D0D;
            --dark: #1A1A1A;
            --gray: #8A8A8A;
            --light: #F2F2F2;
            --white: #FFFFFF;
            /* legacy alias */
            --gold: #FFFFFF;
            --gold-light: #E8E8E8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--white);
            color: var(--black);
        }

        .font-serif {
            font-family: 'Cormorant Garamond', serif;
        }

        .text-accent {
            color: var(--accent);
        }

        .bg-accent {
            background-color: var(--accent);
        }

        /* NAV */
        nav.main-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(13, 13, 13, 0.97);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

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
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 0.1em;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .nav-logo span {
            color: rgba(255, 255, 255, 0.6);
        }

        .nav-logo img {
            height: 48px;
            width: auto;
            mix-blend-mode: screen;
            filter: brightness(1.1);
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.75rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--white);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-icon-btn {
            color: rgba(255, 255, 255, 0.6);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            position: relative;
            transition: color 0.2s;
            text-decoration: none;
            padding: 0.3rem;
        }

        .nav-icon-btn:hover {
            color: var(--white);
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            right: -8px;
            background: var(--white);
            color: var(--black);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.65rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* MOBILE */
        .hamburger {
            display: none;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hamburger {
                display: block;
            }
        }

        /* FLASH MESSAGES */
        .flash-container {
            position: fixed;
            top: 80px;
            right: 1.5rem;
            z-index: 999;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .flash {
            padding: 1rem 1.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
            animation: slideIn 0.3s ease;
            max-width: 350px;
        }

        .flash-success {
            background: #1a472a;
            color: #a3e9b4;
            border-left: 3px solid #2ed573;
        }

        .flash-error {
            background: #4a1515;
            color: #fca5a5;
            border-left: 3px solid #ef4444;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* FOOTER */
        footer.main-footer {
            background: var(--black);
            color: rgba(255, 255, 255, 0.6);
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
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            color: var(--white);
            margin-bottom: 1rem;
        }

        .footer-brand p {
            font-size: 0.85rem;
            line-height: 1.8;
        }

        .footer-links h4 {
            color: var(--white);
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 1.2rem;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.6rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--white);
        }

        .footer-social {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .footer-social a {
            width: 38px;
            height: 38px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .footer-social a:hover {
            background: var(--white);
            color: var(--black);
            border-color: var(--white);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 3rem;
            padding-top: 1.5rem;
            text-align: center;
            font-size: 0.78rem;
            max-width: 1400px;
            margin: 3rem auto 0;
            padding: 1.5rem 2rem 0;
        }

        /* BTN */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 2rem;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-gold {
            background: var(--white);
            color: var(--black);
            border: 1px solid var(--white);
        }

        .btn-gold:hover {
            background: var(--light);
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.15);
        }

        .btn-outline {
            background: transparent;
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .btn-outline:hover {
            background: var(--white);
            color: var(--black);
            border-color: var(--white);
        }

        .btn-dark {
            background: var(--black);
            color: var(--white);
            border: 1px solid var(--black);
        }

        .btn-dark:hover {
            background: var(--dark);
            border-color: var(--dark);
        }

        /* btn-outline on light bg */
        .btn-outline-dark {
            background: transparent;
            color: var(--black);
            border: 1px solid rgba(0, 0, 0, 0.4);
        }

        .btn-outline-dark:hover {
            background: var(--black);
            color: var(--white);
        }

        .btn-full {
            width: 100%;
            justify-content: center;
        }

        /* PRODUCT CARD */
        .product-card {
            background: var(--white);
            position: relative;
            overflow: hidden;
        }

        .product-card-img {
            position: relative;
            overflow: hidden;
            aspect-ratio: 3/4;
            background: var(--light);
        }

        .product-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-card-img img {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            z-index: 1;
            padding: 0.3rem 0.8rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .badge-new {
            background: var(--black);
            color: var(--white);
        }

        .badge-sale {
            background: var(--white);
            color: var(--black);
            border: 1px solid var(--black);
        }

        .product-card-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(13, 13, 13, 0.92);
            padding: 1rem;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-card-actions {
            transform: translateY(0);
        }

        .product-card-body {
            padding: 1.2rem 0;
        }

        .product-card-category {
            font-size: 0.65rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 0.4rem;
        }

        .product-card-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--black);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
        }

        .product-card-price {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .price-current {
            font-weight: 700;
            font-size: 1rem;
            color: var(--black);
        }

        .price-original {
            font-size: 0.85rem;
            color: var(--gray);
            text-decoration: line-through;
        }

        .price-sale {
            color: #c0392b;
        }

        /* PAGE WRAPPER */
        .page-content {
            padding-top: 72px;
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <!-- NAVIGATION -->
    <nav class="main-nav">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="nav-logo">
                @if(file_exists(public_path('images/logo.jpg')))
                    <img src="{{ asset('images/logo.jpg') }}" alt="Totti Legacy">
                @else
                    TOTTI <span>LEGACY</span>
                @endif
            </a>

            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.*') ? 'active' : '' }}">Camisas</a></li>
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
                <div class="flash flash-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('coupon_success'))
                <div class="flash flash-success">
                    <i class="fas fa-ticket-alt"></i> {{ session('coupon_success') }}
                </div>
            @endif
            @if(session('coupon_error'))
                <div class="flash flash-error">
                    <i class="fas fa-times-circle"></i> {{ session('coupon_error') }}
                </div>
            @endif
        </div>
        <script>setTimeout(() => { const f = document.getElementById('flashContainer'); if (f) f.remove(); }, 4000);</script>
    @endif

    <!-- PAGE CONTENT -->
    <main class="page-content">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="main-footer">
        <div class="footer-grid">
            <div class="footer-brand">
                @if(file_exists(public_path('images/logo.jpg')))
                    <img src="{{ asset('images/logo.jpg') }}" alt="Totti Legacy"
                        style="height: 70px; width: auto; mix-blend-mode: screen; filter: brightness(1.1); margin-bottom: 1rem;">
                @else
                    <h3>TOTTI LEGACY</h3>
                @endif
                <p>Nascida do amor a um cãozinho chamado Totti, a Totti Legacy carrega o legado de quem nos inspira a ir
                    além.</p>
                <div class="footer-social">
                    <a href="https://www.instagram.com/tottilegacy/" target="_blank" rel="noopener">
                        <i class="fab fa-instagram"></i>
                    </a>
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
                    <li><a href="#">Guia de Tamanhos</a></li>
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
            <p>© {{ date('Y') }} Totti Legacy. Todos os direitos reservados. | CNPJ: 00.000.000/0001-00</p>
        </div>
    </footer>

    @livewireScripts
    <script>
        // Auto-dismiss flash messages
        document.addEventListener('DOMContentLoaded', function () {
            const flashes = document.querySelectorAll('.flash');
            flashes.forEach(f => {
                setTimeout(() => { f.style.opacity = '0'; f.style.transition = 'opacity 0.5s'; setTimeout(() => f.remove(), 500); }, 4000);
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
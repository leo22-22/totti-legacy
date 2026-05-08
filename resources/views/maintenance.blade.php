<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Em Manutenção — Totti Legacy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0D0D0D;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .container { max-width: 500px; padding: 2rem; }
        .logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            font-weight: 600;
            letter-spacing: .2em;
            margin-bottom: 2.5rem;
            color: #fff;
        }
        .logo span { color: rgba(255,255,255,.4); }
        .icon { font-size: 3rem; margin-bottom: 1.5rem; }
        h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 400;
            margin-bottom: 1rem;
        }
        p {
            color: rgba(255,255,255,.6);
            line-height: 1.8;
            font-size: .9rem;
            margin-bottom: 2rem;
        }
        .divider {
            width: 60px;
            height: 1px;
            background: rgba(255,255,255,.2);
            margin: 2rem auto;
        }
        .social { display: flex; gap: 1rem; justify-content: center; }
        .social a {
            width: 40px; height: 40px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.6);
            text-decoration: none;
            font-size: .9rem;
            transition: all .2s;
        }
        .social a:hover { border-color: #fff; color: #fff; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="container">
        @if(file_exists(public_path('images/logo.jpg')))
            <img src="{{ asset('images/logo.jpg') }}" alt="Totti Legacy"
                 style="height:70px;mix-blend-mode:screen;filter:brightness(1.1);margin-bottom:2rem">
        @else
            <div class="logo">TOTTI <span>LEGACY</span></div>
        @endif

        <div class="icon">🛠️</div>
        <h1>Em Manutenção</h1>
        <p>{{ $message }}</p>

        <div class="divider"></div>

        <div class="social">
            <a href="https://www.instagram.com/tottilegacy/" target="_blank">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="#"><i class="fab fa-whatsapp"></i></a>
            <a href="#"><i class="fab fa-tiktok"></i></a>
        </div>
    </div>
</body>
</html>

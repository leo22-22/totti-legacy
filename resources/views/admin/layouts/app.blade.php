<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Totti Legacy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-width: 230px; --sidebar-bg: #111827; }
        body { background: #f3f4f6; font-size: .9rem; }

        /* Sidebar */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            overflow-y: auto; z-index: 1000;
        }
        .sidebar-brand {
            padding: 1.25rem 1rem .75rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-brand .brand-name { color: #fff; font-weight: 700; font-size: 1rem; }
        .sidebar-brand .brand-sub  { color: #6b7280; font-size: .7rem; letter-spacing: .05em; }
        .nav-section-label {
            padding: .75rem 1rem .2rem;
            font-size: .65rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .1em;
            color: #4b5563;
        }
        .sidebar .nav-link {
            display: flex; align-items: center; gap: .5rem;
            color: #9ca3af; padding: .45rem 1rem;
            border-radius: 6px; margin: 1px 6px;
            font-size: .85rem; transition: background .15s, color .15s;
        }
        .sidebar .nav-link:hover { background: rgba(255,255,255,.06); color: #e5e7eb; }
        .sidebar .nav-link.active { background: rgba(99,102,241,.2); color: #a5b4fc; }
        .sidebar .nav-link .badge-count {
            margin-left: auto; background: #ef4444;
            color: #fff; font-size: .65rem; padding: .15rem .4rem; border-radius: 999px;
        }
        .sidebar-footer { margin-top: auto; padding: .75rem; border-top: 1px solid rgba(255,255,255,.07); }

        /* Main */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            background: #fff; border-bottom: 1px solid #e5e7eb;
            padding: .6rem 1.5rem; display: flex; align-items: center;
            gap: 1rem; position: sticky; top: 0; z-index: 100;
        }
        .topbar .page-title { font-weight: 600; color: #111827; margin: 0; }
        .topbar .admin-name  { margin-left: auto; color: #6b7280; font-size: .85rem; }
        .content-area { padding: 1.5rem; flex: 1; }

        /* Cards */
        .stat-card { border: none; border-radius: 10px; }
        .stat-card .card-body { padding: 1.25rem; }

        /* Tables */
        .table th { font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; font-weight: 600; }
        .badge-status { font-size: .72rem; font-weight: 500; }

        /* Flash */
        .alert { border-radius: 8px; }
    </style>
    @yield('styles')
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-name">🦅 Totti Legacy</div>
        <div class="brand-sub">Painel Administrativo</div>
    </div>

    <nav class="nav flex-column mt-1 flex-grow-1">
        <div class="nav-section-label">Geral</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <div class="nav-section-label">Catálogo</div>
        <a href="{{ route('admin.products.index') }}"
           class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-bag"></i> Produtos
            @php $lowStock = \App\Models\Product::where('is_active',true)->where('stock','<=',5)->count(); @endphp
            @if($lowStock > 0)
                <span class="badge-count">{{ $lowStock }}</span>
            @endif
        </a>
        <a href="{{ route('admin.categories.index') }}"
           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tag"></i> Categorias
        </a>

        <div class="nav-section-label">Vendas</div>
        <a href="{{ route('admin.orders.index') }}"
           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-cart3"></i> Pedidos
            @php $pending = \App\Models\Order::where('status','pending')->count(); @endphp
            @if($pending > 0)
                <span class="badge-count">{{ $pending }}</span>
            @endif
        </a>

        <div class="nav-section-label">Marketing</div>
        <a href="{{ route('admin.coupons.index') }}"
           class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i> Cupons
        </a>

        <div class="nav-section-label">Configurações</div>
        <a href="{{ route('admin.settings.index') }}"
           class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-sliders"></i> Configurações da Loja
        </a>

        <div class="nav-section-label">Loja</div>
        <a href="{{ route('home') }}" target="_blank" class="nav-link">
            <i class="bi bi-box-arrow-up-right"></i> Ver Site
        </a>
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                <i class="bi bi-box-arrow-left me-1"></i> Sair
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <h6 class="page-title">@yield('title', 'Dashboard')</h6>
        <span class="admin-name"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}</span>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible mb-3" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible mb-3" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible mb-3">
                <strong>Corrija os erros abaixo:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>

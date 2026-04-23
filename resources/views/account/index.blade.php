@extends('layouts.app')
@section('title', 'Minha Conta — Totti Legacy')
@section('content')
<section style="max-width: 1000px; margin: 0 auto; padding: 4rem 2rem;">
    <h1 class="font-serif" style="font-size: 2.5rem; font-weight: 400; margin-bottom: 0.5rem;">Minha Conta</h1>
    <p style="color: var(--gray); margin-bottom: 3rem;">Olá, {{ auth()->user()->name }}</p>

    @if(session('success'))
        <div style="background: #1a472a; color: #a3e9b4; padding: 1rem 1.5rem; border-radius: 4px; margin-bottom: 2rem; font-size: 0.85rem;">{{ session('success') }}</div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">

        {{-- Dados pessoais --}}
        <div>
            <h2 style="font-size: 0.75rem; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 700; margin-bottom: 1.5rem; padding-bottom: 0.8rem; border-bottom: 1px solid rgba(0,0,0,0.1);">Dados Pessoais</h2>
            <form method="POST" action="{{ route('account.profile.update') }}">
                @csrf @method('PUT')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; margin-bottom: 0.4rem;">Nome</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required style="width: 100%; padding: 0.8rem 1rem; border: 1px solid rgba(0,0,0,0.15); font-family: inherit; font-size: 0.9rem; outline: none;">
                    @error('name')<p style="color:#e53e3e;font-size:0.78rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; margin-bottom: 0.4rem;">E-mail</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled style="width: 100%; padding: 0.8rem 1rem; border: 1px solid rgba(0,0,0,0.1); font-family: inherit; font-size: 0.9rem; background: #f9f9f9; color: var(--gray);">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; margin-bottom: 0.4rem;">Telefone</label>
                        <input type="tel" name="phone" value="{{ auth()->user()->phone }}" placeholder="(11) 99999-9999" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid rgba(0,0,0,0.15); font-family: inherit; font-size: 0.9rem; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; margin-bottom: 0.4rem;">CPF</label>
                        <input type="text" name="cpf" value="{{ auth()->user()->cpf }}" placeholder="000.000.000-00" maxlength="14" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid rgba(0,0,0,0.15); font-family: inherit; font-size: 0.9rem; outline: none;">
                    </div>
                </div>
                <button type="submit" class="btn btn-dark">Salvar Alterações</button>
            </form>
        </div>

        {{-- Alterar senha --}}
        <div>
            <h2 style="font-size: 0.75rem; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 700; margin-bottom: 1.5rem; padding-bottom: 0.8rem; border-bottom: 1px solid rgba(0,0,0,0.1);">Alterar Senha</h2>
            <form method="POST" action="{{ route('account.password.update') }}">
                @csrf @method('PUT')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; margin-bottom: 0.4rem;">Senha Atual</label>
                    <input type="password" name="current_password" required style="width: 100%; padding: 0.8rem 1rem; border: 1px solid {{ $errors->has('current_password') ? '#e53e3e' : 'rgba(0,0,0,0.15)' }}; font-family: inherit; font-size: 0.9rem; outline: none;">
                    @error('current_password')<p style="color:#e53e3e;font-size:0.78rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; margin-bottom: 0.4rem;">Nova Senha</label>
                    <input type="password" name="password" required minlength="8" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid rgba(0,0,0,0.15); font-family: inherit; font-size: 0.9rem; outline: none;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; margin-bottom: 0.4rem;">Confirmar Nova Senha</label>
                    <input type="password" name="password_confirmation" required style="width: 100%; padding: 0.8rem 1rem; border: 1px solid rgba(0,0,0,0.15); font-family: inherit; font-size: 0.9rem; outline: none;">
                </div>
                <button type="submit" class="btn btn-dark">Alterar Senha</button>
            </form>
        </div>
    </div>

    {{-- Pedidos recentes --}}
    @if($recentOrders->isNotEmpty())
    <div style="margin-top: 4rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 0.8rem; border-bottom: 1px solid rgba(0,0,0,0.1);">
            <h2 style="font-size: 0.75rem; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 700;">Pedidos Recentes</h2>
            <a href="{{ route('account.orders') }}" style="font-size: 0.8rem; color: var(--gray); text-decoration: none;">Ver todos →</a>
        </div>
        @foreach($recentOrders as $order)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid rgba(0,0,0,0.06);">
            <div>
                <span style="font-weight: 600; font-size: 0.9rem;">{{ $order->order_number }}</span>
                <span style="color: var(--gray); font-size: 0.8rem; margin-left: 1rem;">{{ $order->created_at->format('d/m/Y') }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <span style="font-weight: 600;">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                <span style="font-size: 0.75rem; padding: 0.3rem 0.8rem; background: {{ $order->status === 'delivered' ? '#1a472a' : ($order->status === 'cancelled' ? '#4a1515' : '#1a1a2e') }}; color: {{ $order->status === 'delivered' ? '#a3e9b4' : ($order->status === 'cancelled' ? '#fca5a5' : '#a0aec0') }}; border-radius: 20px;">{{ $order->status_label }}</span>
                <a href="{{ route('account.order', $order->order_number) }}" style="font-size: 0.8rem; color: var(--gray); text-decoration: none;">Ver →</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>
@endsection

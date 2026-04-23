@extends('layouts.app')
@section('title', 'Entrar — Totti Legacy')
@section('content')
<section style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 4rem 1rem;">
    <div style="width: 100%; max-width: 440px;">
        <h1 class="font-serif" style="font-size: 2.2rem; font-weight: 400; text-align: center; margin-bottom: 0.5rem;">Entrar</h1>
        <p style="text-align: center; color: var(--gray); font-size: 0.85rem; margin-bottom: 2.5rem;">Acesse sua conta Totti Legacy</p>

        @if(session('success'))
            <div style="background: #1a472a; color: #a3e9b4; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem; font-size: 0.85rem;">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    style="width: 100%; padding: 0.9rem 1rem; border: 1px solid {{ $errors->has('email') ? '#e53e3e' : 'rgba(0,0,0,0.15)' }}; font-family: inherit; font-size: 0.9rem; outline: none; transition: border 0.2s;"
                    onfocus="this.style.borderColor='#0D0D0D'" onblur="this.style.borderColor='rgba(0,0,0,0.15)'">
                @error('email')<p style="color: #e53e3e; font-size: 0.78rem; margin-top: 0.4rem;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 0.8rem;">
                <label style="display: block; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">Senha</label>
                <input type="password" name="password" required
                    style="width: 100%; padding: 0.9rem 1rem; border: 1px solid rgba(0,0,0,0.15); font-family: inherit; font-size: 0.9rem; outline: none; transition: border 0.2s;"
                    onfocus="this.style.borderColor='#0D0D0D'" onblur="this.style.borderColor='rgba(0,0,0,0.15)'">
                @error('password')<p style="color: #e53e3e; font-size: 0.78rem; margin-top: 0.4rem;">{{ $message }}</p>@enderror
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; cursor: pointer;">
                    <input type="checkbox" name="remember"> Lembrar de mim
                </label>
                <a href="{{ route('password.request') }}" style="font-size: 0.82rem; color: var(--gray); text-decoration: none;" onmouseover="this.style.color='#0D0D0D'" onmouseout="this.style.color='var(--gray)'">Esqueci a senha</a>
            </div>

            <button type="submit" class="btn btn-dark btn-full">Entrar</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; font-size: 0.85rem; color: var(--gray);">
            Não tem conta? <a href="{{ route('register') }}" style="color: #0D0D0D; font-weight: 600; text-decoration: none;">Criar conta</a>
        </p>
    </div>
</section>
@endsection

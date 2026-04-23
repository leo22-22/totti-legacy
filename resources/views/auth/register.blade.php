@extends('layouts.app')
@section('title', 'Criar Conta — Totti Legacy')
@section('content')
<section style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 4rem 1rem;">
    <div style="width: 100%; max-width: 480px;">
        <h1 class="font-serif" style="font-size: 2.2rem; font-weight: 400; text-align: center; margin-bottom: 0.5rem;">Criar Conta</h1>
        <p style="text-align: center; color: var(--gray); font-size: 0.85rem; margin-bottom: 2.5rem;">Faça parte da família Totti Legacy</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.2rem;">
                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">Nome Completo *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        style="width: 100%; padding: 0.9rem 1rem; border: 1px solid {{ $errors->has('name') ? '#e53e3e' : 'rgba(0,0,0,0.15)' }}; font-family: inherit; font-size: 0.9rem; outline: none;">
                    @error('name')<p style="color: #e53e3e; font-size: 0.78rem; margin-top: 0.3rem;">{{ $message }}</p>@enderror
                </div>

                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">E-mail *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        style="width: 100%; padding: 0.9rem 1rem; border: 1px solid {{ $errors->has('email') ? '#e53e3e' : 'rgba(0,0,0,0.15)' }}; font-family: inherit; font-size: 0.9rem; outline: none;">
                    @error('email')<p style="color: #e53e3e; font-size: 0.78rem; margin-top: 0.3rem;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display: block; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">Telefone</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="(11) 99999-9999"
                        style="width: 100%; padding: 0.9rem 1rem; border: 1px solid rgba(0,0,0,0.15); font-family: inherit; font-size: 0.9rem; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">CPF</label>
                    <input type="text" name="cpf" value="{{ old('cpf') }}" placeholder="000.000.000-00" maxlength="14"
                        style="width: 100%; padding: 0.9rem 1rem; border: 1px solid rgba(0,0,0,0.15); font-family: inherit; font-size: 0.9rem; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">Senha *</label>
                    <input type="password" name="password" required minlength="8"
                        style="width: 100%; padding: 0.9rem 1rem; border: 1px solid {{ $errors->has('password') ? '#e53e3e' : 'rgba(0,0,0,0.15)' }}; font-family: inherit; font-size: 0.9rem; outline: none;">
                    @error('password')<p style="color: #e53e3e; font-size: 0.78rem; margin-top: 0.3rem;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display: block; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">Confirmar Senha *</label>
                    <input type="password" name="password_confirmation" required
                        style="width: 100%; padding: 0.9rem 1rem; border: 1px solid rgba(0,0,0,0.15); font-family: inherit; font-size: 0.9rem; outline: none;">
                </div>
            </div>

            <button type="submit" class="btn btn-dark btn-full" style="margin-top: 0.5rem;">Criar Conta</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; font-size: 0.85rem; color: var(--gray);">
            Já tem conta? <a href="{{ route('login') }}" style="color: #0D0D0D; font-weight: 600; text-decoration: none;">Entrar</a>
        </p>
    </div>
</section>
@endsection

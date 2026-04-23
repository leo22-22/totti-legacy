@extends('layouts.app')
@section('title', 'Esqueci a Senha — Totti Legacy')
@section('content')
<section style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 4rem 1rem;">
    <div style="width: 100%; max-width: 440px;">
        <h1 class="font-serif" style="font-size: 2.2rem; font-weight: 400; text-align: center; margin-bottom: 0.5rem;">Esqueci a Senha</h1>
        <p style="text-align: center; color: var(--gray); font-size: 0.85rem; margin-bottom: 2.5rem;">Enviaremos um link para redefinir sua senha.</p>

        @if(session('success'))
            <div style="background: #1a472a; color: #a3e9b4; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem; font-size: 0.85rem; text-align: center;">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    style="width: 100%; padding: 0.9rem 1rem; border: 1px solid {{ $errors->has('email') ? '#e53e3e' : 'rgba(0,0,0,0.15)' }}; font-family: inherit; font-size: 0.9rem; outline: none;">
                @error('email')<p style="color: #e53e3e; font-size: 0.78rem; margin-top: 0.4rem;">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn btn-dark btn-full">Enviar Link</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; font-size: 0.85rem;">
            <a href="{{ route('login') }}" style="color: var(--gray); text-decoration: none;">← Voltar ao login</a>
        </p>
    </div>
</section>
@endsection

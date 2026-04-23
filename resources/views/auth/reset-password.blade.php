@extends('layouts.app')
@section('title', 'Redefinir Senha — Totti Legacy')
@section('content')
<section style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 4rem 1rem;">
    <div style="width: 100%; max-width: 440px;">
        <h1 class="font-serif" style="font-size: 2.2rem; font-weight: 400; text-align: center; margin-bottom: 0.5rem;">Nova Senha</h1>
        <p style="text-align: center; color: var(--gray); font-size: 0.85rem; margin-bottom: 2.5rem;">Digite sua nova senha abaixo.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">Nova Senha</label>
                <input type="password" name="password" required minlength="8"
                    style="width: 100%; padding: 0.9rem 1rem; border: 1px solid {{ $errors->has('password') ? '#e53e3e' : 'rgba(0,0,0,0.15)' }}; font-family: inherit; font-size: 0.9rem; outline: none;">
                @error('password')<p style="color: #e53e3e; font-size: 0.78rem; margin-top: 0.4rem;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">Confirmar Nova Senha</label>
                <input type="password" name="password_confirmation" required
                    style="width: 100%; padding: 0.9rem 1rem; border: 1px solid rgba(0,0,0,0.15); font-family: inherit; font-size: 0.9rem; outline: none;">
            </div>

            <button type="submit" class="btn btn-dark btn-full">Redefinir Senha</button>
        </form>
    </div>
</section>
@endsection

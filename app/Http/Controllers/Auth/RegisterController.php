<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'cpf'      => 'nullable|string|max:14',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique'      => 'Este e-mail já está cadastrado.',
            'password.min'      => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed'=> 'As senhas não coincidem.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'cpf'      => $request->cpf,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('account.index')->with('success', 'Conta criada com sucesso! Bem-vindo(a)!');
    }
}

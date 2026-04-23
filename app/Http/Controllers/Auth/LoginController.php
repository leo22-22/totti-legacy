<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('account.index'))->with('success', 'Bem-vindo de volta!');
        }

        return back()->withErrors(['email' => 'E-mail ou senha incorretos.'])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Você saiu da sua conta.');
    }
}

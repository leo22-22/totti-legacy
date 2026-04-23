<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $recentOrders = auth()->user()->orders()->latest()->take(5)->get();
        return view('account.index', compact('recentOrders'));
    }

    public function orders()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('account.orders', compact('orders'));
    }

    public function order(string $orderNumber)
    {
        $order = auth()->user()->orders()->where('order_number', $orderNumber)->with('items')->firstOrFail();
        return view('account.order', compact('order'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'cpf'   => 'nullable|string|max:14',
        ]);

        $user->update($request->only('name', 'phone', 'cpf'));

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'As senhas não coincidem.',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Senha alterada com sucesso!');
    }
}

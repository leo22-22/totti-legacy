<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($sq) => $sq
                ->where('order_number', 'like', "%{$q}%")
                ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"))
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function edit(Order $order)
    {
        $order->load('items', 'user');
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status'        => 'required|in:pending,processing,paid,shipped,delivered,cancelled,refunded',
            'tracking_code' => 'nullable|string|max:50',
            'notes'         => 'nullable|string',
        ]);

        $order->update($request->only(['status', 'tracking_code', 'notes']));

        return back()->with('success', 'Pedido atualizado!');
    }

    public function markPaid(Order $order)
    {
        $order->update([
            'status'         => 'paid',
            'payment_status' => 'paid',
            'paid_at'        => now(),
        ]);

        return back()->with('success', 'Pedido marcado como pago.');
    }

    public function markShipped(Request $request, Order $order)
    {
        $request->validate(['tracking_code' => 'required|string|max:50']);

        $order->update([
            'status'        => 'shipped',
            'tracking_code' => $request->tracking_code,
            'shipped_at'    => now(),
        ]);

        try {
            $email = $order->billing_address['email'] ?? $order->user?->email;
            if ($email) {
                Mail::to($email)->send(new OrderShippedMail($order->load('items')));
            }
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail de envio: ' . $e->getMessage());
        }

        return back()->with('success', 'Pedido marcado como enviado. E-mail disparado.');
    }

    public function markDelivered(Order $order)
    {
        $order->update(['status' => 'delivered', 'delivered_at' => now()]);
        return back()->with('success', 'Pedido marcado como entregue.');
    }

    public function cancel(Order $order)
    {
        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Pedido cancelado.');
    }
}

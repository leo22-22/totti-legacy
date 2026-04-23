<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmedMail;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use App\Services\FreightService;
use App\Services\PaymentService;
use App\Services\PixService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService    $cart,
        private PaymentService $payment,
        private FreightService $freight,
        private PixService     $pix,
    ) {}

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Seu carrinho está vazio.');
        }

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Faça login para finalizar sua compra.');
        }

        return view('checkout.index', ['cart' => $this->cart->get()]);
    }

    public function freight(Request $request)
    {
        $request->validate(['cep' => 'required|string|min:8']);

        $subtotal = $this->cart->getSubtotal();
        $options  = $this->freight->calculate($request->cep);

        if ($this->freight->isFreeShipping($subtotal)) {
            foreach ($options as &$opt) {
                $opt['price']         = 0;
                $opt['label_display'] = $opt['service'] . ' — Grátis (' . $opt['days'] . ' dias úteis)';
            }
        } else {
            foreach ($options as &$opt) {
                $opt['label_display'] = $opt['label'] . ' — R$ ' . number_format($opt['price'], 2, ',', '.');
            }
        }

        return response()->json($options);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid()) {
            return back()->with('coupon_error', 'Cupom inválido ou expirado.');
        }

        if ($coupon->minimum_order && $this->cart->getSubtotal() < $coupon->minimum_order) {
            return back()->with('coupon_error', 'Pedido mínimo de R$ ' . number_format($coupon->minimum_order, 2, ',', '.'));
        }

        session(['coupon' => $coupon->id]);
        return back()->with('coupon_success', "Cupom \"{$coupon->code}\" aplicado!");
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('coupon_success', 'Cupom removido.');
    }

    public function process(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Faça login para finalizar sua compra.');
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:20',
            'cpf'            => 'required|string|max:14',
            'cep'            => 'required|string|max:9',
            'address'        => 'required|string|max:255',
            'number'         => 'required|string|max:20',
            'neighborhood'   => 'required|string|max:100',
            'city'           => 'required|string|max:100',
            'state'          => 'required|string|max:2',
            'shipping_option'=> 'required|in:pac,sedex',
            'payment_method' => 'required|in:stripe,mercadopago,pix',
        ]);

        $cartItems = $this->cart->get();
        if (empty($cartItems)) {
            return back()->with('error', 'Seu carrinho está vazio.');
        }

        // Verifica estoque antes de criar o pedido
        foreach ($cartItems as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->stock < $item['quantity']) {
                return back()->with('error', "Produto \"{$item['name']}\" não tem estoque suficiente.");
            }
        }

        $subtotal = $this->cart->getSubtotal();

        // Cupom
        $coupon   = null;
        $discount = 0;
        if (session('coupon')) {
            $coupon = Coupon::find(session('coupon'));
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        // Frete
        $freightOptions = $this->freight->calculate($request->cep);
        $selectedFreight = collect($freightOptions)->firstWhere('code', $request->shipping_option);
        $shipping = $this->freight->isFreeShipping($subtotal) || ($coupon && $coupon->free_shipping)
            ? 0
            : ($selectedFreight['price'] ?? 19.90);

        $total = max(0, $subtotal - $discount + $shipping);

        $shippingAddress = [
            'name'         => $request->name,
            'cep'          => preg_replace('/\D/', '', $request->cep),
            'street'       => $request->address,
            'number'       => $request->number,
            'complement'   => $request->complement,
            'neighborhood' => $request->neighborhood,
            'city'         => $request->city,
            'state'        => $request->state,
        ];

        $billingAddress = array_merge($shippingAddress, [
            'email' => $request->email,
            'phone' => $request->phone,
            'cpf'   => $request->cpf,
        ]);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id'          => auth()->id(),
                'coupon_id'        => $coupon?->id,
                'status'           => 'pending',
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'shipping'         => $shipping,
                'total'            => $total,
                'coupon_code'      => $coupon?->code,
                'payment_method'   => $request->payment_method,
                'payment_status'   => 'pending',
                'billing_address'  => $billingAddress,
                'shipping_address' => $shippingAddress,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $item['product_id'],
                    'product_name'=> $item['name'],
                    'product_sku' => $item['sku'],
                    'size'        => $item['size'],
                    'color'       => $item['color'],
                    'image'       => $item['image'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                // Decrementa estoque e desativa se zerar
                Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
            }

            if ($coupon) {
                $coupon->increment('times_used');
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id'   => auth()->id(),
                    'order_id'  => $order->id,
                    'email'     => $request->email,
                ]);
            }

            $paymentResult = $this->payment->process($order, $request->payment_method, $request->all());

            // PIX: gera payload e QR Code
            if ($request->payment_method === 'pix') {
                $pixKey     = config('services.pix.key', 'contato@tottilegacy.com.br');
                $pixPayload = $this->pix->generatePayload($pixKey, 'email', $total, $order->order_number);
                $paymentResult['pix_payload'] = $pixPayload;
                $paymentResult['pix_qr_url']  = $this->pix->generateQrCodeUrl($pixPayload);
            }

            DB::commit();

            $this->cart->clear();
            session()->forget('coupon');

            // E-mail de confirmação (PIX e Stripe — MercadoPago confirma via webhook)
            if (in_array($request->payment_method, ['pix', 'stripe'])) {
                try {
                    Mail::to($request->email)->send(new OrderConfirmedMail($order->load('items')));
                } catch (\Exception $e) {
                    Log::error('Erro ao enviar e-mail: ' . $e->getMessage());
                }
            }

            if ($paymentResult['redirect_url'] ?? null) {
                return redirect($paymentResult['redirect_url']);
            }

            return redirect()->route('checkout.success', $order->order_number)
                ->with('payment_data', $paymentResult);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro no checkout: ' . $e->getMessage());
            return back()->with('error', 'Erro ao processar pedido. Tente novamente.');
        }
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where(function ($q) {
                $q->where('user_id', auth()->id())->orWhereNull('user_id');
            })
            ->with('items')
            ->firstOrFail();

        $paymentData = session('payment_data', []);

        return view('checkout.success', compact('order', 'paymentData'));
    }
}

<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentService
{
    public function process(Order $order, string $method, array $data): array
    {
        return match($method) {
            'stripe' => $this->processStripe($order, $data),
            'mercadopago' => $this->processMercadoPago($order, $data),
            'pix' => $this->processPix($order),
            default => throw new \InvalidArgumentException("Método de pagamento inválido: {$method}"),
        };
    }

    private function processStripe(Order $order, array $data): array
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount' => (int) ($order->total * 100),
            'currency' => 'brl',
            'metadata' => [
                'order_number' => $order->order_number,
                'order_id' => $order->id,
            ],
            'description' => "Totti Legacy - Pedido {$order->order_number}",
        ]);

        $order->update([
            'payment_id' => $intent->id,
            'payment_status' => 'pending',
        ]);

        return [
            'client_secret' => $intent->client_secret,
            'payment_intent_id' => $intent->id,
            'method' => 'stripe',
        ];
    }

    private function processMercadoPago(Order $order, array $data): array
    {
        $accessToken = config('services.mercadopago.access_token');
        $billingAddress = $order->billing_address;

        $preference = [
            'items' => $order->items->map(fn ($item) => [
                'title' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'currency_id' => 'BRL',
            ])->toArray(),
            'payer' => [
                'name' => $billingAddress['name'],
                'email' => $billingAddress['email'],
                'identification' => [
                    'type' => 'CPF',
                    'number' => preg_replace('/\D/', '', $billingAddress['cpf']),
                ],
            ],
            'external_reference' => $order->order_number,
            'back_urls' => [
                'success' => route('checkout.success', $order->order_number),
                'failure' => route('checkout.index'),
                'pending' => route('checkout.success', $order->order_number),
            ],
            'auto_return' => 'approved',
            'notification_url' => route('webhooks.mercadopago'),
        ];

        $ch = curl_init('https://api.mercadopago.com/checkout/preferences');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer {$accessToken}",
            ],
            CURLOPT_POSTFIELDS => json_encode($preference),
        ]);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (isset($response['init_point'])) {
            $order->update(['payment_id' => $response['id']]);
            return ['redirect_url' => $response['init_point']];
        }

        throw new \Exception('Erro ao criar preferência no MercadoPago.');
    }

    private function processPix(Order $order): array
    {
        $pixKey = config('services.pix.key', 'contato@tottilegacy.com.br');

        $order->update(['payment_status' => 'pending']);

        return [
            'method' => 'pix',
            'pix_key' => $pixKey,
            'pix_key_type' => 'email',
            'amount' => $order->total,
            'order_number' => $order->order_number,
        ];
    }
}

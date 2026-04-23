<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function stripe(Request $request)
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');
        $secret  = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig, $secret);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;
            $order  = Order::where('payment_id', $intent->id)->first();

            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'status'         => 'processing',
                    'payment_status' => 'paid',
                    'paid_at'        => now(),
                ]);
                $this->sendConfirmationEmail($order);
            }
        }

        if ($event->type === 'payment_intent.payment_failed') {
            $intent = $event->data->object;
            $order  = Order::where('payment_id', $intent->id)->first();
            $order?->update(['payment_status' => 'failed']);
        }

        return response()->json(['status' => 'ok']);
    }

    public function mercadopago(Request $request)
    {
        $type = $request->input('type');

        if ($type !== 'payment') {
            return response()->json(['status' => 'ignored']);
        }

        $paymentId   = $request->input('data.id');
        $accessToken = config('services.mercadopago.access_token');

        $ch = curl_init("https://api.mercadopago.com/v1/payments/{$paymentId}");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ["Authorization: Bearer {$accessToken}"],
        ]);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (!isset($response['external_reference'])) {
            return response()->json(['status' => 'no_reference']);
        }

        $order = Order::where('order_number', $response['external_reference'])->first();

        if (!$order) {
            return response()->json(['status' => 'order_not_found']);
        }

        if ($response['status'] === 'approved' && $order->payment_status !== 'paid') {
            $order->update([
                'status'         => 'processing',
                'payment_status' => 'paid',
                'payment_id'     => (string) $paymentId,
                'paid_at'        => now(),
            ]);
            $this->sendConfirmationEmail($order);
        }

        if (in_array($response['status'], ['rejected', 'cancelled'])) {
            $order->update(['payment_status' => 'failed']);
        }

        return response()->json(['status' => 'ok']);
    }

    private function sendConfirmationEmail(Order $order): void
    {
        try {
            $email = $order->billing_address['email'] ?? $order->user?->email;
            if ($email) {
                Mail::to($email)->send(new OrderConfirmedMail($order->load('items')));
            }
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail de confirmação: ' . $e->getMessage());
        }
    }
}

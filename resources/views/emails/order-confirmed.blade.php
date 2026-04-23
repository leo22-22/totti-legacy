<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pedido Confirmado</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:40px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;max-width:600px;width:100%;">

      {{-- Header --}}
      <tr><td style="background:#0D0D0D;padding:32px 40px;text-align:center;">
        <p style="color:#C9A84C;font-size:22px;font-weight:700;letter-spacing:4px;margin:0;">TOTTI LEGACY</p>
      </td></tr>

      {{-- Body --}}
      <tr><td style="padding:40px;">
        <h1 style="font-size:24px;font-weight:400;color:#0D0D0D;margin:0 0 8px;">Pedido Confirmado! ✅</h1>
        <p style="color:#666;font-size:15px;margin:0 0 32px;">Olá, {{ $order->billing_address['name'] ?? 'cliente' }}! Recebemos seu pedido e já estamos preparando tudo com carinho.</p>

        <div style="background:#f9f9f9;padding:20px;margin-bottom:24px;">
          <p style="margin:0 0 8px;font-size:13px;color:#999;text-transform:uppercase;letter-spacing:1px;">Número do Pedido</p>
          <p style="margin:0;font-size:20px;font-weight:700;color:#0D0D0D;">{{ $order->order_number }}</p>
        </div>

        {{-- Itens --}}
        <h2 style="font-size:14px;text-transform:uppercase;letter-spacing:2px;color:#0D0D0D;margin:0 0 16px;">Itens</h2>
        @foreach($order->items as $item)
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #eee;">
          <div>
            <p style="margin:0;font-weight:600;font-size:14px;color:#0D0D0D;">{{ $item->product_name }}</p>
            <p style="margin:4px 0 0;font-size:12px;color:#999;">Tam: {{ $item->size }} | Cor: {{ $item->color }} | Qtd: {{ $item->quantity }}</p>
          </div>
          <p style="margin:0;font-weight:600;font-size:14px;color:#0D0D0D;">R$ {{ number_format($item->total_price, 2, ',', '.') }}</p>
        </div>
        @endforeach

        {{-- Totais --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:24px;">
          <tr><td style="padding:6px 0;font-size:14px;color:#666;">Subtotal</td><td align="right" style="font-size:14px;">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td></tr>
          @if($order->discount > 0)
          <tr><td style="padding:6px 0;font-size:14px;color:#276749;">Desconto</td><td align="right" style="font-size:14px;color:#276749;">− R$ {{ number_format($order->discount, 2, ',', '.') }}</td></tr>
          @endif
          <tr><td style="padding:6px 0;font-size:14px;color:#666;">Frete</td><td align="right" style="font-size:14px;">{{ $order->shipping > 0 ? 'R$ ' . number_format($order->shipping, 2, ',', '.') : 'Grátis' }}</td></tr>
          <tr><td style="padding:12px 0 0;font-size:16px;font-weight:700;border-top:2px solid #eee;">Total</td><td align="right" style="padding:12px 0 0;font-size:16px;font-weight:700;border-top:2px solid #eee;">R$ {{ number_format($order->total, 2, ',', '.') }}</td></tr>
        </table>

        <div style="text-align:center;margin:40px 0;">
          <a href="{{ route('account.order', $order->order_number) }}" style="background:#0D0D0D;color:#fff;text-decoration:none;padding:14px 32px;font-size:13px;letter-spacing:2px;text-transform:uppercase;font-weight:600;display:inline-block;">Ver Pedido</a>
        </div>

        <p style="font-size:13px;color:#999;text-align:center;">Dúvidas? Entre em contato: contato@tottilegacy.com.br</p>
      </td></tr>

      {{-- Footer --}}
      <tr><td style="background:#0D0D0D;padding:24px;text-align:center;">
        <p style="color:#555;font-size:12px;margin:0;">© {{ date('Y') }} Totti Legacy. Todos os direitos reservados.</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>

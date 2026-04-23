<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pedido Enviado</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:40px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;max-width:600px;width:100%;">

      <tr><td style="background:#0D0D0D;padding:32px 40px;text-align:center;">
        <p style="color:#C9A84C;font-size:22px;font-weight:700;letter-spacing:4px;margin:0;">TOTTI LEGACY</p>
      </td></tr>

      <tr><td style="padding:40px;">
        <h1 style="font-size:24px;font-weight:400;color:#0D0D0D;margin:0 0 8px;">Seu pedido está a caminho! 🚚</h1>
        <p style="color:#666;font-size:15px;margin:0 0 32px;">Olá, {{ $order->billing_address['name'] ?? 'cliente' }}! Seu pedido foi enviado pelos Correios.</p>

        <div style="background:#f0fff4;border:1px solid #9ae6b4;padding:24px;margin-bottom:32px;text-align:center;">
          <p style="margin:0 0 8px;font-size:13px;color:#276749;text-transform:uppercase;letter-spacing:1px;">Código de Rastreio</p>
          <p style="margin:0;font-size:28px;font-weight:700;color:#0D0D0D;letter-spacing:4px;">{{ $order->tracking_code }}</p>
          <a href="https://rastreamento.correios.com.br/app/index.php" target="_blank" style="display:inline-block;margin-top:16px;background:#276749;color:#fff;text-decoration:none;padding:10px 24px;font-size:12px;letter-spacing:1px;text-transform:uppercase;">Rastrear Pedido</a>
        </div>

        <div style="background:#f9f9f9;padding:20px;margin-bottom:24px;">
          <p style="margin:0 0 4px;font-size:12px;color:#999;text-transform:uppercase;letter-spacing:1px;">Pedido</p>
          <p style="margin:0;font-size:16px;font-weight:700;">{{ $order->order_number }}</p>
        </div>

        <div style="text-align:center;margin:32px 0;">
          <a href="{{ route('account.order', $order->order_number) }}" style="background:#0D0D0D;color:#fff;text-decoration:none;padding:14px 32px;font-size:13px;letter-spacing:2px;text-transform:uppercase;font-weight:600;display:inline-block;">Ver Pedido</a>
        </div>

        <p style="font-size:13px;color:#999;text-align:center;">Prazo estimado: 5 a 12 dias úteis conforme sua região.</p>
      </td></tr>

      <tr><td style="background:#0D0D0D;padding:24px;text-align:center;">
        <p style="color:#555;font-size:12px;margin:0;">© {{ date('Y') }} Totti Legacy. Todos os direitos reservados.</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>

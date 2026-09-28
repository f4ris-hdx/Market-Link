<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Received</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f5f5f0; margin: 0; padding: 32px 16px; color: #1c1c1c;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e9dcc0;">
        <div style="background: linear-gradient(135deg, #111111, #1d1d1d); color: #f5d57a; padding: 24px 30px; font-size: 20px; font-weight: 700;">
            {{ config('app.name') }}
        </div>
        <div style="padding: 30px;">
            <h2 style="margin-top: 0; color: #111111;">Order Received</h2>
            <p>Hi {{ $order->user->name ?? 'Customer' }},</p>
            <p>We have received your order <strong>{{ $order->order_number }}</strong>.</p>
            <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
            <p>Your farmer will review and confirm the order soon.</p>
            <p style="margin: 0;">Best regards,<br>{{ config('app.name') }} Team</p>
        </div>
    </div>
</body>
</html>

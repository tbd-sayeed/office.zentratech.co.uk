<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Confirmation</title>
</head>
<body>
    <h1>Payment Confirmation</h1>
    <p>Dear {{ $client->contact_person }},</p>
    <p>We have received your payment of <strong>${{ number_format($payment->amount, 2) }}</strong> for the service <strong>{{ $service->service_name }}</strong>.</p>
    <p>Payment Date: {{ $payment->payment_date->format('M d, Y') }}</p>
    @if($payment->transaction_reference)
    <p>Transaction Reference: {{ $payment->transaction_reference }}</p>
    @endif
    <p>Thank you for your payment.</p>
    <p>Best regards,<br>ZentraTech Team</p>
</body>
</html>


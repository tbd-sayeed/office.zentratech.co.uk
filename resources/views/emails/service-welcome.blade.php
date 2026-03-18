<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Service Activated</title>
</head>
<body>
    <h1>Service Activated</h1>
    <p>Dear {{ $client->contact_person }},</p>
    <p>Your service <strong>{{ $service->service_name }}</strong> has been activated.</p>
    @if($service->expiration_date)
    <p>Expiration Date: {{ $service->expiration_date->format('M d, Y') }}</p>
    @endif
    <p>Best regards,<br>ZentraTech Team</p>
</body>
</html>


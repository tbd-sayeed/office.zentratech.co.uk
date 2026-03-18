<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Renewal Reminder</title>
</head>
<body>
    <h1>Renewal Reminder</h1>
    <p>Dear {{ $client->contact_person }},</p>
    <p>This is a reminder that your service <strong>{{ $service->service_name }}</strong> will expire in {{ $daysUntilExpiry }} days.</p>
    <p>Expiration Date: {{ $service->expiration_date->format('M d, Y') }}</p>
    <p>Please renew your service to avoid any interruption.</p>
    <p>Best regards,<br>ZentraTech Team</p>
</body>
</html>


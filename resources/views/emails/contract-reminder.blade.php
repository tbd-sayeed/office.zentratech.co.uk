<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contract Ending Soon</title>
</head>
<body>
    <h1>Service Contract Ending Soon</h1>
    <p>Dear {{ $client->contact_person }},</p>
    <p>Your service contract for <strong>{{ $service->service_name }}</strong> will end in {{ $daysUntilExpiry }} days.</p>
    <p>Contract End Date: {{ $service->contract_end_date->format('M d, Y') }}</p>
    <p><strong>Note:</strong> After contract expiry, all future services will require pre-payment.</p>
    <p>Best regards,<br>ZentraTech Team</p>
</body>
</html>


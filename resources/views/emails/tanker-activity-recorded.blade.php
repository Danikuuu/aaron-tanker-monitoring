<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanker Activity Notification</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.5;">
    <h2 style="margin-bottom: 8px;">Tanker {{ ucfirst($activityType) }} Recorded</h2>
    <p style="margin-top: 0; color: #4B5563;">
        A new tanker {{ $activityType }} was recorded by staff.
    </p>

    <table cellpadding="6" cellspacing="0" border="0" style="border-collapse: collapse; margin-top: 12px;">
        <tr>
            <td><strong>Tanker Number:</strong></td>
            <td>{{ $tankerNumber }}</td>
        </tr>
        <tr>
            <td><strong>{{ $activityType === 'arrival' ? 'Arrival' : 'Departure' }} Date:</strong></td>
            <td>{{ $recordedDate }}</td>
        </tr>
        <tr>
            <td><strong>Recorded By:</strong></td>
            <td>{{ $recordedBy }}</td>
        </tr>
    </table>

    @if(!empty($fuelBreakdown))
        <h3 style="margin-top: 18px; margin-bottom: 8px;">Fuel Breakdown</h3>
        <table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; border-color: #E5E7EB; min-width: 320px;">
            <thead style="background: #F9FAFB;">
                <tr>
                    <th align="left">Fuel Type</th>
                    <th align="right">Liters</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fuelBreakdown as $fuel)
                    <tr>
                        <td style="text-transform: capitalize;">{{ $fuel['fuel_type'] ?? '-' }}</td>
                        <td align="right">{{ number_format((float) ($fuel['liters'] ?? 0), 2) }} L</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p style="margin-top: 18px; color: #6B7280; font-size: 12px;">
        This is an automated system notification.
    </p>
</body>
</html>

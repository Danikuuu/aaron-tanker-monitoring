<!DOCTYPE html>
<html>
<head>
    <title>Analytics PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 4px; text-align: left; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h3>{{ ucfirst($type) }} Analytics ({{ ucfirst($period) }})</h3>
    <table>
        <thead>
            <tr>
                @if($type === 'arrival')
                    <th>ID</th>
                    <th>Tanker No.</th>
                    <th>Arrival Date</th>
                    <th>Recorded By</th>
                    <th>Fuel Type</th>
                    <th>Liters</th>
                @else
                    <th>ID</th>
                    <th>Tanker No.</th>
                    <th>Driver</th>
                    <th>Departure Date</th>
                    <th>Recorded By</th>
                    <th>Fuel Type</th>
                    <th>Liters</th>
                    <th>Methanol %</th>
                    <th>Methanol L</th>
                    <th>Pure L</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach((array) $row as $val)
                        <td>{{ $val }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

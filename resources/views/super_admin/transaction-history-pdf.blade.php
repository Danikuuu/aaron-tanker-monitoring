<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #ccc; padding:6px; font-size:12px; }
        th { background:#eee; }
    </style>
    <title>Transaction History</title>
</head>
<body>
    <h3>Transaction History</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Tanker No.</th>
                <th>Driver</th>
                <th>Date</th>
                <th>Recorded By</th>
                <th>Fuel Type</th>
                <th>Liters</th>
                <th>Methanol %</th>
                <th>Methanol L</th>
            </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ ucfirst($row->type) }}</td>
                <td>{{ $row->tanker_number }}</td>
                <td>{{ $row->driver ?? '—' }}</td>
                <td>{{ \Carbon\Carbon::parse($row->transaction_date)->format('m/d/Y') }}</td>
                <td>{{ $row->recorded_by }}</td>
                <td>{{ $row->fuel_type ?? '—' }}</td>
                <td>{{ isset($row->liters) ? number_format($row->liters,2) : '—' }}</td>
                <td>{{ $row->methanol_percent ?? '—' }}</td>
                <td>{{ $row->methanol_liters ?? '—' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>

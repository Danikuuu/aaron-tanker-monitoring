<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #ccc; padding:6px; font-size:12px; }
        th { background:#eee; }
    </style>
    <title>Fuel Arrivals</title>
</head>
<body>
    <h3>Fuel Arrivals</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanker No.</th>
                <th>Arrival Date</th>
                <th>Recorded By</th>
                <th>Fuel Type</th>
                <th>Liters</th>
            </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->tanker_number }}</td>
                <td>{{ \Carbon\Carbon::parse($row->arrival_date)->format('m/d/Y') }}</td>
                <td>{{ $row->recorded_by }}</td>
                <td>{{ ucfirst($row->fuel_type) }}</td>
                <td>{{ number_format($row->liters, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>

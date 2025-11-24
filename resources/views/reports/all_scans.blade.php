<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Scan Reports</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #aaa; }
        th, td { padding: 6px; }
        h1 { margin-bottom: 20px; }
    </style>
</head>
<body>

<h1>All Scan Reports</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Target</th>
            <th>Mode</th>
            <th>Features</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($scans as $scan)
            <tr>
                <td>{{ $scan->id }}</td>
                <td>{{ $scan->user_id }}</td>
                <td>{{ $scan->target }}</td>
                <td>{{ $scan->scan_mode }}</td>
                <td>{{ implode(', ', $scan->features ?? []) }}</td>
                <td>{{ $scan->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

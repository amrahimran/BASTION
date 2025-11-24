<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Scan Report #{{ $scan->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1, h2, h3 { margin: 0; padding: 6px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table, th, td { border: 1px solid #aaa; }
        th, td { padding: 6px; text-align: left; }
        .section { margin-top: 20px; }
        .raw-output { white-space: pre-wrap; font-family: monospace; }
    </style>
</head>
<body>

<h1>Scan Report #{{ $scan->id }}</h1>
<p><strong>Generated:</strong> {{ now() }}</p>

<hr>

<h2>Scan Summary</h2>
<table>
    <tr><th>Scan ID</th><td>{{ $scan->id }}</td></tr>
    <tr><th>Target</th><td>{{ $scan->target ?? 'Auto-detected' }}</td></tr>
    <tr><th>Scan Mode</th><td>{{ ucfirst($scan->scan_mode) }}</td></tr>
    <tr><th>Features</th><td>{{ implode(', ', $scan->features ?? []) }}</td></tr>
    <tr><th>Run At</th><td>{{ $scan->created_at }}</td></tr>
</table>

@if(!empty($scan->parsed_results['hosts']))
<h2>Hosts</h2>
<table>
    <thead>
        <tr>
            <th>IP Address</th>
            <th>MAC</th>
            <th>Vendor</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($scan->parsed_results['hosts'] as $host)
            <tr>
                <td>{{ $host['ip'] ?? '-' }}</td>
                <td>{{ $host['mac'] ?? '-' }}</td>
                <td>{{ $host['vendor'] ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif

@if(!empty($scan->parsed_results['ports']))
<h2>Open Ports</h2>
<table>
    <thead>
        <tr>
            <th>Port</th>
            <th>Service</th>
            <th>Version</th>
            <th>State</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($scan->parsed_results['ports'] as $port)
            <tr>
                <td>{{ $port['port'] ?? '-' }}</td>
                <td>{{ $port['service'] ?? '-' }}</td>
                <td>{{ $port['version'] ?? '-' }}</td>
                <td>{{ $port['state'] ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif

<h2>Raw Output</h2>
<div class="raw-output">
    {{ $scan->raw_output ?? 'No raw output available.' }}
</div>

</body>
</html>

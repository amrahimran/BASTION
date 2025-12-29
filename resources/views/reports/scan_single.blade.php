<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Scan Report #{{ $scan->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1, h2 { margin: 0; padding: 6px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table, th, td { border: 1px solid #aaa; }
        th, td { padding: 6px; text-align: left; }
        .ai-summary { white-space: pre-wrap; font-family: sans-serif; background: #f4f4f4; padding: 8px; border-radius: 4px; }
    </style>
</head>
<body>

<h1>Scan Report #{{ $scan->id }}</h1>
<p><strong>Generated:</strong> {{ now() }}</p>

<hr>

<h2>Scan Summary</h2>
<table>
    <tr><th>Scan ID</th><td>{{ $scan->id }}</td></tr>
    <tr><th>User</th><td>{{ $scan->user->name ?? 'Unknown User' }}</td></tr>
    <tr><th>User Email</th><td>{{ $scan->user->email ?? '-' }}</td></tr>
    <tr><th>Target</th><td>{{ $scan->target ?? 'Auto-detected' }}</td></tr>
    <tr><th>Scan Mode</th><td>{{ ucfirst($scan->scan_mode) }}</td></tr>
    <tr><th>Features</th><td>{{ implode(', ', $scan->features ?? []) }}</td></tr>
    <tr><th>Run At</th><td>{{ $scan->created_at }}</td></tr>
</table>

{{-- <h2>Raw Output</h2>
<div class="raw-output">
    {{ $scan->raw_output ?? 'No raw output available.' }}
</div> --}}

<h2>AI Summary</h2>
<div class="ai-summary">
    {!! $scan->ai_summary ?? 'No AI summary available.' !!}
</div>

</body>
</html>

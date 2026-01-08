<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Security Scan Report #{{ $scan->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            background: #eef2f7;
            color: #1f2937;
            margin: 0;
            padding: 24px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 6px;
            color: #0f172a;
        }

        h2 {
            font-size: 16px;
            margin: 18px 0 10px;
            color: #0f766e;
            border-bottom: 2px solid #d1fae5;
            padding-bottom: 4px;
        }

        .subtitle {
            font-size: 11px;
            color: #475569;
            margin-bottom: 14px;
        }

        .card {
            background: #ffffff;
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 7px;
            border-bottom: 1px solid #e5e7eb;
        }

        td.label {
            width: 30%;
            font-weight: bold;
            color: #374151;
            background: #f9fafb;
        }

        .risk-box {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 13px;
            font-weight: bold;
        }

        .risk-high {
            background: #fee2e2;
            border-left: 8px solid #dc2626;
            color: #7f1d1d;
        }

        .risk-medium {
            background: #fef3c7;
            border-left: 8px solid #f59e0b;
            color: #78350f;
        }

        .risk-low {
            background: #dcfce7;
            border-left: 8px solid #16a34a;
            color: #065f46;
        }

        .ai-text p {
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #64748b;
            margin-top: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            background: #0f766e;
            color: white;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<h1>Network Security Scan Report</h1>
<div class="subtitle">
    Scan ID: <strong>{{ $scan->id }}</strong> |
    Generated: {{ now() }} |
    Scan Mode: <span class="badge">{{ ucfirst($scan->scan_mode) }}</span>
</div>

<!-- SCAN DETAILS -->
<div class="card">
    <h2>Scan Overview</h2>
    <table>
        <tr>
            <td class="label">User</td>
            <td>{{ $scan->user->name ?? 'Unknown User' }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td>{{ $scan->user->email ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Target</td>
            <td>{{ $scan->target ?? 'Automatically detected network devices' }}</td>
        </tr>
        <tr>
            <td class="label">Scan Features</td>
            <td>{{ implode(', ', $scan->features ?? []) }}</td>
        </tr>
        <tr>
            <td class="label">Executed At</td>
            <td>{{ $scan->created_at }}</td>
        </tr>
    </table>
</div>

<!-- AI SUMMARY -->
<div class="card">
    <h2>AI Security Assessment</h2>

    @php $summary = $scan->ai_summary ?? 'No AI summary available.'; @endphp

    @if(stripos($summary, 'HIGH RISK') !== false)
        <div class="risk-box risk-high">HIGH RISK — Immediate attention required</div>
    @elseif(stripos($summary, 'MEDIUM RISK') !== false)
        <div class="risk-box risk-medium">MEDIUM RISK — Review recommended</div>
    @else
        <div class="risk-box risk-low">LOW RISK — No immediate threats detected</div>
    @endif

    <div class="ai-text">
        {!! $summary !!}
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    This document was generated automatically by the Bastion Cybersecurity System.
    <br>
    For awareness, educational, and internal assessment purposes only.
</div>

</body>
</html>

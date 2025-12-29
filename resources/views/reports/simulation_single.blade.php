<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simulation Report #{{ $simulation->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background: #ffffff;
            color: #000;
            margin: 30px;
        }

        h1 {
            color: #0b1d2a;
            border-bottom: 2px solid #0b1d2a;
            padding-bottom: 5px;
        }

        h2 {
            color: #102635;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        td {
            padding: 8px 10px;
            border: 1px solid #ccc;
            font-size: 13px;
        }

        td.label {
            background: #f1f5f9;
            font-weight: bold;
            width: 40%;
        }

        .section {
            margin-top: 25px;
        }

        .risk {
            font-weight: bold;
        }

        .risk-high { color: #dc2626; }
        .risk-medium { color: #ca8a04; }
        .risk-low { color: #16a34a; }
    </style>
</head>
<body>

<h1>Simulation Report #{{ $simulation->id }}</h1>

<table>
    <tr>
        <td class="label">Simulation Type</td>
        <td>{{ strtoupper($simulation->simulation_type) }}</td>
    </tr>
    <tr>
        <td class="label">Run By</td>
        <td>{{ $simulation->user?->name ?? 'Unknown User' }} (ID: {{ $simulation->user_id }})</td>
    </tr>
    <tr>
        <td class="label">Run Date</td>
        <td>{{ $simulation->created_at->format('Y-m-d H:i:s') }}</td>
    </tr>
</table>

{{-- ================= OUTPUT SECTION ================= --}}
<div class="section">
    <h2>Simulation Output</h2>

    <table>
        {{-- MITM --}}
        @if($simulation->simulation_type === 'MITM')
            <tr>
                <td class="label">Intercepted Packets</td>
                <td>{{ $simulation->intercepted_packets }}</td>
            </tr>
            <tr>
                <td class="label">Credentials Exposed</td>
                <td>{{ $simulation->exposed_credentials }}</td>
            </tr>

        {{-- DDOS --}}
        @elseif($simulation->simulation_type === 'DDOS')
            <tr>
                <td class="label">Target System</td>
                <td>{{ $simulation->target }}</td>
            </tr>
            <tr>
                <td class="label">Attack Strength</td>
                <td>{{ $simulation->ddos_mode }}</td>
            </tr>
            <tr>
                <td class="label">Requests per Second</td>
                <td>{{ $simulation->request_rate }}</td>
            </tr>
            <tr>
                <td class="label">Total Requests</td>
                <td>{{ $simulation->total_requests }}</td>
            </tr>

        {{-- PHISHING --}}
        @elseif($simulation->simulation_type === 'PHISHING')
            <tr>
                <td class="label">Emails Sent</td>
                <td>{{ $simulation->emails_sent }}</td>
            </tr>
            <tr>
                <td class="label">Links Clicked</td>
                <td>{{ $simulation->clicked_links }}</td>
            </tr>
            <tr>
                <td class="label">Details Entered</td>
                <td>{{ $simulation->entered_details }}</td>
            </tr>
        @endif
    </table>
</div>

{{-- ================= RISK ================= --}}
<div class="section">
    <h2>Risk Assessment</h2>

    <p class="risk
        {{ $simulation->risk_level === 'High' ? 'risk-high' :
           ($simulation->risk_level === 'Medium' ? 'risk-medium' : 'risk-low') }}">
        {{ $simulation->risk_level }}
    </p>
</div>

{{-- ================= AI EXPLANATION ================= --}}
@if($simulation->ai_explanation)
<div class="section">
    <h2>AI Security Explanation</h2>
    <p>{!! $simulation->ai_explanation !!}</p>
</div>
@endif

</body>
</html>

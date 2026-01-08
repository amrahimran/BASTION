<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simulation Report #{{ $simulation->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            background: #ffffff;
            color: #1f2937;
            margin: 30px;
        }

        /* Header */
        .header {
            border-bottom: 3px solid #0b1d2a;
            margin-bottom: 25px;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #0b1d2a;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #64748b;
        }

        /* Section */
        .section {
            margin-top: 30px;
        }

        .section h2 {
            font-size: 16px;
            color: #102635;
            margin-bottom: 10px;
            border-left: 4px solid #00c3b3;
            padding-left: 8px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            font-size: 12px;
        }

        td.label {
            background: #f8fafc;
            font-weight: bold;
            width: 40%;
            color: #0f172a;
        }

        /* Risk Box */
        .risk-box {
            display: inline-block;
            padding: 8px 14px;
            font-weight: bold;
            border-radius: 6px;
            font-size: 13px;
            margin-top: 5px;
        }

        .risk-high {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        .risk-medium {
            background: #fef9c3;
            color: #854d0e;
            border: 1px solid #eab308;
        }

        .risk-low {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #22c55e;
        }

        /* AI Explanation */
        .ai-box {
            background: #f8fafc;
            border-left: 4px solid #6366f1;
            padding: 12px 14px;
            border-radius: 4px;
            line-height: 1.6;
            color: #1e293b;
            font-size: 12px;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Bastion Cybersecurity – Simulation Report</h1>
        <p>Simulation ID #{{ $simulation->id }} • Generated {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <!-- Basic Info -->
    <div class="section">
        <h2>Simulation Details</h2>

        <table>
            <tr>
                <td class="label">Simulation Type</td>
                <td>{{ strtoupper($simulation->simulation_type) }}</td>
            </tr>
            <tr>
                <td class="label">Run By</td>
                <td>{{ $simulation->user?->name ?? 'Unknown User' }} (User ID: {{ $simulation->user_id }})</td>
            </tr>
            <tr>
                <td class="label">Run Date</td>
                <td>{{ $simulation->created_at->format('d M Y, H:i:s') }}</td>
            </tr>
        </table>
    </div>

    <!-- Simulation Output -->
    <div class="section">
        <h2>Simulation Output</h2>

        <table>
            @if($simulation->simulation_type === 'MITM')
                <tr>
                    <td class="label">Intercepted Network Packets</td>
                    <td>{{ $simulation->intercepted_packets }}</td>
                </tr>
                <tr>
                    <td class="label">Credentials Exposed</td>
                    <td>{{ $simulation->exposed_credentials }}</td>
                </tr>

            @elseif($simulation->simulation_type === 'DDOS')
                <tr>
                    <td class="label">Target System</td>
                    <td>{{ $simulation->target }}</td>
                </tr>
                <tr>
                    <td class="label">Attack Strength</td>
                    <td>{{ ucfirst($simulation->ddos_mode) }}</td>
                </tr>
                <tr>
                    <td class="label">Requests per Second</td>
                    <td>{{ $simulation->request_rate }}</td>
                </tr>
                <tr>
                    <td class="label">Total Requests Sent</td>
                    <td>{{ $simulation->total_requests }}</td>
                </tr>

            @elseif($simulation->simulation_type === 'PHISHING')
                <tr>
                    <td class="label">Emails Shown</td>
                    <td>{{ $simulation->emails_sent }}</td>
                </tr>
                <tr>
                    <td class="label">Links Clicked</td>
                    <td>{{ $simulation->clicked_links }}</td>
                </tr>
                <tr>
                    <td class="label">Fake Login Details Entered</td>
                    <td>{{ $simulation->entered_details }}</td>
                </tr>
            @endif
        </table>
    </div>

    <!-- Risk -->
    <div class="section">
        <h2>Risk Assessment</h2>

        <div class="risk-box
            {{ $simulation->risk_level === 'High' ? 'risk-high' :
               ($simulation->risk_level === 'Medium' ? 'risk-medium' : 'risk-low') }}">
            {{ strtoupper($simulation->risk_level) }} RISK
        </div>
    </div>

    <!-- AI Explanation -->
    @if($simulation->ai_explanation)
        <div class="section">
            <h2>AI Security Explanation</h2>

            <div class="ai-box">
                {!! $simulation->ai_explanation !!}
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        Generated by Bastion Cybersecurity Platform • Academic Simulation Report
    </div>

</body>
</html>

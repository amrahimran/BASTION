<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Simulation Reports</title>

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        background: #ffffff;
        color: #1f2937;
        margin: 25px;
    }

    /* Page Header */
    .page-header {
        border-bottom: 3px solid #0b1d2a;
        padding-bottom: 10px;
        margin-bottom: 30px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 22px;
        color: #0b1d2a;
    }

    .page-header p {
        margin: 4px 0 0;
        font-size: 11px;
        color: #64748b;
    }

    /* Simulation Card */
    .simulation-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 30px;
        page-break-inside: avoid;
    }

    /* Card Header */
    .simulation-header {
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #cbd5e1;
    }

    .simulation-header h2 {
        font-size: 16px;
        margin: 0;
        color: #102635;
    }

    .simulation-header p {
        margin: 3px 0;
        font-size: 11px;
        color: #475569;
    }

    /* Section */
    .section {
        margin-top: 15px;
    }

    .section h3 {
        font-size: 14px;
        margin-bottom: 8px;
        color: #0f172a;
        border-left: 4px solid #00c3b3;
        padding-left: 8px;
    }

    /* Details Table */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
    }

    td {
        padding: 7px 9px;
        border: 1px solid #e2e8f0;
        font-size: 11.5px;
    }

    td.label {
        background: #f1f5f9;
        font-weight: bold;
        width: 40%;
        color: #0f172a;
    }

    /* Risk Badge */
    .risk-badge {
        display: inline-block;
        padding: 6px 12px;
        font-weight: bold;
        font-size: 11px;
        border-radius: 6px;
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
    .ai-explanation {
        margin-top: 12px;
        background: #ffffff;
        border-left: 4px solid #6366f1;
        border-radius: 4px;
        padding: 12px 14px;
        font-size: 12px;
        line-height: 1.6;
        color: #1e293b;
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

    <!-- Page Header -->
    <div class="page-header">
        <h1>Bastion Cybersecurity – All Simulation Reports</h1>
        <p>Comprehensive overview of all executed security simulations</p>
    </div>

    @forelse($simulations as $simulation)

        <div class="simulation-card">

            <!-- Card Header -->
            <div class="simulation-header">
                <h2>Simulation #{{ $simulation->id }} – {{ strtoupper($simulation->simulation_type) }}</h2>
                <p>Run by: {{ $simulation->user->name ?? 'Unknown User' }} (User ID: {{ $simulation->user_id }})</p>
                <p>Run at: {{ $simulation->created_at->format('d M Y, H:i') }}</p>

                <div class="risk-badge
                    {{ $simulation->risk_level === 'High' ? 'risk-high' :
                       ($simulation->risk_level === 'Medium' ? 'risk-medium' : 'risk-low') }}">
                    {{ strtoupper($simulation->risk_level) }} RISK
                </div>
            </div>

            <!-- Simulation Details -->
            <div class="section">
                <h3>Simulation Details</h3>

                <table>
                    @if($simulation->simulation_type === 'DDOS')
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
                        <tr>
                            <td class="label">Duration (seconds)</td>
                            <td>{{ $simulation->duration }}</td>
                        </tr>

                    @elseif($simulation->simulation_type === 'MITM')
                        <tr>
                            <td class="label">Intercepted Network Packets</td>
                            <td>{{ $simulation->intercepted_packets }}</td>
                        </tr>
                        <tr>
                            <td class="label">Credentials Exposed</td>
                            <td>{{ $simulation->exposed_credentials }}</td>
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

            <!-- AI Explanation -->
            @if(!empty($simulation->ai_explanation))
                <div class="section">
                    <h3>AI Security Explanation</h3>
                    <div class="ai-explanation">
                        {!! $simulation->ai_explanation !!}
                    </div>
                </div>
            @endif

        </div>

    @empty
        <p>No simulation reports available.</p>
    @endforelse

    <!-- Footer -->
    <div class="footer">
        Generated by Bastion Cybersecurity Platform • Academic Simulation Summary
    </div>

</body>
</html>

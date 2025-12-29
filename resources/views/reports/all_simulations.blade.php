<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Simulation Reports</title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #ffffff; color: #000000; }
    h1, h2 { color: #333333; margin-bottom: 10px; }
    .simulation-card { background: #f9f9f9; border: 1px solid #cccccc; border-radius: 6px; padding: 15px; margin-bottom: 25px; page-break-inside: avoid; }
    .simulation-header { margin-bottom: 10px; font-size: 14px; }
    .section { background: #ffffff; border: 1px solid #cccccc; border-radius: 6px; padding: 10px; margin-bottom: 10px; }
    .section h3 { color: #222222; margin-bottom: 8px; }
    .pre-output { background: #f0f0f0; border: 1px solid #cccccc; border-radius: 6px; padding: 10px; white-space: pre-wrap; overflow-x: auto; color: #000000; font-size: 12px; }
    .ai-explanation { background: #f0f0f0; border: 1px solid #cccccc; border-radius: 6px; padding: 10px; margin-top: 10px; font-size: 12px; }
</style>
</head>
<body>

<h1>All Simulation Reports</h1>

@forelse($simulations as $simulation)
<div class="simulation-card">
    <div class="simulation-header">
        <strong>Simulation #{{ $simulation->id }} - {{ strtoupper($simulation->simulation_type) }}</strong><br>
        Run by: {{ $simulation->user->name ?? 'Unknown User' }} (ID: {{ $simulation->user_id }})<br>
        Run at: {{ $simulation->created_at }}<br>
        Risk Level: {{ $simulation->risk_level }}
    </div>

    <div class="section">
        <h3>Simulation Details</h3>
        @if($simulation->simulation_type === 'DDOS')
            Target System: {{ $simulation->target }}<br>
            Attack Strength: {{ $simulation->ddos_mode }}<br>
            Requests / Second: {{ $simulation->request_rate }}<br>
            Total Requests: {{ $simulation->total_requests }}<br>
            Duration (s): {{ $simulation->duration }}<br>
        @elseif($simulation->simulation_type === 'MITM')
            Intercepted Packets: {{ $simulation->intercepted_packets }}<br>
            Exposed Credentials: {{ $simulation->exposed_credentials }}<br>
        @elseif($simulation->simulation_type === 'PHISHING')
            Emails Sent: {{ $simulation->emails_sent }}<br>
            Links Clicked: {{ $simulation->clicked_links }}<br>
            Details Entered: {{ $simulation->entered_details }}<br>
        @endif
    </div>

    @if(!empty($simulation->ai_explanation))
    <div class="ai-explanation">
        <h3>AI Explanation</h3>
        {!! strip_tags($simulation->ai_explanation, '<p><ul><li><strong><em>') !!}
    </div>
    @endif

</div>
@empty
<p>No simulation reports available.</p>
@endforelse

</body>
</html>

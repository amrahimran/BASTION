<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Scan Reports</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background: #ffffff;
            color: #1f2937;
            margin: 20px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 6px;
            color: #0b1d2a;
        }
        .header p {
            font-size: 12px;
            color: #64748b;
        }

        /* Scan card */
        .scan-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
            page-break-inside: avoid;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .scan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .scan-header h2 {
            font-size: 16px;
            color: #0f172a;
            margin: 0;
        }

        .scan-header .risk {
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 6px;
            color: #fff;
            font-size: 12px;
        }

        .risk-high { background-color: #dc2626; }
        .risk-medium { background-color: #ca8a04; }
        .risk-low { background-color: #16a34a; }

        /* Scan info */
        .scan-info {
            margin-bottom: 12px;
            font-size: 12px;
        }
        .scan-info strong { color: #0f172a; }

        /* Features */
        .features {
            margin: 8px 0;
        }
        .features span {
            display: inline-block;
            margin-right: 6px;
            margin-bottom: 4px;
            background: #e0f2fe;
            color: #0369a1;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }

        /* AI Explanation with colored risk highlights */
        .ai-explanation {
            margin-top: 10px;
            font-size: 12px;
            line-height: 1.5;
            color: #1e293b;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px;
            white-space: pre-wrap;
        }

        /* Risk sentence colors inside AI explanation */
        .ai-explanation .high-risk {
            background-color: #fca5a5; /* red bg */
            color: #b91c1c;
            padding: 2px 4px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 4px;
        }
        .ai-explanation .medium-risk {
            background-color: #fde68a; /* yellow bg */
            color: #b45309;
            padding: 2px 4px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 4px;
        }
        .ai-explanation .low-risk {
            background-color: #86efac; /* green bg */
            color: #166534;
            padding: 2px 4px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 4px;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 10px;
            color: #64748b;
            margin-top: 40px;
        }

    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Bastion Cybersecurity</h1>
        <p>Comprehensive Security Scan Report Summary</p>
    </div>

    <!-- Scans -->
    @forelse($scans as $scan)
    <div class="scan-card">
        <div class="scan-header">
            <h2>Scan #{{ $scan->id }} - {{ ucfirst($scan->scan_mode) }}</h2>

            @php
                $riskClass = $scan->risk_level === 'High' ? 'risk-high' : ($scan->risk_level === 'Medium' ? 'risk-medium' : 'risk-low');
            @endphp
            {{-- <div class="risk {{ $riskClass }}">
                {{ strtoupper($scan->risk_level) }} RISK
            </div> --}}
        </div>

        <div class="scan-info">
            <strong>User:</strong> {{ $scan->user->name ?? 'Unknown User' }}<br>
            <strong>Email:</strong> {{ $scan->user->email ?? '-' }}<br>
            <strong>Target:</strong> {{ $scan->target ?? 'Auto-detected' }}<br>
            <strong>Date:</strong> {{ $scan->created_at->format('d M Y, H:i') }}
        </div>

        @if(!empty($scan->features))
        <div class="features">
            @foreach($scan->features as $feature)
                <span>{{ ucwords(str_replace('_', ' ', $feature)) }}</span>
            @endforeach
        </div>
        @endif

        <div class="ai-explanation">
            {{-- Here we assume the AI summary already has the <div class="high-risk"> ... </div> formatting from your GeminiService --}}
            {!! $scan->ai_summary ?? 'AI analysis not available.' !!}
        </div>
    </div>
    @empty
    <p>No scan reports available.</p>
    @endforelse

    <div class="footer">
        Generated by Bastion Cybersecurity Platform • {{ now()->format('d M Y') }}
    </div>

</body>
</html>
